<?php
require_once '../config/db.php';

class Account {
    private $conn;
    private $table = "accounts";

    // Variables with data types
    private int $id;
    private string $customerName;
    private string $customerEmail;
    private string $customerPhone;
    private int $vehicleId;

    public function __construct($db) {
        $this->conn = $db;
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
        return $this->customerPhone;
    }

    public function setCustomerPhone(string $customerPhone): void {
        $this->customerPhone = $customerPhone;
    }

    public function getVehicleId(): int {
        return $this->vehicleId;
    }

    public function setVehicleId(int $vehicleId): void {
        $this->vehicleId = $vehicleId;
    }

    // CRUD operations
    public function createAccount($customerName, $customerEmail, $customerPhone) {
        $stmt = $this->conn->prepare("INSERT INTO $this->table (customerName, customerEmail, customerPhone) VALUES (?, ?, ?)");
        if ($stmt->execute([$customerName, $customerEmail, $customerPhone])) {
            return ['success' => true, 'message' => 'Account created'];
        }
        return ['success' => false, 'message' => 'Error: ' . $stmt->errorInfo()[2]];
    }

    public function getAccountDetails($accountId) {
        $stmt = $this->conn->prepare("SELECT * FROM $this->table WHERE id = ?");
        $stmt->execute([$accountId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateAccount($accountId, $customerName, $customerEmail, $customerPhone) {
        $stmt = $this->conn->prepare("UPDATE $this->table SET customerName = ?, customerEmail = ?, customerPhone = ? WHERE id = ?");
        if ($stmt->execute([$customerName, $customerEmail, $customerPhone, $accountId])) {
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
}
?>
