<?php
require_once '../configs/db.php';
require_once '../classes/Payment.php';

class PaymentController {
    private $db;
    private $payment;

    public function __construct() {
        global $pdo;
        $this->db = $pdo;
        $this->payment = new Payment($this->db);
    }

    // Create a new payment
    public function createPayment($data) {
        if (!isset($data['amount']) || !isset($data['accountId'])) {
            return ['success' => false, 'message' => 'Missing required fields'];
        }

        return $this->payment->createPayment($data['amount'], $data['accountId']);
    }

    // Get payment details by ID
    public function getPaymentDetails($id) {
        if (!isset($id)) {
            return ['success' => false, 'message' => 'Missing payment ID'];
        }

        $paymentDetails = $this->payment->getPaymentDetails($id);
        if ($paymentDetails) {
            return ['success' => true, 'data' => $paymentDetails];
        } else {
            return ['success' => false, 'message' => 'Payment not found'];
        }
    }

    // Update a payment
    public function updatePayment($id, $data) {
        if (!isset($id) || !isset($data['amount'])) {
            return ['success' => false, 'message' => 'Missing required fields'];
        }

        return $this->payment->updatePayment($id, $data['amount']);
    }

    // Delete a payment
    public function deletePayment($id) {
        if (!isset($id)) {
            return ['success' => false, 'message' => 'Missing payment ID'];
        }

        return $this->payment->deletePayment($id);
    }
}
?>
