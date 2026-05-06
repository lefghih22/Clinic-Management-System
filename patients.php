<?php
class Patients {
    public $db;

    // Constructor to initialize the database connection
    public function __construct($conn) {
        $this->db = $conn;
    }

    // Get all patients assigned to a specific doctor
    public function getPatientsByDoctor($doctor_id) {
        $query = "SELECT * FROM patient_details WHERE doctor_id = $doctor_id";
        $result = $this->db->query($query);
        if ($result && $result->num_rows > 0) {
            return $result;
        }
        return null;
    }

    // Get patient details by patient ID
    public function getPatientDetails($patient_id) {
        $query = "SELECT * FROM patient_details WHERE patient_id = $patient_id";
        $result = $this->db->query($query);
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }
}
?>
