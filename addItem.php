<?php

session_start();
if (!isset($_SESSION["username"])) {
    header("Location: index.php");
    die();
}
$db = mysqli_connect("localhost", "root", "", "billboard");

$current = $_SESSION["username"];
if (isset($_POST["operation"])) {
    $operation = $_POST["operation"];
    //echo $name . $price .$quantity.$category.$description.$operation;
} else {
    die();
}

if ($operation == "delete") {
    if (isset($_POST["Uid"])) {
        $id = $_POST["Uid"];
    } else {
        die();
    }
    $query = "delete from `sellitems` where `id`=$id and `username` like '$current'";
    if (mysqli_query($db, $query)) {
        echo "DELETED";
    } else {
        echo "FAILED";
    }
} else {
    if (isset($_POST["pname"]) && isset($_POST["sr"])&&isset($_POST["price"]) && isset($_POST["quantity"]) && isset($_POST["cat"]) && isset($_POST["ds"])) {
        $name = $_POST["pname"];
        $price = $_POST["price"];
        $quantity = $_POST["quantity"];
        $category = $_POST["cat"];
        $description = $_POST["ds"];
        $sell=$_POST["sr"];
    } else {
        die();
    }
    if ($operation == "add") {
        $image;
        $xyz = false;

        if (isset($_FILES['image'])) {
            $maxsize = 10000000; //set to approx 10 MB
            //check whether file is uploaded with HTTP POST
            if (is_uploaded_file($_FILES['image']['tmp_name'])) {
                //checks size of uploaded image on server side
                if ($_FILES['image']['size'] < $maxsize) {
                    //checks whether uploaded file is of image type                    
                    $finfo = finfo_open(FILEINFO_MIME_TYPE);
                    if (strpos(finfo_file($finfo, $_FILES['image']['tmp_name']), "image") === 0) {
                        // prepare the image for insertion
                        $image = addslashes(file_get_contents($_FILES['image']['tmp_name']));
                        $xyz = true;
                    }
                }
            }
        }
        if (!$xyz) {
            $image = addslashes(file_get_contents("image/bb.jpg"));
        }
        //echo "xyz:".var_dump($xyz).var_dump(isset($_FILES['image']))."<br>";

        $query = "insert into `sellitems`(`username`,`name`,`price`,`quantity`,`category`,`description`,`image`,`sell`) "
                . "values('$current','$name','$price','$quantity','$category','$description','$image','$sell')";
        //echo $query;
        $res = mysqli_query($db, $query);
        if ($res == TRUE) {
            echo "SENT";
            header("Location: sell.php");
            die();
        } else {
            echo "ERROR";
        }
    } else if ($operation == "update") {
        if (isset($_POST["Uid"])) {
            $id = $_POST["Uid"];
        } else {
            die();
        }
        $query = "UPDATE `sellitems` SET `name` = '$name', `price` = '$price', `quantity` = '$quantity', `category` = '$category',"
                . " `description` = '$description',`sell`='$sell'";

        $image;
        if (isset($_FILES['image'])) {
            $maxsize = 10000000; //set to approx 10 MB
            //check whether file is uploaded with HTTP POST
            if (is_uploaded_file($_FILES['image']['tmp_name'])) {
                //checks size of uploaded image on server side
                if ($_FILES['image']['size'] < $maxsize) {
                    //checks whether uploaded file is of image type                    
                    $finfo = finfo_open(FILEINFO_MIME_TYPE);
                    if (strpos(finfo_file($finfo, $_FILES['image']['tmp_name']), "image") === 0) {
                        // prepare the image for insertion                    
                        $image = addslashes(file_get_contents($_FILES['image']['tmp_name']));
                        $query.=",`image`='$image'";
                    }
                }
            }
        }
        $query.=" WHERE `id` = '$id' and `username` like '$current'";
        //echo $query;
        $res = mysqli_query($db, $query);
        if ($res == TRUE) {
            echo "UPDATED";
            header("Location: sell.php");
            die();
        } else {
            echo "ERROR";
        }
    }
}
?>