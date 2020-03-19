<?php

error_reporting(E_ALL);
$db = mysqli_connect("localhost", "root", "", "billboard");

$current = $_GET["user"];
$query = "select username from `$current`";
$res = mysqli_query($db, $query);

while ($row = mysqli_fetch_array($res)) {
    $other = $row["username"];
    
    $table1 = $current . "_" . $other;
    mysqli_query($db, "DROP TABLE IF EXISTS `billboard`.`$table1`");
    
    $table2 = $other . "_" . $current;
    mysqli_query($db, "DROP TABLE IF EXISTS `billboard`.`$table2`");
    
    mysqli_query($db, "delete from `$other` where `username` like '$current'");
}
mysqli_query($db, "DROP TABLE IF EXISTS `billboard`.`$current`");
mysqli_query($db, "delete from `usersdetails` where `username` like '$current'");
?>