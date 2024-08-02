<?php
require_once '../config/db.php';

class Invoice {
    private $pdo;
    private int $ID;
    private int $BookingID;
    private float $Amount;
    private int $UserID;
    private string $IssueDate;
    private string $DueDate;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    // Getters and Setters
    public function getID(): int {
        return $this->ID;
    }

    public function setID(int $ID): void {
        $this->ID = $ID;
    }

    public function getBookingID(): int {
        return $this->BookingID;
    }

    public function setBookingID(int $BookingID): void {
        $this->BookingID = $BookingID;
    }

    public function getAmount(): float {
        return $this->Amount;
    }

    public function setAmount(float $Amount): void {
        $this->Amount = $Amount;
    }

    public function getUserID(): int {
        return $this->UserID;
    }

    public function setUserID(int $UserID): void {
        $this->UserID = $UserID;
    }

    public function getIssueDate(): string {
        return $this->IssueDate;
    }

    public function setIssueDate(string $IssueDate): void {
        $this->IssueDate = $IssueDate;
    }

    public function getDueDate(): string {
        return $this->DueDate;
    }

    public function setDueDate(string $DueDate): void {
        $this->DueDate = $DueDate;
    }

    // Methods
    public function createInvoice(): void {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO invoices (userid, bookingid, amount, issuedate, duedate) VALUES (:userid, :bookingid, :amount, :issuedate, :duedate)");
            $stmt->execute([
                ':userid' => $this->UserID,
                ':bookingid' => $this->BookingID,
                ':amount' => $this->Amount,
                ':issuedate' => $this->IssueDate,
                ':duedate' => $this->DueDate
            ]);
            
            if ($stmt->rowCount()) {
                $this->ID = $this->pdo->lastInsertId();
                echo "Invoice created successfully";
            } else {
                echo "Failed to create invoice";
            }
        } catch (PDOException $e) {
            echo "Database error: " . $e->getMessage();
        }
    }

    public function getInvoiceDetails(): Invoice {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM invoices WHERE invoiceid = :invoiceid");
            $stmt->execute([':invoiceid' => $this->ID]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result) {
                $this->BookingID = $result['bookingid'];
                $this->Amount = $result['amount'];
                $this->UserID = $result['userid'];
                $this->IssueDate = $result['issuedate'];
                $this->DueDate = $result['duedate'];
            }
        } catch (PDOException $e) {
            echo "Database error: " . $e->getMessage();
        }
        return $this;
    }

    public function updateInvoice(): void {
        try {
            $stmt = $this->pdo->prepare("UPDATE invoices SET userid = :userid, bookingid = :bookingid, amount = :amount, issuedate = :issuedate, duedate = :duedate WHERE invoiceid = :invoiceid");
            $stmt->execute([
                ':userid' => $this->UserID,
                ':bookingid' => $this->BookingID,
                ':amount' => $this->Amount,
                ':issuedate' => $this->IssueDate,
                ':duedate' => $this->DueDate,
                ':invoiceid' => $this->ID
            ]);
            
            if ($stmt->rowCount()) {
                echo "Invoice updated successfully";
            } else {
                echo "No changes made or invoice not found";
            }
        } catch (PDOException $e) {
            echo "Database error: " . $e->getMessage();
        }
    }

    public function deleteInvoice(): void {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM invoices WHERE invoiceid = :invoiceid");
            $stmt->execute([':invoiceid' => $this->ID]);
            
            if ($stmt->rowCount()) {
                echo "Invoice deleted successfully";
                $this->ID = 0; // Reset the ID as the invoice no longer exists
            } else {
                echo "Invoice not found or already deleted";
            }
        } catch (PDOException $e) {
            echo "Database error: " . $e->getMessage();
        }
    }
}
?>
