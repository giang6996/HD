<?php
require_once '../configs/db.php';

class Invoice {
    private $conn;
    private $table = "invoices";

    // Variables with data types
    private int $id;
    private int $accountId;
    private int $parkingSlotId;
    private DateTime $bookingDate;
    private int $duration;
    private float $amount;

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

    public function getParkingSlotId(): int {
        return $this->parkingSlotId;
    }

    public function setParkingSlotId(int $parkingSlotId): void {
        $this->parkingSlotId = $parkingSlotId;
    }

    public function getBookingDate(): DateTime {
        return $this->bookingDate;
    }

    public function setBookingDate(DateTime $bookingDate): void {
        $this->bookingDate = $bookingDate;
    }

    public function getDuration(): int {
        return $this->duration;
    }

    public function setDuration(int $duration): void {
        $this->duration = $duration;
    }

    public function getAmount(): float {
        return $this->amount;
    }

    public function setAmount(float $amount): void {
        $this->amount = $amount;
    }

    // Method to create an invoice
    public function createInvoice($accountId, $parkingSlotId, $bookingDate, $duration, $amount) {
        $stmt = $this->conn->prepare("INSERT INTO $this->table (accountId, parkingSlotId, bookingDate, duration, amount) VALUES (?, ?, ?, ?, ?)");
        if ($stmt->execute([$accountId, $parkingSlotId, $bookingDate->format('Y-m-d H:i:s'), $duration, $amount * $duration])) {
            return ['success' => true, 'message' => 'Invoice created', 'invoiceId' => $this->conn->lastInsertId()];
        }
        return ['success' => false, 'message' => 'Error: ' . $stmt->errorInfo()[2]];
    }

    // Method to get invoice details by ID
    public function getInvoiceDetails($invoiceId) {
        $stmt = $this->conn->prepare(
            "SELECT i.id as invoiceId, i.parkingSlotId, u.customerName, ps.slotName as itemName, i.bookingDate as bookDate, i.duration, i.amount
            FROM $this->table i
            JOIN accounts u ON i.accountId = u.id
            JOIN parking_slots ps ON i.parkingSlotId = ps.id
            WHERE i.id = ?"
        );
        $stmt->execute([$invoiceId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Method to get invoice details by user ID
    public function getUserInvoice($accountId) {
        $stmt = $this->conn->prepare(
            "SELECT i.id as invoiceId, i.parkingSlotId, u.customerName, ps.slotName as itemName, i.bookingDate as bookDate, i.duration, i.amount
            FROM $this->table i
            JOIN accounts u ON i.accountId = u.id
            JOIN parking_slots ps ON i.parkingSlotId = ps.id
            WHERE i.accountId = ? LIMIT 1"
        );
        $stmt->execute([$accountId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function deleteInvoice($invoiceId) {
        $stmt = $this->conn->prepare("DELETE FROM $this->table WHERE id = ?");
        if ($stmt->execute([$invoiceId])) {
            return ['success' => true, 'message' => 'Invoice deleted'];
        }
        return ['success' => false, 'message' => 'Error: ' . $stmt->errorInfo()[2]];
    }
}
?>
