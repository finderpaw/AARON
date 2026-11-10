<?php
require __DIR__ . '/admin_common.php';

@mysqli_query($conn, "CREATE TABLE IF NOT EXISTS settings (
    setting_key VARCHAR(50) PRIMARY KEY,
    setting_value TEXT NOT NULL
)");

$defaults = ['site_name' => 'PAWFINDER', 'contact_email' => '', 'contact_phone' => '', 'address' => '',
             'adoptions_open' => '1', 'rescue_alerts' => '1'];

function load_settings($conn, $defaults) {
    $out = $defaults;
    $r = q($conn, 'SELECT setting_key, setting_value FROM settings');
    if ($r instanceof mysqli_result) while ($row = mysqli_fetch_assoc($r)) if (isset($out[$row['setting_key']])) $out[$row['setting_key']] = $row['setting_value'];
    return $out;
}

/* the admin's own account lives in registered (or the older users table) */
function find_account($conn, $username) {
    foreach (['registered', 'users'] as $t) {
        $row = one($conn, "SELECT id, password FROM `$t` WHERE username = ?", 's', [$username]);
        if ($row) return [$t, $row];
    }
    return [null, null];
}

$me = $_SESSION['username'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_ok()) { flash('Your session expired. Please try again.', 'err'); redirect('settings.php'); }
    $act = $_POST['action'] ?? '';

    if ($act === 'site') {
        $email = trim($_POST['contact_email'] ?? '');
        if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) { flash('Enter a valid contact email.', 'err'); redirect('settings.php'); }
        $vals = ['site_name' => trim($_POST['site_name'] ?? '') ?: 'PAWFINDER', 'contact_email' => $email,
                 'contact_phone' => trim($_POST['contact_phone'] ?? ''), 'address' => trim($_POST['address'] ?? ''),
                 'adoptions_open' => isset($_POST['adoptions_open']) ? '1' : '0', 'rescue_alerts' => isset($_POST['rescue_alerts']) ? '1' : '0'];
        foreach ($vals as $k => $v) q($conn, 'INSERT INTO settings (setting_key, setting_value) VALUES (?,?) ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)', 'ss', [$k, $v]);
        flash('Settings saved.');
    } elseif ($act === 'password') {
        [$table, $acc] = find_account($conn, $me);
        $cur = $_POST['current'] ?? ''; $new = $_POST['new'] ?? ''; $conf = $_POST['confirm'] ?? '';
        if (!$acc) flash('Could not find your account. Log in again and retry.', 'err');
        elseif (!password_verify($cur, $acc['password']) && !hash_equals((string)$acc['password'], $cur)) flash('Current password is incorrect.', 'err');
        elseif (strlen($new) < 8) flash('New password must be at least 8 characters.', 'err');
        elseif ($new !== $conf) flash('New passwords do not match.', 'err');
        else {
            q($conn, "UPDATE `$table` SET password = ? WHERE id = ?", 'si', [password_hash($new, PASSWORD_DEFAULT), (int)$acc['id']]);
            flash('Password changed.');
        }
    }
    redirect('settings.php');
}

$s = load_settings($conn, $defaults);
render_head('Settings', 'settings', 'System details and your admin account');
?>
<div class="grid g2" style="align-items:start">
    <section class="card">
        <h2>System</h2>
        <form method="post" class="form-grid one" style="margin-top:16px">
            <?php echo csrf_field(); ?><input type="hidden" name="action" value="site">
            <label class="field">System name<input class="input" name="site_name" value="<?php echo e($s['site_name']); ?>"></label>
            <label class="field">Contact email<input class="input" type="email" name="contact_email" value="<?php echo e($s['contact_email']); ?>"></label>
            <label class="field">Contact number<input class="input" name="contact_phone" value="<?php echo e($s['contact_phone']); ?>"></label>
            <label class="field">Office address<input class="input" name="address" value="<?php echo e($s['address']); ?>"></label>
            <label class="check"><input type="checkbox" name="adoptions_open" <?php echo $s['adoptions_open'] === '1' ? 'checked' : ''; ?>> Accept new adoption requests</label>
            <label class="check"><input type="checkbox" name="rescue_alerts" <?php echo $s['rescue_alerts'] === '1' ? 'checked' : ''; ?>> Alert staff to new rescue reports</label>
            <div><button class="btn" type="submit">Save settings</button></div>
        </form>
    </section>

    <section class="card">
        <h2>Change password</h2>
        <p class="muted" style="margin-top:8px">Signed in as <b><?php echo e($me ?: 'admin'); ?></b></p>
        <form method="post" class="form-grid one" style="margin-top:16px">
            <?php echo csrf_field(); ?><input type="hidden" name="action" value="password">
            <label class="field">Current password<input class="input" type="password" name="current" autocomplete="current-password" required></label>
            <label class="field">New password (8+ characters)<input class="input" type="password" name="new" minlength="8" autocomplete="new-password" required></label>
            <label class="field">Confirm new password<input class="input" type="password" name="confirm" minlength="8" autocomplete="new-password" required></label>
            <div><button class="btn" type="submit">Update password</button></div>
        </form>
    </section>
</div>
<?php render_foot();