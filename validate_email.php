<?php
require 'config/database.php';

if (isset($_POST['username'])) {
    $username = trim($_POST['username']);

    $pdo = pdo_connect_mysql();
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM Clients WHERE Username = ?");
    $stmt->execute([$username]);
    $exists = $stmt->fetchColumn() > 0;

    echo json_encode([
        'valid' => !$exists,
        'message' => $exists ? 'This username is already taken.' : 'Username is available.'
    ]);
}
