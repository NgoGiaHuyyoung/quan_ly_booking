<?php
include('../inc/db_connect.php');
include('../api/utils.php');

// Xử lý yêu cầu API
$method = $_SERVER['REQUEST_METHOD'];
switch($method) {
    case 'POST':
        if (isset($_POST['login'])) {
            login();
        }
        break;
    case 'GET':
        if (isset($_GET['logout'])) {
            logout();
        }
        break;
}

function login() {
    global $conn;
    $data = json_decode(file_get_contents('php://input'), true);
    $username = $data['username'];
    $password = $data['password'];

    $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);

    if ($user) {
        echo json_encode(['message' => 'Login successful', 'user' => $user]);
    } else {
        echo json_encode(['message' => 'Invalid credentials']);
    }
}

function logout() {
    session_start();
    session_destroy();
    echo json_encode(['message' => 'Logout successful']);
}
?>
