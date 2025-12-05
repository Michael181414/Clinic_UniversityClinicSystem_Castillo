<?php
require_once 'config/database.php';
$pdo = pdo_connect_mysql();

try {
    $stmt = $pdo->prepare("SELECT * FROM backup_logs ORDER BY id ASC");
    $stmt->execute(); // ✅ You need to execute the query
    $historyData = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching history data: " . $e->getMessage());
}
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

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
    <title>Data Management</title>
    <link rel="stylesheet" href="assets/css/dashboardpagestyles.css">
    <link rel="stylesheet" href="assets/css/adminstyles.css">
    <link rel="stylesheet" href="assets/css/data_management.css">
    <link rel="stylesheet" href="webicons/fontawesome-free-6.7.2-web/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="assets/js/dashboard_func.js" defer></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap"
        rel="stylesheet" />
    <script src="assets/js/dashcalendar.js" defer></script>
    <script src="assets/js/dashgraph.js" defer></script>
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
    <title>Manage Profile</title>
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
            <h4>Data Management</h4>
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
                    <img src="assets/images/data_manage_icon_active.svg" class="button-icon-nav" loading="lazy">
                    <span class="nav-text">Data Management</span>
                </button>
            </a>
            <a href="admin_logout.php">
                <button class="buttons" id="logoutbtn">
                    <img src="assets/images/logout-icon.svg" class="button-icon-nav" loading="lazy">
                    <span class="nav-text">Logout</span>
                </button>
            </a>
        </nav>

        <main class="content">
            <div class="content-body">
                <div class="top-div-body">
                    <div class="data-management-options">

                        <!-- Backup Card -->
                        <a href="javascript:void(0);" onclick="showBackupModal()" class="data-management-link">
                            <div class="data-management-card">
                                <span class="icon-span"><i class="fas fa-file-export"></i>
                                    <p>Backup Data</p>
                                </span>
                                <p class="p-tag">Create a backup of the database to ensure data safety and recovery options.</p>
                            </div>
                        </a>

                        <!-- Restore Card -->
                        <a href="javascript:void(0);" onclick="showRestoreModal()" class="data-management-link">
                            <div class="data-management-card">
                                <span class="icon-span"><i class="fas fa-database"></i>
                                    <p>Restore Data</p>
                                </span>
                                <p class="p-tag">Restore the database from a previously created backup file.</p>
                            </div>
                        </a>
                        <div id="loading-modal" class="modal">
                            <div class="modal-content">
                                <div id="loadingDiv">
                                    <div class="spinner"></div>
                                    <p>Restoring data, please wait...</p>
                                    <div class="progress-bar">
                                        <div class="progress"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="loading-modal">
                            <div class="modal-content">
                                <div class="spinner"></div>
                                <p>Restoring data, please wait...</p>
                                <div class="progress-bar">
                                    <div class="progress"></div>
                                </div>
                            </div>
                        </div>
                        <div id="success-modal" class="modal">
                            <div class="modal-content">
                                <h3>Success</h3>
                                <p id="success-message">Database restored successfully!</p>
                                <button id="close-success" class="btn">OK</button>
                            </div>
                        </div>
                        <!-- Hidden Restore Form -->
                        <form id="restoreForm" action="restore.php" method="post" enctype="multipart/form-data" style="display:none;">
                            <input type="file" id="restoreInput" name="backup_file" accept=".sql">
                            <input type="hidden" name="restore" value="1">
                            <button type="submit">Restore</button>
                        </form>



                    </div>
                </div>

            </div>
            <?php if (!empty($historyData)): ?>
                <div class="backup-history">
                    <h2 style="margin-bottom: 10px;"><i class="fas fa-file-export"></i> Backup History</h2>

                    <div class="table-wrapper">
                        <table class="history-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>File Name</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($historyData as $index => $row): ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td><?= htmlspecialchars($row['file_name']) ?></td>
                                        <td><?= htmlspecialchars($row['backup_date']) ?></td>
                                        <td><?= htmlspecialchars($row['backup_time']) ?></td>
                                        <td>
                                            <?php if ($row['status'] === 'success'): ?>
                                                <span class="status success">Success</span>
                                            <?php else: ?>
                                                <span class="status failed">Failed</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($row['status'] === 'success'): ?>
                                                <a href="backups/<?= htmlspecialchars($row['file_name']) ?>" class="btn-download">Download</a>
                                            <?php else: ?>
                                                <span class="muted">—</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php else: ?>
                <p class="no-records">No backup records found.</p>
            <?php endif; ?>



        </main>

        <!-- Status Modal -->
        <script>
            document.getElementById("restoreInput").addEventListener("change", function() {
                const form = document.getElementById("restoreForm");
                const formData = new FormData(form);
                const loadingModal = document.getElementById("loading-modal");
                const progressBar = loadingModal.querySelector(".progress");
                const successModal = document.getElementById("success-modal");
                const successMsg = document.getElementById("success-message");

                // Show loading modal
                loadingModal.classList.add("active");
                progressBar.style.width = "0%";

                fetch("restore.php", {
                        method: "POST",
                        body: formData
                    })
                    .then(res => {
                        let progress = 0;
                        const interval = setInterval(() => {
                            progress = Math.min(progress + 15, 90);
                            progressBar.style.width = progress + "%";
                        }, 300);

                        return res.json().finally(() => {
                            clearInterval(interval);
                            progressBar.style.width = "100%";
                        });
                    })
                    .then(data => {
                        setTimeout(() => {
                            loadingModal.classList.remove("active");

                            if (data.status === "success") {
                                successMsg.textContent = data.msg;
                                successModal.classList.add("active");
                            } else {
                                alert(data.msg);
                            }
                        }, 400);
                    })
                    .catch(err => {
                        loadingModal.classList.remove("active");
                        alert("❌ Restore error: " + err);
                    });

                // Close success modal
                document.getElementById("close-success").addEventListener("click", () => {
                    successModal.classList.remove("active");
                    location.reload(); // optional: refresh to show updated data
                });
            });
        </script>

        <div id="backup-loading-modal" class="modal">
            <div class="modal-content">
                <div class="spinner"></div>
                <p id="loading-text">Creating backup, please wait...</p>
                <div class="progress-bar">
                    <div class="progress"></div>
                </div>
            </div>
        </div>

        <!-- ✅ Success Modal -->
        <div id="success-modal" class="modal">
            <div class="modal-content">
                <h3>✅ Success</h3>
                <p id="success-message">Backup completed successfully!</p>
                <button id="close-success" class="btn">OK</button>
            </div>
        </div>
        <!-- ⚠️ Custom Modal for Restore Confirmation -->
        <div id="restoreWarningModal" class="modal-overlay">
            <div class="modal-box">
                <h2>⚠️ Restore Database</h2>
                <p>
                    Restoring a backup will <strong>overwrite all current data</strong>.<br>
                    Any new records created <em>after</em> this backup will be <strong>lost permanently</strong>.
                </p>
                <div class="modal-actions">
                    <button class="btn-cancel" onclick="closeModal()">Cancel</button>
                    <button class="btn-confirm" onclick="confirmRestore()">Yes, Restore</button>
                </div>
            </div>
        </div>

        <div id="backupWarningModal" class="modal-overlay">
            <div class="modal-box">
                <h2>💾 Backup Database</h2>
                <p>
                    This will create a backup of the <strong>current database</strong>.<br>
                    Make sure you save it in a secure location.
                </p>
                <div class="modal-actions">
                    <button class="btn-cancel" onclick="closeBackupModal()">Cancel</button>
                    <button class="btn-confirm" onclick="confirmBackup()">Yes, Backup</button>
                </div>
            </div>
        </div>


        <script>
            // ✅ Backup Function
            function runBackup() {
                const loadingModal = document.getElementById("backup-loading-modal");
                const progressBar = document.querySelector(".progress");
                const loadingText = document.getElementById("loading-text");
                const successModal = document.getElementById("success-modal");
                const successMessage = document.getElementById("success-message");
                const closeSuccess = document.getElementById("close-success");

                // 🌀 Show loading modal
                loadingText.textContent = "Creating backup, please wait...";
                loadingModal.classList.add("active");
                progressBar.style.width = "0%";

                let xhr = new XMLHttpRequest();
                xhr.open("GET", "back_up.php", true);

                xhr.onload = function() {
                    let progress = 0;
                    const interval = setInterval(() => {
                        progress = Math.min(progress + 10, 90);
                        progressBar.style.width = progress + "%";
                    }, 300);

                    if (xhr.status === 200) {
                        let response = xhr.responseText.trim();
                        console.log("back_up.php response:", response);

                        setTimeout(() => {
                            clearInterval(interval);
                            progressBar.style.width = "100%";
                            loadingModal.classList.remove("active");

                            if (response.startsWith("success")) {
                                let parts = response.split("|");
                                let file = parts[1];
                                successMessage.textContent = "✅ Backup created successfully!";
                                successModal.classList.add("active");

                                // ✅ Close modal & download file
                                closeSuccess.onclick = () => {
                                    successModal.classList.remove("active");
                                    window.location.href = file; // download backup
                                };
                            } else {
                                alert("❌ Backup failed!\n\nResponse: " + response);
                            }
                        }, 800);
                    } else {
                        clearInterval(interval);
                        loadingModal.classList.remove("active");
                        alert("❌ Request failed. Status: " + xhr.status);
                    }
                };

                xhr.onerror = function() {
                    loadingModal.classList.remove("active");
                    alert("❌ Network error calling back_up.php");
                };

                xhr.send();
            }

            // ✅ Restore Confirmation Modal
            const restoreInput = document.getElementById("restoreInput");
            const restoreForm = document.getElementById("restoreForm");
            const modal = document.getElementById("restoreWarningModal");

            // Show modal first when clicking Restore
            function showRestoreModal() {
                modal.style.display = "flex";
            }

            function closeModal() {
                modal.style.display = "none";
            }

            function confirmRestore() {
                modal.style.display = "none";
                restoreInput.click(); // open file picker
            }

            // After file chosen → submit form


            const backupModal = document.getElementById("backupWarningModal");

            // Show modal first when clicking Backup
            function showBackupModal() {
                backupModal.style.display = "flex";
            }

            function closeBackupModal() {
                backupModal.style.display = "none";
            }

            function confirmBackup() {
                backupModal.style.display = "none";
                runBackup(); // now actually run the backup
            }
        </script>

        <style>

        </style>

</body>

</html>