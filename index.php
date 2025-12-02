<?php

require 'config/database.php';

function verify_password($password, $stored_hash)
{
    return password_verify($password, $stored_hash);
}

session_start();
$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $pdo = pdo_connect_mysql();

        $emailOrUsername = htmlspecialchars(trim($_POST['email'])); // Can be admin username or client email
        $password        = $_POST['password'];

        // ---------- 1️⃣ Check Admin Table First ----------
        $stmt = $pdo->prepare("SELECT * FROM admin WHERE username = ?");
        $stmt->execute([$emailOrUsername]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($admin && verify_password($password, $admin['password'])) {

            // Save session
            $_SESSION['user_type'] = $admin['user_type'];   // Doctor or Nurse
            $_SESSION['user_id']   = $admin['id'];
            $_SESSION['username']  = $admin['username'];

            // Log login
            $logStmt = $pdo->prepare("
        INSERT INTO activity_logs 
        (user_id, username, role, action_type, action_description, status) 
        VALUES (?, ?, ?, ?, ?, ?)
    ");

            $logStmt->execute([
                $admin['id'],
                $admin['username'],
                $admin['user_type'],    // Doctor or Nurse
                'Login',
                $admin['user_type'] . ' logged in',
                'SUCCESS'
            ]);

            header("Location: UC-Admin/Public/Dashboard.php");
            exit();
        }

        // ---------- 2️⃣ If not admin, check Clients Table ----------
        $stmt = $pdo->prepare("SELECT * FROM Clients WHERE Email = ?");
        $stmt->execute([$emailOrUsername]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && verify_password($password, $user['Password'])) {
            // Client login successful
            $_SESSION['user_type'] = 'client';
            $_SESSION['ClientID']  = $user['ClientID'];
            $_SESSION['ClientType'] = $user['ClientType'];
            $_SESSION['Firstname'] = $user['Firstname'];
            $_SESSION['Lastname']  = $user['Lastname'];
            $_SESSION['Email']     = $user['Email'];

            // Redirect based on ClientType
            switch ($user['ClientType']) {
                case 'Student':
                    header("Location: public/Student_Profile.php");
                    break;
                case 'Freshman':
                    header("Location:  public/Freshman_Profile.php");
                    break;
                case 'Faculty':
                case 'Personnel':
                    header("Location:  public/All_Personnel_Profile.php");
                    break;
                case 'NewPersonnel':
                    header("Location:  public/Newly_Hired_Profile.php");
                    break;
                default:
                    header("Location:  public/Profile.php");
                    break;
            }
            exit();
        }

        // ---------- 3️⃣ If no match ----------
        $error_message = 'Invalid username/email or password.';
    } catch (PDOException $e) {
        $error_message = 'Database connection failed: ' . $e->getMessage();
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <title>University Clinic Login</title>
    <link rel="stylesheet" href="public/styles.css">
    <script src="public/assets/js/script.js" defer></script>
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

<body>
    <div class="container">
        <div class="left-section">
            <div class="overlay">
                <img id="lspulogo" src="public/UC-Client/assets/images/Lspu logo.png" alt="LSPU Logo" class="logo">
                <h1 id="welcomesmg">Welcome to LSPU-LBC University Clinic </h1>
                <!--  <p class="login-subtitle">Securely access your medical records and manage your health profile online.</p>-->
            </div>
        </div>

        <div class="right-section" id="login-section">
            <div class="login-header">
                <h2 id="login">Login to your account</h2>
                <p class="login-subtitle">Securely access your medical records and manage your health profile online.</p>
                <div class="error-message <?php echo empty($error_message) ? 'error-hidden' : ''; ?>" id="error-message">
                    <i class="fas fa-exclamation-circle"></i>
                    <span id="error-text"><?php echo htmlspecialchars($error_message); ?></span>
                </div>
            </div>
            <form action="index.php" method="POST">
                <!--   <label for="email">Email</label>-->
                <div class="input-group">
                    <i class="fas fa-envelope left-icon"></i>
                    <input id="Email" type="email" class="inputs" name="email" placeholder="Email" required>
                </div>
                <!--  <label for="password">Password</label>-->
                <div class="input-group">
                    <i class="fas fa-lock left-icon"></i>
                    <input type="password" class="inputs" id="password" name="password" placeholder="Password" required>
                    <i class="fas fa-eye toggle-password" id="togglePassword"></i>
                </div>
                <p class="paragraph"><a href="public/request_reset.php" class="register-link">Forgot Password?</a></p>
                <button type="submit" class="buttons">Login</button>


                <p>Don't have an account? <a href="register.php" class="register-link">Sign up here</a></p>
                <p style="margin-right: 10px">Go to <a href="index.html" class="register-link">Home Page</a></p>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('Email').focus();

        const passwordInput = document.getElementById("password");
        const togglePassword = document.getElementById("togglePassword");

        togglePassword.addEventListener("click", function() {
            const type = passwordInput.type === "password" ? "text" : "password";
            passwordInput.type = type;
            this.classList.toggle("fa-eye");
            this.classList.toggle("fa-eye-slash");
        });
    </script>
</body>


</html>