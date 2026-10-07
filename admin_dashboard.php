<?php

require_once "config.php";

requireRole("admin");

$patients = $conn->query(
    "SELECT COUNT(*) AS total FROM users WHERE role='patient'"
)->fetch_assoc()["total"];

$doctors = $conn->query(
    "SELECT COUNT(*) AS total FROM users WHERE role='doctor'"
)->fetch_assoc()["total"];

$appointments = $conn->query(
    "SELECT COUNT(*) AS total FROM appointments"
)->fetch_assoc()["total"];

$services = $conn->query(
    "SELECT COUNT(*) AS total FROM services"
)->fetch_assoc()["total"];

?>

<!DOCTYPE html>

<html>

<head>

<title>Admin Dashboard</title>

<link rel="stylesheet" href="style.css">

</head>

<body>

<?php include "header.php"; ?>

<section class="dashboard">

<div class="container">

<h1>Administrator Dashboard</h1>

<p>
System overview and management centre.
</p>

<br>

<div class="dashboard-cards">

<div class="stat-card">

<h2><?= $patients ?></h2>

<p>Registered Patients</p>

</div>

<div class="stat-card">

<h2><?= $doctors ?></h2>

<p>Doctors</p>

</div>

<div class="stat-card">

<h2><?= $appointments ?></h2>

<p>Appointments</p>

</div>

<div class="stat-card">

<h2><?= $services ?></h2>

<p>Healthcare Services</p>

</div>

</div>

<div class="grid">

<div class="card">

<h2>User Management</h2>

<p>
Manage patient and doctor accounts.
</p>

</div>

<div class="card">

<h2>Doctor Management</h2>

<p>
Manage specialist profiles and availability.
</p>

</div>

<div class="card">

<h2>Appointment Management</h2>

<p>
Monitor and manage appointment records.
</p>

</div>

</div>

</div>

</section>

</body>

</html>