<?php
session_start();
header('Content-Type: application/json');

// get the HTTP method
$method = $_SERVER['REQUEST_METHOD'];

// connect to the mysql database, provide the appropriate credentials
$conn = mysqli_connect('localhost', 'root', '', 'cos30043');
mysqli_set_charset($conn, 'utf8');

// initialise the table name accordingly
$table = "parkingslots";

// create SQL
switch ($method) {
    case 'GET':
        $sql = "SELECT 
                p.parkingslotid, 
                p.slotname, 
                p.price, 
                (SELECT l.locationname FROM locations l WHERE l.locationid = p.locationid) as locationname, 
                (SELECT t.typename FROM types t WHERE t.typeid = p.typeid) as typename 
            FROM `$table` p
            WHERE p.isavailable = 1";
        break;
}

// execute SQL statement
$result = mysqli_query($conn, $sql);
if ($result) {
    $parkingslots = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $parkingslots[] = $row;
    }
    echo json_encode(['success' => true, 'parkingslots' => $parkingslots]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error: ' . mysqli_error($conn)]);
}

// close mysql connection
mysqli_close($conn);
