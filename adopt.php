<?php
session_start();

$is_logged_in = !empty($_SESSION['logged_in']);
$username     = $_SESSION['username'] ?? '';

// ---------- Filters (from the URL) ----------
$allowed_species = ['all', 'dog', 'cat', 'bird', 'other'];
$species = strtolower(trim($_GET['species'] ?? 'all'));
if (!in_array($species, $allowed_species, true)) {
    $species = 'all';
}
$search = trim($_GET['q'] ?? '');

$pets = [];
$db_error = "";

try {
    $conn = new mysqli("localhost", "root", "", "pawfinder_db");
    $conn->set_charset("utf8mb4");

    $sql = "SELECT id, pet_name, pet_species, breed, age, barangay, pet_photo
            FROM pets
            WHERE 1=1";
    $types  = "";
    $params = [];

    if ($species === 'dog' || $species === 'cat' || $species === 'bird') {
        $sql .= " AND LOWER(pet_species) = ?";
        $types .= "s";
        $params[] = $species;
    } elseif ($species === 'other') {
        $sql .= " AND LOWER(pet_species) NOT IN ('dog', 'cat', 'bird')";
    }

    if ($search !== '') {
        $sql .= " AND (pet_name LIKE ? OR breed LIKE ? OR barangay LIKE ?)";
        $types .= "sss";
        $like = "%" . $search . "%";
        array_push($params, $like, $like, $like);
    }

    $sql .= " ORDER BY created_at DESC";

    $stmt = $conn->prepare($sql);
    if ($types !== "") {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $pets = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    $conn->close();
} catch (Throwable $e) {
    error_log($e->getMessage());
    $db_error = "We couldn't load the pets right now. Please try again later.";
}

$tabs = [
    'all'   => 'All Pets',
    'dog'   => 'Dogs',
    'cat'   => 'Cats',
    'bird'  => 'Birds',
    'other' => 'Others',
];

// Build a link that keeps the search text when switching tabs
function tab_link(string $key, string $search): string {
    $query = ['species' => $key];
    if ($search !== '') {
        $query['q'] = $search;
    }
    return 'adopt.php?' . http_build_query($query);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adopt Pets - PAWFINDER Community Pet System</title>
    <link rel="stylesheet" href="css/homepage.css">
    <link rel="stylesheet" href="css/adopt.css">
</head>
<body>

    <div class="top-bar">
        <div class="role-container">
            <span class="role-label">Current Role:</span>
            <select id="roleSwitcher" class="role-select">
                <option value="admin">Admin</option>
                <option value="guest" selected>Guest / Public Visitor</option>
                <option value="rescuer">Local Rescuers</option>
                <option value="adopter">Adopter</option>
            </select>
        </div>
    </div>

    <header class="navbar">
        <div class="brand-section" onclick="location.href='homepage.php'">
            <img src="image/logo2.jpg" alt="PAWFINDER Logo" class="brand-logo-img">
            <div>
                <span class="brand-title">PAWFINDER</span>
                <span class="brand-subtitle">COMMUNITY PET SYSTEM</span>
            </div>
        </div>

        <ul class="nav-links">
            <li class="nav-item"><a href="homepage.php">Home</a></li>
            <li class="nav-item"><a href="adopt.php" class="active">Adopt Pets</a></li>
            <li class="nav-item"><a href="rescue.php">Emergency Rescues</a></li>
            <li class="nav-item"><a href="health_drives.php">Health Drives</a></li>
            <li class="nav-item"><a href="contact.php">Contact</a></li>
        </ul>

         <div class="auth-actions">
            <button class="btn-login" onclick="location.href='login.php'">Log In</button>
            <button class="btn-register" onclick="location.href='register.php'">Register</button>
        </div>
    </header>

    <main class="content-area">

        <div class="hero-banner">
            <h1>Adopt a Pet, Save a Life</h1>
            <p>Meet rescued dogs, cats, and birds from our community who are waiting for a loving and responsible home.</p>
        </div>

        <!-- SEARCH + FILTER -->
        <section class="partners-section">
            <div class="partners-header">
                <h2>Find Your New Best Friend</h2>
                <p>
                    Browse pets available for adoption in your area. Search by name, breed, or barangay,
                    or choose a category to narrow down the list.
                </p>
            </div>

            <form method="GET" action="adopt.php" class="adopt-search">
                <input type="hidden" name="species" value="<?= htmlspecialchars($species) ?>">
                <input type="text" name="q" value="<?= htmlspecialchars($search) ?>"
                       placeholder="Search by pet name, breed, or barangay...">
                <button type="submit" class="form-submit-btn">Search</button>
                <?php if ($search !== ''): ?>
                    <a href="<?= htmlspecialchars(tab_link($species, '')) ?>" class="adopt-clear">Clear</a>
                <?php endif; ?>
            </form>

            <div class="adopt-tabs">
                <?php foreach ($tabs as $key => $label): ?>
                    <a href="<?= htmlspecialchars(tab_link($key, $search)) ?>"
                       class="adopt-tab <?= $species === $key ? 'active' : '' ?>">
                        <?= htmlspecialchars($label) ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- PET LIST -->
        <section class="layer-slider-section" id="adopt-list">
            <h2 class="section-header-title">
                <?= htmlspecialchars($tabs[$species]) ?> for Adoption
                <span class="adopt-count">(<?= count($pets) ?>)</span>
            </h2>

            <?php if ($db_error !== ""): ?>
                <div class="adopt-empty"><?= htmlspecialchars($db_error) ?></div>

            <?php elseif (empty($pets)): ?>
                <div class="adopt-empty">
                    <strong>No pets found.</strong><br>
                    Try a different category or search, or check back soon for new arrivals.
                </div>

            <?php else: ?>
                <div class="adopt-grid">
                    <?php foreach ($pets as $pet): ?>
                        <?php
                            $pet_id = (int)$pet['id'];
                            $details_link = $is_logged_in
                                ? "pet_details.php?id=" . $pet_id
                                : "login.php?pet_id=" . $pet_id;
                        ?>
                        <div class="pet-card">
                            <div class="adopt-img-wrap">
                                <img src="<?= htmlspecialchars($pet['pet_photo']) ?>"
                                     alt="<?= htmlspecialchars($pet['pet_name']) ?>"
                                     class="pet-card-img"
                                     onerror="this.onerror=null;this.src='image/logo2.jpg';">
                                <span class="adopt-badge"><?= htmlspecialchars(ucfirst($pet['pet_species'])) ?></span>
                            </div>
                            <div class="pet-card-title"><?= htmlspecialchars($pet['pet_name']) ?></div>
                            <div class="pet-card-sub">
                                <?= htmlspecialchars($pet['breed']) ?> • <?= htmlspecialchars($pet['age']) ?>
                            </div>
                            <div class="pet-card-shelter">📍 <?= htmlspecialchars($pet['barangay']) ?></div>
                            <a href="<?= htmlspecialchars($details_link) ?>" class="pet-card-btn">Pet Details &gt;</a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>

        <!-- HOW ADOPTION WORKS -->
        <section class="partners-section" style="margin-top: 50px;">
            <div class="partners-header">
                <h2>How Adoption Works</h2>
                <p>Adopting through Pawfinder is simple. Follow these steps to bring your new pet home.</p>
            </div>

            <div class="shelter-cards-grid">
                <div class="shelter-card">
                    <div>
                        <div class="step-number">1</div>
                        <div class="shelter-name">Browse &amp; Choose</div>
                        <div class="shelter-info">
                            Look through the available pets and open the details of the one you like.
                            Log in or register so you can view the full profile.
                        </div>
                    </div>
                </div>

                <div class="shelter-card">
                    <div>
                        <div class="step-number">2</div>
                        <div class="shelter-name">Meet &amp; Review</div>
                        <div class="shelter-info">
                            Read the pet's health information and background, then contact the owner or
                            rescuer to arrange a meet-up.
                        </div>
                    </div>
                </div>

                <div class="shelter-card">
                    <div>
                        <div class="step-number">3</div>
                        <div class="shelter-name">Adopt &amp; Care</div>
                        <div class="shelter-info">
                            Once everyone agrees, welcome your new family member home and give them
                            the love and care they deserve.
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- REHOME CTA -->
        <section class="post-pet-section adopt-cta">
            <div>
                <h2 class="section-header-title" style="margin-bottom: 8px;">Need to Rehome a Pet?</h2>
                <p style="color: #64748b; font-size: 14px;">
                    If you can no longer take care of your pet, post it here so it can find a new loving home.
                </p>
            </div>
            <a href="homepage.php#post-pet-form" class="form-submit-btn adopt-cta-btn">Post a Pet for Adoption</a>
        </section>

    </main>

</body>
</html>