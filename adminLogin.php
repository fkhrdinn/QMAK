<?php
	include 'dbconnect.php';
	include 'adminHeader.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Log in</title>
</head>
<body>
<div class="container">
	<div class=" text-center mt-5 ">
		<h1 >Admin Login</h1>
	</div>
    <div class="row">
      	<div class="col-lg-7 mx-auto">
        <div class="card mt-2 mx-auto p-4 bg-light shadow border border-secondary">
            <div class="card-body bg-light">
            <div class = "container">
                <form id="login-form" role="form" action="adminLoginProcess.php" method="POST">
            		<div class="controls">
                        <div class="row">
                            <div class="col-md-12">
                                <p id="success" class="text-center h5 <?php if($_GET['error']) {echo 'text-danger';}
                                else { echo "text-success"; } ?>">
                                <?php if(isset($_GET['message'])) { echo $_GET['message']; } if(isset($_GET['error'])) { echo $_GET['error']; }?></p>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="form_email">Email <span class="text-danger">*</span></label>
                                    <input id="form_email" type="text" name="email" class="form-control" placeholder="Enter your email" required="required" data-error="Email is required.">
                                    
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="form_password">Password <span class="text-danger">*</span></label>
                                    <input id="form_password" type="password" name="password" class="form-control" placeholder="Enter your password *" required="required" data-error="Password is required.">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mt-3">
                                    <input type="submit" class="btn btn-success btn-send pt-2 btn-block" value="Log in" name="admin-login">
                                </div>
                            </div>
                        </div>
        			</div>
         		</form>
        	</div>
            </div>
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

<?php
include 'footer.php';
?>