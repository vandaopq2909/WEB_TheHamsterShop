<?php
    $conn = new Database();
    $pdo = $conn->getConnect();
    $listNameHamster = Category::getNameCategoriesHamster($pdo);
    $listNameProductHamster = Category::getNameCategoriesProHamster($pdo);
    //var_dump($listNameHamster);

    $hamsterCat_id = "ch_ht";
    $proHamsterCat_id = "sp_ht";

    $total_quantity = 0;
    foreach ($_SESSION["cart"] as $item) {
        $qty = $item["qty"];
        
        // Tính tổng số lượng
        $total_quantity += $qty;
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
    body {
        list-style-type: none;
    }
    /* card-title */
    a {
        text-decoration: none;
    }
    .card-title {
        color: black;
        transition: color 0.3s ease; /* Áp dụng hiệu ứng mượt mà */
    }

    .card-title:hover {
        color: green; /* Chuyển màu sang xanh lá khi hover */
    }
    .card a img {
        width: 254px;
        height: 254px;
        object-fit: cover;
        object-position: 50% 50%; 
    }
     .stretch-card>.card {
     width: 100%;
     min-width: 100%
    }

    .owl-carousel .item{
    margin: 3px;
    }
    .owl-carousel .item img{
    display: block;
    width: 100%;
    height: auto;
    }

    .owl-carousel .item {
        margin: 3px;
    }

    .owl-carousel{
        margin-bottom:15px;
    }

/* nav */
    * {
        list-style-type: none;
    }
    .nav-link {
        color: black;
        transition: color 0.3s ease; /* Áp dụng hiệu ứng mượt mà */
    }

    .nav-link:hover {
        color: green; /* Chuyển màu sang xanh lá khi hover */
    }
    .nav-item {
        position: relative;
    }
    .sub-menu-lv1 .nav-item {
        position: relative;
    }
    .sub-menu-lv1 {
        display: none;
        position: absolute;
        z-index: 999;
    } 
    .sub-menu-lv2 {
        display: none;
        position: absolute;
    }
    .nav-item .sub-menu-lv1 .nav-item {
        width: 180px;
        background-color: #ffeef2;
    }
    .nav-item .sub-menu-lv2 .nav-item {
        width: 180px;
        background-color: #ffeef2;
    }
    .nav-item .sub-menu-lv1 .nav-item {
        top: -5px;
        left: -30px; 
        text-align: left;
    }
    .nav-item .sub-menu-lv1 .nav-item .nav-link {
        border-bottom: 1px solid black;
        padding-left: 15px;
    }
    .sub-menu-lv2 .nav-item .nav-link {
        border-bottom: 1px solid black;
    }
    .nav-item .sub-menu-lv1 .nav-item:last-child > .nav-link {
        border-bottom: none;
    }
    .nav-item:hover .sub-menu-lv1 {
        display: block;
        transition: all 0.3s;
    }
    .sub-menu-lv1 .nav-item:hover .sub-menu-lv2 {
        display: block;
        transition: all 0.3s;
    }
    .sub-menu-lv1 .nav-item .sub-menu-lv2 .nav-item {
        top: -41px;
        left: 148px; 
        text-align: left;
    }

</style>
<body>
    <header>
    <nav class="navbar navbar-expand-lg fixed-top" style="background-color: #ffeef2;">
            <div class="container-fluid">
                <a class="navbar-brand" href="index.php">
                    <img width="32" height="32" src="https://img.icons8.com/external-justicon-lineal-color-justicon/64/external-hamster-animal-justicon-lineal-color-justicon.png" alt="external-hamster-animal-justicon-lineal-color-justicon"/> 
                    The Hamster Shop
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="index.php">Trang Chủ</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link dropdown-toggle" href="AllProduct.php">Sản Phẩm</a>
                            <ul class="sub-menu-lv1">
                                <li class="nav-item">
                                    <a class="nav-link dropdown-toggle" href="ProductsByCategory.php?cat_id=<?=$hamsterCat_id?>">Chuột Hamster</a>
                                    <ul class="sub-menu-lv2">
                                        <?php foreach ($listNameHamster as $category) :?>
                                            <li class="nav-item">
                                                <a class="nav-link" href="ProductsByCategory.php?cat_id=<?=$category["cat_id"]?>">
                                                    <?= $category["name"]?>
                                                </a>
                                            </li>
                                        <?php endforeach ?>
                                    </ul>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link dropdown-toggle" href="ProductsByCategory.php?cat_id=<?=$proHamsterCat_id?>">Sản Phẩm Hamster</a>
                                    <ul class="sub-menu-lv2">
                                        <?php foreach ($listNameProductHamster as $category) :?>
                                            <li class="nav-item">
                                                <a class="nav-link" href="ProductsByCategory.php?cat_id=<?=$category["cat_id"]?>">
                                                    <?= $category["name"]?>
                                                </a>
                                            </li>
                                        <?php endforeach ?>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="about.php">Giới Thiệu</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="index.php">Chăm Sóc Hamster</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="index.php">Liên Hệ</a>
                        </li>
                        <?php if(!isset($_COOKIE['username'])): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="login.php">Đăng nhập</a>
                            </li>  
                            <li class="nav-item">
                                <a class="nav-link" href="register.php">Đăng ký</a>
                            </li>    
                        <?php else: ?>
                            <li class="nav-item">
                                <span class="nav-link text-success"><?= 'Hello, '. $_COOKIE['username']?></span>
                            </li> 
                            <li class="nav-item">
                                <a type="button" class="btn btn-outline-danger" href="logout.php">Đăng xuất</a>
                            </li>
                        <?php endif; ?>   
                    </ul>
                    <form class="d-flex mb-0" style="margin-right: 30px;" role="search" action="AllProduct.php" method="get">
                        <input class="form-control me-2" type="search" placeholder="Search" name="search" aria-label="Search">
                        <button class="btn btn-outline-success" type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                    </form>
                    <div class="d-flex m-0">
                        <a class="btn btn-outline-danger px-3" href="cart.php"><i class="fa-solid fa-cart-shopping"></i> <?=$total_quantity?> sản phẩm</a>
                    </div>
                </div>
            </div>
    </nav>      
        
    </header>
    <main>