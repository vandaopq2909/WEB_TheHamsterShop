<?php 
class Paginator {

    private $per_page;
    private $total_pages;

    public function __construct($per_page) {
        $this->per_page = $per_page;
    }

public function getCurrentPage() {
        if (isset($_GET['page'])) {
            return (int)$_GET['page'];
        } else {
            return 1;
        }
    }
    

    public function getStartIndex($current_page) {
        return ($current_page - 1) * $this->per_page;
    }

    public function getData($pdo, $start, $search) {
        $sql = "SELECT * FROM product WHERE name LIKE :search LIMIT :start, :per_page";
        $stmt = $pdo->prepare($sql);
        $searchParam = "%$search%";
        $stmt->bindParam(':search', $searchParam, PDO::PARAM_STR);
        $stmt->bindParam(':start', $start, PDO::PARAM_INT);
        $stmt->bindParam(':per_page', $this->per_page, PDO::PARAM_INT);
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
    public function getDataByCatID($pdo, $start, $cat_id) {
        $sql = "SELECT * FROM product WHERE cat_id LIKE :cat_id LIMIT :start, :per_page";
        $stmt = $pdo->prepare($sql);
        $searchParam = "%$cat_id%";
        $stmt->bindParam(':cat_id', $searchParam, PDO::PARAM_STR);
        $stmt->bindParam(':start', $start, PDO::PARAM_INT);
        $stmt->bindParam(':per_page', $this->per_page, PDO::PARAM_INT);
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
    public function getDataForUser($pdo, $start, $search) {
        $sql = "SELECT * FROM user WHERE username LIKE :search LIMIT :start, :per_page";
        $stmt = $pdo->prepare($sql);
        $searchParam = "%$search%";
        $stmt->bindParam(':search', $searchParam, PDO::PARAM_STR);
        $stmt->bindParam(':start', $start, PDO::PARAM_INT);
        $stmt->bindParam(':per_page', $this->per_page, PDO::PARAM_INT);
        if ($stmt->execute()) {
            $usersData = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $users = [];
            
            foreach ($usersData as $userData) {
                $user = new User();
                $user->username = $userData['username'];
                $user->password = $userData['password'];
                $user->email = $userData['email'];             

                $users[] = $user;
            }
    
            return $users;
        } 
        
        return null; 
    }
    public function getDataForOrder($pdo, $start, $search) {
        $sql = "SELECT * FROM `order` WHERE order_id LIKE :search LIMIT :start, :per_page";
        $stmt = $pdo->prepare($sql);
        $searchParam = "%$search%";
        $stmt->bindParam(':search', $searchParam, PDO::PARAM_STR);
        $stmt->bindParam(':start', $start, PDO::PARAM_INT);
        $stmt->bindParam(':per_page', $this->per_page, PDO::PARAM_INT);
        if ($stmt->execute()) {
            $ordersData = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $orders = [];
            
            foreach ($ordersData as $orderData) {
                $order = new Order();
                $order->order_id = $orderData['order_id'];
                $order->username = $orderData['username'];
                $order->total_quantity = $orderData['total_quantity'];   
                $order->total_price = $orderData['total_price']; 
                $order->phone_number = $orderData['phone_number']; 
                $order->delivery_address = $orderData['delivery_address'];           

                $orders[] = $order;
            }
    
            return $orders;
        } 
        
        return null; 
    }
    public function getDataForCategory($pdo, $start, $search) {
        $sql = "SELECT * FROM category WHERE cat_id LIKE :search LIMIT :start, :per_page";
        $stmt = $pdo->prepare($sql);
        $searchParam = "%$search%";
        $stmt->bindParam(':search', $searchParam, PDO::PARAM_STR);
        $stmt->bindParam(':start', $start, PDO::PARAM_INT);
        $stmt->bindParam(':per_page', $this->per_page, PDO::PARAM_INT);
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
    public function getTotalPages($pdo, $search) {
        $sql = "SELECT COUNT(*) AS total FROM product WHERE name LIKE '%$search%'";
        $stmt = $pdo->query($sql);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->total_pages = ceil($row['total'] / $this->per_page);
        return $this->total_pages;
    }
    public function getTotalPagesForCatID($pdo, $cat_id) {
        $sql = "SELECT COUNT(*) AS total FROM product WHERE cat_id LIKE '%$cat_id%'";
        $stmt = $pdo->query($sql);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->total_pages = ceil($row['total'] / $this->per_page);
        return $this->total_pages;
    }
    public function getTotalPagesForUser($pdo, $search) {
        $sql = "SELECT COUNT(*) AS total FROM user WHERE username LIKE '%$search%'";
        $stmt = $pdo->query($sql);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->total_pages = ceil($row['total'] / $this->per_page);
        return $this->total_pages;
    }
    public function getTotalPagesForOrder($pdo, $search) {
        $sql = "SELECT COUNT(*) AS total FROM `order` WHERE order_id LIKE '%$search%'";
        $stmt = $pdo->query($sql);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->total_pages = ceil($row['total'] / $this->per_page);
        return $this->total_pages;
    }
    public function getTotalPagesForCategory($pdo, $search) {
        $sql = "SELECT COUNT(*) AS total FROM category WHERE cat_id LIKE '%$search%'";
        $stmt = $pdo->query($sql);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->total_pages = ceil($row['total'] / $this->per_page);
        return $this->total_pages;
    }
}

?>