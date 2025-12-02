<?php
session_start();
require_once('../config/database.php');
require_once('../../../vendor/autoload.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

header('Content-Type: application/json'); // important for AJAX

$pdo = pdo_connect_mysql();

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
    echo json_encode([
      'success' => false,
      'message' => 'Email already exists'
    ]);
    exit();
  }

  try {
    // Insert client
    $insert_stmt = $pdo->prepare("
            INSERT INTO clients (Firstname, Lastname, Email, Sex, BirthDate, Password, ClientType, Department)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
    $insert_stmt->execute([$firstname, $lastname, $email, $sex, $birthdate, $password, $client_type, $department]);

    // Get inserted patient ID
    $patient_id = $pdo->lastInsertId();

    // --- Insert activity log ---
    // Make sure $admin exists (you need to set $admin from session or authentication)
    $admin_id = $_SESSION['user_id'] ?? null;        // correct session variable
    $admin_username = $_SESSION['username'] ?? 'System';
    $admin_role     = $_SESSION['user_type'] ?? 'Unknown';

    $action_description = "Added patient: ID {$patient_id}, Name {$firstname} {$lastname}";
    $logStmt = $pdo->prepare("
    INSERT INTO activity_logs (user_id, username, role, action_type, action_description, status) 
    VALUES (?, ?, ?, ?, ?, ?)
");

    $logStmt->execute([
      $admin_id,          // user_id
      $admin_username,    // username
      $admin_role,        // Doctor or Nurse
      'Add Patient',      // action_type
      $action_description,
      'SUCCESS'
    ]);
    // --- Send email ---
    $mail = new PHPMailer(true);
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
    $mail->Body = '...'; // your email HTML

    $mail->send();

    echo json_encode([
      'success' => true,
      'message' => 'Patient created and email sent successfully'
    ]);
    exit();
  } catch (Exception $e) {
    echo json_encode([
      'success' => false,
      'message' => 'Error: ' . $e->getMessage()
    ]);
    exit();
  }
}
