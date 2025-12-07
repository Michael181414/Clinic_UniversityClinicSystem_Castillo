<?php
require_once __DIR__ . '/../config/database.php';
$pdo = pdo_connect_mysql();

session_start();
$clientId = $_SESSION['ClientID'] ?? null;

header('Content-Type: application/json');

if (!$clientId) {
    echo json_encode(['error' => 'User not logged in']);
    exit;
}

$email = trim($_POST['email'] ?? '');
$username = trim($_POST['username'] ?? '');

try {
    $emailExists = false;
    $usernameExists = false;

    if ($email) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM clients WHERE Email = ? AND ClientID != ?");
        $stmt->execute([$email, $clientId]);
        $emailExists = $stmt->fetchColumn() > 0;
    }

    if ($username) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM clients WHERE Username = ? AND ClientID != ?");
        $stmt->execute([$username, $clientId]);
        $usernameExists = $stmt->fetchColumn() > 0;
    }

    echo json_encode([
        'emailExists' => $emailExists,
        'usernameExists' => $usernameExists
    ]);
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
