<?php
include_once('autoload.php');
class Product
{
	public $db;
	private $fm;

	public function __construct()
	{
		$this->db = new Database();
		$this->fm = new Format();
	}
	// --------------product add--------------------------------------------
	public function productInsert($data, $file)
	{  //$data = $_POST  $file = $_FILES
		$name                   = $this->fm->validation($data['name']);
		$fk_category		    = $this->fm->validation($data['fk_category']);
		$price 		       		= $this->fm->validation($data['price']);
		$description 		    = $this->fm->validation($data['description']);
		$discount      		    = $this->fm->validation($data['discount']);

		// $visibility 		    = $this->fm->validation($data['visibility']);


		$name         = mysqli_real_escape_string($this->db->link, $data['name']);
		$fk_category  = mysqli_real_escape_string($this->db->link, $data['fk_category']);
		$description  = mysqli_real_escape_string($this->db->link, $data['description']);
		$price        = mysqli_real_escape_string($this->db->link, $data['price']);
		$discount     = mysqli_real_escape_string($this->db->link, $data['discount']);
		// $visibility   = mysqli_real_escape_string($this->db->link, $data['visibility']);

	     	$permited  = array('jpg', 'jpeg', 'png', 'gif');
			$file_name = $file['image']['name'];
			$file_size = $file['image']['size'];
			$file_temp = $file['image']['tmp_name'];

			$div 			= explode('.', $file_name);
			$file_ext 		= strtolower(end($div));
			$unique_image 	= substr(md5(time()), 0, 10) . '.' . $file_ext;
			$uploaded_image = "../pictures/".$unique_image;

		if ($name == "" || $fk_category == "" || $description == "" || $price == "") {
			// || $discount == "" || $file_name == ""
			$msg = "<span class='error'>Field must not be empty!</span>";
			return $msg;
		}

		if (empty($file['image']['name'])){
			$uploaded_image = "../pictures/product.png";
			$file_temp = "";
			$query = "INSERT INTO
			 `products`(`id`,
			 `name`,
			 `fk_category`,
			 `price`,
			 `description`,
			  `image`,
			  `discount`,
			  `visibility`
			  ) VALUES 
			  (NULL,
			   '$name',
			    '$fk_category',
				'$price',
				'$description',
				'$uploaded_image',
				'$discount',
				1)";
			$inserted_row = $this->db->insert($query);
			if ($inserted_row) {
				$msg = "<span class='success'>Data inserted successfully !</span>";
				return $msg;
			} else {
				$msg = "<span class='error'>Data not inserted !</span>";
				return $msg;
			}
			$uploaded_image = "";
		} else {
			
			if (!empty($file_name)){

			if ($file_size > 7048567) {
				$msg = "<span class='error'>Image Size should be less then 7MB!
	     	    </span>";
				return $msg;
			}elseif (in_array($file_ext, $permited) === 'false') {
				$msg = "<span class='error'>You can upload only:-"
					. implode(', ', $permited) . "</span>";
				return $msg;
			} else {
				move_uploaded_file($file_temp, $uploaded_image);
				$query = "INSERT INTO `products`(
					`id`,
					`name`,
					`fk_category`,
					`price`,
					`description`,
					 `image`,
					 `discount`,
					 `visibility`
					 ) VALUES
					  (
						 NULL,
						  '$name',
						   '$fk_category',
						   '$price',
						   '$description',
						   '$uploaded_image',
						   '$discount',
						   1)";
				$inserted_row = $this->db->insert($query);
				if ($inserted_row) {
					$msg = "<span class='success'>Data inserted successfully !</span>";
					return $msg;
				} else {
					$msg = "<span class='error'>Data not inserted !</span>";
					return $msg;
				}
			}

			$uploaded_image = "";
		}
	}
}

// ---------------------------get all Product---------------------

public function getAllProduct()
{
	$query = "SELECT `products`.*,`category`.`catName` FROM `products`
		INNER JOIN `category`
		ON `products`.`fk_category` = `category`.`id`
	 	ORDER BY `products`.`id` DESC";
		$selected_row = $this->db->select($query);
		return $selected_row;
	}
	
	// -----------------get details about  (1)Product---------------------

	public function getAllProductById($id)
	{
		$query = "SELECT * FROM `products` WHERE `id` = '$id' ";
		$selected_row = $this->db->select($query);
		return $selected_row;
	}

	// -----------------get details about  Product by category---------------------

	public function getAllProductByCat($catid)
	{
		$query = "SELECT * FROM `products` WHERE `fk_category` = '$catid' ";
		$selected_row = $this->db->select($query);
		return $selected_row;
	}
	
	//-------------------------------------------------------------------------

	public function updateProduct($data, $file, $id)
	{
		$name          = $this->fm->validation($data['name']);
		$fk_category   = $this->fm->validation($data['fk_category']);
		$description   = $this->fm->validation($data['description']);
		$price 		   = $this->fm->validation($data['price']);
		$discount 	   = $this->fm->validation($data['discount']);
		$visibility 		    = $this->fm->validation($data['visibility']);


		// $visibility 		    = $this->fm->validation($data['visibility']);

		$name            = mysqli_real_escape_string($this->db->link, $data['name']);
		$fk_category 	 = mysqli_real_escape_string($this->db->link, $data['fk_category']);

		$description     = mysqli_real_escape_string($this->db->link, $data['description']);
		$price           = mysqli_real_escape_string($this->db->link, $data['price']);
		$discount        = mysqli_real_escape_string($this->db->link, $data['discount']);
		$visibility   = mysqli_real_escape_string($this->db->link, $data['visibility']);

		$permited  = array('jpg', 'jpeg', 'png', 'gif');
		$file_name = $file['image']['name'];
		$file_size = $file['image']['size'];
		$file_temp = $file['image']['tmp_name'];

		$div 			= explode('.', $file_name);
		$file_ext 		= strtolower(end($div));
		$unique_image 	= substr(md5(time()), 0, 10) . '.' . $file_ext;
		$uploaded_image = "../pictures/" . $unique_image;

		if ($name == "" || $fk_category == "" || $description == "" || $price == "") {
			$msg = "<span class='error'>Field must not be empty!</span>";
			return $msg;
		}

		if (empty($file['image']['name'])) {
			// $uploaded_image = "../pictures/product.png";
			$file_temp = "";

			$query = "UPDATE `products` SET 
            `name`            = '$name',
            `fk_category` 	  = '$fk_category',
            `description`     = '$description',
            -- `image`           = '$uploaded_image',
            `Price`           = '$price',
            `discount`        = '$discount',
			`visibility`      = '$visibility'

            WHERE `id` = '$id' ";
			$updated_row = $this->db->update($query);


			if ($updated_row) {
				$msg = "<span class='success'>Data inserted successfully !</span>";
				return $msg;
			} else {
				$msg = "<span class='error'>Data not updated !</span>";
				return $msg;
			}
			$uploaded_image = "";
		} else {
			if (!empty($file_name)){
				if ($file_size > 7048567) {
					$msg = "<span class='error'>Image Size should be less then 7MB!
			     	</span>";
					return $msg;
				} elseif (in_array($file_ext, $permited) === 'false') {
					$msg = "<span class='error'>You can upload only:-"
						. implode(', ', $permited) . "</span>";
					return $msg;
				} else {
					move_uploaded_file($file_temp, $uploaded_image);
					$query = "UPDATE `products` SET 
			    	`name`            = '$name',
			    	`fk_category` 	  = '$fk_category',
			    	`description`     = '$description',
			    	`Price`           = '$price',
			    	`image`           = '$uploaded_image',
			    	`discount`        = '$discount',
			        `visibility`      = '$visibility'

			        WHERE `id` = '$id' ";
					$updated_row = $this->db->update($query);
					if ($updated_row) {

						$msg = "<span class='success'>Data updated successfully !</span>";

						return $msg;
					} else {
						$msg = "<span class='error'>Data not updated !</span>";
						return $msg;
					}
				}
			}
		} //first else
	} //class end
	// --------------------------delete product---------------------------
	public function delProductById($id)
	{
		// $query = "SELECT * FROM `products` WHERE `id` = '$id'";
		// $result = $this->db->select($query);
		// if ($result) {
		// 	while ($product = $result->fetch_assoc()) {
		// 		$delImg = $product['image'];
		// 		unlink($delImg);
		// 	}
		// }
		$query = "DELETE FROM `products` WHERE `id` = '$id' ";
		$deleted = $this->db->delete($query);
		if ($deleted) {
			$msg = "<div class='alert alert-success  text-center fs-4'>Data deleted successfully !</div>";
			return $msg;
		} else {
			$msg = "<div class='alert alert-success  text-center fs-4'>Data not deleted !</div>";
			return $msg;
		}
	}
	// -----------------------------------------------------

	public function getFeaturedProduct()
	{
		$query = "SELECT * FROM `products` WHERE `discount` > 0 ORDER BY `id` DESC LIMIT 4";
		$result = $this->db->select($query);
		return $result;
	}
	
	public function getSingleProductById($id)
	{
		 $query = "SELECT `products`.*, `category`.`catName` FROM `products`
				INNER JOIN `category` ON `products`.`fk_category` = `category`.`id`
			   WHERE `products`.`id` = '$id' ";

		$result = $this->db->select($query);
		return $result;
	}
		    //------------------ get_searchingproduct -------------------//

			public function get_product($id){
				// $sql= "select * from products where id = '$id' limit 1";
				$sql= "select p.*,c.catName from products p join category c on p.fk_category=c.id where p.id = '$id' limit 1";
				$DB = new Database();
				$result= $DB->select($sql);
				if ($result){
					return $result;
				}else{
					return false;
				}
			}


			public function saveWishListData($id,$userId){
			
				$checkQuery = "SELECT * FROM `tbl_wish` WHERE `fk_userId`= '$userId' AND `fk_productId` = '$id' ";
				$result = $this->db->select($checkQuery);
				if ($result) {
				// $msg = 'Product Already Added !';
				$msg="<div class='alert alert-warning text-center fs-4'>Item already added to Wishlist!</div>";
				return $msg;
				}

				$query = "SELECT * FROM `products` WHERE `id` = '$id' " ;
		 	   $result = $this->db->select($query)->fetch_assoc();
			   if ($result) {
			
				$productId   = $result['id'];
				$productName = $result['name'];
				$price       = $result['price'];  //quantity*price
				$image       = $result['image'];

					$query = "INSERT INTO `tbl_wish` (`fk_userId`, `fk_productId`, `productName`, `price`, `image`) VALUES ('$userId', '$productId', '$productName','$price', '$image') ";
					$inserted_row = $this->db->insert($query);

					if ($inserted_row) {
						$msg ="<div class='alert alert-success text-center fs-4'>Yay! Item added to your Wishlist !</div>";
						return $msg;
					} else {
						$msg ="<div class='alert alert-warning text-center'>Error, nothing has been added!</div>";
						return $msg;
					}
				}
			}

			public function delWishListData($productId,$userId){

				$query = "DELETE FROM `tbl_wish` WHERE `fk_userId` = '$userId' 
				AND `fk_productId`='$productId' ";
				$deleted = $this->db->delete($query);
			  if ($deleted) {
			
				 $msg=" <div class='alert alert-success text-center'>Item removed from your Wishlist!</div>";
				
				return $msg;
			  }else{
				$msg = "<span class='error'>Item not deleted!</span>";
				  return $msg;
			  }
			}
		
			public function checkWlist($id){

				if(isset($_SESSION['adm'])){

				  $id=$_SESSION['adm']['id'];
				}
				if(isset($_SESSION['user'])){

				 $id=$_SESSION['user']['id'];

				}

				$query = "SELECT * FROM `tbl_wish` 
				JOIN `products` ON `tbl_wish`.`fk_productId`=`products`.`id` 
				JOIN `category` ON `category`.`id`=`products`.`fk_category` 
				 WHERE `fk_userId` = '$id' ORDER BY wishId DESC ";
						
				$result = $this->db->select($query);
				// print_r($result);
				return $result;
			}
 }
?>