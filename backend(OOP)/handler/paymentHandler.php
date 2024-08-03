<?php
require_once '../controllers/PaymentController.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new PaymentController();

    // Read raw input data
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    // Check if the action is set
    if (!isset($data['action'])) {
        echo json_encode(['success' => false, 'message' => 'Missing action']);
        exit();
    }

    switch ($data['action']) {
        case 'createPayment':
            $response = $controller->createPayment($data);
            echo json_encode($response);
            break;

        case 'getPaymentDetails':
            if (isset($data['id'])) {
                $response = $controller->getPaymentDetails($data['id']);
                echo json_encode($response);
            } else {
                echo json_encode(['success' => false, 'message' => 'Missing required fields for getPaymentDetails']);
            }
            break;

        case 'updatePayment':
            if (isset($data['id'])) {
                $response = $controller->updatePayment($data['id'], $data);
                echo json_encode($response);
            } else {
                echo json_encode(['success' => false, 'message' => 'Missing required fields for updatePayment']);
            }
            break;

        case 'deletePayment':
            if (isset($data['id'])) {
                $response = $controller->deletePayment($data['id']);
                echo json_encode($response);
            } else {
                echo json_encode(['success' => false, 'message' => 'Missing required fields for deletePayment']);
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
