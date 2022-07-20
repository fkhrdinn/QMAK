<?php
	include 'dbconnect.php';
    include 'header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact</title>
</head>
<style>
    .centered {
        position: absolute;
        top: 45%;
        left: 50%;
        transform: translate(-45%, -50%);
    }
</style>
<body>
    <div>
        <img src="Media/Contact Background.jpg" alt="ContactBackground" style="height:670px"
        class="mt-5 shadow mx-auto d-block img-fluid">
    </div>
    <p class="centered text-white display-3"><strong>CONTACT</strong></p>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-4">
                <h3>Our Address</h3>
                <p>JALAN RAJA BASIR, SEMENTA 42100 KLANG SELANGOR MALAYSIA</p>
            </div>
            <div class="col-md-4">
                <h3>Contact Us</h3>
                <p>QMAK@qmak.info.com</p>
                <p>016-2121400</p>
            </div>
            <div class="col-md-4">
                <h3>Working Hours</h3>
                <p>Monday - Sunday 08:00 to 20:00</p>
            </div>
        </div>
    </div>
</body>
</html>

<?php
    include 'footer.php';
?>