<?php
include 'config.php';

session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$admin_id = $_SESSION['admin_id'];

class AdminDashboard
{
    public $conn;
    public $adminId;
    public $adminName;

    public function __construct($dbConnection, $admin_id)
    {
        $this->conn = $dbConnection;
        $this->adminId = $admin_id;
    }

    public function fetchAdminName()
    {
        $sql = "SELECT admin_name FROM admin_details WHERE admin_id = $this->adminId";
        $result = $this->conn->query($sql);
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $this->adminName = $row['admin_name'];
        } else {
            $this->adminName = 'Admin';
        }
    }

    public function getAdminName()
    {
        return htmlspecialchars($this->adminName);
    }

    public function showDashboard()
    {
?>
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Admin Dashboard</title>
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
                    --accent: #F59E0B;
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

                /* WELCOME BANNER */
                .welcome-banner {
                    background: linear-gradient(135deg, var(--blue-deep) 0%, #1E3A8A 45%, #1D4ED8 100%);
                    padding: 3rem 2.5rem;
                    position: relative;
                    overflow: hidden;
                }

                .welcome-banner::before {
                    content: '';
                    position: absolute;
                    top: -80px;
                    right: -80px;
                    width: 400px;
                    height: 400px;
                    background: radial-gradient(circle, rgba(59, 130, 246, 0.25) 0%, transparent 70%);
                    border-radius: 50%;
                    pointer-events: none;
                }

                .welcome-inner {
                    max-width: 1100px;
                    margin: 0 auto;
                    position: relative;
                    z-index: 2;
                }

                .welcome-badge {
                    display: inline-flex;
                    align-items: center;
                    gap: 8px;
                    background: rgba(59, 130, 246, 0.22);
                    border: 1px solid rgba(59, 130, 246, 0.45);
                    color: #93C5FD;
                    padding: 5px 14px;
                    border-radius: 50px;
                    font-size: 0.75rem;
                    font-weight: 600;
                    letter-spacing: 0.06em;
                    text-transform: uppercase;
                    margin-bottom: 1rem;
                    animation: fadeUp 0.5s ease both;
                }

                .welcome-banner h1 {
                    font-family: 'Sora', sans-serif;
                    font-size: clamp(1.8rem, 4vw, 2.8rem);
                    font-weight: 800;
                    color: white;
                    line-height: 1.15;
                    animation: fadeUp 0.6s ease 0.1s both;
                }

                .welcome-banner h1 span {
                    color: var(--accent);
                }

                .welcome-banner p {
                    color: rgba(255, 255, 255, 0.65);
                    font-size: 1rem;
                    margin-top: 0.5rem;
                    animation: fadeUp 0.6s ease 0.2s both;
                }

                /* MAIN CONTENT */
                .main-content {
                    flex: 1;
                    max-width: 1100px;
                    margin: 0 auto;
                    width: 100%;
                    padding: 3rem 2.5rem;
                }

                .section-eyebrow {
                    display: inline-block;
                    background: var(--blue-light);
                    color: var(--blue-mid);
                    font-size: 0.72rem;
                    font-weight: 700;
                    letter-spacing: 0.1em;
                    text-transform: uppercase;
                    padding: 4px 14px;
                    border-radius: 50px;
                    margin-bottom: 1.5rem;
                }

                /* CARDS GRID */
                .cards-grid {
                    display: grid;
                    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                    gap: 1.5rem;
                }

                .dash-card {
                    background: var(--white);
                    border-radius: 24px;
                    padding: 2rem 1.75rem;
                    border: 2px solid transparent;
                    transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
                    position: relative;
                    overflow: hidden;
                    animation: fadeUp 0.5s ease both;
                }

                .dash-card::after {
                    content: '';
                    position: absolute;
                    top: 0;
                    left: 0;
                    right: 0;
                    height: 5px;
                }

                .card-view-doctors::after {
                    background: linear-gradient(90deg, #3B82F6, #818CF8);
                }

                .card-add-doctors::after {
                    background: linear-gradient(90deg, #10B981, #34D399);
                }

                .card-view-patients::after {
                    background: linear-gradient(90deg, #F59E0B, #FB923C);
                }

                .card-appointments::after {
                    background: linear-gradient(90deg, #06B6D4, #3B82F6);
                }

                .card-remove::after {
                    background: linear-gradient(90deg, #EF4444, #F97316);
                }

                .card-bill::after {
                    background: linear-gradient(90deg, #0EB09D, #10B981);
                }

                .dash-card:hover {
                    transform: translateY(-7px) scale(1.01);
                    box-shadow: 0 20px 50px rgba(10, 36, 114, 0.12);
                }

                .card-view-doctors:hover {
                    border-color: var(--blue-light);
                }

                .card-add-doctors:hover {
                    border-color: #A7F3D0;
                }

                .card-view-patients:hover {
                    border-color: #FDE68A;
                }

                .card-appointments:hover {
                    border-color: #A5F3FC;
                }

                .card-remove:hover {
                    border-color: #FECACA;
                }

                .card-bill:hover {
                    border-color: #99F6E4;
                }

                .card-icon-wrap {
                    width: 64px;
                    height: 64px;
                    border-radius: 16px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 1.75rem;
                    margin-bottom: 1.25rem;
                }

                .card-view-doctors .card-icon-wrap {
                    background: #DBEAFE;
                }

                .card-add-doctors .card-icon-wrap {
                    background: #D1FAE5;
                }

                .card-view-patients .card-icon-wrap {
                    background: #FEF3C7;
                }

                .card-appointments .card-icon-wrap {
                    background: #CFFAFE;
                }

                .card-remove .card-icon-wrap {
                    background: #FEE2E2;
                }

                .card-bill .card-icon-wrap {
                    background: #CCFBF1;
                }

                .dash-card h4 {
                    font-family: 'Sora', sans-serif;
                    font-size: 1.1rem;
                    font-weight: 700;
                    color: var(--blue-deep);
                    margin-bottom: 0.5rem;
                }

                .dash-card p {
                    color: var(--text-muted);
                    font-size: 0.9rem;
                    line-height: 1.6;
                    margin-bottom: 1.5rem;
                }

                .btn-dash {
                    display: inline-block;
                    padding: 10px 26px;
                    border-radius: 50px;
                    font-weight: 700;
                    font-size: 0.88rem;
                    text-decoration: none;
                    transition: all 0.2s;
                    font-family: 'DM Sans', sans-serif;
                    border: none;
                    cursor: pointer;
                }

                .btn-blue {
                    background: #3B82F6;
                    color: white;
                    box-shadow: 0 4px 14px rgba(59, 130, 246, 0.3);
                }

                .btn-blue:hover {
                    background: #2563EB;
                    transform: scale(1.04);
                    color: white;
                }

                .btn-green {
                    background: #10B981;
                    color: white;
                    box-shadow: 0 4px 14px rgba(16, 185, 129, 0.3);
                }

                .btn-green:hover {
                    background: #059669;
                    transform: scale(1.04);
                    color: white;
                }

                .btn-amber {
                    background: #F59E0B;
                    color: white;
                    box-shadow: 0 4px 14px rgba(245, 158, 11, 0.3);
                }

                .btn-amber:hover {
                    background: #D97706;
                    transform: scale(1.04);
                    color: white;
                }

                .btn-cyan {
                    background: #06B6D4;
                    color: white;
                    box-shadow: 0 4px 14px rgba(6, 182, 212, 0.3);
                }

                .btn-cyan:hover {
                    background: #0891B2;
                    transform: scale(1.04);
                    color: white;
                }

                .btn-red {
                    background: #EF4444;
                    color: white;
                    box-shadow: 0 4px 14px rgba(239, 68, 68, 0.3);
                }

                .btn-red:hover {
                    background: #DC2626;
                    transform: scale(1.04);
                    color: white;
                }

                .btn-teal {
                    background: #0EB09D;
                    color: white;
                    box-shadow: 0 4px 14px rgba(14, 176, 157, 0.3);
                }

                .btn-teal:hover {
                    background: #0d9e8c;
                    transform: scale(1.04);
                    color: white;
                }

                /* LOGOUT */
                .logout-section {
                    text-align: center;
                    margin-top: 2.5rem;
                }

                .btn-logout {
                    display: inline-block;
                    padding: 12px 36px;
                    border-radius: 50px;
                    font-weight: 700;
                    font-size: 0.95rem;
                    text-decoration: none;
                    background: transparent;
                    color: #EF4444;
                    border: 2px solid #EF4444;
                    transition: all 0.2s;
                    font-family: 'DM Sans', sans-serif;
                }

                .btn-logout:hover {
                    background: #EF4444;
                    color: white;
                    transform: scale(1.04);
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
                    margin-top: auto;
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

                @media (max-width: 640px) {
                    nav {
                        padding: 0 1.25rem;
                    }

                    .nav-search input {
                        width: 120px;
                    }

                    .welcome-banner {
                        padding: 2.5rem 1.25rem;
                    }

                    .main-content {
                        padding: 2rem 1.25rem;
                    }

                    footer {
                        flex-direction: column;
                        text-align: center;
                    }

                    .footer-links {
                        justify-content: center;
                    }
                }
            </style>
        </head>

        <body>

            <!-- NAV -->
            <nav>
                <a class="nav-brand" href="#">
                    <span class="brand-icon">🏥</span>
                    Admin Dashboard
                </a>
                <div class="nav-search">
                    <input type="search" placeholder="Search...">
                    <button>Search</button>
                </div>
            </nav>

            <!-- WELCOME BANNER -->
            <div class="welcome-banner">
                <div class="welcome-inner">
                    <div class="welcome-badge">✦ Admin Portal</div>
                    <h1>Welcome, <span><?= $this->getAdminName() ?></span> 👋</h1>
                    <p>Manage Doctors, Patients, Appointments, and Billing — all in one place.</p>
                </div>
            </div>

            <!-- MAIN CONTENT -->
            <div class="main-content">
                <span class="section-eyebrow">Quick Actions</span>

                <div class="cards-grid">

                    <div class="dash-card card-view-doctors" style="animation-delay: 0.05s">
                        <div class="card-icon-wrap">👨‍⚕️</div>
                        <h4>View Doctors</h4>
                        <p>Check the full list of registered doctors in the system.</p>
                        <a href="admin_view_doctor.php" class="btn-dash btn-blue">View Doctors</a>
                    </div>

                    <div class="dash-card card-add-doctors" style="animation-delay: 0.1s">
                        <div class="card-icon-wrap">➕</div>
                        <h4>Add Doctors</h4>
                        <p>Register and add new doctors to the system.</p>
                        <a href="add_doctor.php" class="btn-dash btn-green">Add Doctors</a>
                    </div>

                    <div class="dash-card card-view-patients" style="animation-delay: 0.15s">
                        <div class="card-icon-wrap">🩺</div>
                        <h4>View Patients</h4>
                        <p>Manage all registered patients and their information.</p>
                        <a href="admin_view_patients.php" class="btn-dash btn-amber">View Patients</a>
                    </div>

                    <div class="dash-card card-appointments" style="animation-delay: 0.2s">
                        <div class="card-icon-wrap">📅</div>
                        <h4>View Appointments</h4>
                        <p>Check and manage the complete list of all appointments.</p>
                        <a href="admin_view_appointments.php" class="btn-dash btn-cyan">View Appointments</a>
                    </div>

                    <div class="dash-card card-remove" style="animation-delay: 0.25s">
                        <div class="card-icon-wrap">🗑️</div>
                        <h4>Remove Appointments</h4>
                        <p>Remove canceled or outdated appointments from the system.</p>
                        <a href="admin_remove_cancel_appointment.php" class="btn-dash btn-red">Remove Appointments</a>
                    </div>

                    <div class="dash-card card-bill" style="animation-delay: 0.3s">
                        <div class="card-icon-wrap">🧾</div>
                        <h4>Generate Bill</h4>
                        <p>Create and manage billing for patients and services.</p>
                        <a href="generate_bill.php" class="btn-dash btn-teal">Generate Bill</a>
                    </div>

                </div>

                <div class="logout-section">
                    <a href="admin_logout.php" class="btn-logout">Logout</a>
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

$dashboard = new AdminDashboard($conn, $admin_id);
$dashboard->fetchAdminName();
$dashboard->showDashboard();
?>