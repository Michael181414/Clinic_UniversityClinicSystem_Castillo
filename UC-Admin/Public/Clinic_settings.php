<?php
include 'dashboard.dbf/fetch_dashboard_data.php';
require 'dashboard.dbf/recent_consultations.php';
require_once 'config/database.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: ../../index.php');
    exit;
}


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$pdo = pdo_connect_mysql();

// Get admin ID from session
$admin_id = $_SESSION['user_id'] ?? null;

// Query admin table safely
$stmt = $pdo->prepare("SELECT * FROM admin WHERE id = :adminID");
$stmt->execute(['adminID' => $admin_id]);

$current_user = $stmt->fetch(PDO::FETCH_ASSOC); // false if no row found

$role = $current_user['user_type'] ?? 'Admin';
$name = $current_user['username'] ?? '';
$email = $current_user['email'] ?? '';
?>
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Settings</title>
    <link rel="stylesheet" href="assets/css/dashboardpagestyles.css">
    <link rel="stylesheet" href="assets/css/adminstyles.css">
    <link rel="stylesheet" href="assets/css/profile_settings.css">
    <link rel="stylesheet" href="webicons/fontawesome-free-6.7.2-web/css/all.min.css">
    <script src="assets/js/dashboard_func.js" defer></script>
    <script src="assets/js/clientprofile.js" defer></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap"
        rel="stylesheet" />
    <style>
        @font-face {
            font-family: "Montserrat";
            src: url("assets/fonts/Montserrat/Montserrat-VariableFont_wght.ttf") format("woff2");
            font-weight: 400;
            font-style: normal;
        }

        @font-face {
            font-family: "Poppins";
            src: url("assets/fonts/Poppins/Poppins-Medium.ttf") format("woff2");
            font-weight: 400;
            font-style: normal;
        }
    </style>
    <title>Profile Settings</title>
</head>

<body>
    <div class="header">
        <img src="assets/images/Lspu logo.png" alt="Logo" type="image/webp" loading="lazy">
        <div class="title">
            <span class="university_title">LSPU-LBC</span>
            <span class="university_title"> University Clinic </span>
        </div>
        <button id="toggle-btn">
            <img id="btnicon" src="assets/images/menu.png">
        </button>
        <div class="page-title">
            <h4>Profile Settings</h4>
        </div>

        <div class="profile-container">
            <img id="profileBtn" src="<?= htmlspecialchars(!empty($rec['profilePicturePath']) ? '../../uploads/' . $rec['profilePicturePath'] : '../../uploads/profilepic2.png') ?>" class="profile-pic" alt="Profile">
            <div class="profile-dropdown" id="profileDropdown">
                <div class="fixed-profile-item">
                    <i class="fas fa-envelope"></i> <?= htmlspecialchars($current_user['username'] ?? 'Admin') ?>
                </div>
                <a href="Clinic_settings.php">
                    <div class="fixed-profile-item"><i class="fas fa-cog"></i> Settings</div>
                </a>
                <div class="profile-item" onclick="document.getElementById('logoutForm').submit()">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </div>
                <form id="logoutForm" action="admin_logout.php" method="post"></form>
            </div>
        </div>
    </div>
    <script>
        const profileBtn = document.getElementById("profileBtn");
        const profileDropdown = document.getElementById("profileDropdown");

        profileBtn.addEventListener("click", (e) => {
            e.stopPropagation();
            profileDropdown.style.display =
                profileDropdown.style.display === "block" ? "none" : "block";
        });

        document.addEventListener("click", (e) => {
            if (!profileBtn.contains(e.target) && !profileDropdown.contains(e.target)) {
                profileDropdown.style.display = "none";
            }
        });
    </script>
    <div class="main-container">
        <nav class="navbar">
            <a href="Dashboard.php">
                <button class="buttons" id="dashboardBtn">
                    <img src="assets/images/dashboard_icon.svg" class="button-icon-nav" loading="lazy">
                    <span class="nav-text">Dashboard</span>
                </button>
            </a>
            <a href="Manage_Clients.php">
                <button class="buttons" id="manageclientsBtn">
                    <img src="assets/images/manageclients_icon.svg" class="button-icon-nav" loading="lazy">
                    <span class="nav-text">Manage Patients</span>
                </button>
            </a>
            <a href="Activity_logs.php">
                <button class="buttons" id="activitylogsBtn">
                    <img src="assets/images/activity_logs_icon.png" class="button-icon-nav" loading="lazy">
                    <span class="nav-text">Activity Logs</span>
                </button>
            </a>
            <a href="Data_Management.php">
                <button class="buttons" id="datamanagementBtn">
                    <img src="assets/images/data_manage_icon.svg" class="button-icon-nav" loading="lazy">
                    <span class="nav-text">Data Management</span>
                </button>
            </a>
            <!--
            <a href="Calendar.html">
                <button class="buttons" id="calendarBtn">
                    <img src="assets/images/calendar_icon.svg" class="button-icon-nav" loading="lazy">
                    <span class="nav-text">Caledar</span>
                </button>
            </a>
    -->
            <a href="admin_logout.php">
                <button class="buttons" id="logoutbtn">
                    <img src="assets/images/logout-icon.svg" class="button-icon-nav" loading="lazy">
                    <span class="nav-text">Logout</span>
                </button>
            </a>
        </nav>

        <main class="content" loading="lazy">
            <div class="profile-main-container">
                <div class="profile-header">
                    <h2>User Profile</h2>
                    <p class="profile-subtitle">Manage your personal details</p>
                </div>
                <div class="profile-picture-section">
                    <div class="profile-pic-wrapper">
                        <img id="rofileBtn" class="profile-pic" src="<?= htmlspecialchars(!empty($rec['profilePicturePath']) ? '../../uploads/' . $rec['profilePicturePath'] : '../../uploads/profilepic2.png') ?>" class="profile-pic" alt="Profile">
                    </div>
                </div>
                <div class="profile-info-section">

                    <form id="profileForm" method="POST">
                        <div class="profile-grid">
                            <div class="profile-field">
                                <label for="username">Username:</label>
                                <input type="text" name="username" id="username"
                                    value="<?= htmlspecialchars($current_user['username'] ?? 'admin') ?>">
                            </div>

                            <div class="profile-field">
                                <label for="email">Email:</label>
                                <input type="email" name="email" id="email"
                                    value="<?= htmlspecialchars($current_user['email'] ?? 'user@email.com') ?>">
                            </div>

                            <div class="profile-field">
                                <label for="birthdate">Role:</label>
                                <input type="text" name="role" id="role" value="<?= $role ?>" readonly>
                            </div>
                        </div>
                        <div class="profile-grid">
                            <div class="profile-field">
                                <label for="password">New Password:</label>
                                <input type="password" name="password" id="password" placeholder="Enter new password">
                            </div>

                            <div class="profile-field">
                                <label for="confirmPassword">Confirm Password:</label>
                                <input type="password" id="confirmPassword" placeholder="Confirm new password">
                            </div>

                            <div class="btn-wrapper">
                                <button type="button" class="btn-save" onclick="confirmUpdate()">
                                    <i class="fas fa-save"></i> Save Changes
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Confirm Modal -->
            <div id="confirmModal" class="messagemodal">
                <div class="messagemodal-content">
                    <h2>Confirm Update</h2>
                    <p>Are you sure you want to save these changes?</p>
                    <div class="messagemodal-buttons">
                        <button id="confirmYes" class="btn-primary">Yes, Update</button>
                        <button onclick="closeModal('confirmModal')" class="btn-secondary">Cancel</button>
                    </div>
                </div>
            </div>

            <!-- Success Modal -->
            <div id="successModal" class="messagemodal">
                <div class="messagemodal-content">
                    <h2>✅ Profile Updated!</h2>
                    <p>Your profile information has been successfully updated.</p>
                    <div class="messagemodal-buttons">
                        <button onclick="closeModal('successModal')" class="btn-primary">OK</button>
                    </div>
                </div>
            </div>

            <!-- Error Modal -->
            <div id="errorModal" class="messagemodal">
                <div class="messagemodal-content">
                    <h2>⚠️ Error</h2>
                    <p id="errorMsg">Something went wrong.</p>
                    <div class="messagemodal-buttons">
                        <button onclick="closeModal('errorModal')" class="btn-secondary">Close</button>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <script>
        function confirmUpdate() {
            const username = document.getElementById('username').value.trim();
            const email = document.getElementById('email').value.trim();
            const pass = document.getElementById('password').value;
            const confirm = document.getElementById('confirmPassword').value;

            if (!username) return showError('Username name cannot be empty.');
            if (!email || !validateEmail(email)) return showError('Please enter a valid email address.');
            if (pass && pass !== confirm) return showError('Passwords do not match.');

            // Check email availability before showing confirm modal
            fetch('manageclients.dbf/confirm_email.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'email=' + encodeURIComponent(email)
                })
                .then(res => res.json())
                .then(data => {
                    if (data.exists) {
                        showError('This email is already taken. Please choose another.');
                    } else {
                        openModal('confirmModal');
                    }
                })
                .catch(err => showError('Error checking email: ' + err.message));
        }

        function validateEmail(email) {
            return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
        }

        function openModal(id) {
            document.getElementById(id).style.display = 'flex';
        }

        function closeModal(id) {
            document.getElementById(id).style.display = 'none';
        }

        function showError(msg) {
            document.getElementById('errorMsg').textContent = msg;
            openModal('errorModal');
        }

        document.getElementById('confirmYes').addEventListener('click', function() {
            const form = document.getElementById('profileForm');
            const formData = new FormData(form);
            closeModal('confirmModal');

            fetch('manageclients.dbf/update_profile.php', {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') openModal('successModal');
                    else showError(data.message || 'Failed to update profile.');
                })
                .catch(err => showError('Error: ' + err.message));
        });
    </script>

    </main>
    <script>
        document.getElementById('backToForm')?.addEventListener('click', () => {
            window.location.href = "<?= $targetPage ?>";
        });
    </script>



</body>

</html