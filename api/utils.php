<?php
// Kết nối cơ sở dữ liệu
function getDbConnection() {
    $host = 'localhost'; // Địa chỉ máy chủ MySQL
    $username = 'root';  // Tên đăng nhập
    $password = '';      // Mật khẩu
    $dbname = 'hotel_management'; // Tên cơ sở dữ liệu

    $conn = new mysqli($host, $username, $password, $dbname);

    if ($conn->connect_error) {
        die(json_encode(['message' => 'Database connection failed: ' . $conn->connect_error]));
    }
    return $conn;
}

// Hàm trả về kết quả dưới dạng JSON
function jsonResponse($data, $status = 200) {
    header('Content-Type: application/json');
    http_response_code($status);
    echo json_encode($data);
}

// Kiểm tra xác thực người dùng (JWT)
function verifyJwtToken($jwt) {
    $secret_key = "your_secret_key"; // Khóa bí mật để mã hóa và giải mã JWT

    // Kiểm tra nếu không có JWT trong header
    if (empty($jwt)) {
        jsonResponse(['message' => 'Token is missing'], 401);
        exit();
    }

    // Tách JWT ra thành 3 phần (header, payload, signature)
    list($header, $payload, $signature) = explode('.', $jwt);
    $decoded = base64_decode($payload);

    // Kiểm tra tính hợp lệ của JWT
    $payload_data = json_decode($decoded, true);
    if (isset($payload_data['exp']) && $payload_data['exp'] < time()) {
        jsonResponse(['message' => 'Token has expired'], 401);
        exit();
    }

    return $payload_data;
}

// Hàm kiểm tra quyền truy cập của người dùng (Quản trị viên, Khách hàng...)
function checkUserPermission($user_role, $required_role) {
    if ($user_role !== $required_role) {
        jsonResponse(['message' => 'Access denied'], 403);
        exit();
    }
}

// Hàm đăng nhập giả (chỉ dùng trong ví dụ, cần thay thế với cơ chế xác thực thực tế)
function validateUserCredentials($username, $password) {
    // Tạo một mảng giả để mô phỏng người dùng
    $users = [
        'admin' => ['password' => 'admin123', 'role' => 'admin'],
        'customer' => ['password' => 'customer123', 'role' => 'customer']
    ];

    if (isset($users[$username]) && $users[$username]['password'] === $password) {
        return $users[$username];
    } else {
        return false;
    }
}

// Hàm tạo JWT (Dành cho việc login)
function createJwtToken($username, $role) {
    $secret_key = "your_secret_key"; // Khóa bí mật để mã hóa JWT
    $issued_at = time();
    $expiration_time = $issued_at + 3600; // Token hết hạn sau 1 giờ
    $payload = [
        'sub' => $username,
        'role' => $role,
        'iat' => $issued_at,
        'exp' => $expiration_time
    ];

    // Mã hóa và tạo JWT
    $jwt = base64_encode(json_encode($payload));
    return $jwt;
}

// Hàm thoát (Logout) (Xóa session hoặc token)
function logout() {
    // Hủy session (đối với session-based auth)
    session_start();
    session_destroy();
    jsonResponse(['message' => 'Logout successful'], 200);
}

// Hàm xử lý lỗi (log lỗi vào file log)
function logError($message) {
    // Bạn có thể viết lỗi vào một file log
    $error_log = __DIR__ . '/../logs/error_log.txt'; // Đường dẫn tới file log
    $time = date('Y-m-d H:i:s');
    file_put_contents($error_log, "[$time] - $message\n", FILE_APPEND);
}

// Hàm kiểm tra xem người dùng đã đăng nhập chưa (dành cho session-based auth)
function isUserLoggedIn() {
    session_start();
    if (isset($_SESSION['user_id'])) {
        return true;
    }
    return false;
}

?>
