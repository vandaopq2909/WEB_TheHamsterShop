<?php 
    require_once "../class/Database.php"; 
    require_once "../class/Product.php"; 
    require_once "../class/User.php";
    require_once "../class/Category.php";
    

    $conn = new Database();
    $pdo = $conn->getConnect();

    if(!isset($_COOKIE['username'])) {
        die('Bạn phải đăng nhập!!!!!');
    }
    elseif($_COOKIE['username'] == 'admin') {
        echo 'Admin is logged in | '. date('d-m-Y') . '<br/>';
    }

    $cat_idErr = '';
    $nameErr = '';
    
    $cat_id = '';
    $name = '';
    
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $cat_id = $_POST["cat_id"];
            $name = $_POST["name"];
            
            if(empty($cat_id)) {
                $cat_idErr = "Bắt buộc nhập cat_id!";
            }
            if(empty($name)) {
                $nameErr = "Bắt buộc nhập tên loại sản phẩm!";
            }
            
            if(!$cat_idErr && !$nameErr) {           
                $category = new Category($cat_id, $name);
                
                if(Category::addOneCategory($pdo, $category)) {
                    echo "Thêm loại sản phẩm thành công!";
                    header("location: category-management.php");
                }
                else {
                    echo "Lỗi!!!!";
                }
            }
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
        .list-group a {
            background-color: #3d4b64;
            color: white;
        }
    </style>
<body>
    <header></header>
    <main>
        <h1 class="text text-center text-danger pt-3" style="text-transform: uppercase;">Thêm Loại Sản Phẩm Mới</h1>
        <form class="w-50 m-auto" method="post">
            <div class="mb-3">
                <label for="cat_id" class="form-label">Cat ID</label>
                <input class="form-control" id="cat_id" name="cat_id" value="<?= $cat_id ?>">
                <span class="text-danger"><?= $cat_idErr ?></span>
            </div>
            
            <div class="mb-3">
                <label for="name" class="form-label">Tên Loại Sản Phẩm</label>
                <input class="form-control" id="name" name="name" type="name" value="<?= $name ?>">
                <span class="text-danger"><?= $nameErr ?></span>
            </div>  
            
            <button class="btn btn-primary" type="submit">Thêm Loại Sản Phẩm</button>
        </form>


        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.carousel.js"></script>
    </main>
    <footer></footer>
</body>
</html>