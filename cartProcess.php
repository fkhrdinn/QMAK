<?php
    include 'dbconnect.php';
    session_start();

    if(isset($_POST['productID']))
    {
        $productID = $_POST['productID'];
        $email = $_SESSION['EMAIL'];
        $customerQuery = "SELECT * FROM CUSTOMERS WHERE EMAIL = '$email'";
        $customerResult = oci_parse($dbconn, $customerQuery);
        oci_execute($customerResult);
        
        while($row = oci_fetch_array($customerResult))
        {
            $customerID = $row['CUSTOMER_ID'];
            $query = "INSERT INTO CARTS (CUSTOMER_ID, PRODUCT_ID) VALUES ('$customerID', '$productID')";
            $result = oci_parse($dbconn, $query);
            oci_execute($result);
            header("Location: shop.php?message=Item%20added%20to%20cart.");
        }
        
    }
?>