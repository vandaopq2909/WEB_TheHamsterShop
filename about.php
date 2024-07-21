<?php 
    require_once "inc/init.php";
    require_once "class/Product.php";
    require_once "class/Category.php";

    $title = "Giới Thiệu";

    $conn = new Database();
    $pdo = $conn->getConnect();
?>

<?php require_once "inc/header_nonslider.php"; ?>

<div class="container-fluid" style="margin-top: 56px;">
    <nav style="--bs-breadcrumb-divider: '>'; border-bottom: 1px solid black;" aria-label="breadcrumb">
        <ol class="breadcrumb m-0 p-0 pt-2 pb-2">
            <li class="breadcrumb-item"><a href="index.php" style="color: black;">Trang chủ</a></li>
            <li class="breadcrumb-item" style="color: black;">Giới Thiệu</li>
        </ol>
    </nav>
    <h2 class="text-danger text-center mt-3" style="text-transform: uppercase;">Giới Thiệu Về The Hamster Shop</h2>
    <div class="container-fluid mt-3">
        <div class="mb-3" style="max-width: auto;">
            <div class="row g-0">
                <div class="col-md-2"></div>
                <div class="col-md-6">
                    <div class="card-body">
                        <p class="card-text mb-3" style="font-size:1.3em; text-align: justify">
                            Chào mừng bạn đến với The Hamster Shop - thiên đường dành cho những người yêu thích và chăm sóc hamster!
                            <br /><br />
                            Tại đây, chúng tôi cung cấp đa dạng các giống hamster khỏe mạnh, đáng yêu và những sản phẩm chăm sóc chất lượng hàng đầu. Từ thức ăn dinh dưỡng, chuồng trại tiện nghi đến các phụ kiện vui chơi, tất cả đều được chọn lọc kỹ lưỡng để đảm bảo sức khỏe và sự thoải mái cho hamster của bạn. Với đội ngũ tư vấn viên giàu kinh nghiệm, chúng tôi luôn sẵn sàng hỗ trợ bạn trong việc chọn lựa và chăm sóc thú cưng. Hãy để The Hamster Shop trở thành người bạn đồng hành tin cậy của bạn và hamster yêu quý!
                        </p>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <img src="Image/about_hamster_background.png" style="width: 250px;" class="img-fluid rounded-start" alt="...">
                </div>
            </div>
        </div>

        <div class="container-fluid mt-3" style="display: flex; justify-content: space-evenly; flex-wrap: wrap;">
            <div class="card mt-1" style="width: 16rem;">
                <img src="Image/about_hamster_1.jpg" class="card-img-top" alt="...">
                <div class="card-body">
                    <h5 class="card-title">
                        Đa dạng các loại Hamster
                    </h5>
                    <p class="card-text" style="text-align: justify">
                        Từ những bé Robo bé bỏng đến những chú Bear to lớn, chúng tôi luôn có sẵn các giống Hamster phổ biến nhất.
                    </p>
                </div>
            </div>
            <div class="card mt-1" style="width: 16rem;">
                <img src="Image/about_hamster_2.jpg" class="card-img-top" alt="...">
                <div class="card-body">
                    <h5 class="card-title">
                        Phụ kiện phong phú
                    </h5>
                    <p class="card-text" style="text-align: justify">
                        Lồng, thức ăn, đồ chơi, nhà tắm,... Mọi thứ để chăm sóc Hamster chu đáo đều có tại đây!
                    </p>
                </div>
            </div>
            <div class="card mt-1" style="width: 16rem;">
                <img src="Image/about_hamster_3.jpg" class="card-img-top" alt="...">
                <div class="card-body">
                    <h5 class="card-title">
                        Tư vấn chuyên nghiệp
                    </h5>
                    <p class="card-text" style="text-align: justify">
                        Đội ngũ nhân viên am hiểu, nhiệt tình luôn sẵn sàng giải đáp mọi thắc mắc về cách nuôi Hamster.
                    </p>
                </div>
            </div>
            <div class="card mt-1" style="width: 16rem;">
                <img src="Image/about_hamster_4.jpg" class="card-img-top" alt="...">
                <div class="card-body">
                    <h5 class="card-title">
                        Giá cả hợp lý
                    </h5>
                    <p class="card-text" style="text-align: justify">
                        Sản phẩm chất lượng cao với mức giá cạnh tranh, phù hợp với mọi đối tượng.
                    </p>
                </div>
            </div>
        </div>

        <div class="container-fluid mt-3">
            <h3 class="text-danger text-center" style="font-size: 2em; text-transform: uppercase;">Giới Thiệu Sinh Viên</h3>
            <div class="card mb-3 mt-3" style="max-width: auto;">
                <div class="row g-0 justify-content-center">
                    <div class="col-md-2 d-flex align-items-center mx-3">
                        <img src="Image/avt_admin.jpg" class="img-fluid rounded" alt="...">
                    </div>
                    <div class="col-md-5">
                        <div class="card-body">
                            <p class="card-text fs-5">Họ Tên SV: Trần Văn Đảo</p>
                            <p class="card-text fs-5">MSSV: 2001210818</p>
                            <p class="card-text fs-5">Lớp: 12DHTH05</p>
                            <p class="card-text fs-5">Ngành: Công Nghệ Thông Tin</p>
                            <p class="card-text fs-5">Chuyên Ngành: Công Nghệ Phần Mềm</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.carousel.js"></script>
</div>

<?php require_once "inc/footer.php"; ?>