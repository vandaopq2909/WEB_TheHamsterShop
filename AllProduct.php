<?php 
    require_once "inc/init.php";
    require_once "class/Product.php";
    require_once "class/Category.php";
    require_once "class/Paginator.php";

    $title = "Danh sách sản phẩm";

    $conn = new Database();
    $pdo = $conn->getConnect();

    //lấy ds chuột hamster
    $listNameHamster = Category::getNameCategoriesHamster($pdo);
    $listNameProductHamster = Category::getNameCategoriesProHamster($pdo);

    $products = [];
    $search = '';

    //Xử lí sort
    function sortByCriteria(&$products, $criteria) {
        // Tạo closure để so sánh theo tiêu chí
        $compare = function ($a, $b) use ($criteria) {
          if ($criteria === 'id') {
            return $a->id - $b->id;
          } elseif ($criteria === 'name') {
            return strcmp($a->name, $b->name);
          } elseif ($criteria === 'price') {
            return $a->price - $b->price;
        } elseif ($criteria === 'cat_id') {
            return strcmp($a->cat_id, $b->cat_id);
          } else {
            throw new Exception("Tiêu chí không hợp lệ: $criteria");
          }
        };
      
        // Sắp xếp mảng sử dụng usort()
        usort($products, $compare);
        return $products;
    }
    function sortByCriteriaReverse(&$products, $criteria) {
        // Tạo closure để so sánh ngược lại
        $compare = function ($a, $b) use ($criteria) {
          if ($criteria === 'id') {
            return $b->id - $a->id;
          } elseif ($criteria === 'name') {
            return strcmp($b->name, $a->name);
          } elseif ($criteria === 'price') {
            return $b->price - $a->price;
          } elseif ($criteria === 'cat_id') {
            return strcmp($b->cat_id, $a->cat_id);
          } else {
            throw new Exception("Tiêu chí không hợp lệ: $criteria");
          }
        };
      
        // Sắp xếp mảng sử dụng usort()
        usort($products, $compare);
        return $products;
    }

        if (isset($_GET["search"])) {
            $search = $_GET["search"];

            //$products = Product::getProductsBySearch($pdo, $search);

            $paginator = new Paginator(4);
            $current_page = $paginator->getCurrentPage();
            $start = $paginator->getStartIndex($current_page);
            $products = $paginator->getData($pdo, $start, $search);
            $total_pages = $paginator->getTotalPages($pdo, $search);

        }
        else {
            //lấy ds tất cả sp nếu không phải search hoặc request method == post                  

            $paginator = new Paginator(4);
            $current_page = $paginator->getCurrentPage();
            $start = $paginator->getStartIndex($current_page);
            $products = $paginator->getData($pdo, $start, $search="");
            $total_pages = $paginator->getTotalPages($pdo, $search="");
        }
 

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
    

    $sortOptions = [
        "id" => "ID sản phẩm tăng dần",
        "name" => "Name sản phẩm tăng dần",
        "cat_id" => "Loại sản phẩm tăng dần",
        "price" => "Giá sản phẩm tăng dần",
        "id-za" => "ID sản phẩm giảm dần",
        "name-za" => "Name sản phẩm giảm dần",
        "cat_id-za" => "Loại sản phẩm giảm dần",
        "price-za" => "Giá sản phẩm giảm dần"
    ];

    $hamsterCat_id = "ch_ht";
    $proHamsterCat_id = "sp_ht";

    

?>
<style>
    .list-products .product-item {
        border-top: 1px solid black;
    }
    .list-products .product-item > a {
        font-size: 20px;
        padding-top: 10px;
    }
    .list-products .product-item > a:hover {
        color: green;
    }
    .list-products li a {
        color: black;
    }
    .list-hamsters li {
        padding-top: 10px;
    }
    .list-hamsters li:last-child {
        padding-bottom: 10px;
    }
    .list-hamsters li a {
        color: black;
    }
    .list-hamsters li a:hover {
        color: green;
    }
</style>
<?php require_once "inc/header_nonslider.php"?>
<div class="container-fluid mx-0 mb-0" style="background-color: white; margin-top: 56px;">
    <nav style="--bs-breadcrumb-divider: '>'; border-bottom: 1px solid black;" aria-label="breadcrumb">
        <ol class="breadcrumb m-0 p-0 pt-2 pb-2">
            <li class="breadcrumb-item"><a href="index.php" style="color: black;">Trang chủ</a></li>
            <li class="breadcrumb-item" style="color: black;">Tất cả sản phẩm</li>
        </ol>
    </nav>
    <div class="contents" style="display:flex; justify-content: center;">
        <div class="filters" style="flex: 1;">
            <h4 class="text text-center m-3"><i class="fa-solid fa-bars"></i> Danh Mục Sản Phẩm</h4>
            <ul class="list-products">
                <li class="product-item">
                    <a href="ProductsByCategory.php?cat_id=<?=$hamsterCat_id?>"><i class="fa-solid fa-star"></i> Chuột Hamster</a>
                    <ul class="list-hamsters">
                        <?php foreach ($listNameHamster as $name) :?>
                            <li>
                                <a href="ProductsByCategory.php?cat_id=<?=$name["cat_id"]?>">
                                    <i class="fa-solid fa-heart"></i> <?= $name["name"]?>
                                </a>
                            </li>
                        <?php endforeach ?>
                    </ul>
                </li>
                <li class="product-item">
                    <a href="ProductsByCategory.php?cat_id=<?=$proHamsterCat_id?>"><i class="fa-solid fa-star"></i> Sản Phẩm Hamster</a>
                    <ul class="list-hamsters">
                    <?php foreach ($listNameProductHamster as $name) :?>
                            <li>
                                <a href="ProductsByCategory.php?cat_id=<?=$name["cat_id"]?>">
                                    <i class="fa-solid fa-heart"></i> <?= $name["name"]?>
                                </a>
                            </li>
                        <?php endforeach ?>
                    </ul>
                </li>
            </ul>
        </div>
        <div class="all-product" style="flex: 4;">
            
            <h3 class="text text-danger text-center m-3">Danh Sách Sản Phẩm</h3>
            <div class="d-flex" style="margin-left: 35px;">
                <h5 class="m-0">Sắp xếp theo:</h5>                               
                <form method="post" action="">
                    <select name="sortOptionName" onchange="this.form.submit()" class="mx-2" style="height: 30px;">
                        <?php
                        foreach ($sortOptions as $value => $label) {
                            $selected = '';
                            if (isset($_POST['sortOptionName']) && $_POST['sortOptionName'] == $value) {
                                $selected = 'selected';
                                $sortValue = $selected;
                            }
                            echo '<option value="' . $value . '" ' . $selected . '>' . $label . '</option>';
                        }
                        ?>
                    </select>
                </form>
            </div>
            <div class="container-fluid" style="display: flex; justify-content:center; flex-wrap: wrap;">
                <?php
                // Kiểm tra nếu form đã được submit
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $selectedValue = $_POST['sortOptionName'];
                    
                    // Lấy tên của tỉnh thành dựa trên giá trị được chọn
                    $selectedLabel = $sortOptions[$selectedValue];
                    
                    // Kiểm tra nếu chọn 
                    if ($selectedLabel == 'ID sản phẩm tăng dần') {
                        sortByCriteria($products, 'id');
                    }
                    else if ($selectedLabel == 'Name sản phẩm tăng dần') {
                        sortByCriteria($products, 'name');
                    }
                    else if ($selectedLabel == 'Loại sản phẩm tăng dần') {
                        sortByCriteria($products, 'cat_id');
                    }
                    else if ($selectedLabel == 'Giá sản phẩm tăng dần') {
                        sortByCriteria($products, 'price');
                    }
                    else if ($selectedLabel == 'ID sản phẩm giảm dần') {
                        sortByCriteriaReverse($products, 'id');
                    }
                    else if ($selectedLabel == 'Name sản phẩm giảm dần') {
                        sortByCriteriaReverse($products, 'name');
                    }
                    else if ($selectedLabel == 'Loại sản phẩm giảm dần') {
                        sortByCriteriaReverse($products, 'cat_id');
                    }
                    else if ($selectedLabel == 'Giá sản phẩm giảm dần') {
                        sortByCriteriaReverse($products, 'price');
                    }
                }
            ?>
                
                <?php if($products == null):?>
                    <h3 class="text text-danger text-center m-3">Sản phẩm bạn cần không tồn tại!</h3>
                <?php endif;?>
                <?php foreach ($products as $product) :?>
                    <div class="card" style="width: 16rem; margin: 10px 10px;">
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
                                <a href="AllProduct.php?search=<?=$search?>&page=<?=$current_page?>&action=addcart&pro_id=<?=$product->id?>" class="btn btn-outline-danger mx-1 px-3" style="display: flex; justify-content: center; align-items: center;">
                                    <i class="fa-solid fa-cart-plus"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <?php endforeach ?>


                    <?php 
                        $prev_page = $current_page - 1;
                        if($prev_page <= 0 )
                        {
                            $prev_page = 1;
                        }
                        $next_page = $current_page+1;
                        if($next_page > $total_pages) {
                            $next_page = $total_pages;
                        }
                    ?>
                    <div class="container">
                        <ul class="pagination justify-content-center">
                            <!-- Previous page-->
                            <li class="page-item">
                                <a class="page-link" href="?search=<?= $search?>&page=<?= $prev_page ?>">Previous</a>
                            </li>

                                <!-- Current page-->
                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                <?php if($i==$current_page): ?>
                                    <li class="page-item active">
                                        <a class="page-link" href="?search=<?= $search?>&page=<?= $i ?>"><?= $i ?></a>           
                                    </li>
                                <?php else :?>
                                    <li class="page-item ">
                                        <a class="page-link" href="?search=<?= $search?>&page=<?= $i ?>"><?= $i ?></a>           
                                    </li>
                                <?php endif; ?>
                            <?php endfor; ?>


                            <!-- Next page-->
                            <li class="page-item">
                                <a class="page-link" href="?search=<?= $search?>&page=<?= $next_page ?>">Next</a>
                            </li>
                        </ul>

                    </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.carousel.js"></script>
</div>
<?php require_once "inc/footer.php"?>