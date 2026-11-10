<?php
require __DIR__ . '/admin_common.php';

$roles = ['admin', 'rescuer', 'adopter'];
$me    = $_SESSION['username'] ?? '';

/* ---------- actions ---------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_ok()) { flash('Your session expired. Please try again.', 'err'); redirect('users.php'); }
    $act = $_POST['action'] ?? '';
    $id  = (int)($_POST['id'] ?? 0);
    $target = $id ? one($conn, 'SELECT username FROM registered WHERE id = ?', 'i', [$id]) : null;

    if ($act === 'add') {
        $name = trim($_POST['fullname'] ?? ''); $email = trim($_POST['email'] ?? '');
        $user = trim($_POST['username'] ?? ''); $pass  = $_POST['password'] ?? '';
        $role = $_POST['role'] ?? ''; $contact = trim($_POST['contact'] ?? ''); $address = trim($_POST['address'] ?? '');
        if ($name === '' || $user === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            flash('Enter a full name, username and valid email.', 'err');
        } elseif (strlen($pass) < 8) {
            flash('Password must be at least 8 characters.', 'err');
        } elseif (!in_array($role, $roles, true)) {
            flash('Choose a valid role.', 'err');
        } else {
            $ok = q($conn, 'INSERT INTO registered (fullname, email, address, contact, role, username, password) VALUES (?,?,?,?,?,?,?)',
                    'sssssss', [$name, $email, $address, $contact, $role, $user, password_hash($pass, PASSWORD_DEFAULT)]);
            $ok ? flash("Account for $user created.") : flash('That username or email is already registered.', 'err');
        }
    } elseif ($act === 'role' && $target) {
        $role = $_POST['role'] ?? '';
        if ($target['username'] === $me) flash('You cannot change your own role.', 'err');
        elseif (in_array($role, $roles, true) && q($conn, 'UPDATE registered SET role = ? WHERE id = ?', 'si', [$role, $id])) flash($target['username'] . ' is now ' . $role . '.');
        else flash('Could not update the role.', 'err');
    } elseif ($act === 'delete' && $target) {
        if ($target['username'] === $me) flash('You cannot delete your own account.', 'err');
        else { q($conn, 'DELETE FROM registered WHERE id = ?', 'i', [$id]); flash('Account ' . $target['username'] . ' deleted.'); }
    }
    redirect('users.php');
}

/* ---------- data ---------- */
$search = trim($_GET['q'] ?? '');
$role_f = in_array($_GET['role'] ?? '', $roles, true) ? $_GET['role'] : '';
$like   = '%' . $search . '%';
$res    = q($conn, "SELECT * FROM registered WHERE (fullname LIKE ? OR email LIKE ? OR username LIKE ?) AND (? = '' OR role = ?) ORDER BY id DESC",
            'sssss', [$like, $like, $like, $role_f, $role_f]);
$total  = count_of($conn, 'SELECT COUNT(*) FROM registered');

render_head('Users', 'users', 'Manage adopter, rescuer and admin accounts');
?>
<div class="grid g4">
    <div class="card stat"><h2>All accounts</h2><div class="num"><strong><?php echo $total; ?></strong></div></div>
    <?php foreach ($roles as $r): ?>
    <div class="card stat"><h2><?php echo ucfirst($r); ?>s</h2><div class="num"><strong><?php echo count_of($conn, 'SELECT COUNT(*) FROM registered WHERE role = ?', 's', [$r]); ?></strong></div></div>
    <?php endforeach; ?>
</div>

<details class="panel">
    <summary class="btn">+ Add user</summary>
    <form method="post" class="form-grid" style="margin-top:18px">
        <?php echo csrf_field(); ?><input type="hidden" name="action" value="add">
        <label class="field">Full name<input class="input" name="fullname" required></label>
        <label class="field">Email<input class="input" type="email" name="email" required></label>
        <label class="field">Username<input class="input" name="username" required></label>
        <label class="field">Password (8+ characters)<input class="input" type="password" name="password" minlength="8" required></label>
        <label class="field">Contact number<input class="input" name="contact"></label>
        <label class="field">Role<select class="input" name="role"><?php foreach ($roles as $r) echo "<option>$r</option>"; ?></select></label>
        <label class="field wide">Address<input class="input" name="address"></label>
        <div class="wide"><button class="btn" type="submit">Create account</button></div>
    </form>
</details>

<section class="panel">
    <form class="toolbar" method="get">
        <input class="input search" type="search" name="q" value="<?php echo e($search); ?>" placeholder="Search name, email or username...">
        <select class="input" name="role" onchange="this.form.submit()">
            <option value="">All roles</option>
            <?php foreach ($roles as $r) echo '<option value="' . $r . '"' . ($r === $role_f ? ' selected' : '') . '>' . ucfirst($r) . '</option>'; ?>
        </select>
        <button class="btn" type="submit">Search</button>
    </form>
    <div class="table-wrap">
        <table>
            <thead><tr><th>Name</th><th>Username</th><th>Contact</th><th>Role</th><th>Joined</th><th class="tcenter">Actions</th></tr></thead>
            <tbody>
            <?php if ($res instanceof mysqli_result && mysqli_num_rows($res) > 0): while ($u = mysqli_fetch_assoc($res)): $self = $u['username'] === $me; ?>
                <tr>
                    <td><b><?php echo e($u['fullname']); ?></b><br><small><?php echo e($u['email']); ?></small></td>
                    <td><?php echo e($u['username']); ?><?php if ($self) echo ' <span class="badge">You</span>'; ?></td>
                    <td><?php echo e($u['contact']); ?><br><small><?php echo e($u['address']); ?></small></td>
                    <td>
                        <form method="post"><?php echo csrf_field(); ?>
                            <input type="hidden" name="action" value="role"><input type="hidden" name="id" value="<?php echo (int)$u['id']; ?>">
                            <select class="input sel" name="role" onchange="this.form.submit()" <?php echo $self ? 'disabled' : ''; ?>>
                                <?php foreach ($roles as $r) echo '<option' . ($r === $u['role'] ? ' selected' : '') . '>' . $r . '</option>'; ?>
                            </select>
                        </form>
                    </td>
                    <td><?php echo e(substr($u['created_at'], 0, 10)); ?></td>
                    <td class="tcenter">
                        <?php if (!$self): ?>
                        <form method="post" onsubmit="return confirm('Delete this account?')">
                            <?php echo csrf_field(); ?><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?php echo (int)$u['id']; ?>">
                            <button class="mini danger" type="submit">Delete</button>
                        </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; else: ?>
                <tr><td colspan="6" class="empty" style="text-align:center">No accounts match. Add one above.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>
<?php render_foot();