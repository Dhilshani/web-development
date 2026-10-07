<?php

require_once "config.php";

requireRole("patient");

$patientId = $_SESSION["user_id"];

$stmt = $conn->prepare("
SELECT
    a.*,
    u.full_name AS doctor_name,
    d.specialization
FROM appointments a
JOIN doctors d ON a.doctor_id = d.id
JOIN users u ON d.user_id = u.id
WHERE a.patient_id = ?
ORDER BY a.appointment_date DESC,
         a.appointment_time DESC
");

$stmt->bind_param("i", $patientId);

$stmt->execute();

$appointments = $stmt->get_result();

$reportStmt = $conn->prepare("
SELECT *
FROM medical_reports
WHERE patient_id = ?
ORDER BY report_date DESC
");

$reportStmt->bind_param("i", $patientId);

$reportStmt->execute();

$reports = $reportStmt->get_result();

?>

<!DOCTYPE html>

<html>

<head>

<title>Patient Dashboard</title>

<link rel="stylesheet" href="style.css">

</head>

<body>

<?php include "header.php"; ?>

<section class="dashboard">

<div class="container">

<h1>
Welcome, <?= e($_SESSION["full_name"]) ?>
</h1>

<p>
Your personal MediCare Plus healthcare dashboard.
</p>

<br>

<div class="dashboard-cards">

<div class="stat-card">
<h2>+</h2>
<p>Book Appointment</p>
<a href="doctors.php">Find Doctor</a>
</div>

<div class="stat-card">
<h2>✓</h2>
<p>Appointments</p>
<a href="my_appointments.php">View All</a>
</div>

<div class="stat-card">
<h2>+</h2>
<p>Medical Reports</p>
<a href="#reports">View Reports</a>
</div>

<div class="stat-card">
<h2>?</h2>
<p>Need Help?</p>
<a href="index.php">Contact Support</a>
</div>

</div>

<h2>My Appointments</h2>

<br>

<div class="table-container">

<table>

<tr>

<th>Doctor</th>
<th>Specialisation</th>
<th>Date</th>
<th>Time</th>
<th>Status</th>

</tr>

<?php while ($appointment = $appointments->fetch_assoc()): ?>

<tr>

<td><?= e($appointment["doctor_name"]) ?></td>

<td><?= e($appointment["specialization"]) ?></td>

<td><?= e($appointment["appointment_date"]) ?></td>

<td><?= e($appointment["appointment_time"]) ?></td>

<td><?= e(ucfirst($appointment["status"])) ?></td>

</tr>

<?php endwhile; ?>

</table>

</div>

<br><br>

<h2 id="reports">Medical Reports</h2>

<br>

<div class="grid">

<?php if ($reports->num_rows > 0): ?>

<?php while ($report = $reports->fetch_assoc()): ?>

<div class="card">

<h3><?= e($report["title"]) ?></h3>

<p>
Date:
<?= e($report["report_date"]) ?>
</p>

<p>
<?= e($report["report_text"]) ?>
</p>

</div>

<?php endwhile; ?>

<?php else: ?>

<div class="card">

<p>No medical reports are currently available.</p>

</div>

<?php endif; ?>

</div>

</div>

</section>

</body>

</html>