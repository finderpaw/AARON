<?php
session_start();

if (empty($_SESSION['logged_in']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: .php");
    exit();
}

/* config.php: look in this folder first, then one level up (pawfinder/) */
$config_file = file_exists(__DIR__ . '/config.php') ? __DIR__ . '/config.php' : __DIR__ . '/../config.php';
require_once $config_file;

/* ---------- helpers ---------- */
/* Folder that holds image/ and uploads, relative to this admin/ folder.
   '../' = pawfinder root. Change to '' if your image folder is inside admin/. */
$root = '../';

function asset($path, $root) {
    if ($path === '' || preg_match('#^(https?:)?//#', $path) || $path[0] === '/') return $path;
    return $root . ltrim($path, './');
}

function count_rows($conn, $sql) {
    $r = @mysqli_query($conn, $sql);
    if (!$r) return 0;
    $row = mysqli_fetch_row($r);
    return (int)($row[0] ?? 0);
}

function pet_section($conn, $title, $species, $label, $root) {
    $sp = mysqli_real_escape_string($conn, $species);
    $result = mysqli_query($conn, "SELECT * FROM pets WHERE LOWER(pet_species) LIKE '%$sp%' ORDER BY id DESC");
    ?>
    <section class="panel" data-section>
        <div class="panel-head">
            <h2><?php echo $title; ?></h2>
            <div class="panel-tools">
                <input type="search" class="search" placeholder="Search <?php echo $label; ?>..." data-filter>
                <a href="add.php" class="btn">+ Add</a>
            </div>
        </div>
        <div class="slider-wrap">
            <button class="arrow" data-dir="-1" aria-label="Scroll left">‹</button>
            <div class="slider">
                <?php if ($result && mysqli_num_rows($result) > 0):
                    while ($row = mysqli_fetch_assoc($result)): ?>
                    <article class="pet" data-item>
                        <img src="<?php echo htmlspecialchars(asset($row['pet_photo'], $root)); ?>" alt="<?php echo htmlspecialchars($row['pet_name']); ?>">
                        <h3><?php echo htmlspecialchars($row['pet_name']); ?></h3>
                        <p class="muted"><?php echo htmlspecialchars($row['breed']); ?> • <?php echo htmlspecialchars($row['age']); ?></p>
                        <p class="tag">Barangay: <?php echo htmlspecialchars($row['barangay']); ?></p>
                        <a class="btn btn-soft" href="pet_details.php?id=<?php echo (int)$row['id']; ?>">View details</a>
                        <div class="row-actions">
                            <a class="mini" href="edit.php?id=<?php echo (int)$row['id']; ?>">Edit</a>
                            <a class="mini danger" href="delete_pet.php?id=<?php echo (int)$row['id']; ?>" onclick="return confirm('Are you sure you want to delete this pet?')">Delete</a>
                        </div>
                    </article>
                <?php endwhile; else: ?>
                    <p class="empty">No <?php echo $label; ?> available for adoption right now.</p>
                <?php endif; ?>
            </div>
            <button class="arrow" data-dir="1" aria-label="Scroll right">›</button>
        </div>
    </section>
    <?php
}

/* ---------- dashboard numbers ---------- */
$dogs   = count_rows($conn, "SELECT COUNT(*) FROM pets WHERE LOWER(pet_species) LIKE '%dog%'");
$cats   = count_rows($conn, "SELECT COUNT(*) FROM pets WHERE LOWER(pet_species) LIKE '%cat%'");
$birds  = count_rows($conn, "SELECT COUNT(*) FROM pets WHERE LOWER(pet_species) LIKE '%bird%'");
$pets_total   = count_rows($conn, "SELECT COUNT(*) FROM pets");
$sched_total  = count_rows($conn, "SELECT COUNT(*) FROM schedules");
$rescue_total = count_rows($conn, "SELECT COUNT(*) FROM rescue_reports");
$members      = count_rows($conn, "SELECT COUNT(*) FROM registered");

/* schedules per month, last 6 months (skipped quietly if event_date isn't a date) */
$months = [];
for ($i = 5; $i >= 0; $i--) {
    $key = date('Y-m', strtotime("-$i month", strtotime(date('Y-m-01'))));
    $months[$key] = ['label' => date('M', strtotime($key . '-01')), 'n' => 0];
}
$rm = @mysqli_query($conn, "SELECT DATE_FORMAT(event_date,'%Y-%m') ym, COUNT(*) n FROM schedules GROUP BY ym");
if ($rm) {
    while ($m = mysqli_fetch_assoc($rm)) {
        if (isset($months[$m['ym']])) $months[$m['ym']]['n'] = (int)$m['n'];
    }
}
$max_month = max(1, max(array_column($months, 'n')));

$bubble_max = max(1, $dogs, $cats, $birds);
function bubble($n, $max) { return (int)round(78 + 62 * ($n / $max)); }

$hour = (int)date('G');
$greeting = $hour < 12 ? 'Good Morning' : ($hour < 18 ? 'Good Afternoon' : 'Good Evening');
$admin_name = htmlspecialchars($_SESSION['username'] ?? 'Admin');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>PAWFINDER - Admin Dashboard</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@400;500;600;700&display=swap">
<link rel="stylesheet" href="admin.css">
</head>
<body>
<div class="app">

    <!-- SIDEBAR -->
    <aside class="side">
        <div class="brand">
            <img src="<?php echo $root; ?>image/logo2.jpg" alt="PAWFINDER logo" onerror="this.src='https://placehold.co/42x42/0b5a6b/ffffff?text=PF'">
            <div><b>PAWFINDER</b><small>Community Pet System</small></div>
        </div>

        <div class="group">MAIN MENU</div>
        <nav>
            <a href="admin.php" class="active">
                <svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
                Overview
            </a>
            <a href="pets.php">
                <svg viewBox="0 0 24 24"><path d="M12 21s-7-4.5-9-9a5 5 0 0 1 9-3 5 5 0 0 1 9 3c-2 4.5-9 9-9 9z"/></svg>
                Pets
            </a>
            <a href="users.php">
                <svg viewBox="0 0 24 24"><circle cx="9" cy="8" r="3.5"/><path d="M2.5 20c0-3.6 2.9-6 6.5-6s6.5 2.4 6.5 6"/><path d="M16 4.6a3.5 3.5 0 0 1 0 6.8M18 14.4c2 .7 3.5 2.500 3.5 5.600"/></svg>
                Users
            </a>
            <a href="notifications.php">
                <svg viewBox="0 0 24 24"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 8 3 8H3s3-1 3-8"/><path d="M10.300 21a1.900 1.900 0 0 0 3.400 0"/></svg>
                Notifications
            </a>
        </nav>

        <hr>
        <div class="group">OTHER</div>
        <nav>
            <a href="settings.php">
                <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M19.400 15a1.700 1.700 0 0 0 .3 1.800l.1.1a2 2 0 1 1-2.800 2.800l-.1-.1a1.700 1.700 0 0 0-1.800-.3 1.700 1.700 0 0 0-1 1.500V21a2 2 0 1 1-4 0v-.1a1.700 1.700 0 0 0-1.100-1.500 1.700 1.700 0 0 0-1.800.3l-.1.1a2 2 0 1 1-2.800-2.800l.1-.1a1.700 1.700 0 0 0 .3-1.800 1.700 1.700 0 0 0-1.500-1H3a2 2 0 1 1 0-4h.1a1.700 1.700 0 0 0 1.500-1.100 1.700 1.700 0 0 0-.3-1.800l-.1-.1a2 2 0 1 1 2.800-2.800l.1.1a1.700 1.700 0 0 0 1.800.3H9a1.700 1.700 0 0 0 1-1.500V3a2 2 0 1 1 4 0v.1a1.700 1.700 0 0 0 1 1.500 1.700 1.700 0 0 0 1.800-.3l.1-.1a2 2 0 1 1 2.800 2.800l-.1.1a1.700 1.700 0 0 0-.3 1.800V9a1.700 1.700 0 0 0 1.500 1H21a2 2 0 1 1 0 4h-.1a1.700 1.700 0 0 0-1.500 1z"/></svg>
                Settings
            </a>
            <a href="contact.php">
                <svg viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg>
                Contact
            </a>
        </nav>

        <nav class="logout">
            <a href="/pawfinder/homepage.php" onclick="return confirm('Are you sure you want to log out?');">
                <svg viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9"/></svg>
                Log Out
            </a>
        </nav>
    </aside>

    <!-- MAIN -->
    <main class="main">
        <div class="top">
            <div>
                <h1><?php echo $greeting; ?>, <?php echo $admin_name; ?> 👋</h1>
                <p>Welcome to the Pawfinder Admin Dashboard</p>
            </div>
            <div class="role">Active session role: <b>Admin / Staff</b></div>
        </div>

        <div class="filters">
            <select aria-label="Species filter" id="speciesFilter">
                <option value="">All pets</option>
                <option value="Dogs">Dogs</option>
                <option value="Cats">Cats</option>
                <option value="Birds">Birds</option>
            </select>
        </div>

        <!-- STAT CARDS -->
        <div class="grid g4">
            <div class="card stat">
                <h2>Pets for adoption</h2>
                <div class="num"><strong><?php echo $pets_total; ?></strong></div>
                <div class="sub">Dogs, cats and birds listed</div>
            </div>
            <div class="card stat">
                <h2>Health drives</h2>
                <div class="num"><strong><?php echo $sched_total; ?></strong></div>
                <div class="sub">Scheduled vaccination events</div>
            </div>
            <div class="card stat">
                <h2>Rescue reports</h2>
                <div class="num"><strong><?php echo $rescue_total; ?></strong></div>
                <div class="sub">Emergency reports received</div>
            </div>
            <div class="card stat">
                <h2>Registered users</h2>
                <div class="num"><strong><?php echo $members; ?></strong></div>
                <div class="sub">Adopters and rescuers</div>
            </div>
        </div>

        <!-- CHARTS -->
        <div class="grid g2">
            <div class="card">
                <h2>Pets by species</h2>
                <div class="legend">
                    <span style="--c:var(--teal-2)">Dogs</span>
                    <span style="--c:var(--lav)">Cats</span>
                    <span style="--c:var(--mint)">Birds</span>
                </div>
                <div class="bubbles">
                    <?php $d = bubble($dogs,$bubble_max); $c = bubble($cats,$bubble_max); $b = bubble($birds,$bubble_max); ?>
                    <div class="b1" style="width:<?php echo $d; ?>px;height:<?php echo $d; ?>px"><?php echo $dogs; ?></div>
                    <div class="b2" style="width:<?php echo $c; ?>px;height:<?php echo $c; ?>px"><?php echo $cats; ?></div>
                    <div class="b3" style="width:<?php echo $b; ?>px;height:<?php echo $b; ?>px"><?php echo $birds; ?></div>
                </div>
            </div>

            <div class="card">
                <h2>Health drives per month</h2>
                <div class="bars">
                    <?php foreach ($months as $m): ?>
                        <div title="<?php echo $m['n']; ?> drive(s)"><i style="height:<?php echo round(($m['n'] / $max_month) * 100); ?>%"></i></div>
                    <?php endforeach; ?>
                </div>
                <div class="bars-x">
                    <?php foreach ($months as $m): ?><span><?php echo $m['label']; ?></span><?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- PARTNER SHELTERS -->
        <section class="panel">
            <div class="panel-head"><h2>Our Partner Shelters</h2></div>
            <p class="muted" style="margin-bottom:16px;max-width:760px">
                Pets that stay unadopted for a long time are transferred to larger partner facilities for long-term shelter, rehabilitation and specialized care.
            </p>
            <div class="grid g4" style="grid-template-columns:repeat(auto-fit,minmax(240px,1fr));margin-bottom:0">
                <div class="pet" style="width:auto;min-width:0">
                    <img src="<?php echo $root; ?>image/afk.jpg" alt="AKF Animal Rescue" onerror="this.src='https://placehold.co/280x150/f1f5f9/0b5a6b?text=AKF'">
                    <h3>Animal Kingdom Foundation (AKF)</h3>
                    <p class="muted">★ 4.5 (2 reviews) • Verified<br>Animal Shelter • Non-Profit</p>
                    <p class="muted"><b>Address:</b> No. 8, Purante Street, Cub-Cub, Capas, Tarlac<br><b>Contact:</b> +63 939 914 2403</p>
                    <a class="btn btn-soft" href="https://animalkingdomfoundation.org" target="_blank" rel="noopener">Visit website</a>
                </div>
                <div class="pet" style="width:auto;min-width:0">
                    <img src="<?php echo $root; ?>image/part.jpg" alt="Philippine Animal Rescue Team" onerror="this.src='https://placehold.co/280x150/f1f5f9/0b5a6b?text=PART'">
                    <h3>Philippine Animal Rescue Team (PART)</h3>
                    <p class="muted">★ 4.8 • Verified Sanctuary<br>No-Kill Shelter • Rehabilitation Center</p>
                    <p class="muted"><b>Address:</b> San Jose del Monte, Bulacan<br><b>Contact:</b> +63 917 859 2928</p>
                    <a class="btn btn-soft" href="https://www.philippineanimalrescueteam.org" target="_blank" rel="noopener">Visit website</a>
                </div>
                <div class="pet" style="width:auto;min-width:0">
                    <img src="<?php echo $root; ?>image/paw.jpg" alt="PAWS Philippines" onerror="this.src='https://placehold.co/280x150/f1f5f9/0b5a6b?text=PAWS'">
                    <h3>Philippine Animal Welfare Society (PAWS)</h3>
                    <p class="muted">★ 4.9 • Verified Organization<br>Animal Welfare • Spay &amp; Neuter Clinic</p>
                    <p class="muted"><b>Address:</b> Aurora Blvd., Katipunan Valley, Loyola Heights, Quezon City<br><b>Contact:</b> +63 2 8475 1686</p>
                    <a class="btn btn-soft" href="https://paws.org.ph" target="_blank" rel="noopener">Visit website</a>
                </div>
            </div>
        </section>

        <!-- PET SECTIONS -->
        <?php
        pet_section($conn, 'Dogs for Adoption',  'dog',  'dogs',  $root);
        pet_section($conn, 'Cats for Adoption',  'cat',  'cats',  $root);
        pet_section($conn, 'Birds for Adoption', 'bird', 'birds', $root);
        ?>

        <!-- SCHEDULES -->
        <section class="panel" data-section>
            <div class="panel-head">
                <h2>Free Medicine &amp; Vaccination Schedule</h2>
                <div class="panel-tools">
                    <input type="search" class="search" placeholder="Search schedules..." data-filter>
                    <a href="addschedule.php" class="btn">+ Add</a>
                </div>
            </div>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Date &amp; Time</th>
                            <th>Location / Barangay</th>
                            <th>Services Offered</th>
                            <th>Status</th>
                            <th class="tcenter">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $result_sched = mysqli_query($conn, "SELECT * FROM schedules ORDER BY event_date DESC");
                    if ($result_sched && mysqli_num_rows($result_sched) > 0):
                        while ($row = mysqli_fetch_assoc($result_sched)): ?>
                        <tr data-item>
                            <td><?php echo htmlspecialchars($row['event_date']); ?><br><small><?php echo htmlspecialchars($row['event_time']); ?></small></td>
                            <td><?php echo htmlspecialchars($row['barangay']); ?><br><small><?php echo htmlspecialchars($row['venue']); ?></small></td>
                            <td><?php echo htmlspecialchars($row['services']); ?></td>
                            <td><span class="status"><?php echo htmlspecialchars($row['status']); ?></span></td>
                            <td class="tcenter">
                                <a class="mini" href="edit_schedule.php?id=<?php echo (int)$row['id']; ?>">Edit</a>
                                <a class="mini danger" href="delete_schedule.php?id=<?php echo (int)$row['id']; ?>" onclick="return confirm('Delete this schedule?')">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; else: ?>
                        <tr><td colspan="5" class="empty" style="text-align:center">No vaccination schedules available.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- STRAYS -->
        <section class="panel" data-section>
            <div class="panel-head">
                <h2>Missing &amp; Found Stray Pets (Reported Outside)</h2>
                <div class="panel-tools">
                    <input type="search" class="search" placeholder="Search strays..." data-filter>
                    <a href="report.php" class="btn">+ Add</a>
                </div>
            </div>
            <div class="slider-wrap">
                <button class="arrow" data-dir="-1" aria-label="Scroll left">‹</button>
                <div class="slider">
                    <article class="pet" data-item>
                        <img src="https://placehold.co/220x140/f1f5f9/0b5a6b?text=Stray+Photo" alt="Missing pet">
                        <h3>------------</h3>
                        <p class="muted">------------</p>
                        <p class="tag">Barangay: ------------</p>
                        <a class="btn btn-soft" href="pet_details.html">View details</a>
                        <div class="row-actions">
                            <button class="mini" style="border:0;cursor:pointer">Edit</button>
                            <button class="mini danger" style="border:0;cursor:pointer">Delete</button>
                        </div>
                    </article>
                </div>
                <button class="arrow" data-dir="1" aria-label="Scroll right">›</button>
            </div>
        </section>
    </main>
</div>

<script>
/* slider arrows */
document.querySelectorAll('.slider-wrap').forEach(function (w) {
    var s = w.querySelector('.slider');
    w.querySelectorAll('.arrow').forEach(function (b) {
        b.addEventListener('click', function () {
            s.scrollBy({ left: 240 * parseInt(b.dataset.dir, 10) });
        });
    });
});

/* per-section search */
document.querySelectorAll('[data-section]').forEach(function (sec) {
    var box = sec.querySelector('[data-filter]');
    if (!box) return;
    box.addEventListener('input', function () {
        var q = box.value.toLowerCase();
        sec.querySelectorAll('[data-item]').forEach(function (it) {
            it.style.display = it.textContent.toLowerCase().indexOf(q) > -1 ? '' : 'none';
        });
    });
});

/* species filter: show only the chosen species section */
document.getElementById('speciesFilter').addEventListener('change', function (e) {
    var v = e.target.value.toLowerCase();
    document.querySelectorAll('[data-section]').forEach(function (sec) {
        var t = sec.querySelector('h2').textContent.toLowerCase();
        var isSpecies = /dogs|cats|birds/.test(t);
        sec.style.display = (!v || !isSpecies || t.indexOf(v) > -1) ? '' : 'none';
    });
});
</script>
</body>
</html>