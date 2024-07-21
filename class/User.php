<?php
class User {
    public $username;
    public $password;
    public $email;

    public function __construct($username="", $password="", $email="") {
        $this->username = $username;
        $this->password = $password;
        $this->email = $email;
    }
    public static function getAll($pdo) {
        $sql = "SELECT * FROM user";
        $stmt = $pdo->prepare($sql);
    
        if ($stmt->execute()) {
            $usersData = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $users = [];
            
            foreach ($usersData as $userData) {
                $user = new user();
                $user->username = $userData['username'];
                $user->password = $userData['password'];
                $user->email = $userData['email'];

                $users[] = $user;
            }
    
            return $users;
        } 
        
        return null; 
    }
    public static function addOneUser($pdo, $user) {
        $sql = "INSERT INTO user(username, password, email) VALUE('$user->username', '$user->password', '$user->email')";
        if ($pdo->query($sql)) {
            return true;
        } else {
            return false;
        }
    }
    public static function isUsernameExists($pdo, $username) {     
        try {
            $query = "SELECT COUNT(*) FROM user WHERE username = :username";
            $stmt = $pdo->prepare($query);
            $stmt->execute(array(':username' => $username));
            $count = $stmt->fetchColumn();
            if ($count == 0) {
                return true;
            }
            return false;
        } catch (PDOException $e) {
            return false;
        }
    }
    public static function deleteUserByUsername($pdo, $username) {
        // Chuẩn bị truy vấn SQL DELETE
        $sql = "DELETE FROM user WHERE username = :username";

        // Thực thi truy vấn với tham số được ràng buộc
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();
        if ($stmt->execute()) {
            echo "User đã được xóa thành công!";
        } else {
            echo "Lỗi: " . $sql . "<br>" . $pdo->error;
        }
    }
    
}