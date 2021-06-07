<?php
ob_start();
session_start();
// logout.php -> redirect
if (!isset($_SESSION['adm']) && !isset($_SESSION['user'])) {
    header("Location: ../home.php");
    exit;
}
else if(isset($_SESSION[ 'user'])!="") {
    header("Location: ../home.php");
}
else if(isset($_SESSION[ 'adm'])!="") {
    header("Location: ../admin.php");
}
// logout.php?logout -> logged out + redirect
if (isset($_GET['logout'])) {
    unset($_SESSION['user']);
    unset($_SESSION['adm']);
    session_unset();
    session_destroy();
    header("Location: ../home.php");
    
}
?>