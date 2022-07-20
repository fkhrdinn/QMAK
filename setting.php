<?php
    include 'dbconnect.php';
    include 'header.php';

    $email = $_SESSION['EMAIL'];
    $customerQuery = "SELECT * FROM CUSTOMERS WHERE EMAIL = '$email'";
    $customerResult = oci_parse($dbconn, $customerQuery);
    oci_execute($customerResult);

    $data = oci_fetch_array($customerResult);
    $id = $data['CUSTOMER_ID'];

    $picQuery = "SELECT MEDIA_ID, MEDIA_NAME FROM MEDIA WHERE CUSTOMER_ID = '$id'";
    $picResult = oci_parse($dbconn, $picQuery);
    oci_execute($picResult);
    $pic = oci_fetch_array($picResult);
    $media = $pic['MEDIA_NAME'];
    $mediaID = $pic['MEDIA_ID'];

    if(isset($_POST['password-form']))
    {
        $email = $_SESSION['EMAIL'];
        $customerQuery = "SELECT * FROM CUSTOMERS WHERE EMAIL = '$email'";
        $customerResult = oci_parse($dbconn, $customerQuery);
        oci_execute($customerResult);

        $currentPass = $_POST['currentPass'];
        $newPass = $_POST['newPass'];
        $retypePass = $_POST['retypePass'];

        while($row = oci_fetch_array($customerResult))
        {
            if($currentPass == $row['PASSWORD'])
            {
                $customerID = $row['CUSTOMER_ID'];
                if($newPass == $retypePass)
                {
                    $query = "UPDATE CUSTOMERS SET PASSWORD = '$newPass' WHERE CUSTOMER_ID = '$customerID'";
                    $queryResult = oci_parse($dbconn, $query);
                    oci_execute($queryResult);
                    header("Location: Setting.php?message=Password%20succesfully%20updated.");
                }
                else
                {
                    header("Location: Setting.php?PassError=Re-enter%20password%20is%20not%20match.");
                }
            }
            else
            {
                header("Location: Setting.php?PassError=Current%20password%20is%20not%20match.");
            }
        }
    }

    if(isset($_POST['profile-form']))
    {
        if(isset($_FILES['profile']))
        {
            $allowedFiles =  array('png', 'jpg', 'jpeg'); 
            $filename = $_FILES['profile']['name'];
            $tempname = $_FILES["profile"]["tmp_name"];
            $folder = "./Media/ProfilePic/" . $filename;
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            
            if (in_array($ext, $allowedFiles) && ($_FILES["profile"]["size"] < 15728640)) 
            {           
                move_uploaded_file($tempname, $folder);
                $mediaQuery = "UPDATE MEDIA SET MEDIA_NAME = '$filename' WHERE CUSTOMER_ID = '$id' AND MEDIA_ID = '$mediaID'";
                $mediaResult = oci_parse($dbconn, $mediaQuery);
                $mediaStatus = oci_execute($mediaResult);
                
                if($mediaStatus)
                {
                    header("Location: Setting.php?profile=Profile%20picture%20updated.");
                }
                else
                {
                    header("Location: Setting.php?error=Error%20occured.");
                }
            } 
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Settings</title>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-lg-7 mx-auto">
        <div class="mt-5 card mt-2 mx-auto p-4 bg-light shadow border border-secondary">
            <div class="card-body bg-light">
            <div class = "container">
                <p class="h4 text-center mb-4">Profile Picture</p>
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="controls">
                        <div class="row">
                            <div class="col-md-12">
                                <p id="success" class="text-center <?php if(isset($_GET['error'])) { echo "text-danger"; } 
                                    else { echo "text-success"; } ?> h5">
                                    <?php if(isset($_GET['profile'])) { echo $_GET['profile']; } 
                                    if(isset($_GET['error'])) { echo $_GET['error']; } ?>
                                </p>
                            </div>
                            <div class="col-md-12 mb-2">
                                <label>Current Profile Picture</label>
                                <div class="form-group text-center">
                                    <img style="width:200px;height:170px" class="img-thumbnail" src="Media/ProfilePic/<?php echo $media; ?>" alt="PFP">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="form_profile">Profile Picture <span class="text-danger">*</span></label>
                                    <input id="form_profile" type="file" name="profile" class="form-control" required="required">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mt-3">
                                    <input type="submit" class="btn btn-success btn-send pt-2 btn-block" value="Change Profile Picture" name="profile-form" >
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

    <div class="row">
      	<div class="col-lg-7 mx-auto">
        <div class="mt-5 card mt-2 mx-auto p-4 bg-light shadow border border-secondary">
            <div class="card-body bg-light">
            <div class = "container">
                <p class="h4 text-center mb-4">Change Password</p>
                <form action="" method="POST">
            		<div class="controls">
                        <div class="row">
                            <div class="col-md-12">
                                <p id="success" class="text-center <?php if(isset($_GET['PassError'])) { echo "text-danger"; } 
                                    else { echo "text-success"; } ?> h5">
                                    <?php if(isset($_GET['message'])) { echo $_GET['message']; } 
                                    if(isset($_GET['PassError'])) { echo $_GET['PassError']; } ?>
                                </p>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="form_currentPass">Current Password <span class="text-danger">*</span></label>
                                    <input id="form_currentPass" type="password" name="currentPass" class="form-control" placeholder="Enter your current password" required="required" data-error="current password is required.">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="form_newPass">New Password <span class="text-danger">*</span></label>
                                    <input id="form_newPass" type="password" name="newPass" class="form-control" placeholder="Enter your new password *" required="required" data-error="new password is required.">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="form_retypePass">Re-enter New Password <span class="text-danger">*</span></label>
                                    <input id="form_retypePass" type="password" name="retypePass" class="form-control" placeholder="Re-enter your new password *" required="required" data-error="retype password is required.">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mt-3">
                                    <input type="submit" class="btn btn-success btn-send pt-2 btn-block" value="Change Password" name="password-form" >
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