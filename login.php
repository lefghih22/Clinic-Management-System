<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $patient_name = $_POST['patient_name'];
    $password = $_POST['password'];

    // Query to verify patient's credential
    $sql = "SELECT * FROM patient_details WHERE patient_name = '$patient_name' AND patient_password = '$password'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        // Get patient ID if credentials match
        $patientData = $result->fetch_assoc();
        $patient_id = $patientData['patient_id']; // Corrected column name

        // Start session and store patient ID
        session_start();
        $_SESSION['patient_id'] = $patient_id;

        // Redirect to patient dashboard
        header("Location: patient_dashboard.php");
        exit();
    } else {
        $error_message = "Invalid name or password. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body>

    <div class="container mt-5">
        <h2 class="text-center">Patient Login</h2>

        <?php if (isset($error_message)) { ?>
        <div class="alert alert-danger"><?= $error_message ?></div>
        <?php } ?>

        <form method="POST" action="" class="mt-4">
            <div class="mb-3">
                <label for="patient_name" class="form-label">Patient Name</label>
                <input type="text" class="form-control" id="patient_name" name="patient_name" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
    </div>

</body>

</html>