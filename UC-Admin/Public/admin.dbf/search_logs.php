<?php
require_once 'config/database.php';
$pdo = pdo_connect_mysql();

header('Content-Type: application/json');

$role = $_GET['role'] ?? 'all';
$search = $_GET['search'] ?? '';
$search = "%$search%";

$query = "SELECT * FROM activity_logs WHERE 1=1";

// Role filter
if ($role !== 'all') {
    $query .= " AND role = :role";
}

// Search filter
if (!empty($search) && $search !== '%%') {
    $query .= " AND (
        username LIKE :search OR
        role LIKE :search OR
        action_type LIKE :search OR
        action_description LIKE :search OR
        status LIKE :search OR
        id LIKE :search OR
        user_id LIKE :search OR
        created_at LIKE :search
    )";
}

$query .= " ORDER BY id DESC";

$stmt = $pdo->prepare($query);

if ($role !== 'all') $stmt->bindValue(':role', $role);
if (!empty($search) && $search !== '%%') $stmt->bindValue(':search', $search);

$stmt->execute();

$logs = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Format date + time
foreach ($logs as &$log) {
    $datetime = strtotime($log['created_at']);
    $log['date_only'] = date("M d, Y", $datetime);
    $log['time_12h']  = date("h:i A", $datetime);
}

echo json_encode($logs);
