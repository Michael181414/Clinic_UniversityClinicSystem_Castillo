<?php
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
    $mail->Body = '...'; // your email HTML

    $mail->send();

    // Only return JSON, no header redirects
    echo json_encode([
      'success' => true,
      'message' => 'Patient created and email sent successfully'
    ]);
    exit();
  } catch (Exception $e) {
    echo json_encode([
      'success' => false,
      'message' => "Mailer Error: {$mail->ErrorInfo}"
    ]);
    exit();
  }
}
