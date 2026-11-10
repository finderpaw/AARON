<?php

include 'config.php';


if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: admin.php");
    exit();
}

$sched_id = intval($_GET['id']);


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $event_date = trim($_POST['event_date']);
    $event_time = trim($_POST['event_time']);
    $barangay   = trim($_POST['barangay']);
    $venue      = trim($_POST['venue']);
    $services   = isset($_POST['services']) ? implode(', ', $_POST['services']) : '';
    $status     = trim($_POST['status']);
    $organizer  = trim($_POST['organizer']);
    $reminders  = trim($_POST['reminders']);

    $stmt = $conn->prepare("UPDATE schedules SET event_date=?, event_time=?, barangay=?, venue=?, services=?, status=?, organizer=?, reminders=? WHERE id=?");
    $stmt->bind_param("ssssssssi", $event_date, $event_time, $barangay, $venue, $services, $status, $organizer, $reminders, $sched_id);

    if ($stmt->execute()) {
        header("Location: admin.php?success=schedule_updated");
        exit();
    } else {
        echo "Error updating schedule: " . $stmt->error;
    }
}

$stmt = $conn->prepare("SELECT * FROM schedules WHERE id = ?");
$stmt->bind_param("i", $sched_id);
$stmt->execute();
$result = $stmt->get_result();
$sched = $result->fetch_assoc();

if (!$sched) {
    echo "Schedule not found.";
    exit();
}

$current_services = explode(', ', $sched['services']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pawfinder - Edit Health & Vaccination Schedule</title>
    <link rel="stylesheet" href="css/addschedule.css">
</head>
<body>

<div class="register-card">
    <div class="sidebar-left">
        <div>
            <div class="brand-logo">🐾 PAWFINDER</div>
            <ul class="step-list">
                <li class="step-item active">✏️ Edit Schedule</li>
                <li class="step-item">○ Update Venue & Services</li>
                <li class="step-item">○ Save Changes</li>
            </ul>
        </div>
        <div>
            <div class="back-to-login">
                <a href="admin.php">← Back to Dashboard</a>
            </div>
        </div>
    </div>

    <div class="form-right">
        <h2>Edit Health & Vaccination Event</h2>
        <p>Modify the details of the health drive schedule below.</p>

        <form action="" method="POST">
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label>Event Date</label>
                        <input type="date" name="event_date" value="<?php echo htmlspecialchars($sched['event_date']); ?>" required>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label>Start & End Time</label>
                        <input type="text" name="event_time" value="<?php echo htmlspecialchars($sched['event_time']); ?>" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label>Location / Barangay</label>
                        <input type="text" name="barangay" value="<?php echo htmlspecialchars($sched['barangay']); ?>" required>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label>Specific Venue / Covered Court</label>
                        <input type="text" name="venue" value="<?php echo htmlspecialchars($sched['venue']); ?>" required>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Services Offered</label>
                <div class="checkbox-group">
                    <?php 
                    $all_services = [
                        "Anti-Rabies Vaccine", 
                        "Free Deworming", 
                        "5-in-1 / 4-in-1 Vaccine", 
                        "Free Vitamin Supplements", 
                        "Basic Health Checkup", 
                        "Spay & Neuter Consultation"
                    ];
                    foreach ($all_services as $srv) {
                        $checked = in_array($srv, $current_services) ? 'checked' : '';
                        echo '<label class="checkbox-item"><input type="checkbox" name="services[]" value="'.$srv.'" '.$checked.'> '.$srv.'</label>';
                    }
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label>Schedule Status</label>
                        <input type="text" name="status" value="<?php echo htmlspecialchars($sched['status']); ?>" required>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label>Organizer / Veterinary In-Charge</label>
                        <input type="text" name="organizer" value="<?php echo htmlspecialchars($sched['organizer']); ?>" required>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Reminders & Guidelines for Pet Owners</label>
                <textarea name="reminders" rows="3"><?php echo htmlspecialchars($sched['reminders']); ?></textarea>
            </div>

            <button type="submit" class="btn-submit">Update Schedule</button>
        </form>
    </div>
</div>

</body>
</html>