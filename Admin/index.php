<?php
require_once "../class/Database.php"; 
require_once "../class/Product.php"; 
session_start();

if (!isset($_SESSION["cart"])) {
    $_SESSION["cart"] = array();
}

$conn = new Database();
$pdo = $conn->getConnect();

$usernameErr = '';
$passwordErr = '';
        
$checkUser = '';
$checkPass = '';
$username = '';
$password = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $stmt = $pdo->prepare('SELECT * from user');
    //Thiết lập kiểu dữ liệu trả về
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $stmt->execute();
    $resultSet = $stmt->fetchAll();

    foreach ($resultSet as $row) {
        if($username == $row['username'] && password_verify($password, $row['password'])) {
            $checkUser = $username;
            $checkPass = $password;
        }
        
    }
    if(empty($username)) {
        $usernameErr = "Bắt buộc nhập username!";
    }
    elseif(!preg_match('/^[a-zA-Z0-9]+([._]?[a-zA-Z0-9]+)*$/', $username)) {
        $usernameErr = "Username chưa đúng!";
    }
    if(empty($password)) {
        $passwordErr = "Phải nhập mật khấu!";
    }
    if(!$usernameErr && !$passwordErr) {
        if($username == $checkUser && $password == $checkPass && $username == "admin") {
            $_COOKIE["username"] = $username;
            $_COOKIE["password"] = $password;
            setcookie("username", $username, time() + (86400 * 30),"/");
            setcookie("password", $password, time() + (86400 * 30),"/");

            header("location: admin-page.php");
        }
        else {
            $passwordErr = "username hoặc password không đúng!";
        }
    }
}

$title = "Admin - Đăng nhập";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=$title?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.carousel.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <div class="container-fluid" style="margin-top: 56px;">
        <h1 class="text text-center text-danger pt-3" style="text-transform: uppercase;">Admin - Đăng Nhập</h1>
        <form class="w-50 m-auto" method="post">
            <div class="mb-3">
                <label for="username" class="form-label">Tên tài khoản</label>
                <input class="form-control" id="username" name="username" value="<?= $username ?>">
                <span class="text-danger"><?= $usernameErr ?></span>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Mật khẩu</label>
                <input class="form-control" id="password" name="password" type="password" value="<?= $password ?>">
                <span class="text-danger"><?= $passwordErr ?></span>
            </div>
            <button class="btn btn-primary" type="submit">Đăng Nhập</button>
        </form>
    
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.carousel.js"></script>
    </div>
</body>
</html>
