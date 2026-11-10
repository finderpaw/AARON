<?php
session_start();

$conn = new mysqli("localhost", "root", "", "pawfinder_db");

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $reporter_name = trim($_POST["reporter_name"] ?? "");
    $contact_number = trim($_POST["contact_number"] ?? "");
    $location = trim($_POST["location"] ?? "");
    $pet_type = trim($_POST["pet_type"] ?? "");
    $pet_condition = trim($_POST["pet_condition"] ?? "");
    $description = trim($_POST["description"] ?? "");

    if (
        $reporter_name === "" || $contact_number === "" ||
        $location === "" || $pet_type === "" ||
        $pet_condition === "" || $description === ""
    ) {
        $error = "Please complete all fields.";
    } elseif (!preg_match("/^09[0-9]{9}$/", $contact_number)) {
        $error = "Please enter a valid 11-digit mobile number.";
    } else {
        $stmt = $conn->prepare(
            "INSERT INTO rescue_reports
            (reporter_name, contact_number, location, pet_type,
             pet_condition, description)
            VALUES (?, ?, ?, ?, ?, ?)"
        );

        $stmt->bind_param(
            "ssssss",
            $reporter_name,
            $contact_number,
            $location,
            $pet_type,
            $pet_condition,
            $description
        );

        if ($stmt->execute()) {
            $success = "Your rescue report has been submitted successfully!";
        } else {
            $error = "Unable to submit your report. Please try again.";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PAWFINDER - Emergency Rescues</title>
    <link rel="stylesheet" href="css/homepage.css">
    <link rel="stylesheet" href="css/rescue.css">
</head>
<body>

    <div class="top-bar">
        <div class="role-container">
            <span class="role-label">Current Role:</span>
            <select id="roleSwitcher" class="role-select">
                <option value="admin">Admin</option>
                <option value="guest" selected>Guest / Public Visitor</option>
                <option value="rescuer">Local Rescuers</option>
                <option value="adopter">Adopter</option>
            </select>
        </div>
    </div>

    <header class="navbar">
        <div class="brand-section">
            <img src="image/logo2.jpg" alt="PAWFINDER Logo" class="brand-logo-img">
            <div>
                <span class="brand-title">PAWFINDER</span>
                <span class="brand-subtitle">COMMUNITY PET SYSTEM</span>
            </div>
        </div>

        <ul class="nav-links">
            <li class="nav-item"><a href="homepage.php">Home</a></li>
            <li class="nav-item"><a href="adopt.php">Adopt Pets</a></li>
            <li class="nav-item"><a href="rescue.php" class="active">Emergency Rescues</a></li>
            <li class="nav-item"><a href="health_drives.php">Health Drives</a></li>
            <li class="nav-item"><a href="contact.php">Contact</a></li>
        </ul>

        <div class="auth-actions">
            <button class="btn-login" onclick="location.href='login.php'">Log In</button>
            <button class="btn-register" onclick="location.href='register.php'">Register</button>
        </div>
    </header>

    <main class="content-area">

        <div class="hero-banner">
            <h1>Every Life Deserves a Chance.</h1>
            <p>
                Help us protect animals in need. Report stray, abandoned,
                injured, or endangered pets in your community.
            </p>
            <a href="report.php" class="hero-button">Report a Pet</a>
        </div>

        <section class="partners-section">
            <div class="partners-header">
                <h2>Emergency Pet Rescues</h2>
                <p>
                    PAWFINDER encourages the community to take part in helping
                    animals that need immediate attention. Submit a report so
                    our team can review the situation and coordinate assistance.
                </p>
            </div>
        </section>

        <section class="post-pet-section" id="report-form">
            <h2 class="section-header-title" style="margin-bottom: 8px;">Report an Animal in Need</h2>
            <p style="color: #64748b; font-size: 14px; margin-bottom: 20px;">
                Provide accurate information about the animal and its location.
            </p>

            <?php if ($success !== ""): ?>
                <div class="message success">
                    <?= htmlspecialchars($success) ?>
                </div>
            <?php endif; ?>

            <?php if ($error !== ""): ?>
                <div class="message error">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="rescue.php">
                <div class="form-grid">

                    <div class="form-group">
                        <label for="reporter_name">Your Full Name</label>
                        <input type="text" id="reporter_name" name="reporter_name"
                               placeholder="Enter your full name" required>
                    </div>

                    <div class="form-group">
                        <label for="contact_number">Contact Number</label>
                        <input type="tel" id="contact_number" name="contact_number"
                               placeholder="09XXXXXXXXX" maxlength="11" required>
                    </div>

                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label for="location">Rescue Location</label>
                        <input type="text" id="location" name="location"
                               placeholder="Enter the exact location or address" required>
                    </div>

                    <div class="form-group">
                        <label for="pet_type">Type of Animal</label>
                        <select id="pet_type" name="pet_type" required>
                            <option value="">Select animal</option>
                            <option value="Dog">Dog</option>
                            <option value="Cat">Cat</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="pet_condition">Animal's Condition</label>
                        <select id="pet_condition" name="pet_condition" required>
                            <option value="">Select condition</option>
                            <option value="Injured">Injured</option>
                            <option value="Abandoned">Abandoned</option>
                            <option value="Stray">Stray</option>
                            <option value="Sick">Sick</option>
                            <option value="In Danger">In Danger</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>

                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label for="description">Description of the Situation</label>
                        <textarea id="description" name="description" rows="4"
                                  placeholder="Describe the animal and explain why it needs help..."
                                  required></textarea>
                    </div>

                </div>
                <button type="submit" class="form-submit-btn">Submit Rescue Report</button>
                <p class="form-note">Please make sure the information you provide is accurate.</p>
            </form>
        </section>

        <section class="partners-section" style="margin-top: 50px;">
            <div class="shelter-cards-grid">
                <div class="shelter-card rescue-reminder">
                    <div>
                        <div class="shelter-name">See an Animal in Danger?</div>
                        <div class="shelter-info">
                            Report its location and condition as soon as possible.
                            If the animal is in immediate danger, contact your local
                            animal welfare or emergency service as well.
                        </div>
                    </div>
                    <a href="contact.php" class="shelter-link">Contact Us →</a>
                </div>
            </div>
        </section>

    </main>

</body>
</html>

<?php $conn->close(); ?>