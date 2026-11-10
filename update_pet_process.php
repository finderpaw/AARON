<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pet_id          = intval($_POST['pet_id']);
    $pet_name        = trim($_POST['pet_name']);
    $pet_species     = strtolower(trim($_POST['pet_species']));
    $breed           = trim($_POST['breed']);
    $age             = trim($_POST['age']);
    $barangay        = trim($_POST['barangay']);
    $adoption_reason = trim($_POST['adoption_reason']);
    $health_issues   = trim($_POST['health_issues']);
    $pet_details     = trim($_POST['pet_details']);

    if (!empty($_FILES["pet_photo"]["name"])) {
        $target_dir = "uploads/";
        $file_name = time() . '_' . basename($_FILES["pet_photo"]["name"]);
        $target_file = $target_dir . $file_name;

        if (move_uploaded_file($_FILES["pet_photo"]["tmp_name"], $target_file)) {
            $stmt = $conn->prepare("UPDATE pets SET pet_name=?, pet_species=?, breed=?, age=?, barangay=?, pet_photo=?, adoption_reason=?, health_issues=?, pet_details=? WHERE id=?");
            $stmt->bind_param("sssssssssi", $pet_name, $pet_species, $breed, $age, $barangay, $target_file, $adoption_reason, $health_issues, $pet_details, $pet_id);
        }
    } else {
        $stmt = $conn->prepare("UPDATE pets SET pet_name=?, pet_species=?, breed=?, age=?, barangay=?, adoption_reason=?, health_issues=?, pet_details=? WHERE id=?");
        $stmt->bind_param("ssssssssi", $pet_name, $pet_species, $breed, $age, $barangay, $adoption_reason, $health_issues, $pet_details, $pet_id);
    }

    if ($stmt->execute()) {
        header("Location: admin.php?success=updated");
        exit();
    } else {
        echo "Error updating record: " . $stmt->error;
    }
}
?>