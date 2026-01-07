<?php
require_once 'config/database.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    header('Location: ../../index.php');
    exit;
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

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Layout Example</title>
    <link rel="stylesheet" href="assets/css/dashboardpagestyles.css">
    <link rel="stylesheet" href="assets/css/manageusers.css">
    <link rel="stylesheet" href="assets/css/profileclients.css">
    <link rel="stylesheet" href="assets/css/adminstyles.css">
    <link rel="stylesheet" href="assets/css/dashboardpagestyles.css">
    <link rel="stylesheet" href="assets/css/activity_logsstyles.css">
    <!-- Manage Clients CSS - Linked here for style of class name: "table-header-controls",
      ctrl + f classname to locate.-->
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
    <link rel="stylesheet" href="webicons/fontawesome-free-6.7.2-web/css/all.min.css">
    <link rel="stylesheet" href="webicons/fontawesome-free-6.7.2-web/css/all.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <script src="assets/js/dashboard_func.js" defer></script>
    <script src="assets/js/dashcalendar.js" defer></script>
    <script src="assets/js/dashgraph.js" defer></script>
    <script src="assets/js/clientTypeChart.js" defer></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <title>Activity Logs</title>
    <script src="node_modules/chart.js/dist/chart.js"></script>
    <script src="assets/js/chart.umd.js"></script>
    <script src="assets/js/chart.umd.js" defer></script>
    <script src="assets/js/activity_logs_functions.js" defer></script>
</head>

<body>
    <!-- Dont remove this divs below -->
    <div class="client-stats">
        <div id="student-count" class="stat-box"></div>
        <div id="freshman-count" class="stat-box"></div>
        <div id="faculty-count" class="stat-box"></div>
        <div id="personnel-count" class="stat-box"></div>
        <div id="newpersonnel-count" class="stat-box"></div>
        <div id="total-count" class="stat-box"></div>
    </div>

    <!-- Dont remove this divs above -->

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
            <h4>Activity Logs</h4>
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
                    <img src="assets/images/activity_logs_icon_active.png" class="button-icon-nav" loading="lazy">
                    <span class="nav-text">Activity Logs</span>
                </button>
            </a>
            <a href="Manage_Staffs.php">
                <button class="buttons" id="managestaffsBtn">
                    <img src="assets/images/manageclients_icon.svg" class="button-icon-nav" loading="lazy">
                    <span class="nav-text">Manage Staffs</span>
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

        <main class="content">
            <!--table header control class used the style of different page (ManageClients)-->
            <div class="clients-table-container">
                <div class="table-header-controls">
                    <div class="table-left-controls">
                        <div class="search-input-container rectangular-search">
                            <div class="input-wrapper">
                                <i class="fas fa-search search-icon-inset"></i>
                                <input type="text"
                                    id="searchInput"
                                    placeholder="Search User logs..."
                                    maxlength="400">
                            </div>
                        </div>
                        <div class="select-wrapper">
                            <i class="fas fa-filter"></i>
                            <select id="roleFilter" class="client-type-dropdown">
                                <option value="all">All</option>
                                <option value="Doctor">Doctor</option>
                                <option value="Nurse">Nurse</option>
                            </select>

                        </div>
                    </div>
                </div>
            </div>

            <!--client-history-container class used the style of different page (ClientProfile)-->
            <div id="client-history-container" class="client-history-container">
                <div class="table-wrapper">
                    <table class="client-history-table">
                        <thead class="table-header">
                            <tr>
                                <th class="id-col">Log ID</th>
                                <th class="id-col">User ID</th>
                                <th class="action-datetime">Username</th>
                                <th class="action-datetime">Role</th>
                                <th class="action-datetime">Action Description</th>
                                <th class="action-datetime">Date</th>
                                <th class="action-datetime">Time</th>
                                <th class="action-datetime">Status</th>
                            </tr>
                        </thead>
                        <tbody id="logsTableBody">

                        </tbody>
                    </table>
                </div>

            </div>
        </main>
    </div>
    <!-- Modal for displaying full log -->
    <!-- Modal for displaying full log -->
    <div id="logModal" class="modal" style="display:none;">
        <div class="modal-content">
            <div class="medform-modal-header">
                <h3 style="color:#0056b3;">Activity Log Details</h3>
                <span class="close">&times;</span>
            </div>

            <div class="modal-details">

                <div class="detail-row">
                    <span class="detail-label">Log ID</span>
                    <span class="detail-value" id="modalLogID"></span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">User ID</span>
                    <span class="detail-value" id="modalUserID"></span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Email/Username</span>
                    <span class="detail-value" id="modalUsername"></span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Role</span>
                    <span class="detail-value" id="modalRole"></span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Action Type</span>
                    <span class="detail-value" id="modalActionType"></span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Action Description</span>
                    <span class="detail-value" id="modalActionDescription"></span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Date</span>
                    <span class="detail-value" id="modalDate"></span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Time</span>
                    <span class="detail-value" id="modalTime"></span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Status</span>
                    <span class="detail-value" id="modalStatus"></span>
                </div>

            </div>

        </div>
    </div>

</body>

</html