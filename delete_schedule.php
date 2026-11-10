<?php

include 'config.php';

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $sched_id = intval($_GET['id']);

    $stmt = $conn->prepare("DELETE FROM schedules WHERE id = ?");
    $stmt->bind_param("i", $sched_id);

    if ($stmt->execute()) {
        header("Location: admin.php?success=schedule_deleted");
        exit();
    } else {
        echo "Error deleting schedule: " . $stmt->error;
    }
} else {
    header("Location: admin.php");
    exit();
}
?>