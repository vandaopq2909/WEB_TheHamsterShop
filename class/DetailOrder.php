<?php
class DetailOrder {
    public $detail_order_id;
    public $order_id;
    public $pro_id;
    public $quantity;
    public $price;

    public function __construct($detail_order_id=0, $order_id="", $pro_id=0, $quantity=0, $price=0) {
        $this->detail_order_id = $detail_order_id;
        $this->order_id = $order_id;
        $this->pro_id = $pro_id;
        $this->quantity = $quantity;
        $this->price = $price;
    }

    public static function getAll($pdo) {
        $sql = "SELECT * FROM detail_order";
        $stmt = $pdo->prepare($sql);
    
        if ($stmt->execute()) {
            $detailOrdersData = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $detailOrders = [];
            
            foreach ($detailOrdersData as $detailOrderData) {
                $detailOrder = new DetailOrder();
                $detailOrder->detail_order_id = $detailOrderData['detail_order_id'];
                $detailOrder->order_id = $detailOrderData['order_id'];
                $detailOrder->pro_id = $detailOrderData['pro_id'];
                $detailOrder->quantity = $detailOrderData['quantity'];
                $detailOrder->price = $detailOrderData['price'];

                $detailOrders[] = $detailOrder;
            }
    
            return $detailOrders;
        } 
        
        return null; 
    } 
    public static function getDetailOrdersByOrderID($pdo, $order_id) {
        $sql = "SELECT * FROM detail_order WHERE order_id LIKE :order_id";
        
        $stmt = $pdo->prepare($sql);
        $searchParam = "%$order_id%";
        $stmt->bindParam(':order_id', $searchParam, PDO::PARAM_STR);

        $detailOrders = [];
        if ($stmt->execute()) {
            $detailOrdersData = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($detailOrdersData as $detailOrderData) {
                $detailOrder = new DetailOrder();
                $detailOrder->detail_order_id = $detailOrderData['detail_order_id'];
                $detailOrder->order_id = $detailOrderData['order_id'];
                $detailOrder->pro_id = $detailOrderData['pro_id'];
                $detailOrder->quantity = $detailOrderData['quantity'];
                $detailOrder->price = $detailOrderData['price'];

                $detailOrders[] = $detailOrder;
            }
    
            return $detailOrders;
        } 
        return null;
    }

}
?>