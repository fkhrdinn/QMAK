<?php
	include 'dbconnect.php';
	include 'header.php';

	if(empty($_SESSION['EMAIL']))
	{
		header("Location:login.php");
	}

    $email = $_SESSION['EMAIL'];
    $customerQuery = "SELECT * FROM CUSTOMERS C INNER JOIN RECEIPTS R ON C.CUSTOMER_ID = R.CUSTOMER_ID WHERE C.EMAIL = '$email'";
    $customerResult = oci_parse($dbconn, $customerQuery);
    oci_execute($customerResult);
   
    $number = 1;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order History</title>
</head>
<body>
<div class="col mx-5">
        <div class="mt-5 card mx-auto p-4 bg-light shadow border border-light">
            <div class="table-responsive">
                <table class="mt-3 table caption-top table-striped table-hover">
                    <caption>List of order history</caption>
                    <thead>
                        <tr>
                        <th scope="col">#</th>
                        <th scope="col">First Name</th>
                        <th scope="col">Last Name</th>
                        <th scope="col">Address</th>
                        <th scope="col">Total Price</th>
                        <th scope="col">Status</th>
                        <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while($row = oci_fetch_array($customerResult))
                        {
                            ?>
                            <tr>
                                <th scope="row"><?php echo $number; ?></th>
                                <td><?php echo $row['FIRST_NAME']; ?></td>
                                <td><?php echo $row['LAST_NAME']; ?></td>
                                <td><?php echo $row['ADDRESS']; ?></td>
                                <td><?php echo "RM ". number_format($row['TOTAL_PRICE'], 2); ?></td>
                                <td class="<?php if($row['STATUS'] == 'PENDING') {echo "text-warning";} else {echo "text-success";} ?>"><?php echo $row['STATUS']; ?></td>
                                <td><button type='button' class='btn btn-primary'><a class="text-white text-decoration-none" href="orderDetails.php?receipt=<?php echo $row['RECEIPT_ID']; ?>">View Order</a></button></td>
                            </tr>

                            <?php
                            $number++;
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>