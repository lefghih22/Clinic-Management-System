<?php
include 'config.php';
include 'header_all.php';

session_start();

if (!isset($_SESSION['patient_id'])) {
    header("Location: login.php");
    exit();
}

$patient_id = $_SESSION['patient_id'];

class ViewBill {
    public $conn;
    public $patient_id;
    public $bill_details;

    public function __construct($dbConnection, $patient_id) {
        $this->conn = $dbConnection;
        $this->patient_id = $patient_id;
    }

    public function fetch_bill() {
        $query = "
            SELECT vb.bill_id, vb.Bill_status, vb.date, 
                   pd.patient_name, pd.patient_age, pd.patient_email, pd.patient_gender, 
                   dd.doctor_name, 
                   gb.services, gb.total_amount
            FROM view_bill vb
            INNER JOIN patient_details pd ON vb.patient_id = pd.patient_id
            INNER JOIN doctor_details dd ON vb.doctor_id = dd.doctor_id
            INNER JOIN generate_bill gb ON vb.bill_id = gb.bill_id
            WHERE vb.patient_id = $this->patient_id
        ";

        $result = $this->conn->query($query);

        if ($result && $result->num_rows > 0) {
            $this->bill_details = $result->fetch_assoc();
        } else {
            $this->bill_details = null;
        }
    }

    public function displayBill() {
        ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Mets Tags -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Bill</title>

    <!-- Bootstrap  CSS-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">


    <div class="container mt-3">
        <h1 class="text-center">Your Bill</h1>
        <?php if ($this->bill_details): ?>
        <div class="table-responsive mt-3">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Bill</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Bill ID</td>
                        <td><?= htmlspecialchars($this->bill_details['bill_id']) ?></td>
                    </tr>
                    <tr>
                        <td>Patient Name</td>
                        <td><?= htmlspecialchars($this->bill_details['patient_name']) ?></td>
                    </tr>
                    <tr>
                        <td>Doctor Name</td>
                        <td><?= htmlspecialchars($this->bill_details['doctor_name']) ?></td>
                    </tr>
                    <tr>
                        <td>Services</td>
                        <td><?= htmlspecialchars($this->bill_details['services']) ?></td>
                    </tr>
                    <tr>
                        <td>Total Amount</td>
                        <td><?= htmlspecialchars($this->bill_details['total_amount']) ?> BDT</td>
                    </tr>
                    <tr>
                        <td>Patient Age</td>
                        <td><?= htmlspecialchars($this->bill_details['patient_age']) ?></td>
                    </tr>
                    <tr>
                        <td>Patient Email</td>
                        <td><?= htmlspecialchars($this->bill_details['patient_email']) ?></td>
                    </tr>
                    <tr>
                        <td>Patient Gender</td>
                        <td><?= htmlspecialchars($this->bill_details['patient_gender']) ?></td>
                    </tr>
                    <tr>
                        <td>Bill Status</td>
                        <td><?= htmlspecialchars($this->bill_details['Bill_status']) ?></td>
                    </tr>
                    <tr>
                        <td>Bill Date</td>
                        <td><?= htmlspecialchars($this->bill_details['date']) ?></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Buttons -->
        <div class="d-flex justify-content-start mt-3">
            <a href="pay_bill.php?bill_id=<?= $this->bill_details['bill_id'] ?>" class="btn btn-primary me-2">Pay
                Bill</a>
            <a href="patient_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
        </div>

        <?php else: ?>
        <div class="alert alert-warning text-center">
            No bills found for your account.
        </div>

        <!-- Back Button -->
        <div class="text-center mt-3">
            <a href="patient_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
        </div>
        <?php endif; ?>
    </div>
</body>

</html>
<?php
    }
}

$view_bill = new ViewBill($conn, $patient_id);
$view_bill->fetch_bill();
$view_bill->displayBill();
?>