<?php
include_once 'header_all.php';
include_once 'config.php';
include_once 'doctor.php';
session_start();


if (!isset($_SESSION['doctor_id'])) {
    header("Location: doctor_login.php");
    exit();
}

$doctorId = $_SESSION['doctor_id'];


$doctorClass = new Doctor($conn);
$doctorData = $doctorClass->getDoctorDetails($doctorId);


if (!$doctorData) {
    die("Doctor details not found.");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container mt-5">
        <h2 class="text-center">Doctor Profile</h2>
        <div class="card mt-4">
            <div class="card-body">
                <h4 class="card-title">Profile Details</h4>
                <hr>
                <p><strong>Name:</strong> <?= htmlspecialchars($doctorData['doctor_name']); ?></p>
                <p><strong>Email:</strong> <?= htmlspecialchars($doctorData['doctor_email']); ?></p>
                <p><strong>Specialist:</strong> <?= htmlspecialchars($doctorData['specialist']); ?></p>
            </div>
        </div>
        <div class="mt-4 text-center">
            <a href="doctor_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
        </div>
    </div>
</body>

</html>
