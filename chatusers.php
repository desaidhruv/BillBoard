<?php

session_start();
if (!isset($_SESSION["username"])) {
    header("Location: index.php");
    die();
}
$db = mysqli_connect("localhost", "root", "", "billboard");

$current = $_SESSION["username"];
//$query = "select * from `$current` order by `lasttime` desc";
$query = "select * from `$current` order by `lasttime` desc";
$res = mysqli_query($db, $query);
$json = array();
$lasttime=new DateTime('2015-01-01');
while ($row = mysqli_fetch_array($res)) {
    $json[] = array(
        'username' => $row['username'],
        'lastread' => $row['lastread'],
        'lastrec' => $row['lastrec'],
        'lasttime' => $row['lasttime']
    );
    $d=new DateTime($row['lasttime']);
    if($lasttime<$d){
        $lasttime=$d;
    }
}
$jsonstring = json_encode($json);
echo '{"users":' . $jsonstring . ',"lasttime":'.json_encode($lasttime).'}';
?>