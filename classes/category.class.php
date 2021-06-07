<?php
  include_once ('autoload.php') ;
?>

<?php 
  class Category{
    private $db;
    private $fm;

    public function __construct(){
      $this->db = new Database();
      $this->fm = new Format();
    }

    public function catInsert($catName){
      $catName = $this->fm->validation($catName);
      $catName = mysqli_real_escape_string($this->db->link, $catName);

      if ($catName == "") {
        $msg = "<span class='error'>Field must not be empty!</span>";
        return $msg;
      }
      else{
      	$query = "INSERT INTO `category` (`catName`) VALUES('$catName')";
      	$result = $this->db->insert($query);
      	if ($result) {
      		$msg = "<span class='success'>Category inserted successfully!</span>";
        	return $msg;
      	}else{
      		$msg = "<span class='error'>Category not inserted!</span>";
        	return $msg;
      	}
      }
  }

  public function catSelect(){
  	$query = "SELECT * FROM `category` ORDER BY `id` DESC ";
  	$selected_row = $this->db->select($query);
  	return $selected_row;
  }

  public function getCatById($id){  //get value
  	$query = "SELECT * FROM `category` WHERE `id` = '$id' ";
  	$selected_row = $this->db->select($query);
  	return $selected_row;
  }

  public function catUpdate($catName, $id){
  	 $catName = $this->fm->validation($catName);
     $catName = mysqli_real_escape_string($this->db->link, $catName);
     $id 	    = mysqli_real_escape_string($this->db->link, $id);

      if ($catName == "") {
        $msg = "<span class='error'>Field must not be empty!</span>";
        return $msg;
      }
      else{
      	$query = "UPDATE `category` SET `catName` = '$catName' WHERE `id` = '$id' ";
      	$updated_row = $this->db->update($query);
      	if ($updated_row) {
      		$msg = "<span class='success'>Category updated successfully!</span>";
        	return $msg;
      	}else{
      		$msg = "<span class='error'>Category not updated!</span>";
        	return $msg;
      	}
      }
  	
  }

  public function delCatById($id){
  		$query = "DELETE FROM `category` WHERE `id` = '$id' ";
      	$deleteData = $this->db->delete($query);
      	if ($deleteData) {
      		$msg = "<span class='error'>Category deleted successfully!</span>";
        	return $msg;
      	}else{
      		$msg = "<span class='error'>Category not deleted!</span>";
        	return $msg;
      	}
  }


}
?>