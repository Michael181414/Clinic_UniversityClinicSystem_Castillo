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
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $birthdate = $_POST['birthdate'] ?? null;

    if (!$fullName || !$email || !$username) {
        echo json_encode(['status' => 'error', 'message' => 'Full name, email, and username are required.']);
        exit;
    }

    $names = explode(' ', $fullName, 2);
    $firstname = $names[0] ?? '';
    $lastname = $names[1] ?? '';

    if ($password !== '') {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE clients SET Firstname = ?, Lastname = ?, Email = ?, Username = ?, BirthDate = ?, Password = ? WHERE ClientID = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$firstname, $lastname, $email,  $username, $birthdate, $hashedPassword, $clientId]);
    } else {
        $sql = "UPDATE clients SET Firstname = ?, Lastname = ?, Email = ?, Username = ?, BirthDate = ? WHERE ClientID = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$firstname, $lastname, $email, $username, $birthdate, $clientId]);
    }

    echo json_encode(['status' => 'success']);
} catch (PDOException $e) {

    // ✅ Duplicate Email
    if ($e->getCode() == 23000 && str_contains($e->getMessage(), 'Email')) {
        echo json_encode([
            'status' => 'error',
            'message' => 'This email is already in use. Please use another one.'
        ]);
        exit;
    }

    // ✅ Duplicate Username
    if ($e->getCode() == 23000 && str_contains($e->getMessage(), 'Username')) {
        echo json_encode([
            'status' => 'error',
            'message' => 'This username is already taken. Please choose another.'
        ]);
        exit;
    }

    // ✅ Any Other Database Error (GENERIC MESSAGE ONLY)
    echo json_encode([
        'status' => 'error',
        'message' => 'Something went wrong while saving your profile. Please try again.'
    ]);
}
