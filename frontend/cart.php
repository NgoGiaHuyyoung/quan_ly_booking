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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <?php require('inc/links.php'); ?>
</head>

<body class="bg-light">

    <?php require('inc/header.php'); ?>

    <div class="container mt-5">
        <h2>Your Cart</h2>
        <div class="row" id="cartItems">
            <!-- Cart items will be added here -->
        </div>
        <div class="mt-4">
            <h4>Total Price: ₹<span id="totalPrice">0</span></h4>
            <h6>Created At: <span id="createAt"></span></h6>
            <button id="bookNow" class="btn btn-primary" disabled>Book Now</button>
        </div>
    </div>

    <!-- Modal for Service Selection -->
    <div id="serviceModal" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Select Service</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="serviceList">
                    <!-- List of services will be populated here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" id="saveServices" class="btn btn-primary">Save Services</button>
                </div>
            </div>
        </div>
    </div>

    <?php require('inc/footer.php'); ?>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', fetchCartData);

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

        function renderCart(items, createdAt) {
            const cartItemsContainer = document.getElementById('cartItems');
            const totalPriceElement = document.getElementById('totalPrice');
            const bookNowButton = document.getElementById('bookNow');
            const createAtElement = document.getElementById('createAt');
            const serviceModal = new bootstrap.Modal(document.getElementById('serviceModal'));
            let selectedTotalPrice = 0;
            let selectedItems = []; // To store the selected items
            let selectedServices = []; // To store selected services

            createAtElement.textContent = new Date(createdAt).toLocaleString();

            cartItemsContainer.innerHTML = '';

            items.forEach((item) => {
                const imagePath = item.details.images[0].startsWith('/uploads/') ? 
                    `http://localhost:5000${item.details.images[0]}` : 
                    item.details.images[0];

                const cartItemHTML = `
                    <div class="col-lg-4 col-md-6 mb-4" id="cartItem-${item.itemId}">
                        <div class="card">
                            <img src="${imagePath}" class="card-img-top" alt="${item.details.name}">
                            <div class="card-body">
                                <h5 class="card-title">${item.details.name}</h5>
                                <p class="card-text">Price: ₹${item.details.price}</p>
                                <p class="card-text">Quantity: 
                                    <button class="btn btn-sm btn-secondary" id="decreaseQuantity-${item.itemId}">-</button>
                                    <span id="quantity-${item.itemId}">${item.quantity}</span>
                                    <button class="btn btn-sm btn-secondary" id="increaseQuantity-${item.itemId}">+</button>
                                </p>
                                <button class="btn btn-link" id="addService-${item.itemId}">Add Service</button>
                                <button class="btn btn-danger btn-sm float-end" id="deleteItem-${item.itemId}">Delete</button>
                                <button class="btn btn-warning btn-sm float-end" id="editItem-${item.itemId}">Edit Quantity</button>
                                <label>
                                    <input type="checkbox" class="item-checkbox" data-item-id="${item.itemId}" data-price="${item.details.price}" data-quantity="${item.quantity}">
                                    Select this item
                                </label>
                            </div>
                        </div>
                    </div>`;

                cartItemsContainer.innerHTML += cartItemHTML;

                document.getElementById(`increaseQuantity-${item.itemId}`).addEventListener('click', () => updateQuantity(item.itemId, 1, item.details.price));
                document.getElementById(`decreaseQuantity-${item.itemId}`).addEventListener('click', () => updateQuantity(item.itemId, -1, item.details.price));

                document.getElementById(`addService-${item.itemId}`).addEventListener('click', () => {
                    selectedRoomId = item.itemId;
                    fetchServices();
                    serviceModal.show();
                });

                document.getElementById(`deleteItem-${item.itemId}`).addEventListener('click', () => deleteItem(item.itemId));

                document.getElementById(`editItem-${item.itemId}`).addEventListener('click', () => editItemQuantity(item.itemId, item.details.price));
            });

            cartItemsContainer.addEventListener('change', (e) => {
    if (e.target.classList.contains('item-checkbox')) {
        const itemPrice = parseFloat(e.target.dataset.price);
        const itemId = e.target.dataset.itemId;
        const itemQuantity = parseInt(e.target.dataset.quantity);

        if (e.target.checked) {
            // Add item price * quantity to total when checked
            const totalItemPrice = itemPrice * itemQuantity;
            selectedTotalPrice += totalItemPrice;
            selectedItems.push({
                itemId,
                price: itemPrice,
                quantity: itemQuantity,
                totalPrice: totalItemPrice
            });
        } else {
            // Remove item price * quantity from total when unchecked
            const totalItemPrice = itemPrice * itemQuantity;
            selectedTotalPrice -= totalItemPrice;
            selectedItems = selectedItems.filter(item => item.itemId !== itemId);
        }

        totalPriceElement.textContent = selectedTotalPrice.toFixed(2);
        bookNowButton.disabled = selectedTotalPrice === 0;
    }
});

bookNowButton.addEventListener('click', () => {
    // Lưu tổng giá của các item vào sessionStorage
    sessionStorage.setItem('totalPrice', selectedTotalPrice.toFixed(2));

    // Lưu thông tin chi tiết các phòng đã chọn vào sessionStorage
    sessionStorage.setItem('selectedItems', JSON.stringify(selectedItems));

    const selectedRoomIds = selectedItems.map(item => item.itemId);

    if (selectedRoomIds.length > 0) {
        // Truyền roomId vào URL khi nhấn Book Now
        window.location.href = `http://localhost/hbwebsite/frontend/booking.php?roomId=${selectedRoomIds.join(',')}`;
    } else {
        alert('Please select at least one room to book.');
    }
});


            function updateQuantity(itemId, change, itemPrice) {
                const quantityElement = document.getElementById(`quantity-${itemId}`);
                let quantity = parseInt(quantityElement.textContent);
                quantity += change;
                if (quantity >= 1) {
                    quantityElement.textContent = quantity;
                    const totalItemPrice = change * itemPrice;
                    selectedTotalPrice += totalItemPrice;
                    totalPriceElement.textContent = selectedTotalPrice.toFixed(2);
                    updateCart(itemId, quantity);
                }
            }

            async function updateCart(itemId, quantity) {
                const token = sessionStorage.getItem('accessToken');
                try {
                    const response = await fetch('http://localhost:5000/api/cart/update', {
                        method: 'PUT',
                        headers: {
                            'Authorization': 'Bearer ' + token,
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            itemId: itemId,
                            quantity: quantity,
                            type: 'room'
                        })
                    });

                    if (!response.ok) {
                        throw new Error('Failed to update cart');
                    }

                    const updatedCart = await response.json();
                    renderCart(updatedCart.items, updatedCart.createdAt);
                } catch (error) {
                    console.error('Error updating cart:', error);
                }
            }

            async function deleteItem(itemId) {
                try {
                    const token = sessionStorage.getItem('accessToken');
                    if (!token) {
                        alert('Please log in to delete items.');
                        return;
                    }

                    const response = await fetch(`http://localhost:5000/api/cart/${itemId}`, {
                        method: 'DELETE',
                        headers: {
                            'Authorization': 'Bearer ' + token
                        }
                    });

                    if (response.ok) {
                        document.getElementById(`cartItem-${itemId}`).remove();
                        fetchCartData();
                    } else {
                        const errorResponse = await response.json();
                        console.error('Lỗi khi xóa sản phẩm:', errorResponse);
                    }
                } catch (error) {
                    console.error('Error:', error);
                }
            }

            async function fetchServices() {
                try {
                    const response = await fetch('http://localhost:5000/api/services', {
                        method: 'GET',
                        headers: {
                            'Authorization': 'Bearer ' + sessionStorage.getItem('accessToken')
                        }
                    });

                    if (response.ok) {
                        const services = await response.json();
                        renderServiceList(services);
                    } else {
                        console.error('Error fetching services');
                    }
                } catch (error) {
                    console.error('Error:', error);
                }
            }

            function renderServiceList(services) {
                const serviceListElement = document.getElementById('serviceList');
                serviceListElement.innerHTML = '';
                services.forEach(service => {
                    const serviceHTML = `
                        <div>
                            <button class="btn btn-light" id="selectService-${service.id}" data-price="${service.price}">${service.name} - ₹${service.price}</button>
                        </div>`;
                    serviceListElement.innerHTML += serviceHTML;

                    document.getElementById(`selectService-${service.id}`).addEventListener('click', () => addService(service));
                });
            }

            function addService(service) {
                selectedServices.push(service);
                selectedTotalPrice += service.price;
                updateTotalPrice();
                serviceModal.hide(); 
            }

            function updateTotalPrice() {
                const serviceTotal = selectedServices.reduce((sum, service) => sum + service.price, 0);
                totalPriceElement.textContent = (selectedTotalPrice + serviceTotal).toFixed(2);
            }
        }
    </script>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>


</body>

</html>