<?php
require '../config/database.php';
session_start();

$pdo = pdo_connect_mysql();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Read JSON input
$data = json_decode(file_get_contents("php://input"), true);

if (!$data || !isset($data['client_id'])) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

try {
    date_default_timezone_set('Asia/Manila');

    $client_id = $data['client_id'];
    $actionDate = date('Y-m-d');
    $actionTime = date('h:i:s A');

    // Get client email for logging
    $checkClient = $pdo->prepare("SELECT Email FROM clients WHERE ClientID = ?");
    $checkClient->execute([$client_id]);
    $client = $checkClient->fetch(PDO::FETCH_ASSOC);
    $client_email = $client['Email'] ?? 'Unknown';

    // Insert into history
    $insertHistory = $pdo->prepare("
        INSERT INTO history (ClientID, actionDate, actionTime)
        VALUES (?, ?, ?)
    ");
    $insertHistory->execute([$client_id, $actionDate, $actionTime]);
    $historyID = $pdo->lastInsertId();

    // Insert into prescriptions (WITH sex)
    $stmt = $pdo->prepare("
        INSERT INTO prescriptions
        (
            ClientID,
            historyID,
            patient_name,
            age,
            sex,
            impression,
            physician,
            license_no,
            notes,
            date_created
        )
        VALUES
        (
            :client_id,
            :history_id,
            :patient_name,
            :age,
            :sex,
            :impression,
            :physician,
            :license_no,
            :notes,
            :date_created
        )
    ");

    $stmt->execute([
        ':client_id'    => $client_id,
        ':history_id'   => $historyID,
        ':patient_name' => $data['patient_name'],
        ':age'          => $data['age'],
        ':sex'          => $data['sex'],
        ':impression'   => $data['impression'],
        ':physician'    => $data['physician'],
        ':license_no'   => $data['license_no'],
        ':notes'        => $data['notes'],
        ':date_created' => $data['date_created']
    ]);

    // Insert into consultations
    $remarks = "Prescription created on {$actionDate}";
    $stmt2 = $pdo->prepare("
        INSERT INTO consultations
        (client_id, historyID, consultation_date, certificate_issued, remarks)
        VALUES (?, ?, CURDATE(), FALSE, ?)
    ");
    $stmt2->execute([$client_id, $historyID, $remarks]);

    // Activity log
    $user_id   = $_SESSION['user_id'] ?? null;
    $username  = $_SESSION['username'] ?? 'System';
    $user_role = $_SESSION['user_type'] ?? 'Unknown';

    $logStmt = $pdo->prepare("
        INSERT INTO activity_logs
        (user_id, username, role, action_type, action_description, status)
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    $logStmt->execute([
        $user_id,
        $username,
        $user_role,
        'Create Prescription',
        "Created prescription for Client Email: $client_email (ClientID: $client_id)",
        'SUCCESS'
    ]);

    echo json_encode(['success' => true]);
} catch (PDOException $e) {

    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
