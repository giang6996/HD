<?php
require_once '../configs/db.php';
require_once '../controllers/ParkingSlotController.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $input = file_get_contents('php://input');
    $data = json_decode($input, true);
    
    $controller = new ParkingSlotController();

    switch ($data['action']) {
        case 'createParkingSlot':
            $data = json_decode(file_get_contents("php://input"), true);
            $response = $controller->createParkingSlot($data);
            echo json_encode($response);
            break;

        case 'getParkingSlotDetails':
            $id = $_POST['id'] ?? '';
            $response = $controller->getParkingSlotDetails($id);
            echo json_encode($response);
            break;

        case 'getParkingSlotAll':
            $response = $controller->getParkingSlotAll();
            echo json_encode($response);
            break;

        case 'updateParkingSlot':
            $id = $_POST['id'] ?? '';
            $data = json_decode(file_get_contents("php://input"), true);
            $response = $controller->updateParkingSlot($id, $data);
            echo json_encode($response);
            break;

        case 'deleteParkingSlot':
            $id = $_POST['id'] ?? '';
            $response = $controller->deleteParkingSlot($id);
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
