<?php
    include 'dbconnect.php';
    session_start();

    if(isset($_POST['form-checkout']))
    {
        
        $cartID = $_POST['cartID'];
        $quantity = $_POST['quantity'];
        $totalPrice = $_POST['totalPrice'];

        $date = new DateTime();
		$today = $date->format('y-M-d h:i:s');

        $email = $_SESSION['EMAIL'];
        $customerQuery = "SELECT * FROM CUSTOMERS WHERE EMAIL = '$email'";
        $customerResult = oci_parse($dbconn, $customerQuery);
        oci_execute($customerResult);
        $customerID = NULL;

        while($row = oci_fetch_array($customerResult))
        {
            $customerID = $row['CUSTOMER_ID'];  
        }

        echo $customerID;
        $receiptQuery = "INSERT INTO RECEIPTS (TOTAL_PRICE, CREATED_AT, CUSTOMER_ID, STATUS) 
        VALUES ('$totalPrice', '$today', '$customerID', 'PENDING')";
        $receiptResult = oci_parse($dbconn, $receiptQuery);
        oci_execute($receiptResult);

        $getReceipt = "SELECT * FROM RECEIPTS WHERE CREATED_AT = '$today'";
        $getReceiptResult = oci_parse($dbconn, $getReceipt);
        oci_execute($getReceiptResult);
        $receiptID = null;

        while($row = oci_fetch_array($getReceiptResult))
        {
            $receiptID = $row['RECEIPT_ID'];
        }

        foreach($cartID as $cart)
        {
            $cartQuery = "UPDATE CARTS SET RECEIPT_ID = '$receiptID' WHERE CART_ID = '$cart'";
            $cartResult = oci_parse($dbconn, $cartQuery);
            oci_execute($cartResult);
        }

        $number = 0;
        foreach($quantity as $quan)
        {
            $paid = new DateTime();
		    $now = $paid->format('y-M-d h:i:s');
            $cartQuery = "UPDATE CARTS SET QUANTITY = '$quan', PAID_AT = '$now', STATUS = 'PENDING' WHERE CART_ID = '$cartID[$number]'";
            $cartResult = oci_parse($dbconn, $cartQuery);
            oci_execute($cartResult);
            $number++;
        } 

        header("Location: shop.php?message=Transaction%20successful.");
    }

    if(isset($_POST['removeCart']))
	{
		$cart = $_POST['removeCart'];
		
        $query = "DELETE FROM CARTS WHERE CART_ID = '$cart'";
        $result = oci_parse($dbconn, $query);
        $status = oci_execute($result);

        if($status)
        {
            header("Location: cart.php?message=Product%20has%20been%20removed.");
        }
        else
        {
            header("Location: cart.php?message=Product%20failed%20to%20be%20removed.");
        }
	}
?>