<?php
ob_start();
session_start();
include("classes/autoload.php");
// $user_data=$login->check_login($_SESSION['mybook_userid']);
$card = "";
if (isset($_GET['find'])) {
  $find = addslashes($_GET['find']);
  $find = preg_replace("#[^0-9a-z]#i", "", $find);
  $sql = "select p.*,c.catName from products p join category c on p.fk_category=c.id where p.name like '%$find%' || c.catName like '%$find%' limit 20";
  $DB = new Database();
  $results = $DB->select($sql);
  if ($results) {
    $count = mysqli_num_rows($results);
    if ($count == false) {
      $output = "There is no search results";
    } else {
      while ($row = mysqli_fetch_array($results)) {
        $price = 0;
        $dPrice = 0;
        $discount = 0;
        $priceRounded = 0;
        $id = $row['id'];
        $Pname = $row['name'];
        $catName = $row['catName'];
        $card .= "
        <div class='col-md-6 col-lg-4 col-xl-3'>
        <div class='card h-100 w-100 rounded-3'>
        <a href='details.php?id=" . $row['id'] . "'>
        <div class='ownImgDiv'> 
            <img class='card-img-top ownPro' src='pictures/" . $row['image'] . "' alt='Card image cap'>
              </div></a>
                <div class='card-body'>
                    <h5 class='card-title'>" . $row['name'] . "</h5>
                    <p class='card-text'>Category: " . $row['catName'] . "</p>
                    ";
        if ($row['discount'] > 0) {
          $discount = $row['discount'];
          $price = $row['price'];
          $dPrice = $price * ((100 - $discount) / 100);
          $priceRounded = round($dPrice, 2);
          $card .= "<p class='card-text fs-5'>Original Price: " . $price . " €</p>
                      <p class='card-text text-danger fw-bold'>Off: " . $row['discount'] . " % <img class='rounded-pill' style='width:70px; height:60px;' src='pictures/offered.png' alt='Card image cap'> </p>
                      <p class='card-text text-danger fs-4'>Price: " . $priceRounded . " €</p>";
        } else {
          $card .= "<p class='card-text fs-4'>Price: " . $row['price'] . " €</p>";
        }
        $card .= "
                    </div>
                    <div class='card-footer border-none text-center'>
                    <a href='details.php?id=" . $row['id'] . "' class='btn btn-warning rounded-pill'>Add to Cart<img class='class='rounded-pill' style='width:30px; height:30px;' src='pictures/cart.png' alt='Card image cap'></a>
                    </div>
                    </div>
                    </div>";
      }
    }
  } else {
    $card = '<div class="mx-auto col d-flex justify-content-center">
     <div class="card shadow-lg p-1 mb-5 bg-body rounded rounded-3 m-5" style="width: 20rem;">
     <div class="card-body">
       <h5 class="card-title">Sorry,</h5>
       <p class="card-text">we did not find any matching results</p>
       <a href="products.php" class="btn home-btn">Go Back</a>
       </div>
     </div>
   </div>';
  }
  //  echo"<pre>";
  // print_r($results);
  // echo"</pre>";    
}
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
  <title>Search - Paint Of Heart</title>
</head>
<body>
  <header>
    <?php
    include_once 'components/navbar.php';
    ?>
  </header>
  <?php include_once 'components/hero.php'; ?>
  <main>
    <h2 id="header">Search Results</h2>
    <div class="container container-fluid  pb-3 mb-3">
      <div id="showMsg" class="row row-cols-1 row-cols-md-2 row-cols-lg-3  g-3">
        <?= $card; ?>
      </div>
    </div>
    <div class="topbtn">
      <button onclick="topFunction()" id="scrollToTopBtn" title="Go to top">Top☝️</button>
    </div>
  </main>
  <footer>
    <?php include_once 'components/footer.php'; ?>
  </footer>
  <!-- Bootstrap 5 JS bundle  -->
  <?php include_once 'components/bootjs.php'; ?>
  <script src="js/script.js"></script>
</body>
</html>