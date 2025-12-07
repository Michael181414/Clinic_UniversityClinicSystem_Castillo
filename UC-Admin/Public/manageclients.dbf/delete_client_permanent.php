<?php
session_start();
require 'config/database.php';

if (!isset($_SESSION['user_id'])) {
    $_SESSION['message'] = 'Your session has expired. Please log in again.';
    header('Location: ../../../index.php');
    exit;
}

if (!isset($_GET['id'])) {
    die("Invalid request");
}

$clientID = intval($_GET['id']);
$pdo = pdo_connect_mysql();

// PERMANENT DELETE
$stmt = $pdo->prepare("DELETE FROM clients WHERE ClientID = ?");
$stmt->execute([$clientID]);

header("Location: ../Data_Management.php?tab=deleted&msg=restored");
exit;
