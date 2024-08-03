<?php
require_once '../controllers/InvoiceController.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new InvoiceController();

    // Read raw input data
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    // Check if the action is set
    if (!isset($data['action'])) {
        echo json_encode(['success' => false, 'message' => 'Missing action']);
        exit();
    }

    switch ($data['action']) {
        case 'createInvoice':
            $response = $controller->createInvoice($data);
            echo json_encode($response);
            break;

        case 'getInvoiceDetails':
            if (isset($data['id'])) {
                $response = $controller->getInvoiceDetails($data['id']);
                echo json_encode($response);
            } else {
                echo json_encode(['success' => false, 'message' => 'Missing required fields for getInvoiceDetails']);
            }
            break;

        case 'getUserInvoice':
            if (isset($data['accountId'])) {
                $response = $controller->getUserInvoice($data['accountId']);
                echo json_encode($response);
            } else {
                echo json_encode(['success' => false, 'message' => 'Missing required fields for getUserInvoice']);
            }
            break;
        case 'deleteInvoice':
            if (isset($data['id'])) {
                $response = $controller->deleteInvoice($data['id']);
                echo json_encode($response);
            } else {
                echo json_encode(['success' => false, 'message' => 'Missing required fields for deleteInvoice']);
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
