<?php 
ob_start();
include 'classes/autoload.php' ;
// without login can't access
Session::init();
$id=$_SESSION['user']['id'];
if (!isset($_SESSION['user']) && !isset($_SESSION['adm'])) { // general  cannot see
    header("Location: home.php");
    exit;    
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap 5 CSS bundle  -->
    <link rel="stylesheet" href="components/style.css">
	<?php include_once 'components/boot.php'; ?>
    <title>Payment - Paint Of Heart</title>
		<style>
		.payment{width: 500px; margin: 0 auto;border:2px solid #ddd;text-align: center;padding:50px;min-height: 160px;margin-bottom: 20px;background:#f1f1f1}
		.payment h2 {padding-bottom: 8px;border-bottom: 2px solid #bbb;margin-bottom: 65px;font-weight: bold;
			font-size: 35px;}
		.payment a {background: tomato;color: #fff;padding: 10px 25px;border-radius: 3px;font-size: 20px;}
		.back a {width: 150px;margin: 0 auto;display: block;background: #3435;color: #fff;padding: 6px 20px;text-align:center;font-size: 25px;border-radius: 3px;}
		</style>
</head>
<body>
    <header>
    <?php 
    include_once 'components/navbar.php'; 
    include_once 'components/hero_product.php';
    ?>
    </header>
	<main>
		<div class="main">
			<div class="content">
			  <div class="section group">
			    <div class="payment">
			    	<h2>Choose Payment Option</h2>
			    	<a class="btn nav-btn " href="offline_payment.php?userID=<?=$id?>">Payment</a>
			    	
			    </div>
			  </div>
				<div class="text-center">
			    <a class='btn btn-success my-3 home-btn text-light'href="actions/cart_list.php">Go Back</a></div>	
		 		</div>
		 	</div>
		</div>
		<div class="topbtn"> 
        <button onclick="topFunction()" id="scrollToTopBtn" title="Go to top">Top☝️</button>
        </div>
		</main>
		<footer>
        <?php include_once 'components/footer.php'; ?>
    </footer>
    <!-- Bootstrap 5 JS bundle  -->
    <?php include_once 'components/bootjs.php'; ?>
    <script src="js/script.js"></script>
</body>
</html>