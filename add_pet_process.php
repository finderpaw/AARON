<?php
// add_pet_process.php
include 'config.php'; // Palitan ng totoong database connection file mo

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pet_name        = trim($_POST['pet_name']);
    $pet_species     = strtolower(trim($_POST['pet_species'])); // Ginawang lowercase para sa madaling filtering (e.g., 'dog', 'cat')
    $breed           = trim($_POST['breed']);
    $age             = trim($_POST['age']);
    $barangay        = trim($_POST['barangay']);
    $adoption_reason = trim($_POST['adoption_reason']);
    $health_issues   = trim($_POST['health_issues']);
    $pet_details     = trim($_POST['pet_details']);

    // Handle Image Upload
    $target_dir = "uploads/";
    
    // Siguraduhing umiiral ang uploads folder
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $file_name = time() . '_' . basename($_FILES["pet_photo"]["name"]);
    $target_file = $target_dir . $file_name;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Validations (Optional: Check image format)
    $allowed_types = ['jpg', 'jpeg', 'png', 'webp'];
    if (in_array($imageFileType, $allowed_types)) {
        if (move_uploaded_file($_FILES["pet_photo"]["tmp_name"], $target_file)) {
            
            // Insert sa Database gamit ang Prepared Statement
            $stmt = $conn->prepare("INSERT INTO pets (pet_name, pet_species, breed, age, barangay, pet_photo, adoption_reason, health_issues, pet_details) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssssss", $pet_name, $pet_species, $breed, $age, $barangay, $target_file, $adoption_reason, $health_issues, $pet_details);

            if ($stmt->execute()) {
                // Pag matagumpay, ibabalik sa dashboard page
                header("Location: admin.php?success=1");
                exit();
            } else {
                echo "Error saving to database: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    } else {
        echo "Invalid file type. Only JPG, JPEG, PNG, and WEBP files are allowed.";
    }
}
?>