<?php 
ob_start();
	include '../classes/autoload.php';

    Session::init();
   
    if (!isset($_SESSION['user']) && !isset($_SESSION['adm'])) {
        header("Location: home.php");
        exit;    
    }
    if (isset($_SESSION["user"])) {
        header("Location: home.php");
        exit;
    }
	$pd = new Product();
	$fm = new Format();
	if (isset($_GET['productId'])) {
        $id = preg_replace('/[^-a-zA-Z0-9_]/', '', $_GET['productId']);
    	$deleteProduct = $pd->delProductById($id);
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
<title>Product List - Paint of Heart</title>
</head>
<body>	
<div class="grid_10">
    <div class="box round first grid">
        <h2>Product List</h2>
        <div class="block"> 
	<?php 
	if (isset($deleteProduct)) {
        echo $deleteProduct;   
		}
		if (isset($deleteProduct)) {
			echo $deleteProduct;   
		}
		?>
		<td>
	<a href="../admin.php" class='btn btn-success my-3 dash-btn'>Dashboard</a>
	<a href="../home.php" class='btn btn-success my-3 home-btn'>Home</a>
	</td>
            <table class="table" id="example">
			<thead>
				<tr>
					<th scope="col">#/ID</th>
					<th scope="col">Product Name</th>
					<th scope="col">Category</th>
					<th scope="col">Description</th>
					<th scope="col">Base Price</th>
					<th scope="col">Price</th>
					<th scope="col">Image</th>
					<th scope="col">Discount</th>
					<th scope="col">Visibility</th>
					<th scope="col">Action</th>
				</tr>
			</thead>
			<tbody>
		<?php 
			$getData = $pd->getAllProduct();
			if ($getData) {
				$i=0;
				while ($product = $getData->fetch_assoc()) {
					$i++;
					$price = 0;
					$dPrice = 0;
					$discount = 0;
					$priceRounded = 0;
		?>
				<tr class="odd gradeX">
					<td><?php echo $i."/".$product['id']; ?></td>
					<td><?php echo $product['name'] ;?></td>
					<td><?php echo $product['catName'] ;?></td>
					
					<td><?php echo $fm->textShorten($product['description'],35) ;?></td>
					<td><?php 
					  if($product['discount'] > 0) {
						  $discount=$product['discount'];
						  $price=$product['price'];
						  $dPrice=$price*((100-$discount)/100);
						  $priceRounded = round($dPrice, 2);
							echo "<span class='text-success'>$".$price."</span>" ;
						}else{

							echo "<span class='text-success'>$".$product['price']."</span>" ;
						}
						?>
					</td>
					<td><?php 
					  if($product['discount'] > 0) {
							echo "<span class='text-danger'>$".$priceRounded."</span>" ;
						}else{
							echo "<span class='text-success'>$".$product['price']."</span>" ;
						}
						?>
					</td>
					<td><img src="<?php
                    echo $product['image'];?>" height="50px" width="70px"></td>
					<td>
					<?php 
						if ($product['discount'] > 0) {
							echo "<span class='text-danger'>Offered ".$product['discount'] ." %</span>";
						}else{
							echo 'None';
						}
						?>
					</td>
						<td><?php echo $product['visibility'] ;?></td>
					<td><a class="btn btn-success btn-sm" href="product_edit.php?productId=<?php echo $product['id'] ?>">Edit</a> || 
					<a onclick="return confirm('Are you sure?')" class= "btn btn-danger btn-sm" href="?productId=<?php echo $product['id'] ?>">Delete</a></td>
				</tr>
		<?php } } ?>
			</tbody>
		</table>
    </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        setupLeftMenu();
        $('.datatable').dataTable();
		setSidebarHeight();
    });
</script>
<?php include '../components/footer.php';?>
<?php include_once 'components/bootjs.php'; ?>
</body>
</html>