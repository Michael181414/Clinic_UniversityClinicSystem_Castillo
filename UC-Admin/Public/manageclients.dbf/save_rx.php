<?php
require '../config/database.php';
session_start(); // Needed for session info
$pdo = pdo_connect_mysql();

// Read JSON body
$data = json_decode(file_get_contents("php://input"), true);

// Validate input
if (!$data || !isset($data['client_id'])) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

try {
    date_default_timezone_set('Asia/Manila');

    $client_id = $data['client_id'];
    $actionDate = date('Y-m-d');
    $actionTime12hr = date('h:i:s A');
    $date_issued = date('Y-m-d');

    // --- Get client email for logging ---
    $checkClient = $pdo->prepare("SELECT Email FROM clients WHERE ClientID = ?");
    $checkClient->execute([$client_id]);
    $client = $checkClient->fetch(PDO::FETCH_ASSOC);
    $client_email = $client['Email'] ?? 'Unknown';

    // --- Insert into history table ---
    $insertHistory = $pdo->prepare("
        INSERT INTO history (ClientID, actionDate, actionTime)
        VALUES (?, ?, ?)
    ");
    $insertHistory->execute([$client_id, $actionDate, $actionTime12hr]);
    $historyID = $pdo->lastInsertId();

    // --- Insert into prescriptions table ---
    $stmt = $pdo->prepare("INSERT INTO prescriptions 
        (ClientID, historyID, patient_name, age, impression, physician, license_no, notes, date_created)
        VALUES (:client_id, :history_id, :patient_name, :age, :impression, :physician, :license_no, :notes, :date_created)");

    $stmt->execute([
        ':client_id' => $client_id,
        ':history_id' => $historyID,
        ':patient_name' => $data['patient_name'],
        ':age' => $data['age'],
        ':impression' => $data['impression'],
        ':physician' => $data['physician'],
        ':license_no' => $data['license_no'],
        ':notes' => $data['notes'],
        ':date_created' => $data['date_created']
    ]);

    // --- Insert into consultations table ---
    $remarks = "Prescription created on $date_issued";
    $stmt2 = $pdo->prepare("INSERT INTO consultations (client_id, historyID, consultation_date, certificate_issued, remarks) 
                            VALUES (?, ?, CURDATE(), FALSE, ?)");
    $stmt2->execute([$client_id, $historyID, $remarks]);

    // --- Activity log ---
    $user_id   = $_SESSION['user_id'] ?? null;
    $username  = $_SESSION['username'] ?? 'System';
    $user_role = $_SESSION['user_type'] ?? 'Unknown';

    $action_description = "Created prescription for Client Email: $client_email, ClientID: $client_id";

    $logStmt = $pdo->prepare("
        INSERT INTO activity_logs (user_id, username, role, action_type, action_description, status)
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    $logStmt->execute([
        $user_id,
        $username,
        $user_role,
        'Create Prescription',
        $action_description,
        'SUCCESS'
    ]);

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    // Log failure in activity_logs
    $user_id   = $_SESSION['user_id'] ?? null;
    $username  = $_SESSION['username'] ?? 'System';
    $user_role = $_SESSION['user_type'] ?? 'Unknown';

    $error_description = "Failed to create prescription for Client Email: $client_email, ClientID: $client_id. Error: " . $e->getMessage();
    $logStmt = $pdo->prepare("
        INSERT INTO activity_logs (user_id, username, role, action_type, action_description, status)
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    $logStmt->execute([
        $user_id,
        $username,
        $user_role,
        'Create Prescription',
        $error_description,
        'ERROR'
    ]);

    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
