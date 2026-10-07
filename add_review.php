<?php

require_once "config.php";

requireRole("patient");

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $appointmentId = intval($_POST["appointment_id"]);
    $rating = intval($_POST["rating"]);
    $comment = trim($_POST["comment"]);

    $stmt = $conn->prepare("
        SELECT doctor_id
        FROM appointments
        WHERE id = ?
        AND patient_id = ?
        AND status = 'completed'
    ");

    $stmt->bind_param(
        "ii",
        $appointmentId,
        $_SESSION["user_id"]
    );

    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows === 1) {

        $appointment = $result->fetch_assoc();

        $doctorId = $appointment["doctor_id"];

        $check = $conn->prepare("
            SELECT id
            FROM reviews
            WHERE appointment_id = ?
            AND patient_id = ?
        ");

        $check->bind_param(
            "ii",
            $appointmentId,
            $_SESSION["user_id"]
        );

        $check->execute();

        if ($check->get_result()->num_rows > 0) {

            $message = "You have already reviewed this appointment.";

        } else {

            $insert = $conn->prepare("
                INSERT INTO reviews
                (patient_id, doctor_id, appointment_id, rating, comment)
                VALUES (?, ?, ?, ?, ?)
            ");

            $insert->bind_param(
                "iiiis",
                $_SESSION["user_id"],
                $doctorId,
                $appointmentId,
                $rating,
                $comment
            );

            if ($insert->execute()) {

                $message = "Thank you for your feedback.";

            } else {

                $message = "Unable to submit review.";
            }
        }

    } else {

        $message = "Only completed appointments can be reviewed.";
    }
}

?>

<!DOCTYPE html>

<html>

<head>

<title>Feedback</title>

<link rel="stylesheet" href="style.css">

</head>

<body>

<?php include "header.php"; ?>

<div class="form-container">

<div class="form-card">

<h2>Patient Feedback</h2>

<?php if ($message): ?>

<div class="alert alert-success">
<?= e($message) ?>
</div>

<?php endif; ?>

<form method="POST">

<div class="form-group">

<label>Appointment ID</label>

<input
type="number"
name="appointment_id"
required
>

</div>

<div class="form-group">

<label>Rating</label>

<select name="rating" required>

<option value="">Select rating</option>

<option value="5">5 - Excellent</option>
<option value="4">4 - Very Good</option>
<option value="3">3 - Good</option>
<option value="2">2 - Poor</option>
<option value="1">1 - Very Poor</option>

</select>

</div>

<div class="form-group">

<label>Comment</label>

<textarea name="comment"></textarea>

</div>

<button class="btn btn-green" type="submit">

Submit Feedback

</button>

</form>

</div>

</div>

</body>

</html>