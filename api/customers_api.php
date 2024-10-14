<?php
include('../inc/db_connect.php');
include('../api/utils.php');

$method = $_SERVER['REQUEST_METHOD'];
switch($method) {
    case 'GET':
        getCustomers();
        break;
    case 'POST':
        addCustomer();
        break;
    case 'PUT':
        updateCustomer();
        break;
    case 'DELETE':
        deleteCustomer();
        break;
}

function getCustomers() {
    global $conn;
    $query = "SELECT * FROM customers";
    $result = mysqli_query($conn, $query);
    $customers = mysqli_fetch_all($result, MYSQLI_ASSOC);
    echo json_encode($customers);
}

function addCustomer() {
    global $conn;
    $data = json_decode(file_get_contents('php://input'), true);
    $name = $data['name'];
    $email = $data['email'];

    $query = "INSERT INTO customers (name, email) VALUES ('$name', '$email')";
    if (mysqli_query($conn, $query)) {
        echo json_encode(['message' => 'Customer added successfully']);
    } else {
        echo json_encode(['message' => 'Error adding customer']);
    }
}

function updateCustomer() {
    global $conn;
    $data = json_decode(file_get_contents('php://input'), true);
    $customer_id = $data['customer_id'];
    $name = $data['name'];
    $email = $data['email'];

    $query = "UPDATE customers SET name = '$name', email = '$email' WHERE id = '$customer_id'";
    if (mysqli_query($conn, $query)) {
        echo json_encode(['message' => 'Customer updated successfully']);
    } else {
        echo json_encode(['message' => 'Error updating customer']);
    }
}

function deleteCustomer() {
    global $conn;
    $data = json_decode(file_get_contents('php://input'), true);
    $customer_id = $data['customer_id'];

    $query = "DELETE FROM customers WHERE id = '$customer_id'";
    if (mysqli_query($conn, $query)) {
        echo json_encode(['message' => 'Customer deleted successfully']);
    } else {
        echo json_encode(['message' => 'Error deleting customer']);
    }
}
?>
