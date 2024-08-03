<?php
require_once '../configs/db.php';
require_once '../classes/Receipt.php';

class ReceiptController {
    private $db;
    private $receipt;

    public function __construct() {
        global $pdo;
        $this->db = $pdo;
        $this->receipt = new Receipt($this->db);
    }

    // Create a new receipt
    public function createReceipt($data) {
        if (!isset($data['paymentId']) || !isset($data['accountId']) || !isset($data['receiptDate']) || !isset($data['amount']) || !isset($data['startDate']) || !isset($data['duration'])) {
            return ['success' => false, 'message' => 'Missing required fields'];
        }

        $receiptDate = new DateTime($data['receiptDate']);
        $startDate = new DateTime($data['startDate']);
        return $this->receipt->createReceipt($data['paymentId'], $data['accountId'], $receiptDate, $data['amount'], $startDate, $data['duration']);
    }

    // Get receipt details by ID
    public function getReceiptDetails($id) {
        if (!isset($id)) {
            return ['success' => false, 'message' => 'Missing receipt ID'];
        }

        $receiptDetails = $this->receipt->getReceiptDetails($id);
        if ($receiptDetails) {
            return ['success' => true, 'data' => $receiptDetails];
        } else {
            return ['success' => false, 'message' => 'Receipt not found'];
        }
    }

    // Delete a receipt by ID
    public function deleteReceipt($id) {
        if (!isset($id)) {
            return ['success' => false, 'message' => 'Missing receipt ID'];
        }

        return $this->receipt->deleteReceipt($id);
    }
}
?>
