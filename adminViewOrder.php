<?php
    include 'dbconnect.php';
    include 'adminSidebar.php';

    $receiptID = $_GET['receipt'];

    $query = "SELECT * FROM RECEIPTS JOIN CARTS USING (RECEIPT_ID) JOIN PRODUCTS USING (PRODUCT_ID) JOIN MEDIA USING (PRODUCT_ID) WHERE RECEIPT_ID = '$receiptID'";
    $queryResult = oci_parse($dbconn, $query);
    oci_execute($queryResult);

    $number = 1;
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
            Invoices - Order Details
        </div>

        <div class="mt-5 card mx-auto p-4 bg-light shadow border border-light">
            <div class="table-responsive">
                <table class="mt-3 table caption-top table-striped table-hover">
                    <caption>List of products</caption>
                    <thead>
                        <tr>
                        <th scope="col">#</th>
                        <th scope="col">Product Photo</th>
                        <th scope="col">Product Name</th>
                        <th scope="col">Product Price</th>
                        <th scope="col">Product Quantity</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while($row = oci_fetch_array($queryResult))
                        {
                            ?>
                            <tr>
                                <th scope="row"><?php echo $number; ?></th>
                                <td> <img style="width:150px;height:120px" class="card-img-top" src="Media/ProductPhotos/<?php echo $row['MEDIA_NAME']; ?>" alt="product"></td>
                                <td><?php echo $row['PRODUCT_NAME']; ?></td>
                                <td><?php echo "RM " . number_format($row['PRODUCT_PRICE'], 2); ?></td>
                                <td><?php echo $row['QUANTITY']; ?></td>
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

</div>
</div>