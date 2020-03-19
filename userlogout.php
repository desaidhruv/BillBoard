<?php
    session_start();
    session_unset();
    session_destroy();
    echo "DESTROYED";
    header("Location: index.php");
    die();
?>