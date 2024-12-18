<?php
// Kiểm tra nếu có tham số showLoginModal trong URL
$showLoginModal = isset($_GET['showLoginModal']) && $_GET['showLoginModal'] === 'true';
// Lấy roomId từ URL (nếu có)
$roomId = isset($_GET['id']) ? $_GET['id'] : null;
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
    <style>
        .pop:hover {
            border-top-color: var(--teal_hover) !important;
            transform: scale(1.03);
            transition: all 0.3s;
        }

        .room-image {
            width: 100%;
            height: auto;
        }

        .room-details {
            margin-top: 20px;
        }

        .room-title {
            font-size: 2rem;
            font-weight: bold;
        }

        .room-price {
            font-size: 1.5rem;
            color: #28a745;
        }

        .features,
        .facilities {
            margin-top: 10px;
        }

        .badge {
            margin-right: 5px;
        }

        .review-section {
            margin-top: 40px;
        }
    </style>
</head>

<body class="bg-light">
    <?php require('inc/header.php'); ?>

    <div class="my-5 px-4">
        <h2 class="fw-bold h-font text-center">ROOM DETAILS</h2>
        <div class="h-line bg-dark"></div>
    </div>

    <div class="container">
        <div id="roomDetails" class="row">
            <!-- Room details will be dynamically inserted here -->
        </div>
    </div>

    <!-- Booking Modal -->
    <div class="modal fade" id="bookingModal" tabindex="-1" aria-labelledby="bookingModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="bookingModalLabel">Booking Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" placeholder="Enter your name">
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" id="phone" placeholder="Enter your phone number">
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control" id="address" rows="3" placeholder="Enter your address"></textarea>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="checkIn" class="form-label">Check-in</label>
                                <input type="date" class="form-control" id="checkIn">
                            </div>
                            <div class="col">
                                <label for="checkOut" class="form-label">Check-out</label>
                                <input type="date" class="form-control" id="checkOut">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit Booking</button>
                    </form>
                </div>
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