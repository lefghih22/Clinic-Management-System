<?php
include_once 'header_all.php';
include 'config.php';
include 'Patients.php';
session_start();


if (!isset($_SESSION['doctor_id'])) {
    header("Location: doctor_login.php");
    exit;
}

$patientsClass = new Patients($conn);
$doctor_id = $_SESSION['doctor_id'];
$patients = $patientsClass->getPatientsByDoctor($doctor_id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Patients</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h1 class="text-center mb-4">Your Patients</h1>
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                   
                    <th>Name</th>
                    <th>Age</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($patients): ?>
                    <?php while ($row = $patients->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['patient_name']) ?></td>
                            <td><?= htmlspecialchars($row['patient_age']) ?></td>
                            <td>
                                <a href="view_patient_details.php?id=<?= urlencode($row['patient_id']) ?>" class="btn btn-info btn-sm">View Details</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" class="text-center">No patients found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <div class="text-center mt-4">
            <a href="doctor_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
        </div>
    </div>
</body>
</html>
