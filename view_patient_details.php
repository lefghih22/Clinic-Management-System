<?php
include 'header_all.php';
include 'config.php';
include 'Patients.php';
session_start();

// Ensure the doctor is logged in
if (!isset($_SESSION['doctor_id'])) {
    header("Location: doctor_login.php");
    exit;
}

// Get the patient ID from the URL
$patient_id = isset($_GET['id']) ? intval($_GET['id']) : null;

if (!$patient_id) {
    die('Invalid Patient ID.');
}

// Use the Patients class
$patientsClass = new Patients($conn);
$patientDetails = $patientsClass->getPatientDetails($patient_id);

// Check if patient details exist
if (!$patientDetails) {
    die('Patient not found.');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Patient Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container mt-5">
        <h1 class="text-center mb-4">Patient Details</h1>
        <div class="card">
            <div class="card-header">
                <h3><?= htmlspecialchars($patientDetails['patient_name']) ?></h3>
            </div>
            <div class="card-body">
                <p><strong>Age:</strong> <?= htmlspecialchars($patientDetails['patient_age']) ?></p>
                <p><strong>Gender:</strong> <?= htmlspecialchars($patientDetails['patient_gender']) ?></p>
                <p><strong>Address:</strong> <?= htmlspecialchars($patientDetails['patient_address']) ?></p>
                <p><strong>Email:</strong> <?= htmlspecialchars($patientDetails['patient_email']) ?></p>
                <p><strong>Medical History:</strong> <?= nl2br(htmlspecialchars($patientDetails['medical_history'])) ?></p>
            </div>
        </div>
        <div class="text-center mt-4">
            <a href="doctor_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
        </div>
    </div>
</body>

</html>