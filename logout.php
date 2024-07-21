<?php 
    setcookie('username', '', time() - 86400*30,'/');
    if($_COOKIE["username"] == "admin") {
        header("location: Admin/index.php");
        return;
    }
    header("location: index.php");
?>