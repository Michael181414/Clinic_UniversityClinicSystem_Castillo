<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start(); // Start session to get admin info

require '../config/database.php';
$pdo = pdo_connect_mysql();

$clientID    = $_REQUEST['client_id'] ?? null;
$bp          = $_REQUEST['bp'] ?? '';
$hr_pr       = $_REQUEST['hr_pr'] ?? '';
$temp        = $_REQUEST['temp'] ?? '';
$o2sat       = $_REQUEST['o2sat'] ?? '';
$subjective  = $_REQUEST['subjective'] ?? '';
$objective   = $_REQUEST['objective'] ?? '';
$assessment  = $_REQUEST['assessment'] ?? '';
$plan        = $_REQUEST['plan'] ?? '';

header('Content-Type: application/json');

if (!$clientID) {
    echo json_encode(['status' => 'error', 'message' => 'Missing ClientID.']);
    exit;
}

// Check if client exists and get email
$checkClient = $pdo->prepare("SELECT Email FROM clients WHERE ClientID = ?");
$checkClient->execute([$clientID]);
$client = $checkClient->fetch(PDO::FETCH_ASSOC);

if (!$client) {
    echo json_encode(['status' => 'error', 'message' => "Error: ClientID $clientID does not exist."]);
    exit;
}

// Get session user info
$user_id   = $_SESSION['user_id'] ?? null;
$username  = $_SESSION['username'] ?? 'System';
$user_role = $_SESSION['user_type'] ?? 'Unknown';

try {
    $pdo->beginTransaction();

    date_default_timezone_set('Asia/Manila');
    $actionDate     = date('Y-m-d');
    $actionTime12hr = date('h:i:s A');

    // Insert into history table
    $insertHistory = $pdo->prepare("
        INSERT INTO history (ClientID, actionDate, actionTime) 
        VALUES (?, ?, ?)
    ");
    $insertHistory->execute([$clientID, $actionDate, $actionTime12hr]);
    $historyID = $pdo->lastInsertId();

    // Insert into consultationrecords table
    $stmt = $pdo->prepare("
        INSERT INTO consultationrecords 
        (ClientID, historyid, BP, HR_PR, Temp, O2sat, Subjective, Objective, Assesment, Plan) 
        VALUES 
        (:clientID, :historyID, :bp, :hr_pr, :temp, :o2sat, :subjective, :objective, :assessment, :plan)
    ");
    $stmt->execute([
        ':clientID'   => $clientID,
        ':historyID'  => $historyID,
        ':bp'         => $bp,
        ':hr_pr'      => $hr_pr,
        ':temp'       => $temp,
        ':o2sat'      => $o2sat,
        ':subjective' => $subjective,
        ':objective'  => $objective,
        ':assessment' => $assessment,
        ':plan'       => $plan
    ]);

    // Insert into consultations table
    $remarks     = "Medical certificate issued on " . date('Y-m-d');

    $stmt2 = $pdo->prepare("
        INSERT INTO consultations (client_id, historyID, consultation_date, certificate_issued, remarks) 
        VALUES (?, ?, CURDATE(), TRUE, ?)
    ");
    $stmt2->execute([$clientID, $historyID, $remarks]);

    // --- Activity Log (using client email) ---
    $action_description = "Added consultation record for Client Email: {$client['Email']}, ClientID: $clientID";
    $logStmt = $pdo->prepare("
        INSERT INTO activity_logs (user_id, username, role, action_type, action_description, status) 
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    $logStmt->execute([
        $user_id,
        $username,
        $user_role,
        'Add Consultation Record',
        $action_description,
        'SUCCESS'
    ]);

    $pdo->commit();

    echo json_encode([
        'success' => true,
        'status' => 'success',
        'message' => 'Consultation record and new history saved successfully.'
    ]);
} catch (Exception $e) {
    $pdo->rollBack();

    // Log the error in activity_logs
    $error_description = "Failed to add consultation record for Client Email: {$client['Email']}, ClientID $clientID. Error: " . $e->getMessage();
    $logStmt = $pdo->prepare("
        INSERT INTO activity_logs (user_id, username, role, action_type, action_description, status) 
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    $logStmt->execute([
        $user_id,
        $username,
        $user_role,
        'Add Consultation Record',
        $error_description,
        'ERROR'
    ]);

    echo json_encode([
        'status' => 'error',
        'message' => 'Error saving data: ' . $e->getMessage()
    ]);
}
