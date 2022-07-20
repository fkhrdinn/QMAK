<?php
	include 'dbconnect.php';
	include 'header.php';

	if(isset($_POST['register-form']))
	{
		$firstName = $_POST['firstName'];
		$lastName = $_POST['surname'];
		$email = $_POST['email'];
		$password = $_POST['password'];
		$identification = $_POST['identificationCard'];
		$telephone = $_POST['telephone'];
		$address = $_POST['address'];

		$date = new DateTime();
		$today = $date->format('y-M-d h:i:s');
		
		$query = "INSERT INTO CUSTOMERS 
		(FIRST_NAME, LAST_NAME, EMAIL, PASSWORD, IDENTIFICATION, ADDRESS, PHONE_NUMBER, CREATED_AT) VALUES
		('$firstName', '$lastName', '$email', '$password', '$identification', '$address', '$telephone', '$today')";
		$result = oci_parse($dbconn, $query);
		oci_execute($result);

		$selectSQL = "SELECT * FROM CUSTOMERS WHERE CREATED_AT = '$today'";
		$selectResult = oci_parse($dbconn, $selectSQL);
		oci_execute($selectResult);
		$customerID = null;
		while($selectRow = oci_fetch_array($selectResult))
		{
			$customerID = $selectRow['CUSTOMER_ID'];
		}

		$mediaQuery = "INSERT INTO MEDIA (CUSTOMER_ID, MEDIA_NAME, CREATED_AT) VALUES ('$customerID', 'default.jpg', '$today')";
		$mediaResult = oci_parse($dbconn, $mediaQuery);
		oci_execute($mediaResult);

		if(isset($_POST['normalUser']))
		{
			$secondQuery = "INSERT INTO NORMAL_USERS (CUSTOMER_ID) VALUES ('$customerID')";
			$secondResult = oci_parse($dbconn, $secondQuery);
			oci_execute($secondResult);
			
			header("Location: login.php?message=You%20have%20succesfully%20registered!");
		}
		if(isset($_POST['commercialUser']))
		{
			$ssm = $_POST['ssm'];
			$companyName = $_POST['companyName'];
			$secondQuery = "INSERT INTO COMMERCIAL_USERS (CUSTOMER_ID, SSM, COMPANY_NAME) VALUES ('$customerID', '$ssm', '$companyName')";
			$secondResult = oci_parse($dbconn, $secondQuery);
			oci_execute($secondResult);
			
			header("Location: login.php?message=You%20have%20succesfully%20registered!"); 
		}
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Register</title>
</head>
<body>
<div class="container">
	<div class="text-center mt-5 ">
		<h1 >Register Form</h1>
	</div>
    <div class="row">
      	<div class="col-lg-7 mx-auto">
        <div class="card mt-2 mx-auto p-4 bg-light shadow border border-secondary">
            <div class="card-body bg-light">
            <div class = "container">
                <form id="register-form" role="form" action="" method="POST">
            		<div class="controls">
                	<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="form_name">Firstname <span class="text-danger">*</span></label>
								<input id="form_name" type="text" name="firstName" class="form-control" placeholder="Enter your firstname" required="required" data-error="Firstname is required.">
								
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="form_lastname">Lastname <span class="text-danger">*</span></label>
								<input id="form_lastname" type="text" name="surname" class="form-control" placeholder="Enter your lastname" required="required" data-error="Lastname is required.">
							</div>
						</div>
                	</div>
                	<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="form_email">Email <span class="text-danger">*</span></label>
								<input id="form_email" type="email" name="email" class="form-control" placeholder="Enter your email" required="required" data-error="Valid email is required.">
								
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="form_password">Password <span class="text-danger">*</span></label>
								<input id="form_password" type="password" name="password" class="form-control" placeholder="Enter your password" required="required" data-error="Valid password is required.">
								
							</div>
                    	</div>
                	</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="form_identification-card">Identification Card <span class="text-danger">*</span></label>
								<input id="form_identification-card" type="text" name="identificationCard" class="form-control" placeholder="Enter your identification card" required="required" data-error="Valid identification card is required.">
								
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="form_telephone">Telephone Number <span class="text-danger">*</span></label>
								<input id="form_telephone" type="text" name="telephone" class="form-control" placeholder="Enter your telephone number" required="required" data-error="Valid telephone is required.">
								
							</div>
                    	</div>
                	</div>
					<div class="row">
						<label for="switch">User Type <span class="text-danger">*</span></label>
						<div class="col-md-6">
							<div class="form-check form-switch">
								<input class="form-check-input" type="checkbox" id="normalUser" name="normalUser">
								<label class="form-check-label" for="normalUser">Normal User</label>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-check form-switch">
								<input class="form-check-input" type="checkbox" id="commercialUser" name="commercialUser">
								<label class="form-check-label" for="commercialUser">Commercial User</label>
							</div>
						</div>
					</div>
					<div class="row d-none" id="hideForm">
						<div class="col-md-6">
							<div class="form-group">
								<label for="form_ssm">SSM Number <span class="text-danger">*</span></label>
								<input id="form_ssm" type="text" name="ssm" class="form-control" placeholder="Enter your SSM Number" data-error="SSM Number is required.">
								
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="form_companyName">Company Name <span class="text-danger">*</span></label>
								<input id="form_companyName" type="text" name="companyName" class="form-control" placeholder="Enter your Company Name" data-error="Company Name is required.">
							</div>
						</div>
                	</div>
            		<div class="row">
                    	<div class="col-md-12">
                        	<div class="form-group">
                            	<label for="form_address">Address <span class="text-danger">*</span></label>
                            	<textarea id="form_address" name="address" class="form-control" style="resize:none;" placeholder="Write your address here" rows="4" required="required" data-error="Please, leave us a address."></textarea>
                            </div>
                        </div>
                   		<div class="col-md-12 mt-3">
                        	<input type="submit" class="btn btn-success btn-send pt-2 btn-block" value="Register" name="register-form">
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

<script>
	document.querySelectorAll('input[type=checkbox]').forEach(element => element.addEventListener('click', disableOther))
	function disableOther(event) {
		//"event" is current event(click)
		//"event.target" is our clicked element
		if (event.target.checked) {
			// if current input is checked -> disable ALL inputs
			document.querySelectorAll('input[type=checkbox]').forEach(element => element.disabled = true)
			// enabling our current input
			event.target.disabled = false;

			var check = document.getElementById('commercialUser');
			if(check.checked)
			{
				var element = document.getElementById("hideForm");
  				element.classList.remove("d-none");
				document.getElementById("form_ssm").setAttribute("required", "required");
				document.getElementById("form_companyNumber").setAttribute("required", "required");  
			}

		} else {
			// if current input is NOT checked -> enabling ALL inputs
			document.querySelectorAll('input[type=checkbox]').forEach(element => element.disabled = false)
			var element = document.getElementById("hideForm");
  			element.classList.add("d-none");
			document.getElementById("form_ssm").removeAttribute("required")
			document.getElementById("form_companyNumber").removeAttribute("required")
		}
	}
</script>

</body>
</html>

<?php
include 'footer.php';
?>