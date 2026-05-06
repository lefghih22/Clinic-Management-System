<?php
include 'config.php';
include 'Appointment.php';
include 'header_all.php';

session_start();

$message = '';
$userType = ''; 
$appointments = null;
$result = null; 

if (isset($_SESSION['doctor_id'])) {
    $userType = 'doctor';
    $userId = $_SESSION['doctor_id'];
    $query = "SELECT a.appointment_id, p.patient_name, a.appointment_date, a.appointment_time, a.status 
              FROM appointments a 
              JOIN patient_details p ON a.patient_id = p.patient_id 
              WHERE a.doctor_id = $userId";
} elseif (isset($_SESSION['patient_id'])) {
    $userType = 'patient';
    $userId = $_SESSION['patient_id'];
    $query = "SELECT a.appointment_id, d.doctor_name, a.appointment_date, a.appointment_time, a.status 
              FROM appointments a 
              JOIN doctor_details d ON a.doctor_id = d.doctor_id 
              WHERE a.patient_id = $userId";
} else {
    header("Location: login.php");
    exit();
}

// Cancel an appointment
if (isset($_GET['cancel']) && isset($_GET['appointment_id'])) {
    $appointmentId = $_GET['appointment_id'];
    $updateQuery = "UPDATE appointments SET status = 'Canceled' WHERE appointment_id = $appointmentId";
    $result = $conn->query($updateQuery);
    if ($result) {
        $message = "Appointment canceled successfully.";
    } else {
        $message = "Failed to cancel appointment.";
    }
}


if (isset($query)) { 
    $result = $conn->query($query);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Appointments</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        main {
            flex: 1;
        }
        footer {
            background-color: #343a40;
            color: white;
            text-align: center;
            padding: 1rem 0;
        }
        .back-to-dashboard {
            margin-top: 30px;
            text-align: center;
        }
    </style>
</head>
<body class="bg-light">
    <main class="container mt-5">
        <h1 class="text-center"><?= $userType === 'doctor' ? "Doctor's Appointments" : "My Appointments" ?></h1>

        <?php if (!empty($message)): ?>
            <div class="alert alert-info text-center"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <table class="table table-striped mt-4">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <?php if ($userType === 'doctor'): ?>
                     
                        <th>Patient Name</th>
                    <?php else: ?>
                        <th>Doctor Name</th>
                    <?php endif; ?>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['appointment_id'] ?></td>
                            <?php if ($userType === 'doctor'): ?>
                                <td><?= htmlspecialchars($row['patient_name']) ?></td>
                            <?php else: ?>
                                <td><?= htmlspecialchars($row['doctor_name']) ?></td>
                            <?php endif; ?>
                            <td><?= htmlspecialchars($row['appointment_date']) ?></td>
                            <td><?= htmlspecialchars($row['appointment_time']) ?></td>
                            <td>
                                <?php if ($row['status'] === 'Scheduled'): ?>
                                    <span class="badge bg-success">Scheduled</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Canceled</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($row['status'] === 'Scheduled'): ?>
                                    <a href="?cancel=true&appointment_id=<?= $row['appointment_id'] ?>" class="btn btn-danger btn-sm">Cancel</a>
                                <?php else: ?>
                                    <span class="text-muted">No Action</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">No appointments found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Back to Dashboard Button -->
        <div class="back-to-dashboard">
            <a href="<?= $userType === 'doctor' ? 'doctor_dashboard.php' : 'patient_dashboard.php' ?>" class="btn btn-secondary">Back to Dashboard</a>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
