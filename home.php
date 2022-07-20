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
    <title>Home</title>
</head>
<style>
    .centered {
        position: absolute;
        top: 20%;
        left: 50%;
        transform: translate(-45%, -50%);
    }
    .centeredButton {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-45%, -50%);
    }
    .centeredImage {
        position: absolute;
        top: 35%;
        left: 50%;
        transform: translate(-45%, -50%);
    }
</style>

<body>
    <?php
        if(isset($_GET['success']))
        {
            ?>
            <div class="container rounded shadow-lg">
                <p id="success" class="mt-3 card-text text-center h5 text-success">
                    <?php echo $_GET['success']; ?>
                </p> 
            </div>  
            <?php
        }
    ?>
    <div>
        <img src="Media/Home Back.jpg" alt="HomeBackground" style="width:1920px; height:850px" 
        class="mt-3 shadow mx-auto d-block img-fluid rounded">
    </div>
    <div class="d-none d-sm-block">
        <a href="home.php">
            <img src="Media/QMAK.jpeg" alt="QMAK" class="mx-auto d-block img-fluid rounded centeredImage" style="width:80px; height:80px">
        </a>
    </div>
    <div>
    <p class="centered text-black h1 d-none d-sm-block"><strong>SHOP AT OUR STORE NOW!</strong></p>
    <a href="shop.php">
        <button class="centeredButton btn btn-lg btn-dark">
            Shop Now
        </button>
    </a>
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