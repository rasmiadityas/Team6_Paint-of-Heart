<?php
ob_start();
$logo = 'pictures/Logo.png';
?>        
<nav class="navbar sticky-top navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="home.php"><img class="rounded-circle" src="<?php echo $logo ?>"></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent" data-toggle="collapse" data-target=".navbar-collapse">
            <?php
            $navCart = new Cart();
            if (isset($_SESSION['adm'])) {
                // admin navbar
                $adName = $_SESSION['adm']['f_name'];
                echo '<ul class="navbar-nav me-auto text-center d-flex justify-content-evenly align-items-center">
            <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="home.php">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="contact.php">Contact Us</a>
            </li>
            <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="profile.php?userId=' . $_SESSION['adm']['id'] . '">My Profile</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="products.php">Products</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="admin.php">Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="btn home-btn text-light mx-2 my-2" aria-current="page" href="actions/logout.php?logout">Logout ' . $adName . '</a>
            </li>
            </ul>
            <form class="d-flex" method ="GET" action="search.php">
                <input class="form-control me-2" type="text"name="find" placeholder="Search Product" aria-label="Search">
                <button class="btn btn-outline-success" type="submit">Search</button>
            </form>';
            } else if (isset($_SESSION['user'])) { // user navbar

                $cart = new Cart();
                $pd = new Product();
                $name = $_SESSION['user']['f_name'];
                $id = $_SESSION['user']['id'];
                $chkCart = $cart->checkCartTable();
                $chkWlist = $pd->checkWlist($id);
                $chkOrder = $cart->checkOrderProduct($id);
                $getData = $cart->checkCartTable();
                if ($getData) {
                    $sum = Session::get("sum");
                    $qty = Session::get("qty");
                } else {
                    $sum = "0";
                    $qty = "empty";
                }

                if (isset($_GET['customerId'])) {
                    Session::destroy();
                    $delCart = $cart->delCustomerCart();  //logout  delete 
                }
                echo '<ul class="navbar-nav me-auto text-center d-flex justify-content-evenly align-items-center">
                <li class="nav-item">
                    <p class="fs-5 mb-0"><span class="text-info">Hi ' . $name . '</span></p>
                </li>
                <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="home.php">Home</a>
                </li>
                <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="contact.php">Contact Us</a>
                </li>
                <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="profile.php?userId=' . $id . '">My Profile</a>
                </li>
                <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="products.php">Products</a>
                </li>
                ';
                if ($chkWlist) {
                    echo '<li class="nav-item">
                    <a class="btn dash-btn active text-dark my-2 mx-2" aria-current="page" href="wish_list.php?userId=' . $id . '">Wishlist</a>
                </li>';
                }
                if ($chkCart) {
                    echo '<li class="nav-item">
                    <a class="btn nav-btn active text-dark mx-2 my-2" aria-current="page" href="payment.php?userId=' . $id . '">Payment</a>
                </li>';
                }
                if ($chkOrder) {
                    echo '<li class="nav-item">
                    <a class="btn btn-info active text-dark my-2 mx-2" aria-current="page" href="order_details.php?userId=' . $id . '">Order</a>
                </li>';
                }
                echo '</ul>
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 pb-2 text-center mt-2">
            <li class="nav-item">
            <a class="btn save-btn mb-3 p-2 m-1 active text-light mt-3 text-center" href="actions/cart_list.php?uerId=' . $id . '">Cart:' . $sum . ' €
            </a>
          



            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-cart4 mx-1 mb-2" viewBox="0 0 16 16">
            <path d="M0 2.5A.5.5 0 0 1 .5 2H2a.5.5 0 0 1 .485.379L2.89 4H14.5a.5.5 0 0 1 .485.621l-1.5 6A.5.5 0 0 1 13 11H4a.5.5 0 0 1-.485-.379L1.61 3H.5a.5.5 0 0 1-.5-.5zM3.14 5l.5 2H5V5H3.14zM6 5v2h2V5H6zm3 0v2h2V5H9zm3 0v2h1.36l.5-2H12zm1.11 3H12v2h.61l.5-2zM11 8H9v2h2V8zM8 8H6v2h2V8zM5 8H3.89l.5 2H5V8zm0 5a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0zm9-1a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0z"/>
            </svg>

            <span class="text-danger fw-bold mb-1 p-3">(' . $qty .  ')</span>
            </span>
            </li>
            </ul>
        
            <form class="d-flex" method ="GET" action="search.php">
            <input class="form-control me-2" type="text"name="find" placeholder="Search Product" aria-label="Search">
            <button class="btn btn-outline-success" type="submit">Search</button>
            </form>
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0 p-2 text-center">
            <li class="nav-item">
                <a class="btn home-btn ms-3 text-light" href="?customerId=' . $id . '">Logout </a>
            </li>
            </ul>';
            } else { // general navbar
                echo '<ul class="navbar-nav me-auto mb-2 p-2 mb-lg-0 text-center mt-2">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="home.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="contact.php">Contact Us</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="products.php">Products</a>
                </li>
               
                <li class="nav-item">
                <a class="btn btn-warning" class="nav-link active" aria-current="page" href="register.php">Register Now</a>
                </li>
                </ul>
                <form class="d-flex mb-0" method ="GET" action="search.php">
                <input class="form-control me-2" type="text"name="find" placeholder="Search Product" aria-label="Search">
                <button class="btn btn-outline-success" type="submit">Search</button>
            </form>
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0 p-2 text-center">
            <li class="nav-item">
                <a class="btn home-btn btn-small ms-1 text-light" href="login.php">Login</a>
            </li>
            </ul>';
            }
            ?>
        </div>
    </div>
    <script src="js/script.js"></script>
</nav>

