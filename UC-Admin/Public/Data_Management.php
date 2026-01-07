<?php
require_once 'config/database.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$pdo = pdo_connect_mysql();
try {
    $stmt = $pdo->prepare("SELECT * FROM backup_logs ORDER BY id ASC");
    $stmt->execute(); // ✅ You need to execute the query
    $historyData = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching history data: " . $e->getMessage());
}

if (!isset($_SESSION['user_id'])) {
    header('Location: ../../index.php');
    exit;
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
    <script src="assets/js/data_manage.js" defer></script>
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
            <a href="Manage_Staffs.php">
                <button class="buttons" id="managestaffsBtn">
                    <img src="assets/images/manageclients_icon.svg" class="button-icon-nav" loading="lazy">
                    <span class="nav-text">Manage Staffs</span>
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

                        <!-- Backup and Restore Cards -->
                        <a href="javascript:void(0);" onclick="showBackupModal()" class="data-management-link">
                            <div class="data-management-card">
                                <span class="icon-span"><i class="fas fa-file-export"></i>
                                    <p>Backup Data</p>
                                </span>
                                <p class="p-tag">Create a backup of the database to ensure data safety and recovery options.</p>
                            </div>
                        </a>

                        <a href="javascript:void(0);" onclick="showRestoreModal()" class="data-management-link">
                            <div class="data-management-card">
                                <span class="icon-span"><i class="fas fa-database"></i>
                                    <p>Restore Data</p>
                                </span>
                                <p class="p-tag">Restore the database from a previously created backup file.</p>
                            </div>
                        </a>

                        <!-- Loading & Success Modals -->
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
            </div> <!-- content-body -->

            <!-- TABS -->
            <div class="tabs-container">
                <div class="tab-container">
                    <button id="tab-backup" class="tab-button active" onclick="openTab(event, 'backupTab')">
                        <i class="fas fa-file-export"></i> Backup History
                    </button>

                    <button id="tab-deleted" class="tab-button" onclick="openTab(event, 'deletedUsersTab')">
                        <i class="fas fa-users-slash"></i> Archive Users
                    </button>
                </div>

                <!-- BACKUP HISTORY TAB -->
                <div id="backupTab" class="tab-content active">
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
                </div>

                <!-- DELETED USERS TAB -->
                <div id="deletedUsersTab" class="tab-content">
                    <div class="backup-history">
                        <h2 style="margin-bottom: 10px;"><i class="fas fa-users-slash"></i>Archive Users</h2>

                        <div class="search-input-container rectangular-search">
                            <div class="input-wrapper">
                                <i class="fas fa-search search-icon-inset"></i>
                                <input type="text" class="search-input" id="searchInput" placeholder="Search by name or email...">
                            </div>

                            <div class="select-wrapper">
                                <i class="fas fa-filter"></i>
                                <select id="filterClientType" class="client-type-dropdown">
                                    <option value="">All User Types</option>
                                    <option value="Freshman">Freshman</option>
                                    <option value="Student">Student</option>
                                    <option value="Faculty">Faculty</option>
                                    <option value="Personnel">Personnel</option>
                                    <option value="NewPersonnel">New Personnel</option>
                                </select>
                            </div>
                        </div>
                        <div class="table-wrapper">
                            <table class="history-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Deleted At</th>
                                        <th>User Type</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="archivedUsersTable">
                                    <?php
                                    $archivedUsers = $pdo->query("SELECT * FROM archive_clients ORDER BY deleted_at DESC")->fetchAll();
                                    ?>
                                    <?php if (!empty($archivedUsers)): ?>
                                        <?php foreach ($archivedUsers as $index => $row): ?>
                                            <tr data-client-type="<?= htmlspecialchars($row['ClientType']) ?>">
                                                <td><?= $index + 1 ?></td>
                                                <td><?= htmlspecialchars($row['Firstname'] . ' ' . $row['Lastname']) ?></td>
                                                <td class="email-td"><?= htmlspecialchars($row['Email']) ?></td>
                                                <td><?= htmlspecialchars($row['deleted_at']) ?></td>
                                                <td><?= htmlspecialchars($row['ClientType']) ?></td>
                                                <td>
                                                    <button class="btn-restore"
                                                        onclick="openConfirmModal('restore', 'manageclients.dbf/restore_client.php?id=<?= $row['ClientID'] ?>')">
                                                        Restore
                                                    </button>
                                                    <button class="btn-delete"
                                                        onclick="openConfirmModal('delete', 'manageclients.dbf/delete_client.php?action=permanent&id=<?= $row['ClientID'] ?>')">
                                                        Delete
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="6" class="no-records">No archived users.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Confirmation Modal -->
                <div id="actionModal" class="modal-overlay">
                    <div class="modal-box">
                        <h3 id="modalTitle">Confirm Action</h3>
                        <p id="modalMessage">Are you sure you want to continue?</p>

                        <div class="modal-buttons">
                            <button id="modalCancel" class="modal-btn cancel">Cancel</button>
                            <a id="modalConfirm" class="modal-btn confirm" href="#">Confirm</a>
                        </div>
                    </div>
                </div>

            </div>
        </main>
        <script>
            const searchInput = document.getElementById('searchInput');
            const filterClientType = document.getElementById('filterClientType');
            const tableBody = document.getElementById('archivedUsersTable');

            function filterTable() {
                const searchText = searchInput.value.toLowerCase();
                const selectedType = filterClientType.value;

                Array.from(tableBody.querySelectorAll('tr')).forEach(row => {
                    const name = row.cells[1].textContent.toLowerCase();
                    const email = row.cells[2].textContent.toLowerCase();
                    const type = row.dataset.clientType;

                    const matchesSearch = name.includes(searchText) || email.includes(searchText);
                    const matchesType = selectedType === '' || type === selectedType;

                    row.style.display = matchesSearch && matchesType ? '' : 'none';
                });
            }

            // Event listeners
            searchInput.addEventListener('input', filterTable);
            filterClientType.addEventListener('change', filterTable);
        </script>
        <!-- Status Modal -->
        <script>
            function openTab(evt, tabName) {
                let tabContent = document.getElementsByClassName("tab-content");
                for (let i = 0; i < tabContent.length; i++) {
                    tabContent[i].style.display = "none";
                    tabContent[i].classList.remove("active");
                }

                let tabButtons = document.getElementsByClassName("tab-button");
                for (let i = 0; i < tabButtons.length; i++) {
                    tabButtons[i].classList.remove("active");
                }

                document.getElementById(tabName).style.display = "block";
                document.getElementById(tabName).classList.add("active");
                evt.currentTarget.classList.add("active");
            }

            // Show the default tab on page load
            document.addEventListener("DOMContentLoaded", function() {
                document.getElementById("backupTab").style.display = "block";
            });
        </script>
        <script>
            function openConfirmModal(actionType, url) {
                const modal = document.getElementById("actionModal");
                const title = document.getElementById("modalTitle");
                const message = document.getElementById("modalMessage");
                const confirmBtn = document.getElementById("modalConfirm");

                if (actionType === "restore") {
                    title.textContent = "Restore User?";
                    message.textContent = "Do you want to restore this user?";
                    confirmBtn.style.background = "#1d9bf0";
                } else if (actionType === "delete") {
                    title.textContent = "Delete Permanently?";
                    message.textContent = "This action cannot be undone. Delete this user permanently?";
                    confirmBtn.style.background = "#d62828";
                }

                confirmBtn.href = url;

                modal.style.display = "flex";
            }

            document.getElementById("modalCancel").onclick = function() {
                document.getElementById("actionModal").style.display = "none";
            };
        </script>

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

            const restoreInput = document.getElementById("restoreInput");
            const restoreForm = document.getElementById("restoreForm");
            const modal = document.getElementById("restoreWarningModal");

            function showRestoreModal() {
                modal.style.display = "flex";
            }

            function closeModal() {
                modal.style.display = "none";
            }

            function confirmRestore() {
                modal.style.display = "none";
                restoreInput.click();
            }


            const backupModal = document.getElementById("backupWarningModal");

            function showBackupModal() {
                backupModal.style.display = "flex";
            }

            function closeBackupModal() {
                backupModal.style.display = "none";
            }

            function confirmBackup() {
                backupModal.style.display = "none";
                runBackup();
            }
        </script>
        <script>
            function openTab(evt, tabName) {
                var tabs = document.getElementsByClassName("tab-content");
                for (let i = 0; i < tabs.length; i++) {
                    tabs[i].style.display = "none";
                }

                // Remove active class from all buttons
                var buttons = document.getElementsByClassName("tab-button");
                for (let i = 0; i < buttons.length; i++) {
                    buttons[i].classList.remove("active");
                }

                // Show selected tab
                document.getElementById(tabName).style.display = "block";

                // Add active class to clicked button
                evt.currentTarget.classList.add("active");

                // Save tab selection to localStorage
                localStorage.setItem("activeTab", tabName);
            }

            // Load last active tab on page load
            document.addEventListener("DOMContentLoaded", function() {
                let activeTab = localStorage.getItem("activeTab");

                if (activeTab) {
                    // Simulate click on the correct button
                    if (activeTab === "backupTab") {
                        document.getElementById("tab-backup").click();
                    } else if (activeTab === "deletedUsersTab") {
                        document.getElementById("tab-deleted").click();
                    }
                } else {
                    // Default tab
                    document.getElementById("tab-backup").click();
                }
            });
        </script>


</body>
<div id="RestoreSuccessModal" class="restore-success-modal">
    <div class="restore-success-modal-content">
        <span class="closeRestoreModal">&times;</span>
        <div class="modal-icon">&#10004;</div>
        <h2>Success!</h2>
        <p id="restoreModalMessage"></p>
        <button class="closeRestoreBtn">OK</button>
    </div>
</div>
<div id="deleteSuccessModal" class="delete-success-modal" style="display:none;">
    <div class="delete-success-modal-content">
        <span class="closeDeleteModal">&times;</span>
        <div class="modal-icon">&#10004;</div>
        <h2>Success!</h2>
        <p id="deleteModalMessage"></p>
        <button class="closeModalBtn">OK</button>
    </div>
</div>

</html>