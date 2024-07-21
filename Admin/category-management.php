<?php 
    require_once "../class/Database.php";
    require_once "../class/Product.php"; 
    require_once "../class/Category.php";
    require_once "../class/User.php"; 
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

    $data = Category::getAll($pdo);

    //var_dump($data);

    $title = "Admin Page - Quản Lý Loại Sản Phẩm";
    
    function sortByCriteria(&$categories, $criteria) {
        // Tạo closure để so sánh theo tiêu chí
        $compare = function ($a, $b) use ($criteria) {
          if ($criteria === 'cat_id') {
            return strcmp($a->cat_id, $b->cat_id);        
          } elseif ($criteria === 'name') {
            return strcmp($a->name, $b->name);
          } else {
            throw new Exception("Tiêu chí không hợp lệ: $criteria");
          }
        };
      
        // Sắp xếp mảng sử dụng usort()
        usort($categories, $compare);
        return $categories;
    }
    function sortByCriteriaReverse(&$categories, $criteria) {
        // Tạo closure để so sánh ngược lại
        $compare = function ($a, $b) use ($criteria) {
          if ($criteria === 'cat_id') {
            return strcmp($b->cat_id, $a->cat_id);
          } elseif ($criteria === 'name') {
            return strcmp($b->name, $a->name);
          } else {
            throw new Exception("Tiêu chí không hợp lệ: $criteria");
          }
        };
      
        // Sắp xếp mảng sử dụng usort()
        usort($categories, $compare);
        return $categories;
      }

    $paginator = new Paginator(4);
    $current_page = $paginator->getCurrentPage();
    $start = $paginator->getStartIndex($current_page);
    $data = $paginator->getDataForCategory($pdo, $start, $search="");
    
    $total_pages = $paginator->getTotalPagesForCategory($pdo, $search="");

    $sortOption = [
        "cat_id" => "Cat ID tăng dần",
        "name" => "Tên Loại tăng dần",
        "cat_id-za" => "Cat ID giảm dần",
        "name-za" => "Tên Loại giảm dần"
    ];
?>

<?php require_once "../inc/header_admin-page.php"; ?>
        <div class="container-fluid">
            <div class="dashboard" style="display: flex; justify-content: center; flex-wrap: wrap;">
                <div class="nav" style="display: flex; flex-direction:column; flex: 1; background-color: #3d4b64; color: white;">
                    <div class="brands" style="background-color: #0f203e;">
                        <h2 class="text text-center p-3 px-1">The Hamster Shop</h2>
                    </div>
                    <div class="name-page">
                        <div class="list-group list-group-flush" style="border-bottom: solid 1px white;">
                            <a href="admin-page.php" class="list-group-item list-group-item-action"><i class="fa-solid fa-house"></i> Admin Page</a>
                        </div>
                    </div>
                    <div class="nav-contents">
                        <div class="list-group list-group-flush">
                            <a href="admin-page.php" class="list-group-item list-group-item-action">Quản lý Sản phẩm</a>
                            <a href="category-management.php" class="list-group-item list-group-item-action">Quản lý Loại sản phẩm</a>
                            <a href="user-management.php" class="list-group-item list-group-item-action">Quản lý User</a>
                            <a href="order-management.php" class="list-group-item list-group-item-action">Quản lý Hóa Đơn</a>
                        </div>
                    </div>
                </div>
                <div class="nav" style="display: flex; flex-direction: column; flex-wrap: wrap; flex: 4;">
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
                            <li class="breadcrumb-item" style="color: black;">Quản Lý Loại Sản Phẩm</li>
                        </ol>
                    </nav>	
                    <div class="products mx-2">

                        <h2 class="text text-center text-danger pt-3" style="text-transform: uppercase;">QUẢN LÝ LOẠI SẢN PHẨM</h2>
                        <div class="actions mb-2" style="display: flex; justify-content: space-between;">
                            <div class="sort-options" style="display: flex; justify-content: center; align-items: center;">
                                <h5 class="m-0">Sắp xếp theo:</h5>                               
                                <form method="post" action="">
                                    <select name="province" onchange="this.form.submit()" class="mx-2" style="height: 30px;">
                                        <?php
                                        foreach ($sortOption as $value => $label) {
                                            $selected = '';
                                            if (isset($_POST['province']) && $_POST['province'] == $value) {
                                                $selected = 'selected';
                                                $sortValue = $selected;
                                            }
                                            echo '<option value="' . $value . '" ' . $selected . '>' . $label . '</option>';
                                        }
                                        ?>
                                    </select>
                                </form>

                                <?php
                                    // Kiểm tra nếu form đã được submit
                                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                                        $selectedValue = $_POST['province'];
                                        
                                        // Lấy tên của tỉnh thành dựa trên giá trị được chọn
                                        $selectedLabel = $sortOption[$selectedValue];
                                        
                                        // Kiểm tra nếu chọn 
                                        if ($selectedLabel == 'Cat ID tăng dần') {
                                            sortByCriteria($data, 'cat_id');
                                        }  
                                        else if ($selectedLabel == 'Tên Loại tăng dần') {
                                            sortByCriteria($data, 'name');
                                        }                                     
                                        else if ($selectedLabel == 'Cat ID giảm dần') {
                                            sortByCriteriaReverse($data, 'cat_id');
                                        }
                                        else if ($selectedLabel == 'Tên Loại giảm dần') {
                                            sortByCriteriaReverse($data, 'name');
                                        }
                                    }
                                ?>
                                
                               
                            </div>
                            
                            <a type="button" class="btn btn-success mx-1" href="new-category.php">Thêm Loại SP Mới</a>
                        </div>
                        <table class="table table-bordered text-center">
                            <thead>
                                <tr>
                                    <th>Cat ID</th>
                                    <th>Tên Loại</th>
                                    <th class="col-2">Chức Năng</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data as $category) : ?>
                                    <tr>
                                        <td><?=$category->cat_id ?></td>
                                        <td><?= $category->name?></td>
                                        <td>
                                            <a type="button" style="min-width: 150px;" class="btn btn-outline-danger mx-1 mb-2" href="delete-category.php?cat_id=<?=$category->cat_id?>">Xoá Loại SP</a>
                                            <a type="button" style="min-width: 150px;" class="btn btn-outline-success mx-1 mb-2" href="update-category.php?cat_id=<?=$category->cat_id?>">Cập Nhập Loại</a>
                                        </td>
                                        
                                    </tr>
                                <?php endforeach ?>
                                
                            </tbody>
                        </table>


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
                                    <a class="page-link" href="?page=<?= $prev_page ?>">Previous</a>
                                </li>

                                    <!-- Current page-->
                                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                    <?php if($i==$current_page): ?>
                                        <li class="page-item active">
                                            <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>           
                                        </li>
                                    <?php else :?>
                                        <li class="page-item ">
                                            <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>           
                                        </li>
                                    <?php endif; ?>
                                <?php endfor; ?>


                                <!-- Next page-->
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?= $next_page ?>">Next</a>
                                </li>
                            </ul>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php require_once "../inc/footer_admin-page.php";    
    
