<?php 
include 'header_all.php';
include 'config.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

class AdminDoctorList {
    public $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function getAllDoctors() {
        $query = "SELECT doctor_id, doctor_name, doctor_email, specialist FROM doctor_details";
        return $this->conn->query($query);
    }

    public function deleteDoctor($doctorId) {
        // Step 1: Delete rows from `view_bill`
        $this->conn->query("DELETE FROM view_bill WHERE bill_id IN (SELECT bill_id FROM generate_bill WHERE doctor_id = $doctorId)");

        // Step 2: Delete rows from `generate_bill`
        $this->conn->query("DELETE FROM generate_bill WHERE appointment_id IN (SELECT appointment_id FROM appointments WHERE doctor_id = $doctorId)");

        // Step 3: Delete rows from `appointments`
        $this->conn->query("DELETE FROM appointments WHERE doctor_id = $doctorId");

        // Step 4: Update `patient_details` to remove doctor reference
        $this->conn->query("UPDATE patient_details SET doctor_id = NULL WHERE doctor_id = $doctorId");

        // Step 5: Delete the doctor
        $query = "DELETE FROM doctor_details WHERE doctor_id = $doctorId";
        return $this->conn->query($query);
    }
}

$doctorList = new AdminDoctorList($conn);

// Handle delete request
if (isset($_GET['delete_id'])) {
    $deleteId = intval($_GET['delete_id']); 
    if ($doctorList->deleteDoctor($deleteId)) {
        echo "<script>alert('Doctor deleted successfully!'); window.location.href = 'admin_view_doctor.php';</script>";
    } else {
        echo "<script>alert('Failed to delete doctor.');</script>";
    }
}

$doctors = $doctorList->getAllDoctors();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin View Doctors</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container mt-3">
        <h2 class="text-center">Doctor List</h2>
        <table class="table table-bordered table-striped mt-4">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Specialist</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($doctors && $doctors->num_rows > 0): ?>
                <?php while ($row = $doctors->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['doctor_id']); ?></td>
                    <td><?= htmlspecialchars($row['doctor_name']); ?></td>
                    <td><?= htmlspecialchars($row['doctor_email']); ?></td>
                    <td><?= htmlspecialchars($row['specialist']); ?></td>
                    <td>
                        <a href="admin_view_doctor.php?delete_id=<?= $row['doctor_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this doctor?');">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
                <?php else: ?>
                <tr>
                    <td colspan="5" class="text-center">No doctors found</td>
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
