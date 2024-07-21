<?php        
    require_once "inc/init.php";
    require_once "class/Product.php";
    require_once "class/Category.php";
    
    $conn = new Database();
    $pdo = $conn->getConnect();

    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        //xử lý thêm vào giỏ hàng
        if (isset($_GET["action"]) && isset($_GET["pro_id"])) {
            $action = $_GET["action"];
            $pro_id = $_GET["pro_id"];
            
            if ($action == "addcart" || $action == "buynow") {
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

                if ($action == "buynow") {
                    header("location: cart.php");
                }
    
            }
            
        }
    }


    $product = new Product();
    if (isset($_GET["id"])) {
        $id = $_GET["id"];
        $product = (object)Product::getOnceProductByID($pdo, $id);
        $nameCategory = Category::getNameCategoryByCatID($pdo, $product->cat_id);
        $nameProduct = $product->name;

        if (!$product) {
            die("id không hợp lệ !!!");
        }
    }

        
    
    //var_dump($product);


    //get 4 similar products
    $sql = "SELECT * FROM product ORDER BY id DESC LIMIT 4";
    $stmt = $pdo->prepare($sql);

    $similarProducts = [];
    if ($stmt->execute()) {
        $productsData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($productsData as $productData) {
            $similarProduct = new Product();
            $similarProduct->id = $productData['id'];
            $similarProduct->name = $productData['name'];
            $similarProduct->price = $productData['price'];
            $similarProduct->description = $productData['description'];
            $similarProduct->image = $productData['image'];
            $similarProduct->cat_id = $productData['cat_id'];

            $similarProducts[] = $similarProduct;
        }           
    } 

    

    $title = "Thông tin sản phẩm";
?>
<?php require_once "inc/header_nonslider.php"?>
<style>
body{background-color: #ecedee}.card{border: none;overflow: hidden}.thumbnail_images ul{list-style: none;justify-content: center;display: flex;align-items: center;margin-top:10px}.thumbnail_images ul li{margin: 5px;padding: 10px;border: 2px solid #eee;cursor: pointer;transition: all 0.5s}.thumbnail_images ul li:hover{border: 2px solid #000}.main_image{display: flex;justify-content: center;align-items: center;border-bottom: 1px solid #eee;height: 400px;width: 100%;overflow: hidden}.heart{height: 29px;width: 29px;background-color: #eaeaea;border-radius: 50%;display: flex;justify-content: center;align-items: center}.content p{font-size: 12px}.ratings span{font-size: 14px;margin-left: 12px}.colors{margin-top: 5px}.colors ul{list-style: none;display: flex;padding-left: 0px}.colors ul li{height: 20px;width: 20px;display: flex;border-radius: 50%;margin-right: 10px;cursor: pointer}.colors ul li:nth-child(1){background-color: #6c704d}.colors ul li:nth-child(2){background-color: #96918b}.colors ul li:nth-child(3){background-color: #68778e}.colors ul li:nth-child(4){background-color: #263f55}.colors ul li:nth-child(5){background-color: black}.right-side{position: relative}.search-option{position: absolute;background-color: #000;overflow: hidden;align-items: center;color: #fff;width: 200px;height: 200px;border-radius: 49% 51% 50% 50% / 68% 69% 31% 32%;left: 30%;bottom: -250px;transition: all 0.5s;cursor: pointer}.search-option .first-search{position: absolute;top: 20px;left: 90px;font-size: 20px;opacity: 1000}.search-option .inputs{opacity: 0;transition: all 0.5s ease;transition-delay: 0.5s;position: relative}.search-option .inputs input{position: absolute;top: 200px;left: 30px;padding-left: 20px;background-color: transparent;width: 300px;border: none;color: #fff;border-bottom: 1px solid #eee;transition: all 0.5s;z-index: 10}.search-option .inputs input:focus{box-shadow: none;outline: none;z-index: 10}.search-option:hover{border-radius: 0px;width: 100%;left: 0px}.search-option:hover .inputs{opacity: 1}.search-option:hover .first-search{left: 27px;top: 25px;font-size: 15px}.search-option:hover .inputs input{top: 20px}.search-option .share{position: absolute;right: 20px;top: 22px}.buttons .btn{height: 50px;width: 150px;border-radius: 0px !important}

.quatity input {
    width: 40px;
    height: 40px;
    cursor: pointer;
    border: 1px solid #ddd;
    font-size: 20px;
}
.quatity input:nth-child(2) {
    width: 60px;
    text-align: center;
    outline: none;
    cursor: text;
}
.quatity input:nth-child(1),
.quatity input:nth-child(3) {
    background-color: #00000008;
    outline: none;
    transition: all .3s;
}
.quatity input:nth-child(1):hover,
.quatity input:nth-child(3):hover {
    background-color: #6b6b6b4d;
}

</style>
<div class="container mb-4" style="margin-top: 80px;">
    <div class="card">	
        <nav style="--bs-breadcrumb-divider: '>'; border-bottom: 1px solid black;" aria-label="breadcrumb">
            <ol class="breadcrumb m-0 p-2">
                <li class="breadcrumb-item"><a href="index.php" style="color: black;">Trang chủ</a></li>
                <li class="breadcrumb-item" style="color: black;"><?= $nameCategory?></li>
                <li class="breadcrumb-item" style="color: black;"><?= $nameProduct?></li>
            </ol>
        </nav>	
        <div class="row g-0">	
            <div class="col-md-6 border-end">	
                <div class="d-flex flex-column justify-content-center">	
                    <div class="main_image">	
                        <img src="Image/<?=$product->image?>" id="main_product_image" width="350">	
                    </div>	
                    <div class="thumbnail_images">	
                        <ul id="thumbnail">	
                            <li><img src="Image/<?=$product->image?>" width="70"></li>	
                        </ul>	
                    </div>	
                </div>	
            </div>	
            <div class="col-md-6">	
                <div class="p-3 right-side">	
                    <div class="d-flex justify-content-between align-items-center">	
                        <h2><?= $product->name?></h2>	
                        <h4><i class="fa-solid fa-heart fa-bounce" style="color: #ff0000;"></i></h4>	
                    </div>	
                    <div class="mt-2 pr-3 content">	
                        <h6>
                            <?= $product->description?>   
                        </h6>	
                    </div>	
                    <h3 class="text-danger mt-5 mb-4"><?= number_format($product->price, 0, ',', '.')?> vnđ</h3>	
                    <div class="ratings d-flex flex-row align-items-center">	
                        <h5>441 reviews</h5>                       
                    </div>	
                    <div class="mt-3">	
                        <h4 class="fw-bold">Số lượng</h4>	
                        <div class="quatity">
                            <input type="button" class="button-quantity" onclick="totalClick(-1)" value="-">
                            <input type="text" id="qty" name="qty" value="1">
                            <input type="button" class="button-quantity" onclick="totalClick(1)" value="+">

                            <script>
                                function totalClick(value) {
                                    let qty = document.getElementById('qty');
                                    let sumValue = parseInt(qty.value) + value;
                                    qty.value = sumValue;

                                    if(sumValue < 1)
                                        qty.value = 1;
                                }
                            </script>
                        </div>
                    </div>	
                    <div class="buttons d-flex align-items-center flex-row mt-5 gap-3">	
                        <a href="product.php?id=<?=$product->id?>&action=buynow&pro_id=<?=$product->id?>" class="btn btn-outline-dark">Mua Ngay</a>	
                        <a href="product.php?id=<?=$product->id?>&action=addcart&pro_id=<?=$product->id?>" class="btn btn-dark">Thêm Giỏ Hàng</a>	
                    </div>	
                </div>	
            </div>	
        </div>	
    </div> 
</div>

    <hr class="container">
    <div class="container similar-products my-4 pt-2" style="background-color: white;">
        <h2 class="text text-center text-danger">BẠN CÓ THỂ QUAN TÂM</h2>

        <div class="container-fluid" style="display: flex; justify-content:center; flex-wrap: wrap;">
            <?php foreach ($similarProducts as $product) :?>
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
                            <a href="product.php?id=<?=$_GET["id"]?>&action=addcart&pro_id=<?=$product->id?>" class="btn btn-outline-danger mx-1 px-3" style="display: flex; justify-content: center; align-items: center;">
                                <i class="fa-solid fa-cart-plus"></i>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach ?>
        </div>
    </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.carousel.js"></script>   
    </div>
<script>
function changeImage(element) {

    var main_prodcut_image = document.getElementById('main_product_image');
    main_prodcut_image.src = element.src;


}
</script>
<?php require_once "inc/footer.php"?>
