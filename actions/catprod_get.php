<?php
 include '../classes/autoload.php' ;
 $db = new Database();
 $pd = new Product();
if(isset($_POST['category'])) {
    $catid=$_POST ['category'];
    

    if ($catid==0) { // category selected: All
        $result = $pd->getAllProduct(); // FUNCTION: get all product
        if ($result) { // yes data
            $data = $result->fetch_all(MYSQLI_ASSOC);
        } else { // no data
            $data = 0;
        }
        
    } else {  // category selected: others
        $result = $pd->getAllProductByCat($catid); // FUNCTION: get product by category
        if ($result) { // yes data
            $data = $result->fetch_all(MYSQLI_ASSOC);
        } else { // no data
            $data = 0;
        }
        
    }
    

    // FUNCTION: delivering a JSON response, can have any name
    function response($status,$data){
        $response['status']=$status;
        $response['data']=$data;
        //Generating JSON from the $response array
        $json_response = json_encode($response);
        // Outputting JSON to the client
        echo $json_response;
    }

    // call the function with parameter 200 and $rows(contains all entries)
    response(200, $data);  

    
};
?>