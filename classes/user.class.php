<?php 
  include_once ('autoload.php') ;

  class User{
    private $db;
    private $fm;

	 	public function __construct(){
	      $this->db = new Database();
	      $this->fm = new Format();
	    }

		// FUNCTION: user insert using $_POST
public function userInsert($f_name, $l_name, $address, $email,$password,$user_level) {  
	$error = false;
    $fnameError = $emailError =  $passError = '';
    
    // name validation
    if (empty($f_name) || empty($l_name)) {
        $error = true;
        $fnameError = "Please enter full name and surname";  
        
    } else if (strlen($f_name) < 3 || strlen($l_name) < 3) {
        $error = true;
        $fnameError = "Name and surname must have at least 3 characters.";  
        
    } else if (!preg_match("/^[a-zA-Z]+$/", $f_name) || !preg_match("/^[a-zA-Z]+$/", $l_name)) {
        $error = true;
        $fnameError = "Name and surname must contain only letters and no spaces.";  
        
    }
   
    //email validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = true;
        $emailError = "Please enter a valid email address.";  
     } else {
        $query = "SELECT * FROM user WHERE email = '".$email."'";
        $result = $this->db->select($query);
        if($result){  
            $error = true;
            $emailError = "Email is already in use.";
        } 
    }

    // password validation
    if (strlen($password) < 6) {
        $error = true;
        $passError = "Password must have at least 6 characters.";          
    }

    // if any error -> return error msgs
    if ($error == true) { 
        $errors['fnameError'] = $fnameError;
        $errors['emailError'] = $emailError;
        $errors['passError'] = $passError;
        return $errors;        
    } // no error -> register account
    else if ($error == false) { 
                $pass =  hash('sha256', $password);
                $query = "INSERT INTO user (f_name, l_name, address, email, password, user_level) values('".$f_name."','".$l_name."','".$address."','".$email."','".$pass."',".$user_level.")";
                $result = $this->db->insert($query);
	    if ($result) {
            $msg = "<div class='alert alert-success'>User added successfully!</div>";
		    return $msg;
	    }else{
            $msg = "<div class='alert alert-danger'>Add user failed! Please try again</div>";
		    return $msg;			
	    }
    }                
	 }

	 // FUNCTION: get all user
	 public function getAllUser(){
	 	$query = "SELECT * FROM `user`";
	 	$selected_row = $this->db->select($query);
	 	return $selected_row;
	 }

	 // FUNCTION: get user by ID
	 public function getUserById($id){
	 	$query = "SELECT * FROM `user` WHERE `id` = '$id' ";
	 	$selected_row = $this->db->select($query);
	 	return $selected_row;
	 }

	 // FUNCTION: edit user by id 
	 public function updateUser($id, $f_name, $l_name, $address, $email, $password,$user_level,$user_state) {  
		$error = false;
		$fnameError = $emailError =  $passError = '';
		
		// name validation
		if (empty($f_name) || empty($l_name)) {
			$error = true;
			$fnameError = "Please enter full name and surname";  
			
		} else if (strlen($f_name) < 3 || strlen($l_name) < 3) {
			$error = true;
			$fnameError = "Name and surname must have at least 3 characters.";  
			
		} else if (!preg_match("/^[a-zA-Z]+$/", $f_name) || !preg_match("/^[a-zA-Z]+$/", $l_name)) {
			$error = true;
			$fnameError = "Name and surname must contain only letters and no spaces.";  
			
		}
	   
		// email validation if updated
		if (strlen($email) > 0) {
			if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				$error = true;
				$emailError = "Please enter a valid email address.";  
			 } else {
				$query = "SELECT * FROM user WHERE email = '".$email."'";
				$result = $this->db->select($query);
				if($result){  
					$error = true;
					$emailError = "Email is already in use.";
				} 
					}
	}

	// password validation if updated
		if (strlen($password) > 0) {
				if (strlen($password) < 6) {
			$error = true;
			$passError = "Password must have at least 6 characters.";          
		}
	}
	
		// if any error -> return error msgs
		if ($error == true) { 
			$errors['fnameError'] = $fnameError;
			$errors['emailError'] = $emailError;
			$errors['passError'] = $passError;
			return $errors;        
		} // no error -> register account
		else if ($error == false) { 
			$query = "UPDATE user SET f_name = '$f_name', l_name = '$l_name', address = '$address',user_level = $user_level, user_state = $user_state WHERE id = {$id}";
					$result = $this->db->update($query);

			if (strlen($email) > 0) { // if email updated
					$query = "UPDATE user SET email = '$email' WHERE id = {$id}";
					$result = $this->db->update($query);
			} 
			
			if (strlen($password) > 0) { // if password updated
				$pass =  hash('sha256', $password);
				$query = "UPDATE user SET password = '$pass' WHERE id = {$id}";
				$result = $this->db->update($query);
		} 
		
			if ($result) {
				$msg = "<div class='alert alert-success'>User edited successfully!</div>";
		    return $msg;
	    }else{
            $msg = "<div class='alert alert-danger'>Edit user failed! Please try again</div>";
		    return $msg;			
	    }
		}                
		 }

			// FUNCTION: delete user by id (admin)
	public function delUserById($id){
		$query = "DELETE FROM `user` WHERE `id` = '$id' ";
		$deleted = $this->db->delete($query);
		if ($deleted) {
			$msg = "<div class='alert alert-success'>User deleted successfully!</div>";
		    return $msg;
		}else{
			$msg = "<div class='alert alert-danger'>Delete user failed! Please try again!</div>";
		    return $msg;
		}
	}

	// FUNCTION: delete own profile by id (user)
	public function delProfileById($id){
		$query = "DELETE FROM user WHERE id = {$id}";
		$deleted = $this->db->delete($query);
		if ($deleted) {
			echo "<script>alert('Profile deleted!')</script>";  
		}else{
			echo "<script>alert('Profile delete failed! Please try again')</script>";  
		}
	}

	// FUNCTION: edit address by id 
	public function updateAddress($id, $f_name, $l_name, $address) {  
		$error = false;
		$fnameError = '';
		
		// name validation
		if (empty($f_name) || empty($l_name)) {
			$error = true;
			$fnameError = "Please enter full name and surname";  
			
		} else if (strlen($f_name) < 3 || strlen($l_name) < 3) {
			$error = true;
			$fnameError = "Name and surname must have at least 3 characters.";  
			
		} else if (!preg_match("/^[a-zA-Z]+$/", $f_name) || !preg_match("/^[a-zA-Z]+$/", $l_name)) {
			$error = true;
			$fnameError = "Name and surname must contain only letters and no spaces.";  
			
		}
	   
		// if any error -> return error msgs
		if ($error == true) { 
			$errors['fnameError'] = $fnameError;
			return $errors;        
		} // no error -> register account
		else if ($error == false) { 
			$query = "UPDATE user SET f_name = '$f_name', l_name = '$l_name', address = '$address' WHERE id = {$id}";
					$result = $this->db->update($query);

			if ($result) {
				$msg = "<div class='alert alert-success'>Address edited successfully!</div>";
		    return $msg;
	    }else{
            $msg = "<div class='alert alert-danger'>Edit address failed! Please try again</div>";
		    return $msg;			
	    }
		}                
		 }
 }
 
?>
