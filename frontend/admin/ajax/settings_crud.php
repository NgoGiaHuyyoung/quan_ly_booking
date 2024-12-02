<?php
require('../inc/db_config.php');
require('../inc/essentials.php');
adminLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = isset($_POST['action']) ? $_POST['action'] : null;

    switch ($action) {
        case 'get_general':
            $q = "SELECT * FROM `settings` WHERE `sr_no`=?";
            $values = [1];
            $res = select($q, $values, "i");
            $data = $res ? mysqli_fetch_assoc($res) : [];
            echo json_encode($data);
            break;

        case 'upd_general':
            $frm_data = filteration($_POST);
            $q = "UPDATE `settings` SET `site_title`=?, `site_about`=? WHERE `sr_no`=?";
            $values = [$frm_data['site_title'], $frm_data['site_about'], 1];
            echo update($q, $values, "ssi");
            break;

        case 'upd_shutdown':
            $shutdown_status = isset($_POST['upd_shutdown']) && $_POST['upd_shutdown'] == 0 ? 1 : 0;
            $q = "UPDATE `settings` SET `shutdown`=? WHERE `sr_no`=?";
            $values = [$shutdown_status, 1];
            echo update($q, $values, "ii");
            break;

        case 'get_contacts':
            $q = "SELECT * FROM `contact_details` WHERE `sr_no`=?";
            $values = [1];
            $res = select($q, $values, "i");
            $data = $res ? mysqli_fetch_assoc($res) : [];
            echo json_encode($data);
            break;

        case 'upd_contacts':
            $frm_data = filteration($_POST);
            $q = "UPDATE `contact_details` SET `address`=?, `gmap`=?, `pn1`=?, `pn2`=?, `email`=?, `fb`=?, `insta`=?, `tw`=?, `iframe`=? WHERE `sr_no`=?";
            $values = [
                $frm_data['address'], $frm_data['gmap'], $frm_data['pn1'], $frm_data['pn2'],
                $frm_data['email'], $frm_data['fb'], $frm_data['insta'], $frm_data['tw'],
                $frm_data['iframe'], $frm_data['sr_no']
            ];
            echo update($q, $values, 'sssssssssi');
            break;

        case 'add_member':
            if (isset($_FILES['picture']) && $_FILES['picture']['error'] == UPLOAD_ERR_OK) {
                uploadImage($_FILES['picture'], ABOUT_FOLDER);
                echo "Member added successfully.";
            } else {
                echo "Error uploading file.";
            }
            break;

        default:
            echo "Invalid action.";
    }
}
?>
