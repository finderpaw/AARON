<?php
require __DIR__ . '/admin_common.php';

@mysqli_query($conn, "CREATE TABLE IF NOT EXISTS notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    message TEXT NOT NULL,
    audience VARCHAR(20) NOT NULL DEFAULT 'all',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
)");

$aud = ['all' => 'Everyone', 'adopter' => 'Adopters', 'rescuer' => 'Rescuers'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_ok()) { flash('Your session expired. Please try again.', 'err'); redirect('notifications.php'); }
    $act = $_POST['action'] ?? '';
    if ($act === 'send') {
        $t = trim($_POST['title'] ?? ''); $m = trim($_POST['message'] ?? ''); $a = $_POST['audience'] ?? 'all';
        if ($t === '' || $m === '' || !isset($aud[$a])) flash('Add a title, a message and an audience.', 'err');
        elseif (q($conn, 'INSERT INTO notifications (title, message, audience) VALUES (?,?,?)', 'sss', [$t, $m, $a])) flash('Notification sent to ' . strtolower($aud[$a]) . '.');
        else flash('Could not save the notification.', 'err');
    } elseif ($act === 'delete') {
        q($conn, 'DELETE FROM notifications WHERE id = ?', 'i', [(int)($_POST['id'] ?? 0)]);
        flash('Notification deleted.');
    }
    redirect('notifications.php');
}

$f   = isset($aud[$_GET['a'] ?? '']) ? $_GET['a'] : '';
$res = q($conn, "SELECT * FROM notifications WHERE (? = '' OR audience = ?) ORDER BY id DESC", 'ss', [$f, $f]);

render_head('Notifications', 'notifications', 'Send announcements to adopters and rescuers');
?>
<div class="grid g2" style="align-items:start">
    <section class="card">
        <h2>New notification</h2>
        <form method="post" class="form-grid one" style="margin-top:16px">
            <?php echo csrf_field(); ?><input type="hidden" name="action" value="send">
            <label class="field">Title<input class="input" name="title" maxlength="150" required></label>
            <label class="field">Send to
                <select class="input" name="audience"><?php foreach ($aud as $k => $l) echo "<option value=\"$k\">$l</option>"; ?></select>
            </label>
            <label class="field">Message<textarea class="input" name="message" rows="5" required></textarea></label>
            <div><button class="btn" type="submit">Send notification</button></div>
        </form>
    </section>

    <section class="card">
        <div class="panel-head">
            <h2>Sent notifications</h2>
            <form method="get"><select class="input" name="a" onchange="this.form.submit()">
                <option value="">All audiences</option>
                <?php foreach ($aud as $k => $l) echo '<option value="' . $k . '"' . ($k === $f ? ' selected' : '') . '>' . $l . '</option>'; ?>
            </select></form>
        </div>
        <?php if ($res instanceof mysqli_result && mysqli_num_rows($res) > 0): while ($n = mysqli_fetch_assoc($res)): ?>
            <article class="note">
                <div class="note-top">
                    <b><?php echo e($n['title']); ?></b>
                    <span class="badge"><?php echo e($aud[$n['audience']] ?? $n['audience']); ?></span>
                </div>
                <p><?php echo nl2br(e($n['message'])); ?></p>
                <div class="note-top">
                    <small class="muted"><?php echo e(date('M j, Y g:i A', strtotime($n['created_at']))); ?></small>
                    <form method="post" onsubmit="return confirm('Delete this notification?')">
                        <?php echo csrf_field(); ?><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?php echo (int)$n['id']; ?>">
                        <button class="mini danger" type="submit">Delete</button>
                    </form>
                </div>
            </article>
        <?php endwhile; else: ?>
            <p class="empty">Nothing sent yet. Write your first notification on the left.</p>
        <?php endif; ?>
    </section>
</div>
<?php render_foot();