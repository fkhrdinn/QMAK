<?php
    include 'dbconnect.php';
    session_start();

    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "SELECT * FROM CUSTOMERS WHERE EMAIL = '$email' AND PASSWORD = '$password'";
    $result = oci_parse($dbconn, $query);
    oci_execute($result);
    $row = oci_fetch_array($result);

    if($row)
    {
        $_SESSION['EMAIL'] = $row['EMAIL'];

        header("Location: home.php?success=Successfully%20login.");
    }
    else
    {    
        header("Location: login.php?error=Incorrect%20credentials.");   
    }
?>