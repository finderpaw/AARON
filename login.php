<?php
session_start();
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $conn = new mysqli("localhost", "root", "", "pawfinder_db");

    if ($conn->connect_error) {
        die("Database connection failed: " . $conn->connect_error);
    }

    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";

    if (empty($username) || empty($password)) {
        $error = "Please complete all fields.";
    } else {
        // Query your 'registered' table
        $stmt = $conn->prepare("SELECT * FROM registered WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            // Verify password hash
            if (password_verify($password, $user["password"])) {
                
                // Store user details in session
                $_SESSION["username"] = $user["username"];
                $_SESSION["fullname"] = $user["fullname"];
                $_SESSION["role"]     = $user["role"];

                $stmt->close();
                $conn->close();

                // Redirect based on their role
                switch ($user["role"]) {
                    case 'admin':
                        header("Location: admin_dashboard.php");
                        break;
                    case 'Rescuer':
                        header("Location: rescuer_home.php");
                        break;
                    case 'Local':
                    case 'Visitor':
                    case 'adopter':
                        header("Location: adopter_home.php");
                        break;
                    default:
                        header("Location: homepage.php");
                        break;
                }
                exit();

            } else {
                $error = "Invalid username/email or password.";
            }
        } else {
            $error = "Invalid username/email or password.";
        }

        $stmt->close();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pawfinder - Account Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/index.css">
</head>

<body>

<div class="login-box">
    <div class="card">
        <div class="card-body p-4 p-sm-5">

            <div class="text-center mb-4">
                <div class="d-inline-block p-2 px-3 rounded-pill bg-primary bg-opacity-10 text-primary fw-bold mb-2 small">
                    🐾 Mabalacat Community Portal
                </div>

                <h2 class="fw-bold text-dark mb-1">Pawfinder</h2>

                <p class="text-muted small mb-0">
                    Pet Adoption, Emergency Rescue & Medical Services
                </p>
            </div>

            <div class="row text-center mb-4 g-2">
                <div class="col-3">
                    <div class="p-2 service-badge">
                        <span class="d-block fs-5">🐶</span>
                        <span class="text-dark d-block mt-1" style="font-size: 11px; font-weight: 600;">Adoption</span>
                    </div>
                </div>

                <div class="col-3">
                    <div class="p-2 service-badge">
                        <span class="d-block fs-5">🚨</span>
                        <span class="text-dark d-block mt-1" style="font-size: 11px; font-weight: 600;">Rescue</span>
                    </div>
                </div>

                <div class="col-3">
                    <div class="p-2 service-badge">
                        <span class="d-block fs-5">🔍</span>
                        <span class="text-dark d-block mt-1" style="font-size: 11px; font-weight: 600;">Missing</span>
                    </div>
                </div>

                <div class="col-3">
                    <div class="p-2 service-badge">
                        <span class="d-block fs-5">💉</span>
                        <span class="text-dark d-block mt-1" style="font-size: 11px; font-weight: 600;">Medical</span>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <h5 class="fw-bold text-dark mb-1">Sign In to Pawfinder</h5>
                <p class="text-muted small mb-0">
                    Welcome back! Please enter your credentials.
                </p>
            </div>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger small py-2" role="alert">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <form action="login_process.php" method="POST">

                <input type="hidden" name="pet_id"
                       value="<?php echo htmlspecialchars($pet_id); ?>">

                <div class="mb-3">
                    <label for="username" class="form-label fw-semibold small text-secondary">
                        Username
                    </label>

                    <input
                        type="text"
                        id="username"
                        name="username"
                        class="form-control"
                        placeholder="Enter your username"
                        required
                        autocomplete="username">
                </div>

                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <label for="password" class="form-label fw-semibold small text-secondary">
                            Password
                        </label>

                        <a href="forgot_password.php"
                           class="small text-decoration-none text-primary fw-semibold">
                            Forgot?
                        </a>
                    </div>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-control"
                        placeholder="••••••••"
                        required
                        autocomplete="current-password">
                </div>

                <div class="mb-3">
                    <div class="form-check">
                        <input
                            type="checkbox"
                            class="form-check-input"
                            id="remember_me"
                            name="remember_me">

                        <label class="form-check-label small text-muted" for="remember_me">
                            Remember me
                        </label>
                    </div>
                </div>

                <button class="btn btn-primary w-100 shadow-sm mb-3" type="submit">
                    Sign In
                </button>

            </form>

            <div class="text-center text-muted my-3 small position-relative">
                <span class="bg-white px-3 position-relative z-1 text-secondary" style="font-size: 12px;">
                    OR
                </span>

                <hr class="position-absolute top-50 start-0 end-0 m-0 z-0 text-muted opacity-25">
            </div>

            <div class="mb-2">
                <a href="homepage.php"
                   class="btn btn-outline-secondary w-100 py-2 fw-semibold small d-flex align-items-center justify-content-center gap-2 text-decoration-none rounded-3">
                    <span>👁️</span>
                    View as Guest (Public Landing Page)
                </a>
            </div>

            <div class="text-center mt-3">
                <a href="homepage.php" class="small text-decoration-none text-secondary">
                    ← Back to Homepage
                </a>
            </div>

            <div class="text-center mt-4">
                <span class="small text-muted">Don't have an account?</span>

                <a href="register.php"
                   class="small fw-bold text-decoration-none text-primary ms-1">
                    Create Account
                </a>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
