<?php

// Hàm kiểm tra đăng nhập admin
function adminLogin() {
    // Khởi động phiên
    session_start();

    // Kiểm tra trạng thái đăng nhập
    if (!(isset($_SESSION['adminLogin']) && $_SESSION['adminLogin'] === true)) {
        // Chuyển hướng đến trang đăng nhập nếu chưa đăng nhập
        echo "<script>
        window.location.href='index.php';
        </script>";
        exit; // Dừng thực thi script sau khi chuyển hướng
    }

    // Tái tạo ID phiên để tăng cường bảo mật
    session_regenerate_id(true);
}

// Hàm chuyển hướng đến URL cụ thể
function redirect($url) {
    // Chuyển hướng đến URL
    echo "<script>
    window.location.href='$url';
    </script>";
    exit; // Dừng thực thi script sau khi chuyển hướng
}

// Hàm hiển thị thông báo
function alert($type, $msg) {
    // Xác định lớp CSS dựa trên loại thông báo
    $bs_class = ($type === "success") ? "alert-success" : "alert-danger";

    // Hiển thị thông báo
    echo <<<alert
    <div class="alert $bs_class alert-dismissible fade show custom-alert" role="alert">
        <strong class="me-3">$msg</strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
alert;
}

?>