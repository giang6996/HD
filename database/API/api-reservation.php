<?php
session_start();
header('Content-Type: application/json');

// get the HTTP method and body of the request
$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

// connect to the mysql database, provide the appropriate credentials
$conn = mysqli_connect('localhost', 'root', '', 'cos30043');
mysqli_set_charset($conn, 'utf8');

// initialise the table name accordingly
$reservationTable = "reservations";
$parkingSlotTable = "parkingslots";

// function to validate date (YYYY-MM-DD format)
function isValidDate($date) {
    return preg_match('/\d{4}-\d{2}-\d{2}/', $date) === 1;
}

if ($method == 'POST') {
    // handle reservation creation
    $required_fields = ['userid', 'parkingslotid', 'startdate', 'duration'];
    foreach ($required_fields as $field) {
        if (empty($input[$field])) {
            echo json_encode(['success' => false, 'message' => "$field is required"]);
            exit;
        }
    }

    if (!isValidDate($input['startdate'])) {
        echo json_encode(['success' => false, 'message' => 'Invalid date format. Expected YYYY-MM-DD']);
        exit;
    }

    $slotCheckStmt = $conn->prepare("SELECT isavailable FROM `$parkingSlotTable` WHERE parkingslotid = ?");
    $slotCheckStmt->bind_param("i", $input['parkingslotid']);
    $slotCheckStmt->execute();
    $slotCheckResult = $slotCheckStmt->get_result();
    $slot = $slotCheckResult->fetch_assoc();

    if (!$slot || $slot['isavailable'] == 0) {
        echo json_encode(['success' => false, 'message' => 'Parking slot is not available']);
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO `$reservationTable` (userid, parkingslotid, startdate, duration) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iisi", $input['userid'], $input['parkingslotid'], $input['startdate'], $input['duration']);

    if ($stmt->execute()) {
        $updateSlotStmt = $conn->prepare("UPDATE `$parkingSlotTable` SET isavailable = 0 WHERE parkingslotid = ?");
        $updateSlotStmt->bind_param("i", $input['parkingslotid']);
        $updateSlotStmt->execute();
        $updateSlotStmt->close();

        echo json_encode(['success' => true, 'message' => 'Reservation made successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $stmt->error]);
    }

    $stmt->close();

};

if ($method == 'GET') {
    // handle reservation retrieval
    $userid = isset($_GET['userid']) ? intval($_GET['userid']) : 0;
    if ($userid <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid userid']);
        exit;
    }

    $stmt = $conn->prepare("SELECT * FROM `$reservationTable` WHERE userid = ?");
    $stmt->bind_param("i", $userid);
    
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $reservations = array();
        while ($row = $result->fetch_assoc()) {
            $reservations[] = $row;
        }
        echo json_encode(['success' => true, 'reservations' => $reservations]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $stmt->error]);
    }

    $stmt->close();
}

// close mysql connection
mysqli_close($conn);
?>
