<?php
ob_start();
 include '../classes/autoload.php' ;
 $db = new Database();
 $que = new Question();

if(isset($_POST['question'])&& isset($_POST ['userid'])&& isset($_POST ['itemid'])) {
    $question=mysqli_real_escape_string($db->link, $_POST['question']);
    $userid=$_POST ['userid'];
    $itemid=$_POST ['itemid'];
    $date=date("Y-m-d H:i:s");

    $question = $que->QueAdd($question, $userid, $itemid, $date); // FUNCTION: add question

    if($question==1){
        echo "Question added";
    }else{
        echo "Add question error";
    }

};
?>