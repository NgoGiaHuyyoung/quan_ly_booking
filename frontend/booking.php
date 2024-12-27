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
    <title>Jay Homestay - ROOM DETAILS</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <?php require('inc/links.php'); ?>
    <style>
        .pop:hover {
            border-top-color: var(--teal_hover) !important;
            transform: scale(1.03);
            transition: all 0.3s;
        }

        .room-image-container {
            width: 100%;
            max-width: 300px;
            /* Giới hạn kích thước tối đa của ảnh */
            height: auto;
            margin-bottom: 20px;
            overflow: hidden;
        }

        .room-image {
            width: 100%;
            height: auto;
            object-fit: cover;
            /* Đảm bảo ảnh không bị méo */
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


    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-10">

                <div class="book-details-1">
                    <h2 class="text-center mb-4">Booking Details</h2>
                    <form method="POST" action="process_booking.php">
                        <!-- Hidden room ID -->
                        <div id="profileOutput" class="mt-4">
                            <form id="saveProfile" class="text-center mt-4">
                                <div class="form-group">
                                    <label for="username">Name</label>
                                    <input type="text" id="name" class="form-control" placeholder="Enter name" value="<?php echo htmlspecialchars($name); ?>">
                                </div>
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" id="email" class="form-control" placeholder="Enter email" value="<?php echo htmlspecialchars($email); ?>">
                                </div>
                                <div class="form-group">
                                    <label for="address">Address</label>
                                    <input type="address" id="address" class="form-control" placeholder="Enter your address" value="<?php echo htmlspecialchars($address); ?>">
                                </div>
                                <div class="form-group">
                                    <label for="phone">Phone</label>
                                    <input type="phone" id="phone" class="form-control" placeholder="Enter your address" value="<?php echo htmlspecialchars($phone); ?>">
                                </div>
                            </form>
                        </div>
                </div>

                <div class="book-details-2">
                    <div class="mb-3">
                        <b>Cập Nhật thông tin Booking</b>
                    </div>
                    <div class="row mb-3">
                        <div class="row align-items-end">
                            <!-- Check-in -->
                            <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
                                <label class="form-label" style="font-weight: 500;">Check-in</label>
                                <input type="date" id="startDate" class="form-control shadow-none" required>
                            </div>

                            <!-- Check-out -->
                            <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
                                <label class="form-label" style="font-weight: 500;">Check-out</label>
                                <input type="date" id="endDate" class="form-control shadow-none" required>
                            </div>

                            <!-- Button -->
                            <div class="col-lg-6 col-md-4 col-sm-12 mb-3">
                                <button type="button" class="btn btn-primary w-100" onclick="redirectToPayment()">Đặt phòng</button>
                            </div>
                        </div>


                        <form method="POST" action="process_booking.php">
                            <div id="roomDetails" class="mt-4">
                                <!-- Room details will be injected here by JavaScript -->
                            </div>
                        </form>
                    </div>
                </div>


            </div>
        </div>
    </div>

    <?php require('inc/footer.php'); ?>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>

    <script>
        if (<?php echo $showLoginModal ? 'true' : 'false'; ?>) {
            const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
            loginModal.show();
        }
        // Lấy dữ liệu từ sessionStorage
        const storedUserData = sessionStorage.getItem('user');
        if (storedUserData) {
            try {
                const userData = JSON.parse(storedUserData);

                // Hiển thị dữ liệu trong các trường input
                document.getElementById('name').value = userData.name || 'N/A';
                document.getElementById('email').value = userData.email || 'N/A';
                document.getElementById('phone').value = userData.phone || 'N/A';
                document.getElementById('address').value = userData.address || 'N/A';
            } catch (error) {
                console.error('Lỗi khi parse JSON từ sessionStorage:', error);
            }
        } else {
            console.warn('Không tìm thấy dữ liệu người dùng trong sessionStorage.');
        }
        // Lấy roomId từ URL
        const urlParams = new URLSearchParams(window.location.search);
        const roomId = urlParams.get('roomId');

        // Hàm lấy thông tin phòng từ API
        async function fetchRoomData() {
            try {
                const response = await fetch(`http://localhost:5000/api/rooms/${roomId}`);
                const room = await response.json();

                if (room) {
                    displayRoomDetails(room);
                } else {
                    alert("Room not found!");
                }
            } catch (error) {
                console.error("Error fetching room data:", error);
            }
        }

        // Hàm hiển thị thông tin phòng
        function displayRoomDetails(room) {
            const roomDetailsContainer = document.getElementById('roomDetails');

            // Xử lý đường dẫn ảnh
            const imagePath = room.images[0].startsWith('/uploads/') ?
                `http://localhost:5000${room.images[0]}` :
                room.images[0];

            roomDetailsContainer.innerHTML = `
            <table class="table table-bordered mt-4">
                <tbody>
                    <tr>
                        <!-- Column 1: Room Title and Image -->
                        <td class="col-4">
                            <div class="room-image-container">
                            <h3 class="room-title">${room.name}</h3>
                                <img src="${imagePath}" class="room-image img-fluid rounded-start" alt="${room.name}">
                            </div>
                        </td>

                        <!-- Column 2: Room Price -->
                        <td class="col-2">
                            <p class="room-price">Price: $${room.price}</p>
                        </td>

                        <!-- Column 3: Room Features -->
                        <td class="col-2">
                            <p><strong>Features:</strong></p>
                            <ul>
                                ${room.features.map(feature => `<li>${feature}</li>`).join('')}
                            </ul>
                        </td>

                        <!-- Column 4: Room Guests -->
                        <td class="col-2">
                            <p><strong>Guests:</strong> ${room.guests}</p>
                        </td>

                        <!-- Column 5: Room Rating -->
                        <td class="col-2">
                            <p><strong>Rating:</strong> ${'⭐'.repeat(room.rating)}${'☆'.repeat(5 - room.rating)}</p>
                        </td>
                    </tr>
                </tbody>
            </table>
    `;
        }

        function redirectToPayment() {
            // Lấy giá trị từ các trường check-in, check-out và roomId
            const roomId = '<?php echo $roomId; ?>'; // Đảm bảo roomId đã được lấy từ URL PHP
            const checkInDate = document.getElementById('startDate').value;
            const checkOutDate = document.getElementById('endDate').value;

            // Kiểm tra nếu tất cả thông tin đã được điền
            if (roomId && checkInDate && checkOutDate) {
                // Tạo URL chuyển hướng với các tham số
                const paymentUrl = `payment.php?roomId=${roomId}&checkIn=${checkInDate}&checkOut=${checkOutDate}`;
                window.location.href = paymentUrl; // Chuyển hướng đến payment.php
            } else {
                alert('Vui lòng điền đầy đủ thông tin ngày check-in và check-out!');
            }
        }


        // Function to send booking data to API
        async function submitBooking() {
            // Lấy roomId từ URL
            const urlParams = new URLSearchParams(window.location.search);
            const roomId = urlParams.get('roomId'); // roomId từ URL
            console.log('Room ID:', roomId); // Log giá trị roomId

            // Lấy userId từ sessionStorage
            const storedUserData = sessionStorage.getItem('user');
            let userId = null;
            if (storedUserData) {
                try {
                    const userData = JSON.parse(storedUserData);
                    userId = userData.userId; // userId từ sessionStorage
                    console.log('User ID:', userId); // Log giá trị userId
                } catch (error) {
                    console.error('Error parsing user data from sessionStorage:', error);
                }
            }
            const token = sessionStorage.getItem('accessToken');
            if (!token) {
                alert('Please log in to view your cart.');
                return;
            }
            // Kiểm tra nếu roomId và userId hợp lệ
            if (!roomId || !userId) {
                alert('Thông tin phòng hoặc người dùng không hợp lệ!');
                console.log('Không có thông tin phòng hoặc người dùng hợp lệ.'); // Log lỗi nếu thiếu roomId hoặc userId
                return;
            }

            // Lấy ngày check-in và check-out
            const checkInDate = document.getElementById('startDate').value;
            const checkOutDate = document.getElementById('endDate').value;
            console.log('Check-in Date:', checkInDate); // Log giá trị ngày check-in
            console.log('Check-out Date:', checkOutDate); // Log giá trị ngày check-out

            // Kiểm tra nếu ngày tháng đã được điền
            if (!checkInDate || !checkOutDate) {
                alert('Vui lòng điền đầy đủ thông tin ngày check-in và check-out!');
                console.log('Ngày check-in hoặc check-out không được điền đầy đủ.'); // Log nếu thiếu ngày
                return;
            }

            // Kiểm tra ngày check-out không sớm hơn ngày check-in
            if (new Date(checkInDate) >= new Date(checkOutDate)) {
                alert('Ngày check-out phải sau ngày check-in!');
                console.log('Ngày check-out phải sau ngày check-in.'); // Log lỗi nếu ngày check-out không hợp lệ
                return;
            }

            // Tạo đối tượng dữ liệu đặt phòng
            const bookingData = {
                roomId: roomId,
                userId: userId,
                checkInDate: checkInDate + "T12:00:00Z", // Thêm thời gian để đảm bảo định dạng ISO hợp lệ
                checkOutDate: checkOutDate + "T12:00:00Z" // Thêm thời gian để đảm bảo định dạng ISO hợp lệ
            };

            console.log('Booking Data:', bookingData); // Log toàn bộ dữ liệu đặt phòng

            try {
                const response = await fetch('http://localhost:5000/api/bookings', {
                    method: 'POST',
                    headers: {
                        'Authorization': 'Bearer ' + token,
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(bookingData),
                });

                const result = await response.json();

                // Log kết quả trả về từ API
                console.log('API Response:', result);

                if (response.ok) {
                    // Nếu đặt phòng thành công, chuyển hướng đến trang thanh toán
                    const paymentUrl = `payment.php?roomId=${roomId}&checkIn=${checkInDate}&checkOut=${checkOutDate}`;
                    console.log('Redirecting to payment page:', paymentUrl); // Log URL thanh toán
                    window.location.href = paymentUrl;
                } else {
                    alert('Đặt phòng không thành công. Vui lòng thử lại sau.');
                    console.log('Đặt phòng không thành công. Lỗi API:', result); // Log lỗi khi API không thành công
                }
            } catch (error) {
                console.error('Error booking room:', error);
                alert('Có lỗi xảy ra khi đặt phòng. Vui lòng thử lại sau.');
                console.log('Lỗi khi gửi yêu cầu API:', error); // Log lỗi nếu có lỗi khi gửi yêu cầu API
            }
        }


        // Update the button to call submitBooking
        function redirectToPayment() {
            submitBooking(); // Call submitBooking function
        }

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