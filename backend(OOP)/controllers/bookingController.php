<?php
require_once '../configs/db.php';
require_once '../classes/Booking.php';

class BookingController {
    private $db;
    private $booking;

    public function __construct() {
        global $pdo;
        $this->db = $pdo;
        $this->booking = new Booking($this->db);
    }

    // Create a new booking
    public function createBooking($data) {
        if (!isset($data['accountId']) || !isset($data['parkingSlotId']) || !isset($data['bookingTime']) || !isset($data['duration'])) {
            return ['success' => false, 'message' => 'Missing required fields'];
        }
        return $this->booking->createBooking($data['accountId'], $data['parkingSlotId'], $data['bookingTime'], $data['duration']);
    }

    // Get booking details by ID
    public function getBookingDetails($id) {
        if (!isset($id)) {
            return ['success' => false, 'message' => 'Missing booking ID'];
        }

        $bookingDetails = $this->booking->getBookingDetails($id);
        if ($bookingDetails) {
            return ['success' => true, 'data' => $bookingDetails];
        } else {
            return ['success' => false, 'message' => 'Booking not found'];
        }
    }

    // Get booking details by account ID
    public function getBookingDetailsByAccountId($accountId) {
        if (!isset($accountId)) {
            return ['success' => false, 'message' => 'Missing account ID'];
        }

        $bookingDetails = $this->booking->getBookingDetailsByAccountId($accountId);
        if ($bookingDetails) {
            return ['success' => true, 'data' => $bookingDetails];
        } else {
            return ['success' => false, 'message' => 'No bookings found for this account'];
        }
    }

    // Update a booking
    public function updateBooking($id, $data) {
        if (!isset($id) || !isset($data['parkingSlotId']) || !isset($data['bookingTime']) || !isset($data['duration'])) {
            return ['success' => false, 'message' => 'Missing required fields'];
        }

        $bookingTime = new DateTime($data['bookingTime']);
        return $this->booking->updateBooking($id, $data['parkingSlotId'], $bookingTime->format('Y-m-d H:i:s'), $data['duration']);
    }

    // Cancel a booking
    public function cancelBooking($id) {
        if (!isset($id)) {
            return ['success' => false, 'message' => 'Missing booking ID'];
        }

        return $this->booking->cancelBooking($id);
    }
}
?>
