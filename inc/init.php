<?php
    require_once "class/Database.php";
    session_start();
    
    if (!isset($_SESSION["cart"])) {
        $_SESSION["cart"] = array();
    }
?>