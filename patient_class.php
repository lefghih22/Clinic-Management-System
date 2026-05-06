<?php

class Patient {

    public $db;

    public function __construct($conn) {
        $this->db = $conn;
    }

    public function getPatientDetails($patient_id) {
        $query = "SELECT * FROM patient_details WHERE patient_id = $patient_id";
        return $this->db->query($query)->fetch_assoc();
    }

    public function updatePatientProfile($patient_id, $name, $age, $email, $address, $history, $password = null) {
        
        //Password is updated if only new password is provided
        if ($password) {
            $query = "UPDATE patient_details SET 
                patient_name = '$name', 
                patient_age = $age, 
                patient_email = '$email', 
                patient_address = '$address', 
                medical_history = '$history',
                patient_password = '$password' 
                WHERE patient_id = $patient_id";
        } else {
            $query = "UPDATE patient_details SET 
                patient_name = '$name', 
                patient_age = $age, 
                patient_email = '$email',
                patient_address = '$address', 
                medical_history = '$history'
                WHERE patient_id = $patient_id";
        }
        return $this->db->query($query);
    }
}

?>