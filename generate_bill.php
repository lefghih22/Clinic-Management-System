<?php
include 'config.php';
include 'header_all.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$message = "";

// Fetch patients for the dropdown
$patients = [];
$patientQuery = "
    SELECT DISTINCT a.patient_id, p.patient_name 
    FROM appointments a
    LEFT JOIN patient_details p ON a.patient_id = p.patient_id
";
$patientResult = $conn->query($patientQuery);
if ($patientResult && $patientResult->num_rows > 0) {
    while ($row = $patientResult->fetch_assoc()) {
        $patients[] = $row;
    }
}

// Fetch appointments for the selected patient
$selected_patient_id = $_POST['patient_id'] ?? '';
$appointments = [];
$existing_bill = null;

if ($selected_patient_id) {
    $appointmentQuery = "
        SELECT a.appointment_id, a.appointment_date, d.doctor_id, d.doctor_name
        FROM appointments a
        LEFT JOIN doctor_details d ON a.doctor_id = d.doctor_id
        WHERE a.patient_id = $selected_patient_id
    ";
    $appointmentResult = $conn->query($appointmentQuery);

    if ($appointmentResult && $appointmentResult->num_rows > 0) {
        while ($row = $appointmentResult->fetch_assoc()) {
            $appointment_id = $row['appointment_id'];
            $billCheckQuery = "
                SELECT gb.bill_id, gb.services, gb.total_amount, gb.appointment_id
                FROM generate_bill gb
                WHERE gb.appointment_id = $appointment_id
            ";
            $billCheckResult = $conn->query($billCheckQuery);
            if ($billCheckResult && $billCheckResult->num_rows > 0) {
                $existing_bill = $billCheckResult->fetch_assoc();
                $row['bill_exists'] = true;
            } else {
                $row['bill_exists'] = false;
            }
            $appointments[] = $row;
        }
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['generate_or_update'])) {
    $appointment_id = $_POST['appointment_id'] ?? null;
    $patient_id = $_POST['patient_id'] ?? null;
    $services = $_POST['services'] ?? '';
    $total_amount = $_POST['total_amount'] ?? 0;

    if ($appointment_id && $patient_id) {
        $checkQuery = "SELECT bill_id FROM generate_bill WHERE appointment_id = $appointment_id";
        $checkResult = $conn->query($checkQuery);

        if ($checkResult && $checkResult->num_rows > 0) {
            // Update the existing bill
            $updateQuery = "
                UPDATE generate_bill 
                SET services = '$services', total_amount = $total_amount 
                WHERE appointment_id = $appointment_id
            ";
            if ($conn->query($updateQuery)) {
                // Update the corresponding record in the view_bill table
                $updateViewBillQuery = "
                    UPDATE view_bill 
                    SET date = NOW(), bill_status = 'Unpaid'
                    WHERE bill_id = (SELECT bill_id FROM generate_bill WHERE appointment_id = $appointment_id)
                ";
                $conn->query($updateViewBillQuery);

                // Redirect to view_generated_bill page
                $bill_id = $checkResult->fetch_assoc()['bill_id'];
                header("Location: view_generated_bill.php?bill_id=$bill_id");
                exit();
            } else {
                $message = "Error updating bill: " . $conn->error;
            }
        } else {
            // Insert a new bill
            $insertQuery = "
                INSERT INTO generate_bill (appointment_id, patient_id, doctor_id, services, total_amount) 
                VALUES (
                    $appointment_id, 
                    $patient_id, 
                    (SELECT doctor_id FROM appointments WHERE appointment_id = $appointment_id),
                    '$services', 
                    $total_amount
                )
            ";
            if ($conn->query($insertQuery)) {
                $bill_id = $conn->insert_id;

                // Insert a new record into the view_bill table
                $insertViewBillQuery = "
                    INSERT INTO view_bill (bill_id, patient_id, doctor_id, bill_status, date) 
                    VALUES (
                        $bill_id, 
                        $patient_id, 
                        (SELECT doctor_id FROM appointments WHERE appointment_id = $appointment_id), 
                        'Unpaid', NOW()
                    )
                ";
                $conn->query($insertViewBillQuery);

                //view_generated_bill page
                header("Location: view_generated_bill.php?bill_id=$bill_id");
                exit();
            } else {
                $message = "Error generating bill: " . $conn->error;
            }
        }
    } else {
        $message = "Please select a valid appointment and patient.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Generate or Update Bill</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h1 class="text-center">Generate or Update Bill</h1>
        <?php if ($message): ?>
            <div class="alert alert-info text-center"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="mb-3">
                <label for="patient_id" class="form-label">Patient Name</label>
                <select name="patient_id" id="patient_id" class="form-control" onchange="this.form.submit()" required>
                    <option value="">Select Patient</option>
                    <?php foreach ($patients as $patient): ?>
                        <option value="<?= htmlspecialchars($patient['patient_id']) ?>" <?= $selected_patient_id == $patient['patient_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($patient['patient_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <?php if ($selected_patient_id && count($appointments) > 0): ?>
                <div class="mb-3">
                    <label for="appointment_id" class="form-label">Appointment</label>
                    <select name="appointment_id" id="appointment_id" class="form-control" required>
                        <option value="">Select Appointment</option>
                        <?php foreach ($appointments as $appointment): ?>
                            <option value="<?= htmlspecialchars($appointment['appointment_id']) ?>">
                                <?= htmlspecialchars($appointment['appointment_date']) ?> | Doctor: <?= htmlspecialchars($appointment['doctor_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="services" class="form-label">Services</label>
                    <input type="text" name="services" id="services" class="form-control" value="<?= htmlspecialchars($existing_bill['services'] ?? '') ?>" required>
                </div>
                <div class="mb-3">
                    <label for="total_amount" class="form-label">Total Amount</label>
                    <input type="number" name="total_amount" id="total_amount" class="form-control" value="<?= htmlspecialchars($existing_bill['total_amount'] ?? '') ?>" required>
                </div>
                <button type="submit" name="generate_or_update" class="btn btn-primary">Generate Bill</button>
                <a href="admin_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
            <?php else: ?>
                <div class=""></div>
            <?php endif; ?>
        </form>
        <div class="text-center mt-4">
            <a href="admin_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
        </div>
    </div>
</body>

</html>