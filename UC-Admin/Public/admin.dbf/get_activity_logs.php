<?php
// Include database connection
require_once 'config/database.php';

$pdo = pdo_connect_mysql();

// Prepare and execute query with 12-hour time format
$stmt = $pdo->prepare("
    SELECT 
        id,
        user_id,
        username,
        role,
        action_type,
        action_description,
        record_id,
        status,
        DATE_FORMAT(created_at, '%Y-%m-%d') AS date_only,
        DATE_FORMAT(created_at, '%h:%i %p') AS time_12h
    FROM activity_logs
    ORDER BY created_at DESC
");
$stmt->execute();

// Fetch all rows
$activity_logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
