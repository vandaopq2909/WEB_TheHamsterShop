<?php
require_once "inc/init.php";
require_once "class/Product.php";
require_once "class/User.php";
require_once "class/Category.php";
    $conn = new Database();
    $pdo = $conn->getConnect();

$usernameErr = '';
$emailErr = '';
$passwordErr = '';
$confirmPassErr = '';

$username = '';
$email = '';
$password = '';
$confirmPass = '';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST["username"];
        $email = $_POST["email"];
        $password = $_POST["password"];
        $confirmPass = $_POST["confirmPass"];

        if(empty($username)) {
            $usernameErr = "Bắt buộc nhập username!";
        }
        elseif(!User::isUsernameExists($pdo, $username)) {
            $usernameErr = "Username đã tồn tại!!!";
        }
        if(empty($email)) {
            $emailErr = "Bắt buộc nhập email!";
        }
        elseif(!preg_match('/^\\S+@\\S+\\.\\S+$/', $email)) {
            $emailErr = "Email chưa đúng!";
        }
        if(empty($password)) {
            $passwordErr = "Phải nhập mật khấu!";
        }
        elseif(!preg_match("/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/", $password)) {
            $passwordErr = "Mật Khẩu phải từ 8 kí tự, có in hoa, có số từ 0-9!";
        }
        if(empty($confirmPass)) {
            $confirmPassErr = "Phải nhập lại mật khấu!";
        }
        elseif($confirmPass != $password) {
            $confirmPassErr = "Mật khẩu không trùng khớp!";
        }
        if(!$usernameErr && !$emailErr && !$passwordErr && !$confirmPassErr) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT, ['cost' => 10]);
            $user = new User($username, $hashed_password, $email);
            
            if(User::addOneUser($pdo, $user)) {
                echo "Đăng ký thành công!";
                header("location: login.php");
            }
            else {
                echo "Lỗi!!!!";
            }
        }
    }

    $title = "Đăng ký";
?>

<?php require_once "inc/header_nonslider.php"; ?>
<div class="container-fluid" style="margin-top: 56px;">
<h1 class="text text-center text-danger pt-3" style="text-transform: uppercase;">Đăng Ký</h1>
    <form class="w-50 m-auto" method="post">
        <div class="mb-3">
            <label for="username" class="form-label">Tên tài khoản</label>
            <input class="form-control" id="username" name="username" value="<?= $username ?>">
            <span class="text-danger"><?= $usernameErr ?></span>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input class="form-control" id="email" name="email" type="email" value="<?= $email ?>">
            <span class="text-danger"><?= $emailErr ?></span>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Mật khẩu</label>
            <input class="form-control" id="password" name="password" type="password" value="<?= $password ?>">
            <span class="text-danger"><?= $passwordErr ?></span>
        </div>
        <div class="mb-3">
            <label for="confirmPass" class="form-label">Nhập lại mật khẩu</label>
            <input class="form-control" id="confirmPass" name="confirmPass" type="password" value="<?= $confirmPass ?>">
            <span class="text-danger"><?= $confirmPassErr ?></span>
        </div>
        <div class="mb-3 d-flex justify-content-center">
            <button class="btn btn-primary" type="submit">Đăng Ký</button>
        </div>
        <div>
            <p>Bạn đã có tài khoản? <a href="login.php">Đăng nhập</a></p> 
        </div>
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.carousel.js"></script>
</div>
<?php require_once "inc/footer.php"; ?>