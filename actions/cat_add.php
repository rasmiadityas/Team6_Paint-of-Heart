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
    $cat = new Category();
   if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $catName = $_POST['catName'];
        $catInsert = $cat->catInsert($catName);
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
<title>Category Add - Paint of Heart</title>
</head>
<body>
        <div class="grid_10">
            <div class="box round first grid">
                <h2>Add New Category</h2>
            <div class="block copyblock"> 
    <?php //not msg name
        if (isset($catInsert)) {
        echo $catInsert;
        }                       
    ?>
    <form action="" method="post">
                    <table class="form">					
                        <tr>
                            <td>
                                <input type="text" name="catName" class='form-control'placeholder="Enter Category Name..." class="medium" />
                            </td>
                        </tr>
						<tr> 
                            <td>
                            <input type="submit" name="submit" class="btn btn-sm save-btn" value="Save"> || 
                            <a class="btn btn-sm home-btn" href="cat_list.php">Category List</a> || 
                            <a class="btn btn-sm dash-btn" href="../admin.php">Dashboard</a>
                            </td>
                        </tr>
                    </table>
                    </form>
                </div>
            </div>
        </div>
        <?php include '../components/footer.php';?>
        <?php include '../components/bootjs.php';?>
</body>
</html>