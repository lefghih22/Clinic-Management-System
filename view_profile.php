<?php

include 'config.php';
include 'header_all.php';
session_start();

if (!isset($_SESSION['patient_id'])) {
    header("Location: login.php");
    exit();
}

$patient_id = $_SESSION['patient_id'];

class PatientProfile {
    public $conn;
    public $patientId;
    public $patientData;

  
    public function __construct($dbConnection, $patient_id) {
        $this->conn = $dbConnection;
        $this->patientId = $patient_id;
    }

    
    public function fetchPatientDetails() {
        $sql = "SELECT * FROM patient_details WHERE patient_id = $this->patientId";
        $result = $this->conn->query($sql);
        if ($result && $result->num_rows > 0) {
            $this->patientData = $result->fetch_assoc();
        } else {
            die("Patient not found.");
        }
    }

    
    public function displayProfile() {
        ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Mets Tags -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Profile</title>

    <!-- Bootstrap  CSS-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body class="bg-light">


    <div class="container mt-3">
        <h2 class="text-center">View Profile</h2>
        <div class="card mt-4">
            <div class="card-body">
                <h4 class="card-title">Profile Details</h4>
                <hr>
                <p><strong>Name:</strong> <?= htmlspecialchars($this->patientData['patient_name']); ?></p>
                <p><strong>Gender:</strong> <?= htmlspecialchars($this->patientData['patient_gender']); ?></p>
                <p><strong>Age:</strong> <?= htmlspecialchars($this->patientData['patient_age']); ?></p>
                <p><strong>Email:</strong> <?= htmlspecialchars($this->patientData['patient_email']); ?></p>
                <p><strong>Address:</strong> <?= htmlspecialchars($this->patientData['patient_address']); ?></p>
                <p><strong>Medical History:</strong> <?= htmlspecialchars($this->patientData['medical_history']); ?></p>
            </div>
        </div>
        <div class="mt-3 text-center">

            <!-- Back Button -->
            <a href="patient_dashboard.php" class="btn btn-secondary btn-sm">Back to Dashboard</a>
        </div>
    </div>
</body>

</html>
<?php
    }
}

$profile = new PatientProfile($conn, $patient_id);
$profile->fetchPatientDetails();
$profile->displayProfile();
?>