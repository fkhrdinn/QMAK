<?php
    include 'dbconnect.php';
    include 'adminSidebar.php';

    if(isset($_POST['products-form']))
    {
        $allowedFiles =  array('png', 'jpg', 'jpeg'); 
        $filename = $_FILES['file']['name'];
        $tempname = $_FILES["file"]["tmp_name"];
        $folder = "./Media/ProductPhotos/" . $filename;
        $ext = pathinfo($filename, PATHINFO_EXTENSION);

        if (in_array($ext, $allowedFiles) && ($_FILES["file"]["size"] < 15728640)) 
        {           
            move_uploaded_file($tempname, $folder);
            $photoDate = new DateTime();
            $photoToday = $photoDate->format('y-M-d h:i:s');
            $query = "INSERT INTO MEDIA (MEDIA_NAME, CREATED_AT) VALUES ('$filename', '$photoToday')";
            $result = oci_parse($dbconn, $query);
            $status = oci_execute($result);

            if($status)
            {
                $productName = $_POST['productName'];
                $productDescription = $_POST['productDescription'];
                $productPrice = floatval($_POST['productPrice']);
                $productStock = $_POST['productStock'];
                $categoryID = $_POST['categoryID'];
                $date = new DateTime();
                $today = $date->format('y-M-d h:i:s');

                $productQuery = "INSERT INTO PRODUCTS (CATEGORY_ID, PRODUCT_NAME, PRODUCT_DESCRIPTION, PRODUCT_PRICE, STOCK, CREATED_AT)
                VALUES ('$categoryID', '$productName', '$productDescription', '$productPrice', '$productStock', '$today')";
                $productResult = oci_parse($dbconn, $productQuery);
                $productStatus = oci_execute($productResult);
                
                if($productStatus)
                {
                    $selectSQL = "SELECT * FROM PRODUCTS WHERE CREATED_AT = '$today'";
                    $selectResult = oci_parse($dbconn, $selectSQL);
                    oci_execute($selectResult);
                    $productID = null;
                    while($selectRow = oci_fetch_array($selectResult))
                    {
                        $productID = $selectRow['PRODUCT_ID'];
                    }

                    $selectSQL = "SELECT * FROM MEDIA WHERE CREATED_AT = '$photoToday'";
                    $selectResult = oci_parse($dbconn, $selectSQL);
                    oci_execute($selectResult);
                    $mediaID = null;
                    while($selectRow = oci_fetch_array($selectResult))
                    {
                        $mediaID = $selectRow['MEDIA_ID'];
                    }

                    $finalQuery = "UPDATE MEDIA SET PRODUCT_ID = '$productID' WHERE MEDIA_ID = '$mediaID'";
                    $finalResult = oci_parse($dbconn, $finalQuery);
                    $finalStatus = oci_execute($finalResult);

                    if($finalStatus)
                    {
                        header("Location: adminProducts.php?message=Product%20succesfully%20added.");
                    }
                    else
                    {
                        header("Location: adminProducts.php?message=Products%20failed%20to%20be%20added.");
                    }
                    
                }
                else
                {
                    header("Location: adminProducts.php?message=Product%20failed%20to%20be%20added.");
                }
            }
            else
            {
                header("Location: adminProducts.php?message=Photo%20failed%20to%20be%20added.");
            }
            
        } 
        else 
        {
            echo "Invalid file";
        }
    }

    $categoryQuery = "SELECT * FROM PRODUCT_CATEGORIES";
    $categoryResult = oci_parse($dbconn, $categoryQuery);
    oci_execute($categoryResult);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Category</title>
</head>
<body>
<div class="col">
        <nav class="navbar navbar-expand-lg navbar-light bg-light shadow py-4 rounded">
            <div class="px-4">
                <strong class="h5">Administrator</strong>
            </div>
        </nav>
        <div class="card mx-auto p-4 bg-light shadow border border-light">
            Add Products
        </div>

        <div class="mt-5 card mx-auto p-4 bg-light shadow border border-light">
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="form_photo">Product Photo <span class="text-danger">*</span></label>
                    <input id="form_photo" type="file" name="file" class="form-control" required="required" data-error="product photo is required.">
                </div>
                <div class="form-group">
                    <label for="form_name">Product Name <span class="text-danger">*</span></label>
                    <input id="form_name" type="text" name="productName" class="form-control" placeholder="Enter product name" required="required" data-error="product name is required.">
                </div>
                <div class="form-group">
                    <label for="form_description">Product Description <span class="text-danger">*</span></label>
                    <textarea id="form_description" name="productDescription" class="form-control" style="resize:none;" placeholder="Write your product description here" rows="4" required="required" data-error="product description is required."></textarea>
                </div>
                <div class="form-group">
                    <label for="form_price">Product Price <span class="text-danger">*</span></label>
                    <input id="form_price" type="text" name="productPrice" class="form-control" placeholder="Enter product price" required="required" data-error="product price is required.">
                </div>
                <div class="form-group">
                    <label for="form_stock">Stock Available <span class="text-danger">*</span></label>
                    <input id="form_stock" type="number" name="productStock" class="form-control" placeholder="Enter stock" required="required" data-error="stock is required.">
                </div>
                <div class="form-group">
                    <label>Category <span class="text-danger">*</span></label>
                    <select name="categoryID" class="form-select" aria-label="Default select example">
                        <option value="NULL" selected>--Click here to choose--</option>
                        <?php 
                        while($row = oci_fetch_array($categoryResult))
                        {
                            ?> <option value="<?php echo $row['CATEGORY_ID']; ?>"> <?php echo $row['CATEGORY_NAME']; ?> </option> <?php
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group mt-4">
                    <input type="submit" class="btn btn-success btn-send pt-2 btn-block" value="Add" name="products-form">
                </div>
            </form>
        </div>
    </div>
</body>
</html>

</div>
</div>