<?php
require_once __DIR__ . '/../config/database.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

ini_set('display_errors', 0);
error_reporting(0);
header('Content-Type: application/json');

if (!isset($_FILES['exam_file']) || $_FILES['exam_file']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['status' => 'error', 'message' => 'No valid file uploaded.']);
    exit;
}

$pdo = pdo_connect_mysql();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['exam_file'])) {
    $client_id = $_POST['client_id'] ?? $_SESSION['ClientID'] ?? null;
    if (!$client_id) {
        echo json_encode(['status' => 'error', 'message' => 'Client ID missing.']);
        exit;
    }

    $file = $_FILES['exam_file'];
    $fileName = basename($file['name']);
    $fileTmp  = $file['tmp_name'];
    $fileSize = $file['size'];
    $fileType = mime_content_type($fileTmp);
    $uploadDate = date('Y-m-d H:i:s');

    $allowedTypes = [
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'image/jpeg',
        'image/png'
    ];

    if (!in_array($fileType, $allowedTypes)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid file type.']);
        exit;
    }

    // ✅ Create upload folder if not exists
    $uploadDir = __DIR__ . '/../uploads/annual_exams/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // ✅ Generate unique name BEFORE building file path
    $uniqueName = uniqid('exam_', true) . '_' . $fileName;
    $filePath = $uploadDir . $uniqueName;

    // ✅ Move uploaded file
    if (move_uploaded_file($fileTmp, $filePath)) {
        $stmt = $pdo->prepare("
            INSERT INTO annual_exams (client_id, file_name, file_path, file_size, file_type, upload_date)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([$client_id, $fileName, $uniqueName, $fileSize, $fileType, $uploadDate]);

        echo json_encode([
            'status' => 'success',
            'file_name' => $fileName,
            'file_size' => round($fileSize / 1024, 2),
            'file_type' => $fileType,
            'upload_date' => $uploadDate
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'File move failed. Check folder path or permissions.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'No file uploaded.']);
}
