<?php
session_start();
require_once '../config/database.php';

header('Content-Type: application/json; charset=utf-8');
error_reporting(0);

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method.');
    }

    $clientId = filter_input(INPUT_POST, 'ClientID', FILTER_VALIDATE_INT);
    if (!$clientId && isset($_SESSION['ClientID'])) {
        $clientId = (int)$_SESSION['ClientID'];
    }

    if (!$clientId) {
        throw new Exception('Client ID is missing or invalid.');
    }

    $pdo = pdo_connect_mysql();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if client exists
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM clients WHERE ClientID = ?");
    $stmt->execute([$clientId]);
    if (!(bool)$stmt->fetchColumn()) {
        throw new Exception('Invalid Client ID: client does not exist.');
    }

    // Collect form data
    $d = [
        'Surname' => filter_input(INPUT_POST, 'Surname', FILTER_SANITIZE_SPECIAL_CHARS),
        'GivenName' => filter_input(INPUT_POST, 'GivenName', FILTER_SANITIZE_SPECIAL_CHARS),
        'MiddleName' => filter_input(INPUT_POST, 'MiddleName', FILTER_SANITIZE_SPECIAL_CHARS),
        'Age' => filter_input(INPUT_POST, 'Age', FILTER_VALIDATE_INT),
        'Gender' => filter_input(INPUT_POST, 'Gender', FILTER_SANITIZE_SPECIAL_CHARS),
        'DateOfBirth' => $_POST['DateOfBirth'] ?? null,
        'Status' => filter_input(INPUT_POST, 'Status', FILTER_SANITIZE_SPECIAL_CHARS),
        'Course' => filter_input(INPUT_POST, 'Course', FILTER_SANITIZE_SPECIAL_CHARS),
        'SchoolYearEntered' => filter_input(INPUT_POST, 'SchoolYearEntered', FILTER_SANITIZE_SPECIAL_CHARS),
        'CurrentAddress' => filter_input(INPUT_POST, 'CurrentAddress', FILTER_SANITIZE_SPECIAL_CHARS),
        'ContactNumber' => filter_input(INPUT_POST, 'ContactNumber', FILTER_SANITIZE_SPECIAL_CHARS),
        'MothersName' => filter_input(INPUT_POST, 'MothersName', FILTER_SANITIZE_SPECIAL_CHARS),
        'FathersName' => filter_input(INPUT_POST, 'FathersName', FILTER_SANITIZE_SPECIAL_CHARS),
        'GuardiansName' => filter_input(INPUT_POST, 'GuardiansName', FILTER_SANITIZE_SPECIAL_CHARS),
        'EmergencyContactName' => filter_input(INPUT_POST, 'EmergencyContactName', FILTER_SANITIZE_SPECIAL_CHARS),
        'EmergencyContactRelationship' => filter_input(INPUT_POST, 'EmergencyContactRelationship', FILTER_SANITIZE_SPECIAL_CHARS),
        'EmergencyContactPerson' => filter_input(INPUT_POST, 'EmergencyContactPerson', FILTER_SANITIZE_SPECIAL_CHARS),
    ];

    if (empty($d['Surname']) || empty($d['GivenName']) || $d['Age'] === false) {
        throw new Exception('Please fill all required fields correctly.');
    }

    // Check if personal info exists
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM personalinfo WHERE ClientID = ?");
    $stmt->execute([$clientId]);
    $exists = (bool)$stmt->fetchColumn();

    if ($exists) {
        // Update
        $sql = "UPDATE personalinfo SET
                    Surname=:Surname, GivenName=:GivenName, MiddleName=:MiddleName, Age=:Age, Gender=:Gender,
                    DateOfBirth=:DateOfBirth, Status=:Status, Course=:Course, SchoolYearEntered=:SchoolYearEntered,
                    CurrentAddress=:CurrentAddress, ContactNumber=:ContactNumber, MothersName=:MothersName,
                    FathersName=:FathersName, GuardiansName=:GuardiansName,
                    EmergencyContactName=:EmergencyContactName, EmergencyContactRelationship=:EmergencyContactRelationship,
                    EmergencyContactPerson=:EmergencyContactPerson
                WHERE ClientID=:ClientID";
        $stmt = $pdo->prepare($sql);
        $ok = $stmt->execute(array_merge($d, ['ClientID' => $clientId]));
        $action = 'Updated Personal Info';
    } else {
        // Insert
        $sql = "INSERT INTO personalinfo (
                    ClientID, Surname, GivenName, MiddleName, Age, Gender,
                    DateOfBirth, Status, Course, SchoolYearEntered,
                    CurrentAddress, ContactNumber,
                    MothersName, FathersName, GuardiansName,
                    EmergencyContactName, EmergencyContactRelationship, EmergencyContactPerson
                ) VALUES (
                    :ClientID, :Surname, :GivenName, :MiddleName, :Age, :Gender,
                    :DateOfBirth, :Status, :Course, :SchoolYearEntered,
                    :CurrentAddress, :ContactNumber,
                    :MothersName, :FathersName, :GuardiansName,
                    :EmergencyContactName, :EmergencyContactRelationship, :EmergencyContactPerson
                )";
        $stmt = $pdo->prepare($sql);
        $ok = $stmt->execute(array_merge($d, ['ClientID' => $clientId]));
        $action = 'Inserted Personal Info';
    }

    // Log admin action
    $admin_id = $_SESSION['user_id'] ?? null;
    $admin_username = $_SESSION['username'] ?? 'System';
    $admin_role = $_SESSION['user_type'] ?? 'Unknown';
    $status = $ok ? 'SUCCESS' : 'ERROR';
    $description = $ok ? "Admin {$action} for ClientID {$clientId}" : "Failed to {$action} for ClientID {$clientId}";
    $logStmt = $pdo->prepare("INSERT INTO activity_logs (user_id, username, role, action_type, action_description, status) VALUES (?, ?, ?, ?, ?, ?)");
    $logStmt->execute([$admin_id, $admin_username, $admin_role, $action, $description, $status]);

    if ($ok) {
        echo json_encode(['success' => true, 'message' => "Personal info saved successfully"]);
    } else {
        throw new Exception("Failed to save personal info");
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
exit();
