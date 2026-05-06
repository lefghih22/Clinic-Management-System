<?php
include 'config.php';

if (isset($_GET['query'])) {
    $query = trim($_GET['query']);

    if (empty($query)) {
        echo json_encode([]);
        exit();
    }

    $safe_query = $conn->real_escape_string($query);

    $sql = "SELECT doctor_id, doctor_name, specialist 
            FROM doctor_details 
            WHERE doctor_name LIKE '%$safe_query%' 
               OR specialist LIKE '%$safe_query%'
            LIMIT 8";

    $result = $conn->query($sql);
    $doctors = [];

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $doctors[] = [
                'id'         => $row['doctor_id'],
                'name'       => $row['doctor_name'],
                'specialist' => $row['specialist'] ?? 'General'
            ];
        }
    }

    header('Content-Type: application/json');
    echo json_encode($doctors);
    exit();
}
