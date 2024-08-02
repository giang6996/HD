<?php
require_once '../configs/db.php';

class Payment {
    private $conn;
    private $table = "payments";

    // Variables with data types
    private int $id;
    private float $amount;
    private int $accountId;
    private int $paymentMethodId;
    private int $invoiceId;
    private int $receiptId;

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

    public function getPaymentMethodId(): int {
        return $this->paymentMethodId;
    }

    public function setPaymentMethodId(int $paymentMethodId): void {
        $this->paymentMethodId = $paymentMethodId;
    }

    public function getInvoiceId(): int {
        return $this->invoiceId;
    }

    public function setInvoiceId(int $invoiceId): void {
        $this->invoiceId = $invoiceId;
    }

    public function getReceiptId(): int {
        return $this->receiptId;
    }

    public function setReceiptId(int $receiptId): void {
        $this->receiptId = $receiptId;
    }

    public function createPayment($amount, $accountId, $paymentMethodId, $invoiceId) {
        $stmt = $this->conn->prepare("INSERT INTO $this->table (amount, accountId, paymentMethodId, invoiceId) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$amount, $accountId, $paymentMethodId, $invoiceId])) {
            return ['success' => true, 'message' => 'Payment created'];
        }
        return ['success' => false, 'message' => 'Error: ' . $stmt->errorInfo()[2]];
    }

    public function getPaymentDetails($paymentId) {
        $stmt = $this->conn->prepare("SELECT * FROM $this->table WHERE id = ?");
        $stmt->execute([$paymentId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updatePayment($paymentId, $amount, $paymentMethodId) {
        $stmt = $this->conn->prepare("UPDATE $this->table SET amount = ?, paymentMethodId = ? WHERE id = ?");
        if ($stmt->execute([$amount, $paymentMethodId, $paymentId])) {
            return ['success' => true, 'message' => 'Payment updated'];
        }
        return ['success' => false, 'message' => 'Error: ' . $stmt->errorInfo()[2]];
    }

    public function deletePayment($paymentId) {
        $stmt = $this->conn->prepare("DELETE FROM $this->table WHERE id = ?");
        if ($stmt->execute([$paymentId])) {
            return ['success' => true, 'message' => 'Payment deleted'];
        }
        return ['success' => false, 'message' => 'Error: ' . $stmt->errorInfo()[2]];
    }

}
?>

