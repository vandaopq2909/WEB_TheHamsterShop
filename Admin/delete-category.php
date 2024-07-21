<?php 
    require_once "../class/Database.php"; 
    require_once "../class/Category.php"; 
    
    if(!isset($_COOKIE['username'])) {
        die('Bạn phải đăng nhập!!!!!');
    }
    elseif($_COOKIE['username'] == 'admin') {
        echo 'Admin is logged in | '. date('d-m-Y') . '<br/>';
    }


    $conn = new Database();
    $pdo = $conn->getConnect();

    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        $cat_id = $_GET["cat_id"];

        if(empty($cat_id)) {
            header("location: admin-page.php");
        }
        Category::deleteCategoryByCatID($pdo, $cat_id);
        header("location: category-management.php");
    }

?>