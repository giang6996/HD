<?php
require_once '../configs/db.php';
require_once '../classes/ParkingSlot.php';

class ParkingSlotController {
    private $db;
    private $parkingSlot;

    public function __construct() {
        global $pdo;
        $this->db = $pdo;
        $this->parkingSlot = new ParkingSlot($this->db);
    }

    // Create a new parking slot
    public function createParkingSlot($data) {
        if (!isset($data['slotName']) || !isset($data['slotTypeId']) || !isset($data['price']) || !isset($data['status'])) {
            return ['success' => false, 'message' => 'Missing required fields'];
        }

        return $this->parkingSlot->createParkingSlot($data['slotName'], $data['slotTypeId'], $data['status'], $data['price']);
    }

    // Get parking slot details by ID
    public function getParkingSlotDetails($id) {
        if (!isset($id)) {
            return ['success' => false, 'message' => 'Missing parking slot ID'];
        }

        $parkingSlotDetails = $this->parkingSlot->getParkingSlotDetails($id);
        if ($parkingSlotDetails) {
            return ['success' => true, 'data' => $parkingSlotDetails];
        } else {
            return ['success' => false, 'message' => 'Parking slot not found'];
        }
    }

    // Get all parking slots
    public function getParkingSlotAll() {
        $parkingSlots = $this->parkingSlot->getParkingSlotAll();
        if ($parkingSlots) {
            return ['success' => true, 'data' => $parkingSlots];
        } else {
            return ['success' => false, 'message' => 'No parking slots found'];
        }
    }

    public function getParkingSlotAvailable() {
        $parkingSlots = $this->parkingSlot->getParkingSlotAvailable();
        if ($parkingSlots) {
            return ['success' => true, 'data' => $parkingSlots];
        } else {
            return ['success' => false, 'message' => 'No parking slots found'];
        }
    }

    // Update a parking slot
    public function updateParkingSlot($id, $data) {
        if (!isset($id) || !isset($data['slotTypeId']) || !isset($data['price']) || !isset($data['status'])) {
            return ['success' => false, 'message' => 'Missing required fields'];
        }

        return $this->parkingSlot->updateParkingSlot($id, $data['slotTypeId'], $data['status'], $data['price']);
    }

    // Delete a parking slot
    public function deleteParkingSlot($id) {
        if (!isset($id)) {
            return ['success' => false, 'message' => 'Missing parking slot ID'];
        }

        return $this->parkingSlot->deleteParkingSlot($id);
    }

    public function updateAvailability($id, $status) {
        if (!isset($id) || !isset($status)) {
            return ['success' => false, 'message' => 'Missing required fields'];
        }

        return $this->parkingSlot->updateAvailability($id, $status);
    }
}
?>
