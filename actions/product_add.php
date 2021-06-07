<?php 
ob_start();
include '../classes/autoload.php' ;

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
    if ($_SERVER['REQUEST_METHOD'] == 'POST'){  //as many file
    $productInsert = $pd->productInsert($_POST, $_FILES);
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
	<title>Product Add - Paint of Heart</title>
</head>
<body>
<div class="grid_10">
    <div class="box round first grid">
        <h2>Add New Product</h2>
        <div class="block">   
    <?php 
        if (isset($productInsert)) {
            echo $productInsert;
        }
     ?>
         <form action="" method="post" enctype="multipart/form-data">
            <table class="form">
                <tr>
                    <td>
                        <label>Name</label>
                    </td>
                    <td>
                        <input type="text" name="name" placeholder="Enter Product Name..." class='form-control' />
                    </td>
                </tr>
				<tr>
                    <td>
                        <label>Category</label>
                    </td>
                    <td>
                        <select  id="select" name="fk_category" style="width:100%">
                            <option>Select Category</option>
            <?php 
                $cat = new Category();
                $getData = $cat->catSelect();
                if ($getData) {
                    while ($category = $getData->fetch_assoc()) { ?>
                        <option value="<?php echo $category['id'] ?>"><?php echo $category['catName'] ?></option>                     
            <?php } } ?>
                    </select>
                    </td>
                </tr>
				 <tr>
                    <td style="vertical-align: top; padding-top: 9px;">
                        <label>Description</label>
                    </td>
                    <td>
                        <textarea class='form-control' name="description" placeholder="Short Description"></textarea>
                    </td>
                </tr>
				<tr>
                    <td>
                        <label>Price</label>
                    </td>
                    <td>
                        <!-- <input type="number" name="price" placeholder="Enter Price..." class="medium" /> -->
                        <input class='form-control' type="number" 
                        min="0" step="any"name="price" placeholder="Price"/>
                    </td>
                </tr>
				<tr>
                    <td>
                        <label>Discount</label>
                    </td>
                    <td>
                        <input type="number" name="discount" placeholder="Enter Discount" class='form-control'/>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>Upload Image</label>
                    </td>
                    <td>
                        <input type="file" class='form-control' name="image" />
                    </td>
                </tr>
            </table>
            <input type="submit" name="submit" class="btn btn-sm save-btn" value="Save"> || 
                    <a class="btn btn-sm home-btn" href="product_list.php">Product List</a> || 
                    <a class="btn btn-sm dash-btn" href="../admin.php">Dashboard</a>
            </form>
        </div>
    </div>
</div>
<?php include '../components/footer.php';?>
<?php include_once 'components/bootjs.php'; ?>
</body>
</html>

