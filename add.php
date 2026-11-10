<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pawfinder - Add Pet Application</title>
    <link rel="stylesheet" href="css/add.css">
</head>
<body>

<div class="register-card">
    
    <div class="sidebar-left">
        <div>
            <div class="brand-logo">🐾 PAWFINDER</div>
            
            <ul class="step-list">
                <li class="step-item active">○ Pet Information</li>
                <li class="step-item">○ Photo & Location</li>
                <li class="step-item">○ Reason & Status</li>
                <li class="step-item">○ Submit Application</li>
            </ul>
        </div>

        <div>
            <div class="sidebar-img-container">
                <img src="image/animal.jpg" alt="Animal" onerror="this.src='https'://placehold.co/160x120/1e293b/2dd4bf?text=Pawfinder'">
            </div>
            
            <div class="back-to-login">
                <a href="admin.php">← Back to Dashboard</a>
            </div>
        </div>
    </div>
     <div class="form-right">
        <h2>Add Pet for Adoption</h2>
        <p>Please provide the details of the pet you wish to put up for adoption or report to the system.</p>

        <form action="add_pet_process.php" method="POST" enctype="multipart/form-data">
            
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label>Pet Name / Description</label>
                        <input type="text" name="pet_name" placeholder="e.g., Buddy / White Cat" required>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label>Animal Type</label>
                        <input type="text" name="pet_species" placeholder="e.g., Dog, Cat, Bird" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label>Breed</label>
                        <input type="text" name="breed" placeholder="e.g., Aspin, Shih Tzu, Domestic Short Hair" required>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label>Estimated Age</label>
                        <input type="text" name="age" placeholder="e.g., 5 Months / 2 Years" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label>Barangay Location</label>
                        <input type="text" name="barangay" placeholder="e.g., Brgy. Dau, Mabalacat City" required>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label>Upload Pet Photo</label>
                        <input type="file" name="pet_photo" accept="image/*" required>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Reason for Adoption</label>
                <input type="text" name="adoption_reason" placeholder="e.g., Relocating, financial constraints, unable to care for" required>
            </div>

            <div class="form-group">
                <label>Health Issues / Condition (Optional)</label>
                <input type="text" name="health_issues" placeholder="e.g., Fully vaccinated, injured leg, none">
            </div>

            <div class="form-group">
                <label>Additional Details / Contact Info</label>
                <textarea name="pet_details" rows="3" placeholder="Write any additional information or your contact details for interested adopters..."></textarea>
            </div>

            <button type="submit" class="btn-submit">Submit Pet Listing</button>

        </form>
    </div>

</div>

</body>
</html>