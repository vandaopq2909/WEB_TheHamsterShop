<?php 
    require_once "../class/Database.php"; 
    require_once "../class/Product.php"; 
    require_once "../class/User.php";
    

    $conn = new Database();
    $pdo = $conn->getConnect();

    if(!isset($_COOKIE['username'])) {
        die('Bạn phải đăng nhập!!!!!');
    }
    elseif($_COOKIE['username'] == 'admin') {
        echo 'Admin is logged in | '. date('d-m-Y') . '<br/>';
    }

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
                    echo "Thêm user thành công!";
                    header("location: user-management.php");
                }
                else {
                    echo "Lỗi!!!!";
                }
            }
        }

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?= $title?></title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.carousel.css" rel="stylesheet"/>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    </head>
    <style>
        .list-group a {
            background-color: #3d4b64;
            color: white;
        }
    </style>
<body>
    <header></header>
    <main>
        <h1 class="text text-center text-danger pt-3" style="text-transform: uppercase;">Thêm User Mới</h1>
        <form class="w-50 m-auto" method="post">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input class="form-control" id="username" name="username" value="<?= $username ?>">
                <span class="text-danger"><?= $usernameErr ?></span>
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
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input class="form-control" id="email" name="email" value="<?= $email ?>">
                <span class="text-danger"><?= $emailErr ?></span>
            </div> 
            
            <button class="btn btn-primary" type="submit">Thêm User</button>
        </form>


        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.carousel.js"></script>
    </main>
    <footer></footer>
</body>
</html>