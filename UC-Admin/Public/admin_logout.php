<?php
session_start();
require 'config/database.php';
$pdo = pdo_connect_mysql();

// Get session values BEFORE destroying them
$admin_id       = $_SESSION['user_id']    ?? null;
$admin_username = $_SESSION['username']   ?? 'System';
$admin_role     = $_SESSION['user_type']  ?? 'Unknown';

// Log description
$action_description = "$admin_role logged out";

$logStmt = $pdo->prepare("
    INSERT INTO activity_logs (user_id, username, role, action_type, action_description, status) 
    VALUES (?, ?, ?, ?, ?, ?)
");

$logStmt->execute([
    $admin_id,           // user_id
    $admin_username,     // username
    $admin_role,         // Doctor or Nurse
    'Logout',            // ✅ Correct action_type
    $action_description, // details
    'SUCCESS'
]);

// Destroy session
$_SESSION = [];
session_unset();
session_destroy();

// Remove session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

header("Location: ../../index.php");
exit();
