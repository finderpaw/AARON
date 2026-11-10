
<?php
session_start();

$error = $_SESSION['error'] ?? '';
unset($_SESSION['error']);

$pet_id = $_GET['pet_id'] ?? '';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pawfinder - Community Pet Adoption & Rescue System</title>
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
                <p class="text-muted small mb-0">Pet Adoption, Emergency Rescue & Medical Services</p>
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

           <form action="login_process.php" method="POST">

    <input type="hidden" name="pet_id"
           value="<?php echo htmlspecialchars($pet_id); ?>">

    <div class="mb-3">
        <label class="form-label fw-semibold small text-secondary">
            Username or Email
        </label>

        <input type="text"
               name="username"
               class="form-control"
               placeholder="Enter your username or email"
               required>
    </div>

    <div class="mb-3">
        <div class="d-flex justify-content-between align-items-center">
            <label class="form-label fw-semibold small text-secondary">
                Password
            </label>

            <a href="forgot_password.php"
               class="small text-decoration-none text-primary fw-semibold">
                Forgot?
            </a>
        </div>

        <input type="password"
               name="password"
               class="form-control"
               placeholder="••••••••"
               required>
    </div>

    <button class="btn btn-primary w-100 shadow-sm mb-3" type="submit">
        Sign In
    </button>

</form>

            <div class="text-center text-muted my-3 small position-relative">
                <span class="bg-white px-3 position-relative z-1 text-secondary" style="font-size: 12px;">OR</span>
                <hr class="position-absolute top-50 start-0 end-0 m-0 z-0 text-muted opacity-25">
            </div>

          
            <div class="mb-2">
                <a href="homepage.php" class="btn btn-outline-secondary w-100 py-2.5 fw-semibold small d-flex align-items-center justify-content-center gap-2 text-decoration-none rounded-3">
                    <span>👁️</span> View as Guest (Public Landing Page)
                </a>
            </div>

             <div class="back-to-login">
                <a href="homepage.php">← Back to Homepage</a>
            </div>

          
            <div class="text-center mt-4">
                <span class="small text-muted">Don't have an account?</span>
                <a href="register.php" class="small fw-bold text-decoration-none text-primary ms-1">Create Account</a>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>