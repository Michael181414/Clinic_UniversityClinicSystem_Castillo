<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/database.php';
$pdo = pdo_connect_mysql();

// 🔒 Ensure user is logged in
if (!isset($_SESSION['ClientID'])) {
    header("Location: index.php");
    exit();
}

$clientId = $_SESSION['ClientID'];

// 🔍 Fetch user type from the database
$stmt = $pdo->prepare("SELECT ClientType FROM clients WHERE ClientID = ?");
$stmt->execute([$clientId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$userType = $user['ClientType'] ?? 'Default';

// ✅ Define mapping once (safer and centralized)
$redirectMap = [
    'Freshman' => 'Freshman_Profile.php',
    'Student' => 'Student_Profile.php',
    'Faculty' => 'All_Personnel_Profile.php',
    'Personnel' => 'All_Personnel_Profile.php',
    'NewPersonnel' => 'Newly_Hired_Profile.php',
];

// Fallback page if user type not found
$targetPage = $redirectMap[$userType] ?? 'Profile.php';

try {
    $clientId = $_SESSION['ClientID']; // make sure ClientID is set in session
    $stmt = $pdo->prepare("SELECT * FROM clients WHERE ClientID = ?");
    $stmt->execute([$clientId]);
    $UserInfoData = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$UserInfoData || !isset($UserInfoData['Sex'])) {
        $_SESSION['error_message'] = "No gender data found for this client.";
    }
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    $_SESSION['error_message'] = "Failed to load gender data.";
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Layout Example</title>
    <link rel="stylesheet" href="UC-Client/assets/css/new_profile_style.css">

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
    <script src="UC-Client/assets/js/new_profile_function.js" defer></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap"
        rel="stylesheet" />
    <title>Settings</title>
</head>

<body>

    <div class="header">
        <img src="UC-Client/assets/images/Lspu logo.png" alt="Logo" type="image/webp" loading="lazy">
        <div class="title">
            <span class="university_title">LSPU-LBC</span>
            <span class="university_title"> University Clinic </span>
        </div>
        <button id="toggle-btn">
            <img id="btnicon" src="UC-Client/assets/images/menu.png">
        </button>
        <div class="page-title">
            <h4>Settings</h4>
        </div>

        <!-- Profile dropdown -->
        <div class="profile-container">

            <img id="profileBtn" src="../uploads/profilepic2.png" alt="Profile Picture">

            <div class="profile-dropdown" id="profileDropdown">
                <div class="profile-item">
                    <i class="fas fa-envelope"></i> <?= htmlspecialchars($UserInfoData['Email']) ?>
                </div>
                <div class="profile-item">
                    <i class="fas fa-cog"></i> Settings
                </div>
                <div class="profile-item" onclick="document.getElementById('logoutForm').submit()">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </div>
                <form id="logoutForm" action="logout.php" method="post"></form>
            </div>
        </div>
    </div>

    <div class="main-container">
        <nav class="navbar">
            <button class="buttons" id="backToForm">

                <?php if ($userType === "Faculty" || $userType === "Personnel") : ?>
                    <i class="fas fa-home"></i>
                <?php endif; ?>
                <?php if ($userType === "Freshman" || $userType === "NewPersonnel") : ?>
                    <i class="fas fa-file-lines button-icon-nav"></i>
                <?php endif; ?>
                <?php if ($userType === "Student") : ?>
                    <i class="fas fa-home"></i>
                <?php endif; ?>
                <span class="nav-text">
                    <?php if ($userType === "Faculty" || $userType === "Personnel") : ?>
                        Home
                    <?php endif; ?>
                    <?php if ($userType === "Freshman" || $userType === "NewPersonnel") : ?>
                        Medical Forms
                    <?php endif; ?>
                    <?php if ($userType === "Student") : ?>
                        Home
                    <?php endif; ?>
                </span>
            </button>


            <!--<a href="Settings.php">
                <button class="active-buttons" id="settingBtn">
                    <i class="fas fa-cog"></i>

                    <?php if ($userType === "Faculty" || $userType === "Personnel"): ?>
                        <span class="nav-text">Settings</span>
                    <?php else: ?>
                        <span class="nav-text">Settings</span>
                    <?php endif; ?>

                </button>-->
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
                        <img src="../uploads/profilepic2.png" alt="Profile Picture" class="profile-pic">
                    </div>
                </div>

                <div class="profile-info-section">
                    <form id="profileForm" method="POST">
                        <div class="profile-grid">
                            <div class="profile-field">
                                <label for="fullName">Full Name:</label>
                                <input type="text" name="fullName" id="fullName"
                                    value="<?= htmlspecialchars(trim(($UserInfoData['Firstname'] ?? '') . ' ' . ($UserInfoData['Lastname'] ?? '')) ?: 'Undone') ?>">
                            </div>

                            <div class="profile-field">
                                <label for="email">Email:</label>
                                <input type="email" name="email" id="email"
                                    value="<?= htmlspecialchars($UserInfoData['Email'] ?? '') ?>">
                            </div>
                            <div class="profile-field">
                                <label for="username">Username:</label>
                                <input type="text" name="username" id="username"
                                    value="<?= htmlspecialchars($UserInfoData['Username'] ?? '') ?>">
                            </div>
                        </div>
                        <div class="profile-grid">
                            <div class="profile-field">
                                <label for="birthdate">Birthdate:</label>
                                <input type="date" name="birthdate" id="birthdate"
                                    value="<?= htmlspecialchars($UserInfoData['BirthDate'] ?? '') ?>">
                            </div>

                            <div class="profile-field">
                                <label for="gender">Gender:</label>
                                <input type="text" id="gender"
                                    value="<?= htmlspecialchars($UserInfoData['Sex'] ?? 'Not specified') ?>" readonly>
                            </div>
                            <div class="profile-field"> </div>
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

        <script>
            function confirmUpdate() {
                const fullName = document.getElementById('fullName').value.trim();
                const email = document.getElementById('email').value.trim();
                const pass = document.getElementById('password').value;
                const confirm = document.getElementById('confirmPassword').value;

                if (!fullName) return showError('Full name cannot be empty.');
                if (!email || !validateEmail(email)) return showError('Please enter a valid email address.');
                if (pass && pass !== confirm) return showError('Passwords do not match.');

                // Check email availability before showing confirm modal
                fetch('check_email.php', {
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

                fetch('update_profile.php', {
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



    </div>

</body>



</html