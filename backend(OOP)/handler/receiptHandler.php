<?php
require_once '../controllers/ReceiptController.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new ReceiptController();

    // Read raw input data
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    // Check if the action is set
    if (!isset($data['action'])) {
        echo json_encode(['success' => false, 'message' => 'Missing action']);
        exit();
    }

    switch ($data['action']) {
        case 'createReceipt':
            $response = $controller->createReceipt($data);
            echo json_encode($response);
            break;

        case 'getReceiptDetails':
            if (isset($data['id'])) {
                $response = $controller->getReceiptDetails($data['id']);
                echo json_encode($response);
            } else {
                echo json_encode(['success' => false, 'message' => 'Missing required fields for getReceiptDetails']);
            }
            break;

        case 'deleteReceipt':
            if (isset($data['id'])) {
                $response = $controller->deleteReceipt($data['id']);
                echo json_encode($response);
            } else {
                echo json_encode(['success' => false, 'message' => 'Missing required fields for deleteReceipt']);
            }
            break;

        default:
            echo json_encode(['success' => false, 'message' => 'Invalid action']);
            break;
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
