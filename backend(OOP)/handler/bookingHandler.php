<?php
require_once '../controllers/BookingController.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new BookingController();

    // Read raw input data
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    // Check if the action is set
    if (!isset($data['action'])) {
        echo json_encode(['success' => false, 'message' => 'Missing action']);
        exit();
    }

    switch ($data['action']) {
        case 'createBooking':
            if (isset($data['accountId'], $data['parkingSlotId'], $data['bookingTime'], $data['duration'])) {
                $response = $controller->createBooking($data);
                
            } else {
                $response = ['success' => false, 'accountId' => $data['accountId'], 'message' => 'Missing required fields for create booking'];
            }
            echo json_encode($response);
            break;

        case 'getBookingDetails':
            if (isset($data['id'])) {
                $response = $controller->getBookingDetails($data['id']);
            } else {
                $response = ['success' => false, 'message' => 'Missing required fields for getBookingDetails'];
            }
            echo json_encode($response);
            break;

        case 'getBookingDetailsByAccountId':
            if (isset($data['accountId'])) {
                $response = $controller->getBookingDetailsByAccountId($data['accountId']);
            } else {
                $response = ['success' => false, 'message' => 'Missing required fields for getBookingDetailsByAccountId'];
            }
            echo json_encode($response);
            break;

        case 'updateBooking':
            if (isset($data['id'], $data['parkingSlotId'], $data['bookingTime'], $data['duration'])) {
                $response = $controller->updateBooking($data['id'], $data);
            } else {
                $response = ['success' => false, 'message' => 'Missing required fields for updateBooking'];
            }
            echo json_encode($response);
            break;

        case 'cancelBooking':
            if (isset($data['id'])) {
                $response = $controller->cancelBooking($data['id']);
            } else {
                $response = ['success' => false, 'message' => 'Missing required fields for cancelBooking'];
            }
            echo json_encode($response);
            break;

        default:
            echo json_encode(['success' => false, 'message' => 'Invalid action']);
            break;
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
