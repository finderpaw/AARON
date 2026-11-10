<?php

include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $event_date = trim($_POST['event_date']);
    $event_time = trim($_POST['event_time']);
    $barangay   = trim($_POST['barangay']);
    $venue      = trim($_POST['venue']);
    

    $services   = isset($_POST['services']) ? implode(', ', $_POST['services']) : '';
    
    $status     = trim($_POST['status']);
    $organizer  = trim($_POST['organizer']);
    $reminders  = trim($_POST['reminders']);


    $stmt = $conn->prepare("INSERT INTO schedules (event_date, event_time, barangay, venue, services, status, organizer, reminders) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $event_date, $event_time, $barangay, $venue, $services, $status, $organizer, $reminders);

    if ($stmt->execute()) {
        header("Location: admin.php?success=schedule_added");
        exit();
    } else {
        echo "Error saving schedule: " . $stmt->error;
    }
    $stmt->close();
}
?>