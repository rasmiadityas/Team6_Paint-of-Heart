<?php
  include_once ('autoload.php') ;
    
  class Cart{
    private $db;
    private $fm;

    public function __construct(){
      $this->db = new Database();
      $this->fm = new Format();
    }

    public function addToCart($quantity, $id){
      $quantity  = $this->fm->validation($quantity);
      $quantity  = mysqli_real_escape_string($this->db->link, $quantity);
      $productId = mysqli_real_escape_string($this->db->link, $id);
     
      if(isset($_SESSION['adm'])){

        $userId=$_SESSION['adm']['id'];
      }
      if(isset($_SESSION['user'])){

       $userId=$_SESSION['user']['id'];

      }


      //every pc has unique session_id like 3b4t0onf4a5ch39f6cu5tpatlr;

      $query = "SELECT * FROM `products` WHERE `id` = '$productId' ";
      $result = $this->db->select($query)->fetch_assoc();

      //   echo"<pre>";
      //   print_r($result);
      //   echo"</pre>";

      $productName = $result['name'];
      $image = $result['image'];
      $price = $result['price'];
      if ($result['discount'] > 0) { // check for discount
        $price = $price * ((100 - $result['discount'])/100);
        
    }

      $checkQuery = "SELECT * FROM `tbl_cart` WHERE `productId` = '$productId' AND `userId` = '$userId' ";
      $result = $this->db->select($checkQuery);
      if ($result) {
        // $msg = 'Product Already Added !';
        $msg="<div class='alert alert-warning text-center'>Product Already Added!</div>";
        return $msg;
      }else{
          $query = "INSERT INTO `tbl_cart` (`userId`, `productId`, `productName`, `price`, `quantity`, `image`) VALUES ('$userId', '$productId', '$productName', '$price', '$quantity', '$image') ";
          $inserted_row = $this->db->insert($query);
          if ($inserted_row) {
              // header("location:actions/cart_list.php");
            //   header("location:details.php");
              $msg="<div class='alert alert-success text-center'>Product has been added to your cart!</div>";
              return $msg;
          }else{
            header("location: 404.php");
          } 
      }
  }

  public function getCartProductById(){
    $userId = $_SESSION['user']['id'];
    $query = "SELECT * FROM   `products` JOIN `tbl_cart`
    ON `products`.`id` = `tbl_cart`.`productId`  WHERE `userId` = '$userId' " ;
    $result = $this->db->select($query);
    return $result;
  }

  public function updateCartQuantity($cartId, $quantity){
     $quantity  = $this->fm->validation($quantity);


    if($quantity==0){

    $quantity  = mysqli_real_escape_string($this->db->link, $quantity);
    $cartId    = mysqli_real_escape_string($this->db->link, $cartId);

     $query = "UPDATE `tbl_cart` SET `quantity` = '$quantity' WHERE `cartId` = '$cartId' ";
     $updated_row = $this->db->update($query);

    //  $msg="<div class='alert alert-success text-center'>Quantity updated</div>";
    if($updated_row){
        $msg="<div class='alert alert-danger text-center'>Product is deleted</div>";
        return $msg;
    }else{
        $msg="<div class='alert alert-success text-center'>Something Is Wrong !</div>";
        return $msg;
     }
    }

      else {


        $quantity  = mysqli_real_escape_string($this->db->link, $quantity);
        $cartId    = mysqli_real_escape_string($this->db->link, $cartId);
    
         $query = "UPDATE `tbl_cart` SET `quantity` = '$quantity' WHERE `cartId` = '$cartId' ";
         $updated_row = $this->db->update($query);


            $msg="<div class='alert alert-success text-center'>Quantity updated</div>";
            return $msg;
      } 
      
  }

  public function deleteCartById($delCart){
      $delCart  = mysqli_real_escape_string($this->db->link, $delCart);
      $query = "DELETE FROM `tbl_cart` WHERE `cartId` = '$delCart' ";
      $deleted = $this->db->delete($query);
    if ($deleted) {
      echo "<script>window.location ='cart_list.php'; 
      <div class='alert alert-success text-center'>Cart deleted successfully!</div>
      </script>";
    //   $msg="";
    //   return $msg;
    }else{
      $msg = "<div class='alert alert-danger'>Cart not deleted!</div>";
        return $msg;
    }
  }

  //  -------------------- looking leter ---------- 
  public function checkCartTable(){
    $userId = $_SESSION['user']['id'];
    $query = "SELECT * FROM `tbl_cart` WHERE `userId` = '$userId'" ;
    $result = $this->db->select($query);
    return $result;
  }

  // logout korle cart delete hoye jabe
  public function delCustomerCart(){
    $userId = $_SESSION['user']['id'];
    $query = "DELETE FROM `tbl_cart` WHERE `userId` = '$userId' ";
    $this->db->delete($query);
  }

  public function orderProduct($customerId){
    $query = "SELECT * FROM `tbl_cart` WHERE `userId` = '$customerId' " ;
    $resultcart = $this->db->select($query);
    $content = '';
    
    if ($resultcart) {
      // SQL: make new ID entry in order_register table
      $tot_price = 0;    
      $query = "SELECT MAX(id) as res FROM order_register ";
      $result = $this->db->select($query);
      $data = $result->fetch_assoc();
      $out = $data['res'];
      $regID = $out+1;
      $date=date("Y-m-d H:i:s");
      $query = "INSERT INTO `order_register` (`id`, `fk_user_id`, `tot_price`, `date`) VALUES ('$regID','$customerId', '$tot_price','$date') ";
      $inserted_row = $this->db->insert($query);

      while ($cart   = $resultcart->fetch_assoc()) { // SQL: insert products from tbl_cart to tbl_order
        $productId   = $cart['productId'];
        $productName = $cart['productName'];
        $quantity    = $cart['quantity'];
        $price       = $cart['price'] * $quantity;  //quantity*price
        $image       = $cart['image'];
        $tot_price = $tot_price+$price;
        
        $query = "INSERT INTO `tbl_order` (`fk_userId`, `fk_productId`, `productName`, `quantity`, `price`, `image`, `regID`) VALUES ($customerId,$productId, '$productName', $quantity,$price, '$image', $regID) ";
        $inserted_row = $this->db->insert($query);
        $content .= '<tr>
        <td width="10%" style="text-align: center;">'.$productId.'</td>
        <td width="30%" style="text-align: center;">'.$productName.'</td>
        <td width="10%" style="text-align: center;">'.$quantity.'</td>
        <td width="20%" style="text-align: center;">'.$price.' EURO</td>
        <td width="30%" style="text-align: center;">'.date('Y-m-d H:i:s').'</td>
        </tr>';
      }

      // SQL: update tot_price in order_register table
      $query = "UPDATE order_register 
      SET tot_price = $tot_price WHERE id = {$regID}";
      $inserted_row = $this->db->insert($query);

      // EMAIL: send order confirmation email to customer
      $to = $_SESSION['user']['email'];
      $subject = 'Thank you for your order at "Paint of Heart"!';
      $message = '
          <html>
          <head>
          <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
              <title></title>
          </head>
          <body style="background: #FFFFFF;">
          <div style="margin-left: auto; margin-right: auto;">
              <h1 style="text-align:  center;">Thank you for your order '.$_SESSION['user']['f_name'].' '.$_SESSION['user']['l_name'].'</h1>
              <div style="background: #151515; color: #FFF;">
              <div>
                <table>
                <tr>
                  <th width="10%">No.</th>
                  <th width="30%">Product Name</th>
                  <th width="10%">Quantity</th>
                  <th width="20%">Price</th>
                  <th width="30%">Date of purchase</th>
                </tr>
                '.$content.'
                <tr style="text-align: center">
                  <td></td>
                  <td></td>
                  <td>Total:</td>
                  <td>'.$tot_price.' EURO</td>
                  <td></td>
                </tr>
                </table>
          </div>
          </body>
          </html>
              ';
  
      $from = "admin@paintofheart.com";
      $Bcc = "rakib0751@gmail.com";
  
      // To send HTML mail, the Content-type header must be set
      $headers  = 'MIME-Version: 1.0' . "\r\n";
      $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
  
      // // Additional headers
      // $headers .= 'To: ' .$to. "\r\n";
      // $headers .= 'From: ' .$from. "\r\n";
         $headers .= 'Bcc: '.$Bcc. "\r\n";
  
      // Send the email
      mail($to,$subject,$message,$headers);
    }
  }

  public function getOrderRegID($customerId){
    $query = "SELECT MAX(id) as res FROM `order_register` WHERE `fk_user_id` = {$customerId}";
    $result = $this->db->select($query);
    $data = $result->fetch_assoc();
            $res = $data['res'];
    return $res;
  }

  public function payableAmount($customerId,$regID){
    $query = "SELECT `price` FROM `tbl_order` WHERE `fk_userId` =  {$customerId} AND `regID` =   {$regID}";
    $result = $this->db->select($query);
    return $result;
  }

  public function getOrderDetails($customerId){
    $query = "SELECT *, `category`.`catName`
    FROM `tbl_order` 
    INNER JOIN `products` ON `products`.`id` = `tbl_order`.`fk_productId`
    INNER JOIN `category` ON `products`.`fk_category` = `category`.`id`
    WHERE `fk_userId` = '$customerId' 
    ORDER BY `date` DESC ";
    $result = $this->db->select($query);
    return $result;
  }

  public function checkOrderProduct($customerId){
    $query = "SELECT * FROM `tbl_order` WHERE `fk_userId` = '$customerId' ";
    $result = $this->db->select($query);
    return $result;
  }

  // For Admin Panel inbox
  public function getAllOrderProduct(){
     $query = "SELECT * FROM `tbl_order` ORDER BY `date` DESC ";
     $result = $this->db->select($query);
     return $result;
  }

  public function productShifted($id, $price, $date){
     $id    = $this->fm->validation($id);
     $price = $this->fm->validation($price);
     $date  = $this->fm->validation($date);

     $id    = mysqli_real_escape_string($this->db->link, $id);
     $price = mysqli_real_escape_string($this->db->link, $price);
     $date  = mysqli_real_escape_string($this->db->link, $date);

     $query = "UPDATE `tbl_order`
        SET `status` = '1' 
        WHERE `fk_userId` = '$id' AND `price` = '$price' AND `date` = '$date' ";
      $updated_row = $this->db->update($query);
      if ($updated_row) {
        $msg = "<span class='success'> Updated successfully!</span>";
        return $msg;
      }else{
        $msg = "<span class='error'> Not updated!</span>";
        return $msg;
      }
  }//function end

  // delete shifted product from admin panel
  public function deleteShiftedProduct($id, $price, $date){
    $query = "DELETE FROM `tbl_order`  WHERE `fk_userId` = '$id' AND `price` = '$price' AND `date` = '$date' ";
      $deleted = $this->db->delete($query);
    if ($deleted) {
      $msg = "<div class='container alert alert-success text-center fs-4 '>Data Deleted Successfully !</div>";
        return $msg;
    }else{
      $msg = "<span class='error'>Data not deleted!</span>";
        return $msg;
    }
  }

  //confirm product shifted by customer
  public function confirmProductShifted($id, $price, $date){
    $id     = $this->fm->validation($id);
     $price = $this->fm->validation($price);
     $date  = $this->fm->validation($date);

     $id    = mysqli_real_escape_string($this->db->link, $id);
     $price = mysqli_real_escape_string($this->db->link, $price);
     $date  = mysqli_real_escape_string($this->db->link, $date);

     $query = "UPDATE `tbl_order`
        SET `status` = '2' 
        WHERE `fk_userId` = '$id' AND `price` = '$price' AND `date` = '$date' ";
      $updated_row = $this->db->update($query);    
    }//function end
//--------------------------
}
?>