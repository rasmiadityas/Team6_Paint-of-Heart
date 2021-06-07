<?php
ob_start();
session_start(); 
include 'classes/autoload.php' ;

$userObj = new User();

// get id from $_GET
if (isset($_GET['userId'])) {
if ($_GET['userId']) {
    $id = $_GET['userId'];
    $getData = $userObj->getUserById($id);
    if ($getData) {// id exist
        $user = $getData->fetch_assoc();
    }
}

// session redirection
// admin can see all profile
if (!isset($_SESSION['user']) && !isset($_SESSION['adm'])) { // general cannot see
    header("Location: home.php");
    exit;    
}
if (isset($_SESSION['user'])) { // user can only see own profile
    if ($id != $_SESSION['user']['id']) {
        // header("Location: home.php");
        exit;    
    }
}
}

// delete profile
if (isset($_GET['delprofId'])) {
    $id = $_GET['delprofId'];
    $deleteUser = $userObj->delProfileById($id);
    unset($_SESSION['user']);
    unset($_SESSION['adm']);
    session_unset();
    session_destroy();    
    echo "<script>
    alert('Sad to see you go! We welcome you anytime to be back again!');
    window.location.href='home.php';
    </script>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap 5 CSS bundle  -->
    <?php include_once 'components/boot.php';?>
	<link rel="stylesheet" href="components/style.css">
	<title>User Profile - Paint of Heart</title>
</head>
<header>
<?php include_once 'components/navbar.php'; ?>
</header>
<body>
<div class="grid_10">       
    <h2>Profile</h2><br>
        <div class="col-md-4 form">
            <div class="card mb-3">
                <div class="card-body form">
                    <div class="row">
                        <div class="col-sm-3">
                            <h6 class="mb-0">Full Name</h6>
                        </div>
                            <div class="col-sm-9 text-secondary">
                                <?php echo $user['f_name'] ?> <?php echo $user['l_name'] ?>
                            </div>
                        </div>
                    <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <h6 class="mb-0">Email</h6>
                            </div>
                            <div class="col-sm-9 text-secondary">
                                <?php echo $user['email'] ?>
                            </div>
                        </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-3">
                            <h6 class="mb-0">Address</h6>
                        </div>
                        <div class="col-sm-9 text-secondary">
                            <?php echo $user['address'] ?>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-12">
                            <a class="btn btn-sm save-btn" href="actions/user_edit.php?userId=<?php echo $user['id'] ?>">Edit</a> || 
                            <a onclick="return confirm('Are you sure to delete this user data (<?php echo $user['email'] ?>)?')" class="btn btn-sm dash-btn" href="?delprofId=<?php echo $user['id'] ?>">Delete</a>  || 
                            <a class="btn btn-sm home-btn text-light" href="home.php">Home</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>   
</div>
<?php include 'components/footer.php';?>
<!-- Bootstrap 5 JS bundle  -->
<?php include_once 'components/bootjs.php'; ?>
</body>
</html>