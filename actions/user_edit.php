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
	$email = $_POST['email'];
	$password = $_POST['password'];
    $user_level = $_POST['user_level'];
    $user_state = $_POST['user_state'];
    // updateUser function
      $updateUser = $userObj->updateUser($id, $f_name, $l_name, $address, $email, $password,$user_level,$user_state);
      echo "<script>
alert('User ID".$id." updated!');
window.location.href='user_edit.php?userId=".$id."';
</script>";
          // if updateUser error -> receives error msgs
      if (is_array($updateUser)) {
        $fnameError = $updateUser['fnameError'];
        $emailError = $updateUser['emailError'];
        $passError = $updateUser['passError'];
      }
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
    <title>User Edit - Paint of Heart</title>
</head>
<body>
    <div class="grid_10">
    <div class="box round first grid">
    <?php 
        if (isset($updateUser) && !is_array($updateUser)) {
            echo $updateUser;
        }
    ?>
        <h2>Edit User ID: <?php echo $user['id']; ?></h2>
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
            <tr>
                <td>
                    <label>Email</label>
                </td>
                <td>
                <input type="text" name="email" class="form-control" placeholder="Change email (<?php 
                if (isset($updateUser) && !is_array($updateUser) && strlen($email)>0) {
                            echo $email;
                        } else {
                            echo $user['email'];
                        }
                ?>)">
                <span class="text-danger"> <?php echo $emailError; ?> </span>
                </td>
            </tr>
            <tr>
                <td>
                    <label>Password</label>
                </td>
                <td>
                <input type="text" name="password" class="form-control" placeholder="Change Password">
                <span class="text-danger"> <?php echo $passError; ?> </span>
                <?php if(isset($_SESSION['user'])){
                        echo "<tr style='display: none;'>";
                    } else if (isset($_SESSION['adm'])){
                        echo "<tr>";
                    }
                    ?>
                    <td>
                    <label>User Level</label>
                    </td>
                    <td>
                            <select id="select" name="user_level" style="width:100%">
                            <option value=0  <?php 
                            if(isset($_SESSION['adm'])){
                                if($user['user_level'] == 0) {echo 'selected';};
                            }else{
                                echo"only for Admin";
                            }
                            ?>>User</option>
                            <option value=1 <?php
                            if(isset($_SESSION['adm'])){
                            
                                if ($user['user_level']  == 1) {echo 'selected';};
                            }else{
                                if ($user['user_level']  == 0) {echo 'selected';};
                            }
                            ?>>Admin</option>
                            </select>
                    </td>
                </tr>
                <?php if(isset($_SESSION['user'])){
                        echo "<tr style='display: none;'>";
                    } else if (isset($_SESSION['adm'])){
                        echo "<tr>";
                    }
                    ?>
                    <td>
                    <label>User State</label>
                    </td>
                    <td>
                    <select id="select" name="user_state" style="width:100%">
                        <option value=1 <?php if ($user['user_state']  == 1) {echo 'selected';}; ?>>Active</option>
                        <option value=0 <?php if ($user['user_state']  == 0) {echo 'selected';}; ?>>Banned</option>
                </select>
                    </td>
                </tr>
                <tr>
                <td></td>
                    <td>
                    <input type="submit" name="submit" class="btn btn-sm save-btn" value="Save">  || 
                    
                    <?php 
                    if(isset($_SESSION['user'])){
                        echo "<a class='btn btn-sm dash-btn' href='../profile.php?userId=$id'>Back to Profile</a>";
                    }
                    if(isset($_SESSION['adm'])){
                        echo'  || 
                    <a class="btn btn-sm home-btn" href="user_list.php">User List</a> || 
                    <a class="btn btn-sm dash-btn" href="../admin.php">Dashboard</a>';
                    }
                    ?>
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