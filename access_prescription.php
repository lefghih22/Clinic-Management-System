<?php
include 'config.php';
include 'header_all.php';
session_start();

if (!isset($_SESSION['patient_id'])) {
    header("Location: login.php");
    exit;
}

$patient_id = $_SESSION['patient_id'];

class Prescription
{
    public $conn;
    public $patientId;

    public function __construct($dbConnection, $patient_id)
    {
        $this->conn = $dbConnection;
        $this->patientId = $patient_id;
    }

    public function getPrescriptions()
    {
        $query = "SELECT p.*, d.doctor_name 
                  FROM prescriptions p 
                  LEFT JOIN doctor_details d ON p.doctor_id = d.doctor_id
                  WHERE p.patient_id = $this->patientId 
                  ORDER BY p.prescribed_date DESC";
        return $this->conn->query($query);
    }

    public function getPatientDetails()
    {
        $query = "SELECT * FROM patient_details WHERE patient_id = $this->patientId";
        return $this->conn->query($query)->fetch_assoc();
    }
}

$prescription = new Prescription($conn, $patient_id);
$patient = $prescription->getPatientDetails();
$reports = $prescription->getPrescriptions();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Access Prescription</title>
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

        .page-wrapper {
            max-width: 780px;
            margin: 3.5rem auto;
            padding: 0 1.5rem;
            flex: 1;
            width: 100%;
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

        /* PRESCRIPTION CARD */
        .rx-card {
            background: var(--white);
            border-radius: 24px;
            padding: 2rem;
            margin-bottom: 1.5rem;
            border: 2px solid transparent;
            box-shadow: 0 8px 32px rgba(10, 36, 114, 0.07);
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
            animation: fadeUp 0.5s ease both;
        }

        .rx-card::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, #8B5CF6, #EC4899);
        }

        .rx-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 16px 40px rgba(10, 36, 114, 0.11);
            border-color: #DDD6FE;
        }

        .rx-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            flex-wrap: wrap;
            gap: 0.75rem;
            margin-bottom: 1.25rem;
        }

        .rx-title {
            font-family: 'Sora', sans-serif;
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--blue-deep);
        }

        .rx-date {
            background: var(--blue-light);
            color: var(--blue-mid);
            font-size: 0.78rem;
            font-weight: 600;
            padding: 4px 12px;
            border-radius: 50px;
        }

        .rx-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.75rem 1.5rem;
            margin-bottom: 1.25rem;
        }

        .rx-field label {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--text-muted);
            display: block;
            margin-bottom: 3px;
        }

        .rx-field span {
            font-size: 0.93rem;
            color: var(--text-dark);
            font-weight: 500;
        }

        .rx-notes {
            background: var(--blue-pale);
            border-radius: 12px;
            padding: 12px 16px;
            margin-bottom: 1.25rem;
        }

        .rx-notes label {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--text-muted);
            display: block;
            margin-bottom: 4px;
        }

        .rx-notes p {
            font-size: 0.93rem;
            color: var(--text-dark);
            line-height: 1.6;
        }

        .rx-footer {
            display: flex;
            justify-content: flex-end;
        }

        .btn-download {
            background: #8B5CF6;
            color: white;
            padding: 9px 22px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 0.85rem;
            text-decoration: none;
            transition: all 0.2s;
            font-family: 'DM Sans', sans-serif;
            box-shadow: 0 4px 14px rgba(139, 92, 246, 0.3);
        }

        .btn-download:hover {
            background: #7C3AED;
            transform: scale(1.04);
            color: white;
        }

        /* EMPTY STATE */
        .empty-state {
            background: var(--white);
            border-radius: 24px;
            padding: 3.5rem 2rem;
            text-align: center;
            box-shadow: 0 8px 32px rgba(10, 36, 114, 0.07);
        }

        .empty-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        .empty-state h3 {
            font-family: 'Sora', sans-serif;
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--blue-deep);
            margin-bottom: 0.5rem;
        }

        .empty-state p {
            color: var(--text-muted);
            font-size: 0.93rem;
        }

        /* BACK BUTTON */
        .back-section {
            text-align: center;
            margin-top: 2rem;
        }

        .btn-back {
            background: transparent;
            color: var(--text-muted);
            border: 1.5px solid #CBD5E1;
            padding: 11px 28px;
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

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(18px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 600px) {
            .page-wrapper {
                margin: 2rem auto;
            }

            .rx-grid {
                grid-template-columns: 1fr;
            }

            .rx-header {
                flex-direction: column;
            }
        }
    </style>
</head>

<body>

    <div class="page-wrapper">
        <div class="page-header">
            <span class="eyebrow">Patient Portal</span>
            <h2>My Prescriptions</h2>
        </div>

        <?php if ($reports && $reports->num_rows > 0): ?>
            <?php while ($row = $reports->fetch_assoc()): ?>
                <div class="rx-card">
                    <div class="rx-header">
                        <span class="rx-title">💊 <?= htmlspecialchars($row['medication']) ?></span>
                        <span class="rx-date"><?= date('d M Y', strtotime($row['prescribed_date'])) ?></span>
                    </div>

                    <div class="rx-grid">
                        <div class="rx-field">
                            <label>Patient</label>
                            <span><?= htmlspecialchars($patient['patient_name']) ?></span>
                        </div>
                        <div class="rx-field">
                            <label>Prescribed By</label>
                            <span>Dr. <?= htmlspecialchars($row['doctor_name'] ?? 'N/A') ?></span>
                        </div>
                        <div class="rx-field">
                            <label>Dosage</label>
                            <span><?= htmlspecialchars($row['dosage']) ?></span>
                        </div>
                        <div class="rx-field">
                            <label>Frequency</label>
                            <span><?= htmlspecialchars($row['frequency'] ?: 'N/A') ?></span>
                        </div>
                        <div class="rx-field">
                            <label>Duration</label>
                            <span><?= htmlspecialchars($row['duration'] ?: 'N/A') ?></span>
                        </div>
                    </div>

                    <?php if (!empty($row['notes'])): ?>
                        <div class="rx-notes">
                            <label>Notes</label>
                            <p><?= htmlspecialchars($row['notes']) ?></p>
                        </div>
                    <?php endif; ?>

                    <div class="rx-footer">
                        <a href="download.php?id=<?= $row['prescription_id'] ?>" class="btn-download">⬇ Download</a>
                    </div>
                </div>
            <?php endwhile; ?>

        <?php else: ?>
            <div class="empty-state">
                <div class="empty-icon">📋</div>
                <h3>No Prescriptions Yet</h3>
                <p>Your doctor hasn't issued any prescriptions for you yet.</p>
            </div>
        <?php endif; ?>

        <div class="back-section">
            <a href="patient_dashboard.php" class="btn-back">← Back to Dashboard</a>
        </div>
    </div>

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