<?php
require_once '../configs/db.php';
require_once '../classes/Invoice.php';

class InvoiceController {
    private $db;
    private $invoice;

    public function __construct() {
        global $pdo;
        $this->db = $pdo;
        $this->invoice = new Invoice($this->db);
    }

    // Create a new invoice
    public function createInvoice($data) {
        if (!isset($data['accountId']) || !isset($data['parkingSlotId']) || !isset($data['bookingDate']) || !isset($data['duration'])) {
            return ['success' => false, 'message' => 'Missing required fields'];
        }

        // Check if the user already has an existing invoice
        $existingInvoice = $this->invoice->getUserInvoice($data['accountId']);
        if ($existingInvoice) {
            return ['success' => false, 'message' => 'You already have an existing invoice. Please complete the payment first.'];
        }

        $bookingDate = new DateTime($data['bookingDate']);
        return $this->invoice->createInvoice($data['accountId'], $data['parkingSlotId'], $bookingDate, $data['duration']);
    }

    // Get invoice details by ID
    public function getInvoiceDetails($id) {
        if (!isset($id)) {
            return ['success' => false, 'message' => 'Missing invoice ID'];
        }

        $invoiceDetails = $this->invoice->getInvoiceDetails($id);
        if ($invoiceDetails) {
            return ['success' => true, 'data' => $invoiceDetails];
        } else {
            return ['success' => false, 'message' => 'Invoice not found'];
        }
    }

    // Get invoice details by user ID
    public function getUserInvoice($accountId) {
        if (!isset($accountId)) {
            return ['success' => false, 'message' => 'Missing account ID'];
        }

        $invoiceDetails = $this->invoice->getUserInvoice($accountId);
        if ($invoiceDetails) {
            return ['success' => true, 'data' => $invoiceDetails];
        } else {
            return ['success' => false, 'message' => 'Invoice not found'];
        }
    }

    public function deleteInvoice($id) {
        if (!isset($id)) {
            return ['success' => false, 'message' => 'Missing invoice ID'];
        }

        return $this->invoice->deleteInvoice($id);
    }
}
?>
