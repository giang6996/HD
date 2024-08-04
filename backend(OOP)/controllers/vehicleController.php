<?php
require_once '../configs/db.php';
require_once '../classes/Vehicle.php';

class VehicleController {
    private $db;
    private $vehicle;

    public function __construct() {
        global $pdo;
        $this->db = $pdo;
        $this->vehicle = new Vehicle($this->db);
    }

    // Create a new vehicle
    public function createVehicle($data) {
        if (!isset($data['vehicleTypeId']) || !isset($data['vehicleName']) || !isset($data['licensePlate']) || !isset($data['accountId'])) {
            return ['success' => false, 'message' => 'Missing required fields'];
        }

        return $this->vehicle->createVehicle($data['vehicleTypeId'], $data['vehicleName'], $data['licensePlate'], $data['accountId']);
    }

    // Get vehicle details by ID
    public function getVehicleDetails($id) {
        if (!isset($id)) {
            return ['success' => false, 'message' => 'Missing vehicle ID'];
        }

        $vehicleDetails = $this->vehicle->getVehicleDetails($id);
        if ($vehicleDetails) {
            return ['success' => true, 'data' => $vehicleDetails];
        } else {
            return ['success' => false, 'message' => 'Vehicle not found'];
        }
    }

    // Get vehicles by account ID
    public function getVehiclesByAccountId($accountId) {
        if (!isset($accountId)) {
            return ['success' => false, 'message' => 'Missing account ID'];
        }

        $vehicles = $this->vehicle->getVehiclesByAccountId($accountId);
        if ($vehicles) {
            return ['success' => true, 'data' => $vehicles];
        } else {
            return ['success' => false, 'message' => 'No vehicles found'];
        }
    }

    // Update a vehicle
    public function updateVehicle($id, $data) {
        if (!isset($id) || !isset($data['vehicleTypeId']) || !isset($data['vehicleName'])) {
            return ['success' => false, 'message' => 'Missing required fields'];
        }

        return $this->vehicle->updateVehicle($id, $data['vehicleTypeId'], $data['vehicleName']);
    }

    // Delete a vehicle
    public function deleteVehicle($id) {
        if (!isset($id)) {
            return ['success' => false, 'message' => 'Missing vehicle ID'];
        }

        return $this->vehicle->deleteVehicle($id);
    }

    // Get vehicle types
    public function getVehicleTypes() {
        $vehicleTypes = $this->vehicle->getVehicleTypes();
        if ($vehicleTypes) {
            return ['success' => true, 'data' => $vehicleTypes];
        } else {
            return ['success' => false, 'message' => 'No vehicle types found'];
        }
    }
}
?>
