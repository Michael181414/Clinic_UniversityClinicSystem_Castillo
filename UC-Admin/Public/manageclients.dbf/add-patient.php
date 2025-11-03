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
  $firstname = trim($_POST['firstname']);
  $lastname = trim($_POST['lastname']);
  $email = trim($_POST['email']);
  $sex = $_POST['sex'];
  $birthdate = $_POST['birthdate'];
  $unhashedPassword = $_POST['password'];
  $password = password_hash($unhashedPassword, PASSWORD_DEFAULT);
  $client_type = $_POST['client_type'];
  $department = $_POST['department'] ?? null;

  // Check for existing email
  $stmt = $pdo->prepare("SELECT * FROM clients WHERE Email = ?");
  $stmt->execute([$email]);

  if ($stmt->rowCount() > 0) {
    header("Location: ../Manage_Clients.php?error=Email already exists");
    exit();
  }

  // Insert data
  $insert_stmt = $pdo->prepare("
    INSERT INTO clients (Firstname, Lastname, Email, Sex, BirthDate, Password, ClientType, Department)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)
  ");
  $insert_stmt->execute([$firstname, $lastname, $email, $sex, $birthdate, $password, $client_type, $department]);

  // Send email notification
  $mail = new PHPMailer(true);
  try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'jaymichaelcastillo18@gmail.com';
    $mail->Password = 'dmjh epxq wsiw cwnm'; // app password
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('jaymichaelcastillo18@gmail.com', 'LSPU-LBC University Clinic');
    $mail->addAddress($email);

    $mail->isHTML(true);
    $mail->Subject = 'Welcome to LSPU-LBC University Clinic! Your Account Is Now Active';
    $mail->Body = '
      <div style="font-family: Inter, Arial, sans-serif; background-color: #f6f9fc; padding: 30px;">
        <div style="max-width: 600px; margin: auto; background: #ffffff; border-radius: 8px;">
          <div style="background-color: #2e68cc; color: #fff; text-align: center; padding: 18px 0;">
            <h2 style="margin: 0;">LSPU-LBC University Clinic System</h2>
          </div>

          <div style="padding: 25px 30px; color: #333;">
            <h3 style="color: #2e68cc; margin-top: 0;">Welcome, ' . htmlspecialchars($firstname) . '!</h3>
            <p>Your account has been successfully created. You can now access our system using the credentials below:</p>

            <div style="background: #f2f4f7; padding: 12px 15px; border-left: 4px solid #007bff; margin: 18px 0;">
              <p><strong>Email:</strong> ' . htmlspecialchars($email) . '</p>
              <p><strong>Password:</strong> ' . htmlspecialchars($unhashedPassword) . '</p>
            </div>

            <p><strong>Gender:</strong> ' . htmlspecialchars($sex) . '<br>
            <strong>Birthdate:</strong> ' . htmlspecialchars($birthdate) . '</p>

            <p style="color: #c0392b; font-weight: bold;">⚠️ Do not share your password with anyone.</p>

            <p style="text-align: center;">
              <a href="http://localhost/LSPU%20LBC%20University_Clinic_System/index.php"
                 style="background-color:#2e68cc;color:white;padding:12px 25px;text-decoration:none;border-radius:5px;font-weight:bold;">
                Go to Login Page
              </a>
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
