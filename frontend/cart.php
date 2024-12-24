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

    <div class="container mt-5">
        <h2>Your Cart</h2>
        <div class="row" id="cartItems">
            <!-- Cart items sẽ được thêm ở đây -->
        </div>
        <div class="mt-4">
            <h4>Total Price: ₹<span id="totalPrice">0</span></h4>
            <h6>Created At: <span id="createAt"></span></h6>
            <button id="bookNow" class="btn btn-primary" disabled>Book Now</button>
        </div>
    </div>

    <?php require('inc/footer.php'); ?>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', fetchCartData);

        // Hàm lấy dữ liệu giỏ hàng
        async function fetchCartData() {
            try {
                const token = sessionStorage.getItem('accessToken');
                if (!token) {
                    alert('Please log in to view your cart.');
                    return;
                }

                const response = await fetch('http://localhost:5000/api/cart', {
                    method: 'GET',
                    headers: {
                        'Authorization': 'Bearer ' + token
                    }
                });

                if (!response.ok) {
                    throw new Error('Error fetching cart data');
                }

                const cartData = await response.json();
                renderCart(cartData.items, cartData.createdAt);
            } catch (error) {
                console.error('Error:', error);
            }
        }

        // Hàm hiển thị dữ liệu giỏ hàng
        function renderCart(items, createdAt) {
            const cartItemsContainer = document.getElementById('cartItems');
            const totalPriceElement = document.getElementById('totalPrice');
            const bookNowButton = document.getElementById('bookNow');
            const createAtElement = document.getElementById('createAt');

            let selectedTotalPrice = 0;

            // Gán thời gian tạo đơn hàng
            createAtElement.textContent = new Date(createdAt).toLocaleString();

            // Hiển thị từng item trong giỏ hàng
            items.forEach((item, index) => {
                // Xử lý đường dẫn ảnh
                const imagePath = item.details.images[0].startsWith('/uploads/') ?
                    `http://localhost:5000${item.details.images[0]}` :
                    item.details.images[0];

                const cartItemHTML = `
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card">
                          <img src="${imagePath}" class="card-img-top" alt="${item.details.name}">
                        <div class="card-body">
                            <h5 class="card-title">${item.details.name}</h5>
                            <p class="card-text">Price: ₹${item.details.price}</p>
                            <p class="card-text">Quantity: ${item.quantity}</p>
                            <label>
                                <input type="checkbox" class="item-checkbox" data-price="${item.details.price}">
                                Select this item
                            </label>
                        </div>
                    </div>
                </div>`;
                cartItemsContainer.innerHTML += cartItemHTML;
            });

            // Lắng nghe sự kiện checkbox
            cartItemsContainer.addEventListener('change', (e) => {
                if (e.target.classList.contains('item-checkbox')) {
                    const itemPrice = parseFloat(e.target.dataset.price);

                    if (e.target.checked) {
                        selectedTotalPrice += itemPrice;
                    } else {
                        selectedTotalPrice -= itemPrice;
                    }

                    // Cập nhật tổng tiền
                    totalPriceElement.textContent = selectedTotalPrice.toFixed(2);

                    // Bật/tắt nút Book Now
                    bookNowButton.disabled = selectedTotalPrice === 0;
                }
            });

            // Xử lý khi nhấn nút Book Now
            bookNowButton.addEventListener('click', () => {
                alert('Booking items with total price ₹' + selectedTotalPrice.toFixed(2));
            });
        }
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