<?php
    include 'dbconnect.php';
    include 'adminSidebar.php';

    if(isset($_POST['categories-form']))
    {
        $categoryName = $_POST['categoryName'];
        $query = "INSERT INTO PRODUCT_CATEGORIES (CATEGORY_NAME) VALUES ('$categoryName')";
        $result = oci_parse($dbconn, $query);
        oci_execute($result);

        header("Location: adminProductCategories.php?message=Category%20succesfully%20added.");
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
            Add Product Categories
        </div>

        <div class="mt-5 card mx-auto p-4 bg-light shadow border border-light">
            <form action="" method="POST">
                <div class="form-group">
                    <label for="form_categories">Category Name <span class="text-danger">*</span></label>
                    <input id="form_categories" type="text" name="categoryName" class="form-control" placeholder="Enter category name" required="required" data-error="category is required.">
                </div>
                <div class="form-group mt-4">
                    <input type="submit" class="btn btn-success btn-send pt-2 btn-block" value="Add" name="categories-form">
                </div>
            </form>
        </div>
    </div>
</body>
</html>

</div>
</div>