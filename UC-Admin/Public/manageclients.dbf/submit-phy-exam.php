<?php
session_start();
include 'config/database.php';

$pdo = pdo_connect_mysql();
header('Content-Type: application/json');

if (!isset($_POST['client_id']) || empty($_POST['client_id'])) {
    echo json_encode(["status" => "error", "message" => "Error: No client selected"]);
    exit();
}

$fields = [
    'height',
    'weight',
    'bmi',
    'bp',
    'hr',
    'rr',
    'temp',
    'skin_normal',
    'skin_findings',
    'head_normal',
    'head_findings',
    'chest_normal',
    'chest_findings',
    'abdomen_normal',
    'abdomen_findings',
    'extremities_normal',
    'extremities_findings',
    'others_normal',
    'others_findings'
];

$data = ['client_id' => $_POST['client_id']];
foreach ($fields as $field) {
    $data[$field] = $_POST[$field] ?? '';
}

// Get client email for logging
$clientEmail = 'Unknown';
$checkClient = $pdo->prepare("SELECT Email FROM clients WHERE ClientID = ?");
$checkClient->execute([$data['client_id']]);
$client = $checkClient->fetch(PDO::FETCH_ASSOC);
if ($client) $clientEmail = $client['Email'];

try {
    // Get latest history for the client
    $getHistory = $pdo->prepare("
        SELECT historyID 
        FROM history 
        WHERE ClientID = ? 
        ORDER BY historyID DESC 
        LIMIT 1
    ");
    $getHistory->execute([$data['client_id']]);
    $historyID = $getHistory->fetchColumn();

    // If no history exists → create new one
    if (!$historyID) {
        $insertHistory = $pdo->prepare("INSERT INTO history (ClientID, actionDate) VALUES (?, NOW())");
        $insertHistory->execute([$data['client_id']]);
        $historyID = $pdo->lastInsertId();
    }

    // Check if physical examination record exists
    $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM physicalexamination WHERE historyID = ?");
    $checkStmt->execute([$historyID]);
    $exists = $checkStmt->fetchColumn();

    if ($exists) {
        // UPDATE existing record
        $sql = "UPDATE physicalexamination SET
            Height = ?, Weight = ?, BMI = ?, BP = ?, HR = ?, RR = ?, Temp = ?,
            GenAppearanceAndSkinNormal = ?, GenAppearanceAndSkinFindings = ?,
            HeadAndNeckNormal = ?, HeadAndNeckFindings = ?,
            ChestAndBackNormal = ?, ChestAndBackFindings = ?,
            AbdomenNormal = ?, AbdomenFindings = ?,
            ExtremitiesNormal = ?, ExtremitiesFindings = ?,
            OthersNormal = ?, OthersFindings = ?
            WHERE historyID = ?";
        $params = [
            $data['height'],
            $data['weight'],
            $data['bmi'],
            $data['bp'],
            $data['hr'],
            $data['rr'],
            $data['temp'],
            $data['skin_normal'],
            $data['skin_findings'],
            $data['head_normal'],
            $data['head_findings'],
            $data['chest_normal'],
            $data['chest_findings'],
            $data['abdomen_normal'],
            $data['abdomen_findings'],
            $data['extremities_normal'],
            $data['extremities_findings'],
            $data['others_normal'],
            $data['others_findings'],
            $historyID
        ];
        $actionType = "Update Physical Examination";
        $actionMessage = "Updated physical examination for Client Email: $clientEmail, ClientID: {$data['client_id']}";
    } else {
        // INSERT new record
        $sql = "INSERT INTO physicalexamination (
            ClientID, historyID, Height, Weight, BMI, BP, HR, RR, Temp,
            GenAppearanceAndSkinNormal, GenAppearanceAndSkinFindings,
            HeadAndNeckNormal, HeadAndNeckFindings,
            ChestAndBackNormal, ChestAndBackFindings,
            AbdomenNormal, AbdomenFindings,
            ExtremitiesNormal, ExtremitiesFindings,
            OthersNormal, OthersFindings
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $params = [
            $data['client_id'],
            $historyID,
            $data['height'],
            $data['weight'],
            $data['bmi'],
            $data['bp'],
            $data['hr'],
            $data['rr'],
            $data['temp'],
            $data['skin_normal'],
            $data['skin_findings'],
            $data['head_normal'],
            $data['head_findings'],
            $data['chest_normal'],
            $data['chest_findings'],
            $data['abdomen_normal'],
            $data['abdomen_findings'],
            $data['extremities_normal'],
            $data['extremities_findings'],
            $data['others_normal'],
            $data['others_findings']
        ];
        $actionType = "New Physical Examination";
        $actionMessage = "Inserted new physical examination for Client Email: $clientEmail, ClientID: {$data['client_id']}";
    }

    $stmt = $pdo->prepare($sql);
    if ($stmt->execute($params)) {
        // --- Activity Log Success ---
        $logStmt = $pdo->prepare("
            INSERT INTO activity_logs (user_id, username, role, action_type, action_description, status)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $logStmt->execute([
            $_SESSION['user_id'] ?? null,
            $_SESSION['username'] ?? 'System',
            $_SESSION['user_type'] ?? 'Unknown',
            $actionType,
            $actionMessage,
            'SUCCESS'
        ]);

        echo json_encode(["status" => "success", "message" => "Physical examination saved successfully!"]);
    } else {
        throw new Exception("Failed to save physical examination");
    }
} catch (Exception $e) {
    // --- Activity Log Error ---
    $logStmt = $pdo->prepare("
        INSERT INTO activity_logs (user_id, username, role, action_type, action_description, status)
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    $logStmt->execute([
        $_SESSION['user_id'] ?? null,
        $_SESSION['username'] ?? 'System',
        $_SESSION['user_type'] ?? 'Unknown',
        "Physical Examination Error",
        "Failed to save physical examination for Client Email: $clientEmail, ClientID: {$data['client_id']}. Error: " . $e->getMessage(),
        'ERROR'
    ]);

    echo json_encode(["status" => "error", "message" => "Database error: " . $e->getMessage()]);
}
