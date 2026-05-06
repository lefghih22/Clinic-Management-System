<?php
include 'config.php';

session_start();

if (!isset($_SESSION['doctor_id'])) {
    header("Location: doctor_login.php");
    exit();
}

$doctor_id = $_SESSION['doctor_id'];
$alert = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patient_id     = $_POST['patient_id'];
    $medication     = trim($_POST['medication']);
    $dosage         = trim($_POST['dosage']);
    $frequency      = trim($_POST['frequency']);
    $duration       = trim($_POST['duration']);
    $notes          = trim($_POST['notes']);

    if (!empty($patient_id) && !empty($medication) && !empty($dosage)) {
        $sql = "INSERT INTO prescriptions (doctor_id, patient_id, medication, dosage, frequency, duration, notes, prescribed_date)
                VALUES ('$doctor_id', '$patient_id', '$medication', '$dosage', '$frequency', '$duration', '$notes', NOW())";
        if ($conn->query($sql)) {
            $alert = ['type' => 'success', 'message' => 'Prescription written successfully!'];
        } else {
            $alert = ['type' => 'danger', 'message' => 'Something went wrong. Please try again.'];
        }
    } else {
        $alert = ['type' => 'danger', 'message' => 'Please fill in all required fields.'];
    }
}

// Fetch patients assigned to this doctor
$patients_result = $conn->query("SELECT patient_id, patient_name FROM patient_details ORDER BY patient_name ASC");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Write Prescription</title>
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
            max-width: 680px;
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
            background: linear-gradient(90deg, #8B5CF6, #EC4899);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0 1.25rem;
        }

        .form-card label {
            font-weight: 600;
            font-size: 0.88rem;
            color: var(--text-dark);
            margin-bottom: 6px;
            display: block;
        }

        .required-star {
            color: #EF4444;
            margin-left: 2px;
        }

        .form-card input,
        .form-card select,
        .form-card textarea {
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

        .form-card textarea {
            resize: vertical;
            min-height: 100px;
        }

        .form-card input:focus,
        .form-card select:focus,
        .form-card textarea:focus {
            border-color: #8B5CF6;
            box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.12);
        }

        .form-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 0.5rem;
            gap: 1rem;
        }

        .btn-submit {
            background: #8B5CF6;
            color: white;
            border: none;
            padding: 11px 30px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 0.93rem;
            cursor: pointer;
            font-family: 'DM Sans', sans-serif;
            transition: all 0.2s;
            box-shadow: 0 4px 14px rgba(139, 92, 246, 0.3);
        }

        .btn-submit:hover {
            background: #7C3AED;
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

            .form-row {
                grid-template-columns: 1fr;
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
        <a class="nav-brand" href="doctor_dashboard.php">
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
            <span class="eyebrow">Doctor Portal</span>
            <h2>Write Prescription</h2>
        </div>

        <div class="form-card">

            <?php if ($alert): ?>
                <div class="alert-box alert-<?= $alert['type'] ?>">
                    <?= htmlspecialchars($alert['message']) ?>
                </div>
            <?php endif; ?>

            <form method="POST">

                <label for="patient_id">Select Patient <span class="required-star">*</span></label>
                <select name="patient_id" id="patient_id" required>
                    <option value="">— Choose a patient —</option>
                    <?php if ($patients_result && $patients_result->num_rows > 0): ?>
                        <?php while ($p = $patients_result->fetch_assoc()): ?>
                            <option value="<?= htmlspecialchars($p['patient_id']) ?>">
                                <?= htmlspecialchars($p['patient_name']) ?>
                            </option>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <option value="" disabled>No patients found</option>
                    <?php endif; ?>
                </select>

                <label for="medication">Medication <span class="required-star">*</span></label>
                <input type="text" name="medication" id="medication"
                    placeholder="e.g. Amoxicillin 500mg" required>

                <div class="form-row">
                    <div>
                        <label for="dosage">Dosage <span class="required-star">*</span></label>
                        <input type="text" name="dosage" id="dosage" placeholder="e.g. 1 tablet" required>
                    </div>
                    <div>
                        <label for="frequency">Frequency</label>
                        <input type="text" name="frequency" id="frequency" placeholder="e.g. Twice daily">
                    </div>
                </div>

                <label for="duration">Duration</label>
                <input type="text" name="duration" id="duration" placeholder="e.g. 7 days">

                <label for="notes">Additional Notes</label>
                <textarea name="notes" id="notes" placeholder="Any special instructions or notes for the patient..."></textarea>

                <div class="form-actions">
                    <a href="doctor_dashboard.php" class="btn-back">← Back to Dashboard</a>
                    <button type="submit" class="btn-submit">Submit Prescription</button>
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