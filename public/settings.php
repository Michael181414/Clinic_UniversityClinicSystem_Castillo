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
                    <i class="fas fa-envelope"></i> user@email.com
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


            <a href="Settings.php">
                <button class="active-buttons" id="settingBtn">
                    <i class="fas fa-cog"></i>

                    <?php if ($userType === "Faculty" || $userType === "Personnel"): ?>
                        <span class="nav-text">Settings</span>
                    <?php else: ?>
                        <span class="nav-text">Settings</span>
                    <?php endif; ?>

                </button>
            </a>
        </nav>


        <main class="content" loading="lazy">
            <div class="profile-main-container">
                <div class="profile-picture-section">
                    <div class="profile-pic-wrapper">
                        <img src="../uploads/profilepic2.png" alt="Profile Picture" class="profile-pic">
                    </div>
                </div>

                <div class="profile-info-section">
                    <h2>User Profile</h2>
                    <p class="profile-subtitle">Manage your personal details</p>

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
                                    value="<?= htmlspecialchars($UserInfoData['Email'] ?? 'user@email.com') ?>">
                            </div>
                        </div>

                        <div class="profile-field">
                            <label for="password">New Password:</label>
                            <input type="password" name="password" id="password" placeholder="Enter new password">
                        </div>

                        <div class="profile-field">
                            <label for="confirmPassword">Confirm Password:</label>
                            <input type="password" id="confirmPassword" placeholder="Confirm new password">
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
                        </div>

                        <div class="btn-wrapper">
                            <button type="button" class="btn-save" onclick="confirmUpdate()">
                                <i class="fas fa-save"></i> Save Changes
                            </button>
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


        <style>
            .messagemodal {
                display: none;
                position: fixed;
                z-index: 999;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.4);
                justify-content: center;
                align-items: center;
            }

            .messagemodal-content {
                background: #fff;
                padding: 25px 35px;
                border-radius: 10px;
                text-align: center;
                width: 90%;
                max-width: 400px;
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
                animation: fadeIn 0.3s ease;
            }

            .messagemodal-buttons {
                margin-top: 20px;
                display: flex;
                justify-content: center;
                gap: 10px;
            }

            .btn-primary {
                background: #2767c0;
                color: #fff;
                border: none;
                padding: 10px 20px;
                border-radius: 6px;
                cursor: pointer;
            }

            .btn-secondary {
                background: #ddd;
                color: #333;
                border: none;
                padding: 10px 20px;
                border-radius: 6px;
                cursor: pointer;
            }
        </style>


        <style>
            .profile-main-container {
                display: flex;
                gap: 3rem;
                padding: 2.5rem;
                background-color: #fff;
                border-radius: 10px;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
                align-items: flex-start;
            }

            .profile-picture-section {
                flex: 1;
                display: flex;
                justify-content: center;
                align-items: center;
            }

            .profile-pic-wrapper {
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 12px;
            }

            .profile-pic {
                width: 140px;
                height: 140px;
                border-radius: 50%;
                border: 4px solid #2767c0;
                object-fit: cover;
                transition: transform 0.3s ease;
            }

            .profile-pic:hover {
                transform: scale(1.05);
            }

            .btn-upload {
                background-color: #2767c0;
                color: white;
                border: none;
                padding: 8px 14px;
                border-radius: 8px;
                cursor: pointer;
                font-size: 0.9rem;
                transition: background 0.3s ease;
            }

            .btn-upload:hover {
                background-color: #1e4ea7;
            }

            .btn-upload i {
                margin-right: 6px;
            }

            .profile-info-section {
                flex: 2;
                display: flex;
                flex-direction: column;
                gap: 1.2rem;
            }

            .profile-info-section h2 {
                color: #1547b3;
                margin-bottom: 0.2rem;
                font-size: 1.6rem;
            }

            .profile-subtitle {
                color: #666;
                font-size: 0.9rem;
                margin-bottom: 1rem;
            }

            .profile-grid {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 1.2rem;
            }

            .profile-field {
                display: flex;
                flex-direction: column;
            }

            .profile-field label {
                font-weight: 600;
                margin-bottom: 0.4rem;
                color: #333;
            }

            .profile-field input {
                padding: 10px 12px;
                border-radius: 8px;
                border: 1px solid #ccc;
                font-size: 1rem;
                transition: all 0.2s ease;
            }

            .profile-field input:focus {
                border-color: #2767c0;
                outline: none;
                box-shadow: 0 0 0 2px rgba(39, 103, 192, 0.2);
            }

            .btn-wrapper {
                margin-top: 1.5rem;
                text-align: right;
            }

            .btn-save {
                background-color: #2767c0;
                color: white;
                border: none;
                padding: 10px 20px;
                border-radius: 8px;
                cursor: pointer;
                font-size: 1rem;
                transition: background 0.3s ease;
            }

            .btn-save:hover {
                background-color: #1e4ea7;
            }

            .btn-save i {
                margin-right: 5px;
            }

            @media (max-width: 768px) {
                .profile-main-container {
                    flex-direction: column;
                    align-items: center;
                    padding: 1.5rem;
                }

                .profile-grid {
                    grid-template-columns: 1fr;
                }

                .btn-wrapper {
                    text-align: center;
                }
            }

            /* ================== ENHANCE PROFILE IMAGE ONLY ================== */
/* Target the profile image directly */
#profileBtn {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    object-fit: cover;
    
    /* Add white shadow/glow effect */
    box-shadow: 
        0 0 0 3px white,           /* White border */
        0 0 0 4px rgba(57, 125, 218, 0.3), /* Blue subtle border */
        0 4px 15px rgba(57, 125, 218, 0.25); /* Outer shadow */
    
    /* Add transition for smooth animation */
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    
    /* Make cursor show it's clickable */
    cursor: pointer;
    
    /* Scale the image to 96% so white border is visible */
    transform: scale(0.96);
}

/* Hover effect */
#profileBtn:hover {
    transform: scale(1.03); /* Slight grow on hover */
    box-shadow: 
        0 0 0 4px white,           /* Thicker white border on hover */
        0 0 0 6px rgba(57, 125, 218, 0.4), /* Blue border on hover */
        0 6px 20px rgba(57, 125, 218, 0.35); /* Stronger shadow */
    
    /* Add a subtle pulsing animation */
    animation: pulse-glow 2s infinite;
}

#profileBtn:active {
    transform: scale(0.98);
    box-shadow: 
        0 0 0 3px white,
        0 0 0 5px rgba(57, 125, 218, 0.5),
        0 2px 8px rgba(57, 125, 218, 0.3);
    transition: all 0.1s ease;
}

@keyframes pulse-glow {
    0% {
        box-shadow: 
            0 0 0 4px white,
            0 0 0 6px rgba(57, 125, 218, 0.4),
            0 6px 20px rgba(57, 125, 218, 0.35);
    }
    50% {
        box-shadow: 
            0 0 0 4px white,
            0 0 0 6px rgba(57, 125, 218, 0.6),
            0 8px 25px rgba(57, 125, 218, 0.4);
    }
    100% {
        box-shadow: 
            0 0 0 4px white,
            0 0 0 6px rgba(57, 125, 218, 0.4),
            0 6px 20px rgba(57, 125, 218, 0.35);
    }
}

/* ================== DROPDOWN HOVER COLORS ================== */
/* Settings - Blue hover */
.profile-item:nth-child(2):hover {
    background-color: #e8f0fe; /* Light blue background */
    border-left: solid #0b62c9 3px; /* Blue border */
}

.profile-item:nth-child(2):hover i {
    color: #0b62c9; /* Blue icon */
}

/* Logout - Red hover */
.profile-item:last-child:hover {
    background-color: #fee; /* Light red background */
    border-left: solid #d32f2f 3px; /* Red border */
}

.profile-item:last-child:hover i {
    color: #d32f2f; /* Red icon */
}

/* Smooth hover transitions */
.profile-item:hover {
    transform: translateX(4px);
    transition: all 0.2s ease;
}

/* Mobile responsive */
@media (max-width: 768px) {
    #profileBtn {
        width: 40px;
        height: 40px;
    }
}

@media (max-width: 480px) {
    #profileBtn {
        width: 35px;
        height: 35px;
    }
}
        </style>
        </main>
        <script>
            document.getElementById('backToForm')?.addEventListener('click', () => {
                window.location.href = "<?= $targetPage ?>";
            });
        </script>



    </div>

</body>



</html