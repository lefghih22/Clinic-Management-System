<?php


class Appointment {
    public $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

   
    public function getAvailableDoctors() {
        $query = "SELECT doctor_id, doctor_name FROM doctor_details";
        $result = $this->conn->query($query);
        return $result;
    }


    public function bookAppointment($patient_id, $doctor_id, $date, $time) {
    // Condition 1: if the doctor already has two appointments on the same day
    $doctor_query = "SELECT COUNT(*) AS total_appointments 
                         FROM appointments 
                         WHERE doctor_id = '$doctor_id' AND appointment_date = '$date'";
    $doctor_result = $this->conn->query($doctor_query);
    
    $row = $doctor_result->fetch_assoc(); 
    $doctor_appointment = $row['total_appointments'];

    // Condition 2: 2 appointments per day per doctor; can have appointment another day all things same
    if ($doctor_appointment >= 2) {
        return "Doctor's daily limit reached for the selected date.";
    }

    // Condition 3: Concurrent time slot under same doctor
    $ts_query = "SELECT * 
                           FROM appointments 
                           WHERE doctor_id = '$doctor_id' AND appointment_date = '$date' AND appointment_time = '$time'";
    $ts_result = $this->conn->query($ts_query);
    
    if ($ts_result->num_rows > 0) {
        return "Time slot is not available for the selected doctor.";
    }

    // Condition 4: Already assigned to doctor on the same day
    $assign_query = "SELECT doctor_id 
                                    FROM appointments 
                                    WHERE patient_id = '$patient_id' AND appointment_date = '$date'";
    $assign_result = $this->conn->query($assign_query);
    
    if ($assign_result->num_rows > 0) {
        return "Patient is already assigned to a doctor on this date.";
    }

    // Insertion
    $insert_appointment = "INSERT INTO appointments (patient_id, doctor_id, appointment_date, appointment_time) 
                               VALUES ('$patient_id', '$doctor_id', '$date', '$time')";
    
    if (!$this->conn->query($insert_appointment)) {
        return "Failed to book appointment: " . $this->conn->error;
    }

    // Get inserted appointment ID
    $appointment_id = $this->conn->insert_id;

    // Add scheduled status
    $insert_status = "UPDATE appointments 
                          SET status = 'Scheduled' 
                          WHERE appointment_id = '$appointment_id'";
    if (!$this->conn->query($insert_status)) {
        return "Failed to add scheduled status: " . $this->conn->error;
    }

    // Condition 5: Patient with no appointment
    $patient_query = "SELECT doctor_id 
                          FROM patient_details 
                          WHERE patient_id = '$patient_id'";
    $patient_result = $this->conn->query($patient_query);
    
    $patient_data = $patient_result->fetch_assoc();
    if (!$patient_data || $patient_data['doctor_id'] === null) {
        $assignDoctorQuery = "UPDATE patient_details 
                              SET doctor_id = '$doctor_id' 
                              WHERE patient_id = '$patient_id'";
        if (!$this->conn->query($assignDoctorQuery)) {
            return "Failed to assign doctor to patient: " . $this->conn->error;
        }
    }

    return "Appointment booked successfully!";
}

    


    // Cancel an appointment
    public function cancelAppointment($appointment_id) {
        $query = "UPDATE appointments SET status = 'Canceled' WHERE appointment_id = '$appointment_id'";
        $stmt = $this->conn->query($query);
        
        if (!$stmt) {
            return "Failed to cancel the appointment: " . $this->conn->error;
        }

        // Update status table
        $statusQuery = "UPDATE status SET status = 'Canceled' WHERE appointment_id = '$appointment_id'";
        if (!$this->conn->query($statusQuery)) {
            return "Failed to update status table: " . $this->conn->error;
        }

        return "Appointment canceled successfully!";
    }

    // Remove canceled appointments
    public function removeCanceledAppointments() {
        $query = "DELETE FROM appointments WHERE status = 'Canceled'";
        if ($this->conn->query($query)) {
            return "Canceled appointments removed successfully.";
        } else {
            return "Failed to remove canceled appointments: " . $this->conn->error;
        }
    }

    // Get appointments for a specific user (doctor or patient)
    public function getAppointments($userType, $userId) {
        $query = "";

        if ($userType === 'doctor') {
            $query = "SELECT a.appointment_id, p.patient_name, a.appointment_date, a.appointment_time, a.status
                      FROM appointments a
                      JOIN patient_details p ON a.patient_id = p.patient_id
                      WHERE a.doctor_id = '$userId'
                      ORDER BY a.appointment_date, a.appointment_time";
        } elseif ($userType === 'patient') {
            $query = "SELECT a.appointment_id, d.doctor_name, a.appointment_date, a.appointment_time, a.status
                      FROM appointments a
                      JOIN doctor_details d ON a.doctor_id = d.doctor_id
                      WHERE a.patient_id = '$userId'
                      ORDER BY a.appointment_date, a.appointment_time";
        }

        $result = $this->conn->query($query);

        if (!$result) {
            die("Error fetching appointments: " . $this->conn->error);
        }

        return $result;
    }

    // Get all appointments for admin
    public function getAppointmentsForAdmin() {
        $query = "SELECT a.appointment_id, p.patient_name, d.doctor_name, a.appointment_date, a.appointment_time, a.status
                  FROM appointments a
                  JOIN patient_details p ON a.patient_id = p.patient_id
                  JOIN doctor_details d ON a.doctor_id = d.doctor_id
                  ORDER BY a.appointment_date, a.appointment_time";
        $result = $this->conn->query($query);

        if (!$result) {
            die("Error fetching appointments: " . $this->conn->error);
        }

        return $result;
    }

    // Check if the time slot is available for a doctor on a given date and time
    public function isTimeSlotAvailableForDoctor($doctor_id, $date, $time) {
        $query = "SELECT * 
                  FROM appointments 
                  WHERE doctor_id = '$doctor_id' AND appointment_date = '$date' AND appointment_time = '$time'";
        $result = $this->conn->query($query);
        return $result->num_rows === 0;
    }

    // Check if a doctor can take more appointments on a given date
    public function canDoctorTakeMoreAppointments($doctor_id, $date) {
        $query = "SELECT COUNT(*) AS total_appointments 
                  FROM appointments 
                  WHERE doctor_id = '$doctor_id' AND appointment_date = '$date'";
        $result = $this->conn->query($query);

        if (!$result) {
            die("Error checking doctor's daily appointments: " . $this->conn->error);
        }

        $count = $result->fetch_assoc()['total_appointments'];
        return $count < 2;
    }
}
?>