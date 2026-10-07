<?php

require_once "config.php";

requireRole("patient");

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

$stmt->bind_param("i", $_SESSION["user_id"]);

$stmt->execute();

$appointments = $stmt->get_result();

?>

<!DOCTYPE html>

<html>

<head>

<title>My Appointments</title>

<link rel="stylesheet" href="style.css">

</head>

<body>

<?php include "header.php"; ?>

<section class="section">

<div class="container">

<h1>My Appointments</h1>

<br>

<div class="table-container">

<table>

<tr>

<th>Doctor</th>
<th>Specialisation</th>
<th>Date</th>
<th>Time</th>
<th>Reason</th>
<th>Status</th>

</tr>

<?php while ($row = $appointments->fetch_assoc()): ?>

<tr>

<td><?= e($row["doctor_name"]) ?></td>

<td><?= e($row["specialization"]) ?></td>

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