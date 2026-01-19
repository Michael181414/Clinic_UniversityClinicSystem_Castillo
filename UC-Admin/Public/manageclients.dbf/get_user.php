<?php
require_once 'config/database.php';
header('Content-Type: text/html');
session_start();


if (!isset($_SESSION['user_id'])) {
    header('Location: ../../../index.php');
    exit;
}
function getFilteredClients($clientType = 'All', $globalSearch = '', $limit = 30, $offset = 0)
{
    $pdo = pdo_connect_mysql();

    $sql = "
        SELECT
            c.ClientID,
            c.profilePicturePath,
            CONCAT(c.Firstname, ' ', c.Lastname) AS FullName,
            c.Email,
            COALESCE(pi.Course, 'N/A') AS Course,
            c.Department,
            c.ClientType
        FROM clients c
        LEFT JOIN personalinfo pi ON c.ClientID = pi.ClientID
        WHERE c.deleted_at IS NULL
    ";

    $params = [];

    // ✅ ClientType filter only when NOT AllPatients
    if ($clientType !== 'All' && $clientType !== '') {
        $sql .= " AND c.ClientType = :clientType";
        $params[':clientType'] = $clientType;
    }

    // 🔍 Global search
    if (!empty($globalSearch)) {
        $keywords = preg_split('/\s+/', trim($globalSearch));
        $searchParts = [];

        foreach ($keywords as $index => $keyword) {
            $param = ":keyword$index";
            $searchParts[] = "(
                c.ClientID LIKE $param OR
                CONCAT(c.Firstname, ' ', c.Lastname) LIKE $param OR
                c.Email LIKE $param OR
                c.Department LIKE $param OR
                c.ClientType LIKE $param
            )";
            $params[$param] = "%$keyword%";
        }

        $sql .= " AND (" . implode(" AND ", $searchParts) . ")";
    }

    $sql .= "
        ORDER BY c.ClientID DESC
        LIMIT :limit OFFSET :offset
    ";

    $stmt = $pdo->prepare($sql);

    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value, PDO::PARAM_STR);
    }

    $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);

    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function countFilteredClients($clientType = 'All', $globalSearch = '')
{
    $pdo = pdo_connect_mysql();

    $sql = "
        SELECT COUNT(*)
        FROM clients c
        LEFT JOIN personalinfo pi ON c.ClientID = pi.ClientID
        WHERE c.deleted_at IS NULL
    ";

    $params = [];

    // ✅ Apply ClientType filter ONLY if not AllPatients
    if ($clientType !== 'All' && $clientType !== '') {
        $sql .= " AND c.ClientType = :clientType";
        $params[':clientType'] = $clientType;
    }

    // 🔍 Global search
    if (!empty($globalSearch)) {
        $keywords = preg_split('/\s+/', trim($globalSearch));
        $searchParts = [];

        foreach ($keywords as $index => $keyword) {
            $param = ":keyword$index";
            $searchParts[] = "(
                c.ClientID LIKE $param OR
                CONCAT(c.Firstname, ' ', c.Lastname) LIKE $param OR
                c.Email LIKE $param OR
                c.Department LIKE $param OR
                c.ClientType LIKE $param
            )";
            $params[$param] = "%$keyword%";
        }

        if (!empty($searchParts)) {
            $sql .= " AND (" . implode(" AND ", $searchParts) . ")";
        }
    }

    $stmt = $pdo->prepare($sql);

    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value, PDO::PARAM_STR);
    }

    $stmt->execute();
    return (int) $stmt->fetchColumn();
}

$clientType = $_GET['client_type'] ?? '';
$idFilter = $_GET['id_filter'] ?? '';

if (!empty($clientType)) {
    $clients = getFilteredClients($clientType, $idFilter) ?? [];

    foreach ($clients as $client): ?>
        <tr class="client-row" data-href="ClientProfile.php?id=<?= urlencode($client['ClientID']) ?>">
            <td class="searchable-id"><?= htmlspecialchars($client['ClientID']) ?></td>
            <td>
                <?php
                $profilePath = !empty($client['profilePicturePath'])
                    ? '../../uploads/' . $client['profilePicturePath']
                    : '../../uploads/profilepic2.png';
                ?>
                <img src="<?= htmlspecialchars($profilePath) ?>" alt="Profile" class="rounded-circle" width="50" height="50">
            </td>
            <td class="searchable-name">
                <?= htmlspecialchars($client['FullName']) ?>
            </td>
            <td class="email-td">
                <?= htmlspecialchars($client['Email']) ?>
            </td>

            <?php if ($clientType === 'Student' || $clientType === 'Freshman'): ?>
                <td class="course-td">
                    <?= htmlspecialchars($client['Course']) ?>
                </td>
            <?php endif; ?>

            <td class="department-td">
                <?= htmlspecialchars($client['Department']) ?>
            </td>

            <td class="actions-column">
                <div class="action-buttons">
                    <a href="ClientProfile.php?id=<?= $client['ClientID'] ?>" title="Edit User">
                        <img class="table-icon-img" src="assets/images/edit-blue-icon.svg" alt="Edit Icon" style="border-radius: 0; object-fit: unset; width: 20px; height: 20px;">
                    </a>
                    <a class="row-delete-btn"
                        data-id="<?= $client['ClientID'] ?>"
                        data-url="manageclients.dbf/delete_client.php"
                        title="Delete User">
                        <i class="fa-solid fa-trash delete-icon"></i>
                    </a>
                </div>
            </td>
        </tr>
<?php endforeach;

    exit;
}

// Main page loading
$clientType = $_GET['client_type'] ?? 'Student';
$idFilter = $_GET['id_filter'] ?? '';
$page = max(1, (int)($_GET['page'] ?? 1));
$perPage = 30;

$totalClients = countFilteredClients($clientType, $idFilter);
$totalPages = ceil($totalClients / $perPage);

$clients = getFilteredClients($clientType, $idFilter, $page, $perPage);

// Extra fetch functions, all updated with "AND c.deleted_at IS NULL"

function fetchAllPatients($limit = 30, $offset = 0)
{
    $pdo = pdo_connect_mysql();
    $stmt = $pdo->prepare("
        SELECT 
            c.ClientID,
            c.profilePicturePath,
            CONCAT(c.Firstname, ' ', c.Lastname) AS FullName,
            c.Email,
            COALESCE(pi.Course, 'N/A') AS Course,
            c.Department,
            c.ClientType
        FROM clients c
        LEFT JOIN personalinfo pi ON c.ClientID = pi.ClientID
        WHERE c.deleted_at IS NULL
        ORDER BY c.ClientID DESC
        LIMIT :limit OFFSET :offset
    ");
    $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function fetchStudents($limit = 30, $offset = 0)
{
    $pdo = pdo_connect_mysql();
    $stmt = $pdo->prepare("
        SELECT 
            c.ClientID,
            c.profilePicturePath,
            CONCAT(c.Firstname, ' ', c.Lastname) AS FullName,
            c.Email,
            COALESCE(pi.Course, 'N/A') AS Course,
            c.Department,
            c.ClientType
        FROM clients c
        LEFT JOIN personalinfo pi ON c.ClientID = pi.ClientID
        WHERE c.ClientType = 'Student'
        AND c.deleted_at IS NULL
        ORDER BY c.ClientID DESC
        LIMIT :limit OFFSET :offset
    ");
    $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function fetchFaculty($limit = 30, $offset = 0)
{
    $pdo = pdo_connect_mysql();
    $stmt = $pdo->prepare("
        SELECT 
            c.ClientID,
            c.profilePicturePath,
            CONCAT(c.Firstname, ' ', c.Lastname) AS FullName,
            c.Email,
            COALESCE(pi.Course, 'N/A') AS Course,
            c.Department,
            c.ClientType
        FROM clients c
        LEFT JOIN personalinfo pi ON c.ClientID = pi.ClientID
        WHERE c.ClientType = 'Faculty'
        AND c.deleted_at IS NULL
        ORDER BY c.ClientID DESC
        LIMIT :limit OFFSET :offset
    ");
    $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function fetchPersonnel($limit = 30, $offset = 0)
{
    $pdo = pdo_connect_mysql();
    $stmt = $pdo->prepare("
        SELECT 
            c.ClientID,
            c.profilePicturePath,
            CONCAT(c.Firstname, ' ', c.Lastname) AS FullName,
            c.Email,
            COALESCE(pi.Course, 'N/A') AS Course,
            c.Department,
            c.ClientType
        FROM clients c
        LEFT JOIN personalinfo pi ON c.ClientID = pi.ClientID
        WHERE c.ClientType = 'Personnel'
        AND c.deleted_at IS NULL
        ORDER BY c.ClientID DESC
        LIMIT :limit OFFSET :offset
    ");
    $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function fetchFreshman($limit = 30, $offset = 0)
{
    $pdo = pdo_connect_mysql();
    $stmt = $pdo->prepare("
        SELECT 
            c.ClientID,
            c.profilePicturePath,
            CONCAT(c.Firstname, ' ', c.Lastname) AS FullName,
            c.Email,
            COALESCE(pi.Course, 'N/A') AS Course,
            c.Department,
            c.ClientType
        FROM clients c
        LEFT JOIN personalinfo pi ON c.ClientID = pi.ClientID
        WHERE c.ClientType = 'Freshman'
        AND c.deleted_at IS NULL
        ORDER BY c.ClientID DESC
        LIMIT :limit OFFSET :offset
    ");
    $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function fetchNewPersonnel($limit = 30, $offset = 0)
{
    $pdo = pdo_connect_mysql();
    $stmt = $pdo->prepare("
        SELECT 
            c.ClientID,
            c.profilePicturePath,
            CONCAT(c.Firstname, ' ', c.Lastname) AS FullName,
            c.Email,
            COALESCE(pi.Course, 'N/A') AS Course,
            c.Department,
            c.ClientType
        FROM clients c
        LEFT JOIN personalinfo pi ON c.ClientID = pi.ClientID
        WHERE c.ClientType = 'NewPersonnel'
        AND c.deleted_at IS NULL
        ORDER BY c.ClientID DESC
        LIMIT :limit OFFSET :offset
    ");
    $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function countClientsByType($clientType)
{
    $pdo = pdo_connect_mysql();
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM clients WHERE ClientType = :type AND deleted_at IS NULL");
    $stmt->bindValue(':type', $clientType, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetchColumn();
}
?>