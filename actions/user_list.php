<?php 
ob_start();
session_start(); 
 include '../classes/autoload.php' ;

 // session redirection
if (!isset($_SESSION['adm'])) {
    header("Location: ../home.php");
    exit;
}

$userObj = new User();

	if (isset($_GET['delId'])) {
		$id = preg_replace('/[^-a-zA-Z0-9_]/', '', $_GET['delId']);
    	$deleteUser = $userObj->delUserById($id);
		echo "<script>
		alert('User ID ".$id." deleted!');
		window.location.href='user_list.php';
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
	<title>User List - Paint of Heart</title>
</head>
<body>
        <h2>User List</h2>
		<a href="../admin.php" class='btn btn-success my-3 dash-btn'>Dashboard</a>
		<a href="../home.php" class='btn btn-success my-3 home-btn'>Home</a>
		<a href="user_add.php" class='btn btn-success my-3 save-btn'>Add User</a>
        <div class="block"> 
			<table class="table" id="example">
				<thead>
					<tr>
						<th scope="col">Num/ID</th>
						<th scope="col">First Name</th>
						<th scope="col">Last Name</th>
						<th scope="col">Address</th>
						<th scope="col">Email</th>
						<th scope="col">Status</th>
						<th scope="col">Level</th>
						<th scope="col">Action</th>
					</tr>
				</thead>
			<tbody>
		<?php 
			$getData = $userObj->getAllUser();
			if ($getData) {
				$i=0;
				while ($user = $getData->fetch_assoc()) {
					$i++;
		?>
				<tr class="odd gradeX">
					<td><?php echo $i.'/'.$user['id']; ?></td>
					<td><?php echo $user['f_name'] ;?></td>
					<td><?php echo $user['l_name'] ;?></td>
					<td><?php echo $user['address'] ;?></td>
					<td><?php echo $user['email'] ;?></td>
					<td><?php 
					if ($user['user_state'] == 1) { // active
						echo 'Active';
					} else if ($user['user_state'] == 0) { // banned
						echo 'Banned';
					}					
					?></td>
					<td><?php 
					if ($user['user_level'] == 0) { // user
						echo 'User';
					} else if ($user['user_level'] == 1) { // admin
						echo 'Admin';
					}					
					?></td>
					
					<td><div class="btn-group">
						<a class="btn btn-success btn-sm" href="user_edit.php?userId=<?php echo $user['id'] ?>" title="Edit"><i class='material-icons'>&#xE254;</i></a>&nbsp; 
					<a onclick="return confirm('Are you sure to delete this user (<?php echo $user['email'] ?>)?')" class= "btn btn-danger btn-sm" href="?delId=<?php echo $user['id'] ?>" title="Delete"><i class='material-icons'>&#xE872;</i></a>
				</div></td>
				</tr>
		<?php } } ?>	

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