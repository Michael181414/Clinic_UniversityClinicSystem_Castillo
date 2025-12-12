<?php
session_start();
require 'config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../../../index.php');
    exit;
}

$pdo = pdo_connect_mysql();

if (!isset($_GET['id'], $_GET['action'])) {
    header('Location: ../ManageClients.php?delete=invalid');
    exit;
}
$clientID = (int) $_GET['id'];
$action   = $_GET['action']; // archive | permanent

// --- Validate action ---
if ($action !== 'archive' && $action !== 'permanent') {
    $redirect = $_SERVER['HTTP_REFERER'] ?? '../ManageClients.php';
    header("Location: $redirect?delete=invalid");
    exit;
}

// Admin info
$admin_id       = $_SESSION['user_id'];
$admin_username = $_SESSION['username'] ?? 'System';
$admin_role     = $_SESSION['user_type'] ?? 'Unknown';

// Fetch client from Clients table
$clientStmt = $pdo->prepare("SELECT * FROM Clients WHERE ClientID = ?");
$clientStmt->execute([$clientID]);
$client = $clientStmt->fetch(PDO::FETCH_ASSOC);
$fromArchive = false;

// If not found in main table, check archive_clients
if (!$client) {
    $clientStmt = $pdo->prepare("SELECT * FROM archive_clients WHERE ClientID = ?");
    $clientStmt->execute([$clientID]);
    $client = $clientStmt->fetch(PDO::FETCH_ASSOC);
    $fromArchive = true;
}

// If still not found, return error
if (!$client) {
    $redirect = $_SERVER['HTTP_REFERER'] ?? '../ManageClients.php';
    $message_text = 'Client not found';
    header("Location: $redirect?delete=error&msg=" . urlencode($message_text));
    exit;
}

try {
    $pdo->beginTransaction();

    if ($action === 'archive') {
        if (!$fromArchive) {
            // Move client to archive_clients
            $archiveStmt = $pdo->prepare("
                INSERT INTO archive_clients
                (ClientID, Firstname, Lastname, Email, Username, Sex, BirthDate, Password, ClientType, Department, Course, profilePicturePath, ResetCode, deleted_by, deleted_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
            ");
            $archiveStmt->execute([
                $client['ClientID'],
                $client['Firstname'],
                $client['Lastname'],
                $client['Email'],
                $client['Username'],
                $client['Sex'],
                $client['BirthDate'],
                $client['Password'],
                $client['ClientType'],
                $client['Department'],
                $client['Course'],
                $client['profilePicturePath'],
                $client['ResetCode'],
                $admin_id
            ]);

            // Delete from main Clients table
            $delStmt = $pdo->prepare("DELETE FROM Clients WHERE ClientID = ?");
            $delStmt->execute([$clientID]);
        }
        $action_type = 'Archive Client';
        $action_desc = "Archived client. ID: $clientID, Email: {$client['Email']}";
    } elseif ($action === 'permanent') {
        // Delete permanently from wherever it exists
        $table = $fromArchive ? 'archive_clients' : 'Clients';
        $delStmt = $pdo->prepare("DELETE FROM $table WHERE ClientID = ?");
        $delStmt->execute([$clientID]);

        $action_type = 'Permanent Delete Client';
        $action_desc = "Permanently deleted client. ID: $clientID, Email: {$client['Email']}";
    } else {
        throw new Exception('Invalid delete action');
    }

    // Log action
    $logStmt = $pdo->prepare("
        INSERT INTO activity_logs 
        (user_id, username, role, action_type, action_description, status)
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    $logStmt->execute([
        $admin_id,
        $admin_username,
        $admin_role,
        $action_type,
        $action_desc,
        'SUCCESS'
    ]);

    $pdo->commit();

    $redirect = $_SERVER['HTTP_REFERER'] ?? '../ManageClients.php';
    $message_text = 'Client deleted successfully';
    header("Location: $redirect?delete=success&msg=" . urlencode($message_text));
    exit;
} catch (Exception $e) {
    $pdo->rollBack();

    // Log failure
    $logStmt = $pdo->prepare("
        INSERT INTO activity_logs 
        (user_id, username, role, action_type, action_description, status)
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    $logStmt->execute([
        $admin_id,
        $admin_username,
        $admin_role,
        'Delete Client Failed',
        $e->getMessage(),
        'FAILED'
    ]);

    $redirect = $_SERVER['HTTP_REFERER'] ?? '../ManageClients.php';
    $message_text = 'Failed to delete client';
    header("Location: $redirect?delete=error&msg=" . urlencode($message_text));
    exit;
}
