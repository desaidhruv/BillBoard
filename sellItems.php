<?php

session_start();
if (!isset($_SESSION["username"])) {
    header("Location: index.php");
    die();
}

$db = mysqli_connect("localhost", "root", "", "billboard");

$current = $_SESSION["username"];

$query = "select * from `sellitems` where username like '$current' order by `id` desc";
$res = mysqli_query($db, $query);
$json = array();
$maxid = -1;
while ($row = mysqli_fetch_array($res)) {
    $json[] = array(
        'id' => $row['id'],
        'name' => $row['name'],
        'username' => $row['username'],
        'price' => $row['price'],
        'quantity' => $row['quantity'],
        'category' => $row['category'],
        'description' => $row['description'],
        'sell' => $row['sell']
    );
    $maxid = $row['id'];
}
$jsonstring = json_encode($json);
echo '{"items":' . $jsonstring;
echo ',"maxid":"' . $maxid . '"}';
?>