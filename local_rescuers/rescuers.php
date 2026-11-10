<?php
session_start();

// Block anyone who is not logged in as a rescuer
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'rescuer') {
    header('Location: /pawfinder/local_rescuers/rescuers.php');
    exit;
}   

require_once __DIR__ . '/config.php';   // must create $conn (mysqli)

// CSRF token for the status-update forms
if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(16));
}
$csrf = $_SESSION['csrf'];

$fullName = htmlspecialchars($_SESSION['full_name'] ?? $_SESSION['username'] ?? 'Rescuer');
$email    = htmlspecialchars($_SESSION['email'] ?? '');
$avatar   = htmlspecialchars($_SESSION['avatar'] ?? 'image/default-avatar.png');

function h($v) { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }

function count_rows($conn, $sql) {
    $r = @mysqli_query($conn, $sql);
    if (!$r) return 0;
    $row = mysqli_fetch_row($r);
    return (int)($row[0] ?? 0);
}

$pending  = count_rows($conn, "SELECT COUNT(*) FROM rescue_reports WHERE status = 'Pending'");
$progress = count_rows($conn, "SELECT COUNT(*) FROM rescue_reports WHERE status = 'In Progress'");
$rescued  = count_rows($conn, "SELECT COUNT(*) FROM rescue_reports WHERE status = 'Rescued'");

$reports = @mysqli_query($conn,
    "SELECT * FROM rescue_reports
     ORDER BY FIELD(status,'Pending','In Progress','Rescued','Rejected'), report_date DESC
     LIMIT 20");

$drives = @mysqli_query($conn,
    "SELECT * FROM health_drives
     WHERE status IN ('Upcoming','Ongoing')
     ORDER BY event_date ASC LIMIT 4");

$statuses = ['Pending', 'In Progress', 'Rescued', 'Rejected'];
$flash = $_GET['msg'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PAWFINDER - Rescuer Dashboard</title>
    <link rel="stylesheet" href="css/homepage.css">
    <link rel="stylesheet" href="css/adopter.css">
    <style>
        /* Extra styles for the rescuer page only */
        .flash { max-width: 1100px; margin: 0 auto 16px; padding: 12px 16px; border-radius: 8px;
                 background: #e6f4ea; color: #1e5b32; }
        .flash.error { background: #fdecea; color: #8a1f17; }

        .report-panel { max-width: 1100px; margin: 0 auto 32px; }
        .report-panel h2 { margin-bottom: 12px; }
        .report-head { display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap; }
        .report-search { padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 8px; min-width: 220px; }

        .table-scroll { overflow-x: auto; background: #fff; border-radius: 12px; border: 1px solid #e2e8f0; }
        .report-table { width: 100%; border-collapse: collapse; min-width: 760px; }
        .report-table th, .report-table td { padding: 12px 14px; text-align: left; border-bottom: 1px solid #eef2f6; vertical-align: top; }
        .report-table th { background: #f8fafc; font-size: 0.85rem; }
        .report-table small { color: #64748b; }

        .badge { display: inline-block; padding: 3px 10px; border-radius: 999px; font-size: 0.8rem; font-weight: 600; }
        .badge.pending     { background: #fff3cd; color: #7a5b00; }
        .badge.in-progress { background: #dbeafe; color: #1e40af; }
        .badge.rescued     { background: #dcfce7; color: #166534; }
        .badge.rejected    { background: #fee2e2; color: #991b1b; }

        .status-form { display: flex; gap: 6px; }
        .status-form select { padding: 6px; border-radius: 6px; border: 1px solid #cbd5e1; }
        .status-form button { padding: 6px 12px; border: 0; border-radius: 6px; background: #0b5a6b; color: #fff; cursor: pointer; }

        .empty-row { text-align: center; color: #64748b; padding: 24px; }

        .drive-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px; }
        .drive-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px; }
        .drive-card h3 { margin: 0 0 6px; font-size: 1rem; }
        .drive-card p { margin: 4px 0; color: #475569; font-size: 0.9rem; }

        .report-cta { display: inline-block; padding: 10px 18px; border-radius: 8px; background: #0b5a6b;
                      color: #fff; text-decoration: none; font-weight: 600; }
    </style>
</head>
<body class="adopter-body">

    <!-- TOP BAR: account profile on the upper left -->
    <div class="adopter-topbar">
        <div class="profile-menu" id="profileMenu">
            <button type="button" class="profile-trigger" id="profileTrigger"
                    aria-haspopup="true" aria-expanded="false" aria-controls="profileDropdown">
                <img src="<?= $avatar ?>" alt="" class="profile-avatar">
                <span class="profile-text">
                    <span class="profile-name"><?= $fullName ?></span>
                    <span class="profile-role">Local Rescuer</span>
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

                <a href="rescuer_profile.php" class="dropdown-item" role="menuitem">My Profile</a>
                <a href="rescue_reports.php" class="dropdown-item" role="menuitem">Rescue Reports</a>
                <a href="my_cases.php" class="dropdown-item" role="menuitem">My Active Cases</a>
                <a href="rescue_history.php" class="dropdown-item" role="menuitem">Rescue History</a>
                <a href="messages.php" class="dropdown-item" role="menuitem">Messages</a>
                <a href="settings.php" class="dropdown-item" role="menuitem">Account Settings</a>

                <div class="dropdown-divider"></div>
                <a href="logout.php" class="dropdown-item dropdown-logout" role="menuitem">Log Out</a>
            </div>
        </div>
    </div>

    <!-- NAVBAR -->
    <header class="navbar">
        <div class="brand-section">
            <img src="image/logo2.jpg" alt="PAWFINDER Logo" class="brand-logo-img">
            <div>
                <span class="brand-title">PAWFINDER</span>
                <span class="brand-subtitle">COMMUNITY PET SYSTEM</span>
            </div>
        </div>

        <ul class="nav-links">
            <li class="nav-item"><a href="rescuer_home.php" class="active">Home</a></li>
            <li class="nav-item"><a href="rescue_reports.php">Rescue Reports</a></li>
            <li class="nav-item"><a href="missing_found.php">Missing &amp; Found</a></li>
            <li class="nav-item"><a href="health_drives.php">Health Drives</a></li>
            <li class="nav-item"><a href="contact.php">Contact</a></li>
        </ul>
    </header>

    <main class="content-area">
        <div class="hero-banner adopter-hero">
            <h1>Welcome, <?= $fullName ?>!</h1>
            <p>Review emergency reports in your barangay and update each case as you respond.</p>
        </div>

        <?php if ($flash === 'updated'): ?>
            <div class="flash">Report status updated.</div>
        <?php elseif ($flash === 'error'): ?>
            <div class="flash error">Could not update that report. Please try again.</div>
        <?php endif; ?>

        <!-- QUICK STATS -->
        <section class="adopter-stats">
            <div class="stat-card">
                <span class="stat-number"><?= $pending ?></span>
                <span class="stat-label">Waiting for response</span>
            </div>
            <div class="stat-card">
                <span class="stat-number"><?= $progress ?></span>
                <span class="stat-label">In progress</span>
            </div>
            <div class="stat-card">
                <span class="stat-number"><?= $rescued ?></span>
                <span class="stat-label">Pets rescued</span>
            </div>
        </section>

        <!-- RESCUE REPORTS -->
        <section class="report-panel" id="reports-section">
            <div class="report-head">
                <h2>Rescue Reports</h2>
                <input type="search" class="report-search" id="reportSearch" placeholder="Search reports...">
            </div>

            <div class="table-scroll">
                <table class="report-table">
                    <thead>
                        <tr>
                            <th>Reported</th>
                            <th>Location</th>
                            <th>Pet</th>
                            <th>Details</th>
                            <th>Contact</th>
                            <th>Status</th>
                            <th>Update</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if ($reports && mysqli_num_rows($reports) > 0):
                        while ($r = mysqli_fetch_assoc($reports)):
                            $badge = strtolower(str_replace(' ', '-', $r['status'])); ?>
                        <tr data-item>
                            <td><?= h(date('M j, Y', strtotime($r['report_date']))) ?><br>
                                <small><?= h(date('g:i A', strtotime($r['report_date']))) ?></small></td>
                            <td><?= h($r['location']) ?></td>
                            <td><?= h($r['pet_type']) ?><br><small><?= h($r['pet_condition']) ?></small></td>
                            <td><?= h($r['description']) ?><br><small>Reported by <?= h($r['reporter_name']) ?></small></td>
                            <td><a href="tel:<?= h($r['contact_number']) ?>"><?= h($r['contact_number']) ?></a></td>
                            <td><span class="badge <?= h($badge) ?>"><?= h($r['status']) ?></span></td>
                            <td>
                                <form class="status-form" method="post" action="update_report.php">
                                    <input type="hidden" name="csrf" value="<?= h($csrf) ?>">
                                    <input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
                                    <select name="status" aria-label="New status">
                                        <?php foreach ($statuses as $s): ?>
                                            <option value="<?= h($s) ?>" <?= $s === $r['status'] ? 'selected' : '' ?>><?= h($s) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <button type="submit">Save</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; else: ?>
                        <tr><td colspan="7" class="empty-row">No rescue reports yet. New reports will show up here.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- UPCOMING HEALTH DRIVES -->
        <section class="report-panel" id="drives-section">
            <div class="report-head">
                <h2>Upcoming Health Drives</h2>
                <a href="health_drives.php" class="report-cta">View all drives</a>
            </div>
            <div class="drive-grid">
            <?php if ($drives && mysqli_num_rows($drives) > 0):
                while ($d = mysqli_fetch_assoc($drives)): ?>
                <div class="drive-card">
                    <h3><?= h($d['name']) ?></h3>
                    <p><b><?= h(date('M j, Y', strtotime($d['event_date']))) ?></b> &bull; <?= h($d['event_time']) ?></p>
                    <p><?= h($d['location']) ?></p>
                    <p><?= h($d['description']) ?></p>
                </div>
            <?php endwhile; else: ?>
                <p class="empty-row">No upcoming health drives right now.</p>
            <?php endif; ?>
            </div>
        </section>

        <!-- REPORT A STRAY -->
        <section class="report-panel" style="text-align:center">
            <h2>Found a stray while out on a rescue?</h2>
            <p style="margin-bottom:14px">Log it so other rescuers and shelters can see it.</p>
            <a href="report.php" class="report-cta">Report a stray pet</a>
        </section>
    </main>

    <script>
        // Profile dropdown (same behavior as the adopter page)
        const menu     = document.getElementById('profileMenu');
        const trigger  = document.getElementById('profileTrigger');
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

        // Search the reports table
        document.getElementById('reportSearch').addEventListener('input', function () {
            const q = this.value.toLowerCase();
            document.querySelectorAll('#reports-section [data-item]').forEach((row) => {
                row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
            });
        });
    </script>
</body>
</html>