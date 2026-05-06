<?php
include 'header_all.php';
include 'config.php';
session_start();


if (!isset($_SESSION['patient_id'])) {
    header("Location: login.php");
    exit;
}

$patient_id = $_SESSION['patient_id'];

class DoctorList {
    public $conn;

    
    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

  
    public function getDoctors() {
        $query = "SELECT doctor_name, specialist, doctor_email FROM doctor_details";
        return $this->conn->query($query);
    }
}


$doctorList = new DoctorList($conn);
$doctors = $doctorList->getDoctors();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Mets Tags -->
    <meta charset="UTF-8">
    <title>View Doctors</title>

    <!-- Bootstrap  CSS-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container mt-3">
        <h2 class="text-center">Doctor List</h2>
        <table class="table table-bordered table-striped mt-4">
            <thead class="table-dark">
                <tr>
                    <th>Name</th>
                    <th>Specialist</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($doctors && $doctors->num_rows > 0): ?>
                <?php while ($row = $doctors->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['doctor_name']); ?></td>
                    <td><?= htmlspecialchars($row['specialist']); ?></td>
                    <td><?= htmlspecialchars($row['doctor_email']); ?></td>
                </tr>
                <?php endwhile; ?>
                <?php else: ?>
                <tr>
                    <td colspan="3" class="text-center">No doctors found.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <div class="text-center mt-4">

            <!-- Back Button -->
            <a href="patient_dashboard.php" class="btn btn-secondary  btn-sm">Back to Dashboard</a>
        </div>
    </div>
</body>

</html>