<?php
include_once ('autoload.php') ;

class Stat {
    private $db;
        
    // CONSTRUCT new objects
	 	public function __construct(){
	      $this->db = new Database();
	    }

        // FUNCTION: total order
public function TotalOrder() {  
    $query = "SELECT COUNT(*) as res FROM order_register";  
    $result = $this->db->select($query);
    $data = $result->fetch_assoc();
    $out = $data['res'];
    if(!is_null($out)){   // yes data
                    $out = $out;
                } else { // no data
                    $out =  'N/A';
                }     
                return $out;
        }  
        
        // FUNCTION: total sales in euro
        public function TotalSales() {  
            $query = "SELECT SUM(tot_price) as res FROM order_register";  
                        $result = $this->db->select($query);
                        $data = $result->fetch_assoc();
                        $out = $data['res'];
                        if(!is_null($out)){   // yes data
                                        $out = $out;
                                    } else { // no data
                                        $out = 'N/A';
                                    }                                 
                        return $out;
                }  

                        // FUNCTION: average sales
        public function AveSales() {  
            $TotalOrder = $this->TotalOrder();
            $TotalSales = $this->TotalSales();
            if ($TotalOrder != 'N/A'){
                $out = round(($TotalSales/$TotalOrder),2);
               }else{
                $out ='N/A';
              }              
                        return $out;
                }  
                
                // FUNCTION: recent sales
        public function RecentSales($num) {  
            $query = "SELECT `order_register`.`id`, date, `user`.`email`, tot_price 
            FROM order_register
            INNER JOIN `user` ON `user`.`id` = `order_register`.`fk_user_id`
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

                // FUNCTION: total item sold
        public function TotalSold() {  
            $query = "SELECT SUM(quantity) as res FROM tbl_order";  
                        $result = $this->db->select($query);
                        $data = $result->fetch_assoc();
                        $out = $data['res'];
                        if(!is_null($out)){   // yes data
                                        $out = $out;
                                    } else { // no data
                                        $out = 'N/A';
                                    }                                 
                        return $out;
                }  

                // FUNCTION: most sold color
        public function MostCol() {  
            $query = "SELECT `products`.`name`, SUM(quantity) as quantity
            FROM tbl_order 
            INNER JOIN `products` ON `products`.`id` = `tbl_order`.`fk_productId`
            GROUP BY name
            ORDER BY SUM(quantity) DESC
            LIMIT 1";  
            $TotalSold = $this->TotalSold();
                        $result = $this->db->select($query);
                            if ($result) {
                                $data = $result->fetch_assoc();
                                $out = $data;
                                $out['perc'] = round(100*$out['quantity'] / $TotalSold);
                              } else {
                                $out['name'] = $out['quantity'] = $out['perc'] = 'N/A';
                                } 
                              return $out;
                }  

                // FUNCTION: most sold category
        public function MostCat() {  
            $query = "SELECT `category`.`catName`, SUM(quantity) as quantity
            FROM tbl_order 
            INNER JOIN `products` ON `products`.`id`  = `tbl_order`.`fk_productId`
            INNER JOIN `category` ON `products`.`fk_category` = `category`.`id`
            GROUP BY `category`.`catName`
            ORDER BY SUM(quantity) DESC
            LIMIT 1";  
             $TotalSold = $this->TotalSold();
             $result = $this->db->select($query);
                 if ($result) {
                     $data = $result->fetch_assoc();
                     $out = $data;
                     $out['name'] = $data['catName'];
                     $out['perc'] = round(100*$out['quantity'] / $TotalSold);
                   } else {
                     $out['name'] = $out['quantity'] = $out['perc'] = 'N/A';
                     } 
                   return $out;
                }  

                // FUNCTION: most sold item
        public function MostItem($num) {  
            $query = "SELECT `products`.`id`,`products`.`name`, `category`.`catName`,`products`.`price`, SUM(quantity) as quantity, SUM(`tbl_order`.`price`) as tot_price
            FROM tbl_order 
            INNER JOIN `products` ON `products`.`id` = `tbl_order`.`fk_productId`
            INNER JOIN `category` ON `products`.`fk_category` = `category`.`id`
            GROUP BY `products`.`id`
            ORDER BY SUM(quantity) DESC
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

                // FUNCTION: paying vs total user
        public function CustUser() {  
            $query = "SELECT X.res1, Y.res2
            FROM (SELECT COUNT(DISTINCT fk_userId) as res1
            FROM tbl_order) X, (SELECT user_level, COUNT(id) as res2
            FROM user
            GROUP BY user_level
            LIMIT 1) Y";  
                          $result = $this->db->select($query);
                              if ($result) {
                                  $data = $result->fetch_assoc();
                                  $out = $data;
                                  $out['perc'] = round(100*$out['res1'] / $out['res2']);
                                } else {
                                  $out['res1'] = $out['res2'] = $out['perc'] = 'N/A';
                                  } 
                                return $out;
                }  

                // FUNCTION: most buying user
        public function MostUser($num) {  
            $query = "SELECT `user`.`email`,`user`.`id`, Y.res2, Y.res3
            FROM (SELECT fk_userId, SUM(price) as res2, SUM(quantity) as res3
            FROM tbl_order
            GROUP BY fk_userId
			ORDER BY SUM(price) DESC
            LIMIT {$num}) Y
			INNER JOIN `user` ON `user`.`id` = Y.`fk_userId`";  
                        $result = $this->db->select($query);
                        if($result){   // yes data
                            $data = $result->fetch_all(MYSQLI_ASSOC);
                            $out = $data;
                        } else { // no data
                            $out =  'N/A';
                        }
             
                        return $out;
                }  
        }
?>