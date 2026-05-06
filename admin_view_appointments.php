<?php
include 'config.php';
include 'header_all.php';
include 'Appointment.php';

session_start();



// Create an instance of the Appointment class
$appointment = new Appointment($conn);

// Fetch all appointments
$appointments = $appointment->getAppointmentsForAdmin();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Appointments</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">All Appointments</h1>

        <!-- Appointments Table -->
        <table class="table table-striped mt-4">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Patient Name</th>
                    <th>Doctor Name</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($appointments && $appointments->num_rows > 0): ?>
                    <?php $index = 1; ?>
                    <?php while ($row = $appointments->fetch_assoc()): ?>
                        <tr>
                            <td><?= $index++ ?></td>
                            <td><?= htmlspecialchars($row['patient_name']) ?></td>
                            <td><?= htmlspecialchars($row['doctor_name']) ?></td>
                            <td><?= htmlspecialchars($row['appointment_date']) ?></td>
                            <td><?= htmlspecialchars($row['appointment_time']) ?></td>
                            <td>
                                <?= $row['status'] === 'Scheduled' 
                                    ? '<span class="badge bg-success">Scheduled</span>' 
                                    : '<span class="badge bg-danger">Canceled</span>' ?>
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
        <div class="text-center mt-5">
            <a href="admin_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
        </div>

   

    </div>
</body>
</html>
