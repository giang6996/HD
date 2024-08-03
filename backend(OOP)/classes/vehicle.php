<?php
require_once '../configs/db.php';

class Vehicle {
    private $conn;
    private $table = "vehicles";

    // Variables with data types
    private int $id;
    private int $vehicleTypeId;
    private string $vehicleName;
    private int $accountId;

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

    public function getVehicleTypeId(): int {
        return $this->vehicleTypeId;
    }

    public function setVehicleTypeId(int $vehicleTypeId): void {
        $this->vehicleTypeId = $vehicleTypeId;
    }

    public function getVehicleName(): string {
        return $this->vehicleName;
    }

    public function setVehicleName(string $vehicleName): void {
        $this->vehicleName = $vehicleName;
    }

    public function getAccountId(): int {
        return $this->accountId;
    }

    public function setAccountId(int $accountId): void {
        $this->accountId = $accountId;
    }

    // Method to create a vehicle
    public function createVehicle($vehicleTypeId, $vehicleName, $accountId) {
        $stmt = $this->conn->prepare("INSERT INTO $this->table (vehicleTypeId, vehicleName, accountId) VALUES (?, ?, ?)");
        if ($stmt->execute([$vehicleTypeId, $vehicleName, $accountId])) {
            return ['success' => true, 'message' => 'Vehicle created', 'vehicleId' => $this->conn->lastInsertId()];
        }
        return ['success' => false, 'message' => 'Error: ' . $stmt->errorInfo()[2]];
    }

    // Method to get vehicle details by ID
    public function getVehicleDetails($vehicleId) {
        $stmt = $this->conn->prepare("SELECT v.*, t.typename FROM $this->table v JOIN types t ON v.vehicleTypeId = t.typeid WHERE v.id = ?");
        $stmt->execute([$vehicleId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Method to get vehicles by account ID
    public function getVehiclesByAccountId($accountId) {
        $stmt = $this->conn->prepare("SELECT v.*, t.typename FROM $this->table v JOIN types t ON v.vehicleTypeId = t.typeid WHERE v.accountId = ?");
        $stmt->execute([$accountId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Method to update a vehicle
    public function updateVehicle($vehicleId, $vehicleTypeId, $vehicleName) {
        $stmt = $this->conn->prepare("UPDATE $this->table SET vehicleTypeId = ?, vehicleName = ? WHERE id = ?");
        if ($stmt->execute([$vehicleTypeId, $vehicleName, $vehicleId])) {
            return ['success' => true, 'message' => 'Vehicle updated'];
        }
        return ['success' => false, 'message' => 'Error: ' . $stmt->errorInfo()[2]];
    }

    // Method to delete a vehicle
    public function deleteVehicle($vehicleId) {
        $stmt = $this->conn->prepare("DELETE FROM $this->table WHERE id = ?");
        if ($stmt->execute([$vehicleId])) {
            return ['success' => true, 'message' => 'Vehicle deleted'];
        }
        return ['success' => false, 'message' => 'Error: ' . $stmt->errorInfo()[2]];
    }

    // Method to get vehicle types from the types table
    public function getVehicleTypes() {
        $stmt = $this->conn->prepare("SELECT * FROM types");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
