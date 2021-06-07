<?php
ob_start();
session_start(); 
include '../classes/autoload.php' ;
    $userObj = new User();  
$fnameError = $emailError =  $passError = '';
// get id from $_GET
if ($_GET['userId']) {
    $id = $_GET['userId'];
    $getData = $userObj->getUserById($id);
    if ($getData) {// id exist
        $user = $getData->fetch_assoc();
    } else {  // id doesn't exist
        header("location: user_list.php");
    }        
} else { // get other than id 
    header("location: ../home.php");
}
// session redirection
if (!isset($_SESSION['adm'])) {
    if (isset($_SESSION['user'])) { // user can onlyedit own profile
        if ($id != $_SESSION['user']['id']) {
            header("Location: ../home.php");
            exit;    
        }
    }
}
// get registration data from $_POST
if (isset($_POST['submit'])) {
	$f_name = $_POST['f_name'];
	$l_name = $_POST['l_name'];
	$address = $_POST['address'];
	// updateUser function
      $updateUser = $userObj->updateAddress($id, $f_name, $l_name, $address);
      echo "<script>
alert('User ID".$id." updated!');
window.location.href='address_edit.php?userId=".$id."';
</script>";
          // if updateUser error -> receives error msgs
      if (is_array($updateUser)) {
        $fnameError = $updateUser['fnameError'];
         }
    }
    if (isset($_POST['continue'])) {
        header("Location: ../offline_payment.php");
    }
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap 5 CSS bundle  -->
    <?php include_once '../components/boot.php';?>
	<link rel="stylesheet" href="../components/style.css">
    <title>Address Update - Paint of Heart</title>	
</head>
<body>
    <div class="grid_10">
    <div class="box round first grid">
    <?php 
        if (isset($updateUser) && !is_array($updateUser)) {
            echo $updateUser;
        }
    ?>
        <h2>Update Address</h2>
        <form name="user_edit" method="post" action="">
        <table class="form"> 
            <tr>
                <td>
                    <label>First Name</label>
                </td>
                <td>
                <input type="text" name="f_name" class="form-control" placeholder="first name" required="" value="<?php echo $user['f_name'] ?>">
                <span class="text-danger"> <?php echo $fnameError; ?> </span>
                </td>
            </tr>
            <tr>
                <td>
                    <label>Last Name</label>
                </td>
                <td>
                <input type="text" name="l_name" class="form-control" placeholder="last name" required="" value="<?php echo $user['l_name'] ?>">
                <span class="text-danger"> <?php echo $fnameError; ?> </span>
                </td>
            </tr>
            <tr>
                <td>
                    <label>Adress</label>
                </td>
                <td>
                <input type="text" name="address" class="form-control" placeholder="address" required="" value="<?php echo $user['address'] ?>">
                </td>
            </tr>
            
                <td></td>
                    <td>
                    <input type="submit" name="submit" class="btn btn-sm save-btn" value="Save">|| 
                    <input type="submit" name="continue" class="btn btn-sm home-btn" value="Continue"> 
                    </td>
                </tr>
        </table>        
        </form>  
    </div>
</div>
<?php include '../components/footer.php';?>
<!-- Load TinyMCE -->
<script src="js/tiny-mce/jquery.tinymce.js" type="text/javascript"></script>
<script type="text/javascript">
    $(document).ready(function () {
        setupTinyMCE();
        setDatePicker('date-picker');
        $('input[type="checkbox"]').fancybutton();
        $('input[type="radio"]').fancybutton();
    });
</script>
<?php include_once 'components/bootjs.php'; ?>
</body>
</html>