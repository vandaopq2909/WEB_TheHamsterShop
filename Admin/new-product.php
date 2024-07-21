<?php 
    require_once "../class/Database.php"; 
    require_once "../class/Product.php"; 
    require_once "../class/Category.php";
    
    if(!isset($_COOKIE['username'])) {
        die('Bạn phải đăng nhập!!!!!');
    }
    elseif($_COOKIE['username'] == 'admin') {
        echo 'Admin is logged in | '. date('d-m-Y') . '<br/>';
    }

    function convertNameProduct($str)
{
    // Mảng các ký tự tiếng việt không dấu theo mã unicode tổ hợp
    $tv_unicode_tohop  =
        [
            "à","á","ạ","ả","ã","â","ầ","ấ","ậ","ẩ","ẫ","ă", "ằ","ắ","ặ","ẳ","ẵ",
            "è","é","ẹ","ẻ","ẽ","ê","ề" ,"ế","ệ","ể","ễ",
            "ì","í","ị","ỉ","ĩ",
            "ò","ó","ọ","ỏ","õ","ô","ồ","ố","ộ","ổ","ỗ","ơ" ,"ò","ớ","ợ","ở","õ",
            "ù","ú","ụ","ủ","ũ","ư","ừ","ứ","ự","ử","ữ",
            "ỳ","ý","ỵ","ỷ","ỹ",
            "đ",
            "À","À","Ạ","Ả","Ã","Â","Ầ","Ấ","Ậ","Ẩ","Ẫ","Ă" ,"Ằ","Ắ","Ặ","Ẳ","Ẵ",
            "È","É","Ẹ","Ẻ","Ẽ","Ê","Ề","Ế","Ệ","Ể","Ễ",
            "Ì","Í","Ị","Ỉ","Ĩ",
            "Ò","Ó","Ọ","Ỏ","Õ","Ô","Ồ","Ố","Ộ","Ổ","Ỗ","Ơ" ,"Ờ","Ớ","Ợ","Ở","Ỡ",
            "Ù","Ú","Ụ","Ủ","Ũ","Ư","Ừ","Ứ","Ự","Ử","Ữ",
            "Ỳ","Ý","Ỵ","Ỷ","Ỹ",
            "Đ"
        ];
    // Mảng các ký tự tiếng việt không dấu theo mã unicode dựng sẵn   
    $tv_unicode_dungsan  =
        [
            "à","á","ạ","ả","ã","â","ầ","ấ","ậ","ẩ","ẫ","ă","ằ","ắ","ặ","ẳ","ẵ",
            "è","é","ẹ","ẻ","ẽ","ê","ề","ế","ệ","ể","ễ",
            "ì","í","ị","ỉ","ĩ",
            "ò","ó","ọ","ỏ","õ","ô","ồ","ố","ộ","ổ","ỗ","ơ","ờ","ớ","ợ","ở","ỡ",
            "ù","ú","ụ","ủ","ũ","ư","ừ","ứ","ự","ử","ữ",
            "ỳ","ý","ỵ","ỷ","ỹ",
            "đ",
            "À","Á","Ạ","Ả","Ã","Â","Ầ","Ấ","Ậ","Ẩ","Ẫ","Ă","Ằ","Ắ","Ặ","Ẳ","Ẵ",
            "È","É","Ẹ","Ẻ","Ẽ","Ê","Ề","Ế","Ệ","Ể","Ễ",
            "Ì","Í","Ị","Ỉ","Ĩ",
            "Ò","Ó","Ọ","Ỏ","Õ","Ô","Ồ","Ố","Ộ","Ổ","Ỗ","Ơ","Ờ","Ớ","Ợ","Ở","Ỡ",
            "Ù","Ú","Ụ","Ủ","Ũ","Ư","Ừ","Ứ","Ự","Ử","Ữ",
            "Ỳ","Ý","Ỵ","Ỷ","Ỹ",
            "Đ"
        ];
    // Mảng các ký không dấu sẽ thay thế cho ký tự có dấu
    $tv_khongdau =
        [
            "a","a","a","a","a","a","a","a","a","a","a" ,"a","a","a","a","a","a",
            "e","e","e","e","e","e","e","e","e","e","e",
            "i","i","i","i","i",
            "o","o","o","o","o","o","o","o","o","o","o","o" ,"o","o","o","o","o",
            "u","u","u","u","u","u","u","u","u","u","u",
            "y","y","y","y","y",
            "d",
            "A","A","A","A","A","A","A","A","A","A","A","A" ,"A","A","A","A","A",
            "E","E","E","E","E","E","E","E","E","E","E",
            "I","I","I","I","I",
            "O","O","O","O","O","O","O","O","O","O","O","O" ,"O","O","O","O","O",
            "U","U","U","U","U","U","U","U","U","U","U",
            "Y","Y","Y","Y","Y",
            "D"
        ];
    
    $output = strtolower($str);
    $output = str_replace($tv_unicode_dungsan, $tv_khongdau, $output);
    $output = str_replace($tv_unicode_tohop,   $tv_khongdau, $output);
    
    $output = str_replace(' ', '_', $output);
    $output = trim($output, '_');

    return $output;
}

$nameErr = '';
$priceErr = '';
$descErr = '';
$imageErr = '';
$category_idErr = '';

$name = '';
$price = '';
$desc = '';
$image = '';
$category_id = '';

    $conn1 = new Database();
    $pdo = $conn1->getConnect();
    $categories = Category::loadCategories($pdo);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = $_POST["name"];
        $price = $_POST["price"];
        $desc = $_POST["desc"];
        $image = $_FILES["image"];
        $category_id = $_POST["category_id"];

        if(empty($name)) {
            $nameErr = "Bắt buộc nhập name!";
        }

        switch ($image["error"]) {
            case UPLOAD_ERR_OK:
                if($image["size"] > 10000000) {
                    $imageErr = "Ảnh quá lớn! Vui lòng chọn ảnh có kích thước nhỏ hơn 10 MB!";
                }
                $mine_types = ["image/jpeg", "image/png", "image/webp", "image/gif"];
                $file_info = finfo_open(FILEINFO_MIME_TYPE);
                $mine_type = finfo_file($file_info, $image["tmp_name"]);
                
                if(!in_array($mine_type, $mine_types)) {
                    $imageErr = "File không phải hình ảnh! Vui lòng thử lại!";
                }
                $pathinfo = pathinfo($image["name"]);
                $fname = convertNameProduct($name);
                $extension = $pathinfo["extension"];
                $dest = "../Image/" . $fname . "." . $extension;
                //$i = 1;
                while(file_exists($dest)) {
                    $dest = "../Image/" . $fname . "." . $extension;
                    //$i++;
                } 
                if(move_uploaded_file($image["tmp_name"], $dest)) {
                    //header("location: admin-page.php");
                }
                else {
                    $imageErr = "Lỗi upload file lên máy chủ! Vui lòng thử lại!";
                }
                break;
            case UPLOAD_ERR_NO_FILE:
                $imageErr = "Phải chọn hình ảnh!";
                break;
            default:
                $imageErr = "Lỗi chọn hình ảnh! Vui lòng thử lại!";
        }

        

        if(empty($price)) {
            $priceErr = "Phải nhập giá!";
        }
        elseif($price % 1000 != 0) {
            $priceErr = "Giá phải chia hết cho 1000!";
        }
        if(empty($category_id)) {
            $category_idErr = "Bắt buộc chọn phân loại sản phẩm!";
        }

        if(!$nameErr && !$priceErr && $image["error"] == UPLOAD_ERR_OK && !$category_idErr) {
            $newPro = new Product();
            $newPro->name = $name;
            $newPro->price = $price;
            $newPro->description = $desc;
            $i--;
            $newPro->image = $fname . "." . $extension;
            $newPro->cat_id = $category_id;
            
            $conn = new Database();
            $pdo = $conn->getConnect();
            Product::addOneProduct($pdo, $newPro);
            //var_dump($newPro);
            header("location: admin-page.php");
        }
    }

    $title = "Thêm sản phẩm mới";

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
        <h1 class="text text-center text-danger pt-3" style="text-transform: uppercase;">Thêm Sản Phẩm Mới</h1>
        <form class="w-50 m-auto" method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="name" class="form-label">Tên</label>
            <input class="form-control" id="name" name="name" value="<?= $name ?>">
            <span class="text-danger"><?= $nameErr ?></span>
        </div>
        
        <div class="mb-3">
            <label for="price" class="form-label">Giá</label>
            <input class="form-control" id="price" name="price" value="<?= $price ?>">
            <span class="text-danger"><?= $priceErr ?></span>
        </div>
        <div class="mb-3">
            <label for="desc" class="form-label">Mô tả</label>
            <input class="form-control" id="desc" name="desc" value="<?= $desc ?>">
            <span class="text-danger"><?= $descErr ?></span>
        </div>
        <div class="mb-3">
            <label for="image" class="form-label">Ảnh Sản Phẩm</label>
            <input class="form-control" type="file" id="image" name="image" value="<?= $image ?>">
            <span class="text-danger"><?= $imageErr ?></span>
        </div>

        <div class="mb-3">
            <label for="selcect-category" class="form-label">Phân Loại</label>
            <select id="selcect-category" name="category_id" class="mx-2" style="height: 30px;">
                <?php foreach ($categories as $category) : ?>
                    <option id="<?=$category["cat_id"]?>" name="category_id" value="<?=$category["cat_id"]?>"><?=$category["name"]?></option>
                   
                <?php endforeach ?>
            </select>
            <span class="text-danger"><?= $category_idErr ?></span>
   
        </div>
        <button class="btn btn-primary" type="submit">Thêm SP</button>
        </form>


        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.carousel.js"></script>
    </main>
    <footer></footer>
</body>
</html>