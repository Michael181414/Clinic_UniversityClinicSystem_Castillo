<?php
session_start();
require 'config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../../../index.php');
    exit;
}

$pdo = pdo_connect_mysql();

if (isset($_GET['id'])) {
    $clientID = $_GET['id'];

    // Fetch client info
    $clientStmt = $pdo->prepare("SELECT email FROM Clients WHERE ClientID = ? AND deleted_at IS NULL");
    $clientStmt->execute([$clientID]);
    $client = $clientStmt->fetch(PDO::FETCH_ASSOC);

    if (!$client) {
        $redirect = $_SERVER['HTTP_REFERER'] ?? '../ManageClients.php';
        header("Location: $redirect?delete=notfound");
        exit;
    }

    $clientEmail = $client['email'] ?? 'N/A';

    // Soft delete: Mark as deleted
    $stmt = $pdo->prepare("UPDATE Clients SET deleted_at = NOW() WHERE ClientID = ?");
    $stmt->execute([$clientID]);

    // Log admin action
    $admin_id       = $_SESSION['user_id'];
    $admin_username = $_SESSION['username'] ?? 'System';
    $admin_role     = $_SESSION['user_type'] ?? 'Unknown';

    $action_description = "Soft deleted client: ID: $clientID, (Email: $clientEmail).";

    $logStmt = $pdo->prepare("
        INSERT INTO activity_logs (user_id, username, role, action_type, action_description, status) 
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    $logStmt->execute([
        $admin_id,
        $admin_username,
        $admin_role,
        'Soft Delete Client',
        $action_description,
        'SUCCESS'
    ]);

    // Redirect
    $redirect = $_SERVER['HTTP_REFERER'] ?? '../ManageClients.php';
    header("Location: $redirect?delete=success");
    exit;
}
