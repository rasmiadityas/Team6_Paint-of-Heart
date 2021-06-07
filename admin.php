<?php
ob_start();
session_start();
if (!isset($_SESSION['user']) && !isset($_SESSION['adm'])) {
  header("Location: home.php");
  exit;
}
if (isset($_SESSION["user"])) {
  header("Location: home.php");
  exit;
}
include_once('classes/autoload.php');
$stat = new Stat();
$TotalOrder = $stat->TotalOrder();
$TotalSales = $stat->TotalSales();
$AveSales = $stat->AveSales();
$RecentSales = $stat->RecentSales(5); // number of row
$i = 0; // RecentSales
$tbody1 = '';
if (is_array($RecentSales)) {
  foreach ($RecentSales as $row) {
    $i++;
    $date = new DateTime($row['date']);
    $tbody1 .= "<tr class='text-center fw-normal'>
        <td>" . $i . "</td>
        <td>" . $row['id'] . "</td>
            <td>" . date_format($date, 'D, d-m-Y, H:i') . "</td>
            <td>" . $row['email'] . "</td>
            <td>" . $row['tot_price'] . " €</td>";
  }
} else {
  $tbody1 = "<tr class='text-center fw-normal'><td colspan='5'>No Data Available</td></tr>";
}
$TotalSold = $stat->TotalSold();
$MostCol = $stat->MostCol();
$MostColName = $MostCol['name'];
$MostColQuan = $MostCol['quantity'];
$MostColPerc = $MostCol['perc'];
$MostCat = $stat->MostCat();
$MostCatName = $MostCat['name'];
$MostCatQuan = $MostCat['quantity'];
$MostCatPerc = $MostCat['perc'];
$MostItem = $stat->MostItem(5); // number of row
$i = 0; // MostItem
$tbody2 = '';
if (is_array($MostItem)) {
  foreach ($MostItem as $row) {
    $i++;
    $tbody2 .= "<tr class='text-center fw-normal'>
        <td>" . $i . "</td>
        <td>" . $row['id'] . "</td>
            <td>" . $row['name'] . "</td>
            <td>" . $row['catName'] . "</td>
            <td>" . $row['price'] . " €</td>
            <td>" . $row['quantity'] . " pcs</td>
            <td>" . $row['tot_price'] . " €</td>";
  }
} else {
  $tbody2 = "<tr class='text-center fw-normal'><td colspan='7'>No Data Available</td></tr>";
}
$CustUser = $stat->CustUser();
$CustUserPay = $CustUser['res1'];
$CustUserNo = $CustUser['res2'];
$CustUserPerc = $CustUser['perc'];
$MostUser = $stat->MostUser(5); // number of row
$i = 0; // MostUser
$tbody3 = '';
if (is_array($MostUser)) {
  foreach ($MostUser as $row) {
    $i++;
    $tbody3 .= "<tr class='text-center fw-normal'>
        <td>" . $i . "</td>
        <td>" . $row['id'] . "</td>
            <td>" . $row['email'] . "</td>
            <td>" . $row['res3'] . " pcs</td>
            <td>" . $row['res2'] . " €</td>";
  }
} else {
  $tbody3 = "<tr class='text-center fw-normal'><td colspan='5'>No Data Available</td></tr>";
}

?>
<!doctype html>
<html lang="en">
<head>
  <title>Admin Dashboard</title>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="components/style.css">
  <title>Admin Dashboard - Paint of Heart</title>
  <!-- Bootstrap 5 CSS bundle  -->
  <?php include_once 'components/boot.php'; ?>
</head>
<body>
  <!-- SIDEBAR -->
  <div class="content">
    <div class="container">
      <div class="row">
        <div class="col-md-3">
          <div class="control-box p-3 sidebar">
            <h2 class="mb-3">Admin Dashboard</h2>
            <b>Hello <span style="color:red"><?php echo $_SESSION['adm']['f_name'] ?>!</span> </b>
            <ul>
              <li><a href="actions/logout.php?logout">Logout</a></li>
              <li><a href="home.php"><span>Homepage</span></a> </li>
              <li><a href="admin.php"><span>Dashboard</span></a> </li>
              <li><a href="inbox.php"><span>Inbox</span></a> </li>
            </ul>
            <b>User Option</b>
            <ul>
              <li><a href="actions/user_add.php">Add User</a> </li>
              <li><a href="actions/user_list.php">User List</a> </li>
            </ul>
            <b>Product Option</b>
            <ul>
              <li><a href="actions/product_add.php">Add Product</a> </li>
              <li><a href="actions/product_list.php">Product List</a> </li>
              <li><a href="actions/catprod_list.php">Product by Category</a> </li>
            </ul>
            <b>Category Option</b>
            <ul>
              <li><a href="actions/cat_add.php">Add Category</a> </li>
              <li><a href="actions/cat_list.php">Category List</a> </li>
            </ul>
            <b>Question Option</b>
            <ul>
              <li><a href="actions/que_list.php">Questions List</a> </li>
            </ul>
            <b>Review Option</b>
            <ul>
              <li><a href="actions/rev_list.php">Review List</a> </li>                                          
            </ul>
            </div>
        </div>
        <!-- MAIN -->
        <div class="col-md-8">
          <div class="control-box p-3 main-content">
            <div class="d-grid gap-2">
              <div class="p-2">
                <h2>Sales Statistic</h2>
                <p class="my-1">
                  Total order: <?php echo $TotalOrder; ?> times<br>
                  Total sales: <?php echo $TotalSales; ?> € (ave. <?php echo $AveSales; ?> €/order)<br>
                  Recent sales:<br></p>
                <table class='table table-bordered border-dark'>
                  <thead>
                    <tr class="text-center">
                      <th>#</th>
                      <th>Order ID</th>
                      <th>Created</th>
                      <th>User</th>
                      <th>Total</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php echo $tbody1 ?>
                  </tbody>
                </table>
                </p>
              </div>
              <div class="p-2">
                <h2>Product Statistic</h2>
                <p class="my-1">
                  Product sold: <?php echo $TotalSold; ?> pcs<br>
                  Most sold color: <?php echo $MostColName . " = " . $MostColQuan . " pcs (" . $MostColPerc . "%)"; ?><br>
                  Most sold category: <?php echo $MostCatName . " = " . $MostCatQuan . " pcs (" . $MostCatPerc . "%)"; ?><br>
                  Most sold product:<br>
                <table class='table table-bordered border-dark'>
                  <thead>
                    <tr class="text-center">
                      <th>#</th>
                      <th>Prod. ID</th>
                      <th>Name</th>
                      <th>Category</th>
                      <th>Price</th>
                      <th>Sold</th>
                      <th>Total (incl. Discount)</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php echo $tbody2 ?>
                  </tbody>
                </table>
                </p>
              </div>
              <div class="p-2">
                <h2>Customer Statistic</h2>
                <p class="my-1">
                  Purchasing user: <?php echo $CustUserPay . " out of " . $CustUserNo . " users (" . $CustUserPerc . "%)"; ?> <br>
                  Most purchasing user:<br>
                <table class='table table-bordered border-dark'>
                  <thead>
                    <tr class="text-center">
                      <th>#</th>
                      <th>User. ID</th>
                      <th>Email</th>
                      <th>Order</th>
                      <th>Total</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php echo $tbody3 ?>
                  </tbody>
                </table>
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- jQuery first, then Popper.js, then Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
  <?php include_once 'components/bootjs.php'; ?>
  <script src="https://use.fontawesome.com/b4aae4cb0e.js"></script>
  <script type="text/javascript" src="https://cdn.rawgit.com/leafo/sticky-kit/v1.1.2/jquery.sticky-kit.min.js"></script>
  <script type="text/javascript">
    $(".sidebar").stick_in_parent();
  </script>

</body>
</html>