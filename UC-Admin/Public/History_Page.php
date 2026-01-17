<?php
require_once 'config/database.php';
require 'manageclients.dbf/view-personalform.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    header('Location: ../../index.php');
    exit;
}


$pdo = pdo_connect_mysql();


if (isset($_GET['client_id']) && isset($_GET['history_id'])) {
    $clientID = $_GET['client_id'];
    $historyID = $_GET['history_id'];
    $date = $_GET['date'] ?? null;

    if (!$date) {
        die('Date is required');
    }

    $stmt = $pdo->prepare("SELECT * FROM clients WHERE ClientID = ?");
    $stmt->execute([$clientID]);
    $client = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$client) {
        echo "Client not found!";
        exit;
    }

    $stmt = $pdo->prepare("SELECT * FROM history WHERE historyID = ?");
    $stmt->execute([$historyID]);
    $history = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$history) {
        echo "Medical history not found!";
        exit;
    }
} else {
    echo "Client or history ID not provided!";
    exit;
}
//=======================================================================
$medicalHistory = null;
$familymedicalHistory = null;
$socialHistoryData = null;
$data = null;

if (isset($_GET['id'])) {
    $clientID = $_GET['id'];

    $stmt = $pdo->prepare("SELECT * FROM clients WHERE ClientID = ?");
    $stmt->execute([$clientID]);
    $clientid = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt2 = $pdo->prepare("SELECT * FROM medicaldentalhistory WHERE ClientID = ?");
    $stmt2->execute([$clientID]);
    $medicalHistory = $stmt2->fetch(PDO::FETCH_ASSOC) ?: [];
}

$expectedKeys = [
    'KnownIllness',
    'KnownIllnessDetails',
    'Hospitalization',
    'HospitalizationDetails',
    'Allergies',
    'AllergiesDetails',
    'ChildImmunization',
    'ChildImmunizationDetails',
    'PresentImmunizations',
    'PresentImmunizationsDetails',
    'CurrentMedicines',
    'CurrentMedicinesDetails',
    'DentalProblems',
    'DentalProblemsDetails',
    'PrimaryPhysician',
    'PrimaryPhysicianDetails'
];

// Ensure all keys exist
foreach ($expectedKeys as $key) {
    if (!isset($medicalHistory[$key])) {
        $medicalHistory[$key] = null;
    }
}
//=============================================
if (isset($_GET['id'])) {
    $clientID = $_GET['id'];

    $stmt = $pdo->prepare("SELECT * FROM clients WHERE ClientID = ?");
    $stmt->execute([$clientID]);
    $clientid = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt2 = $pdo->prepare("SELECT * FROM familymedicalhistory WHERE ClientID = ?");
    $stmt2->execute([$clientID]);
    $familymedicalHistory = $stmt2->fetch(PDO::FETCH_ASSOC) ?: [];
}

$expectedFamilyKeys = [
    'Allergy',
    'AllergyDetails',
    'Asthma',
    'AsthmaDetails',
    'Tuberculosis',
    'TuberculosisDetails',
    'Hypertension',
    'HypertensionDetails',
    'BloodDisease',
    'BloodDiseaseDetails',
    'Stroke',
    'StrokeDetails',
    'Diabetes',
    'DiabetesDetails',
    'Cancer',
    'CancerDetails',
    'LiverDisease',
    'LiverDiseaseDetails',
    'KidneyBladder',
    'KidneyBladderDetails',
    'BloodDisorder',
    'BloodDisorderDetails',
    'Epilepsy',
    'EpilepsyDetails',
    'MentalDisorder',
    'MentalDisorderDetails',
    'OtherIllness',
    'OtherIllnessDetails'
];

foreach ($expectedFamilyKeys as $key) {
    if (!isset($familymedicalHistory[$key])) {
        $familymedicalHistory[$key] = null;
    }
}
//======================================================
if (isset($_GET['id'])) {
    $clientID = $_GET['id'];

    $stmt = $pdo->prepare("SELECT * FROM clients WHERE ClientID = ?");
    $stmt->execute([$clientID]);
    $clientid = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt2 = $pdo->prepare("SELECT * FROM personalsocialhistory WHERE ClientID = ?");
    $stmt2->execute([$clientID]);
    $socialHistoryData = $stmt2->fetch(PDO::FETCH_ASSOC) ?: [];
}
//======================================================
if (isset($_GET['id'])) {
    $clientID = $_GET['id'];

    $stmt = $pdo->prepare("SELECT * FROM clients WHERE ClientID = ?");
    $stmt->execute([$clientID]);
    $clientid = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt2 = $pdo->prepare("SELECT * FROM femalehealthhistory WHERE ClientID = ?");
    $stmt2->execute([$clientID]);
    $data = $stmt2->fetch(PDO::FETCH_ASSOC) ?: [];
}

//======================================================
$stmt = $pdo->prepare('SELECT * FROM medicalcertificate WHERE historyID = ?');
$stmt->execute([$history['historyID']]);
$medicalCertData = $stmt->fetch(PDO::FETCH_ASSOC) ?: '';

if (!$medicalCertData) {
    $medicalCertData = [
        'PatientName'   => '',
        'PatientAge'    => '',
        'Gender'        => '',
        'ExamDate'      => '',
        'Findings'      => '',
        'Impression'    => '',
        'NoteContent'   => '',
        'LicenseNo'     => '',
        'DateIssued'    => ''
    ];
}
//========================================================================
$sql = "SELECT * FROM medicaldentalhistory WHERE historyID = :history_id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['history_id' => $historyID]);
$medicalHistory = $stmt->fetch(PDO::FETCH_ASSOC);

$sql = "SELECT * FROM familymedicalhistory WHERE historyID = :history_id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['history_id' => $historyID]);
$familymedicalHistory = $stmt->fetch(PDO::FETCH_ASSOC);

$sql = "SELECT * FROM personalsocialhistory WHERE historyID = :history_id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['history_id' => $historyID]);
$socialHistoryData = $stmt->fetch(PDO::FETCH_ASSOC);

$sql = "SELECT * FROM femalehealthhistory WHERE historyID = :history_id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['history_id' => $historyID]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

$sql = "SELECT * FROM physicalexamination WHERE historyID = :history_id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['history_id' => $historyID]);
$physicalExam = $stmt->fetch(PDO::FETCH_ASSOC);

$sql = "SELECT * FROM diagnosticresults WHERE historyID = :history_id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['history_id' => $historyID]);
$diagnostic = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
//==========================================================================================

//==========================================================================================
// Fetch consultation record based on historyID and date
$sql = "SELECT * FROM consultationrecords WHERE historyid = :history_id OR DATE(datecreated) = :date ORDER BY consultationID DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    'history_id' => $historyID,
    'date' => $date
]);
$consultationrecords = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];

// Fetch prescription based on historyID and date
$sql = "SELECT * FROM prescriptions 
        WHERE historyID = :history_id OR date_created = :date OR ClientID = :client_id 
        ORDER BY id DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    'history_id' => $historyID,
    'date' => $date,
    'client_id' => $clientID
]);
$prescriptions = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];

//===============================================================================================
$stmt = $pdo->prepare("SELECT Surname, GivenName, MiddleName, Age, CurrentAddress, Gender, Course FROM personalinfo WHERE ClientID = ?");
$stmt->execute([$clientID]);
$personalinfo = $stmt->fetch(PDO::FETCH_ASSOC);

$givenName = $personalinfo['GivenName'] ?? '--.--.--';
$middleName = $personalinfo['MiddleName'] ?? '--.--.--';
$surname = $personalinfo['Surname'] ?? '--.--.--';
$age = $personalinfo['Age'] ?? '';
$gender = $personalinfo['Gender'] ?? '';
$address = $personalinfo['CurrentAddress'] ?? '';
$course = $personalinfo['Course'] ?? '';

$fullName = trim($givenName . $surname);
//================================================================================================
$stmt = $pdo->prepare("SELECT * FROM consultationrecords WHERE ClientID = ?");
$stmt->execute([$clientID]);
$consultationPersonInfo = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];

$stmt = $pdo->prepare("
    SELECT sex 
    FROM prescriptions 
    WHERE ClientID = ? 
    ORDER BY id DESC 
    LIMIT 1
");
$stmt->execute([(int)$clientID]);

$gender = $stmt->fetchColumn() ?: 'Unknown';

?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Layout Example</title>
    <link rel="stylesheet" href="assets/css/manageusers.css">
    <link rel="stylesheet" href="assets/css/profileclients.css">
    <link rel="stylesheet" href="assets/css/adminstyles.css">
    <link rel="stylesheet" href="assets/css/historystyles.css">
    <link rel="stylesheet" href="webicons/fontawesome-free-6.7.2-web/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <script src="assets/js/dashboard_func.js" defer></script>
    <script src="assets/js/clientprofile.js" defer></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap"
        rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <style>
        @font-face {
            font-family: "Montserrat";
            src: url("assets/fonts/Montserrat/Montserrat-VariableFont_wght.ttf") format("woff2");
            font-weight: 400;
            font-style: normal;
        }

        @font-face {
            font-family: "Poppins";
            src: url("assets/fonts/Poppins/Poppins-Medium.ttf") format("woff2");
            font-weight: 400;
            font-style: normal;
        }
    </style>
    <title>Manage Profile</title>
</head>

<body>
    <script>
        const clientGender = "<?= htmlspecialchars($personalInfo['Gender']) ?>".toLowerCase();
        const clientType = "<?= htmlspecialchars($clientid['ClientType']) ?>".toLowerCase();

        function controlSectionVisibility() {
            console.log("Gender:", clientGender);
            console.log("Client Type:", clientType);

            if (clientGender === 'male') {
                const menstrualTab = document.querySelector('[data-target="menstrualHistory"]');
                const menstrualSection = document.getElementById('menstrualHistory');
                if (menstrualTab) menstrualTab.style.display = 'none';
                if (menstrualSection) menstrualSection.style.display = 'none';
            }
            /*
                        if (clientType !== 'faculty') {
                            const physicalTab = document.querySelector('[data-target="physicalExamination"]');
                            const physicalSection = document.getElementById('physicalExamination');
                            if (physicalTab) physicalTab.style.display = 'none';
                            if (physicalSection) physicalSection.style.display = 'none';
                        }*/
        }

        window.addEventListener('DOMContentLoaded', controlSectionVisibility);
    </script>
    <div class="header">
        <img src="assets/images/Lspu logo.png" alt="Logo" type="image/webp" loading="lazy">
        <div class="title">
            <span class="university_title">LSPU-LBC</span>
            <span class="university_title"> University Clinic </span>
        </div>
        <button id="toggle-btn">
            <img id="btnicon" src="assets/images/menu.png">
        </button>
        <!-- <div class="page-title">
            <a href="Manage_Clients.php">Manage Clients</a>
            <i class="fas fa-angle-right"></i>
            <a href="ClientProfile.php?id=<?= urlencode($clientID) ?>">Patient's Profile</a>
            <i class="fas fa-angle-right"></i>
            <h4>Patient's Visit History</h4>
        </div> -->
    </div>

    <div class="main-container">
        <nav class="navbar">
            <a href="Dashboard.php">
                <button class="buttons" id="dashboardBtn">
                    <img src="assets/images/dashboard_icon.svg" class="button-icon-nav" loading="lazy">
                    <span class="nav-text">Dashboard</span>
                </button>
            </a>
            <a href="Manage_Clients.php">
                <button class="buttons" id="manageclientsBtn">
                    <img src="assets/images/manageclients_icon2.svg" class="button-icon-nav" loading="lazy">
                    <span class="nav-text">Manage Patients</span>
                </button>
            </a>
            <a href="Data_Management.php">
                <button class="buttons" id="datamanagementBtn">
                    <img src="assets/images/data_manage_icon.svg" class="button-icon-nav" loading="lazy">
                    <span class="nav-text">Data Management</span>
                </button>
            </a>
            <!--
            <a href="Calendar.html">
                <button class="buttons" id="calendarBtn">
                    <img src="assets/images/calendar_icon.svg" class="button-icon-nav" loading="lazy">
                    <span class="nav-text">Caledar</span>
                </button>
            </a>
    -->
            <a href="admin_logout.php">
                <button class="buttons" id="logoutbtn">
                    <img src="assets/images/logout-icon.svg" class="button-icon-nav" loading="lazy">
                    <span class="nav-text">Logout</span>
                </button>
            </a>
        </nav>

        <main class="content" id="mainContent">

            <!--     <a href="ClientProfile.php?id=<?= urlencode($clientID) ?>" class="button"><i class="fas fa-arrow-left"></i>
                Back to Profile</a> -->
            <div class="modal-overlay" style="display: flex;">
                <div class="modal-content">
                    <div class="med-history-modal-header">
                        <h3>Consultation History</h3>
                        <a href="ClientProfile.php?id=<?= urlencode($clientID) ?>"> <span class="close">&times;</span></a>

                    </div>
                    <div class="nav-div">
                        <div class="tabs">
                            <div class="tab active" data-target="medrec">
                                <img class="cp-btn-img"
                                    src="assets/images/patienthistory2.svg"
                                    data-active="assets/images/patienthistory1.svg"
                                    data-inactive="assets/images/patienthistory2.svg">
                                Consultation Record
                            </div>

                            <div class="tab" data-target="rx">
                                <img class="cp-btn-img"
                                    src="assets/images/patienthistory2.svg"
                                    data-active="assets/images/patienthistory1.svg"
                                    data-inactive="assets/images/patienthistory2.svg">
                                Rx Record
                            </div>

                            <!--  <div class="tab" data-target="medrec">Patient Record</div>-->
                        </div>


                        <!--  <button type="button" id="toggle-form-btn">Show Medical Certificate Form</button>-->
                        <!--====================================================================================================================================-->
                        <div id="medrec" class="medrec_container" style="display: block; border: none">
                            <div class="medrec-subparent-div">
                                <form id="consultationForm" class="medrec-subparent-div">
                                    <input type="hidden" name="client_id" id="client-id" value="<?= htmlspecialchars($clientid['ClientID']) ?>">
                                    <div class="left-info-div">
                                        <div class="phyexam-div">
                                            <h3 style="margin-bottom: 15px;">Patient's Info</h3>
                                            <div class=" info-row">
                                                <span class="info-label">Name:</span>
                                                <input type="text" id="name" contenteditable="true" value="<?= htmlspecialchars($consultationPersonInfo['Name']) ?: ''; ?>" />
                                            </div>

                                            <div class="info-row">
                                                <span class="info-label">Age:</span>
                                                <input type="text" id="age" contenteditable="true" value="<?= htmlspecialchars($consultationPersonInfo['Age']) ?: ''; ?>" />
                                            </div>

                                            <div class="info-row">
                                                <span class="info-label">Address:</span>
                                                <input type="text" id="address" contenteditable="true" value="<?= htmlspecialchars($consultationPersonInfo['Address']) ?: ''; ?>" />
                                            </div>

                                            <div class="info-row">
                                                <span class="info-label">Course:</span>
                                                <input type="text" id="course" contenteditable="true" value="<?= htmlspecialchars($consultationPersonInfo['Course']) ?: ''; ?>" />
                                            </div>

                                            <div class="info-row">
                                                <span class="info-label">Date:</span>
                                                <span id="date"></span>
                                            </div>
                                            <script>
                                                document.addEventListener("DOMContentLoaded", function() {
                                                    const dateSpan = document.getElementById("date");
                                                    const today = new Date();
                                                    const formattedDate = today.toLocaleDateString("en-US", {
                                                        year: 'numeric',
                                                        month: 'long',
                                                        day: 'numeric'
                                                    });
                                                    dateSpan.textContent = formattedDate;
                                                });
                                            </script>
                                        </div>

                                        <div id="phyexam-div-2" class="phyexam-div">
                                            <h3 style="padding: 15px;">Vital Signs</h3>
                                            <div class="info-row">
                                                <span class="info-label">BP:</span>
                                                <input type="text" id="bp_input" name="bp" placeholder="BP" required value="<?= htmlspecialchars($consultationrecords['BP'] ?? '') ?>">
                                            </div>
                                            <div class="info-row">
                                                <span class="info-label">HR/PR:</span>
                                                <input type="text" id="hr_pr" name="hr_pr" placeholder="HR/PR" required value="<?= htmlspecialchars($consultationrecords['HR_PR'] ?? '') ?>">
                                            </div>
                                            <div class="info-row">
                                                <span class="info-label">T°:</span>
                                                <input type="text" id="temp_input" name="temp" placeholder="T°" required value="<?= htmlspecialchars($consultationrecords['Temp'] ?? '') ?>">
                                            </div>
                                            <div class="info-row">
                                                <span class="info-label">O²sat:</span>
                                                <input type="text" id="o2sat" name="o2sat" placeholder="O²sat" required value="<?= htmlspecialchars($consultationrecords['O2sat'] ?? '') ?>">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="right-info-div">
                                        <div class="cert-controls" style="margin-top: 20px;">
                                            <button class="buttonsdp2" type="button" onclick="submitPdfForm()">Download as PDF</button>
                                        </div>


                                        <div id="saveStatus" class="status-smg"></div>

                                        <div class="SOAP-div" style="align-items: left;">
                                            <h3 style="padding: 15px;">Subjective</h3>
                                            <textarea style="font-family: Roboto, sans-serif" id="subjective" name="subjective" rows="1" cols="50" placeholder="Enter notes or paragraph here..." oninput="autoGrow(this)"><?= htmlspecialchars($consultationrecords['Subjective'] ?? '') ?></textarea>

                                            <h3 style="padding: 15px;">Objective</h3>
                                            <textarea style="font-family: Roboto, sans-serif" id="objective" name="objective" rows="1" cols="50" placeholder="Enter notes or paragraph here..." oninput="autoGrow(this)"><?= htmlspecialchars($consultationrecords['Objective'] ?? '') ?></textarea>

                                            <h3 style="padding: 15px;">Assessment</h3>
                                            <textarea style="font-family: Roboto, sans-serif" id="assessment" name="assessment" rows="1" cols="50" placeholder="Enter notes or paragraph here..." oninput="autoGrow(this)"><?= htmlspecialchars($consultationrecords['Assesment'] ?? '') ?></textarea>

                                            <h3 style="padding: 15px;">Plan</h3>
                                            <textarea style="font-family: Roboto, sans-serif" id="plan" name="plan" rows="1" cols="50" placeholder="Enter notes or paragraph here..." oninput="autoGrow(this)"><?= htmlspecialchars($consultationrecords['Plan'] ?? '') ?></textarea>
                                        </div>
                                    </div>

                                    <input type="hidden" name="patient_name" id="hidden-name">
                                    <input type="hidden" name="patient_age" id="hidden-age">
                                    <input type="hidden" name="patient_address" id="hidden-address">
                                    <input type="hidden" name="patient_course" id="hidden-course">
                                    <input type="hidden" name="date" id="hidden-date">


                                </form>
                                <form id="pdfForm" action="manageclients.dbf/patients_rec_genpdf.php" method="post" target="_blank">

                                    <input type="text" id="pdf-name" name="name" hidden>
                                    <input type="text" id="pdf-age" name="age" hidden>
                                    <input type="text" id="pdf-address" name="address" hidden>
                                    <input type="text" id="pdf-course" name="course" hidden>

                                    <input type="text" id="pdf-bp" name="bp" hidden>
                                    <input type="text" id="pdf-hr_pr" name="hr_pr" hidden>
                                    <input type="text" id="pdf-temp" name="temp" hidden>
                                    <input type="text" id="pdf-o2sat" name="o2sat" hidden>

                                    <input type="hidden" id="pdf-date" name="date">

                                    <textarea id="pdf-subjective" name="subjective" hidden></textarea>
                                    <textarea id="pdf-objective" name="objective" hidden></textarea>
                                    <textarea id="pdf-assessment" name="assessment" hidden></textarea>
                                    <textarea id="pdf-plan" name="plan" hidden></textarea>

                                </form>

                            </div>

                        </div>
                        <script>
                            function submitPdfForm() {
                                document.getElementById('pdf-name').value = document.getElementById('name').value;
                                document.getElementById('pdf-age').value = document.getElementById('age').value;
                                document.getElementById('pdf-address').value = document.getElementById('address').value;
                                document.getElementById('pdf-course').value = document.getElementById('course').value;

                                document.getElementById('pdf-bp').value = document.getElementById('bp_input').value;
                                document.getElementById('pdf-hr_pr').value = document.getElementById('hr_pr').value;
                                document.getElementById('pdf-temp').value = document.getElementById('temp_input').value;
                                document.getElementById('pdf-o2sat').value = document.getElementById('o2sat').value;

                                document.getElementById('pdf-subjective').value = document.getElementById('subjective').value;
                                document.getElementById('pdf-objective').value = document.getElementById('objective').value;
                                document.getElementById('pdf-assessment').value = document.getElementById('assessment').value;
                                document.getElementById('pdf-plan').value = document.getElementById('plan').value;

                                const today = new Date();
                                document.getElementById('pdf-date').value = today.toLocaleDateString("en-US", {
                                    year: 'numeric',
                                    month: 'long',
                                    day: 'numeric'
                                });

                                document.getElementById('pdfForm').submit();
                            }
                        </script>


                        <script>
                            function autoGrow(element) {
                                element.style.height = "5px"; // reset height
                                element.style.height = (element.scrollHeight) + "px"; // set new height
                            }
                        </script>



                        <div id="rx" class="medrec_container" style="display: none;">
                            <form method="POST" action="manageclients.dbf/generate_rx_pdf.php" class="medrec-subparent-div" onsubmit="return preparedPdfData(event)" target="_blank">
                                <div class="left-info-div" style="overflow: auto">
                                    <div class="phyexam-div">
                                        <h3 style="padding: 15px;">Patient's Info</h3>

                                        <div class="info-row">
                                            <span class="info-label">Name:</span>
                                            <input type="text" id="pname" value="<?= htmlspecialchars($consultationPersonInfo['Name'] ?? ''); ?>" />
                                        </div>

                                        <div class="info-row">
                                            <span class="info-label">Age:</span>
                                            <input type="text" id="page" value="<?= htmlspecialchars($consultationPersonInfo['Age'] ?? '') ?>">
                                        </div>

                                        <div class="info-row">
                                            <span class="info-label">Sex:</span>
                                            <input type="text" id="gender" value="<?= htmlspecialchars($gender) ?>">
                                        </div>

                                        <div class="info-row">
                                            <span class="info-label">Impression</span>
                                            <input type="text" name="p-impression" id="impression" value="<?= htmlspecialchars($prescriptions['impression'] ?? '') ?>" />
                                        </div>

                                        <div class="info-row">
                                            <span class="info-label">Date:</span>
                                            <span id="date2"><?= htmlspecialchars($prescriptions['date_created'] ?? '') ?></span>
                                        </div>
                                    </div>

                                    <div id="phyexam-div-2" class="phyexam-div">
                                        <div class="info-row">
                                            <span class="info-label">Visiting Physician:</span>
                                            <input type="text" name="physician" placeholder="Visiting Physician" value="<?= htmlspecialchars($prescriptions['physician'] ?? '') ?>" />
                                        </div>
                                        <div class="info-row">
                                            <span class="info-label">Lic.No:</span>
                                            <input type="text" name="LicNo" placeholder="Lic.No." value="<?= htmlspecialchars($prescriptions['license_no'] ?? '') ?>" />
                                        </div>
                                    </div>
                                </div>

                                <div class="right-info-div">
                                    <div class="cert-controls" style="margin-top: 20px;">
                                        <button class="buttonsdp2" type="submit">Download as PDF</button>
                                    </div>

                                    <div class="SOAP-div" style="align-items: left;">
                                        <h3 style="font-family: 'DejaVu Sans'; font-size: 28pt;">℞</h3>
                                        <textarea id="notes" name="notes" rows="20" cols="50" placeholder="Enter notes"><?= htmlspecialchars($prescriptions['notes'] ?? '') ?></textarea>
                                    </div>
                                </div>

                                <!-- Hidden inputs -->
                                <input type="hidden" name="p-patient_name" id="input_patient_name">
                                <input type="hidden" name="p-patient_age" id="input_patient_age">
                                <input type="hidden" name="patient_sex" id="input_patient_sex">
                                <input type="hidden" name="input_date" id="input_date" value="<?= htmlspecialchars($prescriptions['date_created'] ?? '') ?>">
                                <input type="hidden" name="input_physician" id="input_physician" value="<?= htmlspecialchars($prescriptions['physician'] ?? '') ?>">
                                <input type="hidden" name="input_LicNo" id="input_LicNo" value="<?= htmlspecialchars($prescriptions['license_no'] ?? '') ?>">
                            </form>

                            <script>
                                function preparedPdfData(event) {
                                    // Copy visible inputs into hidden inputs
                                    document.getElementById('input_patient_name').value = document.getElementById('pname').value.trim();
                                    document.getElementById('input_patient_age').value = document.getElementById('page').value.trim();
                                    document.getElementById('input_patient_sex').value = document.getElementById('gender').value.trim();
                                    document.getElementById('input_date').value = document.getElementById('date2').textContent.trim();
                                    document.getElementById('input_physician').value = document.querySelector('input[name="physician"]').value.trim();
                                    document.getElementById('input_LicNo').value = document.querySelector('input[name="LicNo"]').value.trim();

                                    // Let the form submit normally after values are set
                                    return true;
                                }
                            </script>
                        </div>

                    </div>
                </div>
            </div>
        </main>
    </div>
</body>

</html