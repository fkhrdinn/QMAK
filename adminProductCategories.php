<?php
    include 'dbconnect.php';
    include 'adminSidebar.php';

    $query = "SELECT * FROM PRODUCT_CATEGORIES";
    $result = oci_parse($dbconn, $query);
    oci_execute($result);
    $number = 1;

    if(isset($_POST['edit-category']))
    {
        $categoryName = $_POST['categoryName'];
        $categoryID = $_POST['categoryID'];
        $query = "UPDATE PRODUCT_CATEGORIES SET CATEGORY_NAME = '$categoryName' WHERE CATEGORY_ID = '$categoryID'";
        $result = oci_parse($dbconn, $query);
        $status = oci_execute($result);

        if($status)
        {
            header("Location: adminProductCategories.php?message=Data%20updated%20succesfully.");
        }
        else
        {
            header("Location: adminProductCategories.php?message=Data%20failed%20to%20be%20updated.");
        }
    }

    if(isset($_POST['delete-category']))
    {
        $categoryID = $_POST['categoryID'];
        $query = "DELETE FROM PRODUCT_CATEGORIES WHERE CATEGORY_ID = '$categoryID'";
        $result = oci_parse($dbconn, $query);
        $status = oci_execute($result);

        if($status)
        {
            header("Location: adminProductCategories.php?message=Data%20deleted%20succesfully.");
        }
        else
        {
            header("Location: adminProductCategories.php?message=Data%20failed%20to%20be%20deleted.");
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Product Categories</title>
</head>
<body>
<div class="col">
        <nav class="navbar navbar-expand-lg navbar-light bg-light shadow py-4 rounded">
            <div class="px-4">
                <strong class="h5">Administrator</strong>
            </div>
        </nav>
        <div class="card mx-auto p-4 bg-light shadow border border-light">
            Product Categories
        </div>

        <div class="mt-5 card mx-auto p-4 bg-light shadow border border-light">
            <div class="col-md-2">
                <a href="addProductCategories.php">
                    <button class="btn btn-primary">
                        Add Product Categories
                    </button>
                </a>
            </div>
            
            <div id="success">
                <p class="text-center text-success"><?php if(isset($_GET['message'])) { echo $_GET['message']; }?></p>
            </div>
            
            <div class="table-responsive">
                <table class="mt-3 table caption-top table-striped table-hover">
                    <caption>List of product categories</caption>
                    <thead>
                        <tr>
                        <th scope="col">#</th>
                        <th scope="col">Category Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = oci_fetch_array($result))
                        {
                            ?>
                            <tr>
                            <th scope='row'> <?php echo $number; ?> </th>
                            <td> <?php echo $row['CATEGORY_NAME']; ?> </td>
                            <td><button type='button' class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#edit<?php echo $row["CATEGORY_ID"]; ?>'>Edit</button></td>
                            <td><button type='button' class='btn btn-danger' data-bs-toggle='modal' data-bs-target='#delete<?php echo $row["CATEGORY_ID"]; ?>'>Delete</button></td>
                            </tr>
                            <?php
                                $number++;
                            ?>
                            <!-- Edit Modal -->
                            <div class="modal fade" id="edit<?php echo $row['CATEGORY_ID'];?>" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Edit Category</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="" method="POST">
                                        <div class="modal-body">
                                                <div class="form-group">
                                                    <input id="form_categories" type="text" name="categoryID" class="form-control d-none" value="<?php echo $row['CATEGORY_ID']; ?>" 
                                                    required="required" data-error="category id is required.">
                                                    <label for="form_categories">Category Name <span class="text-danger">*</span></label>
                                                    <input id="form_categories" type="text" name="categoryName" class="form-control" value="<?php echo $row['CATEGORY_NAME']; ?>"
                                                    placeholder="Enter category name" required="required" data-error="category is required.">
                                                </div>
                                            
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary" name="edit-category">Save changes</button>
                                        </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                             <!-- Delete Modal -->
                             <div class="modal fade" id="delete<?php echo $row['CATEGORY_ID'];?>" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deleteModalLabel">Are you sure you want to delete category?</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="" method="POST">
                                        <div class="modal-body">
                                                <div class="form-group">
                                                    <input id="form_categories" type="text" name="categoryID" class="form-control d-none" value="<?php echo $row['CATEGORY_ID']; ?>" 
                                                    required="required" data-error="category id is required.">
                                                    <label for="form_categories">Category Name <span class="text-danger">*</span></label>
                                                    <input id="form_categories" type="text" name="categoryName" class="form-control" value="<?php echo $row['CATEGORY_NAME']; ?>"
                                                    disabled placeholder="Enter category name" required="required" data-error="category is required.">
                                                </div>
                                            
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                                            <button type="submit" class="btn btn-primary" name="delete-category">Yes</button>
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