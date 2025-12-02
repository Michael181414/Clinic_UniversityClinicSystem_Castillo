<?php
require '../config/database.php';
session_start();

$pdo = pdo_connect_mysql();

$client_id     = $_POST['client_id'];
$patient_name  = $_POST['patient_name'];
$patient_age   = $_POST['patient_age'];
$exam_date     = $_POST['exam_date'];
$findings      = $_POST['findings'];
$impression    = $_POST['impression'];
$note_content  = $_POST['note'];
$license_no    = $_POST['license_no'];
$date_issued   = $_POST['date_issued'];

// Validate exam_date
if (!strtotime($exam_date)) {
    die("Invalid date format for exam date");
}
$exam_date = date('Y-m-d', strtotime($exam_date));

// Get client email for logging
$checkClient = $pdo->prepare("SELECT Email FROM clients WHERE ClientID = ?");
$checkClient->execute([$client_id]);
$client = $checkClient->fetch(PDO::FETCH_ASSOC);
$client_email = $client['Email'] ?? 'Unknown';

try {
    $pdo->beginTransaction();

    // Get historyID
    $getHistory = $pdo->prepare("SELECT historyID FROM history WHERE ClientID = ? AND progress = 'inprogress' ORDER BY historyID DESC LIMIT 1");
    $getHistory->execute([$client_id]);
    $historyID = $getHistory->fetchColumn();

    if (!$historyID) {
        $insertHistory = $pdo->prepare("INSERT INTO history (ClientID, actionDate, progress) VALUES (?, NOW(), 'completed')");
        $insertHistory->execute([$client_id]);
        $historyID = $pdo->lastInsertId();
    }

    // Insert into medicalcertificate
    $stmt = $pdo->prepare('INSERT INTO medicalcertificate 
        (ClientID, historyID, PatientName, PatientAge, ExamDate, Findings, Impression, NoteContent, LicenseNo, DateIssued) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
    $stmt->execute([$client_id, $historyID, $patient_name, $patient_age, $exam_date, $findings, $impression, $note_content, $license_no, $date_issued]);

    // Insert into consultations
    $remarks = "Medical certificate issued on $date_issued";
    $stmt2 = $pdo->prepare("INSERT INTO consultations (client_id, historyID, consultation_date, certificate_issued, remarks) VALUES (?, ?, CURDATE(), TRUE, ?)");
    $stmt2->execute([$client_id, $historyID, $remarks]);

    // --- Activity Log ---
    $user_id   = $_SESSION['user_id'] ?? null;
    $username  = $_SESSION['username'] ?? 'System';
    $user_role = $_SESSION['user_type'] ?? 'Unknown';

    $action_description = "Issued medical certificate for Client Email: $client_email, ClientID: $client_id";

    $logStmt = $pdo->prepare("
        INSERT INTO activity_logs (user_id, username, role, action_type, action_description, status)
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    $logStmt->execute([
        $user_id,
        $username,
        $user_role,
        'Issue Medical Certificate',
        $action_description,
        'SUCCESS'
    ]);

    $pdo->commit();

    header('Location: ClientProfile.php?status=success');
    exit;
} catch (Exception $e) {
    $pdo->rollBack();

    // Log failure
    $error_description = "Failed to issue medical certificate for Client Email: $client_email, ClientID: $client_id. Error: " . $e->getMessage();
    $logStmt = $pdo->prepare("
        INSERT INTO activity_logs (user_id, username, role, action_type, action_description, status)
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    $logStmt->execute([
        $user_id,
        $username,
        $user_role,
        'Issue Medical Certificate',
        $error_description,
        'ERROR'
    ]);

    header('Location: ClientProfile.php?status=error&message=' . urlencode($e->getMessage()));
    exit;
}
