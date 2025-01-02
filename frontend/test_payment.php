<?php
// Get URL parameters if needed (optional)
$showLoginModal = isset($_GET['showLoginModal']) && $_GET['showLoginModal'] === 'true';
$roomId = isset($_GET['id']) && !empty($_GET['id']) ? $_GET['id'] : null;

// Hàm gửi yêu cầu POST
function execPostRequest($url, $data) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($data)
    ));
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

// Xử lý yêu cầu thanh toán MoMo
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Các thông tin cấu hình MoMo
    $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";
    $partnerCode = "MOMOBKUN20180529";
    $accessKey = "klm05TvNBzhg7h7j";
    $secretKey = "at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa";
    $redirectUrl = "https://your-redirect-url.com"; // URL chuyển hướng sau thanh toán
    $ipnUrl = "https://your-ipn-url.com"; // URL thông báo trạng thái thanh toán

    // Lấy thông tin từ form
    $orderInfo = $_POST['description'] ?? "Thanh toán qua MoMo";
    $amount = $_POST['amount'] ?? "0";
    $orderId = time() . "";
    $extraData = "";

    $requestId = time() . "";
    $requestType = "captureWallet";

    // Tạo chữ ký
    $rawHash = "accessKey=$accessKey&amount=$amount&extraData=$extraData&ipnUrl=$ipnUrl&orderId=$orderId&orderInfo=$orderInfo&partnerCode=$partnerCode&redirectUrl=$redirectUrl&requestId=$requestId&requestType=$requestType";
    $signature = hash_hmac("sha256", $rawHash, $secretKey);

    // Dữ liệu gửi đến MoMo
    $data = array(
        'partnerCode' => $partnerCode,
        'partnerName' => "MoMo",
        'storeId' => "MoMoTestStore",
        'requestId' => $requestId,
        'amount' => $amount,
        'orderId' => $orderId,
        'orderInfo' => $orderInfo,
        'redirectUrl' => $redirectUrl,
        'ipnUrl' => $ipnUrl,
        'lang' => 'vi',
        'extraData' => $extraData,
        'requestType' => $requestType,
        'signature' => $signature
    );

    // Gửi yêu cầu đến MoMo
    $result = execPostRequest($endpoint, json_encode($data));
    $jsonResult = json_decode($result, true);

    // Trả về URL thanh toán
    echo json_encode(array(
        'status' => 'success',
        'paymentUrl' => $jsonResult['payUrl']
    ));
    exit();
}
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

    <script>
        // Xử lý gửi form
        document.getElementById('paymentForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Ngăn chặn form gửi mặc định
            const formData = new FormData(this);
            
            // Gửi yêu cầu AJAX tới server
            fetch('payment.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    window.location.href = data.paymentUrl; // Chuyển đến trang thanh toán MoMo
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
</body>

</html>
