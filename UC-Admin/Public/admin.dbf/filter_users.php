<?php
require_once 'config/database.php';
$pdo = pdo_connect_mysql();

header('Content-Type: application/json');

$role = $_GET['role'] ?? 'all';

if ($role === 'all') {
    $stmt = $pdo->query("SELECT * FROM activity_logs ORDER BY id DESC");
} else {
    $stmt = $pdo->prepare("SELECT * FROM activity_logs WHERE role = ? ORDER BY id DESC");
    $stmt->execute([$role]);
}

$logs = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Format date + time
foreach ($logs as &$log) {
    $datetime = strtotime($log['created_at']);

    $log['date_only'] = date("M d, Y", $datetime);
    $log['time_12h']  = date("h:i A", $datetime);
}

echo json_encode($logs);
