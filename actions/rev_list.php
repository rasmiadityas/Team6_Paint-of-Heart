<?php 
ob_start();
session_start(); 
 include '../classes/autoload.php' ;

 // session redirection
if (!isset($_SESSION['adm'])) {
    header("Location: ../home.php");
    exit;
}

$rev = new Review();

	if (isset($_GET['delId'])) {
		$id = preg_replace('/[^-a-zA-Z0-9_]/', '', $_GET['delId']);
    	$delRev = $rev->RevDelete($id);
		echo "<script>
alert('Review ID ".$id." deleted!');
window.location.href='rev_list.php';
</script>";
    }
 ?>

<div class="grid_10">
    <div class="box round first grid">	
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap 5 CSS bundle  -->
    <?php include_once '../components/boot.php';?>
	<link rel="stylesheet" href="../components/style.css">                   
    <title>Review List - Paint Of Heart</title>
</head>
<body>
	<h2>Review List</h2>
		<a href="../admin.php" class='btn btn-primary my-3 dash-btn fw-bold'>Dashboard</a>
		<a href="../home.php" class='btn btn-primary my-3 home-btn fw-bold'>Home</a>
        <div class="block"> 
			<table class="table" id="example">
				<thead>
					<tr>
						<th scope="col">Num/ID</th>
						<th scope="col">ProdID</th>
						<th scope="col">Product</th>
						<th scope="col">Category</th>
						<th scope="col">User</th>
						<th scope="col">Score</th>
						<th scope="col">Date</th>
						<th scope="col">Action</th>
					</tr>
				</thead>
			<tbody>
		<?php 
			$review = $rev->AllReviewAll();
			if (is_array($review)) { // if any review
				$i=0;				
				foreach ($review as $review) {
					$i++;
		?>
				<tr class="odd gradeX">
					<td><?php echo $i.'/'.$review['revid']; ?></td>
					<td><?php echo $review['fk_item_id'] ;?></td>
					<td><?php echo $review['name'] ;?></td>
					<td><?php echo $review['catName'] ;?></td>
					<td><?php echo $review['email'] ;?></td>
					<td><?php echo $review['score'] ;?></td>
					<td><?php echo $review['date'] ;?></td>
					<td>
					<div class="btn-group">
						<a onclick="return confirm('Are you sure to delete this review? (<?php echo 'RevID '.$review['revid'].', ProdID '.$review['fk_item_id'].', by '.$review['email'] ?>)?')" class= "btn btn-danger" href="?delId=<?php echo $review['revid'] ?>" title='Delete'><i class='material-icons'>&#xE872;</i></a>&nbsp;
						<a class= "btn btn-primary" href="../review.php?id=<?php echo $review['fk_item_id']."#".$review['revid'] ?>" title='View'><i class='material-icons'>&#xE417;</i></a>
				</div>
				</td>
					</tr>
					<tr><td colspan="8">Review: <?php echo $review['review'] ;?></td></tr>
		<?php } } else  { ?>
			<tr class="odd gradeX">
			<td colspan="8" class="text-center">No data available</td>
			</tr>
			<?php }
		?>	

			</tbody>
		</table>

       </div>
    </div>
</div>
<?php include '../components/footer.php';?>
<script type="text/javascript">
    $(document).ready(function () {
        setupLeftMenu();
        $('.datatable').dataTable();
		setSidebarHeight();
    });
</script>
<?php include_once 'components/bootjs.php'; ?>
</body>
</html>