<?php
require 'config/database.php';
$conn = pdo_connect_mysql();

$year = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');


$monthlyCounts = [];

for ($month = 1; $month <= 12; $month++) {
    $stmt = $conn->prepare("
        SELECT COUNT(DISTINCT CONCAT(cr.ClientID, '-', DATE(c.consultation_date))) AS unique_consultations
        FROM consultationrecords cr
        INNER JOIN consultations c ON cr.historyid = c.historyID
        WHERE MONTH(c.consultation_date) = ? 
          AND YEAR(c.consultation_date) = ?
    ");
    $stmt->execute([$month, $year]);
    $monthlyCounts[] = (int)$stmt->fetchColumn();
}


header('Content-Type: application/json');
echo json_encode($monthlyCounts);
