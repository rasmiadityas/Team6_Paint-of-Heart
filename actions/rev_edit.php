<?php
ob_start();
 include '../classes/autoload.php' ;
 $db = new Database();
 $rev = new Review();
if(isset($_POST['review'])&& isset($_POST ['score'])&& isset($_POST ['userid'])&& isset($_POST ['itemid'])) {
    $review=mysqli_real_escape_string($db->link, $_POST['review']);
    $score=$_POST ['score'];
    $userid=$_POST ['userid'];
    $itemid=$_POST ['itemid'];
    $date=date("Y-m-d H:i:s");
    $review = $rev->RevEdit($review,$score, $userid, $itemid, $date); // FUNCTION: edit review
    if($review==1){
        echo "Review added";
    }else{
        echo "Add review error";
    }
};
?>