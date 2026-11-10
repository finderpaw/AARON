<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pawfinder - Add Vaccination & Health Schedule</title>
    <link rel="stylesheet" href="css/addschedule.css">
</head>
<body>

<div class="register-card">
    
    <div class="sidebar-left">
        <div>
            <div class="brand-logo">🐾 PAWFINDER</div>
            
            <ul class="step-list">
                <li class="step-item active">○ Date & Time</li>
                <li class="step-item">○ Venue & Location</li>
                <li class="step-item">○ Services & Status</li>
                <li class="step-item">○ Publish Schedule</li>
            </ul>
        </div>

        <div>
            <div class="sidebar-img-container">
                <img src="image/animal.jpg" alt="Health Drive" onerror="this.src='https://placehold.co/160x120/1e293b/2dd4bf?text=Health+Drive'">
            </div>
            
            <div class="back-to-login">
                <a href="admin.php">← Back to Dashboard</a>
            </div>
        </div>
    </div>


    <div class="form-right">
        <h2>Add Health & Vaccination Event</h2>
        <p>Set up a new schedule for free pet vaccinations, anti-rabies shots, and medical checkups in your barangay.</p>

        <form action="add_schedule_process.php" method="POST">
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label>Event Date</label>
                        <input type="date" name="event_date" required>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label>Start & End Time</label>
                        <input type="text" name="event_time" placeholder="e.g., 8:00 AM - 12:00 PM" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label>Location / Barangay</label>
                        <input type="text" name="barangay" placeholder="e.g., Brgy. Dau, Mabalacat City" required>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label>Specific Venue / Covered Court</label>
                        <input type="text" name="venue" placeholder="e.g., Community Covered Court" required>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Services Offered</label>
                <div class="checkbox-group">
                    <label class="checkbox-item">
                        <input type="checkbox" name="services[]" value="Anti-Rabies Vaccine" checked> Anti-Rabies Vaccine
                    </label>
                    <label class="checkbox-item">
                        <input type="checkbox" name="services[]" value="Free Deworming"> Free Deworming
                    </label>
                    <label class="checkbox-item">
                        <input type="checkbox" name="services[]" value="5-in-1 / 4-in-1 Vaccine"> 5-in-1 / 4-in-1 Vaccine
                    </label>
                    <label class="checkbox-item">
                        <input type="checkbox" name="services[]" value="Free Vitamin Supplements"> Vitamin Supplements
                    </label>
                    <label class="checkbox-item">
                        <input type="checkbox" name="services[]" value="Basic Health Checkup"> Basic Checkup
                    </label>
                    <label class="checkbox-item">
                        <input type="checkbox" name="services[]" value="Spay & Neuter Consultation"> Spay/Neuter Info
                    </label>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label>Schedule Status</label>
                        <input type="text" name="status" placeholder="e.g., Upcoming, Ongoing, Completed" required>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label>Organizer / Veterinary In-Charge</label>
                        <input type="text" name="organizer" placeholder="e.g., City Veterinary Office" required>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Reminders & Guidelines for Pet Owners</label>
                <textarea name="reminders" rows="3" placeholder="e.g., Bring pet vaccination card, keep dogs on leash, cats in secure cages..."></textarea>
            </div>

            <button type="submit" class="btn-submit">Publish Schedule</button>

        </form>
    </div>

</div>

</body>
</html>