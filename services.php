<?php

require_once "config.php";

$services = $conn->query(
    "SELECT * FROM services ORDER BY name"
);

?>

<!DOCTYPE html>
<html>

<head>

<title>Services - MediCare Plus</title>

<link rel="stylesheet" href="style.css">

</head>

<body>

<?php include "header.php"; ?>

<section class="page-header">

<div class="container">

<h1>Healthcare Services</h1>

<p>
Explore the healthcare services available through MediCare Plus.
</p>

</div>

</section>

<section class="section">

<div class="container">

<div class="grid">

<?php while ($service = $services->fetch_assoc()): ?>

<div class="card">

<div class="service-icon">

<?= e($service["icon"]) ?>

</div>

<h2><?= e($service["name"]) ?></h2>

<p>
<?= e($service["description"]) ?>
</p>

<br>

<a
href="doctors.php?specialization=<?= urlencode($service["name"]) ?>"
class="btn"
>
Find Specialists
</a>

</div>

<?php endwhile; ?>

</div>

</div>

</section>

</body>
</html>