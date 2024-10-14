<?php
include('../inc/db_connect.php');
include('../api/utils.php');

$method = $_SERVER['REQUEST_METHOD'];
switch($method) {
    case 'POST':
        processPayment();
        break;
    case 'GET':
        getPayments();
        break;
}

function processPayment() {
    global $conn;
    $data = json_decode(file_get_contents('php://input'), true);
    $booking_id = $data['booking_id'];
    $amount = $data['amount'];

    $query = "INSERT INTO payments (booking_id, amount) VALUES ('$booking_id', '$amount')";
    if (mysqli_query($conn, $query)) {
        echo json_encode(['message' => 'Payment processed successfully']);
    } else {
        echo json_encode(['message' => 'Error processing payment']);
    }
}

function getPayments() {
    global $conn;
    $query = "SELECT * FROM payments";
    $result = mysqli_query($conn, $query);
    $payments = mysqli_fetch_all($result, MYSQLI_ASSOC);
    echo json_encode($payments);
}
?>
