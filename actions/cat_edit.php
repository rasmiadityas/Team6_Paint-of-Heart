<?php
ob_start();
    include '../classes/category.class.php';
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
    $cat = new Category();
    if (!isset($_GET['catId']) || $_GET['catId'] == 'NULL') {
        echo '<script> window.location = "cat_list.php"; </script>';
    }else{
        // $id = $_GET['catId'];
        $id = preg_replace('/[^-a-zA-Z0-9_]/', '', $_GET['catId']);
    }

   if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $catName = $_POST['catName'];
        $catUpdate = $cat->catUpdate($catName, $id);
    }
 ?>
        <div class="grid_10">
            <div class="box round first grid">
                <h2>Update Category</h2>
               <div class="block copyblock"> 
<?php //not msg name
        if (isset($catUpdate)) {
         echo $catUpdate;
        }                       
        $result = $cat->getCatById($id);
        if ($result) {
            while ($category = $result->fetch_assoc()) {
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
<title>Category Edit - Paint of Heart</title>
            </head>
            <body>
            <form action="" method="post">
                    <table class="form">					
                        <tr>
                            <td>
                                <input type="text" name="catName" value="<?php echo $category['catName'] ?>" class="medium" />
                                <input type="submit" name="submit" class="btn btn-sm save-btn" value="Save">
                            </td>
                        </tr>
						<tr> 
                            <td>
                            <a class="btn btn-sm home-btn" href="cat_list.php">Category List</a> || 
                            <a class="btn btn-sm dash-btn" href="../admin.php">Dashboard</a>
                            </td>
                        </tr>
                    </table>
                    </form>
    <?php } } ?>
                </div>
            </div>
        </div>
<?php include '../components/footer.php';?>
<?php include_once 'components/bootjs.php'; ?>
</body>
</html>