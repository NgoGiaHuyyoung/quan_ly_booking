<?php
include('../inc/db_connect.php');
header('Content-Type: application/json');

// Xử lý các hành động liên quan đến dịch vụ
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Lấy danh sách các dịch vụ
    $query = "SELECT * FROM services";
    $result = mysqli_query($conn, $query);
    $services = mysqli_fetch_all($result, MYSQLI_ASSOC);
    echo json_encode($services);

} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Thêm dịch vụ mới
    $service_name = $_POST['service_name'];
    $price = $_POST['price'];

    $query = "INSERT INTO services (service_name, price) 
              VALUES ('$service_name', '$price')";
    if (mysqli_query($conn, $query)) {
        echo json_encode(["message" => "Service added successfully."]);
    } else {
        echo json_encode(["error" => "Error adding service."]);
    }
}
?>
