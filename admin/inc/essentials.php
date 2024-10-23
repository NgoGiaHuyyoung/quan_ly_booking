<?php


function adminLogin()
 {
    session_start();
    if (!(isset($_SESSION['adminLogin']) && $_SESSION['adminLogin'] === true)) {
        echo "<script>
        window.location.href='index.php';
        </script>";
        exit;
    }
}


function redirect($url) {
    echo "<script>
    window.location.href='$url';
    </script>";
    exit; 
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