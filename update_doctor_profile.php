<?php
include 'header_all.php';
include 'config.php';
include 'doctor.php';
session_start();

$successMessage = null;
$errorMessage = null;

if (!isset($_SESSION['doctor_id'])) {
    header("Location: doctor_login.php");
    exit;
}

$doctorClass = new Doctor($conn);
$doctorId = $_SESSION['doctor_id'];
$doctor = $doctorClass->getDoctorDetails($doctorId);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $specialist = $_POST['specialist'];
    $email = $_POST['email'];

    // Check if password is entered
    if (!empty($_POST['password'])) {
        $password = $_POST['password'];
    } else {
        $password = null;
    }

    if ($doctorClass->updateDoctorProfile($doctorId, $name, $email, $specialist, $password)) {
        $successMessage = "Profile updated successfully.";
        $doctor = $doctorClass->getDoctorDetails($doctorId); 
    } else {
        $errorMessage = "Failed to update profile.";
    }
}
?>

<head>
    <meta charset="UTF-8">
    <title>Update Doctor Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container mt-5">
        <h2 class="text-center">Update Doctor Profile</h2>

        <!-- Success and error messages -->
        <?php if (!empty($successMessage)) { ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($successMessage); ?></div>
        <?php } ?>
        <?php if (!empty($errorMessage)) { ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($errorMessage); ?></div>
        <?php } ?>

        <!-- Profile update form -->
        <form method="POST" class="mt-4">
            <div class="mb-3">
                <label for="name" class="form-label">Name:</label>
                <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($doctor['doctor_name']); ?>" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="specialist" class="form-label">Specialist:</label>
                <select name="specialist" id="specialist" class="form-select" required>
                    <option value="" disabled>Select Specialist</option>
                    <option value="Cardiologist" <?php if ($doctor['specialist'] === 'Cardiologist') { echo 'selected'; } ?>>Cardiologist</option>
                    <option value="Neurologist" <?php if ($doctor['specialist'] === 'Neurologist') { echo 'selected'; } ?>>Surgeon</option>
                    <option value="Orthopedist" <?php if ($doctor['specialist'] === 'Orthopedist') { echo 'selected'; } ?>>Dermatologist</option>
                    <option value="Pediatrician" <?php if ($doctor['specialist'] === 'Pediatrician') { echo 'selected'; } ?>>Pediatrician</option>
                    <option value="Dermatologist" <?php if ($doctor['specialist'] === 'Dermatologist') { echo 'selected'; } ?>>General Pysician</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($doctor['doctor_email']); ?>" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">New Password (Optional):</label>
                <input type="password" name="password" id="password" placeholder="Enter new password" class="form-control">
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Update Profile</button>
            </div>
        </form>

        <div class="text-center mt-3">
            <a href="doctor_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
        </div>
    </div>
</body>

</html>
