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
    <title>Jay Homestay - ABOUT</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <?php require('inc/links.php'); ?>
    <style>
        .box {
            border-top-color: var(--teal) !important;
        }
    </style>
</head>

<body class="bg-light">
    <?php require('inc/header.php'); ?>





    <!-- Display Message Based on Payment Status -->
    <div id="paymentMessage" class="box">
        <p>Processing your payment...</p>
    </div>

    <?php require('inc/footer.php'); ?>

    <!-- Liên kết đến file JavaScript -->
    <script src="admin/js/login-register.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', async function() {
            // Lấy thông tin callback từ URL (ví dụ: MoMo trả về thông tin qua URL query params)
            const urlParams = new URLSearchParams(window.location.search);
            const paymentStatus = urlParams.get('paymentStatus'); // Giả sử MoMo trả về 'paymentStatus'
            const transactionId = urlParams.get('transactionId'); // Giả sử MoMo trả về 'transactionId'
            
            if (paymentStatus && transactionId) {
                try {
                    const response = await fetch('http://localhost:5000/api/payments/handlePaymentCallback', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            paymentStatus: paymentStatus,
                            transactionId: transactionId
                        })
                    });
                    
                    const data = await response.json();
                    
                    if (response.ok) {
                        document.getElementById('paymentMessage').innerHTML = `<p>Payment Successful: ${data.message}</p>`;
                    } else {
                        document.getElementById('paymentMessage').innerHTML = `<p>Payment Failed: ${data.message}</p>`;
                    }
                } catch (error) {
                    console.error('Error processing payment callback:', error);
                    document.getElementById('paymentMessage').innerHTML = `<p>There was an error processing your payment.</p>`;
                }
            } else {
                document.getElementById('paymentMessage').innerHTML = `<p>No payment status found in the URL.</p>`;
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