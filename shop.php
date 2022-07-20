<?php
	include 'dbconnect.php';
    include 'header.php';

    $categoryQuery = "SELECT * FROM PRODUCT_CATEGORIES";
    $categoryResult = oci_parse($dbconn, $categoryQuery);
    oci_execute($categoryResult);

    if(!empty($_GET['searchProduct']) && !empty($_GET['categoryID']))
    {
        $search = $_GET['searchProduct'];
        $categoryID = $_GET['categoryID'];
        $query = "SELECT * FROM PRODUCTS JOIN MEDIA USING (PRODUCT_ID) WHERE LOWER(PRODUCT_NAME) LIKE LOWER('%$search%') AND CATEGORY_ID = '$categoryID'";
        $queryResult = oci_parse($dbconn, $query);
        oci_execute($queryResult);
    }
    else if(!empty($_GET['searchProduct']))
    {
        $search = $_GET['searchProduct'];
        $query = "SELECT * FROM PRODUCTS JOIN MEDIA USING (PRODUCT_ID) WHERE LOWER(PRODUCT_NAME) LIKE LOWER('%$search%')";
        $queryResult = oci_parse($dbconn, $query);
        oci_execute($queryResult);
    }
    else if(!empty($_GET['categoryID']))
    {
        $categoryID = $_GET['categoryID'];
        $query = "SELECT * FROM PRODUCTS JOIN MEDIA USING (PRODUCT_ID) WHERE CATEGORY_ID = '$categoryID'";
        $queryResult = oci_parse($dbconn, $query);
        oci_execute($queryResult);
    }
    else
    {
        $query = "SELECT * FROM PRODUCTS JOIN MEDIA USING (PRODUCT_ID)";
        $queryResult = oci_parse($dbconn, $query);
        oci_execute($queryResult);
    }

    if(isset($_GET['resetFilter']))
    {
        $query = "SELECT * FROM PRODUCTS JOIN MEDIA USING (PRODUCT_ID)";
        $queryResult = oci_parse($dbconn, $query);
        oci_execute($queryResult);
    }

    if(!empty($_SESSION['EMAIL']))
    {
        $email = $_SESSION['EMAIL'];
        $customerQuery = "SELECT * FROM CUSTOMERS WHERE EMAIL = '$email'";
        $customerResult = oci_parse($dbconn, $customerQuery);
        oci_execute($customerResult);

        while($row = oci_fetch_array($customerResult))
        {
            $customerID = $row['CUSTOMER_ID'];
            $cartQuery = "SELECT * FROM CARTS WHERE CUSTOMER_ID = '$customerID' AND STATUS IS NULL";
            $cartResult = oci_parse($dbconn, $cartQuery);
            oci_execute($cartResult);
        }
    }
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop</title>
</head>

<body>

<div class="mt-5 mb-5 shadow-lg border">
<section class="py-5">
    <div class="container px-4 px-lg-5 mt-5">
        <div class="row justify-content-center mb-5">
            <div id="success">
                <p class="text-center text-success"><?php if(isset($_GET['message'])) { echo $_GET['message']; }?></p>
            </div>
            <form action="" method="GET">
                <div class="col-auto input-group mb-3">
                    <span class="input-group-text"><i class="fs-4 bi bi-search"></i></span>
                    <input class="rounded form-control" name="searchProduct" type="text" placeholder="Search products...">
                </div>
                <div class="col-auto input-group mb-3">
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">Categories</button>
                    <ul class="dropdown-menu">
                        <li><button class="dropdown-item d-none" selected value="null"></button></li>
                        <?php while($row = oci_fetch_array($categoryResult))
                        {
                            ?>
                                <li><button class="dropdown-item" name="categoryID" value="<?php echo $row['CATEGORY_ID']; ?>"><?php echo $row['CATEGORY_NAME']; ?></button></li>
                            <?php
                        }
                        ?>
                    </ul>
                    <button class="btn btn-outline-danger" name="resetFilter" type="submit">Reset</button>
                </div>
            </form>
        </div>
        <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-2 row-cols-xl-4 justify-content-center">
            <?php 
            if(!empty($_SESSION['EMAIL']))
            {
                $exist = array();
                while($cartRow = oci_fetch_array($cartResult)) 
                {
                    $exist[] = $cartRow['PRODUCT_ID'];
                }
            }?>
            
            <?php while($row = oci_fetch_array($queryResult))
            {
                ?>
                <div class="col-auto mb-5">
                    <div class="card h-100">
                        <!-- Product image-->
                        <img class="card-img-top" src="Media/ProductPhotos/<?php echo $row['MEDIA_NAME']; ?>" alt="Product"/>
                        <!-- Product details-->
                        <div class="card-body p-4">
                            <div class="text-center">
                                <!-- Product name-->
                                <h5 class="fw-bolder"><?php echo $row['PRODUCT_NAME']; ?></h5>
                                <!-- Product price-->
                                <?php echo 'RM ' . number_format($row['PRODUCT_PRICE'], 2); ?> 
                            </div>
                        </div>
                        <!-- Product actions-->
                        <?php if(!empty($_SESSION['EMAIL']))
                        {
                            ?>
                            <form action="cartProcess.php" method="POST">
                                <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
                                    <div class="text-center">
                                        <button class="btn btn-outline-dark mt-auto" name="productID" type="submit" 
                                        <?php
                                        if(in_array($row['PRODUCT_ID'], $exist))
                                        {
                                            echo "disabled";
                                        }
                                        ?>
                                        value="<?php echo $row['PRODUCT_ID']; ?>">
                                        <?php
                                        if(in_array($row['PRODUCT_ID'], $exist))
                                        {
                                            echo "Item already in cart.";
                                        }
                                        else
                                        {
                                            echo "Add to cart";
                                        }
                                        ?>
                                        </button>
                                    </div>
                                </div>
                            </form>
                            <?php
                        }
                        else
                        {
                            ?>
                            <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
                                <div class="text-center"><a class="btn btn-outline-dark mt-auto" href="login.php">Add to cart</a></div>
                            </div>
                            <?php
                        }
                        ?>
                        
                    </div>
                </div>  
                <?php
            } ?>
        </div>
    </div>
</section>
</div>

</body>
</html>

<script>
    setTimeout(() => {
    const box = document.getElementById('success');
    box.style.display = 'none';
    }, 3000); 
</script>

<?php
    include 'footer.php';
?>