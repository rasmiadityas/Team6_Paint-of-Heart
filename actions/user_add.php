<?php
ob_start();
session_start(); 
include '../classes/autoload.php' ;

// session redirection
if (!isset($_SESSION['adm'])) {
    header("Location: ../home.php");
    exit;
}
    $userObj = new User();
    $f_name = $l_name = $address = $email = '';
$fnameError = $emailError =  $passError = '';
// get registration data from $_POST
if (isset($_POST['submit'])) {
	$f_name = $_POST['f_name'];
	$l_name = $_POST['l_name'];
	$address = $_POST['address'];
	$email = $_POST['email'];
    $password = $_POST['password'];
    $user_level = $_POST['user_level'];
    // userInsert function
    $userInsert = $userObj->userInsert($f_name, $l_name, $address, $email,$password,$user_level);
    echo "<script>
    alert('User created!');
    window.location.href='user_list.php';
    </script>";
    // if userInsert error -> receives error msgs
    if (is_array($userInsert)) {
        $fnameError = $userInsert['fnameError'];
        $emailError = $userInsert['emailError'];
        $passError = $userInsert['passError'];
    }
    }
?>
<div class="grid_10">
    <div class="box round first grid">
    <?php 
        if (isset($userInsert) && !is_array($userInsert)) {
            echo $userInsert;
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
    <title>User Add - Paint of Heart</title>
</head>
<body>
    <div class="grid_10">
    <div class="box round first grid">                     
        <h2>Add New User</h2>
        <div class="block copyblock"> 
            <form name="user_add" method="post" action="">
            <table class="form">
                <tr>
                    <td>	  
                    <input type="text" name="f_name" class="form-control" placeholder="First Name" required="" value="<?php echo $f_name ?>">
                    <span class="text-danger"> <?php echo $fnameError; ?> </span>
                    </td>
                </tr>
                <tr>
                    <td>
                    <input type="text" name="l_name" class="form-control" placeholder="Last Name" required="" value="<?php echo $l_name ?>">
                    <span class="text-danger"> <?php echo $fnameError; ?> </span>
                    </td>
                </tr>
                <tr>
                    <td>	
                    <input type="text" name="address" class="form-control" placeholder="Address" required="" value="<?php echo $address ?>">
                    </td>
                </tr>
                <tr>
                    <td>	
                    <input type="text" name="email" class="form-control" placeholder="Email" required="" value="<?php echo $email ?>">
                    <span class="text-danger"> <?php echo $emailError; ?> </span>
                    </td>
                </tr>
                <tr>
                    <td>
                    <input type="password" name="password" class="form-control" placeholder="Password" required="">
                    <span class="text-danger"> <?php echo $passError; ?> </span>
                    </td>
                </tr>
                <tr>
                    <td>
                    <select id="select" name="user_level" style="width:100%">
                            <option value=0>User</option>
                            <option value=1>Admin</option>
                            </select>
                    </td>
                </tr>
                <tr> 
                    <td>
                    <input type="submit" name="submit" class="btn btn-sm save-btn" value="Save"> || 
                    <a class="btn btn-sm home-btn" href="user_list.php">User List</a> || 
                    <a class="btn btn-sm dash-btn" href="../admin.php">Dashboard</a>
                    </td>
                </tr>
            </table>
            </form>  
        </div>
    </div>
    </div>
<footer>
        <?php include_once '../components/footer.php'; ?>
</footer>
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