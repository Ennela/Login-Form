<?php
session_start();

// Hủy tất cả biến session
$_SESSION = array();

// Phá hủy session
session_destroy();

// Redirect về trang đăng nhập
header("location: login.php");
exit;
?>
