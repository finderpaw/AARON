<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $conn = new mysqli("localhost", "root", "", "pawfinder_db");

    if ($conn->connect_error) {
        die("Database connection failed: " . $conn->connect_error);
    }

    $fullname = trim($_POST["fullname"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $address = trim($_POST["address"] ?? "");
    $contact = trim($_POST["contact"] ?? "");
    $role = $_POST["role"] ?? "";
    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";

    if (
        empty($fullname) ||
        empty($email) ||
        empty($address) ||
        empty($contact) ||
        empty($role) ||
        empty($username) ||
        empty($password)
    ) {
        die("Please complete all fields.");
    }

    $allowedRoles = ["Rescuer", "Visitor", "Local"];

    if (!in_array($role, $allowedRoles, true)) {
        die("Invalid account role.");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email address.");
    }

    if (!preg_match("/^09\d{9}$/", $contact)) {
        die("Please enter a valid 11-digit contact number.");
    }

    if (strlen($password) < 8) {
        die("Password must be at least 8 characters.");
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO registered
            (fullname, email, address, contact, role, username, password)
            VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Query preparation failed: " . $conn->error);
    }

    $stmt->bind_param(
        "sssssss",
        $fullname,
        $email,
        $address,
        $contact,
        $role,
        $username,
        $hashedPassword
    );

    if ($stmt->execute()) {

        $_SESSION["username"] = $username;
        $_SESSION["fullname"] = $fullname;
        $_SESSION["role"] = $role;
        $_SESSION["logged_in"] = true;

        $stmt->close();
        $conn->close();

        header("Location: homepage.php");
        exit();

    } else {

        if ($stmt->errno == 1062) {
            die("Username or email already exists. Please use another one.");
        }

        die("Registration failed: " . $stmt->error);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Pawfinder - Account Registration</title>
    <link rel="stylesheet" href="css/register.css">
</head>

<body>

<div class="register-card">

    <div class="sidebar-left">

        <div>
            <div class="brand-logo">🐾 PAWFINDER</div>

            <ul class="step-list">
                <li class="step-item active">○ Account Setup</li>
                <li class="step-item">○ Resident Details</li>
                <li class="step-item">○ Pet Preferences</li>
                <li class="step-item">○ Confirm</li>
            </ul>
        </div>

        <div>
            <div class="sidebar-img-container">
                <img src="image/animal.jpg" alt="Animal">
            </div>

            <div class="back-to-login">
                <a href="homepage.php">← Back to Homepage</a>
            </div>
        </div>

    </div>

    <div class="form-right">

        <h2>Welcome to Pawfinder!</h2>
        <p>Please provide your basic information to get started.</p>

        <form action="register.php" method="POST">

            <div class="row">

                <div class="col">
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" name="fullname"
                               placeholder="Juan Dela Cruz" required>
                    </div>
                </div>

                <div class="col">
                    <div class="form-group">
                        <label>Email Address</label>
                        <input type="email" name="email"
                               placeholder="juan@gmail.com" required>
                    </div>
                </div>

            </div>

            <div class="row">

                <div class="col">
                    <div class="form-group">
                        <label>Address (Mabalacat City)</label>
                        <input type="text" name="address"
                               placeholder="e.g., Dau, Mabalacat City" required>
                    </div>
                </div>

                <div class="col">
                    <div class="form-group">
                        <label>Contact Number</label>
                        <input type="tel" name="contact"
                               placeholder="09123456789" required>
                    </div>
                </div>

            </div>

            <div class="form-group">

                <label>Register As</label>

                <div class="radio-group">

                    <label>
                        <input type="radio" name="role"
                               value="Local" required>
                        Local Resident
                    </label>

                    <label>
                        <input type="radio" name="role"
                               value="Visitor">
                        Visitor
                    </label>

                    <label>
                        <input type="radio" name="role"
                               value="Rescuer">
                        Pet Rescuer / Volunteer
                    </label>

                </div>

            </div>

            <div class="row">

                <div class="col">
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" name="username"
                               placeholder="Username" required>
                    </div>
                </div>

                <div class="col">
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password"
                               placeholder="********" required>
                    </div>
                </div>

            </div>

            <button type="submit" class="btn-submit">
                Create Account
            </button>

        </form>

    </div>

</div>

<script>
document.querySelector("form").addEventListener("submit", function(event) {

    const password = document.querySelector('[name="password"]').value;

    if (password.length < 8) {

        event.preventDefault();

        alert("Password must be at least 8 characters long! Please try again.");

        document.querySelector('[name="password"]').focus();
    }

});
</script>

</body>
</html>