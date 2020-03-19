<?php

session_start();
if (!isset($_SESSION["username"])) {
    header("Location: index.php");
    die();
}

$db = mysqli_connect("localhost", "root", "", "billboard");

$current = $_SESSION["username"];
/* if (isset($_POST["searchItems"])) {
  $json = array();
  if (!empty($_POST["searchItems"])) {
  $s = $_POST["searchItems"];
  $query = "select name from `sellitems` where username not like '$current' and name like '%$s%'";
  $res = mysqli_query($db, $query);
  while ($row = mysqli_fetch_array($res)) {
  array_push($json,$row["name"]);
  }
  }
  $jsonstring = json_encode($json);
  echo '{"items":' . $jsonstring . '}';
  } else { */
$query = "select * from `sellitems` where username not like '$current'";
if (isset($_POST["search"]) && !empty($_POST["search"])) {
    $name = trim($_POST["search"]);
    $query.=" and name like '%$name%'";
}
if (isset($_POST["cat"]) && !empty($_POST["cat"])) {
    $cat = $_POST["cat"];
    $query.=" and category like '%$cat%'";
}
if (isset($_POST["sortby"]) && !empty($_POST["sortby"])) {
    $sortby = trim($_POST["sortby"]);
    //echo $sortby;
    if ($sortby == "High to Low") {
        $a = "price";
        $b = "desc";
    } else if ($sortby == "Low to High") {
        $a = "price";
        $b = "asc";
    } else if ($sortby == "A-z") {
        $a = "name";
        $b = "asc";
    } else if ($sortby == "Z-a") {
        $a = "name";
        $b = "desc";
    }
    $query.=" order by `$a` $b";
} else {
    $query.=" order by `name` asc";
}
//echo $query;
$res = mysqli_query($db, $query);
$json = array();
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
}
$jsonstring = json_encode($json);
echo '{"items":' . $jsonstring . '}';
//}
?>