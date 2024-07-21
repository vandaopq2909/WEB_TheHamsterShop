<?php
class Category {
    public $cat_id;
    public $name;

    function __construct($cat_id="", $name="") {
        $this->cat_id = $cat_id;
        $this->name = $name;
    }

    public static function getAll($pdo) {
        $sql = "SELECT * FROM category";
        $stmt = $pdo->prepare($sql);
    
        if ($stmt->execute()) {
            $categoriesData = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $categories = [];
            
            foreach ($categoriesData as $categoryData) {
                $category = new Category();
                $category->cat_id = $categoryData['cat_id'];
                $category->name = $categoryData['name'];

                $categories[] = $category;
            }
    
            return $categories;
        } 
        
        return null;
    }
    public static function getNameCategoryByCatID($pdo, $cat_id) {
        $sql = "SELECT name FROM category WHERE cat_id=:cat_id";
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(":cat_id", $cat_id, PDO::PARAM_STR);

        if ($stmt->execute()) {
            $product = $stmt->fetch(PDO::FETCH_ASSOC);
            return $product["name"];
        } 
        return null;
    }
    public static function getNameCategoriesHamster($pdo) {
        $sql = "SELECT * FROM category WHERE cat_id LIKE :cat_id";
        
        $cat_id = "ch_ht"; // Giá trị cat_id cần kiểm tra
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':cat_id', '%' . $cat_id . '%');

        if ($stmt->execute()) {
            $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            return $categories;
        } 
        return null;
    }
    public static function getNameCategoriesProHamster($pdo) {
        $sql = "SELECT * FROM category WHERE cat_id LIKE :cat_id";
        
        $cat_id = "sp_ht"; // Giá trị cat_id cần kiểm tra
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':cat_id', '%' . $cat_id . '%');

        if ($stmt->execute()) {
            $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            return $categories;
        } 
        return null;
    }
    public static function loadCategories($pdo) {
        // Chuẩn bị truy vấn SQL UPDATE
        $sql = "SELECT cat_id, name FROM category";

        // Thực thi truy vấn với tham số được ràng buộc
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute()) {
            $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $categories;
        }
        return null;
    }
    public static function deleteCategoryByCatID($pdo, $cat_id) {
        // Chuẩn bị truy vấn SQL DELETE
        $sql = "DELETE FROM category WHERE cat_id = :cat_id";

        // Thực thi truy vấn với tham số được ràng buộc
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':cat_id', $cat_id, PDO::PARAM_STR);
        $stmt->execute();
        if ($stmt->execute()) {
            echo "Loại Sản Phẩm đã được xóa thành công!";
        } else {
            echo "Lỗi: " . $sql . "<br>" . $pdo->error;
        }
    }  
    public static function addOneCategory($pdo, $category) {
        $sql = "INSERT INTO category(cat_id, name) VALUE('$category->cat_id', '$category->name')";
        if ($pdo->query($sql)) {
            return true;
        } else {
            return false;
        }
    }
    public static function getOneCategoryByCatID($pdo, $cat_id) {
        $sql = "SELECT * FROM category WHERE cat_id = :cat_id";
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(":cat_id", $cat_id, PDO::PARAM_STR);

        if ($stmt->execute()) {
            $category = $stmt->fetch(PDO::FETCH_ASSOC);        
        } 
        return (object)$category;
    }
    public static function isCat_idExists($pdo, $cat_id) {     
        try {
            $query = "SELECT COUNT(*) FROM category WHERE cat_id = :cat_id";
            $stmt = $pdo->prepare($query);
            $stmt->execute(array(':cat_id' => $cat_id));
            $count = $stmt->fetchColumn();
            if ($count == 0) {
                return true;
            }
            return false;
        } catch (PDOException $e) {
            return false;
        }
    }
    public static function updateCategory($pdo, $newCategory) {
        // Chuẩn bị truy vấn SQL UPDATE
        $sql = "UPDATE category SET name = :newName WHERE cat_id = :cat_id";

        // Thực thi truy vấn với tham số được ràng buộc
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':newName', $newCategory->name, PDO::PARAM_STR);
        $stmt->bindParam(':cat_id', $newCategory->cat_id, PDO::PARAM_STR);
        if ($stmt->execute()) {
            echo "Loại sản phẩm đã được cập nhật thành công!";
            return true;
        } else {
            echo "Lỗi: " . $sql . "<br>" . $pdo->error;
            return false;
        }
    }
}
?>