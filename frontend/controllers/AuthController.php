// File: controllers/AuthController.php

require 'vendor/autoload.php';
use \Firebase\JWT\JWT;

class AuthController {
    private $secret_key = 'your-secret-key';

    // Middleware kiểm tra token
    public function verifyToken($token) {
        try {
            // Giải mã token và trả về dữ liệu nếu hợp lệ
            $decoded = JWT::decode($token, $this->secret_key, ['HS256']);
            return $decoded;
        } catch (Exception $e) {
            // Nếu không thể giải mã token, trả về null
            return null;
        }
    }

    // Endpoint xác minh token
    public function verifyTokenEndpoint() {
        // Lấy tất cả headers của yêu cầu
        $headers = getallheaders();
        
        // Lấy header Authorization từ request
        $authHeader = $headers['Authorization'] ?? '';
        
        // Lấy token từ header Authorization (loại bỏ 'Bearer ' nếu có)
        $token = str_replace('Bearer ', '', $authHeader);

        // Kiểm tra và giải mã token
        $decoded = $this->verifyToken($token);
        
        // Nếu token hợp lệ, trả về thông tin user, ngược lại trả về lỗi
        if ($decoded) {
            echo json_encode(['success' => true, 'user' => $decoded]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid or expired token']);
        }
    }
}

// Kiểm tra request method và URI
if ($_SERVER['REQUEST_METHOD'] === 'GET' && $_SERVER['REQUEST_URI'] === '/api/verify-token') {
    // Tạo đối tượng AuthController và gọi phương thức xác minh token
    $authController = new AuthController();
    $authController->verifyTokenEndpoint();
}
