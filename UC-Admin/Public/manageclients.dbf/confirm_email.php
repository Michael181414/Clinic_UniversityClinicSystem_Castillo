<?php
require_once('../config/database.php');
$pdo = pdo_connect_mysql();

session_start();
$UserId = $_SESSION['user_id'] ?? null;

header('Content-Type: application/json');

$email = trim($_POST['email'] ?? '');
if (!$email) {
    echo json_encode(['exists' => false]);
    exit;
}

$stmt = $pdo->prepare("SELECT COUNT(*) FROM admin WHERE email = ? AND id != ?");
$stmt->execute([$email, $UserId]);
$count = $stmt->fetchColumn();

echo json_encode(['exists' => $count > 0]);
