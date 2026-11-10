<?php
// pet_details.php
include 'config.php';

// Suriin kung may pumasok na ID sa URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: admin.php");
    exit();
}

$pet_id = intval($_GET['id']);

// Kunin ang datos ng pet batay sa ID
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pawfinder - <?php echo htmlspecialchars($pet['pet_name']); ?> Details</title>
    <style>
        * {
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
        }
        body {
            background-color: #f8fafc;
            color: #0f172a;
            padding: 40px 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            overflow: hidden;
        }
        .header-bar {
            padding: 20px 30px;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .back-btn {
            text-decoration: none;
            color: #0d9488;
            font-weight: 600;
            font-size: 14px;
        }
        .details-wrapper {
            display: flex;
            flex-wrap: wrap;
            padding: 30px;
            gap: 30px;
        }
        .image-box {
            flex: 1 1 300px;
        }
        .image-box img {
            width: 100%;
            height: 320px;
            object-fit: cover;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
        }
        .info-box {
            flex: 1 1 400px;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }
        .pet-title {
            font-size: 28px;
            font-weight: 700;
            color: #0f172a;
        }
        .badge-list {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        .badge {
            background: #ccfbf1;
            color: #0d9488;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            text-transform: capitalize;
        }
        .info-group {
            border-top: 1px solid #f1f5f9;
            padding-top: 10px;
        }
        .info-label {
            font-size: 12px;
            color: #64748b;
            font-weight: 700;
            text-transform: uppercase;
            margin-bottom: 4px;
        }
        .info-value {
            font-size: 15px;
            color: #334155;
            line-height: 1.5;
        }
        .action-buttons {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }
        .btn-edit {
            text-decoration: none;
            background: #e2e8f0;
            color: #0f172a;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
        }
        .btn-del {
            text-decoration: none;
            background: #fee2e2;
            color: #991b1b;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header-bar">
        <a href="admin.php" class="back-btn">← Back to Dashboard</a>
        <span class="badge">Status: Available</span>
    </div>

    <div class="details-wrapper">
        <div class="image-box">
            <img src="<?php echo htmlspecialchars($pet['pet_photo']); ?>" alt="<?php echo htmlspecialchars($pet['pet_name']); ?>">
        </div>

        <div class="info-box">
            <h1 class="pet-title"><?php echo htmlspecialchars($pet['pet_name']); ?></h1>
            
            <div class="badge-list">
                <span class="badge"><?php echo htmlspecialchars($pet['pet_species']); ?></span>
                <span class="badge"><?php echo htmlspecialchars($pet['breed']); ?></span>
                <span class="badge"><?php echo htmlspecialchars($pet['age']); ?></span>
            </div>

            <div class="info-group">
                <div class="info-label">Barangay Location</div>
                <div class="info-value"><?php echo htmlspecialchars($pet['barangay']); ?></div>
            </div>

            <div class="info-group">
                <div class="info-label">Reason for Adoption</div>
                <div class="info-value"><?php echo htmlspecialchars($pet['adoption_reason']); ?></div>
            </div>

            <div class="info-group">
                <div class="info-label">Health Issues / Condition</div>
                <div class="info-value"><?php echo !empty($pet['health_issues']) ? htmlspecialchars($pet['health_issues']) : 'None reported'; ?></div>
            </div>

            <div class="info-group">
                <div class="info-label">Additional Details & Contact</div>
                <div class="info-value"><?php echo !empty($pet['pet_details']) ? nl2br(htmlspecialchars($pet['pet_details'])) : 'N/A'; ?></div>
            </div>

            <div class="action-buttons">
                <a href="edit_pet.php?id=<?php echo $pet['id']; ?>" class="btn-edit">Edit Listing</a>
                <a href="delete_pet.php?id=<?php echo $pet['id']; ?>" class="btn-del" onclick="return confirm('Are you sure?')">Delete Listing</a>
            </div>
        </div>
    </div>
</div>

</body>
</html>