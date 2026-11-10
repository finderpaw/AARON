<?php
require __DIR__ . '/admin_common.php';

$species = ['dog' => 'Dogs', 'cat' => 'Cats', 'bird' => 'Birds'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_ok()) flash('Your session expired. Please try again.', 'err');
    elseif (($_POST['action'] ?? '') === 'delete') {
        q($conn, 'DELETE FROM pets WHERE id = ?', 'i', [(int)($_POST['id'] ?? 0)]);
        flash('Pet listing deleted.');
    }
    redirect('pets.php');
}

$search = trim($_GET['q'] ?? '');
$sp     = isset($species[$_GET['sp'] ?? '']) ? $_GET['sp'] : '';
$like   = '%' . $search . '%';
$res    = q($conn, "SELECT * FROM pets WHERE (pet_name LIKE ? OR breed LIKE ? OR barangay LIKE ?) AND (? = '' OR LOWER(pet_species) LIKE CONCAT('%', ?, '%')) ORDER BY id DESC",
            'sssss', [$like, $like, $like, $sp, $sp]);

render_head('Pets', 'pets', 'Every pet listed for adoption');
?>
<div class="grid g4">
    <div class="card stat"><h2>All pets</h2><div class="num"><strong><?php echo count_of($conn, 'SELECT COUNT(*) FROM pets'); ?></strong></div></div>
    <?php foreach ($species as $k => $label): ?>
    <div class="card stat"><h2><?php echo $label; ?></h2><div class="num"><strong><?php echo count_of($conn, "SELECT COUNT(*) FROM pets WHERE LOWER(pet_species) LIKE ?", 's', ["%$k%"]); ?></strong></div></div>
    <?php endforeach; ?>
</div>

<section class="panel">
    <form class="toolbar" method="get">
        <input class="input search" type="search" name="q" value="<?php echo e($search); ?>" placeholder="Search name, breed or barangay...">
        <select class="input" name="sp" onchange="this.form.submit()">
            <option value="">All species</option>
            <?php foreach ($species as $k => $label) echo '<option value="' . $k . '"' . ($k === $sp ? ' selected' : '') . '>' . $label . '</option>'; ?>
        </select>
        <button class="btn" type="submit">Search</button>
        <a class="btn" href="<?php echo e($pet_pages); ?>add.php" style="margin-left:auto">+ Add pet</a>
    </form>
    <div class="table-wrap">
        <table>
            <thead><tr><th>Pet</th><th>Species</th><th>Breed &amp; age</th><th>Barangay</th><th class="tcenter">Actions</th></tr></thead>
            <tbody>
            <?php if ($res instanceof mysqli_result && mysqli_num_rows($res) > 0): while ($p = mysqli_fetch_assoc($res)): ?>
                <tr>
                    <td>
                        <div class="person">
                            <img class="thumb" src="<?php echo e(asset($p['pet_photo'], $root)); ?>" alt="" onerror="this.style.visibility='hidden'">
                            <b><?php echo e($p['pet_name']); ?></b>
                        </div>
                    </td>
                    <td><span class="badge"><?php echo e($p['pet_species']); ?></span></td>
                    <td><?php echo e($p['breed']); ?><br><small><?php echo e($p['age']); ?></small></td>
                    <td><?php echo e($p['barangay']); ?></td>
                    <td class="tcenter">
                        <a class="mini" href="<?php echo e($pet_pages); ?>pet_details.php?id=<?php echo (int)$p['id']; ?>">View</a>
                        <a class="mini" href="<?php echo e($pet_pages); ?>edit.php?id=<?php echo (int)$p['id']; ?>">Edit</a>
                        <form method="post" style="display:inline" onsubmit="return confirm('Delete this pet listing?')">
                            <?php echo csrf_field(); ?><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?php echo (int)$p['id']; ?>">
                            <button class="mini danger" type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; else: ?>
                <tr><td colspan="5" class="empty" style="text-align:center">No pets found. Use “Add pet” to list one.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>
<?php render_foot();