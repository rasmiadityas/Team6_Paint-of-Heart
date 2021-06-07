<?php
ob_start();
 include '../classes/autoload.php' ;
 $db = new Database();
 $que = new Question();

if(isset($_POST['answer'])&& isset($_POST ['queid'])) {
    $answer=mysqli_real_escape_string($db->link, $_POST['answer']);
    $queid=$_POST ['queid'];
    
    $result = $que->AnsEdit($answer,$queid); // FUNCTION: edit answer

    if($result==1){
        echo "Answer added";
    }else{
        echo "Add answer error";
    }

};
?>