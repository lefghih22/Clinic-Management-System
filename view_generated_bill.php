<?php
include 'config.php';
include 'header_all.php';

if (!isset($_GET['bill_id'])) {
    header("Location: admin_dashboard.php");
    exit();
}

$bill_id = $_GET['bill_id'];
$query = "
    SELECT gb.bill_id, gb.services, gb.total_amount, 
           p.patient_name, p.patient_email, p.patient_gender, 
           d.doctor_name, a.appointment_date, a.appointment_time
    FROM generate_bill gb
    JOIN patient_details p ON gb.patient_id = p.patient_id
    JOIN doctor_details d ON gb.doctor_id = d.doctor_id
    JOIN appointments a ON gb.appointment_id = a.appointment_id
    WHERE gb.bill_id = $bill_id
";
$result = $conn->query($query);

if ($result && $result->num_rows > 0) {
    $bill = $result->fetch_assoc();
} else {
    die("Bill not found.");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Generated Bill Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h1 class="text-center">Generated Bill Details</h1>
        <table class="table table-bordered mt-4">
            <thead class="table-dark">
                <tr>
                    <th>Category</th>
                    <th>Details</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Bill ID</td>
                    <td><?= htmlspecialchars($bill['bill_id']) ?></td>
                </tr>
                <tr>
                    <td>Patient Name</td>
                    <td><?= htmlspecialchars($bill['patient_name']) ?></td>
                </tr>
                <tr>
                    <td>Patient Email</td>
                    <td><?= htmlspecialchars($bill['patient_email']) ?></td>
                </tr>
                <tr>
                    <td>Patient Gender</td>
                    <td><?= htmlspecialchars($bill['patient_gender']) ?></td>
                </tr>
                <tr>
                    <td>Doctor Name</td>
                    <td><?= htmlspecialchars($bill['doctor_name']) ?></td>
                </tr>
                <tr>
                    <td>Appointment Date</td>
                    <td><?= htmlspecialchars($bill['appointment_date']) ?></td>
                </tr>
                <tr>
                    <td>Appointment Time</td>
                    <td><?= htmlspecialchars($bill['appointment_time']) ?></td>
                </tr>
                <tr>
                    <td>Services</td>
                    <td><?= htmlspecialchars($bill['services']) ?></td>
                </tr>
                <tr>
                    <td>Total Amount</td>
                    <td><?= htmlspecialchars($bill['total_amount']) ?> TL</td>
                </tr>
            </tbody>
        </table>
        <div class="text-center mt-4">
            <a href="admin_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
        </div>
    </div>
</body>

</html>