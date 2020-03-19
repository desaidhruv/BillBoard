<?php
    session_start();
    if(!isset($_SESSION["username"])){
        if(isset($_POST["username"])){
            $_SESSION["username"]=$_POST["username"];
        }
        else{
            header("Location: index.php");
            die();
        }
    }
    header("Location: home_page.php");
    die();
?>