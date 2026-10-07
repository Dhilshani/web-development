<?php

require_once "config.php";

requireRole("doctor");

$stmt = $conn->prepare("
SELECT id
FROM doctors
WHERE user_id = ?
");

$stmt->bind_param("i", $_SESSION["user_id"]);

$stmt->execute();

$doctorResult = $stmt->get_result();

$doctor = $doctorResult->fetch_assoc();

if (!$doctor) {
    die("Doctor profile not found.");
}

$doctorId = $doctor["id"];

$stmt = $conn->prepare("
SELECT
    a.*,
    u.full_name AS patient_name
FROM appointments a
JOIN users u ON a.patient_id = u.id
WHERE a.doctor_id = ?
ORDER BY a.appointment_date,
         a.appointment_time
");

$stmt->bind_param("i", $doctorId);

$stmt->execute();

$appointments = $stmt->get_result();

?>

<!DOCTYPE html>

<html>

<head>

<title>Doctor Portal</title>

<link rel="stylesheet" href="style.css">

</head>

<body>

<?php include "header.php"; ?>

<section class="dashboard">

<div class="container">

<h1>
Doctor Portal
</h1>

<p>
Welcome, <?= e($_SESSION["full_name"]) ?>
</p>

<br>

<div class="dashboard-cards">

<div class="stat-card">
<h2>+</h2>
<p>Appointments</p>
</div>

<div class="stat-card">
<h2>✓</h2>
<p>Patient Care</p>
</div>

<div class="stat-card">
<h2>★</h2>
<p>Professional Profile</p>
</div>

<div class="stat-card">
<h2>✉</h2>
<p>Messages</p>
</div>

</div>

<h2>Appointment Schedule</h2>

<br>

<div class="table-container">

<table>

<tr>

<th>Patient</th>
<th>Date</th>
<th>Time</th>
<th>Reason</th>
<th>Status</th>

</tr>

<?php while ($row = $appointments->fetch_assoc()): ?>

<tr>

<td><?= e($row["patient_name"]) ?></td>

<td><?= e($row["appointment_date"]) ?></td>

<td><?= e($row["appointment_time"]) ?></td>

<td><?= e($row["reason"]) ?></td>

<td><?= e(ucfirst($row["status"])) ?></td>

</tr>

<?php endwhile; ?>

</table>

</div>

</div>

</section>

</body>

</html>