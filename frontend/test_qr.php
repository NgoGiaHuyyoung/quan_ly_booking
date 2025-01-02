<?php
// Lấy thông tin URL nếu cần (tùy chọn)
$showLoginModal = isset($_GET['showLoginModal']) && $_GET['showLoginModal'] === 'true';
$roomId = isset($_GET['id']) && !empty($_GET['id']) ? $_GET['id'] : null;

// Đảm bảo đường dẫn chính xác đến autoload.php của Composer
require __DIR__ . '/../vendor/autoload.php'; // Chỉnh sửa đường dẫn nếu cần

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

// Thông tin tài khoản user
$accountNumber = ""; // Số tài khoản admin
$bankName = "";       // Tên ngân hàng admin
$amount = "";             // Số tiền mặc định
$description = ""; // Mô tả giao dịch

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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" />

    <?php require('inc/links.php'); ?>
</head>

<body class="bg-light">
    <?php require('inc/header.php'); ?>

    <div class="container mt-5">
        <h2 class="text-center mb-4">Thanh Toán</h2>

        <!-- Phần hiển thị mã QR -->
        <div class="text-center mb-5">
            <h4>Quét mã QR để thanh toán</h4>
            <img src="data:image/png;base64,<?= $qrCodeBase64 ?>" alt="QR Code" class="mb-3">
            <p>STK: <b><?= $accountNumber ?></b> | NH: <b><?= $bankName ?></b></p>
        </div>

        <!-- Form thanh toán thủ công -->
        <form id="paymentForm" method="POST">
            <div class="form-group">
                <label for="totalPrice">Total Price</label>
                <input type="text" id="totalPrice" name="totalPrice" class="form-control" readonly>
            </div>
            <div class="form-group">
                <label for="Service">Service</label>
                <input type="text" id="Service" name="Service" class="form-control" readonly>
            </div>
            <div class="mb-3">
                <label for="voucherCode" class="form-label">Mã code giảm giá</label>
                <input type="text" class="form-control" id="voucherCode" name="voucherCode" placeholder="(Tùy chọn)">
            </div>
            <div class="mb-3">
                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="paymentMethod" data-bs-toggle="dropdown" aria-expanded="false">
                        Phương thức thanh toán
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="paymentMethod">
                        <li><a class="dropdown-item" href="#" data-method="coin">Jay-Coin</a></li>
                        <li><a class="dropdown-item" href="#" data-method="cod">COD</a></li>
                    </ul>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100">Xác nhận thanh toán</button>
        </form>
    </div>

    <script>
        // document.addEventListener('DOMContentLoaded', () => {
        //     // Load total price from sessionStorage
        //     const totalPrice = sessionStorage.getItem('totalPrice');
        //     if (totalPrice) {
        //         document.getElementById('totalPrice').value = totalPrice;
        //     }
        // });

        // Capture payment method selection
        document.querySelectorAll('.dropdown-item').forEach(item => {
            item.addEventListener('click', function(e) {
                const selectedMethod = e.target.getAttribute('data-method');
                document.getElementById('paymentMethod').textContent = selectedMethod;
                document.getElementById('paymentMethod').setAttribute('data-method', selectedMethod);
            });
        });

        // Trigger the payment API call when form is submitted
        document.getElementById('paymentForm').addEventListener('submit', async function(e) {
            e.preventDefault(); // Ngăn chặn hành vi mặc định của form

            // Lấy dữ liệu cần thiết từ sessionStorage
            const token = sessionStorage.getItem('accessToken'); // Token
            const userData = JSON.parse(sessionStorage.getItem('user')); // Thông tin người dùng
            const userId = userData?.userId; // Lấy userId từ thông tin người dùng
            const totalPrice = sessionStorage.getItem('totalPrice'); // Tổng giá tiền
            const voucherCode = document.getElementById('voucherCode').value || null; // Mã giảm giá, có thể null
            const paymentMethod = document.getElementById('paymentMethod').getAttribute('data-method'); // Phương thức thanh toán

            // Lấy serviceId từ sessionStorage, nếu không có thì gán là null
            const serviceId = sessionStorage.getItem('serviceId') || null;

            // Kiểm tra các trường bắt buộc
            if (!token || !userId || !totalPrice || !paymentMethod) {
                alert('Vui lòng nhập đầy đủ thông tin thanh toán.');
                return;
            }

            // Chuẩn bị dữ liệu gửi lên API
            const paymentData = {
                customerId: userId,
                amount: parseFloat(totalPrice),
                paymentMethod,
                voucherCode,
                serviceId // Nếu không có serviceId trong sessionStorage, nó sẽ là null
            };

            try {
                const response = await fetch('http://localhost:5000/api/payments/payment', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${token}`, // Đính kèm token
                    },
                    body: JSON.stringify(paymentData), // Chuyển đổi dữ liệu thành JSON
                });

                const data = await response.json();

                if (response.ok) {
                    // Hiển thị thông báo thành công
                    alert('Thanh toán thành công!');
                    console.log('Payment success:', data);

                    // Chuyển hướng đến trang checkoutsuccessful.php
                    window.location.href = 'CheckOutSussecful.php'; // Chuyển đến trang thành công
                } else {
                    alert(`Thanh toán thất bại: ${data.message}`);
                    console.error('Payment failed:', data);
                }
            } catch (error) {
                console.error('Error processing payment:', error);
                alert('Đã xảy ra lỗi trong quá trình thanh toán. Vui lòng thử lại.');
            }
        });
    </script>





    <?php require('inc/footer.php'); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>






    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <h2 class="text-center mb-4">Booking Invoice</h2>
                <form method="POST" action="process_booking.php">
                    <!-- User Information -->
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
                    <!-- 
                    <div class="form-group">
                        <label for="currentTime">Current Time</label>
                        <input type="text" id="currentTime" name="currentTime" class="form-control" readonly>
                    </div> -->

                    <!-- <div class="form-group">
                        <label for="voucher">Voucher</label>
                        <input type="text" id="voucher" name="voucher" class="form-control" readonly>
                    </div> -->

                    <div class="form-group">
                        <label for="totalPrice">Total Price</label>
                        <input type="text" id="totalPrice" name="totalPrice" class="form-control" readonly>
                    </div>

                    <!-- <button type="submit" class="btn btn-primary mt-3">Confirm Booking</button> -->
                </form>
            </div>
        </div>
    </div>









    <script>
        // Lấy các tham số từ URL
        const urlParams = new URLSearchParams(window.location.search);
        const roomId = urlParams.get('roomId');
        const checkIn = urlParams.get('checkIn');
        const checkOut = urlParams.get('checkOut');

        // Hiển thị check-in và check-out từ URL
        document.getElementById('checkIn').value = checkIn || 'N/A';
        document.getElementById('checkOut').value = checkOut || 'N/A';

        // Lấy thông tin từ sessionStorage
        const cart = JSON.parse(sessionStorage.getItem('cart') || '{}');
        const selectedItems = JSON.parse(sessionStorage.getItem('selectedItems') || '[]');
        const roomItem = selectedItems.find(item => item.itemId === roomId);
        const totalPrice = sessionStorage.getItem('totalPrice');
        if (roomItem) {
            // Hiển thị thông tin phòng từ giỏ hàng
            document.getElementById('quantity').value = roomItem.quantity || 'N/A'; // Số lượng phòng
            document.getElementById('totalPrice').value = roomItem.totalPrice || 'N/A'; // Tổng giá

        } else {
            // Nếu không có phòng trong sessionStorage, gọi API để lấy tên phòng
            fetchRoomNameFromAPI(roomId);
        }

        // Hàm gọi API để lấy tên phòng
        // Fetch room details from API based on roomId
        fetch(`http://localhost:5000/api/rooms/${roomId}`)
            .then(response => response.json())
            .then(data => {
                // Hiển thị thông tin phòng từ API
                document.getElementById('roomName').value = data.name || 'N/A'; // Tên phòng
            })
            .catch(error => {
                console.error('Error fetching room details:', error);
                alert('Không thể tải thông tin phòng!');
            });
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



    <script src="admin/js/login-register.js"></script>
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