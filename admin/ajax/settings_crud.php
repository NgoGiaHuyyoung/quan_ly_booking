<?php
    require('../inc/db_config.php');
    require('../inc/essentials.php');
    adminLogin();

    if(isset($_POST['get_general']))
    {
        $q = "SELECT * FROM `settings` WHERE  `sr_no`=?";
        $values = [1];
        $res = select($q,$values,"i");
        $data = mysqli_fetch_assoc($res);
        $json_data = json_encode($data);
        echo $json_data;
    }

    if(isset($_POST['upd_general']))
    {
        $frm_data = filteration($_POST);

        $q ="UPDATE `settings` SET `site_title`=?, `site_about`=? WHERE `sr_no`=?";
        $values = [$frm_data['site_title'],$frm_data['site_about'],1];
        $res = update($q,$values,"ssi");
        echo $res;
    }

    if (isset($_POST['upd_shutdown'])) {
        $frm_data = ($_POST['upd_shutdown'] == 0) ? 1 : 0;
    
        // Giả định bạn có một biến $sr_no chứa giá trị cần thiết
        $sr_no = 1; // Hoặc lấy giá trị này từ một nguồn khác
    
        $q = "UPDATE `settings` SET `shutdown`=?, `site_about`=? WHERE `sr_no`=?";
        $values = [$frm_data, 1, $sr_no]; // Thêm $sr_no vào mảng
        $res = update($q, $values, "iii"); // Cập nhật kiểu tham số cho đúng
        echo $res;
    }
    
?>