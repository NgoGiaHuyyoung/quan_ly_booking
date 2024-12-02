<?php

define('ABOUT_FOLDER', '../images/about/');
define('UPLOAD_IMAGE_PATH',$_SERVER['DOCUMENT_ROOT'].'/hbwebsite/images/');
define('UPLOAD_IMAGE_URL','about/');

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

function uploadImage($image,$folder)
{
    $valid_mime = ['image/jped','image/png','image/webp'];
    $img_mime = $image['type'];

    if(!in_array($img_mime,$valid_mime)){
        return 'inv_img';
    }
    else if(($image['size']/(1024*1024))>2){
        return 'inv_size';
    }
    else{
        $ext = pathinfo($image['name'],PATHINFO_EXTENSION);
    }
}
?>