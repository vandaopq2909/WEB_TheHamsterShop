<?php 
    require_once "../class/Database.php"; 
    require_once "../class/Product.php"; 
    
    if(!isset($_COOKIE['username'])) {
        die('Bạn phải đăng nhập!!!!!');
    }
    elseif($_COOKIE['username'] == 'admin') {
        echo 'Admin is logged in | '. date('d-m-Y') . '<br/>';
    }


    $conn = new Database();
    $pdo = $conn->getConnect();

    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        $id = $_GET["id"];

        if(empty($id)) {
            header("location: admin-page.php");
        }
        Product::deleteProductByID($pdo, $id);
        header("location: admin-page.php");
    }

?>