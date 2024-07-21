<?php 
    require_once "inc/init.php";
    require_once "class/Product.php";
    require_once "class/Category.php";

    $conn = new Database();
    $pdo = $conn->getConnect();
    
    //$products = Product::getAll($pdo);
    $products = [];
    $proHamsters = [];
    $sql = "SELECT * FROM product LIMIT 4";
    $stmt = $pdo->prepare($sql);
    
    if ($stmt->execute()) {
        $productsData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($productsData as $productData) {
            $product = new Product();
            $product->id = $productData['id'];
            $product->name = $productData['name'];
            $product->price = $productData['price'];
            $product->description = $productData['description'];
            $product->image = $productData['image'];
            $product->cat_id = $productData['cat_id'];

            $products[] = $product;
        }
    }
    $sql2 = "SELECT * FROM product where cat_id like '%sp_ht%'";
    $stmt2 = $pdo->prepare($sql2);
    if ($stmt2->execute()) {
        $productsData = $stmt2->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($productsData as $productData) {
            $product = new Product();
            $product->id = $productData['id'];
            $product->name = $productData['name'];
            $product->price = $productData['price'];
            $product->description = $productData['description'];
            $product->image = $productData['image'];
            $product->cat_id = $productData['cat_id'];

            $proHamsters[] = $product;
        }
    }

    //var_dump($products);

    //xử lý thêm vào giỏ hàng
    if (isset($_GET["action"]) && isset($_GET["pro_id"])) {
        $action = $_GET["action"];
        $pro_id = $_GET["pro_id"];
        
        if ($action == "addcart") {
            // Kiểm tra nếu giỏ hàng chưa được khởi tạo
            if (!isset($_SESSION["cart"])) {
                $_SESSION["cart"] = array();
            }
            
            $product_exists = false;
            
            // Lặp qua các sản phẩm trong giỏ hàng
            foreach ($_SESSION["cart"] as &$cart_item) {
                // Nếu sản phẩm đã tồn tại trong giỏ hàng
                if ($pro_id == $cart_item["pro_id"]) {
                    $cart_item["qty"] += 1; // Tăng số lượng
                    $product_exists = true;
                    break; // Thoát khỏi vòng lặp
                }
            }
            
            // Nếu sản phẩm chưa tồn tại trong giỏ hàng
            if (!$product_exists) {
                // Thêm sản phẩm mới vào giỏ hàng
                $product = Product::getOnceProductByID($pdo, $pro_id);
                if ($product) {
                    $product = Product::getOnceProductByID($pdo, $pro_id);
                    if ($product) {
                        $newRow = array("pro_id" => $product["id"], "qty" => 1);
                        $_SESSION["cart"] [] = $newRow;
                    }
                }
            }
        }

    }

?>
    <?php require_once "inc/header.php"?>
    <style>
        /* repsonsive */
    /* for tablet */
    @media screen and (max-width: 820px) {

    }
    /* for mobile */
    @media screen and (max-width: 431px) {
        .card {
            flex-basis: 40%;
            align-items: center;
            margin-bottom: 12px !important;
        }
        .card .card-body {
            padding: 10px;
        }
        .card .card-body .text-group .card-title, .card-text {
            font-size: medium;
        }
        .card a img {
        width: 140px;
        height: 140px;
        object-fit: cover;
        object-position: 50% 50%; 
        }
        .pay-button-group {
            flex-direction: column;
            margin-top: 5px !important;
        }
        .pay-button-group a {
            margin: 0 !important;
            margin-top: 5px !important;
        }
        .pay-button-group #btn-pay-now {
            margin-top: 0;
        }
        .pay-button-group #btn-add-cart {
            padding-top: 10px;
            padding-bottom: 10px;
        }
    }
    </style>
    <main>
        <h4 class="text text-danger text-center m-3">SẢN PHẨM NỔI BẬT <i class="fa-solid fa-fire"></i></h4>
        <div class="container-fluid" id="hot-products" style="display: flex; justify-content:center; flex-wrap: wrap;">
            <?php $i = 1; foreach ($products as $product) :?>
                <div class="card" style="width: 16rem; margin: 0 10px;">
                    <a href="product.php?id=<?= $product->id?>">
                        <img src="Image/<?= $product->image?>" class="card-img-top" alt="...">
                    </a>
                    <div class="card-body" style="display: flex; justify-content:space-between; flex-direction: column;">
                        <div class="text-group" >
                            <a href="product.php?id=<?= $product->id?>">
                                <h5 class="card-title"><?= $product->name?></h5>
                            </a>
                            <h4 class="card-text text-danger"><?= number_format($product->price, 0, ',', '.')?>đ</h4>
                        </div>
                        <div class="pay-button-group mt-3" style="display: flex; justify-content: center;">
                            <a href="product.php?id=<?=$product->id?>" id="btn-pay-now" class="btn btn-outline-success mx-1 text-center">Xem Sản Phẩm</a>
                            <a href="index.php?action=addcart&pro_id=<?=$product->id?>" id="btn-add-cart" class="btn btn-outline-danger mx-1 px-3" style="display: flex; justify-content: center; align-items: center;">
                                <i class="fa-solid fa-cart-plus"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <?php 
                    if ($i == 4) 
                        break; 
                    $i++; 
                ?>
            <?php endforeach ?>
        </div>
        <h4 class="text text-danger text-center m-3">SẢN PHẨM HAMSTER</h4>
        <div class="container-fluid" style="display: flex; justify-content:center; flex-wrap: wrap;">
            <?php $i = 1; foreach ($proHamsters as $product) :?>
                <div class="card" style="width: 16rem; margin: 0 10px;">
                    <a href="product.php?id=<?= $product->id?>">
                        <img src="Image/<?= $product->image?>" class="card-img-top" alt="...">
                    </a>
                    <div class="card-body" style="display: flex; justify-content:space-between; flex-direction: column;">
                        <div class="text-group" >
                            <a href="product.php?id=<?= $product->id?>">
                                <h5 class="card-title"><?= $product->name?></h5>
                            </a>
                            <h4 class="card-text text-danger"><?= number_format($product->price, 0, ',', '.')?>đ</h4>
                        </div>
                        <div class="pay-button-group mt-3" style="display: flex; justify-content: center;">
                            <a href="product.php?id=<?=$product->id?>" class="btn btn-outline-success mx-1 text-center">Xem Sản Phẩm</a>
                            <a href="index.php?action=addcart&pro_id=<?=$product->id?>" class="btn btn-outline-danger mx-1 px-3" style="display: flex; justify-content: center; align-items: center;">
                                <i class="fa-solid fa-cart-plus"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <?php 
                    if ($i == 4) 
                        break; 
                    $i++; 
                ?>
            <?php endforeach ?>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.carousel.js"></script>
    </main>
    <?php require_once "inc/footer.php"?>