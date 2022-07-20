<?php
    include 'dbconnect.php';
    include 'adminSidebar.php';

    $customerQuery = "SELECT COUNT(CUSTOMER_ID) AS TOTAL FROM CUSTOMERS";
    $customerResult = oci_parse($dbconn, $customerQuery);
    oci_execute($customerResult);

    $productQuery = "SELECT COUNT(PRODUCT_ID) AS TOTAL FROM PRODUCTS";
    $productResult = oci_parse($dbconn, $productQuery);
    oci_execute($productResult);

    $staffQuery = "SELECT COUNT(STAFF_ID) AS TOTAL FROM STAFFS";
    $staffResult = oci_parse($dbconn, $staffQuery);
    oci_execute($staffResult);

    $orderQuery = "SELECT COUNT(RECEIPT_ID) AS TOTAL FROM RECEIPTS";
    $orderResult = oci_parse($dbconn, $orderQuery);
    oci_execute($orderResult);

    $salesQuery = "SELECT SUM(TOTAL_PRICE) AS TOTAL FROM RECEIPTS";
    $salesResult = oci_parse($dbconn, $salesQuery);
    oci_execute($salesResult);

    $highestQuery = "SELECT TOTAL_PRICE AS TOTAL FROM RECEIPTS WHERE TOTAL_PRICE = (SELECT MAX(TOTAL_PRICE) FROM RECEIPTS)";
    $highestResult = oci_parse($dbconn, $highestQuery);
    oci_execute($highestResult);

    $longestQuery = "SELECT ROUND((SYSDATE - TO_DATE(TO_CHAR(CREATED_AT, 'DD/MM/YYYY'),'DD/MM/YYYY'))) AS DAYS FROM STAFFS ORDER BY DAYS DESC";
    $longestResult = oci_parse($dbconn, $longestQuery);
    oci_execute($longestResult);

    $salaryQuery = "SELECT * FROM STAFFS WHERE SALARY = (SELECT MAX(SALARY) FROM STAFFS)";
    $salaryResult = oci_parse($dbconn, $salaryQuery);
    oci_execute($salaryResult);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
</head>
<body>
    <div class="col">
        <nav class="navbar navbar-expand-lg navbar-light bg-light shadow py-4 rounded">
            <div class="px-4">
                <strong class="h5">Administrator</strong>
            </div>
        </nav>
        <div class="card mx-auto p-4 bg-light shadow border border-light">
            Dashboard
            <p id="success" class="mt-3 card-text text-center h5 text-success">
                <?php if(isset($_GET['success'])) { echo $_GET['success']; } ?>
            </p>
        </div>

        <div class="row mt-5">
            <div class="col-md card m-2 p-4 bg-light shadow border border-light">
                Total Sales
                <div class="inline">
                    <span class="fs-4">RM</span><span class="fs-4"><?php while($row = oci_fetch_array($salesResult)) { echo number_format($row['TOTAL'], 2); } ?></span>
                </div>
            </div>

            <div class="col-md card m-2 p-4 bg-light shadow border border-light">
                Total Products
                <div class="inline">
                    <i class="fs-4 bi bi-box-seam"></i><span class="fs-4 p-2"><?php while($row = oci_fetch_array($productResult)) { echo $row['TOTAL']; } ?></span>
                </div>
            </div>

            <div class="col-md card m-2 p-4 bg-light shadow border border-light">
                Total Orders
                <div class="inline">
                    <i class="fs-4 bi bi-bag-check"></i><span class="fs-4 p-2"><?php while($row = oci_fetch_array($orderResult)) { echo $row['TOTAL']; } ?></span>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md card m-2 p-4 bg-light shadow border border-light">
                Total Customers
                <div class="inline">
                    <i class="fs-4 bi bi-person-circle"></i><span class="fs-4 p-2"><?php while($row = oci_fetch_array($customerResult)) { echo $row['TOTAL']; } ?></span>
                </div>
            </div>

            <div class="col-md card m-2 p-4 bg-light shadow border border-light">
                Total Staffs
                <div class="inline">
                    <i class="fs-4 bi bi-person-square"></i><span class="fs-4 p-2"><?php while($row = oci_fetch_array($staffResult)) { echo $row['TOTAL']; } ?></span>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md card m-2 p-4 bg-light shadow border border-light">
                Highest Paid on Single Receipts
                <div class="inline">
                    <span class="fs-4">RM</span><span class="fs-4"><?php while($row = oci_fetch_array($highestResult)) { echo number_format($row['TOTAL'], 2); } ?></span>
                </div>
            </div>

            <div class="col-md card m-2 p-4 bg-light shadow border border-light">
                Longest Employed Staff
                <div class="inline">
                <i class="bi bi-calendar fs-4"></i></i><span class="fs-4 p-2"><?php while($row = oci_fetch_array($longestResult)) { echo $row['DAYS'] . ' '; break;} ?>Days</span>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md card m-2 p-4 bg-light shadow border border-light">
                Top Staff Salary
                <div class="inline">
                    <span class="fs-4">RM</span><span class="fs-4"><?php while($row = oci_fetch_array($salaryResult)) { echo number_format($row['SALARY'], 2); } ?></span>
                </div>
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