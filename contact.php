<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - PAWFINDER Community Pet System</title>
    <link rel="stylesheet" href="css/homepage.css">
    <link rel="stylesheet" href="css/contact.css">
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
        <div class="hero-banner hero-photos" style="background-image: url('image/casting-Picture-Background-low-res.webp');">
            <div class="hero-photo-overlay"></div>
            <div class="hero-photo-content">
                <h1>Contact Pawfinder</h1>
                <p>Questions, rescue concerns, or want to partner with us? We're here to help your community's pets.</p>
            </div>
        </div>

        <!-- QUICK CONTACT CARDS -->
        <section class="partners-section">
            <div class="partners-header">
                <h2>Get in Touch</h2>
                <p>
                    Reach the Pawfinder team directly through any of the channels below.
                    For animals in immediate danger, please call our emergency rescue hotline right away.
                </p>
            </div>

            <div class="shelter-cards-grid contact-cards-two">
                <div class="shelter-card">
                    <div>
                        <div class="contact-icon">📞</div>
                        <div class="shelter-name">Emergency Rescue Hotline</div>
                        <div class="shelter-meta">★ Available 24/7 • Verified</div>
                        <div class="shelter-category">Rescue Dispatch • Urgent Cases</div>
                        <div class="shelter-info">
                            <strong>Hotline:</strong> +63 967 984 9940<br>  
                            <strong>Response:</strong> Within 30 minutes
                        </div>
                    </div>
                    <a href="tel:+639679849940" class="shelter-link">Call Now →</a>
                </div>

                <div class="shelter-card">
                    <div>
                        <div class="contact-icon">✉️</div>
                        <div class="shelter-name">Email Support</div>
                        <div class="shelter-meta">★ Replies within 24 hours</div>
                        <div class="shelter-category">General Inquiries • Adoption Help</div>
                        <div class="shelter-info">
                            <strong>Email:</strong> support@pawfinder.com<br>
                            <strong>Hours:</strong> Mon – Sat, 8:00 AM – 6:00 PM
                        </div>
                    </div>
                    <a href="mailto:support@pawfinder.com" class="shelter-link">Send an Email →</a>
                </div>

            </div>
        </section>

        <!-- SOCIAL MEDIA -->
        <?php
        // Edit the URLs and handles below with Pawfinder's real accounts.
        $social_links = [
            [
                'name'   => 'Facebook',
                'handle' => '@pawfinderph',
                'desc'   => 'Rescue updates, adoption posts, and community announcements.',
                'url'    => 'https://www.facebook.com/pawfinderph',
                'badge'  => 'f',
                'class'  => 'social-facebook',
            ],
            [
                'name'   => 'Instagram',
                'handle' => '@pawfinderph',
                'desc'   => 'Photos of pets looking for homes and happy adoption stories.',
                'url'    => 'https://www.instagram.com/pawfinderph',
                'badge'  => 'IG',
                'class'  => 'social-instagram',
            ],
            [
                'name'   => 'TikTok',
                'handle' => '@pawfinderph',
                'desc'   => 'Short rescue stories and tips on caring for your pets.',
                'url'    => 'https://www.tiktok.com/@pawfinderph',
                'badge'  => 'TT',
                'class'  => 'social-tiktok',
            ],
        ];
        ?>
        <section class="partners-section" id="social-section">
            <div class="partners-header">
                <h2>Follow Pawfinder</h2>
                <p>
                    Stay connected with our community. Follow us on social media for the latest rescues,
                    pets up for adoption, and free health drive announcements in your barangay.
                </p>
            </div>

            <div class="social-cards-grid">
                <?php foreach ($social_links as $social): ?>
                    <a href="<?= htmlspecialchars($social['url']) ?>" target="_blank" rel="noopener noreferrer" class="social-card">
                        <span class="social-badge <?= htmlspecialchars($social['class']) ?>"><?= htmlspecialchars($social['badge']) ?></span>
                        <div class="social-text">
                            <div class="shelter-name"><?= htmlspecialchars($social['name']) ?></div>
                            <div class="shelter-meta"><?= htmlspecialchars($social['handle']) ?></div>
                            <div class="social-desc"><?= htmlspecialchars($social['desc']) ?></div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- CONTACT FORM -->
        <section class="post-pet-section" id="contact-form">
            <h2 class="section-header-title" style="margin-bottom: 8px;">Send Us a Message</h2>
            <p style="color: #64748b; font-size: 14px; margin-bottom: 20px;">
                Fill out the form below and our team will get back to you as soon as possible.
            </p>
            <form action="contact_submit.php" method="POST">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="fullName">Full Name</label>
                        <input type="text" id="fullName" name="full_name" placeholder="e.g., Juan Dela Cruz" required>
                    </div>
                    <div class="form-group">
                        <label for="emailAddress">Email Address</label>
                        <input type="email" id="emailAddress" name="email" placeholder="e.g., juan@email.com" required>
                    </div>
                    <div class="form-group">
                        <label for="contactNumber">Contact Number</label>
                        <input type="text" id="contactNumber" name="contact_number" placeholder="e.g., 0917 123 4567">
                    </div>
                    <div class="form-group">
                        <label for="inquiryType">Inquiry Type</label>
                        <select id="inquiryType" name="inquiry_type">
                            <option value="general">General Inquiry</option>
                            <option value="adoption">Adoption Question</option>
                            <option value="rescue">Emergency Rescue Concern</option>
                            <option value="health">Health Drive / Vaccination</option>
                            <option value="partner">Shelter Partnership</option>
                            <option value="report">Report a Problem / Listing</option>
                        </select>
                    </div>
                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label for="subject">Subject</label>
                        <input type="text" id="subject" name="subject" placeholder="Brief summary of your message">
                    </div>
                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label for="message">Message</label>
                        <textarea id="message" name="message" rows="5" placeholder="Tell us how we can help. For rescue concerns, include the barangay and exact street location..." required></textarea>
                    </div>
                </div>
                <button type="submit" class="form-submit-btn">Send Message</button>
            </form>
        </section>

        <!-- FAQ -->
        <section class="layer-slider-section" id="faq-section">
            <h2 class="section-header-title">Frequently Asked Questions</h2>
            <div class="faq-list">
                <details class="faq-item">
                    <summary>How do I adopt a pet through Pawfinder?</summary>
                    <p>Browse the Dogs, Cats, and Birds for Adoption sections, open a pet's details, and send an adoption request. You'll need to register an Adopter account first.</p>
                </details>
                <details class="faq-item">
                    <summary>I found a stray animal. What should I do?</summary>
                    <p>Use the "Report Stray Pet Sighting" form on the homepage with the barangay and exact location. If the animal is injured, call the emergency rescue hotline immediately.</p>
                </details>
                <details class="faq-item">
                    <summary>Are the vaccination drives really free?</summary>
                    <p>Yes. Free medicine and vaccination schedules are posted in the Health Drives section, including the barangay, date, time, and services offered.</p>
                </details>
                <details class="faq-item">
                    <summary>How can my shelter or rescue group become a partner?</summary>
                    <p>Select "Shelter Partnership" in the contact form above and share your organization's details. Our team will review and verify your application.</p>
                </details>
            </div>
        </section>

        <!-- MAP -->
        <section class="layer-slider-section" id="map-section">
            <h2 class="section-header-title">Find Us</h2>
            <div class="map-wrapper">
                <iframe
                    title="Pawfinder office location"
                    src="https://www.google.com/maps?q=Angeles+City,+Pampanga&output=embed"
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"
                    allowfullscreen>
                </iframe>
            </div>
        </section>
    </main>

</body>
</html>