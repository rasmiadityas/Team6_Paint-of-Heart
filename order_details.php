<?php 
ob_start();
include 'classes/autoload.php' ;?>


<?php // without login can't access
Session::init();
$cart =new Cart();
if (!isset($_SESSION['user']) && !isset($_SESSION['adm'])) { // general  cannot see
    header("Location: home.php");
    exit;    
}
?>

<?php 
	if (isset($_GET['confirmid'])) {
		$id    = $_GET['confirmid'];
		$price = $_GET['price'];
		$date  = $_GET['date'];
		$confirmed = $cart->confirmProductShifted($id, $price, $date);
	}
//for delete shifted product from admin panel
	if (isset($_GET['delProId'])) {
		$id    = $_GET['delProId'];
		$price = $_GET['price'];
		$date  = $_GET['date'];
		$delShiftedProduct = $cart->deleteShiftedProduct($id, $price, $date);
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
    <title>Order List - Paint Of Heart</title>
        <style>
            .details {font-size: 26px;font-weight: bold;margin-top: 20px;margin-bottom: 8px;text-align: center;}
        </style>
</head>
<body>
    <header>
    <?php
     include_once 'components/navbar.php'; 
     include_once 'components/hero.php';
     ?>
    </header>
    <div class="container">
<div class="details">
	<h2>Your Order Details</h2>
</div>
<div class="table-responsive">
  <table class="table table-dark">
	  						<tr>
								<th width="5%">No</th>
								<th width="18%">Product Name</th>
								<th width="10%">Image</th>
								<th width="5%">Quantity</th>
								<th width="10%">Price</th>
								<th width="20%">Date</th>
								<th width="8%">Status</th>
								<th width="8%">Action</th>
							</tr>
			<?php 
                $cart=new Cart();
                $fm=new Format();
				$customerId =$_SESSION['user']['id'];
				$getOrder = $cart->getOrderDetails($customerId);
				if ($getOrder) {
					$i = 0;
					while ($order = $getOrder->fetch_assoc()) {
						$i++; ?>				
							<tr>
								<td><?php echo $i; ?></td>
								<td><?php echo $order['productName']."<br>
								Cat.: ".$order['catName'] ;?><br>
								<a class= "btn btn-light" href="details.php?id=<?php echo $order['fk_productId'] ?>#form-rev" title='Review'>Give Review</a></td>
								<td><img style="width: 100px;height:100px;" src="pictures/<?php echo $order['image'] ;?>" alt=""/></td>
								<td><?php echo $order['quantity'] ;?></td>
								<td><?=$order['price'];?> €</td>
								<td><?php echo $fm->formatDate($order['date']) ;?></td>
								<td>
								<?php 
									if ($order['status'] == '0' ) {
										echo "Pending";
									}
									elseif($order['status'] == '1' ){ ?>
										<a class="btn btn-warning" href="?confirmid=<?php echo $order['fk_userId'];?>&price=<?=$order['price'];?>&date=<?php echo $order['date']; ?>">Receive</a>	
								<?php
								 } else{
										echo "Confirmed";
									}
								 ?>
								 </td>
								<td>
									<?php 
										if ($order['status'] == '2') { ?>
											<a class="btn btn-danger" onclick="return confirm('Are you sure?')" href="?delProId=<?php echo $order['fk_userId'];?>&price=<?=$order['price'];?>&date=<?php echo $order['date']; ?>">Delete</a>
									<?php }else{
										echo "N/A";
									} ?>
								</td>
							</tr>
					<?php } } ?>
			</table>
    </div>
          <div class="topbtn"> 
          <button onclick="topFunction()" id="scrollToTopBtn" title="Go to top">Top☝️</button>
    </div>
    </div>
    <footer>
        <?php include_once 'components/footer.php'; ?>
    </footer>
    <!-- Bootstrap 5 JS bundle  -->
    <?php include_once 'components/bootjs.php'; ?>
    <script src="js/script.js"></script>
</body>
</html>