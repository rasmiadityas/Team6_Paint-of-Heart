<?php
include_once('autoload.php');
?>

<?php
class Question
{
    public $db;
    private $fm;

    public function __construct()
    {
        $this->db = new Database();
        $this->fm = new Format();
    }



    // --------------add a question --------------------------------------------
    public function questionInsert($data)
    {  //$data = $_POST  $file = $_FILES
        $fk_user_id  = $this->fm->validation($data['fk_user_id']);
        $fk_item_id  = $this->fm->validation($data['fk_item_id']);
        $question    = $this->fm->validation($data['question']);
        $answer      = $this->fm->validation($data['answer']);
        $date        = $this->fm->validation($data['date']);


        $fk_user_id = mysqli_real_escape_string($this->db->link, $data['fk_user_id']);
        $fk_item_id = mysqli_real_escape_string($this->db->link, $data['fk_item_id']);
        $question   = mysqli_real_escape_string($this->db->link, $data['question']);
        $answer     = mysqli_real_escape_string($this->db->link, $data['answer']);
        $date       = mysqli_real_escape_string($this->db->link, $data['date']);



        if ($fk_user_id == "" || $fk_item_id == "" || $answer == "" || $question == "") {
            // || $date == ""
            $msg = "<span class='error'>Field must not be empty!</span>";
            return $msg;
        } else {
            $query = "INSERT INTO
        `question`(`id`,
        `fk_user_id`,
        `fk_item_id`,
        `question`,
        `answer`,
         `date`
         ) VALUES 
         (NULL,
          '$fk_user_id',
           '$fk_item_id',
           '$question',
           '$answer',
           '$date')";
            $result = $this->db->insert($query);
            if ($result) {
                $msg = "<span class='success'>Question has been asked successfully!</span>";
                return $msg;
            } else {
                $msg = "<span class='error'>Failed to ask question!!</span>";
                return $msg;
            }
        }
    }

    // --------------------get all questions by product ---------------------

    public function getAllQuestions()
    {
        $query = "SELECT `question`.*,`products`.`name` FROM `question`
		INNER JOIN `products`
		ON `question`.`fk_item_id` = `products`.`id`
	 	ORDER BY `question`.`id` DESC";
        $selected_row = $this->db->select($query);
        return $selected_row;
    }

    // -----------------get details about  (1)question---------------------

    public function getAllQuestionsById($id)
    {
        $query = "SELECT * FROM `question` WHERE `id` = '$id' ";
        $selected_row = $this->db->select($query);
        return $selected_row;
    }

    // ------------------get one question by product --------------------------


    public function getQuestionsByProductId($id)
    {
        $query = "SELECT `question`.*, `products`.`name`, `user`.`f_name`, `user`.`l_name` 
            FROM `question`
				INNER JOIN `products` ON `question`.`fk_item_id` = `products`.`id`
                INNER JOIN `user` ON `question`.`fk_user_id` = `user`.`id`
			   WHERE `question`.`fk_item_id` = '$id' ";

        $result = $this->db->select($query);
        return $result;
    }

    //-------------------------------------------------------------------------

    public function updateQuestion($data, $id)
    {
        $fk_user_id   = $this->fm->validation($data['fk_user_id']);
        $fk_item_id   = $this->fm->validation($data['fk_item_id']);
        $answer       = $this->fm->validation($data['answer']);
        $question     = $this->fm->validation($data['question']);
        $date         = $this->fm->validation($data['date']);

        $fk_user_id   = mysqli_real_escape_string($this->db->link, $data['fk_user_id']);
        $fk_item_id   = mysqli_real_escape_string($this->db->link, $data['fk_item_id']);
        $answer       = mysqli_real_escape_string($this->db->link, $data['answer']);
        $question     = mysqli_real_escape_string($this->db->link, $data['question']);
        $date         = mysqli_real_escape_string($this->db->link, $data['date']);


        if ($fk_user_id == "" || $fk_item_id == "" || $answer == "" || $question == "") {
            $msg = "<span class='error'>Field must not be empty!</span>";
            return $msg;
        } else {
            $query = "UPDATE `question` SET 
            `fk_user_id`  = '$fk_user_id',
            `fk_item_id`  = '$fk_item_id',
            `answer`      = '$answer',
            `question`    = '$question',
            `date`        = '$date'
            WHERE `id`    = '$id' ";
            $updated_row = $this->db->update($query);
            if ($updated_row) {
                $msg = "<span class='success'>Question updated successfully!</span>";
                return $msg;
            } else {
                $msg = "<span class='error'>Question not updated!</span>";
                return $msg;
            }
        }
    } //function end


    // --------------------------delete question---------------------------
    public function delQuestionById($id)
    {
        $query = "DELETE FROM `question` WHERE `id` = '$id' ";
        $deleteData = $this->db->delete($query);
        if ($deleteData) {
            $msg = "<span class='error'>Question deleted successfully!</span>";
            return $msg;
        } else {
            $msg = "<span class='error'>Question not deleted!</span>";
            return $msg;
        }
    }

    // FUNCTION: get most recent question for product id
    public function LastQuestion($itemid, $num)
    {
        $query = "SELECT *, `user`.`f_name`, `user`.`l_name`
        FROM question 
        INNER JOIN `user` ON `user`.`id` = `question`.`fk_user_id`
        WHERE fk_item_id = {$itemid}
        ORDER BY date DESC
        LIMIT {$num}";
        $result = $this->db->select($query);
        if ($result) {   // yes data
            $data = $result->fetch_all(MYSQLI_ASSOC);
            $out = $data;
        } else { // no data
            $out =  'N/A';
        }
        return $out;
    }

    // FUNCTION: count question
    public function CountQuestion($itemid)
    {
        $query = "SELECT COUNT(*) as res FROM question WHERE fk_item_id = {$itemid}";
        $result = $this->db->select($query);
    $data = $result->fetch_assoc();
    $out = $data['res'];

    $query = "SELECT COUNT(*) as res FROM question WHERE fk_item_id = {$itemid} AND answer = ''";
        $result = $this->db->select($query);
    $data = $result->fetch_assoc();
    $out2 = $data['res'];

    if(!is_null($out)){   // yes data
                    $out = $out;
                } else { // no data
                    $out =  'N/A';
                }     
                return $out;
    }

    // FUNCTION: count answered question
    public function AnsweredQuestion($itemid)
    {
        $couque = $this->CountQuestion($itemid);

    $query = "SELECT COUNT(*) as res FROM question WHERE fk_item_id = {$itemid} AND answer = ''";
        $result = $this->db->select($query);
    $data = $result->fetch_assoc();
    $out1 = $data['res'];
    $out =$couque - $out1;

    if(!is_null($out)){   // yes data
                    $out = $out;
                } else { // no data
                    $out =  'N/A';
                }     
                return $out;
    }

            
        // FUNCTION: get specific question from userid for product id
        public function UserQuestion($itemid, $userid) {  
            $query = "SELECT * FROM question WHERE fk_item_id = {$itemid} AND fk_user_id = {$userid}";  
            $result = $this->db->select($query);
            if($result){   // yes data
                $data = $result->fetch_all(MYSQLI_ASSOC);
                $out = $data;
            } else { // no data
                $out =  'N/A';
            } 
                        return $out;
                }  
    
                // FUNCTION: user add question for product id
    public function QueAdd($question, $userid, $itemid, $date) {  
        $query = "INSERT INTO question (question, answer, fk_user_id, fk_item_id, date) values('".$question."','',".$userid.",".$itemid.",'".$date."')";
        $result = $this->db->insert($query);

        if ($result) {
            $msg = "<div class='alert alert-success'>Question added successfully!</div>";
            $out = 1;
		    return $out;
	    }else{
            $msg = "<div class='alert alert-danger'>Add question failed! Please try again</div>";
            $out = 0;
		    return $out;			
	    }
    }

            // FUNCTION: get all question for product id
            public function AllQuestion($itemid) {  
                $query = "SELECT *,`question`.`id` as queid, `user`.`f_name`, `user`.`l_name`, `user`.`email`
                FROM question 
                INNER JOIN `user` ON `user`.`id` = `question`.`fk_user_id`
                WHERE `question`.`fk_item_id` = {$itemid}
                ORDER BY date DESC";  
                $result = $this->db->select($query);
                if($result){   // yes data
                    $data = $result->fetch_all(MYSQLI_ASSOC);
                    $out = $data;
                } else { // no data
                    $out =  'N/A';
                } 
                            return $out;
                    }  

                        // FUNCTION: admin delete question for question id
    public function QueDelete($id){
		$query = "DELETE FROM `question` WHERE `id` = '$id' ";
		$deleted = $this->db->delete($query);
		if ($deleted) {
			
		}else{
			$msg = "<div class='alert alert-danger'>Delete question failed! Please try again!</div>";
		    return $msg;
		}
	}

    // FUNCTION: admin list all question for all id
    public function AllQuestionAll() {  
        $query = "SELECT *,`question`.`id` as queid, `user`.`f_name`, `user`.`l_name`, `user`.`email`, `products`.`name`, `category`.`catName` 
        FROM question 
        INNER JOIN `user` ON `user`.`id` = `question`.`fk_user_id`
        INNER JOIN `products` ON `products`.`id` = `question`.`fk_item_id`
        INNER JOIN `category` ON `products`.`fk_category` = `category`.`id`
        ORDER BY `question`.`date` DESC
        ";  
        $result = $this->db->select($query);
        if($result){   // yes data
            $data = $result->fetch_all(MYSQLI_ASSOC);
            $out = $data;
        } else { // no data
            $out =  'N/A';
        } 
                    return $out;
            }  

                            // FUNCTION: admin add & edit answer for question id
    public function AnsEdit($answer,$queid) {  
        $query = "UPDATE question 
        SET answer = '$answer' WHERE id = {$queid}";        
        $result = $this->db->insert($query);

        if ($result) {
            $msg = "<div class='alert alert-success'>Answer updated successfully!</div>";
            $out = 1;
		    return $out;
	    }else{
            $msg = "<div class='alert alert-danger'>Update answer failed! Please try again</div>";
            $out = 0;
		    return $out;			
	    }
    }
    
}




?>
