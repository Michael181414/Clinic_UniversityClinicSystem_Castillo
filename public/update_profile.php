<?php
require_once __DIR__ . '/../config/database.php';
$pdo = pdo_connect_mysql();

session_start();
$clientId = $_SESSION['ClientID'] ?? null;

header('Content-Type: application/json');

if (!$clientId) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in.']);
    exit;
}

try {
    $fullName = trim($_POST['fullName'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $birthdate = $_POST['birthdate'] ?? null;

    if (!$fullName || !$email) {
        echo json_encode(['status' => 'error', 'message' => 'Full name and email are required.']);
        exit;
    }

    $names = explode(' ', $fullName, 2);
    $firstname = $names[0] ?? '';
    $lastname = $names[1] ?? '';

    if ($password !== '') {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE clients SET Firstname = ?, Lastname = ?, Email = ?, BirthDate = ?, Password = ? WHERE ClientID = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$firstname, $lastname, $email, $birthdate, $hashedPassword, $clientId]);
    } else {
        $sql = "UPDATE clients SET Firstname = ?, Lastname = ?, Email = ?, BirthDate = ? WHERE ClientID = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$firstname, $lastname, $email, $birthdate, $clientId]);
    }

    echo json_encode(['status' => 'success']);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
