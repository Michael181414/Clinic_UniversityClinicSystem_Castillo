<?php
$firstname = '';
$lastname  = '';
$sex       = '';
$dob       = '';
$username  = '';
$error     = '';
session_start();
require 'config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['consent']) || $_POST['consent'] !== 'accepted') {
        $error = "You must agree to the data privacy consent to register";
    } else {
        $firstname = $_POST['firstname'] ??  ' ';
        $lastname  = $_POST['lastname'] ?? ' ';
        $sex       = $_POST['sex'] ?? ' ';
        $dob       = $_POST['dob'] ?? ' ';
        $username  = $_POST['username'] ?? ' ';
        $password  = trim($_POST['password']);
        $confirm_password = trim($_POST['confirm_password']);

        // 🧩 VALIDATION SECTION
        if (empty($username)) {
            $error = "Please enter a username.";
        } elseif (empty($sex) || empty($dob)) {
            $error = "Please select your sex and birth date.";
        } elseif (strlen($password) < 8) {
            $error = "Password must be at least 8 characters long.";
        } elseif ($password !== $confirm_password) {
            $error = "Passwords do not match.";
        } else {
            // 🧩 DATABASE CHECK
            $pdo  = pdo_connect_mysql();
            $stmt = $pdo->prepare("SELECT * FROM Clients WHERE Username = ?");
            $stmt->execute([$username]);
            $existingUser = $stmt->fetch();

            if ($existingUser) {
                $error = "This username is already taken. Please choose another.";
            } else {
                // 🧩 INSERT NEW CLIENT
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("
                    INSERT INTO Clients (Firstname, Lastname, Username, Sex, Birthdate, Password)
                    VALUES (?, ?, ?, ?, ?, ?)
                ");

                if ($stmt->execute([$firstname, $lastname, $username, $sex, $dob, $hashed_password])) {
                    $clientId = $pdo->lastInsertId();
                    $_SESSION['ClientID'] = $clientId;
                    header('Location: public/client_type_selection.php');
                    exit;
                } else {
                    $error = "Error creating account. Please try again.";
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>University Clinic Sign Up</title>
    <link rel="stylesheet" href="public/styles.css">
    <script src="public/assets/js/script.js" defer></script>
    <script src="public/UC-Client/assets/js/validation.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        @font-face {
            font-family: "Montserrat";
            src: url("UC-Admin/Public/assets/fonts/Montserrat/Montserrat-VariableFont_wght.ttf") format("woff2");
            font-weight: 400;
            font-style: normal;
        }

        @font-face {
            font-family: "Poppins";
            src: url("UC-Admin/Public/assets/fonts/Poppins/Poppins-Medium.ttf") format("woff2");
            font-weight: 400;
            font-style: normal;
        }
    </style>
</head>

<body onload="autoScrollToLogin();">
    <div class="register-container">
        <div class="left-section">
            <div class="overlay">
                <img id="lspulogo" src="public/UC-Client/assets/images/Lspu logo.png" alt="LSPU Logo" class="logo">
                <h1 id="welcomesmg">Welcome to LSPU-LBC University Clinic </h1>
            </div>
        </div>

        <div class="register-right-section">
            <div class="login-header">
                <h2 id="login">Create your account</h2>
            </div>

            <?php if (!empty($error)): ?>
                <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <!-- Consent Modal -->
            <div id="consentModal" class="modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2>DATA PRIVACY CONSENT</h2>
                    </div>
                    <div class="modal-body">
                        <p>In compliance with the Data Privacy Act of 2012, (DPA) and its Implementing Rules and Regulations (IRR) effective on August 24, 2016.</p>
                        <p>By registering, you agree and authorize University Clinic to:</p>
                        <ol>
                            <li>Collect your personal information for University Clinic services.</li>
                            <li>Retain your information for a period of five years from the date of registration or until you submit a written cancellation of this consent, whichever is earlier. Your information will be deleted/destroyed after this period.</li>
                            <li>Contact you about future University Clinic events and services using the provided contact information.</li>
                        </ol>
                        <p>You acknowledge that you have read and understood this consent form and that you voluntarily agree to its terms.</p>
                    </div> <button class="consent-btn" onclick="agreeConsent()">I AGREE TO THESE TERMS</button>
                </div>
            </div>


            <form autocomplete="off" id="registerForm" action="register.php" method="POST" style="display: block;">
                <input type="hidden" name="consent" id="consentField" value="">

                <div class="name-input-group">
                    <div class="input-container">
                        <label for="firstname">First Name</label>
                        <input class="inputs" type="text" id="fname" name="firstname"
                            value="<?php echo htmlspecialchars($firstname); ?>"
                            placeholder="Ex: Juan" required>
                    </div>
                    <div class="input-container">
                        <label for="lastname">Last Name</label>
                        <input class="inputs" type="text" id="lastname" name="lastname"
                            value="<?php echo htmlspecialchars($lastname); ?>"
                            placeholder="Ex: Dela Cruz" required>

                    </div>
                </div>

                <div class="age-sex-input-group">
                    <div class="input-container">
                        <label for="sex">Sex</label>
                        <select class="inputs" id="sex" name="sex" required>
                            <option value="" disabled>Select Sex</option>
                            <option value="Male" <?php echo ($sex === 'Male') ? 'selected' : ''; ?>>Male</option>
                            <option value="Female" <?php echo ($sex === 'Female') ? 'selected' : ''; ?>>Female</option>
                        </select>
                    </div>

                    <div class="input-container">
                        <label for="dob">Date of Birth</label>
                        <input type="date" class="inputs" id="dob" name="dob"
                            value="<?php echo htmlspecialchars($dob); ?>"
                            required>
                    </div>
                </div>

                <div class="input-container">
                    <label for="username">Username</label>
                    <div class="input-group">
                        <i class="fas fa-user left-icon"></i>
                        <input type="text" class="inputs" id="username" name="username"
                            value="<?php echo htmlspecialchars($username); ?>"
                            placeholder="Enter your username" required>
                    </div>
                    <div id="dobeError" class="error-message error-hidden"></div> <!-- preserved ID class for consistency -->
                </div>

                <div class="parent-pass-input-group">
                    <div class="input-container">
                        <label for="password">Password</label>
                        <div class="input-group">
                            <i class="fas fa-lock left-icon"></i>
                            <input type="password" class="inputs" id="password" name="password" placeholder="••••••" required>
                            <i class="fas fa-eye toggle-password" id="togglePassword"></i>
                        </div>
                        <div id="passwordError" class="error-message error-hidden"></div>
                    </div>

                    <div class="input-container">
                        <label for="confirm-password">Confirm Password</label>
                        <div class="input-group">
                            <i class="fas fa-lock left-icon"></i>
                            <input type="password" class="inputs" id="confirm-password" name="confirm_password" placeholder="••••••" required>
                            <i class="fas fa-eye toggle-password" id="confirm-togglePassword"></i>
                        </div>
                        <div id="confirmPasswordError" class="error-message error-hidden"></div>
                    </div>
                </div>

                <button type="submit">Create Account</button>
                <p>Already have an account? <a class="register-link" href="index.php">Sign in</a></p>
            </form>
        </div>
    </div>

    <script>
        // Password toggle
        const passwordInput = document.getElementById("password");
        const confirmInput = document.getElementById("confirm-password");

        const togglePassword = document.getElementById("togglePassword");
        const confirmToggle = document.getElementById("confirm-togglePassword");

        togglePassword.addEventListener("click", () => {
            const type = passwordInput.type === "password" ? "text" : "password";
            passwordInput.type = type;

            togglePassword.classList.toggle("fa-eye");
            togglePassword.classList.toggle("fa-eye-slash");
        });

        confirmToggle.addEventListener("click", () => {
            const type = confirmInput.type === "password" ? "text" : "password";
            confirmInput.type = type;

            confirmToggle.classList.toggle("fa-eye");
            confirmToggle.classList.toggle("fa-eye-slash");
        });
        // Live password match
        confirmInput.addEventListener('input', () => {
            const msg = document.getElementById('confirmPasswordError');
            if (passwordInput.value !== confirmInput.value) {
                msg.textContent = "Passwords do not match.";
            } else {
                msg.textContent = "";
            }
        });

        // Password strength
        passwordInput.addEventListener('input', () => {
            const msg = document.getElementById('passwordError');
            const value = passwordInput.value;
            const regex = /[!@#$%^&*]/;
            if (value.length < 8) {
                msg.textContent = "Password must be at least 8 characters.";
            } else if (!regex.test(value)) {
                msg.textContent = "Password must include at least one special character.";
            } else {
                msg.textContent = "";
            }
        });

        // Live username check using original ID
        const usernameInput = document.getElementById('username');
        usernameInput.addEventListener('blur', () => {
            const username = usernameInput.value.trim();
            if (username === '') return;

            fetch('check_username.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'username=' + encodeURIComponent(username)
                })
                .then(res => res.json())
                .then(data => {
                    const msg = document.getElementById('dobeError'); // reused original ID
                    msg.textContent = data.valid ? '' : data.message;
                });
        });

        // Consent modal functions
        function showConsentModal() {
            document.getElementById('consentModal').style.display = 'block';
            document.body.style.overflow = 'hidden';
        }

        function agreeConsent() {
            document.getElementById('consentField').value = 'agree';
            document.getElementById('registerForm').style.display = 'block';
            document.getElementById('consentModal').style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        window.onload = function() {
            if (localStorage.getItem("clinic_consent") === "accepted") {
                document.getElementById("consentModal").style.display = "none";
                document.getElementById("consentField").value = "accepted";
            } else {
                document.getElementById("consentModal").style.display = "block";
            }
        };

        // SAVE consent when user clicks the button
        function agreeConsent() {
            localStorage.setItem("clinic_consent", "accepted");
            document.getElementById("consentModal").style.display = "none";
            document.getElementById("consentField").value = "accepted";
        }
    </script>
</body>

</html>