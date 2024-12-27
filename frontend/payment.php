<?php
// Get URL parameters if needed (optional)
$showLoginModal = isset($_GET['showLoginModal']) && $_GET['showLoginModal'] === 'true';
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
</head>

<body class="bg-light">
    <?php require('inc/header.php'); ?>

    <!-- Payment Form -->
    <div class="container mt-5">
        <h2>Room Payment</h2>
        <form id="paymentForm" method="POST">
            <div class="form-group">
                <label for="invoiceNumber">Invoice Number</label>
                <input type="text" id="invoiceNumber" name="invoiceNumber" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="customerId">Customer ID</label>
                <input type="text" id="customerId" name="customerId" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="amount">Amount</label>
                <input type="number" id="amount" name="amount" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <input type="text" id="description" name="description" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Pay Now</button>
        </form>
    </div>

    <?php require('inc/footer.php'); ?>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>

    <script>
        // Show login modal if necessary
        if (<?php echo $showLoginModal ? 'true' : 'false'; ?>) {
            const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
            loginModal.show();
        }

        // Form submission to backend for payment initiation
        document.getElementById('paymentForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent default form submission
            const formData = new FormData(this);
            
            // Send data to backend to create the bill and initiate payment
            fetch('http://localhost:5000/api/payments', {  // Assuming this is your payment API endpoint
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    window.location.href = data.paymentUrl; // Redirect to MoMo payment page
                } else {
                    alert('Payment initiation failed');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while processing your payment.');
            });
        });
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