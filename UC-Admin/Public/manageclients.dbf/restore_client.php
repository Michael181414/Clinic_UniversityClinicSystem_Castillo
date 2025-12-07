<?php
session_start();
require 'config/database.php';

if (!isset($_SESSION['user_id'])) {
    die("Unauthorized access");
}

if (!isset($_GET['id'])) {
    die("Invalid request");
}

$clientID = intval($_GET['id']);
$pdo = pdo_connect_mysql();

// Restore = set deleted_at = NULL
$stmt = $pdo->prepare("UPDATE clients SET deleted_at = NULL WHERE ClientID = ?");
$stmt->execute([$clientID]);

header("Location: ../Data_Management.php?tab=deleted&msg=restored");
exit;
