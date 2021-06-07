<?php 
ob_start();
include 'classes/autoload.php' ;
// without login can't access
Session::init();
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
    <title>Order Success - Paint Of Heart</title>
</head>
<body>
    <header>
    <?php 
     include_once 'components/navbar.php'; 
     include_once 'components/hero.php';
     ?>
    </header>
    <main>
        <div class="container">
		<div class="main">
			<div class="content">
			  <div class="section group">
			    <div class="payment">
			    	<h2>Success</h2>
			    	<?php  
								$cart = new Cart();
			    				$customerId = $_SESSION['user']['id'];

								// obtain order_register ID to create receipt
								$regid = $cart->getOrderRegID($customerId);
								
			    				$amount = $cart->payableAmount($customerId, $regid);
			    				if ($amount) {
			    					$sum = 0;
			    					while ($order = $amount->fetch_assoc()) {
			    						$price = $order['price'];
			    						$sum   = $sum + $price;
			    					}
			    				}
			    			?>
			    	<p>Total Payable Amount ( Including MWSt ) : 
			    		<span>
			    			<?php 
			    				global $sum;								
								$vat = $sum * 0.1;
			    				$total = $sum + $vat;
			    				echo $total.' €';	    				
			    			?>
			    		</span> </p>
			    	<p>Thanks for Purchase. Receive your order successfully. We will contact you with payment slip and also delivery details. Here is your order details... <a class="btn btn-success m-2" href="order_details.php?userId=<?=$customerId?>">Visite Here</a></p>
			    </div>
		 		</div>
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