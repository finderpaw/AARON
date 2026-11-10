<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pawfinder - Add Pet</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@400;500;600;700&display=swap">
    <link rel="stylesheet" href="add.css">
</head>
<body>

<div class="register-card">

    <div class="sidebar-left">
        <div>
            <div class="brand-logo">🐾 PAWFINDER</div>
            <ul class="step-list">
                <li class="step-item active"><a href="#pet-info">Pet information</a></li>
                <li class="step-item"><a href="#photo-location">Photo &amp; location</a></li>
                <li class="step-item"><a href="#reason-status">Reason &amp; status</a></li>
            </ul>
        </div>

        <div>
            <div class="sidebar-img-container">
                <img src="image/animal.jpg" alt="Animal" onerror="this.src='https://placehold.co/260x130/eef1f4/0b5a6b?text=Pawfinder'">
            </div>
            <div class="back-to-login">
                <a href="admin/admin.php">← Back to dashboard</a>
            </div>
        </div>
    </div>

    <div class="form-right">
        <h2>Add pet for adoption</h2>
        <p>Fill in the pet's details. Fields marked as optional can be left blank.</p>

        <form action="../add_pet_process.php" method="POST" enctype="multipart/form-data">

            <fieldset id="pet-info">
                <legend>Pet information</legend>
                <div class="row">
                    <div class="col form-group">
                        <label for="pet_name">Pet name / description</label>
                        <input type="text" id="pet_name" name="pet_name" placeholder="e.g., Buddy / White Cat" required>
                    </div>
                    <div class="col form-group">
                        <label for="pet_species">Animal type</label>
                        <input type="text" id="pet_species" name="pet_species" placeholder="e.g., Dog, Cat, Bird" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col form-group">
                        <label for="breed">Breed</label>
                        <input type="text" id="breed" name="breed" placeholder="e.g., Aspin, Shih Tzu, Domestic Short Hair" required>
                    </div>
                    <div class="col form-group">
                        <label for="age">Estimated age</label>
                        <input type="text" id="age" name="age" placeholder="e.g., 5 Months / 2 Years" required>
                    </div>
                </div>
            </fieldset>

            <fieldset id="photo-location">
                <legend>Photo &amp; location</legend>
                <div class="row">
                    <div class="col form-group">
                        <label for="barangay">Barangay location</label>
                        <input type="text" id="barangay" name="barangay" placeholder="e.g., Brgy. Dau, Mabalacat City" required>
                    </div>
                    <div class="col form-group">
                        <label for="pet_photo">Pet photo</label>
                        <input type="file" id="pet_photo" name="pet_photo" accept="image/*" required>
                    </div>
                </div>
            </fieldset>

            <fieldset id="reason-status">
                <legend>Reason &amp; status</legend>
                <div class="form-group">
                    <label for="adoption_reason">Reason for adoption</label>
                    <input type="text" id="adoption_reason" name="adoption_reason" placeholder="e.g., Relocating, financial constraints, unable to care for" required>
                </div>
                <div class="form-group">
                    <label for="health_issues">Health issues / condition (optional)</label>
                    <input type="text" id="health_issues" name="health_issues" placeholder="e.g., Fully vaccinated, injured leg, none">
                </div>
                <div class="form-group">
                    <label for="pet_details">Additional details / contact info</label>
                    <textarea id="pet_details" name="pet_details" rows="3" placeholder="Anything else interested adopters should know, or how to reach you"></textarea>
                </div>
            </fieldset>

            <button type="submit" class="btn-submit">Submit pet listing</button>
        </form>
    </div>

</div>

</body>
</html>