<?php

require_once "config.php";

requireLogin();

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $receiver = intval($_POST["receiver_id"]);
    $text = trim($_POST["message"]);

    if ($receiver > 0 && $text !== "") {

        $stmt = $conn->prepare("
            INSERT INTO messages
            (sender_id, receiver_id, message)
            VALUES (?, ?, ?)
        ");

        $stmt->bind_param(
            "iis",
            $_SESSION["user_id"],
            $receiver,
            $text
        );

        if ($stmt->execute()) {
            $message = "Message sent successfully.";
        }
    }
}

$users = $conn->query("
    SELECT id, full_name, role
    FROM users
    WHERE id <> " . intval($_SESSION["user_id"]) . "
    ORDER BY full_name
");

?>

<!DOCTYPE html>

<html>

<head>

<title>Messages</title>

<link rel="stylesheet" href="style.css">

</head>

<body>

<?php include "header.php"; ?>

<div class="form-container">

<div class="form-card">

<h2>Secure Messaging</h2>

<p>
Send a follow-up message to another authorised user.
</p>

<br>

<?php if ($message): ?>

<div class="alert alert-success">
<?= e($message) ?>
</div>

<?php endif; ?>

<form method="POST">

<div class="form-group">

<label>Recipient</label>

<select name="receiver_id" required>

<option value="">Select recipient</option>

<?php while ($user = $users->fetch_assoc()): ?>

<option value="<?= $user["id"] ?>">

<?= e($user["full_name"]) ?>

(<?= e($user["role"]) ?>)

</option>

<?php endwhile; ?>

</select>

</div>

<div class="form-group">

<label>Message</label>

<textarea
name="message"
required
placeholder="Write your message..."
></textarea>

</div>

<button class="btn btn-green" type="submit">

Send Message

</button>

</form>

</div>

</div>

</body>

</html>