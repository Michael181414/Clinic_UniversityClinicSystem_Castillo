<?php
session_start();
header('Content-Type: application/json');

$host = "localhost";
$user = "root";
$pass = "";
$db   = "University_Clinic_System";
$port = "3306";

// Admin info
$admin_id       = $_SESSION['user_id'] ?? 0;
$admin_username = $_SESSION['username'] ?? 'System';
$admin_role     = $_SESSION['user_type'] ?? 'Unknown';

if (!isset($_FILES['backup_file']) || $_FILES['backup_file']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode([
        "status" => "error",
        "msg"    => "❌ No backup file uploaded"
    ]);
    exit;
}

$backupFile = $_FILES['backup_file']['tmp_name'];
$mysqlPath = "mysql";
$command = "\"$mysqlPath\" --user=\"$user\" --password=\"$pass\" --host=\"$host\" --port=$port $db < \"$backupFile\"";

$output = [];
$return_var = null;
exec($command, $output, $return_var);

// Connect to DB for logging
$conn = new mysqli($host, $user, $pass, $db, $port);

if ($return_var === 0) {
    echo json_encode([
        "status" => "success",
        "msg"    => "✅ Database restored successfully!"
    ]);

    // ✅ Log admin action
    if (!$conn->connect_error) {
        $logStmt = $conn->prepare("
            INSERT INTO activity_logs
            (user_id, username, role, action_type, action_description, status)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $action_type = "Database Restore";
        $action_desc = "Admin restored the database using backup file: " . basename($backupFile);
        $log_status  = "SUCCESS";
        $logStmt->bind_param("isssss", $admin_id, $admin_username, $admin_role, $action_type, $action_desc, $log_status);
        $logStmt->execute();
        $logStmt->close();
        $conn->close();
    }
} else {
    echo json_encode([
        "status" => "error",
        "msg"    => "Restore failed: " . implode("\n", $output)
    ]);

    // ✅ Log failed admin action
    if (!$conn->connect_error) {
        $logStmt = $conn->prepare("
            INSERT INTO activity_logs
            (user_id, username, role, action_type, action_description, status)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $action_type = "Database Restore";
        $action_desc = "Admin attempted to restore the database using backup file: " . basename($backupFile) . " but failed";
        $log_status  = "FAILED";
        $logStmt->bind_param("isssss", $admin_id, $admin_username, $admin_role, $action_type, $action_desc, $log_status);
        $logStmt->execute();
        $logStmt->close();
        $conn->close();
    }
}

exit;
