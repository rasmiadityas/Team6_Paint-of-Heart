<?php
ob_start();
session_start();
include 'classes/autoload.php';
$pd = new Product();
# var_dump($pd);
$getData = $pd->getAllProduct();
# var_dump($getData);
$product = $getData->fetch_all(MYSQLI_ASSOC);
# var_dump($product);
$cart = new Cart();
if (isset($_GET['id'])) {
    $id = preg_replace('/[^-a-zA-Z0-9_]/', '', $_GET['id']);
    if (!isset($_SESSION['adm']) && !isset($_SESSION['user'])) {
        header("Location: register.php");
        exit;
    }
    // $userId = $_SESSION['user']['id'];

    // else {
    //     $addToCart = $cart->addToCart($id);
    //     unset($_GET);
    // }
}
$card = ''; //this variable will contain the bootstrap cards
if (mysqli_num_rows($getData)  > 0) {
    foreach ($product as $product) {
        if ($product['visibility'] > 0) { // visible product
        $price = 0;
        $dPrice = 0;
        $discount = 0;
        $priceRounded = 0;
        $card .= "     
        <div class='col-md-6 col-lg-4 col-xl-3 mx-auto'>
        <div class='card h-100 w-100 rounded-3'>
        <a href='details.php?id=" . $product['id'] . "'>
        <div class='ownImgDiv'> 
            <div style='background-image: url(".$product['image']."); background-repeat: no-repeat; background-size: contain; height: 230px; background-position: center;'>
            </div>
              </div></a>         
                <div class='card-body mx-auto'>
                    <h5 class='card-title text-center fs-2'>" . $product['name'] . "</h5>
                    <p class='card-text'>Category: " . $product['catName'] . "</p>                 
                    ";
        if ($product['discount'] > 0) {
            $discount = $product['discount'];
            $price = $product['price'];
            $dPrice = $price * ((100 - $discount) / 100);
            $priceRounded = round($dPrice, 2);
            $card .= "<p class='card-text fs-4'>Original Price:" . $price . " €</p>
                        <p class='card-text text-danger fw-bold'>Discount: " . $product['discount'] . " % <img class='rounded-pill' style='width:70px; height:60px;' src='pictures/offered.png' alt='Card image cap'> </p>
                        <p class='card-text text-success fs-4'>Price: " . $priceRounded . " €</p>";
        } else {
            $card .= "<p class='card-text text-success fs-5'>Price: " . $product['price'] . " €</p>";
        }
        $card .= "                 
                    </div>                
                    <div class='card-footer border-none text-center mb-2'>
                    <a href='details.php?id=".$product['id']."' class='btn nav-btn rounded-pill'>Add to Cart</a>   
                    </div>
                    </div>
                    </div>";
    };
}
} else {
    $card =  "<div><center>No Data Available </center></div>";
}
$pd->db->link->close();
?>
<!DOCTYPE HTML>
<html lang="en">
<!-- <p class='card-text'>" . $product['description'] . "</p> -->
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap 5 CSS bundle  -->
    <?php include_once 'components/boot.php'; ?>
    <link rel="stylesheet" href="components/style.css">
    <title>Products - Paint Of Heart</title>
</head>
<body id="products">
    <header>
        <?php
        include_once 'components/navbar.php';
        ?>
    </header>
    <?php include_once 'components/hero_product.php'; ?>
    <main>
        <h1 id="header"><span class="one">Find</span> <span class="two">Your</span> <span class="four">Perfect</span> <span class="three">Color</span></h1>
        <div class="container container-fluid pb-3 mb-3">
            <div id="showMsg" class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3 mx-auto">
                <!--dynamic content-->
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