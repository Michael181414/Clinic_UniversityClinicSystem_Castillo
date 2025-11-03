<?php
ob_start();
require_once '../../../public/vendor/autoload.php'; // Adjust this path if needed

// --- Utility function to sanitize input ---
function clean($text)
{
    return htmlspecialchars(trim($text ?? ''), ENT_QUOTES, 'UTF-8');
}

// --- Retrieve POST values safely ---
$name        = clean($_POST['name'] ?? '');
$age         = clean($_POST['age'] ?? '');
$address     = clean($_POST['address'] ?? '');
$course      = clean($_POST['course'] ?? '');
$date        = clean($_POST['date'] ?? '');
$bp          = clean($_POST['bp'] ?? '');
$hr          = clean($_POST['hr_pr'] ?? '');
$temp        = clean($_POST['temp'] ?? '');
$o2sat       = clean($_POST['o2sat'] ?? '');
$subjective  = clean($_POST['subjective'] ?? '');
$objective   = clean($_POST['objective'] ?? '');
$assessment  = clean($_POST['assessment'] ?? '');
$plan        = clean($_POST['plan'] ?? '');

try {
    // --- Custom TCPDF class for footer ---
    class MYPDF extends TCPDF
    {
        public function Footer()
        {
            $this->SetY(-20);
            $this->SetFont('helvetica', '', 8);
            $html = '
                <table width="100%" style="font-size: 8pt;">
                    <tr>
                        <td align="left">LSPU-OSAS-SF-M08</td>
                        <td align="center">Rev. 0</td>
                        <td align="right">10 Aug. 2016</td>
                    </tr>
                </table>';
            $this->writeHTMLCell(0, 0, '', '', $html, 0, 0, 0, true, 'L', true);
        }
    }

    // --- Initialize TCPDF ---
    $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    $pdf->SetCreator('LSPU Medical Clinic');
    $pdf->SetAuthor('LSPU Medical Clinic');
    $pdf->SetTitle('Patient Record - ' . ($name ?: 'Unnamed'));

    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(true);
    $pdf->SetMargins(12, 12, 12);
    $pdf->SetAutoPageBreak(true, 20);
    $pdf->AddPage();
    $pdf->SetFont('helvetica', '', 11);

    // --- Clinic Header Image ---
    $imagePath = realpath(__DIR__ . '/../assets/images/Lspu-Header.jpg');
    $imageHtml = '';
    if ($imagePath && file_exists($imagePath)) {
        $imageData = base64_encode(file_get_contents($imagePath));
        $imageHtml = '<img src="data:image/jpeg;base64,' . $imageData . '" height="70">';
    }

    // --- Preserve formatting for textarea inputs (lists, paragraphs, line breaks) ---
    function formatList($text)
    {
        $lines = explode("\n", $text);
        $formatted = '';
        $inList = false;
        foreach ($lines as $line) {
            if (preg_match('/^\s*(?:[-*•])\s+(.*)/', $line, $m)) {
                if (!$inList) {
                    $inList = true;
                    $formatted .= '<ul style="margin:0; padding-left:18px;">';
                }
                $formatted .= '<li>' . htmlspecialchars($m[1]) . '</li>';
            } else {
                if ($inList) {
                    $inList = false;
                    $formatted .= '</ul>';
                }
                $formatted .= '<p style="margin:0;">' . htmlspecialchars(trim($line)) . '</p>';
            }
        }
        if ($inList) $formatted .= '</ul>';
        return $formatted;
    }

    // Convert newlines and list markers to readable HTML
    $subjective = formatList($subjective);
    $objective  = formatList($objective);
    $assessment = formatList($assessment);
    $plan       = formatList($plan);


    // --- PDF HTML Content ---
    $html = <<<EOD
<style>
    .header { text-align: center; font-weight: bold; font-size: 12pt; }
    .subheader { text-align: center; font-size: 12pt; margin-bottom: 20px; }
    .section-title { font-weight: bold; font-size: 12pt; text-decoration: underline; margin-top: 15px; margin-bottom: 8px; }
    .field-label { font-weight: bold; display: inline-block; width: 90px; }
    .underline { display: inline-block; padding: 2px 5px; min-width: 60px; border-bottom: 1px solid #000; }
    .soap-container { font-size: 10.5pt; line-height: 1.6; }
    .soap-section { margin-bottom: 12px; padding: 6px;}
    .soap-title { font-weight: bold; margin-bottom: 4px; font-size: 11.5pt; }
    .soap-content { text-align: justify; white-space: pre-line; }
</style>

<table width="90%" align="center">
    <tr><td align="center">$imageHtml</td></tr>
</table>

<div class="header">MEDICAL CLINIC</div>
<div class="subheader">Consultation Record</div>

<table width="100%">
    <tr>
        <td width="50%" valign="top">
            <div><span class="field-label">Name:</span> $name</div>
            <div><span class="field-label">Age:</span> $age</div>
            <div><span class="field-label">Address:</span> $address</div>
            <div><span class="field-label">Course:</span> $course</div>
            <div><span class="field-label">Date:</span> $date</div>
        </td>
        <td width="50%" valign="top">
            <div class="section-title">Vital Signs</div>
            <div><span class="field-label">BP:</span> <span class="underline">$bp</span></div>
            <div><span class="field-label">HR/PR:</span> <span class="underline">$hr</span></div>
            <div><span class="field-label">Temp:</span> <span class="underline">$temp</span></div>
            <div><span class="field-label">O<sub>2</sub> Sat:</span> <span class="underline">$o2sat</span></div>
        </td>
    </tr>
</table>

<div class="soap-container">
    <div class="soap-section">
        <div class="soap-title">Subjective:</div>
        <div class="soap-content">$subjective</div>
    </div>
    <div class="soap-section">
        <div class="soap-title">Objective:</div>
        <div class="soap-content">$objective</div>
    </div>
    <div class="soap-section">
        <div class="soap-title">Assessment:</div>
        <div class="soap-content">$assessment</div>
    </div>
    <div class="soap-section">
        <div class="soap-title">Plan:</div>
        <div class="soap-content">$plan</div>
    </div>
</div>
EOD;

    // --- Generate the PDF ---
    $pdf->writeHTML($html, true, false, true, false, '');
    $pdf->Output('Patient_Record.pdf', 'I');
    ob_end_flush();
} catch (Exception $e) {
    echo 'Error generating PDF: ' . $e->getMessage();
}
