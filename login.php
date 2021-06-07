<?php
ob_start();
session_start();
include 'classes/autoload.php' ;

// session redirection
if(isset($_SESSION['user'])) {
    header("Location: home.php");
}
else if(isset($_SESSION['adm'])) {
    header("Location: admin.php");
}

$userObj = new RegLog();

$email = '';
$success = "";
$error = "";

// get registration data from $_POST
if (isset($_POST['submit'])) {
	$email = $_POST['email'];
    $password = $_POST['password'];
    
      $login = $userObj->Login($email,$password);

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Login</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="components/style.css">
  <title>Login - Paint of Heart</title>
  <?php
  include_once 'components/boot.php';
  include_once 'components/navbar.php';
  include_once 'components/heroLogin.php';
  ?>
</head>
<body>
  <h1 class="login"> Login </h1>   
    <div class="container">
      <div class="d-flex flex-column justify-content-center align-items-center py-5">
            <form name="login" method="post" action="">
              <div class="form-group form-inline">  
                <label for="email">Email</label>  
                  <input type="text" name="email" id="email" class="form-control" placeholder="Your email" required="" value="<?php echo $email ?>"><br>
              </div><br>
            <label for="password">Password</label>
            <input type="password" name="password" id="password" class="form-control" placeholder="Password" required=""><br>
            <div id="alert" class="text-danger fw-bold"></div> <!-- alert for banned user -->
            <input type="submit" id="submit" name="submit" class="btn home-btn btn-block text-light" value="Sign In">
            <a class="btn btn-warning btn-block" href="register.php">No Account yet? Register here</a>
            </form>
        </div>  
    </div>   
    <footer>
        <?php include_once 'components/footer.php'; ?>
    </footer>
    <!-- Bootstrap 5 JS bundle  -->
    <?php include_once 'components/bootjs.php'; ?>
  </body>

  
</html>

