<?php
session_start();

header('Content-Type: application/json');

// Get the HTTP method
$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

// Connect to the MySQL database
$conn = mysqli_connect('localhost', 'root', '', 'cos30043');
mysqli_set_charset($conn, 'utf8');

// Initialise the table names accordingly
$cartTable = "cart";
$slotsTable = "parkingslots";

if (!isset($_SESSION['userId'])) {
    echo json_encode(['success' => false, 'message' => 'User not authenticated']);
    exit;
}
else {
    // Get user ID from session
    $userId = $_SESSION['userId'];
}



// Function to get the user ID from the request (assuming it is passed in the input)

if ($method == 'POST') {
    // Check required fields
    $required_fields = ['slotid', 'startdate', 'duration', 'slotprice'];
    foreach ($required_fields as $field) {
        if (empty($input[$field])) {
            echo json_encode(['success' => false, 'message' => "$field is required"]);
            exit;
        }
    }

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO `$cartTable` (userid, parkingslotid, startdate, duration, slotprice) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iisii", $userId, $input['slotid'], $input['startdate'], $input['duration'], $input['slotprice']);

    // Execute the statement
    if ($stmt->execute()) {
        $stmt = $conn->prepare("UPDATE `$slotsTable` SET isavailable = 0 WHERE parkingslotid = ?");
        $stmt->bind_param("i", $input['slotid']);
        $stmt->execute();
        echo json_encode(['success' => true, 'message' => 'Slot added to cart']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $stmt->error]);
    }

    // Close the statement
    $stmt->close();
};

if ($method == 'GET') {
    // Prepare and execute
    $stmt = $conn->prepare("SELECT cart.*, parkingslots.slotname FROM `$cartTable` JOIN $slotsTable ON cart.parkingslotid = parkingslots.parkingslotid WHERE userid = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch and return data
    $cartItems = [];
    while ($row = $result->fetch_assoc()) {
        $cartItems[] = $row;
    }

    echo json_encode(['success' => true, 'cartItems' => $cartItems]);

    // Close the statement
    $stmt->close();
}

if ($method == 'DELETE') {
    // Check required fields
    $required_fields = ['slotid', 'cartid'];
    foreach ($required_fields as $field) {
        if (empty($input[$field])) {
            echo json_encode(['success' => false, 'message' => "$field is required"]);
            exit;
        }
    }
    // Prepare and bind
    $stmt = $conn->prepare("DELETE FROM `$cartTable` WHERE cartid = ?");
    $stmt->bind_param("i", $input['cartid']);

    // Execute the statement
    if ($stmt->execute()) {
        $stmt = $conn->prepare("UPDATE `$slotsTable` SET isavailable = 1 WHERE parkingslotid = ?");
        $stmt->bind_param("i", $input['slotid']);
        $stmt->execute();
        echo json_encode(['success' => true, 'message' => 'Slot added to cart']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $stmt->error]);
    }

    // Close the statement
    $stmt->close();
};

// Close MySQL connection
mysqli_close($conn);
?>
