<?php
ob_start();
session_start();
include 'classes/autoload.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap 5 CSS bundle  -->
    <link rel="stylesheet" href="components/style.css">
    <title>Home - Paint Of Heart</title>
</head>
<body>
    <header>
    <?php 
        include_once 'components/boot.php';
        include_once 'components/navbar.php'; 
        include_once 'components/hero.php';
     ?>
    </header>
    <main>
        <div class="container">
            <h1 class="my-3 text-center">We Color Your World</h1>
            <?php
            include_once 'components/carousel.php';
            include_once 'components/about.php';
            ?>
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