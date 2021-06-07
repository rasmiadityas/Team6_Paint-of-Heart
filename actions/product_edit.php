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
    if (!isset($_GET['productId']) || $_GET['productId'] == 'NULL') {
        echo '<script> window.location = "product_list.php"; </script>';
    }else{
        // $id = $_GET['productId'];
        $id = preg_replace('/[^-a-zA-Z0-9_]/', '', $_GET['productId']);
    }
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {  //as many file
    $updateProduct = $pd->updateProduct($_POST, $_FILES, $id);
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
<title>Product Edit - Paint of Heart</title>	
</head>    
<body>        
<div class="grid_10">
    <div class="box round first grid">
        <h2>Update Product</h2>
        <div class="block">   
    <?php 
        if (isset($updateProduct)) {
            echo $updateProduct;
        }
        $getData = $pd->getAllProductById($id);
        if ($getData) {
            while ($product = $getData->fetch_assoc()) {
        ?>
         <form action="" method="post" enctype="multipart/form-data">
            <table class="form">
                <tr>
                    <td>
                        <label>Name</label>
                    </td>
                    <td>
                        <input type="text" name="name" value="<?php echo $product['name'] ?>"class='form-control'/>
                    </td>
                </tr>
				<tr>
                    <td>
                        <label>Category</label>
                    </td>
                    <td>
                        <select id="select" class='form-control' name="fk_category">
                            <option>Select Category</option>
            <?php 
                $cat = new Category();
                $getData = $cat->catSelect();
                if ($getData) {
                    while ($category = $getData->fetch_assoc()) { ?>
                        <option     
                            <?php
                            if ($product['fk_category'] == $category['id']){ ?>
                                    selected=""
                            <?php } ?>
                            value="<?php echo $category['id'] ?>">
                          <?php echo $category['catName'] ?>
                        </option>  
            <?php } } ?>
        </select>
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align: top; padding-top: 9px;">
                        <label>Description</label>
                    </td>
                    <td>
                        <textarea  class='form-control' name="description">
                            <?php echo $product['description'] ?>
                        </textarea>
                    </td>
                </tr>
				<tr>
                    <td>
                        <label>Price</label>
                    </td>
                    <td>
                        <input type="text" class='form-control' name="price" value="<?php echo $product['price'] ?>" />
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>Upload Image</label>
                    </td>
                    <td>
                        <img src="<?php echo $product['image'] ;?>" height="80px" width="200px">
                        <input class='form-control' type="file" name="image" />
                    </td>
                </tr>
				<tr>
                    <td>
                        <label>Discount</label>
                    </td>
                    <td>
                        <input type="number" name="discount" value="<?php echo $product['discount'] ?>" placeholder="Enter Descount" class='form-control'/>
                    </td>
                </tr>
				<tr>
                    <td>
                        <label>Visibility</label>
                    </td>
                    <td>
                        <input type="number" name="visibility" value="<?php echo $product['visibility'] ?>" placeholder="visibility" class='form-control'/>
                    </td>
                </tr>
				<tr>
                    <td></td>
                    <td>
                    <input type="submit" name="submit" class="btn btn-sm save-btn" value="Save"> || 
                    <a class="btn btn-sm home-btn" href="product_list.php">Product List</a> || 
                    <a class="btn btn-sm dash-btn" href="../admin.php">Dashboard</a>
                    </td>
                </tr>
            </table>
            </form>
<?php  } } ?>
        </div>
    </div>
</div>
<?php include '../components/footer.php';?>
<!-- Load TinyMCE -->
<script src="js/tiny-mce/jquery.tinymce.js" type="text/javascript"></script>
<script type="text/javascript">
    $(document).ready(function () {
        setupTinyMCE();
        setDatePicker('date-picker');
        $('input[type="checkbox"]').fancybutton();
        $('input[type="radio"]').fancybutton();
    });
</script>
<?php include_once 'components/bootjs.php'; ?>
</body>
</html>