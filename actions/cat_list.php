<?php
ob_start();
	include '../classes/category.class.php'; 
	include '../classes/autoload.php';

    Session::init();
   
    if (!isset($_SESSION['user']) && !isset($_SESSION['adm'])) {
        header("Location: ../home.php");
        exit;    
    }
    if (isset($_SESSION["user"])) {
        header("Location: ../home.php");
        exit;
    }
	
    $cat = new Category();
    if (isset($_GET['catId'])) {
    	// $id = $_GET['catId'];
    	$id = preg_replace('/[^-a-zA-Z0-9_]/', '', $_GET['catId']);
    	$deleteCat = $cat->delCatById($id);
    }
?>
<div class="grid_10">
	<div class="box round first grid">
		<h2>Category List</h2>
		<div class="block"> 
			<?php 
				if (isset($deleteCat)) {
					echo $deleteCat;
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
	<title>Category List - Paint of Heart</title>
</head>
<body>
	<td>
	<a href="../admin.php" class='btn btn-success my-3 dash-btn'>Dashboard</a>
	<a href="../home.php" class='btn btn-success my-3 home-btn'>Home</a>
	</td>
                    <table class="table" id="example">
					<thead>
						<tr>
							<th scope="col">#/ID</th>
							<th scope="col">Category Name</th>
							<th scope="col">Action</th>
						</tr>
					</thead>
					<tbody>
						<tr class="odd gradeX">
			<?php 
				$getAllCat = $cat->catSelect();
				if ($getAllCat) {
					$i = 0;
					while ($category = $getAllCat->fetch_assoc()) { 
						$i++;
			?>
							<td><?php echo $i."/".$category['id'] ;?></td>
							<td><?php echo $category['catName'] ?></td>
							<td><a class="btn btn-success btn-sm" href="cat_edit.php?catId=<?php echo $category['id'] ?>">Edit</a> ||
							<a onclick="return confirm('Are you sure to delete?')" class="btn btn-danger btn-sm" href="?catId=<?php echo $category['id'] ?>">Delete</a></td>
						</tr>
			<?php } } ?>			
					</tbody>
				</table>
            </div>
            </div>
        </div>
<?php include '../components/footer.php';?>
<?php include_once 'components/bootjs.php'; ?>
</body>
</html>