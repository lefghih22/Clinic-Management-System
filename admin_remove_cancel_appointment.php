<?php
include 'config.php';
include 'header_all.php';

session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Step 1: Delete dependent rows in the `view_bill` table
    $deleteViewBillQuery = "
        DELETE FROM view_bill
        WHERE bill_id IN (
            SELECT bill_id FROM generate_bill
            WHERE appointment_id IN (
                SELECT appointment_id FROM appointments WHERE status = 'Canceled'
            )
        )
    ";
    $conn->query($deleteViewBillQuery);

    // Step 2: Delete dependent rows in the `pay_bill` table
    $deletePayBillQuery = "
        DELETE FROM pay_bill
        WHERE bill_id IN (
            SELECT bill_id FROM generate_bill
            WHERE appointment_id IN (
                SELECT appointment_id FROM appointments WHERE status = 'Canceled'
            )
        )
    ";
    $conn->query($deletePayBillQuery);

    // Step 3: Delete dependent rows in the `generate_bill` table
    $deleteGenerateBillQuery = "
        DELETE FROM generate_bill
        WHERE appointment_id IN (
            SELECT appointment_id FROM appointments WHERE status = 'Canceled'
        )
    ";
    $conn->query($deleteGenerateBillQuery);

    // Step 4: Delete canceled appointments
    $deleteAppointmentsQuery = "
        DELETE FROM appointments WHERE status = 'Canceled'
    ";
    if ($conn->query($deleteAppointmentsQuery)) {
        $message = "All canceled appointments removed successfully!";
    } else {
        $message = "Error occurred while removing canceled appointments: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Remove Canceled Appointments</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Remove Canceled Appointments</h1>

        <?php if (!empty($message)): ?>
            <div class="alert alert-info text-center"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <form method="POST" class="text-center mt-4">
            <button type="submit" class="btn btn-danger">Remove All Canceled Appointments</button>
        </form>

        <!-- Back to Dashboard Button -->
        <div class="text-center mt-5">
            <a href="admin_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
        </div>
    </div>
</body>
</html>
