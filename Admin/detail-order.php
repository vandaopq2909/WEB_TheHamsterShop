<?php 
    require_once "../class/Database.php";
    require_once "../class/Product.php"; 
    require_once "../class/DetailOrder.php"; 
    require_once "../class/Paginator.php";
    session_start();
    
    if (!isset($_SESSION["cart"])) {
        $_SESSION["cart"] = array();
    }

    if (!isset($_COOKIE["username"])) {
        header("location: index.php");
    }
    $conn = new Database();
    $pdo = $conn->getConnect();

    $data = DetailOrder::getAll($pdo);

    //var_dump($data);

    $title = "Admin Page - Chi Tiết Đơn Hàng";

    $order_id = $_GET["order_id"];

?>
    
<?php require_once "../inc/header_admin-page.php"?>
<div class="nav-products">
    <nav class="navbar bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand">Admin Page</a>

            <div class="group-user" style="display: flex; align-items: center;">
                <span class="navbar-text text-success">
                    Xin chào, <?=$_COOKIE["username"]?> !
                </span>

                <a type="button" class="btn btn-outline-danger mx-1" href="../logout.php">Đăng xuất</a>
            </div>
        </div>
    </nav>
</div>
<nav style="--bs-breadcrumb-divider: '>'; border-bottom: 1px solid black;" aria-label="breadcrumb">
    <ol class="breadcrumb m-0 p-2">
        <li class="breadcrumb-item" style="color: black;">Admin Page</li>
        <li class="breadcrumb-item" style="color: black;">Chi Tiết Đơn hàng: <strong><?=$order_id?></strong></li>
    </ol>
</nav>	

<div class="container-fluid mb-5" id="detail-order-form">

    <h2 class="text text-center text-danger pt-3 pb-3" style="text-transform: uppercase;">Chi Tiết Đơn Hàng: <strong><?=$order_id?></strong></h2>
    <div class="d-flex justify-content-end">
        <a type="button" class="btn btn-outline-success mx-1" href="order-management.php">Quay lại</a>
    </div>
    <table class="table table-bordered text-center mt-2">
        <thead>
            <tr>
                <th>Detail Order ID</th>
                <th>Order ID</th>
                <th>Tên Sản Phẩm</th>  
                <th>Số Lượng</th>
                <th>Đơn Giá</th>
            </tr>
        </thead>
        <tbody>
            <?php 
                $detailOrders = DetailOrder::getDetailOrdersByOrderID($pdo, $order_id);
                foreach ($detailOrders as $detailOrder) : ?>
                    <tr>
                        <td><?=$detailOrder->detail_order_id ?></td>
                        <td><?= $detailOrder->order_id?></td>
                        <?php $product = (object)Product::getOnceProductByID($pdo, $detailOrder->pro_id);?>
                        <td><?= $product->name?></td>  
                        <td><?= $detailOrder->quantity?></td>
                        <td><?= $detailOrder->price?></td>  
                    </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</div>
<?php require_once "../inc/footer_admin-page.php"?>