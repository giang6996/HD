<?php
require_once '../configs/db.php';

class Payment {
    private $conn;
    private $table = "payments";

    // Variables with data types
    private int $id;
    private float $amount;
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

    public function getAmount(): float {
        return $this->amount;
    }

    public function setAmount(float $amount): void {
        $this->amount = $amount;
    }

    public function getAccountId(): int {
        return $this->accountId;
    }

    public function setAccountId(int $accountId): void {
        $this->accountId = $accountId;
    }

    // Create a new payment
    public function createPayment($amount, $accountId) {
        $stmt = $this->conn->prepare("INSERT INTO $this->table (amount, accountId) VALUES (?, ?)");
        if ($stmt->execute([$amount, $accountId])) {
            return ['success' => true, 'message' => 'Payment created'];
        }
        return ['success' => false, 'message' => 'Error: ' . $stmt->errorInfo()[2]];
    }

    // Get payment details by ID
    public function getPaymentDetails($paymentId) {
        $stmt = $this->conn->prepare(
            "SELECT p.*, a.customerName
            FROM $this->table p
            JOIN accounts a ON p.accountId = a.id
            WHERE p.id = ?"
        );
        $stmt->execute([$paymentId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Update a payment
    public function updatePayment($paymentId, $amount) {
        $stmt = $this->conn->prepare("UPDATE $this->table SET amount = ? WHERE id = ?");
        if ($stmt->execute([$amount, $paymentId])) {
            return ['success' => true, 'message' => 'Payment updated'];
        }
        return ['success' => false, 'message' => 'Error: ' . $stmt->errorInfo()[2]];
    }

    // Delete a payment
    public function deletePayment($paymentId) {
        $stmt = $this->conn->prepare("DELETE FROM $this->table WHERE id = ?");
        if ($stmt->execute([$paymentId])) {
            return ['success' => true, 'message' => 'Payment deleted'];
        }
        return ['success' => false, 'message' => 'Error: ' . $stmt->errorInfo()[2]];
    }
}
?>
