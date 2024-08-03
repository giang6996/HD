<?php
require_once '../controllers/VehicleController.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new VehicleController();

    // Read raw input data
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    // Check if the action is set
    if (!isset($data['action'])) {
        echo json_encode(['success' => false, 'message' => 'Missing action']);
        exit();
    }

    switch ($data['action']) {
        case 'createVehicle':
            $response = $controller->createVehicle($data);
            echo json_encode($response);
            break;

        case 'getVehicleDetails':
            if (isset($data['id'])) {
                $response = $controller->getVehicleDetails($data['id']);
                echo json_encode($response);
            } else {
                echo json_encode(['success' => false, 'message' => 'Missing required fields for getVehicleDetails']);
            }
            break;

        case 'getVehiclesByAccountId':
            if (isset($data['accountId'])) {
                $response = $controller->getVehiclesByAccountId($data['accountId']);
                echo json_encode($response);
            } else {
                echo json_encode(['success' => false, 'message' => 'Missing required fields for getVehiclesByAccountId']);
            }
            break;

        case 'updateVehicle':
            if (isset($data['id'])) {
                $response = $controller->updateVehicle($data['id'], $data);
                echo json_encode($response);
            } else {
                echo json_encode(['success' => false, 'message' => 'Missing required fields for updateVehicle']);
            }
            break;

        case 'deleteVehicle':
            if (isset($data['id'])) {
                $response = $controller->deleteVehicle($data['id']);
                echo json_encode($response);
            } else {
                echo json_encode(['success' => false, 'message' => 'Missing required fields for deleteVehicle']);
            }
            break;

        case 'getVehicleTypes':
            $response = $controller->getVehicleTypes();
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
