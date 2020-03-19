<?php

$db = mysqli_connect("localhost", "root", "", "billboard");

$json;
if (isset($_POST["fname"]) && isset($_POST["lname"]) && isset($_POST["year"]) &&
        isset($_POST["dept"]) && isset($_POST["r"]) /* && isset($_POST["email"]) */ && isset($_POST["pass1"]) /* && isset($_POST["contact"]) */) {
//all values
    session_start();
    $username = $_SESSION["username"];
    $name = $_POST["fname"];
    $surname = $_POST["lname"];
    $year = $_POST["year"];
    $dept = $_POST["dept"];
    $gender = $_POST["r"];
    //$emailid = $_POST["email"];
    $password = $_POST["pass1"];
    //$contact = $_POST["contact"];
    $image_type;
    $image;

    $query = "update `usersdetails` set ";
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
                    $image_type = $_FILES['image']['name'];
                    $image = addslashes(file_get_contents($_FILES['image']['tmp_name']));

                    $query.="`image_type`='$image_type',`image`='$image',";
                }
            }
        }
    }
    $name = trim($name);
    $surname = trim($surname);
    $dept = trim($dept);
    $year = trim($year);
    $gender = trim($gender);

    $query.="`name`='$name',`surname`='$surname',`password`='$password',`dept`='$dept',`year`='$year',`gender`='$gender' where `username` like '$username'";

    if (mysqli_query($db, $query) == true) {
        echo "OK";
    } else {
        echo "ERROR";
    }
    header("Location: edit.php");
    die();
} else {
    if (isset($_POST["username"])) {
        $username = $_POST["username"];
    } else {
        $username = $_SESSION["username"];
    }
    $query = "select * from `usersdetails` where `username` like '$username'";
    $res = mysqli_query($db, $query);
    $row = mysqli_fetch_array($res);

    $json = array(
        'fname' => $row['name'],
        'lname' => $row['surname'],
        'year' => $row['year'],
        'dept' => $row['dept'],
        'r' => $row['gender'],
        'email' => $row['emailid'],
        'username' => $row['username'],
        'pass' => $row['password'],
            //'contact' => $row['contact']
    );
    if (isset($_POST["username"])) {
        echo '{"user":' . json_encode($json) . '}';
    }
}
?>