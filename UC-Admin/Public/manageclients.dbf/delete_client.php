<?php
session_start();
require 'config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$pdo = pdo_connect_mysql();

if (isset($_GET['id'])) {
    $clientID = $_GET['id'];

    // Fetch client info
    $clientStmt = $pdo->prepare("SELECT email FROM Clients WHERE ClientID = ?");
    $clientStmt->execute([$clientID]);
    $client = $clientStmt->fetch(PDO::FETCH_ASSOC);

    if (!$client) {
        // Client not found
        $redirect = $_SERVER['HTTP_REFERER'] ?? 'clients_list.php';
        header("Location: $redirect?delete=notfound");
        exit;
    }

    $clientEmail = $client['email'] ?? 'N/A';

    // Delete client
    $stmt = $pdo->prepare("DELETE FROM Clients WHERE ClientID = ?");
    $stmt->execute([$clientID]);

    // Log admin action
    $admin_id       = $_SESSION['user_id'];
    $admin_username = $_SESSION['username'] ?? 'System';
    $admin_role     = $_SESSION['user_type'] ?? 'Unknown';

    $action_description = "Deleted client: ID: $clientID, (Email: $clientEmail), ";

    $logStmt = $pdo->prepare("
        INSERT INTO activity_logs (user_id, username, role, action_type, action_description, status) 
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    $logStmt->execute([
        $admin_id,
        $admin_username,
        $admin_role,
        'Delete Client',
        $action_description,
        'SUCCESS'
    ]);

    // Redirect back safely
    $redirect = $_SERVER['HTTP_REFERER'] ?? 'clients_list.php';
    $status = ($stmt->rowCount() > 0) ? 'success' : 'error';
    header("Location: $redirect?delete=$status");
    exit;
}
