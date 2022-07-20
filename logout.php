<?php
    session_start();
    unset($_SESSION["EMAIL"]);
    header("Location:home.php");
?>