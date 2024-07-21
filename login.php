<?php
require_once "inc/init.php";
require_once "class/Product.php";
require_once "class/Category.php";

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
        if($username == $checkUser && $password == $checkPass) {
            $_COOKIE["username"] = $username;
            $_COOKIE["password"] = $password;
            setcookie("username", $username, time() + (86400 * 30),"/");
            setcookie("password", $password, time() + (86400 * 30),"/");

            header("location: index.php");
        }
        else {
            $passwordErr = "username hoặc password không đúng!";
        }
    }
}

$title = "Đăng nhập";
?>

<?php require_once "inc/header_nonslider.php"; ?>
<div class="container-fluid" style="margin-top: 56px;">
    <h1 class="text text-center text-danger pt-3" style="text-transform: uppercase;">Đăng Nhập</h1>
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
        <div class="mb-3 d-flex justify-content-center">
            <button class="btn btn-primary" type="submit">Đăng Nhập</button>
        </div>
        <div>
            <p>Bạn chưa có tài khoản? <a href="register.php">Đăng ký</a></p> 
        </div>
    </form>
 
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.carousel.js"></script>
</div>
<?php require_once "inc/footer.php"; ?>