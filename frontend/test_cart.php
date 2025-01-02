<?php
// Kiểm tra nếu có tham số showLoginModal trong URL
$showLoginModal = isset($_GET['showLoginModal']) && $_GET['showLoginModal'] === 'true';
// Lấy roomId từ URL (có thể là chuỗi UUID hoặc số)
$roomId = isset($_GET['id']) && !empty($_GET['id']) ? $_GET['id'] : null;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jay Homestay - Cart</title>
    <?php require('inc/links.php'); ?>
</head>

<body class="bg-light">

    <?php require('inc/header.php'); ?>

    <div class="modal fade" id="cartModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">

    <div class="my-5 px-4">
        <h2 class="fw-bold h-font text-center">Your Cart</h2>
        <div class="h-line bg-dark"></div>
    </div>

    <div class="container">
        <div id="cartItems" class="row">
            <!-- Cart items will be dynamically inserted here -->
        </div>
    </div>

     </div>





    <?php require('inc/footer.php'); ?>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>



<script>
        // Kiểm tra nếu có tham số showLoginModal để mở modal đăng nhập
        if (<?php echo $showLoginModal ? 'true' : 'false'; ?>) {
            // Mở modal đăng nhập
            const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
            loginModal.show();
        }

        // Fetch and display room data from the API
        async function fetchRoomData() {
            // Lấy tham số roomId từ URL
            const urlParams = new URLSearchParams(window.location.search);
            const roomId = urlParams.get('id');

            // Kiểm tra nếu roomId không có hoặc không hợp lệ
            if (!roomId || isNaN(roomId)) {
                console.error('Room ID is invalid or missing');
                alert('Room ID is invalid or missing.');
                return;
            }

            try {
                // Gửi yêu cầu đến API với roomId
                const response = await fetch(`http://localhost:5000/api/rooms/${roomId}`);

                if (!response.ok) {
                    throw new Error('Room not found or API error');
                }

                const room = await response.json();
                const roomContainer = document.getElementById('roomDetails');

                // Kiểm tra và xử lý nếu các thuộc tính features và facilities không phải là mảng
                const features = Array.isArray(room.features) ? room.features : [];
                const facilities = Array.isArray(room.facilities) ? room.facilities : [];

                roomContainer.innerHTML = `
                <div class="col-lg-6 col-md-12 mb-4">
                    <img src="${room.image}" class="room-image img-fluid rounded-start" alt="Room Image">
                </div>
                <div class="col-lg-6 col-md-12">
                    <div class="room-details p-3">
                        <h5 class="room-title">${room.name}</h5>
                        <div class="room-price">₹${room.price} per night</div>
                        <div class="features mb-3">
                            <h6 class="mb-1">Features</h6>
                            <p class="text-muted">${features.join(', ')}</p>
                        </div>
                        <div class="facilities mb-3">
                            <h6 class="mb-1">Facilities</h6>
                            <p class="text-muted">${facilities.join(', ')}</p>
                        </div>
                        <div class="guests mb-3">
                            <h6 class="mb-1">Guests</h6>
                            <p class="text-muted">Max ${room.maxGuests} Guests</p>
                        </div>
                        <a href="#" class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#bookingModal">Book Now</a>
                    </div>
                </div>
            `;
            } catch (error) {
                console.error('Error fetching room data:', error);
                alert('Error fetching room data: ' + error.message);
            }
        }

        // Đảm bảo hàm fetchRoomData chỉ chạy sau khi DOM được tải
        document.addEventListener('DOMContentLoaded', fetchRoomData);
    </script>






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

        // Mở modal giỏ hàng
        addEventListenerSafe('openCartModal', 'click', function() {
            document.getElementById('cartModal').style.display = 'block';
        });

        // Đóng modal giỏ hàng
        addEventListenerSafe('closeCartModal', 'click', function() {
            document.getElementById('cartModal').style.display = 'none';
        });

        // Đóng modal giỏ hàng khi nhấn vào nút Cancel
        addEventListenerSafe('cancelCart', 'click', function() {
            document.getElementById('cartModal').style.display = 'none';
        });
    });
</script>


</body>

</html>