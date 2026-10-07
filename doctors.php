<?php
require_once "config.php";

$search = trim($_GET["search"] ?? "");
$location = trim($_GET["location"] ?? "");
$specialization = trim($_GET["specialization"] ?? "");

$sql = "
SELECT
    d.*,
    u.full_name
FROM doctors d
JOIN users u ON d.user_id = u.id
WHERE 1=1
";

$params = [];
$types = "";

if ($search !== "") {
    $sql .= "
        AND (
            u.full_name LIKE ?
            OR d.specialization LIKE ?
        )
    ";

    $searchValue = "%" . $search . "%";

    $params[] = $searchValue;
    $params[] = $searchValue;

    $types .= "ss";
}

if ($location !== "") {

    $sql .= " AND d.location LIKE ?";

    $locationValue = "%" . $location . "%";

    $params[] = $locationValue;

    $types .= "s";
}

if ($specialization !== "") {

    $sql .= " AND d.specialization = ?";

    $params[] = $specialization;

    $types .= "s";
}

$sql .= " ORDER BY d.rating DESC";

$stmt = $conn->prepare($sql);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();

$doctors = $stmt->get_result();

$specializations = $conn->query(
    "SELECT DISTINCT specialization FROM doctors ORDER BY specialization"
);
?>

<!DOCTYPE html>
<html>
<head>
<title>Find Doctors - MediCare Plus</title>
<link rel="stylesheet" href="style.css">
</head>

<body>

<?php include "header.php"; ?>

<section class="page-header">
<div class="container">
<h1>Find a Doctor</h1>
<p>Search specialists based on your healthcare requirements.</p>
</div>
</section>

<section class="section">

<div class="container">

<div class="card">

<form method="GET" class="search-box">

<input
type="text"
name="search"
value="<?= e($search) ?>"
placeholder="Doctor or speciality">

<select name="specialization">

<option value="">All specialisations</option>

<?php while ($spec = $specializations->fetch_assoc()): ?>

<option
value="<?= e($spec['specialization']) ?>"
<?= $specialization === $spec['specialization'] ? 'selected' : '' ?>
>
<?= e($spec['specialization']) ?>
</option>

<?php endwhile; ?>

</select>

<input
type="text"
name="location"
value="<?= e($location) ?>"
placeholder="Location">

<button class="btn" type="submit">
Search
</button>

</form>

</div>

<br>

<div class="grid">

<?php if ($doctors->num_rows > 0): ?>

<?php while ($doctor = $doctors->fetch_assoc()): ?>

<div class="card doctor-card">

<span class="badge">
Available
</span>

<h3><?= e($doctor["full_name"]) ?></h3>

<p>
<strong><?= e($doctor["specialization"]) ?></strong>
</p>

<p><?= e($doctor["qualification"]) ?></p>

<p><?= e($doctor["experience"]) ?> years experience</p>

<p><?= e($doctor["location"]) ?></p>

<div class="rating">
★ <?= e($doctor["rating"]) ?>/5
</div>

<p>
LKR <?= number_format($doctor["consultation_fee"],2) ?>
</p>

<br>

<a
href="book_appointment.php?doctor=<?= $doctor["id"] ?>"
class="btn"
>
Book Appointment
</a>

</div>

<?php endwhile; ?>

<?php else: ?>

<div class="card">
<h3>No doctors found</h3>
<p>Try changing your search criteria.</p>
</div>

<?php endif; ?>

</div>

</div>

</section>

</body>
</html>