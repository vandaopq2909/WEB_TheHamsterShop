<?php 
    require_once "class/Product.php";
    require_once "class/Category.php";
    require_once "inc/init.php";

    $title = "Giỏ Hàng";

    $conn = new Database();
    $pdo = $conn->getConnect();

    

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        // Nếu sản phẩm đã tồn tại, tăng số lượng lên
        $pro_id = $_POST["pro_id"];

        foreach ($_SESSION["cart"] as $key => &$cart_item) {
            if ($cart_item["pro_id"] == $pro_id) {
                $cart_item["qty"] = $_POST["qty"];
                break; 
            }
        }
    }
    if (isset($_GET["action"])) {
        $action = $_GET["action"];
        if ($action == "empty") {
            $_SESSION["cart"] = [];
            header("location: cart.php");
        }
        if ($action == "remove") {
            if (isset($_GET["pro_id"])) {

                $pro_id = $_GET["pro_id"];

                foreach ($_SESSION["cart"] as $key => $cart_item) {
                    if ($cart_item["pro_id"] == $pro_id) {
                        unset($_SESSION["cart"][$key]);
                        break; 
                    }
                }
                header("location: cart.php");
            }
        }
    }

?>

<?php require_once "inc/header_nonslider.php";?>
<style>
    #verify-form {
        display: none;       
    }
</style>
<section class="h-100" style="background-color: #eee;">
  <div class="container h-100 py-5">
    <div class="row d-flex justify-content-center h-100">
        <h4 class="text text-danger text-center m-4">GIỎ HÀNG</h4>

        <div class="col-8 align-items-center">

            <?php if (isset($_SESSION["cart"])) : 
                    $i = 1; $total = 0;
                    //var_dump($_SESSION["cart"]);
                    //var_dump($pro_id, $action);
                    foreach ($_SESSION["cart"] as $cart):
                        $product = Product::getOnceProductByID($pdo, $cart["pro_id"]);
            ?>
                        <div class="card rounded-3 mb-4">
                            <div class="card-body p-4">
                                <div class="row d-flex justify-content-between align-items-center">
                                    <div class="col-md-2 col-lg-2 col-xl-2">
                                        <img
                                        src="Image/<?=$product["image"]?>"
                                        class="img-fluid rounded-3">
                                    </div>
                                    <div class="col-md-3 col-lg-3 col-xl-3">
                                        <p class="lead fw-normal mb-2"><?=$product["name"]?></p>
                                        <p><span class="text-muted">Loại: </span><?=Category::getNameCategoryByCatID($pdo, $product["cat_id"]);?></p>
                                    </div>
                                    <div class="col-md-3 col-lg-3 col-xl-2 d-flex justify-content-center align-items-center">
                                        <form id="formUpdate<?= $cart["pro_id"]?>" method="post" style="display: flex;">
                                            <input type="button" class="button-quantity px-2" onclick="this.parentNode.querySelector('input[type=number]').stepDown()" value="-">

                                            <input type="number" id="<?= $cart["pro_id"]?>" class="form-control form-control-sm mx-1" min="1" name="qty" value="<?=$cart["qty"]?>" style="width: 50px;">
                                            <input type="hidden" name="pro_id" value="<?=$cart["pro_id"]?>" />
                                            
                                            <input type="button" class="button-quantity px-2" onclick="this.parentNode.querySelector('input[type=number]').stepUp()" value="+">


                                            <input type="hidden" name="update" value="cập nhật giá" class="btn btn-success" />
                                        </form>
                                    </div>
                                    <div class="col-md-3 col-lg-2 col-xl-2 offset-lg-1">
                                        <h5 class="mb-0"><?= number_format($product["price"], 0, ',', '.')?> vnđ</h5>
                                    </div>
                                    <div class="col-md-1 col-lg-1 col-xl-2 text-end">
                                        <a href="#!" id="submitLink<?= $cart["pro_id"]?>" class="text-success"><i class="fa-solid fa-rotate fa-lg"></i></a>
                                        <span class="mx-3">|</span>
                                        <a href="cart.php?action=remove&pro_id=<?= $cart["pro_id"]?>" class="text-danger"><i class="fas fa-trash fa-lg"></i></a>
                                    </div>
                                    </div>

                                    <script>
                                        document.getElementById("submitLink<?= $cart["pro_id"]?>").addEventListener("click", function(event){
                                        event.preventDefault(); // Ngăn chặn hành vi mặc định của thẻ <a>
                                        document.getElementById("formUpdate<?= $cart["pro_id"]?>").submit(); // Kích hoạt sự kiện submit của form
                                        });
                                    </script>
                            </div>
                        </div>
            
            <?php
                        $i++; $total += $product["price"] * $cart["qty"];
                    endforeach; 
                endif;
            ?>

            <a href="cart.php?action=empty" class="btn btn-danger">làm trống giỏ thàng</a>
             

        </div>
        <div class="col-4">
            <div class="card mb-2">
                <div class="card-header py-3">
                    <h5 class="mb-0">Thanh toán đơn hàng</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                    <li
                        class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 pb-0">
                        Tổng tiền
                        <span><?= number_format($total, 0, ',', '.')?> vnđ</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        Phí giao hàng
                        <span>0 vnđ</span>
                    </li>
                    <li
                        class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 mb-3">
                        <div>
                        <strong>Phải trả</strong>
                        <strong>
                            <p class="mb-0">(đã bao gồm VAT)</p>
                        </strong>
                        </div>
                        <span id="total-price"><strong><?= number_format($total, 0, ',', '.')?> vnđ</strong></span>
                    </li>
                    </ul>

                    <button id="btn-pay" type="button"
                    data-mdb-button-init data-mdb-ripple-init class="btn btn-danger btn-lg btn-block">
                        Thanh toán
                    </button>
                    
                </div>
            </div>
            
            <div class="card mb-4" id="verify-form">
                <div class="card-header py-2">
                    <h5 class="mb-0">Xác nhận thanh toán đơn hàng</h5>
                </div>
                <div class="card-body">

                    <form id="verify-payment-form" action="verify-payment.php" method="post">
                        <div class="mb-2">
                            <label for="phone">Số điện thoại:</label>
                            <input type="text" class="form-control" id="phone" name="phone_number" required>
                        </div>
                        <div class="mb-2">
                            <label for="address">Địa chỉ:</label>
                            <input type="text" class="form-control" id="address" name="delivery_address" required>
                        </div>
                        <div class="d-flex justify-content-center">
                            <button id="btn-verify-pay" type="submit"
                            data-mdb-button-init data-mdb-ripple-init class="btn btn-danger btn-lg btn-block">
                                Xác nhận thanh toán
                            </button>
                        </div>
                    </form>         
                    <!-- script cho btn thanh toán -->
                    <script>
                        var totalPriceText = document.getElementById('total-price').textContent;

                        // Ép kiểu về int                      
                        var totalPrice = parseInt(totalPriceText, 10);
                        document.getElementById('btn-pay').addEventListener('click', function(event) {
                            event.preventDefault(); // Ngăn chặn hành vi mặc định của nút

                            if(totalPrice == 0) {
                                Swal.fire({
                                icon: "error",
                                title: "Lỗi...",
                                text: "Bạn phải thêm sản phẩm cần mua vào giỏ hàng trước!",
                                });
                            }
                            else {
                                document.getElementById('btn-pay').addEventListener('click', function() {
                                    var form = document.getElementById('verify-form');
                                    if (form.style.display === 'none' || form.style.display === '') {
                                        form.style.display = 'block';
                                    } else {
                                        form.style.display = 'none';
                                    }
                                });
                            }
                            
                        });
                    </script>
                    <!-- scrip cho btn xác nhận thanh toán -->
                    <script>
                        document.getElementById('btn-verify-pay').addEventListener('click', function(event) {
                            
                            var phoneInput = document.getElementById('phone');
                            var addressInput = document.getElementById('address');

                            // Kiểm tra xem các trường đã được điền đầy đủ hay không
                            if (phoneInput.value.trim() === '' || addressInput.value.trim() === '') {
                                alert('Vui lòng điền đầy đủ thông tin.');
                                event.preventDefault(); // Ngăn chặn việc gửi form nếu thông tin chưa đầy đủ
                            } else {
                                event.preventDefault(); // Ngăn chặn việc gửi form nếu thông tin chưa đầy đủ
                                Swal.fire({
                                title: 'Bạn có chắc chắn muốn thanh toán?',
                                text: "Hành động này không thể hoàn tác!",
                                icon: 'question',
                                showCancelButton: true,
                                confirmButtonText: 'Có, tiếp tục!',
                                cancelButtonText: 'Không, hủy bỏ!'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        // Thực hiện hành động khi người dùng chọn "Có"
                                        document.getElementById('verify-payment-form').submit();
                                        window.location.href("verify-payment.php");
                                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                                        // Hành động khi người dùng chọn "Không"                                  
                                    }
                                });    
                            }                                                  
                        });
                    </script>
                </div>
            </div>
            
        </div>
    </div>
</div>
</section>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.carousel.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php require_once "inc/footer.php";?>