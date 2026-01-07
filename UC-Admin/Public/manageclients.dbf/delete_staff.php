<?php
session_start();
require_once('../config/database.php');

header('Content-Type: application/json; charset=utf-8');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method.');
    }

    $staff_id = intval($_POST['id'] ?? 0);
    if (!$staff_id) throw new Exception('Staff ID missing.');

    $pdo = pdo_connect_mysql();

    // Get staff info for logging
    $stmt = $pdo->prepare("SELECT username, user_type FROM admin WHERE id = ?");
    $stmt->execute([$staff_id]);
    $staff = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$staff) throw new Exception('Staff not found.');

    // Delete staff
    $deleteStmt = $pdo->prepare("DELETE FROM admin WHERE id = ?");
    $deleteStmt->execute([$staff_id]);

    // Log activity
    $admin_id = $_SESSION['user_id'] ?? null;
    $admin_username = $_SESSION['username'] ?? 'System';
    $admin_role = $_SESSION['user_type'] ?? 'Unknown';
    $action_description = "Deleted staff: ID {$staff_id}, Name {$staff['username']}, Role {$staff['user_type']}";

    $logStmt = $pdo->prepare("
        INSERT INTO activity_logs (user_id, username, role, action_type, action_description, status)
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    $logStmt->execute([$admin_id, $admin_username, $admin_role, 'Delete Staff', $action_description, 'SUCCESS']);

    echo json_encode(['success' => true, 'message' => 'Staff deleted successfully']);
    exit;
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    exit;
}
