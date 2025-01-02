<?php
// Kiểm tra nếu có tham số showLoginModal trong URL
$showLoginModal = isset($_GET['showLoginModal']) && $_GET['showLoginModal'] === 'true';
$isLoggedIn = isset($_SESSION['user']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jay Homestay - Voucher</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <?php require('inc/links.php'); ?>
    <style>
        .box {
            border-top-color: var(--teal) !important;
        }

        .voucher-form {
            max-width: 500px;
            margin: 50px auto;
            padding: 30px;
            border-radius: 8px;
            background-color: #fff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .voucher-form input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        .voucher-form button {
            padding: 10px 20px;
            background-color: var(--teal);
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }

        .voucher-form button:hover {
            background-color: var(--teal-dark);
        }

        .voucher-list {
            margin-bottom: 20px;
        }

        .voucher-item {
            margin: 10px 0;
        }

        .voucher-token {
            margin-top: 20px;
            text-align: center;
        }

        .voucher-token input {
            text-align: center;
        }
    </style>
</head>

<body class="bg-light">
    <?php require('inc/header.php'); ?>

    <div class="voucher-form">
        <h2>Chọn voucher</h2>
        
        <!-- Hiển thị danh sách các voucher -->
        <div class="voucher-list">
            <div class="voucher-item">
                <button class="btn btn-outline-primary" onclick="generateVoucherToken('voucher1')">Lấy Voucher 1</button>
            </div>
            <div class="voucher-item">
                <button class="btn btn-outline-primary" onclick="generateVoucherToken('voucher2')">Lấy Voucher 2</button>
            </div>
            <div class="voucher-item">
                <button class="btn btn-outline-primary" onclick="generateVoucherToken('voucher3')">Lấy Voucher 3</button>
            </div>
        </div>

        <!-- Hiển thị mã voucher khi người dùng nhấn "Lấy" -->
        <div class="voucher-token">
            <input type="text" id="voucherCode" placeholder="Mã voucher sẽ hiển thị ở đây" readonly>
        </div>
        
        <button id="applyVoucherBtn" class="btn btn-success" disabled>Áp dụng voucher vào booking</button>
    </div>

    <?php require('inc/footer.php'); ?>

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="admin/js/login-register.js"></script>

    <script>
        // Danh sách mã voucher có sẵn
        const vouchers = {
            voucher1: "VOUCHER2024A",
            voucher2: "VOUCHER2024B",
            voucher3: "VOUCHER2024C"
        };

        // Hàm này sẽ được gọi khi người dùng nhấn nút "Lấy"
        function generateVoucherToken(voucherId) {
            const voucherCode = vouchers[voucherId];
            document.getElementById('voucherCode').value = voucherCode;
            document.getElementById('applyVoucherBtn').disabled = false;  // Kích hoạt nút "Áp dụng voucher"
        }

        // Hàm áp dụng voucher khi người dùng nhấn "Áp dụng voucher"
        document.getElementById('applyVoucherBtn').addEventListener('click', function() {
            const voucherCode = document.getElementById('voucherCode').value;
            if (voucherCode) {
                alert(`Voucher ${voucherCode} đã được áp dụng vào booking!`);
                // Ở đây bạn có thể gọi API để gửi voucher code vào quá trình đặt phòng
                // Ví dụ: gửi voucherCode đến API booking
            }
        });
    </script>


<script>
    function addEventListenerSafe(selector, event, callback) {
        const element = document.getElementById(selector);
        if (element) {
            element.addEventListener(event, callback);
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Mở modal login
        addEventListenerSafe('openLoginModal', 'click', function() {
            document.getElementById('loginModal').style.display = 'block';
        });

        // Mở modal register
        addEventListenerSafe('openRegisterModal', 'click', function() {
            document.getElementById('registerModal').style.display = 'block';
        });

        // Đóng modal login
        addEventListenerSafe('closeLoginModal', 'click', function() {
            document.getElementById('loginModal').style.display = 'none';
        });

        // Đóng modal register
        addEventListenerSafe('closeRegisterModal', 'click', function() {
            document.getElementById('registerModal').style.display = 'none';
        });

        // Mở modal logout
        addEventListenerSafe('openLogoutModal', 'click', function() {
            document.getElementById('logoutModal').style.display = 'block';
        });

        // Đóng modal logout khi nhấn vào dấu "x"
        addEventListenerSafe('closeLogoutModal', 'click', function() {
            document.getElementById('logoutModal').style.display = 'none';
        });

        // Đóng modal logout khi nhấn vào nút Cancel
        addEventListenerSafe('cancelLogout', 'click', function() {
            document.getElementById('logoutModal').style.display = 'none';
        });

        // Xác nhận đăng xuất và thực hiện thao tác logout
        addEventListenerSafe('confirmLogout', 'click', function() {
            alert('You have logged out.');
            document.getElementById('logoutModal').style.display = 'none';
        });
    });
    </script>



</body>

</html>