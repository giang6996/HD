<?php

error_reporting(E_ERROR | E_PARSE);

session_start();

header('Content-Type: application/json');

// get the HTTP method, path and body of the request
$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);  // json string to associative array(true)

// connect to the mysql database, provide the appropriate credentials
$conn = mysqli_connect('localhost', 'root', '', 'cos30043');
mysqli_set_charset($conn, 'utf8');

// initialise the table name accordingly
$table = "users";

// create SQL
switch ($method) {
  case 'POST':
    $stmt = $conn->prepare("SELECT * FROM `$table` WHERE email = ?");
    $stmt->bind_param("s", $input['email']);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Verify password
    if (password_verify($input['password'], $user['password'])) {
        unset($user['password']); // Remove password from user data
        $_SESSION['userId'] = $user['userid']; // store user data in session
        echo json_encode(['success' => true, 'user' => $user]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid email or password']);
    }

    $stmt->close();
    break;
}

// close mysql connection
mysqli_close($conn);
?>
