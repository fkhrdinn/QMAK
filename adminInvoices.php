<?php
    include 'dbconnect.php';
    include 'adminSidebar.php';

    $table = $_GET['table'];

    $customerQuery = "SELECT * FROM CUSTOMERS C INNER JOIN RECEIPTS R ON C.CUSTOMER_ID = R.CUSTOMER_ID WHERE STATUS = '$table' ORDER BY RECEIPT_ID ASC";
    $customerResult = oci_parse($dbconn, $customerQuery);
    oci_execute($customerResult);
   
    $number = 1;

    $email = $_SESSION['EMAIL'];
    $staffQuery = "SELECT * FROM STAFFS WHERE EMAIL = '$email'";
    $staffResult = oci_parse($dbconn, $staffQuery);
    oci_execute($staffResult);
    $staffID = null;

    while($selectRow = oci_fetch_array($staffResult))
    {
        $staffID = $selectRow['STAFF_ID'];
    }

    if(isset($_POST['complete']))
    {
        $data = $_POST['complete'];
        
        $query = "UPDATE RECEIPTS SET STATUS = 'COMPLETED', STAFF_ID = '$staffID' WHERE RECEIPT_ID = '$data'";
        $result = oci_parse($dbconn, $query);
        $statusR = oci_execute($result);

        $cartQuery = "UPDATE CARTS SET STATUS = 'COMPLETED' WHERE RECEIPT_ID = '$data'";
        $cartResult = oci_parse($dbconn, $cartQuery);
        $statusC = oci_execute($cartResult);

        if($statusC && $statusR)
        {
            header("Location: adminInvoices.php?table=PENDING&message=Order%20status%20has%20been%20updated.");
        }
        else
        {
            header("Location: adminInvoices.php?table=PENDING&message=Order%20status%20failed%20to%20be%20updated.");
        }

    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Invoices</title>
</head>
<body>
<div class="col">
        <nav class="navbar navbar-expand-lg navbar-light bg-light shadow py-4 rounded">
            <div class="px-4">
                <strong class="h5">Administrator</strong>
            </div>
        </nav>
        <div class="card mx-auto p-4 bg-light shadow border border-light">
            Invoices
        </div>

        <div class="mt-5 card mx-auto p-4 bg-light shadow border border-light">
            <form action="" method="GET">
            <div class="input-group mb-3">
                <button name="table" value="PENDING" class="btn btn-outline-secondary <?php if($_GET['table'] == 'PENDING') {echo 'bg-secondary text-white';} ?>" type="submit">Pending Order</button>
                <button name="table" value="COMPLETED" class="btn btn-outline-secondary <?php if($_GET['table'] == 'COMPLETED') {echo 'bg-secondary text-white';} ?>" type="submit">Completed Order</button>
            </div>
            </form>
            <div id="success">
                <p class="text-center text-success"><?php if(isset($_GET['message'])) { echo $_GET['message']; }?></p>
            </div>
            <div class="table-responsive">
                <table class="mt-3 table caption-top table-striped table-hover">
                    <caption>List of invoices</caption>
                    <thead>
                        <tr>
                        <th scope="col">#</th>
                        <th scope="col">First Name</th>
                        <th scope="col">Last Name</th>
                        <th scope="col">Address</th>
                        <th scope="col">Total Price</th>
                        <th scope="col">Status</th>
                        <th scope="col">Action</th>
                        <?php
                        if($_GET['table'] == 'PENDING')
                        {
                            ?>
                            <th scope="col">Change Status</th>
                            <?php
                        }  
                        ?>
                        </tr>
                    </thead>
                    <tbody>
                    <form action="" method="POST">
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
                                <td><button type='button' class='btn btn-primary'><a class="text-white text-decoration-none" href="adminViewOrder.php?receipt=<?php echo $row['RECEIPT_ID']; ?>">View Order</a></button></td>
                                <?php
                                if($row['STATUS'] == 'PENDING')
                                {
                                    ?>
                                    <td class="align-middle">
                                        <div class="input-group mb-3">
                                            <div>
                                                <input class="form-check-input mt-0" type="checkbox" onclick="submit();" value="<?php echo $row['RECEIPT_ID']; ?>" name="complete">
                                            </div>
                                        </div>
                                    </td>
                                    <?php
                                }
                                    
                                ?>
                            </tr>
                            <?php
                            $number++;
                        }
                        ?>
                        </form>
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>

<script>
    setTimeout(() => {
    const box = document.getElementById('success');
    box.style.display = 'none';
    }, 3000); 
</script>


</div>
</div>