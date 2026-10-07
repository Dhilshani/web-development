<?php

require_once "config.php";

requireLogin();

if ($_SESSION["role"] !== "patient") {
    die("Only patients can book appointments.");
}

$doctorId = intval($_GET["doctor"] ?? $_POST["doctor_id"] ?? 0);

$stmt = $conn->prepare("
SELECT
    d.*,
    u.full_name
FROM doctors d
JOIN users u ON d.user_id = u.id
WHERE d.id = ?
");

$stmt->bind_param("i", $doctorId);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    die("Doctor not found.");
}

$doctor = $result->fetch_assoc();

$message = "";
$type = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $date = $_POST["appointment_date"];
    $time = $_POST["appointment_time"];
    $reason = trim($_POST["reason"]);

    if ($date < date("Y-m-d")) {

        $message = "Please select a future appointment date.";
        $type = "error";

    } else {

        $check = $conn->prepare("
            SELECT id
            FROM appointments
            WHERE doctor_id = ?
            AND appointment_date = ?
            AND appointment_time = ?
            AND status <> 'cancelled'
        ");

        $check->bind_param(
            "iss",
            $doctorId,
            $date,
            $time
        );

        $check->execute();

        $existing = $check->get_result();

        if ($existing->num_rows > 0) {

            $message = "This appointment slot is already booked.";
            $type = "error";

        } else {

            $insert = $conn->prepare("
                INSERT INTO appointments
                (patient_id, doctor_id, appointment_date,
                 appointment_time, reason, status)
                VALUES (?, ?, ?, ?, ?, 'confirmed')
            ");

            $insert->bind_param(
                "iisss",
                $_SESSION["user_id"],
                $doctorId,
                $date,
                $time,
                $reason
            );

            if ($insert->execute()) {

                $message = "Appointment successfully booked.";
                $type = "success";

            } else {

                $message = "Unable to book appointment.";
                $type = "error";
            }
        }
    }
}

?>

<!DOCTYPE html>

<html>

<head>

<title>Book Appointment - MediCare Plus</title>

<link rel="stylesheet" href="style.css">

</head>

<body>

<?php include "header.php"; ?>

<div class="form-container">

<div class="form-card">

<h2>Book Appointment</h2>

<br>

<div class="card">

<h3><?= e($doctor["full_name"]) ?></h3>

<p>
<?= e($doctor["specialization"]) ?>
</p>

<p>
<?= e($doctor["qualification"]) ?>
</p>

<p>
Consultation Fee:
LKR <?= number_format($doctor["consultation_fee"],2) ?>
</p>

<p>
Availability:
<?= e($doctor["availability"]) ?>
</p>

</div>

<br>

<?php if ($message): ?>

<div class="alert alert-<?= $type ?>">
<?= e($message) ?>
</div>

<?php endif; ?>

<form method="POST">

<input
type="hidden"
name="doctor_id"
value="<?= $doctorId ?>"
>

<div class="form-group">

<label>Appointment Date</label>

<input
type="date"
name="appointment_date"
min="<?= date('Y-m-d') ?>"
required
>

</div>

<div class="form-group">

<label>Appointment Time</label>

<select name="appointment_time" required>

<option value="">Select time</option>

<option value="09:00:00">09:00 AM</option>
<option value="10:00:00">10:00 AM</option>
<option value="11:00:00">11:00 AM</option>
<option value="13:00:00">01:00 PM</option>
<option value="14:00:00">02:00 PM</option>
<option value="15:00:00">03:00 PM</option>

</select>

</div>

<div class="form-group">

<label>Reason for Visit</label>

<textarea
name="reason"
placeholder="Briefly describe your reason for the appointment."
></textarea>

</div>

<button class="btn btn-green" type="submit">

Confirm Appointment

</button>

</form>

</div>

</div>

</body>

</html>