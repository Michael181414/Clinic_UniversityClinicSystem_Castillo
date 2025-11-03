<?php
require_once __DIR__ . '/../config/database.php';
$pdo = pdo_connect_mysql();

session_start();
$clientId = $_SESSION['ClientID'] ?? null;

header('Content-Type: application/json');

$email = trim($_POST['email'] ?? '');
if (!$email) {
    echo json_encode(['exists' => false]);
    exit;
}

$stmt = $pdo->prepare("SELECT COUNT(*) FROM clients WHERE Email = ? AND ClientID != ?");
$stmt->execute([$email, $clientId]);
$count = $stmt->fetchColumn();

echo json_encode(['exists' => $count > 0]);
