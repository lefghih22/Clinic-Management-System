<?php
include 'config.php';

class DownloadPrescription {
    public $conn;
    public $report_id;

    public function __construct($dbConnection, $report_id) {
        $this->conn = $dbConnection;
        $this->report_id = $report_id;
    }

    public function getReport() {
        $query = "SELECT * FROM patient_report WHERE report_id = $this->report_id";
        return $this->conn->query($query)->fetch_assoc();
    }

    public function getPatientDetails($patient_id) {
        $query = "SELECT patient_name, patient_gender, patient_age, medical_history FROM patient_details WHERE patient_id = patient_id";
        return $this->conn->query($query)->fetch_assoc();
    }

    public function generateDownload($report, $patient) {
        $filename = "Prescription_" . $this->report_id . ".txt";
        header("Content-Type: text/plain");
        header("Content-Disposition: attachment; filename=$filename");

        echo "Date: " . $report['date'] . "\n";
        echo "Patient Name: " . $patient['patient_name'] . "\n";
        echo "Gender: " . $patient['patient_gender'] . "\n";
        echo "Age: " . $patient['patient_age'] . "\n";
        echo "Medical History: " . $patient['medical_history'] . "\n";
        echo "Prescription: " . $report['prescription'] . "\n";
        exit;
    }
}


if (!isset($_GET['id'])) {
    die("Invalid request.");
}

$report_id = $_GET['id'];

$downloadPrescription = new DownloadPrescription($conn, $report_id);
$report = $downloadPrescription->getReport();

if ($report) {
    $patient = $downloadPrescription->getPatientDetails($report['patient_id']);
    $downloadPrescription->generateDownload($report, $patient);
} 