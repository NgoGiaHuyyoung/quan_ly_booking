<?php
// Kiểm tra nếu có tham số showLoginModal trong URL
$showLoginModal = isset($_GET['showLoginModal']) && $_GET['showLoginModal'] === 'true';
// Lấy roomId từ URL (có thể là chuỗi UUID hoặc số)
$isLoggedIn = isset($_SESSION['user']);
$roomId = isset($_GET['id']) && !empty($_GET['id']) ? $_GET['id'] : null;
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
                    <form id="bookingForm">
                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="name" placeholder="Enter your full name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email" placeholder="Enter your email address" required>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" id="phone" placeholder="Enter your phone number" required>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="checkIn" class="form-label">Check-in Date</label>
                                <input type="date" class="form-control" id="checkIn" required>
                            </div>
                            <div class="col">
                                <label for="checkOut" class="form-label">Check-out Date</label>
                                <input type="date" class="form-control" id="checkOut" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="roomType" class="form-label">Select Room Type</label>
                            <select class="form-control" id="roomType" required>
                                <option value="" disabled selected>Select a room type</option>
                                <option value="single">Single Room</option>
                                <option value="double">Double Room</option>
                                <option value="suite">Suite</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit Booking</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Add to Cart Modal -->
    <div class="modal fade" id="cartModal" tabindex="-1" aria-labelledby="cartModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button class="btn btn-secondary w-100 mt-2" data-room-id="${room.id}" data-bs-toggle="modal" data-bs-target="#cartModal">Add to Cart</button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="cart.php">
                        <input type="hidden" name="roomId" value="<?php echo htmlspecialchars($roomId); ?>">
                        <p>Room ID: <?php echo htmlspecialchars($roomId); ?></p>
                        <button type="submit" class="btn btn-primary">Add to Cart</button>
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
        if (<?php echo $showLoginModal ? 'true' : 'false'; ?>) {
            const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
            loginModal.show();
        }

        async function fetchRoomData() {
            const urlParams = new URLSearchParams(window.location.search);
            const roomId = urlParams.get('id');
            if (!roomId) {
                alert('Room ID is invalid or missing.');
                window.location.href = 'rooms.php';
                return;
            }

            try {
                const response = await fetch(`http://localhost:5000/api/rooms/${roomId}`);
                if (!response.ok) throw new Error('Room not found or API error');
                const room = await response.json();

                const roomContainer = document.getElementById('roomDetails');
                const features = Array.isArray(room.features) ? room.features : [];
                const facilities = Array.isArray(room.facilities) ? room.facilities : [];
                const images = Array.isArray(room.images) ? room.images : [];

                const imageUrl = images.length > 0 ? images[0] : 'images/rooms/default_image.jpg';

                roomContainer.innerHTML = `
                <div class="col-lg-6 col-md-12 mb-4">
                    <img src="http://localhost:5000${imageUrl}" class="room-image img-fluid rounded-start" alt="${room.name}">
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
                            <p class="text-muted">Max ${room.guests} Guests</p>
                        </div>
                        <a href="#" class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#bookingModal">Book Now</a>
                        <button class="btn btn-secondary w-100 mt-2 add-to-cart" data-room-id="${room.id}">Add to Cart</button>
                    </div>
                </div>`;

                // Gắn sự kiện Add To Cart sau khi nội dung được thêm
                document.querySelectorAll('.add-to-cart').forEach((button) => {
                    button.addEventListener('click', function() {
                        // Lấy roomId từ URL
                        const urlParams = new URLSearchParams(window.location.search);
                        const roomIdFromURL = urlParams.get('id');
                        console.log('Room ID from URL:', roomIdFromURL); // Log roomId

                        if (!roomIdFromURL) {
                            console.log('Room ID not found in URL');
                            alert('Room ID not found in URL!');
                            return;
                        }

                        // Kiểm tra token
                        const token = sessionStorage.getItem('accessToken');
                        console.log('Access token:', token); // Log token

                        if (!token) {
                            console.log('No token found in sessionStorage');
                            alert('Please log in to add items to your cart.');
                            return;
                        }

                        // Gửi request tới API
                        const requestBody = {
                            type: 'room',
                            itemId: roomIdFromURL,
                            quantity: 1,
                        };
                        console.log('Request body:', requestBody); // Log request body

                        fetch('http://localhost:5000/api/cart/add', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Authorization': `Bearer ${token}`,
                                },
                                body: JSON.stringify(requestBody),
                            })
                            .then((response) => {
                                console.log('Response Status:', response.status); // Log status của response

                                // Kiểm tra mã trạng thái trước khi tiếp tục
                                if (!response.ok) {
                                    return response.json().then((data) => {
                                        console.error('Error response from server:', data);
                                        throw new Error(`HTTP error! Status: ${response.status} - ${data.message || 'Unknown error'}`);
                                    });
                                }

                                return response.json(); // Tiến hành xử lý dữ liệu nếu mã trạng thái là 2xx
                            })
                            .then((data) => {
                                console.log('Response data:', data); // Log dữ liệu phản hồi từ server

                                // Kiểm tra dữ liệu phản hồi có đúng cấu trúc và dữ liệu cần thiết không
                                if (data && data._id) {
                                    alert('Room added to cart!');
                                } else {
                                    console.error('API Error:', data);
                                    alert(data.message || 'Failed to add room to cart. Please try again.');
                                }
                            })
                            .catch((error) => {
                                console.error('Error adding to cart:', error); // Log lỗi chi tiết
                                alert('Error adding to cart. Check the console for details.');
                            });
                    });
                });






            } catch (error) {
                console.error('Error fetching room data:', error);
                alert('Error fetching room data: ' + error.message);
            }
        }

        document.addEventListener('DOMContentLoaded', fetchRoomData);
    </script>







    <!-- Liên kết đến file JavaScript -->
    <script src="admin/js/login-register.js"></script>

    <!-- Thêm một số JavaScript cho modal -->
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