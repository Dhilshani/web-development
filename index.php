<?php
require_once "config.php";

$services = $conn->query("SELECT * FROM services LIMIT 6");

$doctors = $conn->query("
    SELECT d.*, u.full_name
    FROM doctors d
    JOIN users u ON d.user_id = u.id
    ORDER BY d.rating DESC
    LIMIT 3
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>MediCare Plus</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<?php include "header.php"; ?>

<section class="hero">

    <div>
        <span class="badge">Trusted Digital Healthcare</span>

        <h1>Healthcare that puts you first.</h1>

        <p>
            Find trusted specialists, manage appointments and
            access your healthcare information securely from one place.
        </p>

        <a href="doctors.php" class="btn">Find a Doctor</a>
        <a href="services.php" class="btn btn-green">Explore Services</a>

        <form action="doctors.php" method="GET" class="search-box">

            <input
                type="text"
                name="search"
                placeholder="Search doctor or speciality"
            >

            <input
                type="text"
                name="location"
                placeholder="Location"
            >

            <button class="btn" type="submit">Search</button>

        </form>
    </div>

    <div class="hero-card">
        <h2>Your health, organised.</h2>
        <p>
            Book appointments, communicate with doctors and
            manage healthcare information through one secure portal.
        </p>

        <hr><br>

        <p><strong>✓</strong> Verified healthcare professionals</p>
        <p><strong>✓</strong> Simple appointment booking</p>
        <p><strong>✓</strong> Secure patient portal</p>
        <p><strong>✓</strong> Digital healthcare management</p>
    </div>

</section>

<section class="section">

<div class="container">

    <div class="section-title">
        <h2>Our Healthcare Services</h2>
        <p>Professional healthcare services designed around your needs.</p>
    </div>

    <div class="grid">

        <?php while ($service = $services->fetch_assoc()): ?>

            <div class="card">

                <div class="service-icon">
                    <?= e($service['icon']) ?>
                </div>

                <h3><?= e($service['name']) ?></h3>

                <p><?= e($service['description']) ?></p>

            </div>

        <?php endwhile; ?>

    </div>

</div>

</section>

<section class="section">

<div class="container">

    <div class="section-title">
        <h2>Featured Specialists</h2>
        <p>Explore our healthcare professionals.</p>
    </div>

    <div class="grid">

        <?php while ($doctor = $doctors->fetch_assoc()): ?>

            <div class="card doctor-card">

                <span class="badge">
                    Available Specialist
                </span>

                <h3><?= e($doctor['full_name']) ?></h3>

                <p>
                    <?= e($doctor['specialization']) ?>
                </p>

                <p>
                    <?= e($doctor['qualification']) ?>
                </p>

                <p>
                    <?= e($doctor['experience']) ?> years experience
                </p>

                <div class="rating">
                    ★ <?= e($doctor['rating']) ?>/5
                </div>

                <p>
                    Consultation:
                    LKR <?= number_format($doctor['consultation_fee'], 2) ?>
                </p>

                <br>

                <a
                    href="book_appointment.php?doctor=<?= $doctor['id'] ?>"
                    class="btn"
                >
                    Book Appointment
                </a>

            </div>

        <?php endwhile; ?>

    </div>

</div>

</section>

<section class="section">

<div class="container">

    <div class="card">

        <h2>Emergency Care</h2>

        <p>
            For medical emergencies, please contact the appropriate
            emergency service or visit the nearest emergency department.
        </p>

        <br>

        <a href="tel:1990" class="btn btn-green">
            Emergency Assistance
        </a>

    </div>

</div>

</section>

<footer class="footer">

<div class="footer-grid">

    <div>
        <h2>MediCare Plus</h2>
        <p>
            A modern healthcare management platform
            designed around patients and professionals.
        </p>
    </div>

    <div>
        <h3>Quick Links</h3>
        <p>Doctors</p>
        <p>Services</p>
        <p>Appointments</p>
    </div>

    <div>
        <h3>Contact</h3>
        <p>Colombo, Sri Lanka</p>
        <p>support@medicareplus.local</p>
    </div>

</div>

</footer>

</body>
</html>