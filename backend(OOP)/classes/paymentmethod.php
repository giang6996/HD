<?php
require_once '../config/db.php';


class PaymentMethod {
    private $conn;
    private $table = "payment_methods";

    // Variables with data types
    private int $id;
    private string $methodType;
    private string $details;

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

    public function getMethodType(): string {
        return $this->methodType;
    }

    public function setMethodType(string $methodType): void {
        $this->methodType = $methodType;
    }

    public function getDetails(): string {
        return $this->details;
    }

    public function setDetails(string $details): void {
        $this->details = $details;
    }

    public function createPaymentMethod($methodType, $details) {
        $stmt = $this->conn->prepare("INSERT INTO $this->table (methodType, details) VALUES (?, ?)");
        if ($stmt->execute([$methodType, $details])) {
            return ['success' => true, 'message' => 'Payment method created'];
        }
        return ['success' => false, 'message' => 'Error: ' . $stmt->errorInfo()[2]];
    }

    public function getPaymentMethodDetails($paymentMethodId) {
        $stmt = $this->conn->prepare("SELECT * FROM $this->table WHERE id = ?");
        $stmt->execute([$paymentMethodId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updatePaymentMethod($paymentMethodId, $methodType, $details) {
        $stmt = $this->conn->prepare("UPDATE $this->table SET methodType = ?, details = ? WHERE id = ?");
        if ($stmt->execute([$methodType, $details, $paymentMethodId])) {
            return ['success' => true, 'message' => 'Payment method updated'];
        }
        return ['success' => false, 'message' => 'Error: ' . $stmt->errorInfo()[2]];
    }

    public function deletePaymentMethod($paymentMethodId) {
        $stmt = $this->conn->prepare("DELETE FROM $this->table WHERE id = ?");
        if ($stmt->execute([$paymentMethodId])) {
            return ['success' => true, 'message' => 'Payment method deleted'];
        }
        return ['success' => false, 'message' => 'Error: ' . $stmt->errorInfo()[2]];
    }
}

?>
