<?php
ob_start();
session_start();
include 'classes/autoload.php';
$userid= 0; //userid error proofing

$pd = new Product();
$card = ''; //this variable will contain the details cards

$rev = new Review();
$cardrev = ''; //this variable will contain the review cards
$cardrevus = ''; //this variable will contain the user review cards
$CanReview= 0; //to determine if user can review
$CanEdit= 0; //to determine if user can edit review

$que = new Question();
$cardque = ''; //this variable will contain the question cards
$cardqueus = ''; //this variable will contain the user question  cards

$cart = new Cart();
// Check for wishlist
if (isset($_SESSION['adm'])) {
    $userId = $_SESSION['adm']['id'];
    $chkWlist = $pd->checkWlist($userId);
}
if (isset($_SESSION['user'])) {
    $userId = $_SESSION['user']['id'];
    $chkWlist = $pd->checkWlist($userId);
}

// GET id of the page
if ($_GET['id']) {
    $pageid = $_GET['id'];
    $id = $_GET['id'];
    
        // CART SECTION: add to cart
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['quantity'])) {
        $quantity = $_POST['quantity'];
        $addCart  = $cart->addToCart($quantity, $id);
    }
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['wlist'])) {
        $saveWlist  = $pd->saveWishListData($id, $userId);
    }

    $getData = $pd->getSingleProductById($id);
    if ($getData) {// id exist
        // CARD SECTION: product
        $product = $getData->fetch_assoc();

        $card .= "
        <div class='col d-flex justify-content-center'>
        <div class='card card-details'>
  <a href='details.php?id=" . $product['id'] . "'><img class='card-img-top img-details mt-3' src='pictures/".$product['image'] . "' alt='Card image cap'></a>
  <div class='card-body'>
     <h5 class='card-title'>" . $product['name'] . "</h5>
     <p class='card-text'>" . $product['description'] . "</p>
     <p class='card-text'>Category: " . $product['catName'] . "</p>";

     if ($product['discount'] > 0) {
        $discount = $product['discount'];
        $price = $product['price'];
        $dPrice = $price * ((100 - $discount) / 100);
        $priceRounded = round($dPrice, 2);

        $card .= "<p class='card-text fs-5'>Original Price:" . $price . " €</p>
                        <p class='card-text text-danger fw-bold'>Discount: " . $product['discount'] . " % <img class='class='rounded-pill' style='width:70px; height:60px;' src='pictures/offered.png' alt='Card image cap'> </p>
                        <p class='card-text text-center text-success fs-4'>Price: " . $priceRounded . " €</p>";
        } else {
            $card .= "<p class='card-text fs-4'>Price: " . $product['price'] . " €</p>";
        }
        if (isset($_SESSION['user']) || isset($_SESSION['adm'])){
            $card .= "
                    </div>
                    <div class='card-footer border-none text-center'>
                    <form action='' method='post'>
                    <div class='btn-group mb-3'>
                   <input type='number' min='1' max='20' class ='w-40' name='quantity' value='1'/>&nbsp;&nbsp;
                    <input type='submit' class='btn save-btn' name='submit' value='Add to Cart'/>&nbsp;&nbsp;
                    </form>
                    <form action='' method='post'>
                    <input id='wish' type='submit' class='btn btn-warning' name='wlist' value='&#10084;&#65039;' title='Wishlist'>
                    </div>
                   </form>
                   </div>
                </div>
             </div>
             ";
            } else {
                $card .= "
                    </div>
                    <div class='card-footer border-none text-center'>
                    <a class='btn save-btn' href='register.php'>Add to Cart<a/>&nbsp;&nbsp;
                    <a id='wish' class='btn btn-warning' href='register.php'>&#10084;&#65039;</a>
                    </div>
                   </div>
                </div>
             </div>
             ";
            }
   
        } else {  // CARD: Product - id doesn't exist
            $card =  "<div><center>No Data Available </center></div>";
    }        

    // CARD SECTION:question
    $couque = $que->CountQuestion($id); // count question
    $ansque = $que->AnsweredQuestion($id); // count question
    $question = $que->LastQuestion($id, 2); // latest 2 question
    if (is_array($question)) { // if any question
        foreach ($question as $question) {
            $userque = ''; //identify user's question

            if(isset($_SESSION['user'])) { // identify user's question
                $userid = $_SESSION['user']['id'];
                if ($userid == $question['fk_user_id']) {
                    $userque .= "(Yours)";
                }
            }            
            $date = new DateTime($question['date']);
            if ($question['answer']==='') { // if it is not yet answered
                $answer = '(Not answered yet)';
            } else { // if it is  answered
                $answer = $question['answer'];
            }

            $cardque .= "
            <div class='mx-3 h-15'>
            <h5 class='rev-head-le'>".$question['f_name']." ".$question['l_name']." ".$userque."
            </div>
            <div class='mx-3'>
            <p>on ".date_format($date, 'M d, Y')."</p>
            <p><b>Q</b>: ".$question['question']."</p>
            <p><b>A</b>: ".$answer."</p>
        </div>";
        }    
    } else { // if not yet question
        $cardque .= "
        <div class='mx-3'>
        <p>There's no question yet</p>
    </div>";
    }

    // CARD SECTION: question - user own questiom
if(isset($_SESSION['user'])) { // only for user: give question
    $userid = $_SESSION['user']['id'];
    
    $queus = $que->UserQuestion($id,$userid);
    if (is_array($queus)) { // if user has questioned
        foreach ($queus as $queus) {
            $date = new DateTime($queus['date']);
            if ($queus['answer']==='') { // if it is not yet answered
                $answer = '(Not answered yet)';
            } else { // if it is  answered
                $answer = $queus['answer'];
            }

            $cardqueus .= "
            <div class='mx-3 h-15'>
            </div>
            <div class='mx-3'>
            <p>on ".date_format($date, 'M d, Y')."</p>
            <p><b>Q</b>: ".$queus['question']."</p>
            <p><b>A</b>: ".$answer."</p>
        </div>";
        
        }    
    } else { // if user has not questioned
        $cardqueus .= "
        <div class='mx-3'>
        <p>You have no question yet</p>
    </div>";
    }

}

    // CARD SECTION:review
    $avescore = $rev->AveScore($id); // average score
    $review = $rev->LastReview($id, 2); // latest 2 review
    if (is_array($review)) { // if any review
        foreach ($review as $review) {
            $userrev = ''; //identify user's review

            if(isset($_SESSION['user'])) { // only for user: give & edit review
                $userid = $_SESSION['user']['id'];
                if ($userid == $review['fk_user_id']) {
                    $userrev .= "(Yours)";
                }
            }            
            $date = new DateTime($review['date']);
            $cardrev .= "
            <div class='mx-3 h-15'>
            <h5 class='rev-head-le'>".$review['f_name']." ".$review['l_name']." ".$userrev."
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

// CARD SECTION: review - user own review
if(isset($_SESSION['user'])) { // only for user: give & edit review
    $userid = $_SESSION['user']['id'];
    $CanReview = $rev->CanReview($id, $userid);// only user can review when has purchased this item
    $CanEdit = $rev->CanEdit($id, $userid); // only user can edit review when has given review

    if ($CanReview==1) { // if user has bought -> can review
    $reviewus = $rev->UserReview($id,$userid);
    if (is_array($reviewus)) { // if user has reviewed
        foreach ($reviewus as $reviewus) {
            $date = new DateTime($reviewus['date']);
            $cardrevus .= "
            <div class='mx-3 h-15'>
            <h5 class='rev-head-le'>Rate: ".$reviewus['score']."</h5>
            </div>
            <div class='mx-3'>
            <p>on ".date_format($date, 'M d, Y')."</p>
            <p>".$reviewus['review']."</p>
        </div>";
        
        }    
    } else { // if user has not reviewed
        $cardrevus .= "
        <div class='mx-3'>
        <p>You have not reviewed yet</p>
    </div>";
    }
} else { // // if user has not bought -> can not review
    $cardrevus .= "
    <div class='mx-3'>
    <p>You have not bought this product yet</p>
</div>";
}
}


} else { // get other than id 
    $card =  "<div><center>No Data Available </center></div>";
    $cardrev =  "<div><center>No Data Available </center></div>";
    $cardrevus =  "<div><center>No Data Available </center></div>";

}

$pd->db->link->close();

?>
<!DOCTYPE HTML>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap 5 CSS bundle  -->
    <?php include_once 'components/boot.php'; ?>
    <link rel="stylesheet" href="components/style.css">
    
    <title>Details <?php echo $product['id'];?> - Paint Of Heart</title>
</head>

<body>
    <header>
        <?php
        include_once 'components/navbar.php'; 
        ?>
    </header>
    <?php include_once 'components/hero_product.php'; ?>
    <main>
    <h2 id="header" class="fw-bold"><span class="three">Is</span> <span class="five">This</span> <span class="four">Your</span> <span class="three">Color?</span></h2>
    <!-- add to cart/wishlist message -->
    <?php
        if (isset($addCart)) {
            echo "<h3 style='color:red;margin-top:10px !important;'>$addCart</h3>";
        }
        if (isset($saveWlist)) {
            echo $saveWlist;
        }
        if (isset($delWlist)) {
            echo $delWlist;
        }
        ?>

        <!-- product details -->
        <div class='row'>
            <?php echo $card; ?>
        </div>

        <!-- question -->        
        <div class='row'>
        <div class='col d-flex justify-content-center'>
        <div class='card' style='width: 30rem;'>

        <div class='m-3'> 
        <h2 class='rev-head-le'>Question
        </div>

         <!-- question: user own -->   
         <?php
        if (isset($_SESSION['user'])) {
            echo "<div class='mx-3 rev-head'>
            <p>Your  Question:</p>
        </div>";        
        echo $cardqueus;
        //this variable will contain the review form
        $queform = "<div class='col-md-12 mx-3'> 
        <form id='form-que'>
            <div class='col-10'>
            <textarea class='form-control mb-3' rows='3' name='question' id='question' placeholder='Your Question' /></textarea>
            <p class='text-danger' id='que-error'></p>"; 
            
                $queform .= "
        <button id='addQueBtn' class='btn btn-success mb-3' type='submit'  value='Add Question'>Add Question</button>";            
       
        $queform .= "
        </div>
    </form>
                </div>
                <hr>";
                    echo $queform;                    
               
        }       
        ?>

        <!-- question: recent -->   
    <div class='mx-3 rev-head'> 
        <p>Recent questions:</p>
    </div>

    <?php echo $cardque; ?>
        
    <div class='mx-3 mb-3 see-rev'>
    <a href='question.php?id=<?php echo $pageid; ?>'>See all questions</a>
    </div>

        </div>
        </div>
        </div> 
        <!-- end of question -->
        
        <!-- review -->        
        <div class='row'>
        <div class='col d-flex justify-content-center'>
        <div class='card' style='width: 30rem;'>

        <div class='m-3'> <!-- review: title -->   
        <h2 class='rev-head-le'>Review
        <h2 class='rev-head-ri'>Rating: <?php echo $avescore; ?>/10</h2>
        </div>

         <!-- review: user own -->   
        <?php
        if (isset($_SESSION['user'])) {
            echo "<div class='mx-3 rev-head'>
            <p>Your  review:</p>
        </div>";        
        echo $cardrevus;
        //this variable will contain the review form
        $revform = "<div class='col-md-12 mx-3'> 
        <form id='form-rev'>
            <div class='col-10'>
            <textarea class='form-control mb-3' rows='3' name='review' id='review' placeholder='Your Review' /></textarea>
            <p class='text-danger' id='rev-error'></p>
            <input class='form-control mb-3' type='number'  id='score' name='score' placeholder ='Rate: 1-10' />
            <p class='text-danger' id='sco-error'></p>"; 
            
        if ($CanEdit==1) {
            $revform .= "
            <button id='editBtn' class='btn btn-warning mb-3' type='submit'  value='Edit Review'>Edit Review</button>";            
        } else {
            if ($CanReview==1) {
                $revform .= "
        <button id='addBtn' class='btn btn-success mb-3' type='submit'  value='Add Review'>Add Review</button>";            
            }            
        }

        $revform .= "
        </div>
    </form>
                </div>
                <hr>";

                if ($CanEdit==1 || $CanReview==1) {
                    echo $revform;                    
                } 
               
        }       
        ?>

<!-- review: recent -->   
    <div class='mx-3 rev-head'> 
        <p>Recent reviews:</p>
    </div>

    <?php echo $cardrev; ?>
        
    <div class='mx-3 mb-3 see-rev'>
    <a href='review.php?id=<?php echo $pageid; ?>'>See all reviews</a>
    </div>
    
    </div>
        </div>
        </div>
        <!-- end of review -->        
       
    </main>
    
    <footer>
        <?php include_once 'components/footer.php'; ?>
    </footer>
    <!-- Bootstrap 5 JS bundle  -->
    <?php include_once 'components/bootjs.php'; ?>
</body>

<script>
        var userid = "<?php echo $userid; ?>";
        var itemid = "<?php echo $pageid; ?>";
        var error = '';

        // FUNCTION: add question
   document.getElementById("addQueBtn").addEventListener("click", insertQue); 
        function insertQue(e) {
            e.preventDefault(); //this prevents the page from refreshing after submitting
            let question=document.getElementById ("question").value; //saving question value
            document.getElementById("que-error").innerHTML =  '';
            
            if (question == "") { // question validation
                error = "Question must be filled";
                document.getElementById("que-error").innerHTML = error;
                return false;
              }

            let params=`question=${question }&userid=${userid}&itemid=${itemid}`; //creating the parameters for the POST method
            console.log(params)
            
            let request=new XMLHttpRequest (); //creating new request
            request.open("POST", "actions/que_add.php" ,true); //connecting to the php file of process
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded"); //setting header for POST method
            request.onload=function(){
                if(this.status== 200){
                    location.reload();
            }
            }
            request.send(params); //send parameters to be further processed by php
        }


        <?php if ($CanEdit==1) { ?>
        // FUNCTION: edit review
        document.getElementById("editBtn").addEventListener("click", editRev); 
        function editRev(e) {
            e.preventDefault(); //this prevents the page from refreshing after submitting
            let review=document.getElementById ("review").value; //saving review value
            let score=document.getElementById ("score").value; //saving score value
            score = parseFloat(score);
            document.getElementById("rev-error").innerHTML = document.getElementById("sco-error").innerHTML = '';
            
            if (review == "") { // review validation
                error = "Review must be filled";
                document.getElementById("rev-error").innerHTML = error;
                return false;
              }

              if (isNaN(score) || score < 1 || score > 10) { // score validation
                error = "Rating must be between 1-10";
                document.getElementById("sco-error").innerHTML = error;
                return false;
          
        }

            let params=`review=${review }&score=${score}&userid=${userid}&itemid=${itemid}`; //creating the parameters for the POST method
            console.log(params)
            
            let request=new XMLHttpRequest (); //creating new request
            request.open("POST", "actions/rev_edit.php" ,true); //connecting to the php file of process
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded"); //setting header for POST method
            request.onload=function(){
                if(this.status== 200){
                    location.reload();
            }
            }
            request.send(params); //send parameters to be further processed by php
        }
        <?php } else if ($CanReview==1) { ?>
            // FUNCTION: add review
        document.getElementById("addBtn").addEventListener("click", insertRev); 
        function insertRev(e) {
            e.preventDefault(); //this prevents the page from refreshing after submitting
            let review=document.getElementById ("review").value; //saving review value
            let score=document.getElementById ("score").value; //saving score value
            score = parseFloat(score);
            document.getElementById("rev-error").innerHTML = document.getElementById("sco-error").innerHTML = '';
            
            if (review == "") { // review validation
                error = "Review must be filled";
                document.getElementById("rev-error").innerHTML = error;
                return false;
              }

              if (isNaN(score) || score < 1 || score > 10) { // score validation
                error = "Rating must be between 1-10";
                document.getElementById("sco-error").innerHTML = error;
                return false;
          
        }

            let params=`review=${review }&score=${score}&userid=${userid}&itemid=${itemid}`; //creating the parameters for the POST method
            console.log(params)
            
            let request=new XMLHttpRequest (); //creating new request
            request.open("POST", "actions/rev_add.php" ,true); //connecting to the php file of process
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded"); //setting header for POST method
            request.onload=function(){
                if(this.status== 200){
                    location.reload();
            }
            }
            request.send(params); //send parameters to be further processed by php
        }        
        <?php } ?>

     
    </script>

</html>