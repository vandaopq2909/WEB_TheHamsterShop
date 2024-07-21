<?php
class Product {
    public $id;
    public $name;
    public $price;
    public $description;
    public $image;
    public $cat_id;

    public function __construct($id=0, $name="", $price=0, $description="", $image="", $cat_id="") {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
        $this->description = $description;
        $this->image = $image;
        $this->cat_id = $cat_id;
    }

    public static function getAll($pdo) {
        $sql = "SELECT * FROM product";
        $stmt = $pdo->prepare($sql);
    
        if ($stmt->execute()) {
            $productsData = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $products = [];
            
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
    
            return $products;
        } 
        
        return null; 
    }    

    public static function getOnceProductByID($pdo, $id) {
        $sql = "SELECT * FROM product WHERE id=:id";
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(":id", $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $product = $stmt->fetch(PDO::FETCH_ASSOC);
            return $product;
        } 
    }
    public static function getProductsBySearch($pdo, $search) {
        $sql = "SELECT * FROM product WHERE name LIKE :search";
        
        $stmt = $pdo->prepare($sql);
        $searchParam = "%$search%";
        $stmt->bindParam(':search', $searchParam, PDO::PARAM_STR);

        $products = [];
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
        return $products;
    }
    public static function getLastID($data) {
        $product = (object)end($data);
        return $product->id;
    }
    public static function getProductsByCatID($pdo, $cat_id) {
        $sql = "SELECT * FROM product WHERE cat_id LIKE :cat_id";
        $stmt = $pdo->prepare($sql);

        $cat_idParam = "%$cat_id%";
        $stmt->bindParam(":cat_id", $cat_idParam, PDO::PARAM_STR);

        $products = [];
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
        return $products;
    }
    public static function addOneProduct($pdo, $product) {
        $sql = "INSERT INTO product (name, price, description, image, cat_id) VALUES ('$product->name', '$product->price', '$product->description', '$product->image', '$product->cat_id')";
        if ($pdo->query($sql)) {
            echo "Sản phẩm đã được thêm thành công!";
        } else {
            echo "Lỗi: " . $sql . "<br>" . $pdo->error;
        }
    }
    public static function deleteProductByID($pdo, $proId) {
        // Chuẩn bị truy vấn SQL DELETE
        $sql = "DELETE FROM product WHERE id = :productId";

        // Thực thi truy vấn với tham số được ràng buộc
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':productId', $proId, PDO::PARAM_INT);
        $stmt->execute();
        if ($stmt->execute()) {
            echo "Sản phẩm đã được xóa thành công!";
        } else {
            echo "Lỗi: " . $sql . "<br>" . $pdo->error;
        }
    }
    public static function updateProduct($pdo, $product) {
        // Chuẩn bị truy vấn SQL UPDATE
        $sql = "UPDATE product SET name = :newName, price = :newPrice, image =:newImage, description =:newDesc, cat_id =:newCat_id WHERE id = :proId";

        // Thực thi truy vấn với tham số được ràng buộc
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':newName', $product->name, PDO::PARAM_STR);
        $stmt->bindParam(':newPrice', $product->price, PDO::PARAM_INT);
        $stmt->bindParam(':newImage', $product->image, PDO::PARAM_STR);
        $stmt->bindParam(':newDesc', $product->description, PDO::PARAM_STR);
        $stmt->bindParam(':newCat_id', $product->cat_id, PDO::PARAM_STR);
        $stmt->bindParam(':proId', $product->id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            echo "Sản phẩm đã được cập nhật thành công!";
        } else {
            echo "Lỗi: " . $sql . "<br>" . $pdo->error;
        }
    }
}