<?php

include 'config.php';
include 'header_all.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form inputs
    $doctor_email = trim($_POST['doctor_email']);
    $password = trim($_POST['password']);

    // Check if inputs are not empty
    if (!empty($doctor_email) && !empty($password)) {
        // Query the doctor_details table
        $sql = "SELECT * FROM doctor_details WHERE doctor_email = '$doctor_email'";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            $doctor = $result->fetch_assoc();

            // Compare password
            if ($password === $doctor['doctor_password']) {

                $_SESSION['doctor_id'] = $doctor['doctor_id'];
                $_SESSION['doctor_email'] = $doctor['doctor_email'];


                header("Location: doctor_dashboard.php");
                exit;
            } else {
                $error = "Invalid password.";
            }
        } else {
            $error = "Doctor not found.";
        }
    } else {
        $error = "Doctor email and Password cannot be empty.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        footer {
            background-color: #343a40;
            color: white;
            text-align: center;
            padding: 1rem 0;
            margin-top: 5vh;
        }

        .login-container {
            max-width: 900px;
            margin: 50px auto;
            height: 70vh;
            display: flex;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .illustration {
            background-color: #ffffff;
            color: white;
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .illustration img {
            width: 100%;
        }

        .form-container {
            flex: 1;
            background-color: white;
            padding: 40px;
        }

        .form-container h2 {
            margin-bottom: 30px;
            color: #4a90e2;
        }

        .btn-primary {
            background-color: #4a90e2;
            border-color: #4a90e2;
        }
    </style>
</head>

<body>



    <div class="login-container">

        <div class="illustration">
            <img src="11.jpg" alt="Illustration">
        </div>


        <div class="form-container">
            <h2>Doctor Login</h2>
            <?php if (isset($error)): ?>
                <div class="alert alert-danger text-center"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <form method="POST">
                <div class="mb-3">
                    <label for="doctor_email" class="form-label">Doctor Email</label>
                    <input type="text" name="doctor_email" id="doctor_email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" id="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Login</button>
            </form>
        </div>
    </div>
    <footer>
        <p>&copy; <?= date('Y') ?> Clinic Management. All Rights Reserved.</p>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>