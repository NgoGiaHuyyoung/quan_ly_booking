<?php
// Kiểm tra nếu có tham số showLoginModal trong URL
$showLoginModal = isset($_GET['showLoginModal']) && $_GET['showLoginModal'] === 'true';
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jay Homestay - FACILITIES</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <?php require('inc/links.php'); ?>
    <style>
        .pop:hover {
            border-top-color: var(--teal_hover) !important;
            transform: scale(1.03);
            transition: all 0.3s;
        }
    </style>
</head>
<body class="bg-light">
    <?php require('inc/header.php'); ?>

    <div class="my-5 px-4">
        <h2 class="fw-bold h-font text-center">OUR FACILITIES</h2>
        <div class="h-line bg-dark"></div>
        <p class="text-center mt-3">
            Lorem ipsum dolor sit amet consectetur adipisicing elit.
            Quam optio impedit soluta. Pariatur unde delectus animi
            at voluptate obcaecati tempore.
        </p>
    </div>
                                                                                             
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-6 mb-5 px-4">
            <div class="bg-white rounded shadow p-4 border-top border-4 border-dark pop">
                    <div class="d-flex align-items-center mb-2">
                        <img src="images/facilities/1.svg" width="40px">
                        <h5 class="m-0 ms-3">Wifi</h5>
                    </div>
                    <p>
                        Lorem ipsum dolor sit amet consectetur, adipisicing elit.
                        Quas, sit, fuga praesentium quibusdam accusamus debitis
                        sed maxime eius ipsam ducimus vero. Tempore, dolorem ea.
                        Rerum velit alias temporibus cumque amet.
                    </p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 mb-5 px-4">
            <div class="bg-white rounded shadow p-4 border-top border-4 border-dark pop">
                    <div class="d-flex align-items-center mb-2">
                        <img src="images/facilities/2.svg" width="40px">
                        <h5 class="m-0 ms-3">Heater</h5>
                    </div>
                    <p>
                        Lorem ipsum dolor sit amet consectetur, adipisicing elit.
                        Quas, sit, fuga praesentium quibusdam accusamus debitis
                        sed maxime eius ipsam ducimus vero. Tempore, dolorem ea.
                        Rerum velit alias temporibus cumque amet.
                    </p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 mb-5 px-4">
            <div class="bg-white rounded shadow p-4 border-top border-4 border-dark pop">
                    <div class="d-flex align-items-center mb-2">
                        <img src="images/facilities/3.svg" width="40px">
                        <h5 class="m-0 ms-3">Massage</h5>
                    </div>
                    <p>
                        Lorem ipsum dolor sit amet consectetur, adipisicing elit.
                        Quas, sit, fuga praesentium quibusdam accusamus debitis
                        sed maxime eius ipsam ducimus vero. Tempore, dolorem ea.
                        Rerum velit alias temporibus cumque amet.
                    </p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 mb-5 px-4">
            <div class="bg-white rounded shadow p-4 border-top border-4 border-dark pop">
                    <div class="d-flex align-items-center mb-2">
                        <img src="images/facilities/4.svg" width="40px">
                        <h5 class="m-0 ms-3">Fan</h5>
                    </div>
                    <p>
                        Lorem ipsum dolor sit amet consectetur, adipisicing elit.
                        Quas, sit, fuga praesentium quibusdam accusamus debitis
                        sed maxime eius ipsam ducimus vero. Tempore, dolorem ea.
                        Rerum velit alias temporibus cumque amet.
                    </p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 mb-5 px-4">
            <div class="bg-white rounded shadow p-4 border-top border-4 border-dark pop">
                    <div class="d-flex align-items-center mb-2">
                        <img src="images/facilities/5.svg" width="40px">
                        <h5 class="m-0 ms-3">Radio</h5>
                    </div>
                    <p>
                        Lorem ipsum dolor sit amet consectetur, adipisicing elit.
                        Quas, sit, fuga praesentium quibusdam accusamus debitis
                        sed maxime eius ipsam ducimus vero. Tempore, dolorem ea.
                        Rerum velit alias temporibus cumque amet.
                    </p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 mb-5 px-4">
            <div class="bg-white rounded shadow p-4 border-top border-4 border-dark pop">
                    <div class="d-flex align-items-center mb-2">
                        <img src="images/facilities/6.svg" width="40px">
                        <h5 class="m-0 ms-3">Television</h5>
                    </div>
                    <p>
                        Lorem ipsum dolor sit amet consectetur, adipisicing elit.
                        Quas, sit, fuga praesentium quibusdam accusamus debitis
                        sed maxime eius ipsam ducimus vero. Tempore, dolorem ea.
                        Rerum velit alias temporibus cumque amet.
                    </p>
                </div>
            </div>

        </div>
    </div>

    <?php require('inc/footer.php') ?>


<!-- Liên kết đến file JavaScript -->
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