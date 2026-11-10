<?php

include 'config.php';


if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: admin.php");
    exit();
}

$pet_id = intval($_GET['id']);

$stmt = $conn->prepare("SELECT * FROM pets WHERE id = ?");
$stmt->bind_param("i", $pet_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "Pet not found.";
    exit();
}

$pet = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pawfinder - Edit Pet Details</title>
    <link rel="stylesheet" href="css/edit.css">
</head>
<body>

<div class="register-card">
    
    <div class="sidebar-left">
        <div>
            <div class="brand-logo">🐾 PAWFINDER</div>
            
            <ul class="step-list">
                <li class="step-item active">✏️ Edit Details</li>
                <li class="step-item">○ Update Photo & Barangay</li>
                <li class="step-item">○ Health & Status</li>
                <li class="step-item">○ Save Changes</li>
            </ul>
        </div>

        <div>
            <div class="sidebar-img-container">
                <img src="<?php echo htmlspecialchars($pet['pet_photo']); ?>" alt="Pet Photo" style="width: 100%; height: 120px; object-fit: cover;" onerror="this.src='https://placehold.co/160x120/1e293b/2dd4bf?text=Current+Photo'">
            </div>
            
            <div class="back-to-login">
                <a href="admin.php">← Cancel & Return</a>
            </div>
        </div>
    </div>

    <div class="form-right">
        <h2>Edit Pet Information</h2>
        <p>Update the information below for this pet record.</p>

        <form action="update_pet_process.php" method="POST" enctype="multipart/form-data">
            
            <input type="hidden" name="pet_id" value="<?php echo $pet['id']; ?>">

            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label>Pet Name / Description</label>
                        <input type="text" name="pet_name" value="<?php echo htmlspecialchars($pet['pet_name']); ?>" placeholder="e.g., Buddy / White Cat" required>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label>Animal Type</label>
                        <input type="text" name="pet_species" value="<?php echo htmlspecialchars($pet['pet_species']); ?>" placeholder="e.g., Dog, Cat, Bird" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label>Breed</label>
                        <input type="text" name="breed" value="<?php echo htmlspecialchars($pet['breed']); ?>" placeholder="e.g., Aspin, Shih Tzu" required>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label>Estimated Age</label>
                        <input type="text" name="age" value="<?php echo htmlspecialchars($pet['age']); ?>" placeholder="e.g., 2 Years" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label>Barangay Location</label>
                        <input type="text" name="barangay" value="<?php echo htmlspecialchars($pet['barangay']); ?>" placeholder="e.g., Brgy. Dau" required>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label>Replace Pet Photo (Optional)</label>
                        <input type="file" name="pet_photo" accept="image/*">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Reason for Adoption</label>
                <input type="text" name="adoption_reason" value="<?php echo htmlspecialchars($pet['adoption_reason']); ?>" placeholder="e.g., Relocating" required>
            </div>

            <div class="form-group">
                <label>Health Issues / Condition (Optional)</label>
                <input type="text" name="health_issues" value="<?php echo htmlspecialchars($pet['health_issues']); ?>" placeholder="e.g., Fully vaccinated">
            </div>

            <div class="form-group">
                <label>Additional Details / Contact Info</label>
                <textarea name="pet_details" rows="3" placeholder="Write any additional details..."><?php echo htmlspecialchars($pet['pet_details']); ?></textarea>
            </div>

            <button type="submit" class="btn-submit">Update Pet Record</button>

        </form>
    </div>

</div>

</body>
</html>