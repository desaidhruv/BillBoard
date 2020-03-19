<?php

session_start();
if (!isset($_SESSION["username"])) {
    header("Location: index.php");
    die();
}
$db = mysqli_connect("localhost", "root", "", "billboard");
$found = false;

$current = $_SESSION["username"];
if (isset($_POST["other"]) && isset($_POST["message"])) {
    $other = $_POST["other"];
    $message = $_POST["message"];
}
else{    
    die();
}
//$other="asd";
//$message="cool";
$table1 = $current . "_" . $other;
$table = $table1;
$checktable1 = mysqli_query($db, "SHOW TABLES LIKE '$table1'");

if (!$found && mysqli_num_rows($checktable1) > 0) {
    $table = $table1;
    $found = true;
}
if (!$found) {
    $table2 = $other . "_" . $current;
    $checktable2 = mysqli_query($db, "SHOW TABLES LIKE '$table2'");
    if (mysqli_num_rows($checktable2) > 0) {
        $table = $table2;
        $found = true;
    }
}
if (!$found) {
    $query = "create table `$table` like `messageSample`";
    $createtable = mysqli_query($db, $query);
    $found = $createtable;
    if ($createtable === FALSE) {
        echo "ERROR";
        die();
    }
}

if ($found) {
    $query = "insert into `$table`(`sender`,`message`,`time`) values('$current','$message',now())";
    $res = mysqli_query($db, $query);
    if ($res == TRUE) {
        $q="select max(id) from `$table`";
        $row = mysqli_fetch_array(mysqli_query($db, $q));
        $maxid=$row["max(id)"];
        
        $query = "INSERT INTO `$current` (`username`,`lastread`,`lastrec`,`lasttime`)"
                . "VALUES ('$other', '$maxid','$maxid', now())"
                . "ON DUPLICATE KEY UPDATE `lastread`=VALUES(`lastread`),`lastrec`=VALUES(`lastrec`),`lasttime`=VALUES(`lasttime`);";
        mysqli_query($db, $query);
        
        $query = "INSERT INTO `$other` (`username`,`lastread`,`lastrec`,`lasttime`)"
                . "VALUES ('$current', 0,'$maxid', now())"
                . "ON DUPLICATE KEY UPDATE `lasttime`=VALUES(`lasttime`),`lastrec`=VALUES(`lastrec`);";
        mysqli_query($db, $query);
        
        echo "SENT";
    } else {
        echo "ERROR";
    }
}
?>