<?php
ob_start();
session_start();
include 'classes/autoload.php';

$pd = new Product();
$que = new Question();
$card = ''; //this variable will contain the details cards
$cardque = ''; //this variable will contain the question cards

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

$question = $que->AllQuestion($id); // all question
if (is_array($question)) { // if any question
    foreach ($question as $question) {
        $admdel= ""; //identify admin for deletion
        if(isset($_SESSION['adm'])) { // only for user: give & edit review
                $admdel .= "<a onclick='return confirm(\"Are you sure to delete this question? (QueID ".$question['queid'].")\")' class= 'btn btn-danger btn-sm' href='?id=".$id."&delId=".$question['queid']."' title='Delete'><i class='material-icons'>&#xE872;</i></a>&nbsp;";
                $admdel .= "<a class= 'btn btn-warning btn-sm' href='actions/que_list.php#".$question['queid']."' title='Add/Edit Answer'><i class='material-icons'>&#xE254;</i></a>";
        }            
    
            $userque = ""; //identify user's question
        if(isset($_SESSION['user'])) { // only for user: give question
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
        <div class='mx-3 h-15' id='".$question['queid']."'>
        <h5 class='rev-head-le'>".$question['f_name']." ".$question['l_name']." ".$userque ."".$admdel ."
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

} else {  // id doesn't exist
    $card =  "<div><center>No Data Available </center></div>";
    $cardrev =  "<div><center>No Data Available </center></div>";
    $cardrevus =  "<div><center>No Data Available </center></div>";
}        

} else if (isset($_GET['id']) && isset($_GET['delId'])) { // get delId: for admin question deletion
    $itemid = $_GET['id'];
    $id = preg_replace('/[^-a-zA-Z0-9_]/', '', $_GET['delId']);
    $delQue = $que->QueDelete($id);
    echo '<script>
    alert("Question (QueID '.$id.') deleted!");
window.location.href="question.php?id='.$itemid.'";
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
    <title>Question ID <?php echo $id; ?> - Paint Of Heart</title>
</head>

<body>
    <header>
        <?php
        include_once 'components/navbar.php';
        ?>
    </header>
    <?php include_once 'components/hero_product.php'; ?>
    <main>
        <h2 id="header">Question for this color</h2>
        
        <!-- product details -->
        <div class='row'>
            <?php echo $card; ?>
        </div>

        <!-- review -->        
        <div class='row'>
        <div class='col d-flex justify-content-center'>
        <div class='card card-review'>
        
        <div class='m-3'>
        <h2 style=" float: left;">Question
        </h2>
        </div>

        <?php echo $cardque; ?>
        
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