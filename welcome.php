<?php
session_start();

// Redirect nếu chưa đăng nhập
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Chủ - Thành Viên</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container welcome-container">

        <h1>Xin chào, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>!</h1>
        <p>Chúc mừng bạn đã đăng nhập thành công. Đây là không gian riêng tư được bảo vệ dành riêng cho thành viên đã
            xác thực của hệ thống.</p>

        <div style="margin-top: 40px; display: flex; gap: 15px; justify-content: center;">
            <a href="#" class="btn" style="width: auto; padding: 12px 30px; text-decoration: none;">Trang cá nhân</a>
            <a href="logout.php" class="btn btn-outline"
                style="width: auto; padding: 12px 30px; text-decoration: none;">Đăng xuất</a>
        </div>
    </div>
</body>

</html>