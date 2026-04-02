<?php
$host = 'localhost';
$username = 'root';
$password = ''; // Default XAMPP password

try {
    // Kết nối MySQL server trước (chưa chọn database)
    $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Tạo Database nếu chưa có
    $sql = "CREATE DATABASE IF NOT EXISTS php_login_demo CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
            USE php_login_demo;
            
            -- Tạo bảng users
            CREATE TABLE IF NOT EXISTS users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                username VARCHAR(50) NOT NULL UNIQUE,
                password VARCHAR(255) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            );";
    
    $pdo->exec($sql);
    
    echo "<div style='font-family: sans-serif; text-align: center; margin-top: 50px; background: #f0fdf4; padding: 30px; border-radius: 10px; border: 1px solid #bbf7d0; max-width: 500px; margin-left: auto; margin-right: auto;'>";
    echo "<h2 style='color: #166534;'>🎉 Khởi tạo cơ sở dữ liệu thành công!</h2>";
    echo "<p style='color: #15803d; margin-bottom: 20px;'>Database <b>php_login_demo</b> và bảng <b>users</b> đã sẵn sàng.</p>";
    echo "<a href='register.php' style='padding: 10px 20px; background: #4f46e5; color: white; text-decoration: none; border-radius: 5px; font-weight: bold;'>Đi đến trang Đăng ký</a>";
    echo "</div>";
    
} catch(PDOException $e) {
    die("Lỗi tạo CSDL: " . $e->getMessage() . "<br>Vui lòng đảm bảo MySQL server (XAMPP) đang chạy.");
}
?>
