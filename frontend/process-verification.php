<?php
// Xác nhận mã xác minh từ yêu cầu POST
$data = json_decode(file_get_contents('php://input'), true);

// Kiểm tra mã xác minh
if (!isset($data['code']) || empty($data['code'])) {
    echo json_encode(['success' => false, 'message' => 'Verification code is required.']);
    exit;
}

$verificationCode = $data['code'];

// Gửi yêu cầu đến API backend để xử lý mã xác minh
$apiUrl = 'http://localhost:5000/api/auth/verifyEmail'; // Đường dẫn API
$payload = json_encode(['code' => $verificationCode]);

$ch = curl_init($apiUrl);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// Kiểm tra mã HTTP trả về từ API
if ($httpCode === 200) {
    $result = json_decode($response, true);
    if ($result['success']) {
        echo json_encode(['success' => true, 'message' => 'Email verified successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => $result['message'] ?? 'Verification failed.']);
    }
} else {
    // Nếu API trả về lỗi, xử lý lỗi từ phía server backend
    echo json_encode(['success' => false, 'message' => 'Server error. Please try again later.']);
}
?>
