<?php
include('../inc/db_connect.php');
include('../api/utils.php');

$method = $_SERVER['REQUEST_METHOD'];
switch($method) {
    case 'GET':
        getRooms();
        break;
    case 'POST':
        addRoom();
        break;
    case 'PUT':
        updateRoom();
        break;
    case 'DELETE':
        deleteRoom();
        break;
}

function getRooms() {
    global $conn;
    $query = "SELECT * FROM rooms";
    $result = mysqli_query($conn, $query);
    $rooms = mysqli_fetch_all($result, MYSQLI_ASSOC);
    echo json_encode($rooms);
}

function addRoom() {
    global $conn;
    $data = json_decode(file_get_contents('php://input'), true);
    $room_name = $data['room_name'];
    $room_type = $data['room_type'];
    $price = $data['price'];

    $query = "INSERT INTO rooms (room_name, room_type, price) VALUES ('$room_name', '$room_type', '$price')";
    if (mysqli_query($conn, $query)) {
        echo json_encode(['message' => 'Room added successfully']);
    } else {
        echo json_encode(['message' => 'Error adding room']);
    }
}

function updateRoom() {
    global $conn;
    $data = json_decode(file_get_contents('php://input'), true);
    $room_id = $data['room_id'];
    $room_name = $data['room_name'];
    $price = $data['price'];

    $query = "UPDATE rooms SET room_name = '$room_name', price = '$price' WHERE id = '$room_id'";
    if (mysqli_query($conn, $query)) {
        echo json_encode(['message' => 'Room updated successfully']);
    } else {
        echo json_encode(['message' => 'Error updating room']);
    }
}

function deleteRoom() {
    global $conn;
    $data = json_decode(file_get_contents('php://input'), true);
    $room_id = $data['room_id'];

    $query = "DELETE FROM rooms WHERE id = '$room_id'";
    if (mysqli_query($conn, $query)) {
        echo json_encode(['message' => 'Room deleted successfully']);
    } else {
        echo json_encode(['message' => 'Error deleting room']);
    }
}
?>
