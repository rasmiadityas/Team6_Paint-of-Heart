<?php 
ob_start();
include ("classes/autoload.php");
Session::init();
// without login can't access
$pd=new Product();
$id=$_SESSION['user']['id'];
if (!isset($_SESSION['user']) && !isset($_SESSION['adm'])) { // general  cannot see
    header("Location: home.php");
    exit;    
}
if(isset($_GET['delwlist'])){
    
    $wishId = preg_replace('/[^-a-zA-Z0-9_]/', '', $_GET['delwlist']);
// if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delwlist'])) {
      
    $delWlist= $pd->delWishListData($wishId,$id);
// }
}
$card="";  
  $getPd=$pd->checkWlist($id);
if ($getPd){
//    if($results){
//     $count=mysqli_num_rows($results);
//     if($count==false){
//     $output="There is no search results";
//     }else{
      while($row=$getPd->fetch_assoc()){
        $price = 0;
        $dPrice = 0;
        $discount = 0;
        $priceRounded = 0;
          $id=$row['wishId'];
          $Pname=$row['productName'];
          $catName=$row['catName'];
        $card .= "
        <div class='mx-auto col-md-6 col-lg-4 col-xl-3'>
        <div class='card h-100 w-100 rounded-3'>
        <a href='details.php?id=" . $row['fk_productId'] . "'>
        <div class='ownImgDiv'> 
            <img class='card-img-top ownPro' src='pictures/" . $row['image'] . "' alt='Card image cap'>
              </div></a>
                <div class='card-body'>
                    <h5 class='card-title'>" . $row['name'] . "</h5>
                    <p class='card-text'>Category: " . $row['catName'] . "</p>                 
                    ";                  
                    if($row['discount']>0){
                      $discount=$row['discount'];
                      $price=$row['price'];
                      $dPrice=$price*((100-$discount)/100);
                      $priceRounded = round($dPrice, 2);               
                      $card .="<p class='card-text fs-5'>Original Price: ".$price." €</p>
                      <p class='card-text text-danger fw-bold'>Discount: " . $row['discount']." % <img class='rounded-pill' style='width:70px; height:60px;' src='pictures/offered.png' alt='Card image cap'> </p>
                      <p class='card-text text-success fs-4'>Price: ".$priceRounded." €</p>";
                    }else {
                        $card.="<p class='card-text fs-4'>Price: " . $row['price'] . " €</p>";                      
                    }               
                    $card .= "                   
                    </div>                   
                    <div class='card-footer border-none text-center'>
                    <a href='details.php?id=".$row['fk_productId']."' class='btn nav-btn rounded-pill'>Add to Cart<img class='class='rounded-pill' style='width:30px; height:30px;' src='pictures/cart.png' alt='Card image cap'></a>                  
                    <a class= 'btn save-btn btn-sm mx-1 rounded-pill' onclick='return confirm('Are you sure?')' href='?delwlist=".$row['fk_productId']." '>Remove &#x2661;</a>
                    </div>
                    </div>
                    <div class='text-center'>
                    <a href='products.php' class='btn home-btn text-light'>Go Back</a>
                    </div>
                    </div>";
                  }
    } else {
     $card='<div class="card-footer border-none text-center"> 
     <div class="card-body">
       <h5 class="card-title">Sorry,</h5>
       <p class="card-text"> There is nothing on your wishlish yet</p> 
       <a href="products.php" class="btn home-btn">Go Back</a>
     </div> 
   </div>';  
}
    //  echo"<pre>";
    // print_r($results);
    // echo"</pre>";    
//   }
?>
<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap 5 CSS bundle  -->
    <?php include_once 'components/boot.php'; ?>
    <link rel="stylesheet" href="components/style.css">
    <title>Wishlist - Paint Of Heart</title>
</head>                                                   
<body>
    <header>
        <?php
        include_once 'components/navbar.php';
        ?>
    </header>
    <?php include_once 'components/hero.php'; ?>
    <main>
    <h2 id="header">My Wishlist</h2>  
        <?php 
        if(isset($delWlist)){
            echo $delWlist;
        }
        ?>
        <div class="container container-fluid  pb-3 mb-3">
        <div class="row" >
          <?= $card; ?>
        </div>
    </div>
    <div class="topbtn"> 
          <button onclick="topFunction()" id="scrollToTopBtn" title="Go to top">Top☝️</button>
       </div>
       <div id="showMsg" class="row row-cols-1 row-cols-md-2 row-cols-lg-3  g-3"></div>
    </main>
    <footer>
        <?php include_once 'components/footer.php'; ?>
    </footer>
    <!-- Bootstrap 5 JS bundle  -->
    <?php include_once 'components/bootjs.php'; ?>
    <script src="js/script.js"></script>
</body>
</html>