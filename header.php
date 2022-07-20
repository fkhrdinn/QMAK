<?php
	include 'dbconnect.php';
	session_start();

	function active($current_page){
		$url_array =  explode('/', $_SERVER['REQUEST_URI']) ;
		$url = end($url_array);  
		if($current_page == $url){
			echo 'active';
		} 
	}

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

<div>
	<nav class="navbar navbar-expand-lg navbar-light bg-light shadow">
	<div class="container-fluid">
		<a class="navbar-brand" href="home.php"><img src="Media/QMAK.jpeg" alt="QMAK" style="width:60px; height:60px;"></a>
		<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="navbarNavAltMarkup">
			<div class="navbar-nav">
				<a class="nav-link <?php active('home.php');?>" href="home.php">Home</a>
				<a class="nav-link <?php active('shop.php');?>" href="shop.php">Shop</a>
				<a class="nav-link <?php active('contact.php');?>" href="contact.php">Contact</a>
				<a class="nav-link <?php active('about.php');?>" href="about.php">About Us</a>
				<?php if(!empty($_SESSION['EMAIL'])) 
				{ 
					?> <a class="nav-link <?php active('cart.php');?>" href="cart.php">Cart</a> <?php
				} 
				?>
			</div>
		</div>
		<?php if(!empty($_SESSION['EMAIL']))
		{
			$query = "SELECT * FROM CUSTOMERS WHERE EMAIL = '$_SESSION[EMAIL]'";
			$result = oci_parse($dbconn, $query);
			oci_execute($result);
			$row = oci_fetch_array($result);
			$email = $row['EMAIL'];
			$id = $row['CUSTOMER_ID'];

			$picQuery = "SELECT MEDIA_NAME FROM MEDIA WHERE CUSTOMER_ID = '$id'";
			$picResult = oci_parse($dbconn, $picQuery);
			oci_execute($picResult);
			$pic = oci_fetch_array($picResult);
			$media = $pic['MEDIA_NAME'];

			$inherit = "SELECT * FROM CUSTOMERS JOIN NORMAL_USERS USING (CUSTOMER_ID) WHERE EMAIL = '$_SESSION[EMAIL]'";
			$inheritResult = oci_parse($dbconn, $inherit);
			oci_execute($inheritResult);

			$type = null;
			if($status = oci_fetch_array($inheritResult))
			{
				$type = "Normal User";
			}
			else
			{
				$type = "Commercial Users";
			}

			?>
			<div class="collapse navbar-collapse justify-content-end" id="navbarNavAltMarkup">
				<div class="navbar-nav">
					<hr>
					<div class="dropdown pb-4">
                    <a href="#" class="d-flex align-items-center text-black text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="Media/ProfilePic/<?php echo $media; ?>" alt="hugenerd" width="30" height="30" class="rounded-circle">
                        <span class="d-none d-sm-inline mx-1"><?php echo $email ?></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-light text-small shadow">
						<li><a class="dropdown-item" href="order.php">Order History</a></li>
                        <li><a class="dropdown-item" href="setting.php">Settings</a></li>
						<hr class="dropdown-divider">
						<li class="disabled dropdown-item text-secondary">
							Type of User: 
						</li>
						<li class="disabled dropdown-item">
							<?php echo ' ' . $type; ?>
						</li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="logout.php">Sign out</a></li>
                    </ul>
                </div>
				</div>
			</div>
			<?php
		}
		else
		{
			?>
			<div class="collapse navbar-collapse justify-content-end" id="navbarNavAltMarkup">
				<div class="navbar-nav">
					<hr>
					<a class="nav-link text-primary" href="login.php">Log in</a>
					<a class="nav-link text-primary" href="register.php">Register</a>
				</div>
			</div>
			<?php
		}
		?>
		
	</div>
	</nav>
</div>