<?php
require_once '../configs/db.php';
require_once '../classes/Account.php';

class AccountController {
    private $db;
    private $account;

    public function __construct() {
        global $pdo;
        $this->db = $pdo;
        $this->account = new Account($this->db);
    }

    // Create a new account
    public function createAccount($data) {
        if (!isset($data['customerName']) || !isset($data['customerEmail']) || !isset($data['customerPassword'])) {
            return ['success' => false, 'message' => 'Missing required fields'];
        }

        return $this->account->createAccount($data['customerName'], $data['customerEmail'], $data['customerPassword']);
    }

    // Get account details by ID
    public function getAccountDetails($id) {
        if (!isset($id)) {
            return ['success' => false, 'message' => 'Missing account ID'];
        }

        $accountDetails = $this->account->getAccountDetails($id);
        if ($accountDetails) {
            return ['success' => true, 'data' => $accountDetails];
        } else {
            return ['success' => false, 'message' => 'Account not found'];
        }
    }

    // Update an account
    public function updateAccount($id, $data) {
        if (!isset($id) || !isset($data['customerName']) || !isset($data['customerEmail']) || !isset($data['customerPassword'])) {
            return ['success' => false, 'message' => 'Missing required fields'];
        }

        return $this->account->updateAccount($id, $data['customerName'], $data['customerEmail'], $data['customerPassword']);
    }

    // Delete an account
    public function deleteAccount($id) {
        if (!isset($id)) {
            return ['success' => false, 'message' => 'Missing account ID'];
        }

        return $this->account->deleteAccount($id);
    }

    public function login($data) {
        if (!isset($data['customerEmail']) || !isset($data['customerPassword'])) {
            return ['success' => false, 'message' => 'Missing required fields'];
        }

        $accountDetails = $this->account->getAccountByEmail($data['customerEmail']);
        if ($accountDetails && $data['customerPassword'] == $accountDetails['customerPassword']) {
            return ['success' => true, 'message' => 'Login successful', 'data' => $accountDetails];
        } else {
            return ['success' => false, 'message' => 'Invalid email or password'];
        }
    }
}

?>