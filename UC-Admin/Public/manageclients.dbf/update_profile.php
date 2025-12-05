<?php
require_once __DIR__ . '/../config/database.php';
$pdo = pdo_connect_mysql();

session_start();
$clientId = $_SESSION['user_id'] ?? null;

header('Content-Type: application/json');

if (!$clientId) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in.']);
    exit;
}

try {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (!$username || !$email) {
        echo json_encode(['status' => 'error', 'message' => 'Full name and email are required.']);
        exit;
    }

    if ($password !== '') {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE admin SET username = ?, email = ?, password = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$username, $email, $hashedPassword, $clientId]);
    } else {
        $sql = "UPDATE admin SET username = ?, email = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$username, $email, $clientId]);
    }

    echo json_encode(['status' => 'success']);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
