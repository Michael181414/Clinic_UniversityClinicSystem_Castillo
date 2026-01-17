<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require '../config/database.php';

header('Content-Type: application/json');

try {
    $pdo = pdo_connect_mysql();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // =========================
    // COLLECT POST DATA
    // =========================
    $clientID   = $_POST['client_id'] ?? null;
    $name       = $_POST['name'] ?? '';
    $age        = $_POST['age'] ?? null;
    $address    = $_POST['address'] ?? '';
    $course     = $_POST['course'] ?? '';

    $bp         = $_POST['bp'] ?? '';
    $hr_pr      = $_POST['hr_pr'] ?? '';
    $temp       = $_POST['temp'] ?? '';
    $o2sat      = $_POST['o2sat'] ?? '';
    $subjective = $_POST['subjective'] ?? '';
    $objective  = $_POST['objective'] ?? '';
    $assessment = $_POST['assessment'] ?? '';
    $plan       = $_POST['plan'] ?? '';

    // =========================
    // BASIC VALIDATION
    // =========================
    if (!$clientID) {
        echo json_encode(['status' => 'error', 'message' => 'Missing ClientID']);
        exit;
    }

    // =========================
    // CHECK CLIENT EXISTS
    // =========================
    $checkClient = $pdo->prepare("SELECT Email FROM clients WHERE ClientID = ?");
    $checkClient->execute([$clientID]);
    $client = $checkClient->fetch(PDO::FETCH_ASSOC);

    if (!$client) {
        echo json_encode(['status' => 'error', 'message' => 'Client does not exist']);
        exit;
    }

    // =========================
    // USER SESSION INFO
    // =========================
    $user_id   = $_SESSION['user_id'] ?? null;
    $username  = $_SESSION['username'] ?? 'System';
    $user_role = $_SESSION['user_type'] ?? 'Unknown';

    date_default_timezone_set('Asia/Manila');

    // =========================
    // START TRANSACTION
    // =========================
    $pdo->beginTransaction();

    // =========================
    // INSERT HISTORY
    // =========================
    $insertHistory = $pdo->prepare("
        INSERT INTO history (ClientID, actionDate, actionTime)
        VALUES (?, ?, ?)
    ");
    $insertHistory->execute([
        $clientID,
        date('Y-m-d'),
        date('h:i:s A')
    ]);

    $historyID = $pdo->lastInsertId();

    // =========================
    // INSERT CONSULTATION RECORD
    // (INCLUDING Name, Age, Address, Course)
    // =========================
    $insertConsultation = $pdo->prepare("
        INSERT INTO consultationrecords
        (
            ClientID,
            Name,
            Age,
            Address,
            Course,
            historyid,
            BP,
            HR_PR,
            Temp,
            O2sat,
            Subjective,
            Objective,
            Assesment,
            Plan
        )
        VALUES
        (
            :clientID,
            :name,
            :age,
            :address,
            :course,
            :historyID,
            :bp,
            :hr_pr,
            :temp,
            :o2sat,
            :subjective,
            :objective,
            :assesment,
            :plan
        )
    ");

    $insertConsultation->execute([
        ':clientID'   => $clientID,
        ':name'       => $name,
        ':age'        => $age,
        ':address'    => $address,
        ':course'     => $course,
        ':historyID'  => $historyID,
        ':bp'         => $bp,
        ':hr_pr'      => $hr_pr,
        ':temp'       => $temp,
        ':o2sat'      => $o2sat,
        ':subjective' => $subjective,
        ':objective'  => $objective,
        ':assesment'  => $assessment,
        ':plan'       => $plan
    ]);

    // =========================
    // INSERT INTO CONSULTATIONS TABLE (IF USED)
    // =========================
    $remarks = "Medical certificate issued on " . date('Y-m-d');

    $insertConsultations = $pdo->prepare("
        INSERT INTO consultations
        (client_id, historyID, consultation_date, certificate_issued, remarks)
        VALUES (?, ?, CURDATE(), TRUE, ?)
    ");
    $insertConsultations->execute([
        $clientID,
        $historyID,
        $remarks
    ]);

    // =========================
    // ACTIVITY LOG
    // =========================
    $action_description = "Added consultation record for ClientID: $clientID";

    $insertLog = $pdo->prepare("
        INSERT INTO activity_logs
        (user_id, username, role, action_type, action_description, status)
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    $insertLog->execute([
        $user_id,
        $username,
        $user_role,
        'Add Consultation Record',
        $action_description,
        'SUCCESS'
    ]);

    // =========================
    // COMMIT
    // =========================
    $pdo->commit();

    echo json_encode([
        'status' => 'success',
        'message' => 'Consultation record saved successfully'
    ]);
} catch (Exception $e) {

    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }

    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
