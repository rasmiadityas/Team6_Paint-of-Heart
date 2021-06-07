<?php 
ob_start();
include 'classes/autoload.php';

Session::init();

if (!isset($_SESSION['user']) && !isset($_SESSION['adm'])) {
    header("Location: home.php");
    exit;    
}
if (isset($_SESSION["user"])) {
    header("Location: home.php");
    exit;
}

	$cart = new Cart();
	$fm 	= new Format();

	if (isset($_GET['shiftid'])) {
		$id    = $_GET['shiftid'];
		$price = $_GET['price'];
		$date  = $_GET['date'];
		$shifted = $cart->productShifted($id, $price, $date);
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
    <?php include_once 'components/boot.php';?>
	<link rel="stylesheet" href="components/style.css"> 
</head>
<body> 
        <div class="grid_10">
            <div class="box round first grid">
                <h2>Inbox</h2>			
				<a  href="admin.php" class="btn btn-primary">Dashboard</a>
            <?php 
            	if (isset($shifted)) {
            		echo $shifted;
            	}
            	if (isset($delShiftedProduct)) {
            		echo $delShiftedProduct;
            	}

             ?>
                <div class="container">        
         <table class="table  display datatable" id="example">
					<thead>
						<tr>
							<th>ID</th>
							<th>Order Time</th>
							<th>Product</th>
							<th>Quantity</th>
							<th>Image</th>
							<th>Price</th>
							<th>custID</th>
							<th>Address</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
				<?php 
					$orderData = $cart->getAllOrderProduct();
					if ($orderData) {
						while ($order = $orderData->fetch_assoc()) {
				 ?>
						<tr class="odd gradeX">
							<td><?php echo $order['fk_productId']; ?></td>
							<td><?php echo $fm->formatDate($order['date']); ?></td>
							<td><?php echo $order['productName']; ?></td>
							<td><?php echo $order['quantity']; ?></td>
							<td><img style="width: 100px;height:150px;" src="pictures/<?php echo $order['image']; ?>"/></td>
							<td><?php echo $order['price']; ?> €</td>
							<td><?php echo $order['fk_userId']; ?> </td>
							<td><a href="customer.php?custId=<?php echo $order['fk_userId']; ?>">View Details</a></td>						
								<?php
								 if ($order['status'] == '0') { ?>
									<td><a class="btn btn-secondary" href="?shiftid=<?php echo $order['fk_userId'];?>&price=<?=$order['price']; ?>&date=<?php echo $order['date']; ?>">shipping</a></td>
							<?php }elseif ($order['status'] == '1') { ?>
								 <td class="text-warning" >Pending</td>
							<?php  } else{ ?>
									<td><a class="btn btn-danger" href="?delProId=<?php echo $order['fk_userId'];?>&price=<?= $order['price']; ?>&date=<?php echo $order['date']; ?>">Remove</a></td>
							<?php } ?>
						</tr>
				<?php } } ?>		
					</tbody>
				</table>
               </div>
            </div>
        </div>
<?php include 'components/footer.php';?>
<!-- Bootstrap 5 JS bundle  -->
<?php include_once 'components/bootjs.php'; ?>
</body>
</html>