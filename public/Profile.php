<?php
require_once '../config/database.php';

require_once '../Profile/Profile_db.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


$clientType = $_POST['clientType'] ?? '';
$pdo = pdo_connect_mysql();
$user_data = getUserDataFromDatabase($pdo);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Layout Example</title>
    <link rel="stylesheet" href="UC-Client/assets/css/ClientStyles.css">
    <link rel="stylesheet" href="UC-Client/assets/css/MediaQuery.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="webicons/fontawesome-free-6.7.2-web/css/all.min.css">
    <script src="UC-Client/assets/js/script.js" defer></script>
    <script src="UC-Client/assets/js/profile.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

    <title>Manage Profile</title>
</head>
</head>

<body>

    <div class="header">
        <img src="UC-Client/assets/images/Lspu logo.png" alt="Logo" type="image/webp" loading="lazy">
        <div class="title">
            <span>University</span>
            <span>Clinic</span>
        </div>
        <button id="toggle-btn">
            <img id="btnicon" src="UC-Client/assets/images/menu-icon.svg">
        </button>
    </div>
    <div class="main-container">
        <nav class="navbar">
            <a href="Profile.php">
                <button class="buttons" id="profileBtn">
                    <img src="UC-Client/assets/images/Usericon2.svg" class="button-icon-nav" loading="lazy">
                    <span class="nav-text">Manage Profile</span>
                </button>
            </a>
            <a href="Profile.php">
                <button class="buttons" id="profileBtn">
                    <img src="UC-Client/assets/images/Usericon2.svg" class="button-icon-nav" loading="lazy">
                    <span class="nav-text">Manage Profile</span>
                </button>
            </a>
            <a href="Profile.php">
                <button class="buttons" id="profileBtn">
                    <img src="UC-Client/assets/images/Usericon2.svg" class="button-icon-nav" loading="lazy">
                    <span class="nav-text">Manage Profile</span>
                </button>
            </a>

            <?php if (
                strtolower($clientType) === 'freshman' || strtolower($clientType) === 'students'
            ) : ?>
                <a href="Medical_Form.php">
                    <button class="buttons" id="medicalBtn">
                        <img src="UC-Client/assets/images/Form-icon.svg" class="button-icon-nav" loading="lazy">
                        <span class="nav-text">Medical Form</span>
                    </button>
                </a>
            <?php endif; ?>
            <form action="logout.php" method="post">
                <button type="submit" class="buttons" id="logoutbtn">
                    <img src="UC-Client/assets/images/logout-icon.svg" class="button-icon-nav" loading="lazy">
                    <span class="nav-text">Logout</span>
                </button>
            </form>
        </nav>
        <script>

        </script>
        <main class="content" loading="lazy">
            <div class="Content2-Div" style="display: none;">

                <div id="profile">
                    <!-- Modal -->
                    <!--This modal will only shown if the clienttype is freshman -->
                    <?php if (strtolower($clientType) === 'freshman'): ?>
                        <div id="exam-modal" class="modal" style="display: none;">
                            <div class="modal-content">
                                <span class="close-btn" onclick="closeExamModal()">&times;</span>
                                <h2 class="modal-title">Physical Examination Instructions</h2>

                                <div class="steps-container">
                                    <div class="step">
                                        <div class="step-number">1</div>
                                        <div class="step-content">
                                            <h3>Fill Out the Medical Form</h3>
                                            <p>Go to the Medical Form page and complete the required fields: Personal Information and Medical History.</p>
                                        </div>
                                    </div>

                                    <div class="step">
                                        <div class="step-number">2</div>
                                        <div class="step-content">
                                            <h3>Submit all labolatory results</h3>
                                            <p>cbc, Urinalysis, X-ray, and Drugtest. Submit to Clinic Staff.</p>
                                        </div>
                                    </div>

                                    <div class="step">
                                        <div class="step-number">3</div>
                                        <div class="step-content">
                                            <h3>Take the Physical Examination</h3>
                                            <p>Proceed to the physical examination as instructed by the medical personnel.</p>
                                        </div>
                                    </div>

                                    <div class="step">
                                        <div class="step-number">4</div>
                                        <div class="step-content">
                                            <h3>Recieve Medical Certificate</h3>
                                            <p>After being assest by the university physician.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div id="profile-div-2">

                        <p id="Name" class="ptext"><?= $fullName ?></p>
                        <p id="Email" class="ptext"><?= $email ?></p>

                        <?php if (isset($_GET['upload'])): ?>
                            <?php if ($_GET['upload'] == 'success'): ?>
                                <div class="alert-success"></div>
                            <?php elseif ($_GET['upload'] == 'fail'): ?>
                                <div class="alert-fail"></div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                    <div id="left-profile-sec-freshman">
                        <div id="status-info">
                            <div id="left-text" class="divtext">
                                <p class="info-label">
                                    <i class="fa-solid fa-id-badge"></i> Client ID:
                                    <span class="info-value"><?= htmlspecialchars($clientId) ?></span>
                                </p>
                                <p class="info-label">
                                    <i class="fa-solid fa-building-user"></i> Department:
                                    <span class="info-value"><?= htmlspecialchars($department) ?></span>
                                </p>
                                <?php if (strtolower($clientType) === 'freshman'): ?>
                                    <p class="info-label">
                                        <i class="fa-solid fa-book"></i> Course:
                                        <span class="info-value"><?= htmlspecialchars($course) ?></span>
                                    </p>
                                <?php endif; ?>
                                <p class="info-label">
                                    <i class="fa-solid fa-user-tag"></i> Client Type:
                                    <span class="info-value"><?= htmlspecialchars($clientType) ?></span>
                                </p>
                            </div>

                        </div>
                        <!--<button class="page-buttons" id="view-docs-btn">View Documents</button>-->
                        <?php if ($progressStatus !== 'completed'): ?>
                            <button class="page-buttons" onclick="showExamInstructions()">
                                <i class="fas fa-notes-medical" style="margin-right: 8px;"></i>
                                View Instructions
                            </button>
                        <?php endif; ?>
                        <?php if ($progressStatus === 'completed'): ?>
                            <div class="exam-complete">
                                <i class="fas fa-check-circle"></i>
                                <span class="exam-text">Physical Examination Completed</span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div id="freshman-medcert" style="display: <?= ($progressStatus === 'completed') ? 'flex' : 'none' ?>;">

                    <div id="medical-certificate-form">
                        <div class="medcertheader">
                            <img src="UC-Client/assets/images/Lspu logo.png" alt="LSPU Logo">
                            <div class="headertextdiv">
                                <div>Republic of the Philippines</div>
                                <div>Laguna State Polytechnic University</div>
                                <div>Province of Laguna</div>
                            </div>
                        </div>

                        <div class="medcertitle">MEDICAL CERTIFICATE</div>

                        <div class="medcertcontent">
                            <div class="form-field">
                                This is to certify that
                                <span class="underline">
                                    <?= htmlspecialchars($medicalCertData['PatientName'] ?? '') ?>
                                </span>,
                                a
                                <span class="underline">
                                    <?= htmlspecialchars($medicalCertData['PatientAge'] ?? '') ?>
                                </span>
                                year old F/M,
                                has been seen and examined on
                                <span class="underline">
                                    <?= htmlspecialchars($medicalCertData['ExamDate'] ?? '') ?>
                                </span>
                                at the Medical Clinic.
                            </div>

                            <div class="form-field">
                                Pertinent findings:
                                <span class="underline">
                                    <?= htmlspecialchars($medicalCertData['Findings'] ?? '') ?>
                                </span>
                            </div>

                            <div class="form-field">
                                Impression on examination:
                                <span class="underline">
                                    <?= htmlspecialchars($medicalCertData['Impression'] ?? '') ?>
                                </span>
                            </div>

                            <div class="form-field">
                                NOTE:
                                <span class="underline">
                                    <?= htmlspecialchars($medicalCertData['NoteContent'] ?? '') ?>
                                </span>
                            </div>

                            <div class="signature-section">
                                Visiting Physician/University Nurse<br>
                                License No.
                                <span class="underline">
                                    <?= htmlspecialchars($medicalCertData['LicenseNo'] ?? '') ?>
                                </span><br>
                                Date Issued:
                                <span class="underline">
                                    <?= htmlspecialchars($medicalCertData['DateIssued'] ?? '') ?>
                                </span>
                            </div>

                            <div class="form-number">
                                LSPU-OSAS-SF-M08 | Rev. 0 | 10 Aug. 2016
                            </div>

                            <div class="cert-controls">
                                <?php if (!$isDownloaded): ?>
                                    <a href="generate_pdf_client.php?historyID=<?= urlencode($historyID) ?>" class="btn btn-success btn-sm" onclick="return confirmDownload();">
                                        <i class="fa-solid fa-download"></i> Download PDF
                                    </a>
                                <?php endif; ?>

                                <script>
                                    function confirmDownload() {
                                        return confirm("This certificate can only be downloaded once. Do you want to proceed?");
                                    }
                                </script>

                            </div>
                        </div>
                    </div>
                </div>
                <!---->
                <?php if (strtolower($clientType) === 'freshman'): ?>
                    <div class="progress-container" style="display: <?= ($progressStatus === 'completed') ? 'none' : 'flex' ?>;">
                        <h2 class="progress-title">Your Progress</h2>
                        <div class="progress-visual">
                            <div class="vertical-steps-container">
                                <?php
                                $gender = strtolower($user_data['gender'] ?? '--.--.--');

                                $steps = [
                                    'Personal Information' => 'personalinfo',
                                    'Medical & Dental History' => 'medicaldentalhistory',
                                    'Family Medical History' => 'familymedicalhistory',
                                    'Personal Social History' => 'personalsocialhistory',
                                    'Female Health History' => 'femalehealthhistory',
                                    'Physical Examination' => 'physicalexamination',
                                    'Diagnostic Results' => 'diagnosticresults',
                                    'Medical Certificate' => 'medicalcertificate'
                                ];

                                $completed = [];
                                $stepIndex = 0;
                                $currentStepIndex = -1;

                                foreach ($steps as $label => $table) {
                                    if ($table === 'femalehealthhistory' && $gender !== 'female') {
                                        continue;
                                    }

                                    // Check if 'historyID' exists in the table
                                    $checkColumn = $pdo->prepare("SHOW COLUMNS FROM `$table` LIKE 'historyID'");
                                    $checkColumn->execute();
                                    $hasHistoryID = $checkColumn->fetch() !== false;

                                    if ($hasHistoryID) {
                                        $stmt = $pdo->prepare("SELECT COUNT(*) FROM `$table` WHERE ClientID = ?");
                                        $stmt->execute([$clientId]);
                                    } else {
                                        $stmt = $pdo->prepare("SELECT COUNT(*) FROM `$table` WHERE ClientID = ?");
                                        $stmt->execute([$clientId]);
                                    }

                                    $count = $stmt->fetchColumn();

                                    $completed[$label] = ($count > 0);
                                    if (!$count && $currentStepIndex == -1) {
                                        $currentStepIndex = $stepIndex;
                                    }
                                    $stepIndex++;
                                }

                                $stepNumber = 1;
                                $stepIndex = 0;
                                foreach ($completed as $label => $is_done) {
                                    $status_class = $is_done ? 'done' : ($stepIndex === $currentStepIndex ? 'current' : 'pending');
                                    echo "<div class='vertical-step $status_class'>";
                                    echo "<span class='step-icon'>";
                                    if ($status_class === 'done') {
                                        echo "<i class='fas fa-circle-check'></i>";
                                    } elseif ($status_class === 'current') {
                                        echo "<i class='fas fa-hourglass-half'></i>";
                                    } else {
                                        echo "<i class='fas fa-clock'></i>";
                                    }

                                    echo "</span>";
                                    $displayLabel = ($label === 'Medical Certificate') ? "$label (Optional)" : $label;
                                    echo "<div class='step-text'><div class='step-numbers'>STEP $stepNumber</div><div class='step-label'>$displayLabel</div></div>";

                                    echo "</div>";
                                    $stepNumber++;
                                    $stepIndex++;
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <?php if (($clientType) === 'NewPersonnel'): ?>
                    <div class="form-container">
                        <form id="medicalForm" method="post">
                            <input type="hidden" name="client_id" value="<?= htmlspecialchars($clientID ?? '') ?>">
                            <input type="hidden" id="print_action" name="print_action" value="">

                            <div style="display: flex; width: 100%; justify-content: right; align-items: center;">
                                <button style="display: flex; width: 10%;" type="button" class="page-buttons " onclick="printMedicalForm()">Print</button>
                            </div>
                            <div class="form-header">
                                <h1>CS Form No. 211</h1>
                                <h2>Revised 2018</h2>
                                <h1>MEDICAL CERTIFICATE</h1>
                                <h2>(For Employment)</h2>
                            </div>

                            <div class="section">
                                <div class="section-title">INSTRUCTIONS</div>
                                <p>a. This medical certificate should be accomplished by a licensed government physician.</p>
                                <p>b. Attach this certificate to original appointment, transfer and reemployment.</p>
                                <p>c. The results of the following pre-employment medical/physical must be attached to this form:</p>

                                <div class="checkbox-group">
                                    <input type="checkbox" id="blood-test" name="blood_test" value="1" <?= !empty($blood_test) ? 'checked' : '' ?>>
                                    <label for="blood-test">Blood Test</label>

                                    <input type="checkbox" id="urinalysis" name="urinalysis" value="1" <?= !empty($urinalysis) ? 'checked' : '' ?>>
                                    <label for="urinalysis">Urinalysis</label>

                                    <input type="checkbox" id="xray" name="chest_xray" value="1" <?= !empty($chest_xray) ? 'checked' : '' ?>>
                                    <label for="xray">Chest X-Ray</label>

                                    <input type="checkbox" id="drug-test" name="drug_test" value="1" <?= !empty($drug_test) ? 'checked' : '' ?>>
                                    <label for="drug-test">Drug Test</label>

                                    <input type="checkbox" id="psych-test" name="psych_test" value="1" <?= !empty($psych_test) ? 'checked' : '' ?>>
                                    <label for="psych-test">Psychological Test</label>

                                    <input type="checkbox" id="neuro-test" name="neuro_test" value="1" <?= !empty($neuro_test) ? 'checked' : '' ?>>
                                    <label for="neuro-test">Neuro-Psychiatric Examination</label>
                                </div>
                            </div>

                            <div class="section">
                                <div class="section-title">FOR THE PROPOSED APPOINTEE</div>

                                <div class="form-group">
                                    <label for="name">NAME</label>
                                    <input type="text" id="name" name="name" value="<?= htmlspecialchars($name ?? '') ?>" required>
                                </div>

                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="agency">AGENCY / ADDRESS</label>
                                        <input type="text" id="agency" name="agency" value="<?= htmlspecialchars($agency ?? '') ?>" required>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="address">ADDRESS</label>
                                        <input type="text" id="address" name="address" value="<?= htmlspecialchars($address ?? '') ?>" required>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="age">AGE</label>
                                        <input type="number" id="age" name="age" value="<?= htmlspecialchars($age ?? '') ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="sex">SEX</label>
                                        <select id="sex" name="sex" required>
                                            <option value="">Select</option>
                                            <option value="Male" <?= ($sex ?? '') === 'Male' ? 'selected' : '' ?>>Male</option>
                                            <option value="Female" <?= ($sex ?? '') === 'Female' ? 'selected' : '' ?>>Female</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="civil-status">CIVIL STATUS</label>
                                        <select id="civil-status" name="civil-status" required>
                                            <option value="">Select</option>
                                            <option value="Single" <?= ($civil_status ?? '') === 'Single' ? 'selected' : '' ?>>Single</option>
                                            <option value="Married" <?= ($civil_status ?? '') === 'Married' ? 'selected' : '' ?>>Married</option>
                                            <option value="Divorced" <?= ($civil_status ?? '') === 'Divorced' ? 'selected' : '' ?>>Divorced</option>
                                            <option value="Widowed" <?= ($civil_status ?? '') === 'Widowed' ? 'selected' : '' ?>>Widowed</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="position">PROPOSED POSITION</label>
                                        <input type="text" id="position" name="position" value="<?= htmlspecialchars($position ?? '') ?>" required>
                                    </div>
                                </div>
                            </div>

                            <div class="section">
                                <div class="section-title">FOR THE LICENSED GOVERNMENT PHYSICIAN</div>

                                <div class="form-row">
                                    <div class="form-group">
                                        <p>I hereby certify that I have reviewed and evaluated the attached examination results, personally examined the above named individual and found him/her to be physically and medically □FIT / □UNFIT for employment.</p>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="physician_signature">SIGNATURE over PRINTED NAME:</label>
                                        <input type="text" id="physician_signature" name="physician_signature" value="<?= htmlspecialchars($physician_signature ?? '') ?>" readonly>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="physician_agency">AGENCY/Affiliation:</label>
                                        <input type="text" id="physician_agency" name="physician_agency" value="<?= htmlspecialchars($physician_agency ?? '') ?>" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label for="otherinfo">Other Information About The Proposed Appointee:</label>
                                        <input type="text" id="otherinfo" name="otherinfo" value="<?= htmlspecialchars($other_info ?? '') ?>" readonly>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="license_no">LICENSE NO.</label>
                                        <input type="text" id="license_no" name="license_no" value="<?= htmlspecialchars($license_no ?? '') ?>" readonly>
                                    </div>

                                    <div class="form-group">
                                        <label for="height">HEIGHT (M)</label>
                                        <input type="text" id="height" name="height" value="<?= htmlspecialchars($height ?? '') ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="weight">WEIGHT (KG)</label>
                                        <input type="text" id="weight" name="weight" value="<?= htmlspecialchars($weight ?? '') ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="blood-type">BLOOD TYPE</label>
                                        <input type="text" id="blood-type" name="blood-type" value="<?= htmlspecialchars($blood_type ?? '') ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="official_designation">OFFICIAL DESIGNATION</label>
                                        <input type="text" id="official_designation" name="official_designation" value="<?= htmlspecialchars($official_designation ?? '') ?>" readonly>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="date_created">Date:</label>
                                        <input type="date" id="date_created" name="date_created" value="<?= htmlspecialchars($date_created ?? date('Y-m-d')) ?>" required>
                                    </div>
                                </div>
                            </div>

                            <div class="form-footer" style="margin-top: 20px; display: flex; justify-content: left; align-items: left; width: 100%;">
                                <button class="buttons" type="submit" id="submitBtn"
                                    style="max-width: 110px;padding: 20px 30px; background-color: #1583EB; border:none; border-radius: 10px;">Submit</button>
                            </div>
                        </form>
                        <script>
                            function printMedicalForm() {
                                document.getElementById('print_action').value = '1'; // set to "1" to trigger printing
                                document.getElementById('medicalForm').action = 'generate_np_medform.php'; // send to PHP file
                                document.getElementById('medicalForm').submit();
                            }
                        </script>
                    </div>

                    <script>
                        document.getElementById('medicalForm').addEventListener('submit', async function(e) {
                            e.preventDefault();

                            const form = e.target;
                            const formData = new FormData(form);

                            // Disable submit button to prevent multiple submissions
                            const submitBtn = document.getElementById('submitBtn');
                            submitBtn.disabled = true;
                            submitBtn.textContent = 'Submitting...';

                            try {
                                const response = await fetch('submit_np_form.php', {
                                    method: 'POST',
                                    body: formData
                                });
                                const result = await response.json();

                                if (result.success) {
                                    alert(result.message);
                                    form.reset();
                                } else {
                                    alert('Error: ' + (result.message || 'Unknown error'));
                                    if (result.missing_fields) {
                                        console.warn('Missing fields:', result.missing_fields);
                                    }
                                }
                            } catch (error) {
                                alert('Failed to submit form. Please try again.');
                                console.error(error);
                            } finally {
                                submitBtn.disabled = false;
                                submitBtn.textContent = 'Submit';
                            }
                        });
                    </script>

                    <style>
                        :root {
                            --primary-color: #3498db;
                            --secondary-color: #2980b9;
                            --accent-color: #e74c3c;
                            --light-gray: #f8f9fa;
                            --medium-gray: #e9ecef;
                            --dark-gray: #6c757d;
                            --text-color: #212529;
                            --border-radius: 8px;
                            --box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                        }

                        .form-container {
                            display: flex;
                            flex-direction: column;
                            justify-content: flex-start;
                            width: 100%;
                            height: 60%;
                            overflow: auto;
                            max-height: 600px;
                            background-color: white;
                            padding: 30px;
                            border-radius: 5px;
                            gap: 15px;
                        }

                        .form-header {
                            text-align: center;
                            margin-bottom: 40px;
                            padding-bottom: 20px;
                            border-bottom: 2px solid var(--medium-gray);
                        }

                        .form-header h1 {
                            font-size: 24px;
                            margin: 0;
                            font-weight: 600;
                            color: var(--primary-color);
                        }

                        .form-header h2 {
                            font-size: 18px;
                            margin: 10px 0 0 0;
                            font-weight: 500;
                            color: var(--dark-gray);
                        }

                        .section {
                            margin-bottom: 30px;
                            padding: 25px;
                            background-color: var(--light-gray);
                            border-radius: var(--border-radius);
                        }

                        .section-title {
                            font-weight: 600;
                            margin-bottom: 20px;
                            color: var(--primary-color);
                            font-size: 18px;
                            padding-bottom: 8px;
                            border-bottom: 2px solid var(--medium-gray);
                        }

                        .form-row {
                            display: flex;
                            flex-wrap: wrap;
                            gap: 20px;
                            margin-bottom: 20px;
                        }

                        .form-group {
                            flex: 1;
                            min-width: 200px;
                        }

                        label {
                            display: block;
                            margin-bottom: 8px;
                            font-weight: 500;
                            color: var(--dark-gray);
                        }

                        input[type="text"],
                        input[type="number"],
                        select {
                            width: 100%;
                            padding: 12px;
                            border: 1px solid var(--medium-gray);
                            border-radius: var(--border-radius);
                            background-color: white;
                            transition: border-color 0.3s, box-shadow 0.3s;
                        }

                        input[type="text"]:focus,
                        input[type="number"]:focus,
                        select:focus {
                            outline: none;
                            border-color: var(--primary-color);
                            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
                        }

                        .checkbox-group {
                            display: grid;
                            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
                            gap: 15px;
                            margin-bottom: 20px;
                        }

                        .checkbox-item {
                            display: flex;
                            align-items: center;
                            gap: 10px;
                        }

                        .checkbox-item input[type="checkbox"] {
                            width: 18px;
                            height: 18px;
                            accent-color: var(--primary-color);
                        }

                        .checkbox-item label {
                            margin: 0;
                            font-weight: 400;
                        }

                        .display-text {
                            padding: 12px;
                            background-color: var(--medium-gray);
                            border: 1px solid var(--medium-gray);
                            border-radius: var(--border-radius);
                            min-height: 20px;
                            color: var(--dark-gray);
                        }

                        .signature-section {
                            margin-top: 40px;
                            padding-top: 20px;
                            border-top: 2px solid var(--medium-gray);
                        }

                        .signature-line {
                            width: 300px;
                            margin: 40px auto 0;
                            text-align: center;
                        }

                        .signature-line::before {
                            content: "";
                            display: block;
                            width: 100%;
                            height: 1px;
                            background-color: var(--text-color);
                            margin-bottom: 5px;
                        }

                        .text-center {
                            text-align: center;
                        }

                        .text-muted {
                            color: var(--dark-gray);
                            font-size: 0.9em;
                        }

                        @media (max-width: 768px) {
                            .form-container {
                                padding: 20px;
                            }

                            .section {
                                padding: 15px;
                            }

                            .form-row {
                                flex-direction: column;
                                gap: 15px;
                            }

                            .form-group {
                                min-width: 100%;
                            }
                        }
                    </style>
                <?php endif; ?>
            </div>
            <!--This part is the form of the NewPersonnel type of client.
                            ========================================================================================
                            ========================================================================================-->



            <!--=======================================================================================================-->
            <div class="Content1-Div" style="display: none;" loading="lazy">
                <div id="left-content" loading="lazy">
                    <div id="profile">
                        <div id="profile-div">
                            <form id="profile-pic-form" method="POST" enctype="multipart/form-data" action="Profile.php">
                                <div class="profile-pic-div">
                                    <div class="profile-pic-wrapper">
                                        <img id="profile-pic" src="<?= $profilePic ?>" alt="Profile Picture"
                                            onerror="this.onerror=null;this.src='../uploads/profilepic2.png'">
                                    </div>
                                    <input type="file" id="image-upload" name="image" accept="image/*"
                                        onchange="previewImage();" style="display: none;" required>

                                </div>
                                <div class="profile-actions">

                                    <!--<button type="button" class="page-buttons" onclick="document.getElementById('image-upload').click()">
                                    Upload Profile Picture
                                </button>-->

                                    <button type="submit" name="submit" class="page-buttons">Save Profile Picture</button>
                                </div>
                            </form>

                            <p id="Name" class="ptext"><?= $fullName ?></p>
                            <p id="Email" class="ptext"><?= $email ?></p>

                            <?php if (isset($_GET['upload'])): ?>
                                <?php if ($_GET['upload'] == 'success'): ?>
                                    <div class="alert-success"></div>
                                <?php elseif ($_GET['upload'] == 'fail'): ?>
                                    <div class="alert-fail"></div>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                        <div id="left-profile-sec">
                            <div id="status-info">
                                <div id="left-text" class="divtext">
                                    <p class="info-label">Client ID: <span class="info-value"><?php echo htmlspecialchars($clientId); ?></span></p>
                                    <p class="info-label">Department: <span class="info-value"><?= htmlspecialchars($department) ?></span></p>
                                    <?php if (strtolower($clientType) === 'freshman' || strtolower($clientType) === 'students') : ?>
                                        <p class="info-label">Course: <span class="info-value"><?= htmlspecialchars($course) ?></span></p>

                                    <?php endif; ?>

                                    <p class="info-label">Client Type: <span class="info-value"><?= htmlspecialchars($clientType) ?></span></p>
                                </div>

                            </div>
                            <!--<button class="page-buttons" id="view-docs-btn">View Documents</button>-->
                        </div>
                    </div>

                    <style>
                        .upload-container {
                            display: flex;
                            flex-direction: column;
                            justify-content: center;
                            align-items: center;
                            padding: 30px;
                            width: 100%;
                        }

                        .upload-box {
                            display: flex;
                            flex-direction: column;
                            align-items: center;
                            justify-content: center;
                            border: 2px dashed #4FA8F2;
                            border-radius: 12px;
                            background: #f9fbff;
                            padding: 40px 20px;
                            cursor: pointer;
                            transition: all 0.3s ease;
                            width: 100%;
                            max-width: 400px;
                            text-align: center;
                        }

                        .upload-box:hover {
                            border-color: #3372D3;
                            background: #f0f6ff;
                            box-shadow: 0 5px 12px rgba(51, 114, 211, 0.2);
                        }

                        .upload-icon {
                            font-size: 48px;
                            color: #4FA8F2;
                            margin-bottom: 15px;
                            transition: color 0.3s ease;
                        }

                        .upload-box:hover .upload-icon {
                            color: #3372D3;
                        }

                        .upload-text {
                            font-size: 16px;
                            font-weight: 600;
                            color: #333;
                            margin: 0;
                        }

                        .upload-hint {
                            font-size: 13px;
                            color: #666;
                            margin-top: 8px;
                        }

                        .upload-input {
                            display: none;
                        }

                        .drop-file-div {
                            display: flex;
                            flex-direction: row;
                            justify-content: center;
                            width: 100%;
                            height: 15px;
                            padding: 30px;
                            font-family: 'Roboto', sans-serif;
                            font-size: clamp(13px, 1vw, 16px);
                            color: #3372D3;
                        }

                        .alert {
                            margin-top: 15px;
                            padding: 12px 16px;
                            border-radius: 8px;
                            font-size: 14px;
                            font-weight: 500;
                            display: flex;
                            align-items: center;
                            gap: 8px;
                            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
                        }

                        .alert.success {
                            background: #e6f7f1;
                            color: #1e7e34;
                            border: 1px solid #1e7e34;
                        }

                        .alert.error {
                            background: #fdecea;
                            color: #b02a37;
                            border: 1px solid #b02a37;
                        }

                        .file-list {
                            list-style: none;
                            padding: 0;
                            margin: 15px 0;
                            width: 100%;
                            max-width: 500px;
                        }

                        .file-list li {
                            display: flex;
                            align-items: center;
                            background: #f8f9fa;
                            padding: 10px 15px;
                            margin-bottom: 8px;
                            border-radius: 10px;
                            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.08);
                            transition: background 0.2s;
                        }

                        .file-list li:hover {
                            background: #eef2f7;
                        }

                        .file-list i {
                            font-size: 20px;
                            margin-right: 12px;
                        }

                        .file-list a {
                            flex: 1;
                            text-decoration: none;
                            color: #333;
                            font-weight: 500;
                            overflow-wrap: anywhere;
                        }

                        .file-list button {
                            background: none;
                            border: none;
                            cursor: pointer;
                            font-size: 16px;
                            margin-left: 10px;
                            color: #dc3545;
                            transition: color 0.2s;
                        }

                        .file-list button:hover {
                            color: #a71d2a;
                        }
                    </style>

                    <?php if (!empty($message)): ?>
                        <script>
                            alert("<?= htmlspecialchars($message) ?>");
                        </script>
                    <?php endif; ?>
                    <div id="info-div">
                        <?php if (strtolower($clientType) === "faculty" || strtolower($clientType) === "personnel"): ?>
                            <div class="drop-file-div">
                                <h4>Upload your document for the annual examination</h4>
                            </div>

                            <div class="upload-container">
                                <ul id="fileList" class="file-list"></ul>
                                <form id="uploadForm" enctype="multipart/form-data">
                                    <!-- Hidden user_id from PHP session -->
                                    <input type="hidden" name="user_id" value="<?= $clientID ?>">

                                    <label for="doc-upload" class="upload-box">
                                        <i class="fas fa-file-upload upload-icon"></i>
                                        <p class="upload-text">Click to upload or drag & drop your document here</p>
                                        <span class="upload-hint">Accepted formats: .doc, .docx, .pdf</span>
                                    </label>

                                    <input type="file" id="doc-upload" name="document" class="upload-input" accept=".doc,.docx,.pdf" required>

                                    <div style="display:flex;justify-content:center;align-items:center;margin-top:20px;">
                                        <button type="submit" class="submit-btn">
                                            <i class="fas fa-paper-plane"></i> Submit
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <!-- Uploaded file list -->



                            <script>
                                // Function to get icon based on extension
                                function getFileIcon(filename) {
                                    let ext = filename.split('.').pop().toLowerCase();
                                    switch (ext) {
                                        case "pdf":
                                            return '<i class="fas fa-file-pdf" style="color:#e74c3c;"></i>';
                                        case "doc":
                                        case "docx":
                                            return '<i class="fas fa-file-word" style="color:#2a74d8;"></i>';
                                        default:
                                            return '<i class="fas fa-file-alt" style="color:#6c757d;"></i>';
                                    }
                                }

                                // Handle form submission (upload)
                                document.getElementById("uploadForm").addEventListener("submit", function(e) {
                                    e.preventDefault();

                                    let formData = new FormData(this);

                                    fetch("upload_document.php", {
                                            method: "POST",
                                            body: formData
                                        })
                                        .then(response => response.json())
                                        .then(data => {
                                            console.log("Server response:", data); // Debug

                                            if (data.success) {
                                                let fileList = document.getElementById("fileList");
                                                fileList.innerHTML = ""; // clear old if only one allowed

                                                let li = document.createElement("li");
                                                li.innerHTML = `
                ${getFileIcon(data.originalName)} 
                <a href="uploads/${data.filename}" target="_blank">${data.originalName}</a>
                <button onclick="removeFile('${data.filename}', this)">
                    <i class="fas fa-trash"></i>
                </button>
            `;
                                                fileList.appendChild(li);

                                                document.getElementById("uploadForm").reset();
                                            } else {
                                                alert("❌ Upload failed: " + data.message);
                                            }
                                        })
                                        .catch(error => {
                                            alert("⚠️ Error uploading file (network or PHP crash).");
                                            console.error(error);
                                        });
                                });

                                // Remove file function
                                function removeFile(filename, btn) {
                                    fetch("remove_document.php", {
                                            method: "POST",
                                            headers: {
                                                "Content-Type": "application/x-www-form-urlencoded"
                                            },
                                            body: "filename=" + encodeURIComponent(filename)
                                        })
                                        .then(response => response.json())
                                        .then(data => {
                                            if (data.success) {
                                                btn.parentElement.remove();
                                            } else {
                                                alert("❌ Failed to remove: " + data.message);
                                            }
                                        })
                                        .catch(error => {
                                            alert("⚠️ Error removing file.");
                                            console.error(error);
                                        });
                                }
                            </script>


                        <?php endif; ?>


                        <?php if (strtolower($clientType) === "student" || strtolower($clientType) === "freshman"): ?>
                            <script>
                                function switchTab(evt, tabId) {
                                    // Hide all tab contents
                                    const tabContents = document.querySelectorAll(".tab-content");
                                    tabContents.forEach(content => {
                                        content.style.display = "none";
                                    });

                                    // Remove "active" class from all tab buttons
                                    const tabButtons = document.querySelectorAll(".tabs .tab");
                                    tabButtons.forEach(btn => {
                                        btn.classList.remove("active");
                                    });

                                    // Show the selected tab content
                                    const selectedTab = document.getElementById(tabId);
                                    if (selectedTab) {
                                        selectedTab.style.display = "block";
                                    }

                                    // Add "active" class to the clicked button
                                    evt.currentTarget.classList.add("active");
                                }
                            </script>

                            <div class="tabs">
                                <button class="tab active" onclick="switchTab(event, 'personal-info')"><img id="person-info-icon" class="cp-btn-img" src="UC-Client/assets/images/id-card.png">Personal
                                    Information</button>
                                <button class="tab" onclick="switchTab(event, 'medical-history')"><img id="person-info-icon" class="cp-btn-img" src="UC-Client/assets/images/diagnosis.png">Medical History</button>
                                <button class="tab" onclick="switchTab(event, 'medical-certificate')"><img id="person-info-icon" class="cp-btn-img" src="UC-Client/assets/images/medcert.png">Medical
                                    Certificate</button>
                            </div>
                            <style>
                                .tab {
                                    display: flex;
                                    flex-direction: row;
                                    justify-content: center;
                                    align-items: center;
                                    gap: 10px;
                                    font-size: clamp(9px, 1vw, 14px);
                                }

                                .cp-btn-img {
                                    height: 25px;
                                    width: 25px;
                                }

                                #person-info-icon {
                                    height: 25px;
                                    width: 25px;
                                }

                                @media (max-width: 768px) {
                                    .tab {
                                        display: flex;
                                        flex-direction: column;
                                    }
                                }
                            </style>

                            <div id="personal-info" class="tab-content active">
                                <div class="info-grid">
                                    <p><span class="person-info-label">Surname:</span><br>
                                        <span class="person-value-label"><?= htmlspecialchars($user_data['surname'] ?? '--.--.--') ?></span>
                                    </p>

                                    <p><span class="person-info-label">Given Name:</span><br>
                                        <span class="person-value-label"><?= htmlspecialchars($user_data['given_name'] ?? '--.--.--') ?></span>
                                    </p>

                                    <p><span class="person-info-label">Middle Name:</span><br>
                                        <span class="person-value-label"><?= htmlspecialchars($user_data['middle_name'] ?? '--.--.--') ?></span>
                                    </p>

                                    <p><span class="person-info-label">Age:</span><br>
                                        <span class="person-value-label"><?= htmlspecialchars($user_data['age'] ?? '--.--.--') ?></span>
                                    </p>

                                    <p><span class="person-info-label">Sex:</span><br>
                                        <span class="person-value-label"><?= htmlspecialchars($user_data['gender'] ?? '--.--.--') ?></span>
                                    </p>

                                    <p><span class="person-info-label">Birthday:</span><br>
                                        <span class="person-value-label"><?= htmlspecialchars($user_data['dob'] ?? '--.--.--') ?></span>
                                    </p>

                                    <p><span class="person-info-label">Status:</span><br>
                                        <span class="person-value-label"><?= htmlspecialchars($user_data['status'] ?? '--.--.--') ?></span>
                                    </p>

                                    <p><span class="person-info-label">Course:</span><br>
                                        <span class="person-value-label"><?= htmlspecialchars($course) ?></span>
                                    </p>

                                    <p><span class="person-info-label">School Year Entered:</span><br>
                                        <span class="person-value-label"><?= htmlspecialchars($user_data['school_year_entered'] ?? '--.--.--') ?></span>
                                    </p>

                                    <p><span class="person-info-label">Phone Number:</span><br>
                                        <span class="person-value-label"><?= htmlspecialchars($user_data['contact_number'] ?? '--.--.--') ?></span>
                                    </p>

                                    <p><span class="person-info-label">Current Address:</span><br>
                                        <span class="person-value-label"><?= htmlspecialchars($user_data['current_address'] ?? '--.--.--') ?></span>
                                    </p>

                                    <p><span class="person-info-label">Mother's Name:</span><br>
                                        <span class="person-value-label"><?= htmlspecialchars($user_data['mothers_name'] ?? '--.--.--') ?></span>
                                    </p>

                                    <p><span class="person-info-label">Father's Name:</span><br>
                                        <span class="person-value-label"><?= htmlspecialchars($user_data['fathers_name'] ?? '--.--.--') ?></span>
                                    </p>

                                    <p><span class="person-info-label">Guardian's Name:</span><br>
                                        <span class="person-value-label"><?= htmlspecialchars($user_data['guardians_name'] ?? '--.--.--') ?></span>
                                    </p>

                                    <p><span class="person-info-label">Name of Emergency Contact:</span><br>
                                        <span class="person-value-label"><?= htmlspecialchars($user_data['emergency_contact_name'] ?? '--.--.--') ?></span>
                                    </p>

                                    <p><span class="person-info-label">Relationship:</span><br>
                                        <span class="person-value-label"><?= htmlspecialchars($user_data['emergency_contact_relationship'] ?? '--.--.--') ?></span>
                                    </p>
                                </div>
                            </div>

                            <div id="medical-history" class="tab-content">

                                <div id="visit-history" class="department-table-container " style="display: block;">
                                    <table class="department-table">
                                        <thead>
                                            <tr>
                                                <th class="id-col">ID</th>
                                                <th>Client ID</th>
                                                <th class="action-datetime">Action Date</th>
                                                <th class="action-datetime">Action Time</th>
                                                <th>Progress</th>
                                                <th class="visual-col">Visual</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($historyData as $index => $row): ?>
                                                <tr>
                                                    <td class="id-col"><?= htmlspecialchars($row['historyID']) ?></td>
                                                    <td><?= htmlspecialchars($row['ClientID']) ?></td>
                                                    <td class="action-datetime"><?= htmlspecialchars($row['actionDate']) ?></td>
                                                    <td class="action-datetime"><?= htmlspecialchars($row['actionTime']) ?></td>
                                                    <td class="progress-<?= htmlspecialchars($row['progress']) ?>">
                                                        <?= ucfirst(htmlspecialchars($row['progress'])) ?>
                                                    </td>
                                                    <td>
                                                        <?php if ($row['progress'] === 'completed'): ?>
                                                            <div class="percentage-bar">
                                                                <div class="percentage-fill" style="width: 100%"></div>
                                                            </div>
                                                        <?php elseif ($row['progress'] === 'inprogress'): ?>
                                                            <div class="percentage-bar">
                                                                <div class="percentage-fill" style="width: 50%"></div>
                                                            </div>
                                                        <?php else: ?>
                                                            <div class="percentage-bar">
                                                                <div class="percentage-fill" style="width: 10%"></div>
                                                            </div>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div id="medical-certificate" class="tab-content">
                                <div class="medcert-conparent">
                                    <div id="medical-certificate-form">
                                        <div class="medcertheader">
                                            <img src="UC-Client/assets/images/Lspu logo.png" alt="LSPU Logo">
                                            <div class="headertextdiv">
                                                <div>Republic of the Philippines</div>
                                                <div>Laguna State Polytechnic University</div>
                                                <div>Province of Laguna</div>
                                            </div>
                                        </div>

                                        <div class="medcertitle">MEDICAL CERTIFICATE</div>

                                        <div class="medcertcontent">
                                            <div class="form-field">
                                                This is to certify that
                                                <span class="underline">
                                                    <?= htmlspecialchars($medicalCertData['PatientName'] ?? '') ?>
                                                </span>,
                                                a
                                                <span class="underline">
                                                    <?= htmlspecialchars($medicalCertData['PatientAge'] ?? '') ?>
                                                </span>
                                                year old F/M,
                                                has been seen and examined on
                                                <span class="underline">
                                                    <?= htmlspecialchars($medicalCertData['ExamDate'] ?? '') ?>
                                                </span>
                                                at the Medical Clinic.
                                            </div>

                                            <div class="form-field">
                                                Pertinent findings:
                                                <span class="underline">
                                                    <?= htmlspecialchars($medicalCertData['Findings'] ?? '') ?>
                                                </span>
                                            </div>

                                            <div class="form-field">
                                                Impression on examination:
                                                <span class="underline">
                                                    <?= htmlspecialchars($medicalCertData['Impression'] ?? '') ?>
                                                </span>
                                            </div>

                                            <div class="form-field">
                                                NOTE:
                                                <span class="underline">
                                                    <?= htmlspecialchars($medicalCertData['NoteContent'] ?? '') ?>
                                                </span>
                                            </div>

                                            <div class="signature-section">
                                                Visiting Physician/University Nurse<br>
                                                License No.
                                                <span class="underline">
                                                    <?= htmlspecialchars($medicalCertData['LicenseNo'] ?? '') ?>
                                                </span><br>
                                                Date Issued:
                                                <span class="underline">
                                                    <?= htmlspecialchars($medicalCertData['DateIssued'] ?? '') ?>
                                                </span>
                                            </div>

                                            <div class="form-number">
                                                LSPU-OSAS-SF-M08 | Rev. 0 | 10 Aug. 2016
                                            </div>

                                            <div class="cert-controls">
                                                <?php if (!$isDownloaded): ?>
                                                    <a href="generate_pdf_client.php?historyID=<?= urlencode($historyID) ?>" class="btn btn-success btn-sm" onclick="return confirmDownload();">
                                                        <i class="fa-solid fa-download"></i> Download PDF
                                                    </a>
                                                <?php endif; ?>

                                                <script>
                                                    function confirmDownload() {
                                                        return confirm("This certificate can only be downloaded once. Do you want to proceed?");
                                                    }
                                                </script>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="div-info-buttons">
                                <!-- <button class="btn save">
                            <img src="UC-Client/assets/images/File-icon.svg" class="button-icon-div" loading="lazy">
                            Save Documents
                        </button>-->
                                <a href="Medical_Form.php">
                                    <button class="btn edit">
                                        <img src="UC-Client/assets/images/Edit-icon.svg" class="button-icon-div" loading="lazy">
                                        Edit Information
                                    </button>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>

                </div>

                <script>
                    const currentDate = new Date();
                    let selectedDate = {
                        month: currentDate.getMonth(),
                        year: currentDate.getFullYear(),
                    };

                    // 🔑 Make issuanceDate global so it works everywhere
                    let issuanceDate = null;

                    document.addEventListener("DOMContentLoaded", () => {
                        const elements = cacheDOMElements();

                        // ✅ Load issuanceDate from PHP
                        const issuanceDateString =
                            "<?= htmlspecialchars($medicalCertData['DateIssued'] ?? '') ?>";
                        issuanceDate = issuanceDateString ? new Date(issuanceDateString) : null;

                        initializeDateSelect(elements);
                        generateCalendar(elements, selectedDate.month, selectedDate.year, issuanceDate);
                        startClock(elements);
                    });

                    function cacheDOMElements() {
                        return {
                            selectedDate: document.getElementById("selected-date"),
                            dateSelect: document.getElementById("date-select"),
                            weekdaysContainer: document.querySelector(".weekdays"),
                            daysContainer: document.querySelector(".days"),
                            timeDisplay: document.getElementById("time"),
                        };
                    }

                    function generateCalendar(elements, month, year, issuanceDate) {
                        const weekdays = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
                        const weekdayFragment = document.createDocumentFragment();
                        const daysFragment = document.createDocumentFragment();

                        elements.weekdaysContainer.innerHTML = "";
                        elements.daysContainer.innerHTML = "";

                        weekdays.forEach((day) => {
                            const div = document.createElement("div");
                            div.className = "weekday";
                            div.textContent = day;
                            weekdayFragment.appendChild(div);
                        });
                        elements.weekdaysContainer.appendChild(weekdayFragment);

                        const firstDay = new Date(year, month, 1).getDay();
                        for (let i = 0; i < firstDay; i++) {
                            const emptyDay = document.createElement("div");
                            emptyDay.className = "day empty";
                            daysFragment.appendChild(emptyDay);
                        }

                        const daysInMonth = new Date(year, month + 1, 0).getDate();
                        for (let day = 1; day <= daysInMonth; day++) {
                            const dayElement = document.createElement("div");
                            dayElement.className = "day";

                            const isToday =
                                day === currentDate.getDate() &&
                                month === currentDate.getMonth() &&
                                year === currentDate.getFullYear();

                            const isIssuanceDate =
                                issuanceDate &&
                                day === issuanceDate.getDate() &&
                                month === issuanceDate.getMonth() &&
                                year === issuanceDate.getFullYear();

                            if (isToday) {
                                dayElement.classList.add("current-day");
                            }
                            if (isIssuanceDate) {
                                dayElement.classList.add("issuance-day");
                            }

                            dayElement.textContent = day;
                            dayElement.addEventListener("click", (event) =>
                                selectDate(event, elements, day, month, year)
                            );
                            daysFragment.appendChild(dayElement);
                        }

                        elements.daysContainer.appendChild(daysFragment);
                    }

                    function selectDate(event, elements, day, month, year) {
                        document
                            .querySelectorAll(".day")
                            .forEach((el) => el.classList.remove("selected"));
                        event.target.classList.add("selected");
                        elements.selectedDate.textContent = `${String(day).padStart(2, "0")}.${String(
    month + 1
  ).padStart(2, "0")}.${year}`;
                    }

                    function initializeDateSelect(elements) {
                        let html = "";
                        for (let y = currentDate.getFullYear(); y <= currentDate.getFullYear() + 3; y++) {
                            html += `<optgroup label="${y}">`;
                            for (let m = 0; m < 12; m++) {
                                const isSelected = y === selectedDate.year && m === selectedDate.month;
                                html += `<option value="${m}-${y}" ${isSelected ? "selected" : ""}>
        ${new Date(0, m).toLocaleString("en", { month: "long" })} ${y}
      </option>`;
                            }
                            html += "</optgroup>";
                        }
                        elements.dateSelect.innerHTML = html;

                        elements.dateSelect.addEventListener("change", () => {
                            const [month, year] = elements.dateSelect.value.split("-").map(Number);
                            selectedDate = {
                                month,
                                year
                            };
                            generateCalendar(elements, month, year, issuanceDate); // ✅ issuanceDate is global now
                        });
                    }

                    function startClock(elements) {
                        function updateClock() {
                            const now = new Date();
                            const hours = now.getHours() % 12 || 12;
                            elements.timeDisplay.textContent = `${hours}:${now
      .getMinutes()
      .toString()
      .padStart(2, "0")}:${now.getSeconds().toString().padStart(2, "0")} ${
      now.getHours() >= 12 ? "PM" : "AM"
    }`;
                        }
                        updateClock();
                        setInterval(updateClock, 1000);
                    }
                </script>
                <div id="right-content">
                    <div id="calendar">
                        <div class="Calendarheader">
                            <p id="calendar-header-text">My Calendar</h2>
                                <select id="date-select"></select>
                        </div>

                        <div class="calendar">
                            <div class="weekdays"></div>
                            <div class="days"></div>
                        </div>

                        <!--  <div class="time-display" id="time"></div>
                        <div class="status">
                            <div class="status-icon"></div>
                            <p class="status-text"><span id="selected-date"><?= htmlspecialchars($medicalCertData['DateIssued'] ?? '   ') ?></span>Medical Certificate Issuance</p>
                        </div>
                -->
                    </div>
                    <div id="call-div">
                        <div id="text-overlay">
                            <h3>Emergency Contacts:</h3>
                            <p>Clinic Hotline: (+63) 912-345-6789</p>
                            <p>Campus Emergency: (+63) 911-123-4567</p>
                            <p>Email: support@universityclinic.edu</p>
                        </div>
                        <div id="pic-div">
                            <img id="picimg" src="UC-Client/assets/images/office-pic.svg" type="image/webp" loading="lazy">
                        </div>
                        <h2>Need help?</h2>
                        <a href="#">
                            <p><span class="clinic-policies-label">View Clinic Policies | FAQs:</span>
                        </a>
                    </div>
                </div>
            </div>
            <!--===================================================================================================-->
        </main>
    </div>

</body>

</html>