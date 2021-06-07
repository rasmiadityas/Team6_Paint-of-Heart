<?php
include_once ('autoload.php') ;

class Review {
    private $db;
        
    // CONSTRUCT new objects
	 	public function __construct(){
	      $this->db = new Database();
	    }

        // FUNCTION: check if user can review
public function CanReview($itemid, $userid) {  
    $query = "SELECT COUNT(*) as res FROM tbl_order WHERE fk_productId = {$itemid} AND fk_userId = {$userid}";  
    $result = $this->db->select($query);
    $data = $result->fetch_assoc();
    $res = $data['res'];
    if($res === "0"){   // no match
                    $out = 0;
                } else { // yes match
                    $out =  1;
                }     
                return $out;
        }  

        // FUNCTION: check if user has given review and can edit
        public function CanEdit($itemid, $userid) {  
            $query = "SELECT COUNT(*) as res FROM review WHERE fk_item_id = {$itemid} AND fk_user_id = {$userid}";  
            $result = $this->db->select($query);
            $data = $result->fetch_assoc();
            $res = $data['res'];
            if($res === "0"){   // no match
                            $out = 0;
                        } else { // yes match
                            $out =  1;
                        }     
                        return $out;
                }  
        
                // FUNCTION: get average score review for product id
                public function AveScore($itemid) {  
                    $query = "SELECT score as res FROM review WHERE fk_item_id = {$itemid}";  
                    $result = $this->db->select($query);
                    if($result){   // yes data
                        $data = $result->fetch_all(MYSQLI_ASSOC);
                        $rows = array();
                        foreach ($data as $row)
                        {
                                $rows[] = intval($row['res']); // string to int conversion
                            }
                        $out = round(array_sum($rows)/count($rows),1);
                    } else { // no data
                        $out =  '-';
                    } 
                                return $out;
                        }  
                
        // FUNCTION: get all review for product id
        public function AllReview($itemid) {  
            $query = "SELECT *,`review`.`id` as revid, `user`.`f_name`, `user`.`l_name`, `user`.`email`
            FROM review 
            INNER JOIN `user` ON `user`.`id` = `review`.`fk_user_id`
            WHERE `review`.`fk_item_id` = {$itemid}
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
        
        // FUNCTION: get specific review from userid for product id
        public function UserReview($itemid, $userid) {  
            $query = "SELECT * FROM review WHERE fk_item_id = {$itemid} AND fk_user_id = {$userid}";  
            $result = $this->db->select($query);
            if($result){   // yes data
                $data = $result->fetch_all(MYSQLI_ASSOC);
                $out = $data;
            } else { // no data
                $out =  'N/A';
            } 
                        return $out;
                }  
    
      // FUNCTION: get most recent review for product id
      public function LastReview($itemid,$num) {  
        $query = "SELECT *, `user`.`f_name`, `user`.`l_name`
        FROM review 
        INNER JOIN `user` ON `user`.`id` = `review`.`fk_user_id`
        WHERE fk_item_id = {$itemid}
        ORDER BY date DESC
        LIMIT {$num}";  
        $result = $this->db->select($query);
        if($result){   // yes data
            $data = $result->fetch_all(MYSQLI_ASSOC);
            $out = $data;
        } else { // no data
            $out =  'N/A';
        } 
                    return $out;
            }  

    // FUNCTION: user add review for product id
    public function RevAdd($review,$score, $userid, $itemid, $date) {  
        $query = "INSERT INTO review (review,score, fk_user_id, fk_item_id, date) values('".$review."',".$score.",".$userid.",".$itemid.",'".$date."')";
        $result = $this->db->insert($query);

        if ($result) {
            $msg = "<div class='alert alert-success'>Review added successfully!</div>";
            $out = 1;
		    return $out;
	    }else{
            $msg = "<div class='alert alert-danger'>Add review failed! Please try again</div>";
            $out = 0;
		    return $out;			
	    }
    }

    // FUNCTION: user edit review for product id
    public function RevEdit($review,$score, $userid, $itemid, $date) {  
        $query = "UPDATE review 
        SET review = '$review', score = $score, date = '$date' WHERE fk_user_id = {$userid} AND fk_item_id = {$itemid}";        
        $result = $this->db->insert($query);

        if ($result) {
            $msg = "<div class='alert alert-success'>Review added successfully!</div>";
            $out = 1;
		    return $out;
	    }else{
            $msg = "<div class='alert alert-danger'>Add review failed! Please try again</div>";
            $out = 0;
		    return $out;			
	    }
    }

    // FUNCTION: admin delete review for review id
    public function RevDelete($id){
		$query = "DELETE FROM `review` WHERE `id` = '$id' ";
		$deleted = $this->db->delete($query);
		if ($deleted) {
			$msg = "<div class='alert alert-success'>Review deleted successfully!</div>";
		    return $msg;
		}else{
			$msg = "<div class='alert alert-danger'>Delete review failed! Please try again!</div>";
		    return $msg;
		}
	}

    // FUNCTION: admin list all review for all id
    public function AllReviewAll() {  
        $query = "SELECT *,`review`.`id` as revid, `user`.`f_name`, `user`.`l_name`, `user`.`email`, `products`.`name`, `category`.`catName` 
        FROM review 
        INNER JOIN `user` ON `user`.`id` = `review`.`fk_user_id`
        INNER JOIN `products` ON `products`.`id` = `review`.`fk_item_id`
        INNER JOIN `category` ON `products`.`fk_category` = `category`.`id`
        ORDER BY `review`.`date` DESC
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
    
    // FUNCTION: admin filter all review for given id

    // FUNCTION: user list all given review for all purchased  id


        }