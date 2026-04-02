<?php
session_start();
require_once 'db.php';

// Đã đăng nhập thì chuyển hướng
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: welcome.php");
    exit;
}

$error = '';
$username = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirm_password = trim($_POST['confirm_password'] ?? '');

    if (empty($username) || empty($password) || empty($confirm_password)) {
        $error = "Vui lòng điền đầy đủ tất cả thông tin.";
    } elseif ($password !== $confirm_password) {
        $error = "Mật khẩu xác nhận không trùng khớp.";
    } elseif (strlen($password) < 6) {
        $error = "Mật khẩu phải có ít nhất 6 ký tự để đảm bảo an toàn.";
    } else {
        try {
            // Kiểm tra username tồn tại
            $stmt = $pdo->prepare("SELECT id FROM users WHERE username = :username");
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $error = "Tên người dùng này đã được sử dụng, vui lòng chọn tên khác.";
            } else {
                // Thêm vào database
                $sql = "INSERT INTO users (username, password) VALUES (:username, :password)";
                if ($stmt = $pdo->prepare($sql)) {
                    // Hash password 
                    $param_password = password_hash($password, PASSWORD_DEFAULT);

                    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
                    $stmt->bindParam(':password', $param_password, PDO::PARAM_STR);

                    if ($stmt->execute()) {
                        $_SESSION['success_msg'] = "Đăng ký thành công! Bạn có thể đăng nhập bằng tài khoản vừa tạo.";
                        header("location: login.php");
                        exit;
                    } else {
                        $error = "Đã xảy ra lỗi trong quá trình đăng ký. Vui lòng thử lại sau.";
                    }
                }
            }
        } catch (PDOException $e) {
            $error = "Lỗi hệ thống: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Ký Tài Khoản</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">
        <h2>Tạo Tài Khoản Mới</h2>
        <?php
        if (!empty($error)) {
            echo '<div class="alert alert-error">' . htmlspecialchars($error) . '</div>';
        }
        ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Tên người dùng</label>
                <input type="text" name="username" placeholder="Tên đăng nhập" required
                    value="<?php echo htmlspecialchars($username); ?>">
            </div>
            <div class="form-group">
                <label>Mật khẩu</label>
                <input type="password" name="password" placeholder="Tối thiểu 6 ký tự" required>
            </div>
            <div class="form-group">
                <label>Xác nhận mật khẩu</label>
                <input type="password" name="confirm_password" placeholder="Nhập lại mật khẩu" required>
            </div>
            <div class="form-group">
                <button type="submit" class="btn">Đăng ký hoàn tất</button>
            </div>
            <div class="switch-link">
                Đã có tài khoản? <a href="login.php">Đăng nhập ngay</a>
            </div>
        </form>
    </div>
</body>

</html>