<?php
include 'config.php';
include 'header_all.php';

class Doctor {
    public $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function add($name, $email, $specialist, $password) {
    
        $name = htmlspecialchars($name);
        $email = htmlspecialchars($email);
        $specialist = htmlspecialchars($specialist);
        $password = htmlspecialchars($password);

        // Insert into the database
        $sql = "INSERT INTO doctor_details (doctor_name, doctor_email, specialist, doctor_password) 
                VALUES ('$name', '$email', '$specialist', '$password')";

        if ($this->conn->query($sql)) {
            return "Doctor added successfully!";
        } else {
            return "Error: " . $this->conn->error;
        }
    }
}

// Initialize the Doctor class
$doctor = new Doctor($conn);

$message = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Fetch form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $specialist = $_POST['specialist'];
    $password = $_POST['password'];

    // Add doctor and display the result message
    $message = $doctor->add($name, $email, $specialist, $password);
    echo "<script>alert('$message');</script>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Add Doctor</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <h1 class="text-center">Add Doctor</h1>
        <form action="" method="POST">
            <div class="form-group">
                <label for="name">Doctor Name</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="email">Doctor Email</label>
                <input type="email" name="email" id="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="specialist">Specialist</label>
                <select name="specialist" id="specialist" class="form-control" required>
                    <option value="Cardiologist">Cardiologist</option>
                    <option value="Surgeon">Surgeon</option>
                    <option value="Dermatologist">Dermatologist</option>
                    <option value="Pediatrician">Pediatrician</option>
                    <option value="General Physician">General Physician</option>
                </select>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Doctor</button>
        </form>

        <div class="text-center mt-4">
            <a href="admin_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
        </div>
    </div>
</body>

</html>
