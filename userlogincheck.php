
<?php    
    if (isset($_POST["username"])&& isset($_POST["password"])) {
        
        $username = $_POST["username"];
        $username = trim($username);
        $password=trim($_POST["password"]);
        
        $db = mysqli_connect("localhost", "root", "", "billboard");
        $query = "select `username` from `usersdetails` where `username`='$username' and `password`='$password'";
        $res = mysqli_query($db, $query);
        
        if (mysqli_num_rows($res) > 0) {
            echo "ACCEPT USER";
        } else {
            echo "INVALID USER";
        }
    }
    else{
        header("Location: index.php");
    }
    die();
?>