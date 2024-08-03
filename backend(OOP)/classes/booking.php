<?php
require_once '../configs/db.php';

class Booking {
    private $conn;
    private $table = "bookings";

    // Variables with data types
    private int $id;
    private int $accountId;
    private int $parkingSlotId;
    private int $paymentId;
    private DateTime $bookingTime;
    private int $duration;

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

    public function getAccountId(): int {
        return $this->accountId;
    }

    public function setAccountId(int $accountId): void {
        $this->accountId = $accountId;
    }

    public function getpaymentId(): int {
        return $this->paymentId;
    }

    public function setpaymentId(int $paymentId): void {
        $this->paymentId = $paymentId;
    }

    public function getParkingSlotId(): int {
        return $this->parkingSlotId;
    }

    public function setParkingSlotId(int $parkingSlotId): void {
        $this->parkingSlotId = $parkingSlotId;
    }

    public function getBookingTime(): DateTime {
        return $this->bookingTime;
    }

    public function setBookingTime(DateTime $bookingTime): void {
        $this->bookingTime = $bookingTime;
    }

    public function getDuration(): int {
        return $this->duration;
    }

    public function setDuration(int $duration): void {
        $this->duration = $duration;
    }


    public function createBooking($accountId, $paymentId, $parkingSlotId, $bookingTime, $duration) {
        $stmt = $this->conn->prepare("INSERT INTO $this->table (accountId,paymentId, parkingSlotId, bookingTime, duration) VALUES (?, ?, ?, ?, ?)");
        if ($stmt->execute([$accountId, $paymentId, $parkingSlotId, $bookingTime, $duration])) {
            return ['success' => true, 'message' => 'Booking created'];
        }
        return ['success' => false, 'message' => 'Error: ' . $stmt->errorInfo()[2]];
    }

    public function getBookingDetails($bookingId) {
        $stmt = $this->conn->prepare("SELECT * FROM $this->table WHERE id = ?");
        $stmt->execute([$bookingId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getBookingDetailsByAccountId($accountId) {
        $stmt = $this->conn->prepare(
            "SELECT b.*, ps.slotName 
            FROM $this->table b 
            JOIN parking_slots ps ON b.parkingSlotId = ps.id 
            WHERE b.accountId = ?"
        );
        $stmt->execute([$accountId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateBooking($bookingId, $parkingSlotId, $bookingTime, $duration) {
        $stmt = $this->conn->prepare("UPDATE $this->table SET parkingSlotId = ?, bookingTime = ?, duration = ? WHERE id = ?");
        if ($stmt->execute([$parkingSlotId, $bookingTime, $duration, $bookingId])) {
            return ['success' => true, 'message' => 'Booking updated'];
        }
        return ['success' => false, 'message' => 'Error: ' . $stmt->errorInfo()[2]];
    }

    public function cancelBooking($bookingId) {
        $stmt = $this->conn->prepare("DELETE FROM $this->table WHERE id = ?");
        if ($stmt->execute([$bookingId])) {
            return ['success' => true, 'message' => 'Booking cancelled'];
        }
        return ['success' => false, 'message' => 'Error: ' . $stmt->errorInfo()[2]];
    }

}
?>
