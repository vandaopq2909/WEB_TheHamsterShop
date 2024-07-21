<?php 
class Database {
    public function getConnect() {
        // $host = "sql313.byethost7.com";
        // $db = "b7_36199636_mydb_hamster_shop";
        // $username = "b7_36199636";
        // $password = "zandaone";

        $host = "localhost";
        $db = "mydb_hamster_shop";
        $username = "mydb_hamster_shop_admin";
        $password = "5mCEYeK!P5OrMGt6";

        $dsn = "mysql:host=$host;dbname=$db;charset=UTF8";

        try {
            $pdo = new PDO($dsn, $username, $password);

            if ($pdo) {
                return $pdo;
            }
        } catch (PDOException $ex) {
            echo $ex->getMessage();
        }
    }
}