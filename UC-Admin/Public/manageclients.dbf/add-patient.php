<?php
require_once('../config/database.php');
require_once('../../../vendor/autoload.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (!class_exists('PHPMailer\PHPMailer\PHPMailer')) {
    die('PHPMailer not loaded — check autoload path.');
}

$pdo = pdo_connect_mysql();
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $unhashedPassword = $_POST['password'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $client_type = $_POST['client_type'];
    $department = $_POST['department'] ?? null;

    $parts = explode(" ", $fullname, 2);
    $firstname = $parts[0];
    $lastname = $parts[1] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM clients WHERE Email = ?");
    $stmt->execute([$email]);

    if ($stmt->rowCount() > 0) {
        header("Location: ../Manage_Clients.php?error=Email already exists");
        exit();
    }

    $insert_stmt = $pdo->prepare("INSERT INTO clients (Firstname, Lastname, Email, Password, ClientType, Department) VALUES (?, ?, ?, ?, ?, ?)");
    $insert_stmt->execute([$firstname, $lastname, $email, $password, $client_type, $department]);

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'jaymichaelcastillo18@gmail.com';
        $mail->Password = 'dmjh epxq wsiw cwnm';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('jaymichaelcastillo18@gmail.com', 'LSPU-LBS University Clinic');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Welcome to ClinicConnect! Your Account Is Now Active';
        $mail->Body = '
<div style="font-family: Inter, -apple-system, BlinkMacSystemFont, Segoe UI, Roboto, Helvetica, Arial, sans-serif; background-color: #f6f9fc; padding: 30px;">
  <div style="max-width: 600px; margin: auto; background: #ffffff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); overflow: hidden;">
    <div style="background-color: #2e68cc;; color: #fff; text-align: center; padding: 18px 0;">
      <h2 style="margin: 0;">Clinic Connect</h2>
      <p style="margin: 0; font-size: 14px;">LSPU-LBC University Clinic System</p>
    </div>

    <div style="padding: 25px 30px; color: #333;">
      <h3 style="color: #2e68cc;; margin-top: 0;">Welcome, ' . htmlspecialchars($fullname) . '!</h3>
      <p>Your account has been successfully created. You can now access our system using the credentials below:</p>

      <div style="background: #f2f4f7; padding: 12px 15px; border-left: 4px solid #007bff; margin: 18px 0; border-radius: 6px;">
        <p style="margin: 6px 0;"><strong>Email:</strong> ' . htmlspecialchars($email) . '</p>
        <p style="margin: 6px 0;"><strong>Password:</strong> ' . htmlspecialchars($unhashedPassword) . '</p>
      </div>

      <p style="color: #c0392b; font-weight: bold;">⚠️ Important: Please do not share your password with anyone.</p>
      <p>You can change your password anytime after logging in for your security.</p>

      <p style="text-align: center; margin: 25px 0;">
        <a href="http://localhost/LSPU%20LBC%20University_Clinic_System/index.php" 
           style="background-color:#2e68cc;;color:white;padding:12px 25px;text-decoration:none;border-radius:5px;font-weight:bold;">
          Go to Login Page
        </a>
      </p>

      <p style="font-size: 14px; color: #666;">
        If you didn’t request this account, please contact our support team immediately.
      </p>
    </div>

    <div style="background-color:#f1f3f5; text-align:center; padding:12px; font-size:13px; color:#777;">
      <p>© ' . date('Y') . ' University Clinic - ClinicConnect System</p>
    </div>
  </div>
</div>
';


        $mail->send();
    } catch (Exception $e) {
        $message = "Mailer Error: {$mail->ErrorInfo}";
    }

    header("Location: ../Manage_Clients.php?success=User created successfully");
    exit();
}
