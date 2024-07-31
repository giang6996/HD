<?php
header('Content-Type: application/json');

// get the HTTP method and body of the request
$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

// connect to the mysql database, provide the appropriate credentials
$conn = mysqli_connect('localhost', 'root', '', 'cos30043');
mysqli_set_charset($conn, 'utf8');

// initialise the table name accordingly
$table = "users";

// function to validate email
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

// function to validate date of birth (YYYY-MM-DD format)
function isValidDate($date) {
    return preg_match('/\d{4}-\d{2}-\d{2}/', $date) === 1;
}

// create SQL
if ($method == 'POST') {
    // check required fields
    $required_fields = ['firstname', 'lastname', 'username', 'email', 'dateofbirth', 'street', 'district', 'city', 'password'];
    foreach ($required_fields as $field) {
        if (empty($input[$field])) {
            echo json_encode(['success' => false, 'message' => "$field is required"]);
            exit;
        }
    }

    // validate email
    if (!isValidEmail($input['email'])) {
        echo json_encode(['success' => false, 'message' => 'Invalid email format']);
        exit;
    }

    // validate date of birth
    if (!isValidDate($input['dateofbirth'])) {
        echo json_encode(['success' => false, 'message' => 'Invalid date format. Expected YYYY-MM-DD']);
        exit;
    }

    // hash the password
    $hashed_password = password_hash($input['password'], PASSWORD_DEFAULT);

    // prepare and bind
    $stmt = $conn->prepare("INSERT INTO `$table` (firstname, lastname, username, email, dateofbirth, street, district, city, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssss", $input['firstname'], $input['lastname'], $input['username'], $input['email'], $input['dateofbirth'], $input['street'], $input['district'], $input['city'], $hashed_password);

    // execute the statement
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'User registered successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $stmt->error]);
    }

    // close the statement
    $stmt->close();
}

// close mysql connection
mysqli_close($conn);
?>
