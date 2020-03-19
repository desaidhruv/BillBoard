<?php

session_start();
if (!isset($_SESSION["username"])) {
    header("Location: index.php");
    die();
}
$db = mysqli_connect("localhost", "root", "", "billboard");
$found = false;

$current = $_SESSION["username"];
if (isset($_POST["other"])) {
    $other = $_POST["other"];
} else {
    echo "Other Not set";
    die();
}
//$other = $_GET["other"];

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
    $query = "select * from `$table` order by `id`";
    $res = mysqli_query($db, $query);
    $json = array();
    $maxid = 0;
    while ($row = mysqli_fetch_array($res)) {
        $json[] = array(
            'id' => $row['id'],
            'sender' => $row['sender'],
            'message' => $row['message'],
            'time' => $row['time']
        );
        $maxid = $row['id'];
    }
    //$query = "replace into`$current`(`username`,`lastread`,`lasttime`) values('$other','$maxid',now())";
    //mysqli_query($db, $query);
    $query = "INSERT INTO `$current` (`username`,`lastread`,`lastrec`,`lasttime`)"
            . "VALUES ('$other', '$maxid',0, now())"
            . "ON DUPLICATE KEY UPDATE `lastread`=VALUES(`lastread`);";
    mysqli_query($db, $query);

    $jsonstring = json_encode($json);
    echo '{"Messages":' . $jsonstring;
    echo ',"maxid":"' . $maxid . '"}';
}
?>