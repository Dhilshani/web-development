<?php require_once "config.php"; ?>

<header class="navbar">
    <a href="index.php" class="logo">
        MediCare <span>Plus</span>
    </a>

    <nav class="nav-links">
        <a href="index.php">Home</a>
        <a href="doctors.php">Doctors</a>
        <a href="services.php">Services</a>

        <?php if (isLoggedIn()): ?>

            <?php if ($_SESSION['role'] === 'patient'): ?>
                <a href="dashboard.php">Dashboard</a>
                <a href="my_appointments.php">Appointments</a>
            <?php elseif ($_SESSION['role'] === 'doctor'): ?>
                <a href="doctor_dashboard.php">Doctor Portal</a>
            <?php elseif ($_SESSION['role'] === 'admin'): ?>
                <a href="admin_dashboard.php">Admin Portal</a>
            <?php endif; ?>

            <a href="logout.php" class="btn">Logout</a>

        <?php else: ?>

            <a href="login.php">Login</a>
            <a href="register.php" class="btn btn-green">Register</a>

        <?php endif; ?>
    </nav>
</header>