<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pawfinder - Report Missing or Found Stray Pet</title>
    <link rel="stylesheet" href="css/report.css">
</head>
<body>

<div class="register-card">
    
    <div class="sidebar-left">
        <div>
            <div class="brand-logo">🐾 PAWFINDER</div>
            
            <ul class="step-list">
                <li class="step-item active">○ Report Category</li>
                <li class="step-item">○ Sighting & Location</li>
                <li class="step-item">○ Pet Photo & Details</li>
                <li class="step-item">○ Submit Report</li>
            </ul>
        </div>

        <div>
            <div class="sidebar-img-container">
                <img src="image/animal.jpg" alt="Report Stray" onerror="this.src='https://placehold.co/160x120/1e293b/2dd4bf?text=Report+Stray'">
            </div>
            
            <div class="back-to-login">
                <a href="homepage.php">← Back to Dashboard</a>
            </div>
        </div>
    </div>

    <div class="form-right">
        <h2>Report Missing / Found Stray Pet</h2>
        <p>Help connect lost pets with their owners or report stray animal sightings around your neighborhood.</p>

        <form action="add_stray_process.php" method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label>Report Type / Status</label>
                        <input type="text" name="report_type" placeholder="e.g., Missing Pet / Found Stray" required>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label>Pet Name or Title</label>
                        <input type="text" name="pet_name" placeholder="e.g., Brown Aspin / Lost Golden Retriever" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label>Animal Type</label>
                        <input type="text" name="pet_species" placeholder="e.g., Dog, Cat, Bird" required>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label>Color & Distinct Markings</label>
                        <input type="text" name="markings" placeholder="e.g., Black fur with white chest, red collar" required>
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
                        <label>Last Seen Location / Landmark</label>
                        <input type="text" name="landmark" placeholder="e.g., Near Elementary School / Main Market" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label>Date Lost or Found</label>
                        <input type="date" name="sighting_date" required>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label>Upload Sighting Photo</label>
                        <input type="file" name="stray_photo" accept="image/*" required>
                    </div>
                </div>
            </div>

            
            <div class="form-group">
                <label>Reporter Contact Number / Email</label>
                <input type="text" name="reporter_contact" placeholder="e.g., 09123456789 / email@gmail.com" required>
            </div>

           
            <div class="form-group">
                <label>Additional Notes / Medical Condition</label>
                <textarea name="stray_details" rows="3" placeholder="Describe the pet's condition, whether friendly or scared, current temp shelter status, etc."></textarea>
            </div>

            <button type="submit" class="btn-submit">Submit Stray Report</button>

        </form>
    </div>

</div>

</body>
</html>