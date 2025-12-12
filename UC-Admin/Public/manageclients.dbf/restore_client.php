<?php
session_start();
require 'config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../../../index.php');
    exit;
}

$pdo = pdo_connect_mysql();

if (!isset($_GET['id'])) {
    $redirect = $_SERVER['HTTP_REFERER'] ?? '../ManageClients.php';
    header("Location: $redirect?restore=invalid");
    exit;
}

$clientID = (int) $_GET['id'];

$admin_id       = $_SESSION['user_id'];
$admin_username = $_SESSION['username'] ?? 'System';
$admin_role     = $_SESSION['user_type'] ?? 'Unknown';

// Fetch client from archive
$clientStmt = $pdo->prepare("SELECT * FROM archive_clients WHERE ClientID = ?");
$clientStmt->execute([$clientID]);
$client = $clientStmt->fetch(PDO::FETCH_ASSOC);

if (!$client) {
    $redirect = $_SERVER['HTTP_REFERER'] ?? '../ManageClients.php';
    header("Location: $redirect?restore=notfound");
    exit;
}

try {
    $pdo->beginTransaction();

    // Insert back into Clients table with original ClientID
    $insertStmt = $pdo->prepare("
        INSERT INTO Clients
        (ClientID, Firstname, Lastname, Email, Username, Sex, BirthDate, Password, ClientType, Department, Course, profilePicturePath, ResetCode)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $insertStmt->execute([
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
        $client['ResetCode']
    ]);

    // Delete from archive_clients
    $delStmt = $pdo->prepare("DELETE FROM archive_clients WHERE ClientID = ?");
    $delStmt->execute([$clientID]);

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
        'Restore Client',
        "Restored archived client. ID: $clientID, Email: {$client['Email']}",
        'SUCCESS'
    ]);

    $pdo->commit();

    $redirect = $_SERVER['HTTP_REFERER'] ?? '../ManageClients.php';
    header("Location: $redirect?restore=success");
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
        'Restore Client Failed',
        $e->getMessage(),
        'FAILED'
    ]);

    $redirect = $_SERVER['HTTP_REFERER'] ?? '../ManageClients.php';
    header("Location: $redirect?restore=error");
    exit;
}
