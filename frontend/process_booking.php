<?php
require('inc/db_config.php'); // Kết nối database

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $roomId = $_POST['roomId'];
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $checkIn = $_POST['checkIn'];
    $checkOut = $_POST['checkOut'];

    // Kiểm tra dữ liệu
    if (empty($roomId) || empty($name) || empty($phone) || empty($checkIn) || empty($checkOut)) {
        die('Please fill in all required fields.');
    }

    // Thêm booking vào database
    $stmt = $conn->prepare("INSERT INTO bookings (room_id, name, phone, address, check_in, check_out) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssss", $roomId, $name, $phone, $address, $checkIn, $checkOut);

    if ($stmt->execute()) {
        echo "<script>alert('Booking successful!'); window.location.href = 'rooms_details.php';</script>";
    } else {
        echo "<script>alert('Failed to book the room. Please try again.'); window.history.back();</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
