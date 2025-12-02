<?php
require_once __DIR__ . '/../config/database.php';
session_start(); // Needed for admin/session info
header('Content-Type: application/json');

// Debug: Log received POST data
file_put_contents('form_debug.log', print_r($_POST, true), FILE_APPEND);

// Check if client_id is provided
if (empty($_POST['client_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Client ID is required',
        'missing_fields' => ['client_id'],
        'received_data' => $_POST
    ]);
    exit;
}

// --- Helper: Insert medical form ---
function insertMedicalForm($formData)
{
    $pdo = pdo_connect_mysql();

    try {
        $stmt = $pdo->prepare("
            INSERT INTO newpersonnel_form (
                client_id, blood_test, urinalysis, chest_xray, drug_test, psych_test, neuro_test,
                full_name, agency_address, address, age, sex, civil_status, proposed_position,
                height, weight, blood_type,
                physician_signature, physician_agency, OtherInfo, physician_license, physician_designation,
                created_at
            ) VALUES (
                :client_id, :blood_test, :urinalysis, :chest_xray, :drug_test, :psych_test, :neuro_test,
                :full_name, :agency_address, :address, :age, :sex, :civil_status, :proposed_position,
                :height, :weight, :blood_type,
                :physician_signature, :physician_agency, :otherinfo, :physician_license, :official_designation,
                NOW()
            )
        ");

        // Convert checkbox input
        $cb = function ($key) use ($formData) {
            return isset($formData[$key]) && ($formData[$key] === '1' || $formData[$key] === 'on') ? 1 : 0;
        };

        $stmt->execute([
            ':client_id' => $formData['client_id'],
            ':blood_test' => $cb('blood_test'),
            ':urinalysis' => $cb('urinalysis'),
            ':chest_xray' => $cb('chest_xray'),
            ':drug_test' => $cb('drug_test'),
            ':psych_test' => $cb('psych_test'),
            ':neuro_test' => $cb('neuro_test'),
            ':full_name' => $formData['name'] ?? '',
            ':agency_address' => $formData['agency'] ?? '',
            ':address' => $formData['address'] ?? '',
            ':age' => $formData['age'] ?? 0,
            ':sex' => $formData['sex'] ?? '',
            ':civil_status' => $formData['civil-status'] ?? '',
            ':proposed_position' => $formData['position'] ?? '',
            ':height' => $formData['height'] ?? '',
            ':weight' => $formData['weight'] ?? '',
            ':blood_type' => $formData['blood-type'] ?? '',
            ':physician_signature' => $formData['physician_signature'] ?? '',
            ':physician_agency' => $formData['physician_agency'] ?? '',
            ':otherinfo' => $formData['otherinfo'] ?? '',
            ':physician_license' => $formData['license_no'] ?? '',
            ':official_designation' => $formData['official_designation'] ?? ''
        ]);

        return $pdo->lastInsertId();
    } catch (PDOException $e) {
        throw $e;
    }
}

// --- Handle POST request ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $required = [
        'client_id',
        'name',
        'agency',
        'address',
        'age',
        'sex',
        'civil-status',
        'position',
        'height',
        'weight',
        'blood-type'
    ];
    $missing = [];

    foreach ($required as $field) {
        if (empty($_POST[$field])) {
            $missing[] = $field;
        }
    }

    if (!empty($missing)) {
        echo json_encode([
            'success' => false,
            'message' => 'Missing required fields: ' . implode(', ', $missing),
            'missing_fields' => $missing
        ]);
        exit;
    }

    try {
        $insertId = insertMedicalForm($_POST);

        // --- Activity log ---
        $pdo = pdo_connect_mysql();

        // Get client email
        $client_id = $_POST['client_id'];
        $checkClient = $pdo->prepare("SELECT Email FROM clients WHERE ClientID = ?");
        $checkClient->execute([$client_id]);
        $client = $checkClient->fetch(PDO::FETCH_ASSOC);
        $client_email = $client['Email'] ?? 'Unknown';

        // Get session info
        $user_id   = $_SESSION['user_id'] ?? null;
        $username  = $_SESSION['username'] ?? 'System';
        $user_role = $_SESSION['user_type'] ?? 'Unknown';

        $action_type = "New Personnel Form Submission";
        $action_description = "Submitted new personnel form for Client Email: $client_email, ClientID: $client_id";

        $logStmt = $pdo->prepare("
            INSERT INTO activity_logs (user_id, username, role, action_type, action_description, status)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $logStmt->execute([
            $user_id,
            $username,
            $user_role,
            $action_type,
            $action_description,
            'SUCCESS'
        ]);

        echo json_encode([
            'success' => true,
            'message' => 'Medical form submitted successfully',
            'insert_id' => $insertId
        ]);
    } catch (PDOException $e) {
        // Log failure
        $pdo = pdo_connect_mysql();
        $user_id   = $_SESSION['user_id'] ?? null;
        $username  = $_SESSION['username'] ?? 'System';
        $user_role = $_SESSION['user_type'] ?? 'Unknown';
        $client_id = $_POST['client_id'] ?? 'Unknown';
        $checkClient = $pdo->prepare("SELECT Email FROM clients WHERE ClientID = ?");
        $checkClient->execute([$client_id]);
        $client = $checkClient->fetch(PDO::FETCH_ASSOC);
        $client_email = $client['Email'] ?? 'Unknown';

        $error_description = "Failed to submit new personnel form for Client Email: $client_email, ClientID: $client_id. Error: " . $e->getMessage();

        $logStmt = $pdo->prepare("
            INSERT INTO activity_logs (user_id, username, role, action_type, action_description, status)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $logStmt->execute([
            $user_id,
            $username,
            $user_role,
            "New Personnel Form Submission",
            $error_description,
            'ERROR'
        ]);

        echo json_encode([
            'success' => false,
            'message' => 'Database error: ' . $e->getMessage()
        ]);
        exit;
    }
}
