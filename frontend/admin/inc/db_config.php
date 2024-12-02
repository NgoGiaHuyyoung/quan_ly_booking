<?php

// Thông tin kết nối đến cơ sở dữ liệu
$hname = 'localhost';
$uname = 'root';
$pass = '';
$db = 'hbwebsite';

// Kết nối đến cơ sở dữ liệu
$con = mysqli_connect($hname, $uname, $pass, $db);

// Kiểm tra kết nối
if (!$con) {
    die("Cannot Connect to Database: " . mysqli_connect_error());
}

// Hàm lọc dữ liệu đầu vào
function filteration($data) {
    foreach ($data as $key => $value) {
        // Lọc và làm sạch dữ liệu
        $data[$key] = trim($value);
        $data[$key] = stripslashes($data[$key]); // Sửa lại để sử dụng đúng biến
        $data[$key] = htmlspecialchars($data[$key]);
        $data[$key] = strip_tags($data[$key]);
    }
    return $data;
}

// Hàm thực hiện truy vấn SELECT
function select($sql, $values, $datatypes) {
    $con = $GLOBALS['con'];
    if ($stmt = mysqli_prepare($con, $sql)) {
        mysqli_stmt_bind_param($stmt, $datatypes, ...$values);
        if (mysqli_stmt_execute($stmt)) {
            $res = mysqli_stmt_get_result($stmt);
            mysqli_stmt_close($stmt);
            return $res;
        } else {
            mysqli_stmt_close($stmt);
            die("Query cannot be executed - Select");
        }
    } else {
        die("Query cannot be prepared - Select");
    }
}

// Hàm thực hiện truy vấn UPDATE
function update($sql, $values, $datatypes) {
    $con = $GLOBALS['con'];
    if ($stmt = mysqli_prepare($con, $sql)) {
        mysqli_stmt_bind_param($stmt, $datatypes, ...$values);
        if (mysqli_stmt_execute($stmt)) {
            $res = mysqli_stmt_affected_rows($stmt);
            mysqli_stmt_close($stmt);
            return $res;
        } else {
            mysqli_stmt_close($stmt);
            die("Query cannot be executed - Update");
        }
    } else {
        die("Query cannot be prepared - Update");
    }
}
?>