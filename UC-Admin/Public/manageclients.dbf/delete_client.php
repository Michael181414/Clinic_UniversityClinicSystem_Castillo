v<?php
    // delete_client.php
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
    $action   = $_GET['action']; // trash | permanent

    // Admin info
    $admin_id       = $_SESSION['user_id'];
    $admin_username = $_SESSION['username'] ?? 'System';
    $admin_role     = $_SESSION['user_type'] ?? 'Unknown';

    // Fetch client info (regardless of deleted_at)
    $clientStmt = $pdo->prepare("SELECT Email FROM Clients WHERE ClientID = ?");
    $clientStmt->execute([$clientID]);
    $client = $clientStmt->fetch(PDO::FETCH_ASSOC);

    if (!$client) {
        $redirect = $_SERVER['HTTP_REFERER'] ?? '../ManageClients.php';
        header("Location: $redirect?delete=notfound");
        exit;
    }

    $clientEmail = $client['Email'];

    try {
        $pdo->beginTransaction();

        if ($action === 'trash') {
            // Soft delete (Move to Trash)
            $stmt = $pdo->prepare("
            UPDATE Clients
            SET deleted_at = NOW(),
                deleted_by = ?
            WHERE ClientID = ?
        ");
            $stmt->execute([$admin_id, $clientID]);

            $action_type = 'Soft Delete Client';
            $action_desc = "Moved client to trash. ID: $clientID, Email: $clientEmail";
        } elseif ($action === 'permanent') {
            // Hard delete (Permanent)
            $stmt = $pdo->prepare("DELETE FROM Clients WHERE ClientID = ?");
            $stmt->execute([$clientID]);

            $action_type = 'Permanent Delete Client';
            $action_desc = "Permanently deleted client. ID: $clientID, Email: $clientEmail";
        } else {
            throw new Exception('Invalid delete action');
        }

        // Log the action
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
        header("Location: $redirect?delete=success");
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
        header("Location: $redirect?delete=error");
        exit;
    }
    ?>