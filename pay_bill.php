<?php
include 'config.php';

session_start();

if (!isset($_SESSION['patient_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['bill_id'])) {
    header("Location: patient_dashboard.php");
    exit();
}

$bill_id = $_GET['bill_id'];

class PayBill
{
    public $conn;
    public $bill_id;
    public $alert;

    public function __construct($dbConnection, $bill_id)
    {
        $this->conn = $dbConnection;
        $this->bill_id = $bill_id;
    }

    public function handlePayment()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $account_number = $_POST['account_number'];
            $account_name = $_POST['account_name'];
            $amount = $_POST['amount'];
            $payment_method = $_POST['payment_method'];

            if ($this->validPayment($amount)) {
                $this->processPayment($account_number, $account_name, $amount, $payment_method);
                $this->alert = ['type' => 'success', 'message' => 'Payment successful!'];
            } else {
                $this->alert = ['type' => 'danger', 'message' => 'Invalid payment amount.'];
            }
        }
    }

    public function validPayment($amount)
    {
        $query = "SELECT total_amount FROM generate_bill WHERE bill_id = '$this->bill_id'";
        $result = $this->conn->query($query);
        $row = $result->fetch_assoc();
        return $row && $amount == $row['total_amount'];
    }

    public function processPayment($account_number, $account_name, $amount, $payment_method)
    {
        $update_view_bill = "UPDATE view_bill SET Bill_status = 'Paid' WHERE bill_id = '$this->bill_id'";
        $update_generate_bill = "UPDATE generate_bill SET total_amount = 0 WHERE bill_id = '$this->bill_id'";
        $insert_payment = "INSERT INTO pay_bill (bill_id, payment_date, payment_method, payment_status, paid_amount) 
                          VALUES ('$this->bill_id', NOW(), '$payment_method', 'Paid', '$amount')";

        $this->conn->query($update_view_bill);
        $this->conn->query($update_generate_bill);
        $this->conn->query($insert_payment);
    }

    public function displayPayBill()
    {
?>
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Pay Bill</title>
            <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700;800&family=DM+Sans:wght@400;500&display=swap" rel="stylesheet">
            <style>
                *,
                *::before,
                *::after {
                    margin: 0;
                    padding: 0;
                    box-sizing: border-box;
                }

                :root {
                    --blue-deep: #0A2472;
                    --blue-mid: #1D4ED8;
                    --blue-bright: #3B82F6;
                    --blue-light: #DBEAFE;
                    --blue-pale: #EFF6FF;
                    --white: #ffffff;
                    --text-dark: #0F172A;
                    --text-muted: #64748B;
                }

                body {
                    font-family: 'DM Sans', sans-serif;
                    background: var(--blue-pale);
                    color: var(--text-dark);
                    min-height: 100vh;
                    display: flex;
                    flex-direction: column;
                }

                /* NAV */
                nav {
                    position: sticky;
                    top: 0;
                    z-index: 100;
                    background: var(--blue-deep);
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    padding: 0 2.5rem;
                    height: 68px;
                    box-shadow: 0 4px 24px rgba(10, 36, 114, 0.25);
                }

                .nav-brand {
                    font-family: 'Sora', sans-serif;
                    font-weight: 800;
                    font-size: 1.3rem;
                    color: var(--white);
                    text-decoration: none;
                    display: flex;
                    align-items: center;
                    gap: 10px;
                }

                .brand-icon {
                    width: 36px;
                    height: 36px;
                    background: var(--blue-bright);
                    border-radius: 10px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 18px;
                }

                .nav-search {
                    display: flex;
                    gap: 8px;
                }

                .nav-search input {
                    background: rgba(255, 255, 255, 0.12);
                    border: 1.5px solid rgba(255, 255, 255, 0.2);
                    color: white;
                    padding: 7px 16px;
                    border-radius: 50px;
                    font-size: 0.875rem;
                    outline: none;
                    width: 180px;
                    transition: all 0.2s;
                    font-family: 'DM Sans', sans-serif;
                }

                .nav-search input::placeholder {
                    color: rgba(255, 255, 255, 0.45);
                }

                .nav-search input:focus {
                    background: rgba(255, 255, 255, 0.2);
                    border-color: var(--blue-bright);
                }

                .nav-search button {
                    background: var(--blue-bright);
                    color: white;
                    border: none;
                    padding: 7px 18px;
                    border-radius: 50px;
                    font-size: 0.875rem;
                    font-weight: 600;
                    cursor: pointer;
                    transition: background 0.2s;
                    font-family: 'DM Sans', sans-serif;
                }

                .nav-search button:hover {
                    background: #2563EB;
                }

                /* PAGE */
                .page-wrapper {
                    max-width: 620px;
                    margin: 3.5rem auto;
                    padding: 0 1.5rem;
                    flex: 1;
                }

                .page-header {
                    margin-bottom: 2rem;
                }

                .eyebrow {
                    display: inline-block;
                    background: var(--blue-light);
                    color: var(--blue-mid);
                    font-size: 0.72rem;
                    font-weight: 700;
                    letter-spacing: 0.1em;
                    text-transform: uppercase;
                    padding: 4px 14px;
                    border-radius: 50px;
                    margin-bottom: 0.75rem;
                }

                .page-header h2 {
                    font-family: 'Sora', sans-serif;
                    font-size: 1.9rem;
                    font-weight: 800;
                    color: var(--blue-deep);
                }

                /* FORM CARD */
                .form-card {
                    background: var(--white);
                    border-radius: 24px;
                    padding: 2.5rem;
                    box-shadow: 0 8px 32px rgba(10, 36, 114, 0.08);
                    position: relative;
                    overflow: hidden;
                }

                .form-card::after {
                    content: '';
                    position: absolute;
                    top: 0;
                    left: 0;
                    right: 0;
                    height: 5px;
                    background: linear-gradient(90deg, #0EB09D, #10B981);
                }

                .form-card label {
                    font-weight: 600;
                    font-size: 0.88rem;
                    color: var(--text-dark);
                    margin-bottom: 6px;
                    display: block;
                }

                .form-card input,
                .form-card select {
                    width: 100%;
                    padding: 10px 16px;
                    border: 1.5px solid #E2E8F0;
                    border-radius: 12px;
                    font-size: 0.93rem;
                    font-family: 'DM Sans', sans-serif;
                    color: var(--text-dark);
                    background: var(--white);
                    outline: none;
                    transition: border-color 0.2s, box-shadow 0.2s;
                    margin-bottom: 1.25rem;
                }

                .form-card input:focus,
                .form-card select:focus {
                    border-color: var(--blue-bright);
                    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.12);
                }

                .form-actions {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    margin-top: 0.5rem;
                    gap: 1rem;
                }

                .btn-submit {
                    background: #0EB09D;
                    color: white;
                    border: none;
                    padding: 11px 30px;
                    border-radius: 50px;
                    font-weight: 700;
                    font-size: 0.93rem;
                    cursor: pointer;
                    font-family: 'DM Sans', sans-serif;
                    transition: all 0.2s;
                    box-shadow: 0 4px 14px rgba(14, 176, 157, 0.3);
                }

                .btn-submit:hover {
                    background: #0d9e8c;
                    transform: translateY(-2px);
                }

                .btn-back {
                    background: transparent;
                    color: var(--text-muted);
                    border: 1.5px solid #CBD5E1;
                    padding: 11px 24px;
                    border-radius: 50px;
                    font-weight: 600;
                    font-size: 0.88rem;
                    text-decoration: none;
                    font-family: 'DM Sans', sans-serif;
                    transition: all 0.2s;
                }

                .btn-back:hover {
                    border-color: var(--blue-bright);
                    color: var(--blue-bright);
                }

                /* ALERTS */
                .alert-box {
                    padding: 12px 18px;
                    border-radius: 12px;
                    font-size: 0.9rem;
                    font-weight: 500;
                    margin-bottom: 1.5rem;
                }

                .alert-success {
                    background: #D1FAE5;
                    color: #065F46;
                    border: 1px solid #A7F3D0;
                }

                .alert-danger {
                    background: #FEE2E2;
                    color: #991B1B;
                    border: 1px solid #FECACA;
                }

                /* FOOTER */
                footer {
                    background: #040E2B;
                    padding: 1.5rem 2.5rem;
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    flex-wrap: wrap;
                    gap: 1rem;
                }

                footer p {
                    color: rgba(255, 255, 255, 0.35);
                    font-size: 0.85rem;
                }

                footer a {
                    color: rgba(255, 255, 255, 0.45);
                    text-decoration: none;
                    transition: color 0.2s;
                    font-size: 0.85rem;
                }

                footer a:hover {
                    color: white;
                }

                .footer-links {
                    display: flex;
                    gap: 1.5rem;
                }

                @media (max-width: 640px) {
                    nav {
                        padding: 0 1.25rem;
                    }

                    .nav-search input {
                        width: 120px;
                    }

                    .page-wrapper {
                        margin: 2rem auto;
                    }

                    .form-card {
                        padding: 1.75rem 1.25rem;
                    }

                    .form-actions {
                        flex-direction: column-reverse;
                    }

                    .btn-submit,
                    .btn-back {
                        width: 100%;
                        text-align: center;
                    }
                }
            </style>
        </head>

        <body>

            <!-- NAV -->
            <nav>
                <a class="nav-brand" href="patient_dashboard.php">
                    <span class="brand-icon">🏥</span>
                    Clinic Management
                </a>
                <div class="nav-search">
                    <input type="search" placeholder="Search...">
                    <button>Search</button>
                </div>
            </nav>

            <!-- PAGE -->
            <div class="page-wrapper">
                <div class="page-header">
                    <span class="eyebrow">Patient Portal</span>
                    <h2>Pay Your Bill</h2>
                </div>

                <div class="form-card">

                    <?php if (isset($this->alert)): ?>
                        <div class="alert-box alert-<?= $this->alert['type'] ?>">
                            <?= htmlspecialchars($this->alert['message']) ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST">
                        <label for="account_number">Account Number</label>
                        <input type="text" name="account_number" id="account_number"
                            placeholder="Enter your account number" required>

                        <label for="account_name">Account Name</label>
                        <input type="text" name="account_name" id="account_name"
                            placeholder="Enter your account name" required>

                        <label for="amount">Amount</label>
                        <input type="number" name="amount" id="amount"
                            placeholder="Enter the amount" required>

                        <label for="payment_method">Payment Method</label>
                        <select name="payment_method" id="payment_method" required>
                            <option value="">Select Payment Method</option>
                            <option value="Credit Card">Credit Card</option>
                            <option value="Debit Card">Debit Card</option>
                            <option value="Online Banking">Online Banking</option>
                        </select>

                        <div class="form-actions">
                            <a href="patient_dashboard.php" class="btn-back">← Back to Dashboard</a>
                            <button type="submit" class="btn-submit">Pay Now</button>
                        </div>
                    </form>

                </div>
            </div>

            <!-- FOOTER -->
            <footer>
                <p>&copy; <?= date('Y') ?> Clinic Management. All Rights Reserved.</p>
                <div class="footer-links">
                    <a href="#">Back to top ↑</a>
                    <a href="#">Privacy</a>
                    <a href="#">Terms</a>
                </div>
            </footer>

        </body>

        </html>
<?php
    }
}

$pay_bill = new PayBill($conn, $bill_id);
$pay_bill->handlePayment();
$pay_bill->displayPayBill();
?>