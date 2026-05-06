<?php
class Doctor {
    private $db;

    public function __construct($conn) {
        $this->db = $conn;
    }

    // Fetch details of a specific doctor by Id
    public function getDoctorDetails($doctor_id) {
        $doctor_id = $this->sanitizeInput($doctor_id);
        $query = "SELECT * FROM doctor_details WHERE doctor_id = $doctor_id";
        $result = $this->db->query($query);

        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return null; 
        }
    }

    // Update doctor profile
    public function updateDoctorProfile($doctor_id, $name, $email, $specialist, $password = null) {
        $doctor_id = $this->sanitizeInput($doctor_id);
        $name = $this->sanitizeInput($name);
        $email = $this->sanitizeInput($email);
        $specialist = $this->sanitizeInput($specialist);

        if ($password) {
            $hashedPassword = $this->sanitizeInput($password);
            $query = "UPDATE doctor_details 
                      SET doctor_name = '$name', doctor_email = '$email', specialist = '$specialist', doctor_password = '$hashedPassword' 
                      WHERE doctor_id = $doctor_id";
        } else {
            $query = "UPDATE doctor_details 
                      SET doctor_name = '$name', doctor_email = '$email', specialist = '$specialist' 
                      WHERE doctor_id = $doctor_id";
        }

        return $this->db->query($query);
    }

    // Fetch all categories for the dropdown
    public function getCategories() {
        $query = "SELECT * FROM categories";
        return $this->db->query($query);
    }

    // Sanitize input to prevent SQL injection and XSS
    private function sanitizeInput($input) {
        return htmlspecialchars(strip_tags($input));
    }
}
?>
