<?php 
    require_once "../class/Database.php"; 
    require_once "../class/User.php"; 
    
    if(!isset($_COOKIE['username'])) {
        die('Bạn phải đăng nhập!!!!!');
    }
    elseif($_COOKIE['username'] == 'admin') {
        echo 'Admin is logged in | '. date('d-m-Y') . '<br/>';
    }


    $conn = new Database();
    $pdo = $conn->getConnect();

    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        $username = $_GET["username"];

        if(empty($username)) {
            header("location: admin-page.php");
        }
        User::deleteUserByUsername($pdo, $username);
        header("location: user-management.php");
    }

?>