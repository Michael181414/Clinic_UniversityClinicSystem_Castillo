<?php
session_start();

require '../config/database.php';

$pdo = pdo_connect_mysql();
$message = '';
$password_error = '';
$confirm_error = '';

if (!isset($_SESSION['reset_email'])) {
    header('Location: request_reset.php'); // If no email stored, go back
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_SESSION['reset_email'];
    $reset_code = $_POST['reset_code'];
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Password validation
    if (strlen($new_password) < 8) {
        $password_error = "Password must be at least 8 characters long.";
    } elseif ($new_password !== $confirm_password) {
        $confirm_error = "Passwords do not match!";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM clients WHERE Email = ? AND ResetCode = ?");
        $stmt->execute([$email, $reset_code]);
        $user = $stmt->fetch();

        if ($user) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE clients SET Password = ?, ResetCode = NULL WHERE Email = ?");
            $stmt->execute([$hashed_password, $email]);

            unset($_SESSION['reset_email']);
            $message = "Password reset successfully! <a href='../index.php'>Sign In</a>";
        } else {
            $message = "Invalid reset code!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <title>University Clinic Login Page</title>
    <link rel="stylesheet" href="styles.css">
    <script src="assets/js/script.js" defer></script>
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

        .right-section {
            font-family: "Poppins", sans-serif;
        }

        h2 {
            font-family: "Poppins", sans-serif;
        }

        .password-error {
            color: #d93025;
            font-size: 13px;
            margin-top: 5px;
            margin-bottom: 10px;
            text-align: left;
            padding-left: 5px;
        }

        .confirm-error {
            color: #d93025;
            font-size: 13px;
            margin-top: 5px;
            margin-bottom: 10px;
            text-align: left;
            padding-left: 5px;
        }

        .input-error {
            border-color: #d93025 !important;
        }

        .message {
            color: #d93025;
            font-size: 13px;
            margin-top: 5px;
            margin-bottom: 10px;
            text-align: left;
            padding-left: 5px;
        }

        .success-message {
            color: #1a73e8;
            font-size: 13px;
            margin-top: 5px;
            margin-bottom: 10px;
            text-align: left;
            padding-left: 5px;
        }
    </style>
</head>

<body onload="autoScrollToLogin()">
    <div class="container">
        <div class="left-section">
            <div class="overlay">
                <img id="lspulogo" src="UC-Client/assets/images/Lspu logo.png" alt="LSPU Logo" class="logo">
                <h1 id="welcomesmg">Welcome to LSPU-LBC University Clinic </h1>
                <p id="loginsmg"></p>

            </div>
        </div>

        <div class="right-section">
            <h2 id="login">Reset Your Password</h2>
            <?php if (!empty($message)): ?>
                <?php if (strpos($message, 'successfully') !== false): ?>
                    <div class="success-message"><?php echo $message; ?></div>
                <?php else: ?>
                    <div class="message"><?php echo $message; ?></div>
                <?php endif; ?>
            <?php endif; ?>
            <form id="resetForm" action="reset-pass.php" method="POST">
                <div class="input-group">
                    <i class="fas fa-key left-icon"></i>
                    <input class="inputs" type="text" name="reset_code" placeholder="Enter Reset Code" required>
                </div>

                <div class="input-group">
                    <i class="fas fa-lock left-icon"></i>
                    <input class="inputs <?php echo !empty($password_error) ? 'input-error' : ''; ?>" 
                           type="password" id="new_password" name="new_password" placeholder="Enter New Password" required>
                    <i class="fas fa-eye toggle-password" data-target="new_password"></i>
                </div>
                <?php if (!empty($password_error)): ?>
                    <div class="password-error"><?php echo htmlspecialchars($password_error); ?></div>
                <?php endif; ?>

                <div class="input-group">
                    <i class="fas fa-lock left-icon"></i>
                    <input class="inputs <?php echo !empty($confirm_error) ? 'input-error' : ''; ?>" 
                           type="password" id="confirm_password" name="confirm_password" placeholder="Confirm New Password" required>
                    <i class="fas fa-eye toggle-password" data-target="confirm_password"></i>
                </div>
                <?php if (!empty($confirm_error)): ?>
                    <div class="confirm-error"><?php echo htmlspecialchars($confirm_error); ?></div>
                <?php endif; ?>

                <button type="submit">Reset Password</button>
            </form>
        </div>
        <script>
            document.querySelectorAll(".toggle-password").forEach(toggle => {
                toggle.addEventListener("click", function() {
                    const input = document.getElementById(this.dataset.target);
                    const type = input.type === "password" ? "text" : "password";
                    input.type = type;

                    this.classList.toggle("fa-eye");
                    this.classList.toggle("fa-eye-slash");
                });
            });

            const newPasswordInput = document.getElementById('new_password');
            const confirmPasswordInput = document.getElementById('confirm_password');

            function validatePasswords() {
                const newPassword = newPasswordInput.value;
                const confirmPassword = confirmPasswordInput.value;
                let hasError = false;

                const newPasswordError = newPasswordInput.parentElement.nextElementSibling;
                const confirmPasswordError = confirmPasswordInput.parentElement.nextElementSibling;

                if (newPassword.length > 0 && newPassword.length < 8) {
                    if (!newPasswordError || !newPasswordError.classList.contains('password-error')) {
                        const newError = document.createElement('div');
                        newError.className = 'password-error';
                        newError.textContent = 'Password must be at least 8 characters long.';
                        newPasswordInput.parentElement.after(newError);
                    } else {
                        newPasswordError.textContent = 'Password must be at least 8 characters long.';
                    }
                    newPasswordInput.classList.add('input-error');
                    hasError = true;
                } else {
                    if (newPasswordError && newPasswordError.classList.contains('password-error')) {
                        newPasswordError.remove();
                    }
                    newPasswordInput.classList.remove('input-error');
                }

                if (newPassword.length >= 8 && confirmPassword.length > 0 && newPassword !== confirmPassword) {
                    if (!confirmPasswordError || !confirmPasswordError.classList.contains('confirm-error')) {
                        const newError = document.createElement('div');
                        newError.className = 'confirm-error';
                        newError.textContent = 'Passwords do not match!';
                        confirmPasswordInput.parentElement.after(newError);
                    } else {
                        confirmPasswordError.textContent = 'Passwords do not match!';
                    }
                    confirmPasswordInput.classList.add('input-error');
                    hasError = true;
                } else {
                    if (confirmPasswordError && confirmPasswordError.classList.contains('confirm-error')) {
                        confirmPasswordError.remove();
                    }
                    confirmPasswordInput.classList.remove('input-error');
                }

                return !hasError;
            }

            newPasswordInput.addEventListener('input', validatePasswords);
            confirmPasswordInput.addEventListener('input', validatePasswords);

            document.getElementById('resetForm').addEventListener('submit', function(e) {
                const newPassword = newPasswordInput.value;
                const confirmPassword = confirmPasswordInput.value;
                let hasError = false;

                validatePasswords();

                const newPasswordError = newPasswordInput.parentElement.nextElementSibling;
                const confirmPasswordError = confirmPasswordInput.parentElement.nextElementSibling;

                if (newPassword.length < 8) {
                    if (!newPasswordError || !newPasswordError.classList.contains('password-error')) {
                        const newError = document.createElement('div');
                        newError.className = 'password-error';
                        newError.textContent = 'Password must be at least 8 characters long.';
                        newPasswordInput.parentElement.after(newError);
                    }
                    newPasswordInput.classList.add('input-error');
                    hasError = true;
                }

                if (newPassword !== confirmPassword) {
                    if (!confirmPasswordError || !confirmPasswordError.classList.contains('confirm-error')) {
                        const newError = document.createElement('div');
                        newError.className = 'confirm-error';
                        newError.textContent = 'Passwords do not match!';
                        confirmPasswordInput.parentElement.after(newError);
                    }
                    confirmPasswordInput.classList.add('input-error');
                    hasError = true;
                }

                if (hasError) {
                    e.preventDefault();
                }
            });
        </script>
    </div>
</body>

</html>