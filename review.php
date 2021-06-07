<?php
ob_start();
session_start();
include 'classes/autoload.php';

$pd = new Product();
$rev = new Review();
$card = ''; //this variable will contain the details cards
$cardrev = ''; //this variable will contain the review cards
$cardrevus = ''; //this variable will contain the user review cards
$CanReview= 0; //to determine if user can review
$CanEdit= 0; //to determine if user can edit review

if (isset($_GET['id']) && !isset($_GET['delId'])) {
    $id = $_GET['id'];
    $getData = $pd->getSingleProductById($id);
    
    if ($getData) {// id exist
        $product = $getData->fetch_assoc();
        $card .= "
        <div class='col d-flex justify-content-center'>
        <div class='card shadow-lg p-1 mb-5 bg-body rounded rounded-3 m-5' style='width: 20rem;'>
            <a href='details.php?id=" . $product['id'] . "'>
            <div class='ownImgDiv'>
            <img class='card-img-top ownPro' src='pictures/" . $product['image'] . "' alt='Card image cap'>
              </div></a> 
                <div class='card-body'>
                    <h4 class='card-title text-center'>" . $product['name'] . "</h4>
                    <p class='card-text'>Category: " . $product['catName'] . "</p>
                    <p class='card-text'>Price: " . $product['price'] . " €</p>
                    </div>
            </a>
        </div>
</div>";

$avescore = $rev->AveScore($id); // average score
$review = $rev->AllReview($id); // all review
if (is_array($review)) { // if any review
    foreach ($review as $review) {
        $admdel= ""; //identify admin for deletion
        if(isset($_SESSION['adm'])) { // only for user: give & edit review
                $admdel .= "<a onclick='return confirm(\"Are you sure to delete this review? (RevID ".$review['revid'].")\")' class= 'btn btn-danger btn-sm' href='?id=".$id."&delId=".$review['revid']."' title='Delete'><i class='material-icons'>&#xE872;</i></a>";
        }            
    
            $userrev = ""; //identify user's review
        if(isset($_SESSION['user'])) { // only for user: give & edit review
                $userid = $_SESSION['user']['id'];
                if ($userid == $review['fk_user_id']) {
                    $userrev .= "(Yours)";
                }
            }            
            
        $date = new DateTime($review['date']);
        $cardrev .= "
        <div class='mx-3 h-15' id='".$review['revid']."'>
        <h5 class='rev-head-le'>".$review['f_name']." ".$review['l_name']." ".$userrev ."".$admdel ."
        <h5 class='rev-head-ri'>Rate: ".$review['score']."</h5>
        </div>
        <div class='mx-3'>
        <p>on ".date_format($date, 'M d, Y')."</p>
        <p>".$review['review']."</p>
    </div>";
    }    
} else { // if not yet review
    $cardrev .= "
        <div class='mx-3'>
        <p>There's no review yet</p>
    </div>";
}

if(isset($_SESSION['user'])) { // only for user: give & edit review
    $userid = $_SESSION['user']['id'];
    $CanReview = $rev->CanReview($id, $userid);// only user can review when has purchased this item
    $CanEdit = $rev->CanEdit($id, $userid); // only user can edit review when has given review

    $reviewus = $rev->UserReview($id,$userid);
    $cardrevus .= "name, score, review";
}

        } else {  // id doesn't exist
            $card =  "<div><center>No Data Available </center></div>";
            $cardrev =  "<div><center>No Data Available </center></div>";
            $cardrevus =  "<div><center>No Data Available </center></div>";
    }        
    
    $AveScore = $rev->AveScore($id); // average score: number for data, -- for no data
    
} else if (isset($_GET['id']) && isset($_GET['delId'])) { // get delId: for admin review deletion
    $itemid = $_GET['id'];
    $id = preg_replace('/[^-a-zA-Z0-9_]/', '', $_GET['delId']);
    $delRev = $rev->RevDelete($id);
    echo '<script>
    alert("Review (RevID '.$id.') deleted!");
window.location.href="review.php?id='.$itemid.'";
</script>';
    
}else { // get other than id 
    header("location: products.php");
}

$pd->db->link->close();
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
    <title>Review ID <?php echo $id; ?> - Paint Of Heart</title>
</head>
<body>
    <header>
        <?php
        include_once 'components/navbar.php';
        ?>
    </header>
    <?php include_once 'components/hero.php'; ?>
    <main>
        <h2 id="header">Review this color</h2>   
        <!-- product details -->
        <div class='row'>
            <?php echo $card; ?>
        </div>
        <!-- review -->        
        <div class='row'>
        <div class='col d-flex justify-content-center'>
        <div class='card card-review'>
        
        <div class='m-3'>
        <h2 style=" float: right;">Rating: <?php echo $avescore; ?>/10</h2>
        </div>
        <?php echo $cardrev; ?>
    </div>
        </div>
        </div>
    </main> 
    <footer>
        <?php include_once 'components/footer.php'; ?>
    </footer>
    <!-- Bootstrap 5 JS bundle  -->
    <?php include_once 'components/bootjs.php'; ?>
</body>
</html>