<?php
// TEMPORARY DEBUG FILE - delete it after you find the problem.
require_once 'config.php';

$result = null;
$input_user = trim($_POST['username'] ?? '');
$input_pass = $_POST['password'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = mysqli_prepare($conn, "SELECT id, username, email, password, role FROM registered WHERE username = ? OR email = ?");
    mysqli_stmt_bind_param($stmt, "ss", $input_user, $input_user);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $rows = mysqli_fetch_all($res, MYSQLI_ASSOC);

    $result = [];
    $result[] = "Rows found in `registered`: " . count($rows) . " (login needs exactly 1)";

    foreach ($rows as $row) {
        $stored = $row['password'];
        $result[] = "Username: " . $row['username'] . " | Email: " . $row['email'] . " | Role: " . $row['role'];
        $result[] = "Stored password starts with: " . htmlspecialchars(substr($stored, 0, 7)) . " (length " . strlen($stored) . ")";

        if (strpos($stored, '$2y$') === 0) {
            $result[] = "Looks like a proper password_hash() hash.";
            $result[] = "password_verify result: " . (password_verify($input_pass, $stored) ? "MATCH" : "NO MATCH (wrong password typed)");
        } elseif ($stored === $input_pass) {
            $result[] = "PROBLEM: password is saved as PLAIN TEXT. Register page must use password_hash().";
        } elseif (strlen($stored) === 32) {
            $result[] = "PROBLEM: password looks like an MD5 hash. Register page must use password_hash().";
        } else {
            $result[] = "PROBLEM: stored password is not a password_hash() hash and does not match what you typed.";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Login debug</title></head>
<body style="font-family: Arial; max-width: 600px; margin: 40px auto;">
    <h2>Login debug (temporary)</h2>
    <form method="POST">
        <p><input type="text" name="username" placeholder="username or email" style="width:100%;padding:8px;"></p>
        <p><input type="password" name="password" placeholder="password" style="width:100%;padding:8px;"></p>
        <button type="submit" style="padding:8px 16px;">Check</button>
    </form>

    <?php if ($result): ?>
        <h3>Result</h3>
        <ul>
            <?php foreach ($result as $line): ?>
                <li><?php echo htmlspecialchars($line); ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</body>
</html>