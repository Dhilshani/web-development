<?php

require_once "config.php";

$name = "Demo Patient";
$email = "patient@medicare.com";
$password = password_hash("Patient123!", PASSWORD_DEFAULT);
$phone = "0772222222";

$check = $conn->prepare(
    "SELECT id FROM users WHERE email = ?"
);

$check->bind_param("s", $email);
$check->execute();

if ($check->get_result()->num_rows > 0) {

    echo "Demo patient already exists.";

} else {

    $stmt = $conn->prepare("
        INSERT INTO users
        (full_name,email,password,role,phone)
        VALUES (?, ?, ?, 'patient', ?)
    ");

    $stmt->bind_param(
        "ssss",
        $name,
        $email,
        $password,
        $phone
    );

    if ($stmt->execute()) {
        echo "Demo patient created successfully.";
    } else {
        echo "Unable to create demo patient.";
    }
}

?>