<?php
include 'config.php';
include 'patient_class.php';
include 'header_all.php';
session_start();

$success = null;

if (!isset($_SESSION['patient_id'])) {
    header("Location: login.php");
    exit;
}

$patientClass = new Patient($conn);
$patient_id = $_SESSION['patient_id'];
$patient = $patientClass->getPatientDetails($patient_id);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  
    $name = $_POST['name'];
    $age =  $_POST['age'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $history = $_POST['history'];
    $password = $_POST['password'];

    if ($patientClass->updatePatientProfile($patient_id, $name, $age, $email, $address, $history, $password)) {
        $success = "Profile updated!";
        $patient = $patientClass->getPatientDetails($patient_id); 
    } 
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Mets Tags -->
    <meta charset="UTF-8">
    <title>Update Profile</title>

    <!-- Bootstrap  CSS-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">


    <div class="container mt-3">
        <h2 class="text-center">Update Profile</h2>

        <?php if ($success): ?>
        <div class="alert alert-success text-center"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <form method="POST" class="mt-3">
            <div class="row">
                <div class="col-md-12">

                    <div class="mb-2">
                        <label for="name" class="form-label">Name:</label>
                        <input type="text" name="name" id="name" placeholder="Enter name" class="form-control" required>
                    </div>

                    <div class="mb-2">
                        <label for="age" class="form-label">Age:</label>
                        <input type="number" name="age" id="age" placeholder="Enter age" class="form-control" required>
                    </div>

                    <div class="mb-2">
                        <label for="email" class="form-label">Email:</label>
                        <input type="email" name="email" id="email" placeholder="Enter email" class="form-control"
                            required>
                    </div>

                    <div class="mb-2">
                        <label for="patient_address" class="form-label">Address:</label>
                        <input type="text" name="address" id="address" placeholder="Enter address" class="form-control"
                            required>
                    </div>

                    <div class="mb-2">
                        <label for="medical_history" class="form-label">Medical History:</label>
                        <textarea name="history" id="history" placeholder="Enter medical history" class="form-control"
                            required></textarea>
                    </div>

                    <div class="mb-2">
                        <label for="password" class="form-label">New Password (Optional):</label>
                        <input type="password" name="password" id="password" placeholder="Enter new password"
                            class="form-control">
                    </div>
                </div>
            </div>

            <!-- Update Button -->
            <div class="row mt-3">
                <div class="col-md-6">
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Update Profile</button>
                    </div>
                </div>

                <!-- Back Button -->
                <div class="col-md-6">
                    <div class="d-grid">
                        <a href="patient_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
                    </div>
                </div>

            </div>
        </form>
    </div>

</body>

</html>