<?php
require_once '../configs/db.php';

class Receipt {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function generateReceipt($receiptDetails) {
        $stmt = $this->pdo->prepare("INSERT INTO receipts (paymentid, receiptdate, amount, details) VALUES (?, ?, ?, ?)");
        $stmt->execute([
            $receiptDetails['paymentid'],
            $receiptDetails['receiptdate'],
            $receiptDetails['amount'],
            $receiptDetails['details']
        ]);
        if ($stmt->rowCount()) {
            return ['success' => true, 'message' => 'Receipt generated successfully'];
        } else {
            return ['success' => false, 'message' => 'Failed to generate receipt'];
        }
    }

    public function getReceiptByPayment($paymentId) {
        $stmt = $this->pdo->prepare("SELECT * FROM receipts WHERE paymentid = ?");
        $stmt->execute([$paymentId]);
        return $stmt->fetch();
    }
}
?>
