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


    <div class="container mt-5">
        <h2 class="text-center mb-4">Thanh Toán Thành Công</h2>

     
            <div class="alert alert-success text-center">
                <strong>Thanh toán của bạn đã được xử lý thành công!</strong><br>

                Chúng tôi sẽ gửi thông tin chi tiết qua email.
            </div>
 

    </div>


    <?php require('inc/footer.php') ?>

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>



    <!-- Liên kết đến file JavaScript -->
    <script src="admin/js/login-register.js"></script>


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