<?php 
    require_once "../class/Database.php"; 
    require_once "../class/Order.php"; 
    
    if(!isset($_COOKIE['username'])) {
        die('Bạn phải đăng nhập!!!!!');
    }
    elseif($_COOKIE['username'] == 'admin') {
        echo 'Admin is logged in | '. date('d-m-Y') . '<br/>';
    }


    $conn = new Database();
    $pdo = $conn->getConnect();

    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        $order_id = $_GET["order_id"];

        if(empty($order_id)) {
            header("location: admin-page.php");
        }
        Order::deleteOrderByOrderID($pdo, $order_id);
        header("location: order-management.php");
    }

?>