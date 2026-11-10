<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PAWFINDER - Community Pet System</title>
   <link rel="stylesheet" href="css/homepage.css">
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
            <li class="nav-item"><a href="#home">Home</a></li>
            <li class="nav-item"><a href="adopt.php">Adopt Pets</a></li>
            <li class="nav-item"><a href="rescue.php">Emergency Rescues</a></li>
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
            <h1>Welcome to Pawfinder!</h1>
            <p>Creating a safer community by connecting rescued pets with responsible and loving owners</p>
        </div>

        <section class="partners-section">
            <div class="partners-header">
                <h2>Our Partner Shelters</h2>
                <p>
                    When rescued animals or pets available for adoption remain unadopted for an extended period, 
                    they are safely transferred to larger partner facilities for long-term shelter, rehabilitation, and specialized care.
                </p>
            </div>

            <div class="shelter-cards-grid">
                <div class="shelter-card">
                    <div>
                        <img src="image/afk.jpg" alt="AKF Animal Rescue" class="shelter-img">
                        <div class="shelter-name">Animal Kingdom Foundation (AKF)</div>
                        <div class="shelter-meta">★ 4.5 (2 reviews) • Verified</div>
                        <div class="shelter-category">Animal Shelters • Community Service / Non-Profit</div>
                        <div class="shelter-info">
                            <strong>Address:</strong> No. 8, Purante Street, Cub-Cub, Capas, Tarlac<br>
                            <strong>Contact:</strong> +63 939 914 2403
                        </div>
                    </div>
                    <a href="https://animalkingdomfoundation.org" target="_blank" class="shelter-link">Visit Website / Page →</a>
                </div>

                <div class="shelter-card">
                    <div>
                        <img src="image/part.jpg" alt="Philippine Animal Rescue Team" class="shelter-img">
                        <div class="shelter-name">Philippine Animal Rescue Team (PART)</div>
                        <div class="shelter-meta">★ 4.8 • Verified Sanctuary</div>
                        <div class="shelter-category">No-Kill Animal Shelter • Rehabilitation Center</div>
                        <div class="shelter-info">
                            <strong>Address:</strong> San Jose del Monte, Bulacan<br>
                            <strong>Contact:</strong> +63 917 859 2928
                        </div>
                    </div>
                    <a href="https://www.philippineanimalrescueteam.org" target="_blank" class="shelter-link">Visit Website / Page →</a>
                </div>

                <div class="shelter-card">
                    <div>
                        <img src="image/paw.jpg" alt="PAWS Philippines" class="shelter-img">
                        <div class="shelter-name">Philippine Animal Welfare Society (PAWS)</div>
                        <div class="shelter-meta">★ 4.9 • Verified Organization</div>
                        <div class="shelter-category">Animal Welfare • Spay & Neuter Clinic</div>
                        <div class="shelter-info">
                            <strong>Address:</strong> Aurora Blvd., Katipunan Valley, Loyola Heights, Quezon City<br>
                            <strong>Contact:</strong> +63 2 8475 1686
                        </div>
                    </div>
                    <a href="https://paws.org.ph" target="_blank" class="shelter-link">Visit Website / Page →</a>
                </div>
            </div>
        </section>

        <section class="layer-slider-section" id="dogs-section">
            <h2 class="section-header-title">Dogs for Adoption</h2>
            <div class="slider-wrapper">
                <div class="slider-container">
                    <div class="pet-card">
                        <img src="image/dog1.jpg" alt="Dog" class="pet-card-img">
                        <div class="pet-card-title">Pet Name</div>
                        <div class="pet-card-sub">Gender, Breed</div>
                        <div class="pet-card-shelter">Shelter Name</div>
                        <button class="pet-card-btn">Pet Details &gt;</button>
                    </div>
                    <div class="pet-card">
                        <img src="image/dog2.jpg" alt="Dog" class="pet-card-img">
                        <div class="pet-card-title">Pet Name</div>
                        <div class="pet-card-sub">Gender, Breed</div>
                        <div class="pet-card-shelter">Shelter Name</div>
                        <button class="pet-card-btn">Pet Details &gt;</button>
                    </div>
                    <div class="pet-card">
                        <img src="image/dog3.jpg" alt="Dog" class="pet-card-img">
                        <div class="pet-card-title">Pet Name</div>
                        <div class="pet-card-sub">Gender, Breed</div>
                        <div class="pet-card-shelter">Shelter Name</div>
                        <button class="pet-card-btn">Pet Details &gt;</button>
                    </div>
                    <div class="pet-card">
                        <img src="image/dog4.jpg" alt="Dog" class="pet-card-img">
                        <div class="pet-card-title">Pet Name</div>
                        <div class="pet-card-sub">Gender, Breed</div>
                        <div class="pet-card-shelter">Shelter Name</div>
                        <button class="pet-card-btn">Pet Details &gt;</button>
                    </div>
                    <div class="pet-card">
                        <img src="image/dog5.jpg" alt="Dog" class="pet-card-img">
                        <div class="pet-card-title">Pet Name</div>
                        <div class="pet-card-sub">Gender, Breed</div>
                        <div class="pet-card-shelter">Shelter Name</div>
                        <button class="pet-card-btn">Pet Details &gt;</button>
                    </div>
                </div>
            </div>
        </section>

        <section class="layer-slider-section" id="cats-section">
            <h2 class="section-header-title">Cats for Adoption</h2>
            <div class="slider-wrapper">
                <div class="slider-container">
                    <div class="pet-card">
                        <img src="image/cat1.jpg" alt="Cat" class="pet-card-img">
                        <div class="pet-card-title">Pet Name</div>
                        <div class="pet-card-sub">Gender, Breed</div>
                        <div class="pet-card-shelter">Shelter Name</div>
                        <button class="pet-card-btn">Pet Details &gt;</button>
                    </div>
                    <div class="pet-card">
                        <img src="image/cat2.jpg" alt="Cat" class="pet-card-img">
                        <div class="pet-card-title">Pet Name</div>
                        <div class="pet-card-sub">Gender, Breed</div>
                        <div class="pet-card-shelter">Shelter Name</div>
                        <button class="pet-card-btn">Pet Details &gt;</button>
                    </div>
                    <div class="pet-card">
                        <img src="image/cat3.jpg" alt="Cat" class="pet-card-img">
                        <div class="pet-card-title">Pet Name</div>
                        <div class="pet-card-sub">Gender, Breed</div>
                        <div class="pet-card-shelter">Shelter Name</div>
                        <button class="pet-card-btn">Pet Details &gt;</button>
                    </div>
                    <div class="pet-card">
                        <img src="image/cat4.jpg" alt="Cat" class="pet-card-img">
                        <div class="pet-card-title">Pet Name</div>
                        <div class="pet-card-sub">Gender, Breed</div>
                        <div class="pet-card-shelter">Shelter Name</div>
                        <button class="pet-card-btn">Pet Details &gt;</button>
                    </div>
                    <div class="pet-card">
                        <img src="image/cat5.jpg" alt="Cat" class="pet-card-img">
                        <div class="pet-card-title">Pet Name</div>
                        <div class="pet-card-sub">Gender, Breed</div>
                        <div class="pet-card-shelter">Shelter Name</div>
                        <button class="pet-card-btn">Pet Details &gt;</button>
                    </div>
                </div>
            </div>
        </section>

        <section class="layer-slider-section" id="birds-section">
            <h2 class="section-header-title">Birds for Adoption</h2>
            <div class="slider-wrapper">
                <div class="slider-container">
                    <div class="pet-card">
                        <img src="image/bird1.jpg" alt="Bird" class="pet-card-img">
                        <div class="pet-card-title">Pet Name</div>
                        <div class="pet-card-sub">Gender, Breed</div>
                        <div class="pet-card-shelter">Shelter Name</div>
                        <button class="pet-card-btn">Pet Details &gt;</button>
                    </div>
                    <div class="pet-card">
                        <img src="image/bird2.jpg" alt="Bird" class="pet-card-img">
                        <div class="pet-card-title">Pet Name</div>
                        <div class="pet-card-sub">Gender, Breed</div>
                        <div class="pet-card-shelter">Shelter Name</div>
                        <button class="pet-card-btn">Pet Details &gt;</button>
                    </div>
                    <div class="pet-card">
                        <img src="image/bird3.jpg" alt="Bird" class="pet-card-img">
                        <div class="pet-card-title">Pet Name</div>
                        <div class="pet-card-sub">Gender, Breed</div>
                        <div class="pet-card-shelter">Shelter Name</div>
                        <button class="pet-card-btn">Pet Details &gt;</button>
                    </div>
                    <div class="pet-card">
                        <img src="image/bird4.jpg" alt="Bird" class="pet-card-img">
                        <div class="pet-card-title">Pet Name</div>
                        <div class="pet-card-sub">Gender, Breed</div>
                        <div class="pet-card-shelter">Shelter Name</div>
                        <button class="pet-card-btn">Pet Details &gt;</button>
                    </div>
                    <div class="pet-card">
                        <img src="image/bird5.jpg" alt="Bird" class="pet-card-img">
                        <div class="pet-card-title">Pet Name</div>
                        <div class="pet-card-sub">Gender, Breed</div>
                        <div class="pet-card-shelter">Shelter Name</div>
                        <button class="pet-card-btn">Pet Details &gt;</button>
                    </div>
                </div>
            </div>
        </section>

        <section class="layer-slider-section" id="medication-section">
            <h2 class="section-header-title">Free Medicine & Vaccination Schedule</h2>
            <table class="med-schedule-table">
                <thead>
                    <tr>
                        <th>Date & Time</th>
                        <th>Location / Barangay</th>
                        <th>Services Offered</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>-----------</td>
                        <td>-----------</td>
                        <td>-----------</td>
                        <td>-----------</td>
                    </tr>
                    <tr>
                        <td>-----------</td>
                        <td>-----------</td>
                        <td>-----------</td>
                        <td>-----------</td>
                    </tr>
                    <tr>
                        <td>-----------</td>
                        <td>-----------</td>
                        <td>-----------</td>
                        <td>-----------</td>
                    </tr>
                </tbody>
            </table>
        </section>

        <section class="layer-slider-section" id="missing-pets-section">
            <h2 class="section-header-title">Missing & Found Stray Pets (Reported Outside)</h2>
            <div class="slider-wrapper">
                <div class="slider-container">
                    <div class="pet-card">
                        <img src="image/stray1.jpg" alt="Missing Pet" class="pet-card-img">
                        <div class="pet-card-title">Stray / Found</div>
                        <div class="pet-card-sub">Gender, Breed</div>
                        <div class="pet-card-shelter">Barangay: -----------</div>
                        <button class="pet-card-btn">View Details &gt;</button>
                    </div>
                    <div class="pet-card">
                        <img src="image/stray2.jpg" alt="Missing Pet" class="pet-card-img">
                        <div class="pet-card-title">Stray / Found</div>
                        <div class="pet-card-sub">Gender, Breed</div>
                        <div class="pet-card-shelter">Barangay: -----------</div>
                        <button class="pet-card-btn">View Details &gt;</button>
                    </div>
                    <div class="pet-card">
                        <img src="image/stray3.jpg" alt="Missing Pet" class="pet-card-img">
                        <div class="pet-card-title">Stray / Found</div>
                        <div class="pet-card-sub">Gender, Breed</div>
                        <div class="pet-card-shelter">Barangay: -----------</div>
                        <button class="pet-card-btn">View Details &gt;</button>
                    </div>
                </div>
            </div>
        </section>

        <section class="post-pet-section" id="post-pet-form">
            <h2 class="section-header-title" style="margin-bottom: 8px;">Post a Pet for Adoption / Report Stray Pet Sighting</h2>
            <p style="color: #64748b; font-size: 14px; margin-bottom: 20px;">
                Fill out the details below to list a pet for adoption or report an animal you found roaming outside in your barangay.
            </p>
            <form onsubmit="event.preventDefault();">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="postType">Listing Category</label>
                        <select id="postType">
                            <option value="adoption">Pet for Adoption</option>
                            <option value="stray">Found Stray / Missing Pet Outside</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="petName">Pet Name or Description</label>
                        <input type="text" id="petName" placeholder="e.g., Brown Aspin / White Cat">
                    </div>
                    <div class="form-group">
                        <label for="petSpecies">Animal Type</label>
                        <select id="petSpecies">
                            <option value="dog">Dog</option>
                            <option value="cat">Cat</option>
                            <option value="bird">Bird</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="barangayLocation">Barangay Location</label>
                        <input type="text" id="barangayLocation" placeholder="e.g., Brgy. Sto. Rosario">
                    </div>
                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label for="petDetails">Additional Notes / Sighting Details</label>
                        <textarea id="petDetails" rows="3" placeholder="Describe the pet's condition, exact street location where found, or contact details..."></textarea>
                    </div>
                </div>
                <button type="submit" class="form-submit-btn">Submit Listing / Report</button>
            </form>
        </section>
    </main>

</body>
</html>