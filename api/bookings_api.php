<?php
include('../inc/db_connect.php');
include('../api/utils.php');

// Xử lý yêu cầu API
$method = $_SERVER['REQUEST_METHOD'];
switch($method) {
    case 'GET':
        getBookings();
        break;
    case 'POST':
        createBooking();
        break;
    case 'PUT':
        updateBooking();
        break;
    case 'DELETE':
        deleteBooking();
        break;
}

function getBookings() {
    global $conn;
    $query = "SELECT * FROM bookings";
    $result = mysqli_query($conn, $query);
    $bookings = mysqli_fetch_all($result, MYSQLI_ASSOC);
    echo json_encode($bookings);
}

function createBooking() {
    global $conn;
    $data = json_decode(file_get_contents('php://input'), true);
    $room_id = $data['room_id'];
    $customer_id = $data['customer_id'];
    $check_in = $data['check_in'];
    $check_out = $data['check_out'];

    $query = "INSERT INTO bookings (room_id, customer_id, check_in, check_out) VALUES ('$room_id', '$customer_id', '$check_in', '$check_out')";
    if (mysqli_query($conn, $query)) {
        echo json_encode(['message' => 'Booking created successfully']);
    } else {
        echo json_encode(['message' => 'Error creating booking']);
    }
}

function updateBooking() {
    global $conn;
    $data = json_decode(file_get_contents('php://input'), true);
    $booking_id = $data['booking_id'];
    $check_in = $data['check_in'];
    $check_out = $data['check_out'];

    $query = "UPDATE bookings SET check_in = '$check_in', check_out = '$check_out' WHERE id = '$booking_id'";
    if (mysqli_query($conn, $query)) {
        echo json_encode(['message' => 'Booking updated successfully']);
    } else {
        echo json_encode(['message' => 'Error updating booking']);
    }
}

function deleteBooking() {
    global $conn;
    $data = json_decode(file_get_contents('php://input'), true);
    $booking_id = $data['booking_id'];

    $query = "DELETE FROM bookings WHERE id = '$booking_id'";
    if (mysqli_query($conn, $query)) {
        echo json_encode(['message' => 'Booking deleted successfully']);
    } else {
        echo json_encode(['message' => 'Error deleting booking']);
    }
}
?>
