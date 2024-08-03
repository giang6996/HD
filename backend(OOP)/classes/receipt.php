<?php
require_once '../configs/db.php';

class Receipt {
    private $conn;
    private $table = "receipts";

    // Variables with data types
    private int $id;
    private int $paymentId;
    private int $accountId;
    private DateTime $receiptDate;
    private float $amount;
    private DateTime $startDate;
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

    public function getPaymentId(): int {
        return $this->paymentId;
    }

    public function setPaymentId(int $paymentId): void {
        $this->paymentId = $paymentId;
    }

    public function getAccountId(): int {
        return $this->accountId;
    }

    public function setAccountId(int $accountId): void {
        $this->accountId = $accountId;
    }

    public function getReceiptDate(): DateTime {
        return $this->receiptDate;
    }

    public function setReceiptDate(DateTime $receiptDate): void {
        $this->receiptDate = $receiptDate;
    }

    public function getAmount(): float {
        return $this->amount;
    }

    public function setAmount(float $amount): void {
        $this->amount = $amount;
    }

    public function getStartDate(): DateTime {
        return $this->startDate;
    }

    public function setStartDate(DateTime $startDate): void {
        $this->startDate = $startDate;
    }

    public function getDuration(): int {
        return $this->duration;
    }

    public function setDuration(int $duration): void {
        $this->duration = $duration;
    }

    // Method to create a receipt
    public function createReceipt($paymentId, $accountId, $receiptDate, $amount, $startDate, $duration) {
        $stmt = $this->conn->prepare("INSERT INTO $this->table (paymentId, accountId, receiptDate, amount, startDate, duration) VALUES (?, ?, ?, ?, ?, ?)");
        if ($stmt->execute([$paymentId, $accountId, $receiptDate->format('Y-m-d'), $amount, $startDate->format('Y-m-d'), $duration])) {
            return ['success' => true, 'message' => 'Receipt created', 'receiptId' => $this->conn->lastInsertId()];
        }
        return ['success' => false, 'message' => 'Error: ' . $stmt->errorInfo()[2]];
    }

    // Method to get receipt details by ID
    public function getReceiptDetails($paymentId) {
        $stmt = $this->conn->prepare(
            "SELECT r.*, a.customerName, p.amount as paymentAmount, p.paymentDate
            FROM $this->table r
            JOIN accounts a ON r.accountId = a.id
            JOIN payments p ON r.paymentId = p.id
            WHERE r.paymentId = ?"
        );
        $stmt->execute([$paymentId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Method to delete a receipt by ID
    public function deleteReceipt($receiptId) {
        $stmt = $this->conn->prepare("DELETE FROM $this->table WHERE id = ?");
        if ($stmt->execute([$receiptId])) {
            return ['success' => true, 'message' => 'Receipt deleted'];
        }
        return ['success' => false, 'message' => 'Error: ' . $stmt->errorInfo()[2]];
    }
}
?>
