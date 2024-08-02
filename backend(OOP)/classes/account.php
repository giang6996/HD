<?php
// require_once '../configs/db.php';

// class Account {
//     private $conn;
//     private $table = "accounts";

//     // Variables with data types
//     private int $id;
//     private string $customerName;
//     private string $customerEmail;
//     private string $customerPassword;
//     private int $vehicleId;

//     public function __construct($db) {
//         $this->conn = $db;
//     }

//     // Getter and Setter methods
//     public function getId(): int {
//         return $this->id;
//     }

//     public function setId(int $id): void {
//         $this->id = $id;
//     }

//     public function getCustomerName(): string {
//         return $this->customerName;
//     }

//     public function setCustomerName(string $customerName): void {
//         $this->customerName = $customerName;
//     }

//     public function getCustomerEmail(): string {
//         return $this->customerEmail;
//     }

//     public function setCustomerEmail(string $customerEmail): void {
//         $this->customerEmail = $customerEmail;
//     }

//     public function getCustomerPhone(): string {
//         return $this->customerPassword;
//     }

//     public function setCustomerPhone(string $customerPassword): void {
//         $this->customerPassword = $customerPassword;
//     }

//     public function getVehicleId(): int {
//         return $this->vehicleId;
//     }

//     public function setVehicleId(int $vehicleId): void {
//         $this->vehicleId = $vehicleId;
//     }

//     // CRUD operations
//     public function createAccount($customerName, $customerEmail, $customerPassword) {
//         $stmt = $this->conn->prepare("INSERT INTO $this->table (customerName, customerEmail, customerPassword) VALUES (?, ?, ?)");
//         if ($stmt->execute([$customerName, $customerEmail, $customerPassword])) {
//             return ['success' => true, 'message' => 'Account created'];
//         }
//         return ['success' => false, 'message' => 'Error: ' . $stmt->errorInfo()[2]];
//     }

//     public function getAccountDetails($accountId) {
//         $stmt = $this->conn->prepare("SELECT * FROM $this->table WHERE id = ?");
//         $stmt->execute([$accountId]);
//         return $stmt->fetch(PDO::FETCH_ASSOC);
//     }

//     public function updateAccount($accountId, $customerName, $customerEmail, $customerPassword) {
//         $stmt = $this->conn->prepare("UPDATE $this->table SET customerName = ?, customerEmail = ?, customerPassword = ? WHERE id = ?");
//         if ($stmt->execute([$customerName, $customerEmail, $customerPassword, $accountId])) {
//             return ['success' => true, 'message' => 'Account updated'];
//         }
//         return ['success' => false, 'message' => 'Error: ' . $stmt->errorInfo()[2]];
//     }

//     public function deleteAccount($accountId) {
//         $stmt = $this->conn->prepare("DELETE FROM $this->table WHERE id = ?");
//         if ($stmt->execute([$accountId])) {
//             return ['success' => true, 'message' => 'Account deleted'];
//         }
//         return ['success' => false, 'message' => 'Error: ' . $stmt->errorInfo()[2]];
//     }
// }

require_once '../configs/db.php';

class Account {
    private $conn;
    private $table = "accounts";

    // Variables with data types
    private int $id;
    private string $customerName;
    private string $customerEmail;
    private string $customerPassword;

    public function __construct($db) {
        $this->conn = $db;
        $this->createTableIfNotExists();
    }

    // Getter and Setter methods
    public function getId(): int {
        return $this->id;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function getCustomerName(): string {
        return $this->customerName;
    }

    public function setCustomerName(string $customerName): void {
        $this->customerName = $customerName;
    }

    public function getCustomerEmail(): string {
        return $this->customerEmail;
    }

    public function setCustomerEmail(string $customerEmail): void {
        $this->customerEmail = $customerEmail;
    }

    public function getCustomerPhone(): string {
        return $this->customerPassword;
    }

    public function setCustomerPhone(string $customerPassword): void {
        $this->customerPassword = $customerPassword;
    }


 // Create table if it doesn't exist
    private function createTableIfNotExists() {
        $sql = "CREATE TABLE IF NOT EXISTS $this->table (
            id INT AUTO_INCREMENT PRIMARY KEY,
            customerName VARCHAR(100) NOT NULL,
            customerEmail VARCHAR(255) NOT NULL UNIQUE,
            customerPassword VARCHAR(255) NOT NULL
        );";
    $this->conn->exec($sql);
    }


   // CRUD operations
   public function createAccount($customerName, $customerEmail, $customerPassword) {
    $stmt = $this->conn->prepare("INSERT INTO $this->table (customerName, customerEmail, customerPassword) VALUES (?, ?, ?)");
    if ($stmt->execute([$customerName, $customerEmail, $customerPassword])) {
        return ['success' => true, 'message' => 'Account created'];
    }
    return ['success' => false, 'message' => 'Error: ' . $stmt->errorInfo()[2]];
    }

    public function getAccountDetails($accountId) {
        $stmt = $this->conn->prepare("SELECT * FROM $this->table WHERE id = ?");
        $stmt->execute([$accountId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateAccount($accountId, $customerName, $customerEmail, $customerPassword) {
        $stmt = $this->conn->prepare("UPDATE $this->table SET customerName = ?, customerEmail = ?, customerPassword = ? WHERE id = ?");
        if ($stmt->execute([$customerName, $customerEmail, $customerPassword, $accountId])) {
            return ['success' => true, 'message' => 'Account updated'];
        }
        return ['success' => false, 'message' => 'Error: ' . $stmt->errorInfo()[2]];
    }

    public function deleteAccount($accountId) {
        $stmt = $this->conn->prepare("DELETE FROM $this->table WHERE id = ?");
        if ($stmt->execute([$accountId])) {
            return ['success' => true, 'message' => 'Account deleted'];
        }
        return ['success' => false, 'message' => 'Error: ' . $stmt->errorInfo()[2]];
    }

    // Add sample records
    public function addSampleRecords() {
        $samples = [
            ['customerName' => 'John Doe', 'customerEmail' => 'john.doe@example.com', 'customerPassword' => 'password123'],
            ['customerName' => 'Jane Smith', 'customerEmail' => 'jane.smith@example.com', 'customerPassword' => 'password123'],
            ['customerName' => 'Alice Johnson', 'customerEmail' => 'alice.johnson@example.com', 'customerPassword' => 'password123'],
            ['customerName' => 'Bob Brown', 'customerEmail' => 'bob.brown@example.com', 'customerPassword' => 'password123'],
            ['customerName' => 'Charlie Davis', 'customerEmail' => 'charlie.davis@example.com', 'customerPassword' => 'password123']
        ];

        foreach ($samples as $sample) {
            $this->createAccount($sample['customerName'], $sample['customerEmail'], $sample['customerPassword']);
        }
    }

    public function getAccountByEmail($customerEmail) {
        $stmt = $this->conn->prepare("SELECT * FROM $this->table WHERE customerEmail = ?");
        $stmt->execute([$customerEmail]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

// Initialize database connection
$db = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

// Create Account instance and add sample records if needed
$account = new Account($db);


?>
