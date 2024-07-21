<?php 
    require_once "class/Product.php";
    require_once "class/Category.php";
    require_once "inc/init.php";

    $title = "Xác Nhận Thanh Toán";

    if(!isset($_COOKIE["username"])) {
        die("Bạn cần phải đăng nhập!");
    }
    if($_SESSION["cart"] == []) {
        die("Bạn cần phải thanh mua hàng và thanh toán!");
    }

    $order_id = "DH" . uniqid();
    $username = $_COOKIE["username"];
    $phone_number = "01234567"; 
    $delivery_addres = "Tân Phú, TP. HCM";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $phone_number = $_POST["phone_number"]; 
        $delivery_address = $_POST["delivery_address"]; 
    }

    $conn = new Database();
    $pdo = $conn->getConnect();

    $total_quantity = 1;
    $total_price = 0;
    // Kiểm tra nếu giỏ hàng tồn tại
    if (isset($_SESSION["cart"])) {
        $cart = $_SESSION["cart"];
        $total_quantity = 0;
        $total_price = 0;

        // Duyệt qua giỏ hàng để tính tổng số lượng và tổng giá trị
        foreach ($cart as $item) {
            $pro_id = $item["pro_id"];
            $qty = $item["qty"];
            
            // Tính tổng số lượng
            $total_quantity += $qty;

            // Tính tổng giá trị đơn hàng     
            $product = (object)Product::getOnceProductByID($pdo, $pro_id); 
            $total_price += $qty * $product->price;
            
        }
    }

    
    $checkAddOrder = "";
    $checkAddDetailOrder = "";

    //thêm đơn hàng
    $sqlAddOrder = "INSERT INTO `order`(`order_id`, `username`, `total_quantity`, `total_price`, `phone_number`, `delivery_address`) VALUES ('$order_id', '$username', '$total_quantity', '$total_price', '$phone_number', '$delivery_address')";
    if($pdo->query($sqlAddOrder)) {
        $checkAddOrder = "ok";
    }
    else {
        $checkAddOrder = "lỗi";
    }
    foreach ($cart as $item) {
        $pro_id = $item["pro_id"];
        $quantity = $item["qty"];
        $product = (object)Product::getOnceProductByID($pdo, $pro_id); 
        $price = $product->price;

        $sqlAddDetailOrder = "INSERT INTO `detail_order`(`order_id`, `pro_id`, `quantity`, `price`) VALUES ('$order_id', '$pro_id', '$quantity', '$price')";
        if($pdo->query($sqlAddDetailOrder)) {
            $checkAddDetailOrder = "ok";
        }
        else {
            $checkAddDetailOrder = "lỗi";
        }
    }
    
    if($checkAddOrder === "ok" && $checkAddDetailOrder === "ok") {
        $cart = $_SESSION["cart"] = [];
    } 
?>
<?php require_once "inc/header_nonslider.php"?>
<style>
html,
body {
  height: 100%;
  display: flex;
  justify-content: center;
}

svg {
  width: 100%;
}

.tick {
  transform-origin: center;
  animation: grow 0.8s ease-in-out forwards;
}

@keyframes grow {
  0% {
    transform: scale(0);
    opacity: 0;
  }
  60% {
    transform: scale(1.1);
  }
  100% {
    transform: scale(1);
    opacity: 1;
  }
}

.circle {
  transform-origin: center;
  stroke-dasharray: 1000;
  stroke-dashoffset: 0;
  animation: dash 1s linear;
}

@keyframes dash {
  from {
    stroke-dashoffset: 1000;
    opacity: 0;
  }
  to {
    stroke-dashoffset: 0;
  }
}
</style>
<div class="container-fluid" style="margin-top: 56px;">
    <nav style="--bs-breadcrumb-divider: '>'; border-bottom: 1px solid black;" aria-label="breadcrumb">
        <ol class="breadcrumb m-0 p-0 pt-2 pb-2">
            <li class="breadcrumb-item"><a href="index.php" style="color: black;">Trang chủ</a></li>
            <li class="breadcrumb-item" style="color: black;">Xác nhận thanh toán</li>
        </ol>
    </nav>
    <div class="container-fluid h-100 d-flex justify-content-center pt-2">
        <script src="https://cdn.lordicon.com/lordicon.js"></script>
        <lord-icon
            src="https://cdn.lordicon.com/oqdmuxru.json"
            trigger="hover"
            colors="primary:#16c72e"
            style="width:150px;height:150px">
        </lord-icon>
    </div>
    <?php //var_dump($_SESSION["cart"]); var_dump($flag, $flag2)?>
    <p>Kính gửi Quý khách hàng: <?=$username?>,</p>
    <p>
        Chúng tôi xin chân thành cảm ơn bạn đã xác nhận thanh toán thành công đơn hàng của mình. Đơn hàng của bạn đang được chúng tôi xử lý và chuẩn bị để gửi đến bạn trong thời gian sớm nhất.
    </p>
        
    <p>
        Thông tin chi tiết về đơn hàng của bạn như sau:
    </p>
    <p>Mã đơn hàng: <?=$order_id?></p>
    <p>Ngày đặt hàng: <?=date('d-m-Y')?></p>
    <p>Trạng thái đơn hàng: đang xử lý</p>
    <p>Nếu bạn có bất kỳ thắc mắc hoặc cần hỗ trợ thêm, vui lòng liên hệ với chúng tôi qua email <strong>vandaopq@gmail.com</strong> hoặc số điện thoại <strong>0327845877</strong>.</p>
    <p>Một lần nữa, chúng tôi xin cảm ơn bạn đã tin tưởng và sử dụng dịch vụ của chúng tôi. Chúc bạn một ngày tốt lành!</p>
    <br/>       
    <p>Trân trọng,</p>
    <p>The Hamster Shop</p>  
    <p>vandao.io.vn</p>

    <div class="container-fluid d-flex justify-content-center">
        <a href="index.php" class="btn btn-success">Quay về trang chủ, tiếp tục mua sắm!</a>
    </div>
    
</div>

<?php require_once "inc/footer.php"?>