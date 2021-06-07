<?php
ob_start();
session_start();
include 'classes/autoload.php';

// session redirection
if(isset($_SESSION[ 'user'])!="") {
    header("Location: home.php");
}
else if(isset($_SESSION[ 'adm'])!="") {
    header("Location: admin.php");
}

$userObj = new RegLog();
$f_name = $l_name = $address = $email = '';
$fnameError = $emailError =  $passError = '';

// get registration data from $_POST
if (isset($_POST['submit'])) {
	$f_name = $_POST['f_name'];
	$l_name = $_POST['l_name'];
	$address = $_POST['address'];
	$email = $_POST['email'];
    $password = $_POST['password'];
    
// register function
  $register = $userObj->UserRegister($f_name, $l_name, $address, $email,$password);
  // if register error -> receives error msgs
    if (is_array($register)) {
      $fnameError = $register['fnameError'];
      $emailError = $register['emailError'];
      $passError = $register['passError'];
      }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Register</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="components/style.css">
  <title>Register - Paint Of Heart</title>
  <?php include_once 'components/boot.php'; include_once 'components/navbar.php'; include_once 'components/heroLogin.php';?>
</head>
<body>
    <div div id="register">  
        <h1 class="login">Register below</h1>   
          <div class="container">
              <div class="d-flex flex-column justify-content-center align-items-center py-5">
                  <form name="register" method="post" action="">
                  <div class="form-group form-inline">
                    <label for="f_name">First Name</label>      
                      <input type="text" name="f_name" class="form-control" placeholder="First Name" required="" value="<?php echo $f_name ?>">
                      <span class="text-danger"> <?php echo $fnameError; ?> </span>
                  </div><br>
                  <div class="form-group form-inline">
                    <label for="l_name">Last Name</label>   
                      <input type="text" name="l_name" class="form-control" placeholder="Last Name" required="" value="<?php echo $l_name ?>">
                      <span class="text-danger"> <?php echo $fnameError; ?> </span>
                  </div><br>
                  <div class="form-group form-inline">
                    <label for="address">Address</label>    
                      <input type="text" name="address" class="form-control" placeholder="Address" required="" value="<?php echo $address ?>">
                  </div><br>
                  <div class="form-group form-inline">
                    <label for="email">Email</label>    
                      <input type="text" name="email" class="form-control" placeholder="Your Email" required="" value="<?php echo $email ?>">
                      <span class="text-danger"> <?php echo $emailError; ?> </span>
                  </div><br>
                  <div class="form-group form-inline">
                    <label for="password">Password</label>  
                      <input type="password" name="password" class="form-control" placeholder="Password" required="">
                      <span class="text-danger"> <?php echo $passError; ?> </span>
                  </div><br>
                  <input type="submit" name="submit" class="btn btn-primary btn-block" value="Sign Up">
                  <a class="btn btn-outline-warning" href="login.php">Already a member? Click Here</a>
                  </form>  
              </div>
          </div>
    </div>  
    <footer>
        <?php include_once 'components/footer.php'; ?>
  </footer>
    <!-- Bootstrap 5 JS bundle  -->
    <?php include_once 'components/bootjs.php'; ?>
  </body>
</html>