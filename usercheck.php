
<?php
    $db = mysqli_connect("localhost", "root", "", "billboard");
    if(isset($_POST["username"])){
        $username=$_POST["username"];
        $username=trim($username);
        $query = "select `username` from `usersdetails` where `username`='$username'";
        $res = mysqli_query($db, $query);
        
        if(mysqli_num_rows($res)>0){
            echo '<span style="color: red;">The username <b>'.$username.'</b> is already in use.</span>';
        }
        else{
            echo '<span style="color: lightgreen;">Available</span>';
        }
    }
?>