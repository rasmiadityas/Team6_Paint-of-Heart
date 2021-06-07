<?php
ob_start();
include 'classes/autoload.php';

Session::init();
if (!isset($_SESSION['user']) && !isset($_SESSION['adm'])) { // general  cannot see
    header("Location: home.php");
    exit;    
}
		if (isset($_GET['orderId']) && $_GET['orderId'] == 'order') {
            $cart= new Cart();
			$customerId = $_SESSION['user']['id'];
			$order = $cart->orderProduct($customerId);
			$deleteCart = $cart->delCustomerCart();
			 //after order deleted cart data
			header("location: success.php?userId=$customerId");
			exit;
		}
		// elseif($_GET['orderId'] == $customerId){
			
		// 	header("location: products.php");
		// }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap 5 CSS bundle  -->
    <link rel="stylesheet" href="components/style.css">
	<?php include_once 'components/boot.php';?>
    <title>Order Confirmation - Paint Of Heart</title>
</head>
<body>
    <header>
    <?php
     include_once 'components/navbar.php'; 
     include_once 'components/hero_product.php';
     ?>
    </header>
<?php 
    $customer=new User();
	$id =$_SESSION['user']['id'];
	$getData = $customer->getUserById($id);
	if($getData){
	//   if(!is_numeric($getData)){
		// print_r($getData);
		$result=$getData->fetch_assoc();
	   ?>
			 <div class="container mt-2">
			    		<div class="container">
			    			<table class=" table table-sm table-dark table-hover">
							<tr>
								<th>No</th>
								<th>Product</th>
								<th>Price</th>
								<th>Quantity</th>
								<th>Total</th>
							</tr>
			<?php 
                $cart= new Cart();
				$getData = $cart->getCartProductById();
				if ($getData) {
					$i = 0;
					$qty = 0;
					$sum = 0;
					while ($cart = $getData->fetch_assoc()) {
						$i++; ?>				
							<tr>
								<td><?php echo $i; ?></td>
								<td><?php echo $cart['productName'] ;?></td>
								<td><?php echo $cart['price'] ;?></td>
								<td><?php echo $cart['quantity'] ;?></td>
								<td>
									<?php 
										$totalPrice = $cart['price'] * $cart['quantity'];
										echo $totalPrice .' €';
								  ?>
								</td>
							</tr>
							<?php 
								$qty = $qty + $cart['quantity'];
								$sum = $sum + $totalPrice;	
								Session::set("sum", $sum);
								Session::set("qty", $qty);
							} } ?>
						</table>
						<table class="table table-success table-striped">
							<tr>
								<th>Sub Total</th>
								<td>:</td>
								<td><?php echo $sum ;?> €</td> <!--$sum GLOBAl variable-->
							</tr>
							<tr>
								<th>MWST</th>
								<td>:</td>
								<td>10% (<?php
									if($sum>0){
                                      echo $vat = $sum * 0.1 ; 
									}else{

										echo (int)$vat = (int)$sum * 0.1 ; 
									}							  
								 ?> €) </td>
							</tr>
							<tr>
								<th>Final Total</th>
								<td>:</td>
								<td> 
									<?php
										if($sum>0){
										// echo $vat = $sum * 0.1 ; 
										$vat = $sum * 0.1 ;  
										$grandTotal = $sum + $vat;
										echo $grandTotal.' €';
										}else{

										(int)$vat = (int)$sum * 0.1 ; 
										echo (int)$vat.' €' ; 
										}
									?> 
								</td>
							</tr>
								<tr>
								<th>Quantity</th>
								<td>:</td>
								<td><?php echo $qty ;?></td> <!--$sum ke GLOBAl korte hobe -->
							</tr>
					   </table>
			    			<table class="table table-dark table-sm">
			    			<tr>
			    				<td colspan="3"><h2>Your Address Details</h2></td>
			    			</tr>
			    			<tr>
			    				<td>Name</td>
			    				<td>:</td>
			    				<td><?php echo $result['f_name']." ".$result['l_name']; ?></td>
			    			</tr>
                            <tr>
                                <td>Address</td>
                                <td>:</td>
                                <td><?php echo $result['address']; ?></td>
                            </tr>
			    			<tr>
			    				<td>Email</td>
			    				<td>:</td>
			    				<td><?php echo $result['email']; ?></td>
			    			</tr>		    		
			    			<tr>
			    				<td></td>
			    				<td></td>
			    				<td><a class="text-reset text-decoration-underline" href="actions/address_edit.php?userId=<?php echo $id?>">Update Address</a></td>
			    			</tr>
			    			<tr>
			    				<td></td>
			    				<td></td>
			    				<td><a class="btn save-btn" href="?orderId=order">Order Now</a></td>
			    			</tr>	
							</table>
						</div>
       <?php 
	} else{
		echo "no data avialbve";
	} 
		$work='<div>
			<a class="btn btn-success" href="?orderId=order">Order Now</a>
			</div>';
	 ?>
				</div>
					<footer>
						<?php include_once 'components/footer.php'; ?>
					</footer>
					<!-- Bootstrap 5 JS bundle  -->
					<?php include_once 'components/bootjs.php'; ?>
				</body>
</html>