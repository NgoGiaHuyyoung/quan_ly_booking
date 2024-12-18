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
    <title>Jay Homestay - ROOMS</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <?php require('inc/links.php'); ?>
    <style>
        .pop:hover {
            border-top-color: var(--teal_hover) !important;
            transform: scale(1.03);
            transition: all 0.3s;
        }

        .pagination-button {
            cursor: pointer;
            margin: 0 5px;
            padding: 5px 10px;
            background-color: #f1f1f1;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .pagination-button.active {
            background-color: #007bff;
            color: white;
            border: 1px solid #007bff;
        }

        .pagination-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 20px;
        }

        .pagination-button:hover {
            background-color: #ddd;
        }

        .page-number {
            margin: 0 5px;
        }

        .page-number span {
            margin: 0 3px;
        }
    </style>
</head>

<body class="bg-light">
    <?php require('inc/header.php'); ?>

    <div class="my-5 px-4">
        <h2 class="fw-bold h-font text-center">OUR ROOMS</h2>
        <div class="h-line bg-dark"></div>
    </div>

    <div class="container">
        <div class="row">
            <!-- Filters Section -->
            <div class="col-lg-3 col-md-12 mb-lg-0 mb-4 px-lg-0">
                <nav class="navbar navbar-expand-lg navbar-light bg-light rounded shadow">
                    <div class="container-fluid flex-lg-column align-items-stretch">
                        <h4 class="mt-2">FILTERS</h4>
                        <button class="navbar-toggler shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#filterDropdown" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse flex-column align-items-stretch mt-2" id="filterDropdown">
                            <!-- Filters content -->
                            <div class="border bg-light p-3 rounded mb-3">
                                <h5 class="mb-3" style="font-size: 18px;">CHECK AVAILABILITY</h5>
                                <label class="form-label">Check-in</label>
                                <input type="date" class="form-control shadow-none">
                                <label class="form-label">Check-out</label>
                                <input type="date" class="form-control shadow-none">
                            </div>
                            <div class="border bg-light p-3 rounded mb-3">
                                <h5 class="mb-3" style="font-size: 18px;">FACILITIES</h5>
                                <div class="mb-2">
                                    <input type="checkbox" id="f1" class="form-check-input shadow-none me-1">
                                    <label class="form-check-label" for="f1">Facilities one</label>
                                </div>
                                <div class="mb-2">
                                    <input type="checkbox" id="f2" class="form-check-input shadow-none me-1">
                                    <label class="form-check-label" for="f2">Facilities two</label>
                                </div>
                                <div class="mb-2">
                                    <input type="checkbox" id="f3" class="form-check-input shadow-none me-1">
                                    <label class="form-check-label" for="f3">Facilities three</label>
                                </div>
                            </div>
                            <div class="border bg-light p-3 rounded mb-3">
                                <h5 class="mb-3" style="font-size: 18px;">GUESTS</h5>
                                <div class="d-flex">
                                    <div class="me-2">
                                        <label class="form-label">Adult</label>
                                        <input type="number" class="form-control shadow-none">
                                    </div>
                                    <div>
                                        <label class="form-label">Children</label>
                                        <input type="number" class="form-control shadow-none">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </nav>
            </div>

            <!-- Rooms Section -->
            <div class="col-lg-9 col-md-12 px-4" id="rooms-container">
                <!-- Rooms will be dynamically loaded here -->
            </div>

            <!-- Pagination Section -->
            <div class="col-12 text-center my-4" id="pagination-container" class="pagination-container">
                <!-- Pagination buttons will be dynamically added here -->
            </div>

        </div>
    </div>

    <?php require('inc/footer.php'); ?>

    <script>
        let currentPage = 1;
        const roomsPerPage = 10;

        async function fetchRooms(page = 1) {
            try {
                const response = await fetch(`http://localhost:5000/api/rooms?page=${page}&limit=${roomsPerPage}`);
                if (!response.ok) throw new Error(`Failed to fetch data: ${response.statusText}`);

                const data = await response.json();

                // Kiểm tra dữ liệu trả về có hợp lệ không
                if (!data.rooms || !Array.isArray(data.rooms)) {
                    throw new Error('Invalid data format: Expected an array in "rooms" property');
                }

                const rooms = data.rooms;
                const roomsContainer = document.getElementById('rooms-container');
                const paginationContainer = document.getElementById('pagination-container');

                // Xóa nội dung cũ trong các container
                roomsContainer.innerHTML = '';
                paginationContainer.innerHTML = '';

                // Render các phòng
                rooms.forEach(room => {
                    // Kiểm tra và xử lý các trường hợp undefined hoặc không phải mảng
                    const images = Array.isArray(room.images) ? room.images : [];
                    const features = Array.isArray(room.features) ? room.features : [];
                    const facilities = Array.isArray(room.facilities) ? room.facilities : [];

                    roomsContainer.innerHTML += `
                    <div class="card mb-4 border-0 shadow">
                        <div class="row g-0 p-3 align-items-center">
                            <div class="col-md-5">
                                <div>
                                    ${images.length > 0 
                                        ? images.map(image => `<img src="http://localhost:5000${image}" alt="Room image" class="img-fluid rounded-start">`).join('') 
                                        : `<img src="images/rooms/1.jpg" alt="Default Room image" class="img-fluid rounded-start">`}
                                </div>
                            </div>
                            <div class="col-md-5 px-lg-3">
                                <h5 class="mb-3">${sanitizeHTML(room.name)}</h5>
                                ${generateBadgeSection('Features', features)}
                                ${generateBadgeSection('Facilities', facilities)}
                                <div class="guests">
                                    <h6 class="mb-1">Guests</h6>
                                    <span class="badge rounded-pill bg-light text-dark">${sanitizeHTML(room.maxGuests)} Guests</span>
                                </div>
                            </div>
                            <div class="col-md-2 text-center">
                                <h6 class="mb-4">$${sanitizeHTML(room.price)} per night</h6>
                                <a href="rooms_details.php?id=${room.id}" class="btn btn-sm w-100 text-white custom-bg shadow-none mb-2">Book Now</a>
                                <a href="rooms_details.php?id=${room.id}" class="btn btn-sm w-100 btn-outline-dark shadow-none">More details</a>
                            </div>
                        </div>
                    </div>`;
                });

                // Tính toán số trang và range phân trang
                const totalPages = Math.ceil(data.totalRooms / roomsPerPage);
                const paginationRange = getPaginationRange(page, totalPages);

                // Thêm nút "Previous"
                const prevButton = document.createElement('button');
                prevButton.classList.add('pagination-button');
                prevButton.innerText = 'Previous';
                prevButton.disabled = page === 1;
                prevButton.addEventListener('click', () => fetchRooms(page - 1));
                paginationContainer.appendChild(prevButton);

                // Thêm các nút trang
                paginationRange.forEach(pageNum => {
                    const pageButton = document.createElement('a');
                    pageButton.classList.add('pagination-button');
                    pageButton.href = "#";
                    pageButton.innerText = pageNum;
                    if (pageNum === page) {
                        pageButton.classList.add('active');
                    }
                    pageButton.addEventListener('click', () => fetchRooms(pageNum));
                    paginationContainer.appendChild(pageButton);
                });

                // Thêm nút "Next"
                const nextButton = document.createElement('button');
                nextButton.classList.add('pagination-button');
                nextButton.innerText = 'Next';
                nextButton.disabled = page === totalPages;
                nextButton.addEventListener('click', () => fetchRooms(page + 1));
                paginationContainer.appendChild(nextButton);

            } catch (error) {
                console.error('Error fetching rooms:', error);
            }
        }

        // Hàm lấy phạm vi trang
        function getPaginationRange(currentPage, totalPages) {
            let start = Math.max(currentPage - 2, 1);
            let end = Math.min(currentPage + 2, totalPages);
            let range = [];

            for (let i = start; i <= end; i++) {
                range.push(i);
            }

            return range;
        }

        // Hàm để sanitize dữ liệu
        function sanitizeHTML(str) {
            const element = document.createElement('div');
            if (str) {
                element.innerText = str;
                return element.innerHTML;
            }
            return '';
        }

        // Hàm sinh phần badges cho Features và Facilities
        function generateBadgeSection(title, items) {
            return `
            <div>
                <h6 class="mb-2">${title}</h6>
                ${items.length > 0
                ? items.map(item => `<span class="badge rounded-pill bg-light text-dark">${sanitizeHTML(item)}</span>`).join(' ')
                : `<span class="badge rounded-pill bg-light text-dark">No ${title}</span>`}
            </div>`;
        }

        fetchRooms(currentPage);
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