<?php
session_start();
require_once('../config/database.php');

// MUST be first thing
header('Content-Type: application/json; charset=utf-8');

// Disable warnings for production or log them instead
error_reporting(0);

try {
  if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    throw new Exception('Invalid request method.');
  }

  $firstname = trim($_POST['firstname'] ?? '');
  $lastname = trim($_POST['lastname'] ?? '');
  $username = trim($_POST['username'] ?? '');
  $password_plain = $_POST['password'] ?? '';
  $password_hashed = password_hash($password_plain, PASSWORD_DEFAULT);
  $sex = $_POST['sex'] ?? '';
  $birthdate = $_POST['birthdate'] ?? '';
  $client_type = $_POST['client_type'] ?? '';
  $department = $_POST['department'] ?? null;

  if (!$firstname || !$lastname || !$username || !$password_plain) {
    throw new Exception('Required fields missing.');
  }

  $pdo = pdo_connect_mysql();

  // Check username uniqueness
  $stmt = $pdo->prepare("SELECT 1 FROM clients WHERE Username = ?");
  $stmt->execute([$username]);
  if ($stmt->fetchColumn()) {
    echo json_encode(['success' => false, 'message' => 'Username already exists']);
    exit();
  }

  // Insert patient
  $insert_stmt = $pdo->prepare("
        INSERT INTO clients (Firstname, Lastname, Username, Sex, BirthDate, Password, ClientType, Department)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");
  $insert_stmt->execute([$firstname, $lastname, $username, $sex, $birthdate, $password_hashed, $client_type, $department]);

  $patient_id = $pdo->lastInsertId();

  // Log activity
  $admin_id = $_SESSION['user_id'] ?? null;
  $admin_username = $_SESSION['username'] ?? 'System';
  $admin_role = $_SESSION['user_type'] ?? 'Unknown';
  $action_description = "Added patient: ID {$patient_id}, Name {$firstname} {$lastname}";
  $logStmt = $pdo->prepare("
        INSERT INTO activity_logs (user_id, username, role, action_type, action_description, status)
        VALUES (?, ?, ?, ?, ?, ?)
    ");
  $logStmt->execute([$admin_id, $admin_username, $admin_role, 'Add Patient', $action_description, 'SUCCESS']);

  echo json_encode(['success' => true, 'message' => 'Patient created successfully']);
} catch (Exception $e) {
  echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
exit();


/*    ---  ---
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
*/