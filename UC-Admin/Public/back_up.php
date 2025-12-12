<?php
session_start(); // Needed to get admin info

$host = "localhost";
$user = "root";
$pass = "";
$db   = "University_Clinic_System";
$port = "3306";

// Admin info
$admin_id       = $_SESSION['user_id'] ?? 0;
$admin_username = $_SESSION['username'] ?? 'System';
$admin_role     = $_SESSION['user_type'] ?? 'Unknown';

// Backup directory
$backupDir = __DIR__ . "/backups/";
if (!is_dir($backupDir)) {
    mkdir($backupDir, 0777, true);
}

// Backup file
$backupFile = $backupDir . "university_clinic_backup_" . date("Y-m-d_H-i-s") . ".sql";

// mysqldump path
$mysqldumpPath = "mysqldump";

if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
    $command = "\"$mysqldumpPath\" --user=\"$user\" --password=\"$pass\" --host=\"$host\" --port=$port $db > \"$backupFile\" 2>nul";
} else {
    $command = "\"$mysqldumpPath\" --user=\"$user\" --password=\"$pass\" --host=\"$host\" --port=$port $db > \"$backupFile\" 2>/dev/null";
}

$output = [];
$return_var = null;
exec($command, $output, $return_var);

// Database connection for logging
$conn = new mysqli($host, $user, $pass, $db, $port);

if ($return_var === 0 && file_exists($backupFile)) {
    echo "success|backups/" . basename($backupFile);

    // Insert into backup_logs
    if (!$conn->connect_error) {
        $stmt = $conn->prepare("INSERT INTO backup_logs (file_name, backup_date, backup_time, status) VALUES (?, ?, ?, ?)");
        $date = date("Y-m-d");
        $time = date("h:i:s A");
        $status = "success";
        $fileName = basename($backupFile);

        $stmt->bind_param("ssss", $fileName, $date, $time, $status);
        $stmt->execute();
        $stmt->close();

        // ✅ Log admin action
        $logStmt = $conn->prepare("
            INSERT INTO activity_logs
            (user_id, username, role, action_type, action_description, status)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $action_type = "Database Backup";
        $action_desc = "Admin performed a database backup. File: $fileName";
        $log_status  = "SUCCESS";
        $logStmt->bind_param("isssss", $admin_id, $admin_username, $admin_role, $action_type, $action_desc, $log_status);
        $logStmt->execute();
        $logStmt->close();
        $conn->close();
    }
} else {
    echo "error|Backup failed\n" . implode("\n", $output);

    // Insert failed attempt into backup_logs
    if (!$conn->connect_error) {
        $stmt = $conn->prepare("INSERT INTO backup_logs (file_name, backup_date, backup_time, status) VALUES (?, ?, ?, ?)");
        $date = date("Y-m-d");
        $time = date("H:i:s");
        $status = "failed";
        $fileName = basename($backupFile);

        $stmt->bind_param("ssss", $fileName, $date, $time, $status);
        $stmt->execute();
        $stmt->close();

        // ✅ Log failed admin action
        $logStmt = $conn->prepare("
            INSERT INTO activity_logs
            (user_id, username, role, action_type, action_description, status)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $action_type = "Database Backup";
        $action_desc = "Admin attempted a database backup but it failed. File: $fileName";
        $log_status  = "FAILED";
        $logStmt->bind_param("isssss", $admin_id, $admin_username, $admin_role, $action_type, $action_desc, $log_status);
        $logStmt->execute();
        $logStmt->close();
        $conn->close();
    }
}
