<?php
    include 'dbconnect.php';
    include 'adminSidebar.php';

    $email = $_SESSION['EMAIL'];
    $customerQuery = "SELECT * FROM STAFFS WHERE EMAIL = '$email'";
    $customerResult = oci_parse($dbconn, $customerQuery);
    oci_execute($customerResult);

    $data = oci_fetch_array($customerResult);
    $id = $data['STAFF_ID'];

    $picQuery = "SELECT MEDIA_ID, MEDIA_NAME FROM MEDIA WHERE STAFF_ID = '$id'";
    $picResult = oci_parse($dbconn, $picQuery);
    oci_execute($picResult);
    $pic = oci_fetch_array($picResult);
    $media = $pic['MEDIA_NAME'];
    $mediaID = $pic['MEDIA_ID'];

    if(isset($_POST['password-form']))
    {
        $email = $_SESSION['EMAIL'];
        $staffQuery = "SELECT * FROM STAFFS WHERE EMAIL = '$email'";
        $staffResult = oci_parse($dbconn, $staffQuery);
        oci_execute($staffResult);

        $currentPass = $_POST['currentPassword'];
        $newPass = $_POST['newPassword'];
        $retypePass = $_POST['retypePassword'];

        while($row = oci_fetch_array($staffResult))
        {
            if($currentPass == $row['PASSWORD'])
            {
                $staffID = $row['STAFF_ID'];
                if($newPass == $retypePass)
                {
                    $query = "UPDATE STAFFS SET PASSWORD = '$newPass' WHERE STAFF_ID = '$staffID'";
                    $queryResult = oci_parse($dbconn, $query);
                    oci_execute($queryResult);
                    header("Location: adminSetting.php?message=Password%20succesfully%20updated.");
                }
                else
                {
                    header("Location: adminSetting.php?PassError=Re-enter%20password%20is%20not%20match.");
                }
            }
            else
            {
                header("Location: adminSetting.php?PassError=Current%20password%20is%20not%20match.");
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
                $mediaQuery = "UPDATE MEDIA SET MEDIA_NAME = '$filename' WHERE STAFF_ID = '$id' AND MEDIA_ID = '$mediaID'";
                $mediaResult = oci_parse($dbconn, $mediaQuery);
                $mediaStatus = oci_execute($mediaResult);
                
                if($mediaStatus)
                {
                    header("Location: adminSetting.php?profile=Profile%20picture%20updated.");
                }
                else
                {
                    header("Location: adminSetting.php?error=Error%20occured.");
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
    <title>Admin Setting</title>
</head>
<body>
    <div class="col">
        <nav class="navbar navbar-expand-lg navbar-light bg-light shadow py-4 rounded">
            <div class="px-4">
                <strong class="h5">Administrator</strong>
            </div>
        </nav>
        <div class="card mx-auto p-4 bg-light shadow border border-light">
            Settings
        </div>

        <div class="mt-5 card mx-auto p-4 bg-light shadow border border-light">
            <div class="h4">Change Password</div>
            <div id="success">
                <p id="success" class="text-center <?php if(isset($_GET['error'])) { echo "text-danger"; } 
                    else { echo "text-success"; } ?> h5">
                    <?php if(isset($_GET['profile'])) { echo $_GET['profile']; } 
                    if(isset($_GET['error'])) { echo $_GET['error']; } ?>
                </p>
            </div>
            <form action="" method="POST" enctype="multipart/form-data">
                <label>Current Profile Picture</label>
                <div class="form-group text-center">
                    <img style="width:200px;height:170px" class="img-thumbnail" src="Media/ProfilePic/<?php echo $media; ?>" alt="PFP">
                </div>
                <div class="form-group">
                    <label for="form_profile">Profile Picture <span class="text-danger">*</span></label>
                    <input id="form_profile" type="file" name="profile" class="form-control" required="required">
                </div>
                <div class="form-group mt-4">
                    <input type="submit" class="btn btn-success btn-send pt-2 btn-block" value="Change Profile Picture" name="profile-form">
                </div>
            </form>
        </div>

        <div class="mt-5 mb-5 card mx-auto p-4 bg-light shadow border border-light">
            <div class="h4">Change Password</div>
            <div id="success">
                <p id="success" class="text-center <?php if(isset($_GET['PassError'])) { echo "text-danger"; } 
                    else { echo "text-success"; } ?> h5">
                    <?php if(isset($_GET['message'])) { echo $_GET['message']; } 
                    if(isset($_GET['PassError'])) { echo $_GET['PassError']; } ?>
                </p>
            </div>
            <form action="" method="POST">
                <div class="form-group">
                    <label for="form_currentPass">Current Password <span class="text-danger">*</span></label>
                    <input id="form_currentPass" type="password" placeholder="Enter your current password" name="currentPassword" class="form-control" required="required" data-error="Password is required.">
                </div>
                <div class="form-group">
                    <label for="form_newPass">New Password <span class="text-danger">*</span></label>
                    <input id="form_newPass" type="password" name="newPassword" class="form-control" placeholder="Enter your new password" required="required" data-error="New password is required.">
                </div>
                <div class="form-group">
                    <label for="form_retypePass">Re-enter New Password <span class="text-danger">*</span></label>
                    <input id="form_retypePass" type="password" name="retypePassword" class="form-control" placeholder="Re-enter your new password" required="required" data-error="Re-enter new password is required.">
                </div>
                <div class="form-group mt-4">
                    <input type="submit" class="btn btn-success btn-send pt-2 btn-block" value="Change Password" name="password-form">
                </div>
            </form>
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