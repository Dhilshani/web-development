<?php

session_start();

$host = "localhost";
$username = "root";
$password = "";
$database = "medicare_plus";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Unable to connect to the database. Please try again later.");
}

$conn->set_charset("utf8mb4");

function e($value) {
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: login.php");
        exit;
    }
}

function requireRole($role) {
    requireLogin();

    if ($_SESSION['role'] !== $role) {
        http_response_code(403);
        die("Access denied.");
    }
}
?>