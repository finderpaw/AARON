<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'adopter') {
    header('Location: /pawfinder/login.php');
    exit;
}

$fullName = htmlspecialchars($_SESSION['full_name'] ?? 'Adopter');
$email    = htmlspecialchars($_SESSION['email'] ?? '');
$avatar   = htmlspecialchars($_SESSION['avatar'] ?? 'image/default-avatar.png');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PAWFINDER - Adopter Dashboard</title>
    <link rel="stylesheet" href="css/homepage.css">
    <link rel="stylesheet" href="css/adopter.css">
</head>
<body class="adopter-body">

    <div class="adopter-topbar">
        <div class="profile-menu" id="profileMenu">
            <button type="button" class="profile-trigger" id="profileTrigger"
                    aria-haspopup="true" aria-expanded="false" aria-controls="profileDropdown">
                <img src="<?= $avatar ?>" alt="" class="profile-avatar">
                <span class="profile-text">
                    <span class="profile-name"><?= $fullName ?></span>
                    <span class="profile-role">Adopter</span>
                </span>
                <svg class="profile-arrow" viewBox="0 0 24 24" width="18" height="18" aria-hidden="true">
                    <path d="M9 6l6 6-6 6" fill="none" stroke="currentColor" stroke-width="2.5"
                          stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>

            <div class="profile-dropdown" id="profileDropdown" role="menu" hidden>
                <div class="dropdown-header">
                    <img src="<?= $avatar ?>" alt="" class="dropdown-avatar">
                    <div>
                        <div class="dropdown-name"><?= $fullName ?></div>
                        <div class="dropdown-email"><?= $email ?></div>
                    </div>
                </div>

                <a href="adopter_profile.php" class="dropdown-item" role="menuitem">My Profile</a>
                <a href="my_applications.php" class="dropdown-item" role="menuitem">My Adoption Applications</a>
                <a href="saved_pets.php" class="dropdown-item" role="menuitem">Saved Pets</a>
                <a href="adoption_history.php" class="dropdown-item" role="menuitem">Adoption History</a>
                <a href="messages.php" class="dropdown-item" role="menuitem">Messages</a>
                <a href="settings.php" class="dropdown-item" role="menuitem">Account Settings</a>

                <div class="dropdown-divider"></div>
                <a href="logout.php" class="dropdown-item dropdown-logout" role="menuitem">Log Out</a>
            </div>
        </div>
    </div>

    <header class="navbar">
        <div class="brand-section">
            <img src="image/logo2.jpg" alt="PAWFINDER Logo" class="brand-logo-img">
            <div>
                <span class="brand-title">PAWFINDER</span>
                <span class="brand-subtitle">COMMUNITY PET SYSTEM</span>
            </div>
        </div>

        <ul class="nav-links">
            <li class="nav-item"><a href="adopter_home.php" class="active">Home</a></li>
            <li class="nav-item"><a href="adopt.php">Adopt Pets</a></li>
            <li class="nav-item"><a href="my_applications.php">My Applications</a></li>
            <li class="nav-item"><a href="health_drives.php">Health Drives</a></li>
            <li class="nav-item"><a href="contact.php">Contact</a></li>
        </ul>
    </header>

    <main class="content-area">
        <div class="hero-banner adopter-hero">
            <h1>Welcome back, <?= $fullName ?>!</h1>
            <p>Browse pets waiting for a home and track your adoption applications.</p>
        </div>

        <section class="adopter-stats">
            <div class="stat-card">
                <span class="stat-number">0</span>
                <span class="stat-label">Pending applications</span>
            </div>
            <div class="stat-card">
                <span class="stat-number">0</span>
                <span class="stat-label">Saved pets</span>
            </div>
            <div class="stat-card">
                <span class="stat-number">0</span>
                <span class="stat-label">Approved adoptions</span>
            </div>
        </section>

        <section class="layer-slider-section" id="dogs-section">
            <h2 class="section-header-title">Dogs for Adoption</h2>
            <div class="slider-wrapper">
                <div class="slider-container">
                    <div class="pet-card">
                        <img src="image/dog1.jpg" alt="Dog" class="pet-card-img">
                        <div class="pet-card-title">Pet Name</div>
                        <div class="pet-card-sub">Gender, Breed</div>
                        <div class="pet-card-shelter">Shelter Name</div>
                        <button class="pet-card-btn">Pet Details &gt;</button>
                        <button class="pet-card-btn adopt-btn">Apply to Adopt</button>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <script>
        const menu    = document.getElementById('profileMenu');
        const trigger = document.getElementById('profileTrigger');
        const dropdown = document.getElementById('profileDropdown');

        function setMenu(open) {
            menu.classList.toggle('open', open);
            trigger.setAttribute('aria-expanded', open);
            dropdown.hidden = !open;
        }

        trigger.addEventListener('click', (e) => {
            e.stopPropagation();
            setMenu(!menu.classList.contains('open'));
        });

        document.addEventListener('click', (e) => {
            if (!menu.contains(e.target)) setMenu(false);
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') { setMenu(false); trigger.focus(); }
        });
    </script>
</body>
</html>