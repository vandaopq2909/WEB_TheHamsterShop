<?php   
    require_once "../class/Database.php";
    require_once "../class/Order.php"; 
    require_once "../class/DetailOrder.php"; 
    require_once "../class/Paginator.php";


    if (!isset($_COOKIE["username"])) {
        header("location: index.php");
    }
    $conn = new Database();
    $pdo = $conn->getConnect();

    $data = Order::getAll($pdo);

    //var_dump($data);

    $title = "Admin Page - Quản Lý Đơn Hàng";
    
    function sortByCriteria(&$orders, $criteria) {
        // Tạo closure để so sánh theo tiêu chí
        $compare = function ($a, $b) use ($criteria) {
          if ($criteria === 'order_id') {
            return strcmp($a->order_id, $b->order_id);        
          } elseif ($criteria === 'username') {
            return strcmp($a->username, $b->username);
          } elseif ($criteria === 'total_quantity') {
            return $a->total_quantity - $b->total_quantity;
          } elseif ($criteria === 'total_price') {
            return $a->total_price - $b->total_price;
          }
          else {
            throw new Exception("Tiêu chí không hợp lệ: $criteria");
          }
        };
      
        // Sắp xếp mảng sử dụng usort()
        usort($orders, $compare);
        return $orders;
    }
    function sortByCriteriaReverse(&$orders, $criteria) {
        // Tạo closure để so sánh ngược lại
        $compare = function ($a, $b) use ($criteria) {
            if ($criteria === 'order_id') {
                return strcmp($b->order_id, $a->order_id);        
              } elseif ($criteria === 'username') {
                return strcmp($b->username, $a->username);
              } elseif ($criteria === 'total_quantity') {
                return $b->total_quantity - $a->total_quantity;
              } elseif ($criteria === 'total_price') {
                return $b->total_price - $a->total_price;              
              } else {
            throw new Exception("Tiêu chí không hợp lệ: $criteria");
          }
        };
      
        // Sắp xếp mảng sử dụng usort()
        usort($orders, $compare);
        return $orders;
      }

    $paginator = new Paginator(4);
    $current_page = $paginator->getCurrentPage();
    $start = $paginator->getStartIndex($current_page);
    $data = $paginator->getDataForOrder($pdo, $start, $search="");
    
    $total_pages = $paginator->getTotalPagesForOrder($pdo, $search="");

    $sortOption = [
        "order_id" => "order_id tăng dần",
        "username" => "username tăng dần",
        "total_quantity" => "Tổng số lượng tăng dần",
        "total_price" => "Tổng tiền tăng dần",
        "order_id-za" => "order_id giảm dần",
        "username-za" => "username giảm dần",
        "total_quantity-za" => "Tổng số lượng giảm dần",
        "total_price-za" => "Tổng tiền giảm dần",
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
                            <li class="breadcrumb-item" style="color: black;">Quản Lý Đơn Hàng</li>
                        </ol>
                    </nav>	
                    <div class="products mx-2">

                        <h2 class="text text-center text-danger pt-3" style="text-transform: uppercase;">QUẢN LÝ Đơn Hàng</h2>
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
                                        if ($selectedLabel == 'order_id tăng dần') {
                                            sortByCriteria($data, 'order_id');
                                        }  
                                        else if ($selectedLabel == 'username tăng dần') {
                                            sortByCriteria($data, 'username');
                                        } 
                                        else if ($selectedLabel == 'Tổng số lượng tăng dần') {
                                            sortByCriteria($data, 'total_quantity');
                                        } 
                                        else if ($selectedLabel == 'Tổng tiền tăng dần') {
                                            sortByCriteria($data, 'total_price');
                                        }                                    
                                        else if ($selectedLabel == 'order_id giảm dần') {
                                            sortByCriteriaReverse($data, 'order_id');
                                        }  
                                        else if ($selectedLabel == 'username giảm dần') {
                                            sortByCriteriaReverse($data, 'username');
                                        } 
                                        else if ($selectedLabel == 'Tổng số lượng giảm dần') {
                                            sortByCriteriaReverse($data, 'total_quantity');
                                        } 
                                        else if ($selectedLabel == 'Tổng tiền giảm dần') {
                                            sortByCriteriaReverse($data, 'total_price');
                                        }
                                    }
                                ?>
                                
                               
                            </div>
                            
                        </div>
                        <table class="table table-bordered text-center">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>username</th>
                                    <th>Tổng số lượng SP</th>  
                                    <th>Tổng tiền</th>
                                    <th>Số ĐT</th>
                                    <th>Địa Chỉ</th>
                                    <th class="col-2">Chức Năng</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data as $order) : ?>
                                    <tr>
                                        <td><?=$order->order_id ?></td>
                                        <td><?= $order->username?></td>
                                        <td><?= $order->total_quantity?></td>  
                                        <td><?= $order->total_price?></td>
                                        <td><?= $order->phone_number?></td>  
                                        <td><?= $order->delivery_address?></td>                                   
                                        <td>
                                            <a type="button" class="btn btn-outline-danger mx-1 mb-2" style="min-width: 150px;" href="delete-order.php?order_id=<?=$order->order_id?>">
                                                Xoá Đơn Hàng
                                            </a>
                                            <a type="button" class="btn btn-outline-success" style="min-width: 150px;" href="detail-order.php?order_id=<?=$order->order_id?>">
                                                Xem Chi Tiết ĐH
                                            </a>
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
    






