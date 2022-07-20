<?php
    include 'dbconnect.php';
    include 'adminSidebar.php';

    $query = "SELECT * FROM MEDIA INNER JOIN PRODUCTS USING (PRODUCT_ID) INNER JOIN PRODUCT_CATEGORIES USING (CATEGORY_ID)";
    $result = oci_parse($dbconn, $query);
    oci_execute($result);
    $number = 1;

    $selectSQL = "SELECT * FROM PRODUCT_CATEGORIES";
    $selectResult = oci_parse($dbconn, $selectSQL);
    oci_execute($selectResult);

    if(isset($_POST['edit-product']))
    {
        if(isset($_FILES['file']))
        {
            $allowedFiles =  array('png', 'jpg', 'jpeg'); 
            $filename = $_FILES['file']['name'];
            $tempname = $_FILES["file"]["tmp_name"];
            $folder = "./Media/ProductPhotos/" . $filename;
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            $mediaID = $_POST['mediaID'];
            
            if (in_array($ext, $allowedFiles) && ($_FILES["file"]["size"] < 15728640)) 
            {           
                move_uploaded_file($tempname, $folder);
                $mediaQuery = "UPDATE MEDIA SET MEDIA_NAME = '$filename' WHERE MEDIA_ID = '$mediaID'";
                $mediaResult = oci_parse($dbconn, $mediaQuery);
                $mediaStatus = oci_execute($mediaResult);        
            } 
        }

        $productID = $_POST['productID'];
        $productName = $_POST['productName'];
        $productDescription = $_POST['productDescription'];
        $productPrice = floatval($_POST['productPrice']);
        $productStock = $_POST['productStock'];
        $categoryID = $_POST['categoryID'];

        $updateQuery = "UPDATE PRODUCTS SET CATEGORY_ID = '$categoryID', PRODUCT_NAME = '$productName', PRODUCT_DESCRIPTION = '$productDescription', 
        PRODUCT_PRICE = '$productPrice', STOCK = '$productStock' WHERE PRODUCT_ID = '$productID'";
        $updateResult = oci_parse($dbconn, $updateQuery);
        $updateStatus = oci_execute($updateResult);
        
        if($updateStatus)
        {
            header("Location: adminProducts.php?message=Data%20updated%20succesfully.");
        }
        else
        {
            header("Location: adminProducts.php?message=Data%20failed%20to%20be%20updated.");
        } 
    }

    if(isset($_POST['delete-product']))
    {
        $mediaID = $_POST['mediaID'];
        $mediaQuery = "DELETE FROM MEDIA WHERE MEDIA_ID = '$mediaID'";
        $mediaResult = oci_parse($dbconn, $mediaQuery);
        $mediaStatus = oci_execute($mediaResult);

        $productID = $_POST['productID'];
        $query = "DELETE FROM PRODUCTS WHERE PRODUCT_ID = '$productID'";
        $result = oci_parse($dbconn, $query);
        $status = oci_execute($result);

        if($status && $mediaStatus)
        {
            header("Location: adminProducts.php?message=Data%20deleted%20succesfully.");
        }
        else
        {
            header("Location: adminProducts.php?message=Data%20failed%20to%20be%20deleted.");
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Products</title>
</head>
<body>
    <div class="col">
        <nav class="navbar navbar-expand-lg navbar-light bg-light shadow py-4 rounded">
            <div class="px-4">
                <strong class="h5">Administrator</strong>
            </div>
        </nav>
        <div class="card mx-auto p-4 bg-light shadow border border-light">
            Products
        </div>

        <div class="mt-5 card mx-auto p-4 bg-light shadow border border-light">
            <div class="col-md-2">
                <a href="addProducts.php">
                    <button class="btn btn-primary">
                        Add Products
                    </button>
                </a>
            </div>
            
            <div id="success">
                <p class="text-center text-success"><?php if(isset($_GET['message'])) { echo $_GET['message']; }?></p>
            </div>

            <div class="table-responsive">
                <table class="mt-3 table caption-top table-striped table-hover">
                    <caption>List of products</caption>
                    <thead>
                        <tr>
                        <th scope="col">#</th>
                        <th scope="col">Product Photo</th>
                        <th scope="col">Product Name</th>
                        <th scope="col">Product Description</th>
                        <th scope="col">Product Price</th>
                        <th scope="col">Stock Available</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = oci_fetch_array($result))
                        {
                            ?>
                            <tr>
                            <th scope="row"><?php echo $number; ?></th>
                            <td> <img style="width:150px;height:120px" class="card-img-top" src="Media/ProductPhotos/<?php echo $row['MEDIA_NAME']; ?>" alt="product"></td>
                            <td> <?php echo $row['PRODUCT_NAME']; ?></td>
                            <td> <?php echo $row['PRODUCT_DESCRIPTION']; ?></td>
                            <td> <?php echo 'RM ' . number_format($row['PRODUCT_PRICE'], 2); ?></td>
                            <td> <?php echo $row['STOCK']; ?></td>
                            <td><button type='button' class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#edit<?php echo $row["PRODUCT_ID"]; ?>'>Edit</button></a>
                            <td><button type='button' class='btn btn-danger' data-bs-toggle='modal' data-bs-target='#delete<?php echo $row["PRODUCT_ID"]; ?>'>Delete</button></a>
                            </tr>
                            <?php
                            $number++;
                            ?>
                            <!-- Edit Modal -->
                            <div class="modal fade" id="edit<?php echo $row['PRODUCT_ID'];?>" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Edit Product</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="" method="POST" enctype="multipart/form-data">
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <input id="form_media" type="text" name="mediaID" class="form-control d-none" value="<?php echo $row['MEDIA_ID']; ?>" 
                                                required="required" data-error="media id is required.">
                                            </div>
                                            <div class="form-group">
                                                <input id="form_product" type="text" name="productID" class="form-control d-none" value="<?php echo $row['PRODUCT_ID']; ?>" 
                                                required="required" data-error="product id is required.">
                                            </div>
                                            <div class="form-group">
                                                <label for="form_photo">Product Photo <span class="text-danger">*</span></label>
                                                <input id="form_photo" type="file" name="file" class="form-control" 
                                                data-error="product photo is required.">
                                            </div>
                                            <div class="form-group">
                                                <img style="width:150px;height:120px" src="Media/ProductPhotos/<?php echo $row['MEDIA_NAME']; ?>" alt="product">
                                            </div>
                                            <div class="form-group">
                                                <label for="form_name">Product Name <span class="text-danger">*</span></label>
                                                <input id="form_name" type="text" name="productName" class="form-control" 
                                                placeholder="Enter product name" required="required" data-error="product name is required." value="<?php echo $row['PRODUCT_NAME'] ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="form_description">Product Description <span class="text-danger">*</span></label>
                                                <textarea id="form_description" name="productDescription" class="form-control" 
                                                style="resize:none;" placeholder="Write your product description here" rows="4" required="required" 
                                                data-error="product description is required."><?php echo $row['PRODUCT_DESCRIPTION'] ?></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label for="form_price">Product Price <span class="text-danger">*</span></label>
                                                <input id="form_price" type="number" name="productPrice" class="form-control" 
                                                placeholder="Enter product price" required="required" data-error="product price is required." value="<?php echo $row['PRODUCT_PRICE'] ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="form_stock">Stock Available <span class="text-danger">*</span></label>
                                                <input id="form_stock" type="number" name="productStock" class="form-control" 
                                                placeholder="Enter stock" required="required" data-error="stock is required." value="<?php echo $row['STOCK'] ?>">
                                            </div>
                                            <div class="form-group">
                                                <label>Category <span class="text-danger">*</span></label>
                                                <select name="categoryID" class="form-select" aria-label="Default select example">
                                                    <option value="<?php echo $row['CATEGORY_ID'] ?>" selected><?php echo $row['CATEGORY_NAME'] ?></option>
                                                    <?php 
                                                    while($selectRow = oci_fetch_array($selectResult))
                                                    {
                                                        if($row['CATEGORY_ID'] != $selectRow['CATEGORY_ID'])
                                                        {
                                                            ?> <option value="<?php echo $selectRow['CATEGORY_ID']; ?>"> <?php echo $selectRow['CATEGORY_NAME']; ?> </option> <?php
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary" name="edit-product">Save changes</button>
                                        </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- Delete Modal -->
                            <div class="modal fade" id="delete<?php echo $row['PRODUCT_ID'];?>" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Are you sure you want to delete product?</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="" method="POST" enctype="multipart/form-data">
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <input id="form_product" type="text" name="mediaID" class="form-control d-none" value="<?php echo $row['MEDIA_ID']; ?>" 
                                                required="required" data-error="media id is required.">
                                            </div>
                                            <div class="form-group">
                                                <input id="form_product" type="text" name="productID" class="form-control d-none" value="<?php echo $row['PRODUCT_ID']; ?>" 
                                                required="required" data-error="product id is required.">
                                            </div>
                                            <div class="form-group">
                                                <label for="form_photo">Product Photo <span class="text-danger">*</span></label>
                                            </div>
                                            <div class="form-group">
                                                <img style="width:150px;height:120px" src="Media/ProductPhotos/<?php echo $row['MEDIA_NAME']; ?>" alt="product">
                                            </div>
                                            <div class="form-group">
                                                <label for="form_name">Product Name <span class="text-danger">*</span></label>
                                                <input id="form_name" type="text" name="productName" class="form-control" 
                                                disabled placeholder="Enter product name" required="required" data-error="product name is required." value="<?php echo $row['PRODUCT_NAME'] ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="form_description">Product Description <span class="text-danger">*</span></label>
                                                <textarea id="form_description" name="productDescription" class="form-control" 
                                                style="resize:none;" placeholder="Write your product description here" rows="4" required="required" 
                                                disabled data-error="product description is required."><?php echo $row['PRODUCT_DESCRIPTION'] ?></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label for="form_price">Product Price <span class="text-danger">*</span></label>
                                                <input id="form_price" type="number" name="productPrice" class="form-control" 
                                                disabled placeholder="Enter product price" required="required" data-error="product price is required." value="<?php echo $row['PRODUCT_PRICE'] ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="form_stock">Stock Available <span class="text-danger">*</span></label>
                                                <input id="form_stock" type="number" name="productStock" class="form-control" 
                                                disabled placeholder="Enter stock" required="required" data-error="stock is required." value="<?php echo $row['STOCK'] ?>">
                                            </div>
                                            <div class="form-group">
                                                <label>Category <span class="text-danger">*</span></label>
                                                <select name="categoryID" class="form-select" aria-label="Default select example" disabled>
                                                    <option value="<?php echo $row['CATEGORY_ID'] ?>" selected><?php echo $row['CATEGORY_NAME'] ?></option>
                                                    <?php 
                                                    while($selectRow = oci_fetch_array($selectResult))
                                                    {
                                                        if($row['CATEGORY_ID'] != $selectRow['CATEGORY_ID'])
                                                        {
                                                            ?> <option value="<?php echo $selectRow['CATEGORY_ID']; ?>"> <?php echo $selectRow['CATEGORY_NAME']; ?> </option> <?php
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                                            <button type="submit" class="btn btn-primary" name="delete-product">Yes</button>
                                        </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }?>
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