<?php
require_once '../configs/db.php';
require_once '../controllers/AccountController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new AccountController();
    
    // Read raw input data
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);
    
    // Validate the decoded data
    if($data['authmode'] == 'signup'){
        if (isset($data['customerName'], $data['customerEmail'], $data['customerPassword'])) {
            $response = $controller->createAccount($data);
        } else {
            $response = ['success' => false, 'message' => 'Missing required fields signup handler'];
        }
    } 
    else if($data['authmode'] == 'accountDetails'){
        if (isset($data['accountId'])) {
            $response = $controller->getAccountDetails($data['accountId']);
        } else {
            $response = ['success' => false, 'message' => 'Missing required fields in accountDetails handler'];
        }
    }
    else {
        if (isset($data['customerEmail'], $data['customerPassword'])) {
            $response = $controller->loginAccount($data);
        } else {
            $response = ['success' => false, 'message' => 'Missing required fields in login handler'];
        }
    }
    
    
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>
