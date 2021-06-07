<?php
ob_start();
    include '../classes/autoload.php' ;
	session_start();
    // session redirection
    // admin can see all profile
    if (!isset($_SESSION['user']) && !isset($_SESSION['adm'])) { // general  cannot see
        header("Location: home.php");
        exit;    
    }
	   $cart = new Cart();
       $user = $_SESSION['user']['f_name'];
       $id = $_SESSION['user']['id'];
       if (isset($_GET['delCart'])) {
		$delCart = preg_replace('/[^-a-zA-Z0-9_]/', '', $_GET['delCart']);
		$deleteCart = $cart->deleteCartById($delCart);
	}
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$cartId   = $_POST['cartId'];  //cartId database mistake
		$quantity = $_POST['quantity'];
		$updateQuantity = $cart->updateCartQuantity($cartId, $quantity);

		if ($quantity <= 0 ) {
			$deleteCart = $cart->deleteCartById($cartId);
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
<title>Cart - Paint of Heart</title>	
<div class="grid_10">
    <div class="box round first grid">
        <h2><?php echo $user ?>'s Cart</h2>
        <div class="block"> 	
    	<?php 
				if (isset($updateQuantity)) {
					echo $updateQuantity;
				}
				if (isset($deleteCart)) {
					echo $deleteCart;
				}
		  ?>
<td>
    <div class="d-flex justify-content-center">
        <a href="../products.php" class='btn btn-success my-3 mx-2 home-btn'>Home</a>
        <a href="../products.php" class='btn btn-success my-3 mx-2 dash-btn'>Products</a>
    </div>
</td>
		<div class="table-responsive">
            <table class="container table table-striped w-auto mx-auto"id="example">
			<thead class='table-success '>
				<tr>
					<th scope="col">SL No.</th>
					<th scope="col">Product Name</th>
					<th scope="col">Image</th>
					<th scope="col">Discount</th> 
					<th scope="col">Price</th>
                    <th scope="col">Quantity</th>
                    <th scope="col">Total</th>
					<th scope="col">Action</th>
				</tr>
			  </thead>
			<tbody>
				<?php 
				$getData = $cart->getCartProductById();
				if ($getData) {
					$i = 0;
					$qty = 0;
					$sum = 0;
					while ($cart = $getData->fetch_assoc()) {
						$i++;
	                 ?>	
				<tr class="odd gradeX">
					<td><?php echo $i; ?></td>
					<td><?php echo $cart['productName'] ;?></td>
					<td><img src="<?php
                    echo $cart['image'];?>" height="50px" width="70px"></td>
					<td>
						<?php 
						if ($cart['discount'] > 0) {
							echo '<span class="text-danger"> '.$cart['discount'] .' %</span>';
						}else{
							echo 'None';
						}
						?>							
				     </td>
				     <td>
					<?php 
						if ($cart['discount'] > 0) {
							echo '<span class="text-danger">€'.$cart['price'].'</span>';
						}else{
							echo '<span class="text-black"> €'.$cart['price'].'</span>';
						}
						?>	
				     </td>
					<td>
					  <form action="" method="post">
							<input type="hidden" name="cartId" value="<?php echo $cart['cartId'] ;?>"/>
							<input type="number" class=" form-control " name="quantity" min="0" max="20" value="<?php echo $cart['quantity'] ;?>"/>
							<input class="btn btn-success rounded-pill btn-sm mx-1" type="submit" name="submit" value="Update"/>
					  </form>
					</td>
					<td>
					<?php 
						$totalPrice = $cart['price'] * $cart['quantity'];
						echo $totalPrice;
					  ?> €
					</td>
					<td><a class= "btn save-btn btn-sm mx-1 rounded-pill" onclick="return confirm('Are you sure?')" href="?delCart=<?php echo $cart['cartId'] ;?>">Remove</a>
				     </td>
				</tr>
				    <?php 
						$qty = $qty + $cart['quantity'];
						$sum = $sum + $totalPrice;	
						Session::set("sum", $sum);
						Session::set("qty", $qty);						
				}
			  }						 
				$cart=new Cart();
				$getData=$cart->checkCartTable();
							
				if($getData){
				?>
	             <tr>
						  <td></td>
						  <td>
							  <div class="shopleft">
								  <a href="../products.php"> <img src="../pictures/shop.png" alt="shopping" /></a>
							  </div>
						  </td>
						  <td></td>
						  <td>Sub Total :
						<?php 
							// $cart=new Cart();
							$getData=$cart->checkCartTable();
							if($getData){
								$sum=Session::get("sum");
								$qty=Session::get("qty");	
								echo $sum ;
							}else {
								$sum="0.00 ";
								echo $sum;
								$qty="(empty) ";
							}
							?>€
						  </td>
                    <td>
						MWST 10%: 
					</td>
                    <td>
						Quantity: 
						<?php 
						// $cart=new Cart();
						$getData=$cart->checkCartTable();
						if($getData){
							$qty=Session::get("qty");	
							echo $qty;
						}else {
							$qty="";
							echo $qty;
						}
						?>
					</td>
					<td> Final Total :
									<?php
									if($sum==0){
										$grandTotal ="0.00";
										echo $grandTotal;

									}else{
										$vat = $sum * 0.1 ;  
										$grandTotal = $sum + $vat;
										$grandTotal = round($grandTotal,2);
										echo $grandTotal;
									}	
									?>€
							</td>
                    <td>
						<div class="shopright">
							<a href="../payment.php?userId=<?=$id?>"> <img src="../pictures/check.png" alt="checkout" /></a>
						</div>
					</td>
                </tr>
				<?php 
					 }else{
					 	echo "<tr>
						<td colspan='8'>
						Cart Empty! Please Shop Now.
						</td>
						</tr>";
						
					 } 
					 ?> 
			</tbody>
		</table>
	</div>
    </div>
</div>
</div>
<?php include '../components/footer.php';?>
<?php include_once 'components/bootjs.php'; ?>
</body>
</html>