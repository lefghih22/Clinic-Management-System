<?php
include 'config.php';
include 'Appointment.php';
include 'header_all.php';

session_start();

if (!isset($_SESSION['patient_id'])) {
    header("Location: login.php");
    exit();
}

$patientId = $_SESSION['patient_id'];
$appointment = new Appointment($conn);

$message = "";
$alertType = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $doctorId = $_POST['doctor_id'];
    $date = $_POST['appointment_date'];
    $time = $_POST['appointment_time'];

    $result = $appointment->bookAppointment($patientId, $doctorId, $date, $time);

    if ($result === "Appointment booked successfully!") {
        $message = $result;
        $alertType = "success";
    } else {
        $message = $result;
        $alertType = "danger";
    }
}

$availableDoctors = $appointment->getAvailableDoctors();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Appointment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
        }

        .page-wrapper {
            max-width: 620px;
            margin: 3.5rem auto;
            padding: 0 1.5rem;
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

        .form-card {
            background: var(--white);
            border-radius: 24px;
            padding: 2.5rem;
            border: 2px solid transparent;
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
            background: linear-gradient(90deg, #3B82F6, #818CF8);
        }

        .form-card label {
            font-weight: 600;
            font-size: 0.88rem;
            color: var(--text-dark);
            margin-bottom: 6px;
            display: block;
        }

        .form-card select,
        .form-card input[type="date"],
        .form-card input[type="time"] {
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

        .form-card select:focus,
        .form-card input:focus {
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
            background: var(--blue-bright);
            color: white;
            border: none;
            padding: 11px 30px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 0.93rem;
            cursor: pointer;
            font-family: 'DM Sans', sans-serif;
            transition: all 0.2s;
            box-shadow: 0 4px 14px rgba(59, 130, 246, 0.3);
        }

        .btn-submit:hover {
            background: #2563EB;
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

        @media (max-width: 640px) {
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

    <div class="page-wrapper">
        <div class="page-header">
            <span class="eyebrow">Patient Portal</span>
            <h2>Book an Appointment</h2>
        </div>

        <div class="form-card">

            <?php if (!empty($message)): ?>
                <div class="alert-box alert-<?= $alertType; ?>">
                    <?= htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <label for="doctor_id">Select Doctor</label>
                <select name="doctor_id" id="doctor_id" required>
                    <?php if ($availableDoctors->num_rows > 0): ?>
                        <?php while ($doctor = $availableDoctors->fetch_assoc()): ?>
                            <option value="<?= htmlspecialchars($doctor['doctor_id']); ?>">
                                <?= htmlspecialchars($doctor['doctor_name']); ?>
                            </option>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <option value="" disabled>No doctors available</option>
                    <?php endif; ?>
                </select>

                <label for="appointment_date">Appointment Date</label>
                <input type="date" name="appointment_date" id="appointment_date" required>

                <label for="appointment_time">Appointment Time</label>
                <input type="time" name="appointment_time" id="appointment_time" required>

                <div class="form-actions">
                    <a href="patient_dashboard.php" class="btn-back">← Back to Dashboard</a>
                    <button type="submit" class="btn-submit">Book Appointment</button>
                </div>
            </form>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>