<?php
session_start();
require_once 'db.php';

// Đã đăng nhập thì chuyển hướng vào trong
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: welcome.php");
    exit;
}

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($username) || empty($password)) {
        $error = "Vui lòng nhập đầy đủ tên đăng nhập và mật khẩu.";
    } else {
        try {
            $stmt = $pdo->prepare("SELECT id, username, password FROM users WHERE username = :username");
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->execute();

            if ($stmt->rowCount() == 1) {
                if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $hashed_password = $row['password'];
                    // Kiểm tra (băm) mật khẩu
                    if (password_verify($password, $hashed_password)) {
                        $_SESSION['loggedin'] = true;
                        $_SESSION['id'] = $row['id'];
                        $_SESSION['username'] = $row['username'];

                        header("location: welcome.php");
                        exit;
                    } else {
                        $error = "Đăng nhập thất bại! Sai mật khẩu.";
                    }
                }
            } else {
                $error = "Đăng nhập thất bại! Tên người dùng không tồn tại.";
            }
        } catch (PDOException $e) {
            $error = "Lỗi hệ thống, vui lòng thử lại sau.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập Hệ Thống</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">
        <h2>Đăng Nhập</h2>
        <?php
        if (!empty($error)) {
            echo '<div class="alert alert-error">' . htmlspecialchars($error) . '</div>';
        }
        if (isset($_SESSION['success_msg'])) {
            echo '<div class="alert alert-success">' . htmlspecialchars($_SESSION['success_msg']) . '</div>';
            unset($_SESSION['success_msg']);
        }
        ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Tên người dùng</label>
                <input type="text" name="username" placeholder="Nhập username" required
                    value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>">
            </div>
            <div class="form-group">
                <label>Mật khẩu</label>
                <input type="password" name="password" placeholder="Nhập password" required>
            </div>
            <div class="form-group">
                <button type="submit" class="btn">Đăng nhập ngay</button>
            </div>
            <div class="switch-link">
                Chưa có tài khoản? <a href="register.php">Đăng ký ngay</a>
            </div>
        </form>
    </div>
</body>

</html>