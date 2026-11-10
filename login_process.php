<?php
session_start();
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: login.php");
    exit();
}

$username_email = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';
$pet_id = (int)($_POST['pet_id'] ?? 0);

if ($username_email === '' || $password === '') {
    $_SESSION['error'] = "Please enter your username and password.";
    header("Location: login.php");
    exit();
}

$sql = "SELECT id, username, password, role, fullname
        FROM registered
        WHERE username = ? OR email = ?";

$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    error_log(mysqli_error($conn));
    $_SESSION['error'] = "Database query failed.";
    header("Location: login.php");
    exit();
}

mysqli_stmt_bind_param($stmt, "ss", $username_email, $username_email);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);

if (mysqli_stmt_num_rows($stmt) === 1) {
    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password, $role, $fullname);
    mysqli_stmt_fetch($stmt);

    if (!empty($hashed_password) && password_verify($password, $hashed_password)) {
        mysqli_stmt_close($stmt);

        session_regenerate_id(true);

        $_SESSION['user_id'] = $id;
        $_SESSION['username'] = $username;
        $_SESSION['full_name'] = $fullname;
        $_SESSION['role'] = trim($role);
        $_SESSION['logged_in'] = true;

        $normalizedRole = strtolower(trim($role));

        if ($normalizedRole === 'admin') {
            header("Location: /pawfinder/admin/admin.php");
            exit();
        }

        if ($pet_id > 0) {
            header("Location: /pawfinder/pet_details.php?id=" . $pet_id);
            exit();
        }

        switch ($normalizedRole) {
            case 'rescuer':
            case 'pet rescuer / volunteer':
                header("Location: rescue.php");
                exit();

            case 'local':
            case 'visitor':
            case 'adopter':
                header("Location: adopter_home.php");
                exit();

            default:
                session_unset();
                session_destroy();
                session_start();
                $_SESSION['error'] = "Invalid account role.";
                header("Location: login.php");
                exit();
        }
    }
}

mysqli_stmt_close($stmt);

$_SESSION['error'] = "Invalid username/email or password.";

$redirect = "login.php";

if ($pet_id > 0) {
    $redirect .= "?pet_id=" . $pet_id;
}

header("Location: " . $redirect);
exit();
?>