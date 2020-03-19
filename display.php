<?php
session_start();
error_reporting(E_ALL);
if (!isset($_SESSION["username"])) {
    header("Location: index.php");
    die();
}
// some basic sanity checks
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    //connect to the db
    $db = mysqli_connect("localhost", "root", "", "billboard") or die(mysqli_error($db));

    // get the image from the db
    $sql = "select `image` from `sellitems` where `id`=" . $_GET['id'];

    // the result of the query
    $result = mysqli_query($db, $sql) or die(mysqli_error($db));
    $r = mysqli_fetch_array($result);
    // set the header for the image
    header("Content-type: image/jpeg");
    echo $r["image"];

    // close the db link
    mysqli_close($db);
}else {
    if (isset($_GET["username"])) {
        $username = $_GET["username"];
    } else {
        $username = $_SESSION["username"];
    }
    //connect to the db
    $db = mysqli_connect("localhost", "root", "", "billboard") or die(mysqli_error($db));

    // get the image from the db
    $sql =  "SELECT `image` FROM `usersdetails` WHERE `username` like '$username'";

    // the result of the query
    $result = mysqli_query($db, $sql) or die(mysqli_error($db));
    $r = mysqli_fetch_array($result);
    // set the header for the image
    header("Content-type: image/jpeg");
    echo $r["image"];

    // close the db link
    mysqli_close($db);
}
?>