<?php
// Lấy thông tin URL nếu cần (tùy chọn)
$showLoginModal = isset($_GET['showLoginModal']) && $_GET['showLoginModal'] === 'true';
$roomId = isset($_GET['id']) && !empty($_GET['id']) ? $_GET['id'] : null;

// Đảm bảo đường dẫn chính xác đến autoload.php của Composer
require __DIR__ . '/../vendor/autoload.php';

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

// Thông tin tài khoản admin
$accountNumber = "123456789"; // Số tài khoản admin
$bankName = "Ngo Gia Huy";       // Tên ngân hàng admin
$amount = 100000;             // Số tiền mặc định
$description = "Thanh toan hoa don #1234"; // Mô tả giao dịch

// Nội dung QR chuẩn VNPAY hoặc ví MoMo
$qrContent = "STK: $accountNumber; NH: $bankName; TT: $amount; DES: $description";

// Tạo mã QR
$qrCode = new QrCode($qrContent);
$qrCode->setSize(300);

// Kiểm tra xem GD extension có được kích hoạt không
if (!extension_loaded('gd')) {
    die('GD extension is not enabled. Please enable GD in your php.ini file.');
}

// Tạo writer để xuất mã QR dưới dạng PNG
$writer = new PngWriter();
$result = $writer->write($qrCode);

// Lấy nội dung mã QR dưới dạng Base64 để hiển thị
$qrCodeBase64 = base64_encode($result->getString());
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jay Homestay - ROOM DETAILS</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <?php require('inc/links.php'); ?>
</head>

<body class="bg-light">
    <?php require('inc/header.php'); ?>
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-10">

                <h2 class="text-center mb-4">Booking Invoice</h2>
                <form method="POST" action="process_booking.php">
                    <!-- User Information -->
                    <div class="form-group">
                        <label for="username">Name</label>
                        <input type="text" id="name" name="name" class="form-control" readonly>
                    </div>

                    <div class="form-group">
                        <label for="roomName">Room Name</label>
                        <input type="text" id="roomName" name="roomName" class="form-control" readonly>
                    </div>

                    <div class="form-group">
                        <label for="quantity">Room Quantity</label>
                        <input type="number" id="quantity" name="quantity" class="form-control" readonly>
                    </div>

                    <div class="form-group">
                        <label for="checkIn">Check-in Date</label>
                        <input type="text" id="checkIn" name="checkIn" class="form-control" readonly>
                    </div>

                    <div class="form-group">
                        <label for="checkOut">Check-out Date</label>
                        <input type="text" id="checkOut" name="checkOut" class="form-control" readonly>
                    </div>

                    <div class="form-group">
                        <label for="currentTime">Current Time</label>
                        <input type="text" id="currentTime" name="currentTime" class="form-control" readonly>
                    </div>

                    <div class="form-group">
                        <label for="voucher">Voucher</label>
                        <input type="text" id="voucher" name="voucher" class="form-control" readonly>
                    </div>

                    <div class="form-group">
                        <label for="totalPrice">Total Price</label>
                        <input type="text" id="totalPrice" name="totalPrice" class="form-control" readonly>
                    </div>

                    <button type="submit" class="btn btn-primary mt-3">Confirm Booking</button>
                </form>

            </div>
        </div>
    </div>

    <div class="container mt-5">
        <h2 class="text-center mb-4">Thanh Toán</h2>

        <!-- Phần hiển thị mã QR -->
        <div class="text-center mb-5">
            <h4>Quét mã QR để thanh toán</h4>
            <img id="qrCode" src="" alt="QR Code" class="mb-3">
            <p>STK: <b id="accountNumber"></b> | NH: <b id="bankName"></b></p>
        </div>

        <!-- Form thanh toán thủ công -->
        <form id="paymentForm" method="POST" action="processPayment.php">
            <div class="mb-3">
                <label for="accountNumber" class="form-label">Số tài khoản nhận</label>
                <input type="text" class="form-control" id="accountNumber" name="accountNumber" readonly>
            </div>
            <div class="mb-3">
                <label for="bankName" class="form-label">Tên ngân hàng</label>
                <input type="text" class="form-control" id="bankName" name="bankName" readonly>
            </div>
            <div class="mb-3">  
                <label for="amount" class="form-label">Số tiền</label>
                <input type="number" class="form-control" id="amount" name="amount" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Nội dung chuyển khoản</label>
                <input type="text" class="form-control" id="description" name="description" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Xác nhận thanh toán</button>
        </form>
    </div>

    <?php require('inc/footer.php'); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Liên kết đến file JavaScript -->
    <script src="admin/js/login-register.js"></script>

    <script>
        // Lấy billId từ URL
        const urlParams = new URLSearchParams(window.location.search);
        const billsId = urlParams.get('billsId');

        if (billsId) {
            // Gọi API getBill để lấy thông tin hóa đơn
            fetch(`http://localhost:3000/api/getBill?billId=${billId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        const bill = data.bill;

                        // Cập nhật các trường thông tin
                        document.getElementById('name').value = bill.userName || 'N/A';
                        document.getElementById('roomName').value = bill.roomName || 'N/A';
                        document.getElementById('quantity').value = bill.quantity || 'N/A';
                        document.getElementById('checkIn').value = bill.checkInDate || 'N/A';
                        document.getElementById('checkOut').value = bill.checkOutDate || 'N/A';
                        document.getElementById('currentTime').value = new Date().toLocaleString();
                        document.getElementById('voucher').value = bill.voucher || 'N/A';
                        document.getElementById('totalPrice').value = bill.totalPrice || 'N/A';

                        // Hiển thị mã QR
                        document.getElementById('qrCode').src = bill.qrCodeBase64;
                        document.getElementById('accountNumber').innerText = bill.accountNumber;
                        document.getElementById('bankName').innerText = bill.bankName;
                    } else {
                        alert('Không tìm thấy hóa đơn');
                    }
                })
                .catch(error => {
                    console.error('Lỗi khi gọi API:', error);
                    alert('Có lỗi xảy ra khi tải thông tin hóa đơn');
                });
        }
    </script>
    
    <script>
        // Show login modal if necessary
        if (<?php echo $showLoginModal ? 'true' : 'false'; ?>) {
            const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
            loginModal.show();
        }

        // Form submission to backend for payment initiation
        // const handlePaymentCallback = async (transactionId, status, message, billId) => {
        //     // Kiểm tra các tham số cần thiết
        //     if (!transactionId || !status || !billId) {
        //         alert('Transaction ID, status, and bill ID are required.');
        //         return;
        //     }

        //     try {
        //         // Gửi yêu cầu đến backend để xử lý thanh toán
        //         const response = await fetch('http://localhost:5000/api/payment-callback', { // Thay URL với API thực tế
        //             method: 'POST',
        //             headers: {
        //                 'Content-Type': 'application/json',
        //             },
        //             body: JSON.stringify({
        //                 transactionId: transactionId,
        //                 status: status,
        //                 message: message,
        //                 billId: billId,
        //             }),
        //         });

        //         const data = await response.json();

        //         if (response.ok && data.message) {
        //             // Nếu thanh toán thành công
        //             alert(data.message); // Hiển thị thông báo thành công
        //             // Cập nhật giao diện hoặc làm gì đó sau khi thanh toán thành công
        //             // Ví dụ: Redirect đến trang khác, cập nhật UI, etc.
        //         } else {
        //             // Nếu có lỗi hoặc thất bại
        //             alert(data.message || 'Error processing payment callback');
        //         }
        //     } catch (error) {
        //         console.error('Error:', error);
        //         alert('An error occurred while processing the payment callback.');
        //     }
        // };

        // Giả sử bạn nhận được thông tin từ API hoặc sự kiện
        // Dưới đây là một ví dụ sử dụng thông tin giả để gọi hàm trên
        // Ví dụ này sẽ được thay bằng thông tin thực tế khi nhận được phản hồi từ hệ thống thanh toán

        const transactionId = 'TXN123456789'; // Thông tin giao dịch giả
        const status = 'success'; // Trạng thái thanh toán
        const message = 'Thanh toán thành công'; // Thông điệp chi tiết
        const billId = 'bill12345'; // ID của hóa đơn

        // Gọi hàm xử lý callback khi nhận được thông tin thanh toán
        handlePaymentCallback(transactionId, status, message, billId);
    </script>


    <!-- Thêm một số JavaScript cho modal -->
    <script>
        // Mở modal login
        document.getElementById('openLoginModal').addEventListener('click', function() {
            document.getElementById('loginModal').style.display = 'block';
        });

        // Mở modal register
        document.getElementById('openRegisterModal').addEventListener('click', function() {
            document.getElementById('registerModal').style.display = 'block';
        });

        // Đóng modal login
        document.getElementById('closeLoginModal').addEventListener('click', function() {
            document.getElementById('loginModal').style.display = 'none';
        });

        // Đóng modal register
        document.getElementById('closeRegisterModal').addEventListener('click', function() {
            document.getElementById('registerModal').style.display = 'none';
        });

        // Mở modal logout
        document.getElementById('openLogoutModal').addEventListener('click', function() {
            document.getElementById('logoutModal').style.display = 'block';
        });

        // Đóng modal logout khi nhấn vào dấu "x"
        document.getElementById('closeLogoutModal').addEventListener('click', function() {
            document.getElementById('logoutModal').style.display = 'none';
        });

        // Đóng modal logout khi nhấn vào nút Cancel
        document.getElementById('cancelLogout').addEventListener('click', function() {
            document.getElementById('logoutModal').style.display = 'none';
        });

        // Xác nhận đăng xuất và thực hiện thao tác logout
        document.getElementById('confirmLogout').addEventListener('click', function() {
            alert('You have logged out.');
            document.getElementById('logoutModal').style.display = 'none';
        });
    </script>
</body>

</html>