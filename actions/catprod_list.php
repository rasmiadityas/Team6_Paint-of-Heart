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
	$cat = new Category();
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
	<title>Grouped Product - Paint Of Heart</title>
	</head>
<div class="grid_10">
    <div class="box round first grid">
        <h2>Product by Category List</h2>
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

	<?php // get category list for select input
			$getCat = $cat->catSelect();
			$data = $getCat->fetch_all(MYSQLI_ASSOC);
			if (is_array($data)) {
				$catselect= "";
				foreach ($data as $data) {
					$catselect.= "<option value='".$data['id']."'>".$data['catName']."</option>";
				}
			} else {
				
			}


				?>

<!-- Select category dropdown  -->
	<div class="d-flex justify-content-center align-items-center container my-3">
    <div class="row ">
		<label for="category" class="control-label fw-bold">Category</label>
			<select class=" form-select" name="category" id="category" onchange="getCat(this)">
	<option value='0'>All</option> 
				<?php echo $catselect; ?>
                        </select>
		
	</div>
	</div>

	<div id="info" class="my-3 p-3">
        <b>Search results</b>
    </div>

 
    </div>
    </div>
</div>

<script type="text/javascript">
let out = document.querySelector('#info');
console.log(out);

function getCat(selectObject) {
  var value = selectObject.value;  
  console.log(value);

  let params=`category=${value}`; //creating the parameters for the POST method
  console.log(params);

  let request=new XMLHttpRequest (); //creating new request
            request.open("POST", "catprod_get.php" ,true); //connecting to the php file of process
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded"); //setting header for POST method
            request.onload=function(){
                if(this.status== 200){
                    console.log(this.responseText);
					
					let data = JSON.parse(this.responseText);
					console.log(data);
					let product = data.data;
					console.log(product);

					if (product != 0) { // yes data
						htmlBuilder = "<table class='table' id='example'><thead><tr><th scope='col'>#/ID</th><th scope='col'>Product Name</th><th scope='col'>Description</th><th scope='col'>Base Price</th><th scope='col'>End Price</th><th scope='col'>Image</th><th scope='col'>Discount</th><th scope='col'>Visibility</th></tr></thead><tbody>";

						for (let i = 0; i < product.length; i++) {
							let j = i+1;
						let id = `${product[i].id}`;
						let name = `${product[i].name}`;
						let price = `${product[i].price}`;
						let description = `${product[i].description}`;
						let discount = `${product[i].discount}`;
						let visibility = `${product[i].visibility}`;
						let image = `${product[i].image}`;
						let endprice = (price*((100-discount)/100)).toFixed(2);
						
						htmlBuilder += "<tr>";
       htmlBuilder += "<td>"+ j + "/" + id + "</td>";
       htmlBuilder += "<td>" + name + "</td>";
       htmlBuilder += "<td>" + description + "</td>";
       htmlBuilder += "<td>" + price + " €</td>";
       htmlBuilder += "<td>" + endprice + " €</td>";
	   htmlBuilder += "<td><img src='" + image + "' height='50px' width='70px'></td>";
	   htmlBuilder += "<td>" + discount + "%</td>";
	   htmlBuilder += "<td>" + visibility + "</td>";
       htmlBuilder += "</tr>";
						
					}
					htmlBuilder += "</tbody></table>";
					console.log(htmlBuilder);
					out.innerHTML = htmlBuilder;
					
					} else { // no data
						out.innerHTML = "There is no data<br>";
					}
            } else {

			}
            }
            request.send(params); //send parameters to be further processed by php
}

    $(document).ready(function () {
        setupLeftMenu();
        $('.datatable').dataTable();
		setSidebarHeight();
    });
</script>
<?php include '../components/footer.php';?>