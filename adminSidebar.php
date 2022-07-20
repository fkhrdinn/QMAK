<?php
    include 'dbconnect.php';
    session_start();

    if(empty($_SESSION['EMAIL']))
    {
        header("Location:adminLogin.php");
    }

    $query = "SELECT STAFF_ID, EMAIL FROM STAFFS WHERE EMAIL = '$_SESSION[EMAIL]'";
    $result = oci_parse($dbconn, $query);
    oci_execute($result);
    $row = oci_fetch_array($result);

    $email = $row['EMAIL'];
    $id = $row['STAFF_ID'];

    $picQuery = "SELECT MEDIA_NAME FROM MEDIA WHERE STAFF_ID = '$id'";
    $picResult = oci_parse($dbconn, $picQuery);
    oci_execute($picResult);
    $pic = oci_fetch_array($picResult);
    $media = $pic['MEDIA_NAME'];
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.3/font/bootstrap-icons.css">
<style>
	body {
	font-family: 'Nunito', sans-serif;
	}
</style>

<div class="container-fluid">
    <div class="row flex-nowrap">
        <div class="col-auto col-md-2 px-sm-2 px-0 bg-dark">
            <div class="d-flex flex-column align-items-center align-items-sm-start px-3 pt-2 text-white min-vh-100">
                <a href="/" class="d-flex align-items-center pb-3 mb-md-0 me-md-auto text-white text-decoration-none">
                    <img src="Media/QMAK.jpeg" alt="." style="width:70px; height:70px;">
                </a>
                <ul class="nav nav-pills flex-column mb-sm-auto mb-0 align-items-center align-items-sm-start" id="menu">
                    <li>
                        <a href="adminDashboard.php" class="nav-link px-0 align-middle text-white">
                            <i class="fs-4 bi-speedometer2"></i> <span class="ms-1 d-none d-sm-inline">Dashboard</span> </a>
                    </li>
                    <li>
                        <a href="adminInvoices.php?table=PENDING" class="nav-link px-0 align-middle text-white">
                        <i class="fs-4 bi bi-receipt"></i> <span class="ms-1 d-none d-sm-inline">Invoices</span></a>
                    </li>
                    <li>
                        <a href="adminProducts.php" class="nav-link px-0 align-middle text-white">
                        <i class="fs-4 bi bi-box-seam"></i> <span class="ms-1 d-none d-sm-inline">Products</span> </a>
                    </li>
                    <li>
                        <a href="adminProductCategories.php" class="nav-link px-0 align-middle text-white">
                        <i class="fs-4 bi bi-grid"></i> <span class="ms-1 d-none d-sm-inline">Product Categories</span> </a>
                    </li>
                </ul>
                <hr>
                <div class="dropdown pb-4">
                    <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="Media/ProfilePic/<?php echo $media; ?>" alt="hugenerd" width="30" height="30" class="rounded-circle">
                        <span class="d-none d-sm-inline mx-1"><?php echo $email ?></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark text-small shadow">
                        <li><a class="dropdown-item" href="adminSetting.php">Settings</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="logout.php">Sign out</a></li>
                    </ul>
                </div>
            </div>
        </div>