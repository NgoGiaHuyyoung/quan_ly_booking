<?php
// Include database connection
require './config/db.js';

// Lấy thông tin từ form
$accountNumber = $_POST['accountNumber'];
$bankName = $_POST['bankName'];
$amount = $_POST['amount'];
$description = $_POST['description'];

// Kiểm tra thông tin (giả lập gọi API ngân hàng để xác minh)
$isPaymentValid = ($accountNumber === "123456789" && $bankName === "BankName" && $amount == 100000 && $description === "Thanh toan hoa don #1234");

if ($isPaymentValid) {
    // Lưu thông tin thanh toán vào cơ sở dữ liệu
    $stmt = $conn->prepare("INSERT INTO payments (account_number, bank_name, amount, description, status) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdss", $accountNumber, $bankName, $amount, $description, $status);

    $status = "success";
    if ($stmt->execute()) {
        // Hiển thị thông báo thành công
        echo "<script>alert('Thanh toán thành công!'); window.location.href='success.php';</script>";
    } else {
        echo "<script>alert('Lỗi lưu dữ liệu. Vui lòng thử lại.'); window.location.href='payment.php';</script>";
    }
} else {
    // Hiển thị thông báo thất bại
    echo "<script>alert('Thông tin thanh toán không hợp lệ. Vui lòng kiểm tra lại.'); window.location.href='payment.php';</script>";
}
?>
