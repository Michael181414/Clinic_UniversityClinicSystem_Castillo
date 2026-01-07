<?php
session_start();
require_once('../config/database.php');

header('Content-Type: application/json; charset=utf-8');
error_reporting(0);

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method.');
    }

    // Get POST data
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password_plain = $_POST['password'] ?? '';
    $user_type = $_POST['user_type'] ?? '';

    // Validate required fields
    if (!$username || !$email || !$password_plain || !$user_type) {
        throw new Exception('Required fields missing.');
    }

    // Validate user_type
    if (!in_array($user_type, ['Doctor', 'Nurse'])) {
        throw new Exception('Invalid user type.');
    }

    // Hash password
    $password_hashed = password_hash($password_plain, PASSWORD_DEFAULT);

    // Connect to DB
    $pdo = pdo_connect_mysql();

    // Check if username or email already exists in admin table
    $stmt = $pdo->prepare("SELECT 1 FROM admin WHERE username = ? OR email = ?");
    $stmt->execute([$username, $email]);
    if ($stmt->fetchColumn()) {
        echo json_encode(['success' => false, 'message' => 'Username or email already exists']);
        exit();
    }

    // Insert into admin table
    $insert_stmt = $pdo->prepare("
        INSERT INTO admin (username, email, password, user_type)
        VALUES (?, ?, ?, ?)
    ");
    $insert_stmt->execute([$username, $email, $password_hashed, $user_type]);

    $staff_id = $pdo->lastInsertId();

    // Log activity
    $admin_id = $_SESSION['user_id'] ?? null;
    $admin_username = $_SESSION['username'] ?? 'System';
    $admin_role = $_SESSION['user_type'] ?? 'Unknown';
    $action_description = "Added staff: ID {$staff_id}, Name {$username}, Role {$user_type}";

    $logStmt = $pdo->prepare("
        INSERT INTO activity_logs (user_id, username, role, action_type, action_description, status)
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    $logStmt->execute([$admin_id, $admin_username, $admin_role, 'Add Staff', $action_description, 'SUCCESS']);

    echo json_encode(['success' => true, 'message' => 'Staff added successfully']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
exit();
