<?php
include('../inc/db_connect.php');
include('../api/utils.php');

$method = $_SERVER['REQUEST_METHOD'];
switch($method) {
    case 'GET':
        getStaff();
        break;
    case 'POST':
        addStaff();
        break;
    case 'PUT':
        updateStaff();
        break;
    case 'DELETE':
        deleteStaff();
        break;
}

function getStaff() {
    global $conn;
    $query = "SELECT * FROM staff";
    $result = mysqli_query($conn, $query);
    $staff = mysqli_fetch_all($result, MYSQLI_ASSOC);
    echo json_encode($staff);
}

function addStaff() {
    global $conn;
    $data = json_decode(file_get_contents('php://input'), true);
    $name = $data['name'];
    $position = $data['position'];

    $query = "INSERT INTO staff (name, position) VALUES ('$name', '$position')";
    if (mysqli_query($conn, $query)) {
        echo json_encode(['message' => 'Staff added successfully']);
    } else {
        echo json_encode(['message' => 'Error adding staff']);
    }
}

function updateStaff() {
    global $conn;
    $data = json_decode(file_get_contents('php://input'), true);
    $staff_id = $data['staff_id'];
    $name = $data['name'];
    $position = $data['position'];

    $query = "UPDATE staff SET name = '$name', position = '$position' WHERE id = '$staff_id'";
    if (mysqli_query($conn, $query)) {
        echo json_encode(['message' => 'Staff updated successfully']);
    } else {
        echo json_encode(['message' => 'Error updating staff']);
    }
}

function deleteStaff() {
    global $conn;
    $data = json_decode(file_get_contents('php://input'), true);
    $staff_id = $data['staff_id'];

    $query = "DELETE FROM staff WHERE id = '$staff_id'";
    if (mysqli_query($conn, $query)) {
        echo json_encode(['message' => 'Staff deleted successfully']);
    } else {
        echo json_encode(['message' => 'Error deleting staff']);
    }
}
?>
