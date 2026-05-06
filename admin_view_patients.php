<?php
include_once 'header_all.php';
include 'config.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

class AdminPatients {
    public $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function getAllPatients() {
        $query = "SELECT patient_id, patient_name, patient_email, patient_age, patient_address, doctor_name 
                  FROM patient_details 
                  JOIN doctor_details ON patient_details.doctor_id = doctor_details.doctor_id";
        return $this->conn->query($query);
    }
}

$patientsClass = new AdminPatients($conn);
$patients = $patientsClass->getAllPatients();
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
        <h1 class="text-center mb-4"> All Assigned Patient</h1>
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Age</th>
                    <th>Address</th>
                    <th>Assigned Doctor</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($patients && $patients->num_rows > 0): ?>
                    <?php $index = 1; ?>
                    <?php while ($row = $patients->fetch_assoc()): ?>
                        <tr>
                            <td><?= $index++ ?></td>
                            <td><?= htmlspecialchars($row['patient_name']) ?></td>
                            <td><?= htmlspecialchars($row['patient_email']) ?></td>
                            <td><?= htmlspecialchars($row['patient_age']) ?></td>
                            <td><?= htmlspecialchars($row['patient_address']) ?></td>
                            <td><?= htmlspecialchars($row['doctor_name']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">No patients found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <div class="text-center mt-4">
            <a href="admin_dashboard.php" class="btn btn-secondary btn-sm">Back to Dashboard</a>
        </div>
    </div>
</body>

</html>
