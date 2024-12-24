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
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f5f5f5;
    }

    .container {
      display: flex;
      justify-content: space-between;
      padding: 20px;
    }

    .cart-items {
      width: 70%;
      background-color: #fff;
      padding: 20px;
      border-radius: 5px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .cart-summary {
      width: 25%;
      background-color: #fff;
      padding: 20px;
      border-radius: 5px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .cart-item {
      display: flex;
      justify-content: space-between;
      border-bottom: 1px solid #ddd;
      padding: 10px 0;
    }

    .cart-item:last-child {
      border-bottom: none;
    }

    .cart-item img {
      width: 100px;
      height: 100px;
      border-radius: 5px;
    }

    .cart-item-details {
      flex: 1;
      margin-left: 20px;
    }

    .cart-item-details h4 {
      margin: 0;
      font-size: 18px;
    }

    .cart-item-details p {
      margin: 5px 0;
      color: #666;
    }

    .cart-item-price {
      text-align: right;
    }

    .cart-item-price p {
      margin: 5px 0;
    }

    .cart-item-price .original-price {
      text-decoration: line-through;
      color: #999;
    }

    .cart-item-price .discounted-price {
      color: #e74c3c;
      font-size: 18px;
    }

    .cart-summary h3 {
      margin: 0 0 20px;
      font-size: 24px;
    }

    .cart-summary p {
      margin: 10px 0;
      font-size: 18px;
    }

    .cart-summary .total-price {
      color: #e74c3c;
      font-size: 24px;
    }

    .cart-summary .next-button {
      display: block;
      width: 100%;
      padding: 10px;
      background-color: #007bff;
      color: #fff;
      text-align: center;
      border-radius: 5px;
      text-decoration: none;
      font-size: 18px;
      margin-top: 20px;
    }

    .cart-summary .next-button:hover {
      background-color: #0056b3;
    }

    .cart-footer {
      text-align: center;
      margin-top: 20px;
    }

    .cart-footer a {
      color: #007bff;
      text-decoration: none;
    }

    .cart-footer a:hover {
      text-decoration: underline;
    }

    @media (max-width: 768px) {
      .container {
        flex-direction: column;
      }

      .cart-items,
      .cart-summary {
        width: 100%;
        margin-bottom: 20px;
      }
    }
  </style>
  <?php require('inc/links.php'); ?>
</head>

<body class="bg-light">

  <?php require('inc/header.php'); ?>

  <!-- Modal for Cart -->
  <div class="modal fade" id="cartModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="cartModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="cartModalLabel">Your Cart</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div id="cartItems" class="row">
            <!-- Cart items will be dynamically injected here -->
          </div>
        </div>
        <div class="modal-footer">
          <p>Total Price: ₹<span id="totalPrice">0</span></p>
          <button type="button" class="btn btn-primary">Proceed to Checkout</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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

    // Fetch and display cart data
    async function fetchCartData() {
      try {
        const token = sessionStorage.getItem('accessToken');
        if (!token) {
          console.log('No token found in sessionStorage');
          alert('Please log in to view your cart.');
          return;
        }

        const response = await fetch('http://localhost:5000/api/cart', {
          method: 'GET',
          headers: {
            'Authorization': 'Bearer ' + token
          }
        });

        console.log('API Response Status:', response.status);
        console.log('API Response Headers:', response.headers);
        console.log('API Response URL:', response.url);

        if (!response.ok) {
          throw new Error('Error fetching cart data');
        }

        const cartData = await response.json();
        console.log('Cart Data:', cartData);
        console.log('Is Items an Array?', Array.isArray(cartData.items)); // Kiểm tra items có phải là mảng không

        if (cartData.items && Array.isArray(cartData.items)) {
          // Nếu items là mảng, tiếp tục duyệt qua các mục trong giỏ hàng
          const cartItemsContainer = document.getElementById('cartItems');
          cartItemsContainer.innerHTML = '';

          let totalPrice = 0; // Khởi tạo biến tổng tiền

          cartData.items.forEach(item => {
            const itemPrice = item.details.price;
            const itemTotal = item.quantity * itemPrice;
            totalPrice += itemTotal; // Cộng dồn tổng tiền

            const cartItemHTML = `
              <div class="col-lg-4 col-md-6 mb-4">
                <div class="card">
                  <img src="${item.details.images[0]}" class="card-img-top" alt="${item.details.name}">
                  <div class="card-body">
                    <h5 class="card-title">${item.details.name}</h5>
                    <p class="card-text">Price: ₹${itemPrice}</p>
                    <p class="card-text">Quantity: ${item.quantity}</p>
                    <p class="card-text">Total: ₹${itemTotal}</p>
                  </div>
                </div>
              </div>
            `;
            cartItemsContainer.innerHTML += cartItemHTML;
          });

          // Cập nhật tổng giá trị vào phần tử #totalPrice
          document.getElementById('totalPrice').textContent = totalPrice;

          // Mở modal giỏ hàng
          const cartModal = new bootstrap.Modal(document.getElementById('cartModal'));
          cartModal.show();
        } else {
          // Nếu items không phải là mảng, xử lý trường hợp này
          alert('Cart items are not in the expected format.');
        }

      } catch (error) {
        console.error('Error fetching cart data:', error);
        alert('Error fetching cart data: ' + error.message);
      }
    }

    // Đảm bảo hàm fetchCartData chỉ chạy sau khi DOM được tải
    document.addEventListener('DOMContentLoaded', fetchCartData);
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