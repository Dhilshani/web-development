<?php
require_once "config.php";

$message = "";
$type = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST["full_name"]);
    $email = trim($_POST["email"]);
    $phone = trim($_POST["phone"]);
    $password = $_POST["password"];
    $confirm = $_POST["confirm_password"];

    if ($name === "" || $email === "" || $password === "") {

        $message = "Please complete all required fields.";
        $type = "error";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $message = "Please enter a valid email address.";
        $type = "error";

    } elseif (strlen($password) < 8) {

        $message = "Password must contain at least 8 characters.";
        $type = "error";

    } elseif ($password !== $confirm) {

        $message = "Passwords do not match.";
        $type = "error";

    } else {

        $check = $conn->prepare(
            "SELECT id FROM users WHERE email = ?"
        );

        $check->bind_param("s", $email);
        $check->execute();

        $result = $check->get_result();

        if ($result->num_rows > 0) {

            $message = "An account already exists with this email.";
            $type = "error";

        } else {

            $hashedPassword = password_hash(
                $password,
                PASSWORD_DEFAULT
            );

            $stmt = $conn->prepare(
                "INSERT INTO users
                (full_name,email,password,role,phone)
                VALUES (?, ?, ?, 'patient', ?)"
            );

            $stmt->bind_param(
                "ssss",
                $name,
                $email,
                $hashedPassword,
                $phone
            );

            if ($stmt->execute()) {

                header("Location: login.php?registered=1");
                exit;

            } else {

                $message = "Registration could not be completed.";
                $type = "error";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register - MediCare Plus</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<?php include "header.php"; ?>

<div class="form-container">

<div class="form-card">

<h2>Create Patient Account</h2>

<p>Register securely to manage your healthcare appointments.</p>

<br>

<?php if ($message): ?>

<div class="alert alert-<?= $type ?>">
    <?= e($message) ?>
</div>

<?php endif; ?>

<form method="POST">

<div class="form-group">
<label>Full Name</label>
<input type="text" name="full_name" required>
</div>

<div class="form-group">
<label>Email Address</label>
<input type="email" name="email" required>
</div>

<div class="form-group">
<label>Phone Number</label>
<input type="text" name="phone">
</div>

<div class="form-group">
<label>Password</label>
<input type="password" name="password" required>
</div>

<div class="form-group">
<label>Confirm Password</label>
<input type="password" name="confirm_password" required>
</div>

<button class="btn btn-green" type="submit">
Create Account
</button>

</form>

<br>

<p>
Already registered?
<a href="login.php" style="color:#087ea4;">Login here</a>
</p>

</div>

</div>

</body>
</html>