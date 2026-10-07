<?php
require_once "config.php";

$message = "";
$type = "";

if (isset($_GET["registered"])) {
    $message = "Registration successful. Please log in.";
    $type = "success";
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    $stmt = $conn->prepare(
        "SELECT id, full_name, password, role
         FROM users
         WHERE email = ?"
    );

    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows === 1) {

        $user = $result->fetch_assoc();

        if (password_verify($password, $user["password"])) {

            session_regenerate_id(true);

            $_SESSION["user_id"] = $user["id"];
            $_SESSION["full_name"] = $user["full_name"];
            $_SESSION["role"] = $user["role"];

            if ($user["role"] === "admin") {
                header("Location: admin_dashboard.php");
            } elseif ($user["role"] === "doctor") {
                header("Location: doctor_dashboard.php");
            } else {
                header("Location: dashboard.php");
            }

            exit;

        } else {
            $message = "Invalid email or password.";
            $type = "error";
        }

    } else {
        $message = "Invalid email or password.";
        $type = "error";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - MediCare Plus</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<?php include "header.php"; ?>

<div class="form-container">

<div class="form-card">

<h2>Welcome Back</h2>

<p>Sign in to your MediCare Plus account.</p>

<br>

<?php if ($message): ?>

<div class="alert alert-<?= $type ?>">
    <?= e($message) ?>
</div>

<?php endif; ?>

<form method="POST">

<div class="form-group">
<label>Email Address</label>
<input type="email" name="email" required>
</div>

<div class="form-group">
<label>Password</label>
<input type="password" name="password" required>
</div>

<button class="btn" type="submit">
Login
</button>

</form>

<br>

<p>
New patient?
<a href="register.php" style="color:#087ea4;">
Create an account
</a>
</p>

</div>

</div>

</body>
</html>