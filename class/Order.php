<?php
    class Order {
        public $order_id;
        public $username;
        public $total_quantity;
        public $total_price;
        public $phone_number;
        public $delivery_address;

        public function __construct($order_id="", $username="", $total_quantity=0, $total_price=0, $phone_number="", $delivery_address="") {
            $this->order_id = $order_id;
            $this->username = $username;
            $this->total_quantity = $total_quantity;
            $this->total_price = $total_price;
            $this->phone_number = $phone_number;
            $this->delivery_address = $delivery_address;
        }
        public static function getAll($pdo) {
            $sql = "SELECT * FROM `order`";
            $stmt = $pdo->prepare($sql);
        
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
        public static function deleteOrderByOrderID($pdo, $order_id) {
            // Chuẩn bị truy vấn SQL DELETE
            $sql = "DELETE FROM `order` WHERE order_id = :order_id";
    
            // Thực thi truy vấn với tham số được ràng buộc
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':order_id', $order_id, PDO::PARAM_STR);
            $stmt->execute();
            if ($stmt->execute()) {
                echo "Đơn hàng đã được xóa thành công!";
            } else {
                echo "Lỗi: " . $sql . "<br>" . $pdo->error;
            }
        }  
    }
?>