<?php
include_once ('autoload.php') ;

class RegLog {
    private $db;
    private $fm;

    // CONSTRUCT new objects
	 	public function __construct(){
	      $this->db = new Database();
	      $this->fm = new Format();
	    }

        // FUNCTION: user registration using $_POST
public function UserRegister($f_name, $l_name, $address, $email,$password) {  
    $error = false;
    $fnameError = $emailError =  $passError = '';
    
    // name validation
    if (empty($f_name) || empty($l_name)) {
        $error = true;
        $fnameError = "Please enter your full name and surname";  
        
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
        $emailError = "Please enter valid email address.";  
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
                $query = "INSERT INTO user (f_name, l_name, address, email, password) values('".$f_name."','".$l_name."','".$address."','".$email."','".$pass."')";
                $result = $this->db->insert($query);
	    if ($result) {
            echo "<script>alert('User registered successfully! Please login')</script>";  
	    	
	    }else{
            echo "<script>alert('Error: User registration failed! Please try again')</script>";  
	    	
	    }
    }
        }  

        // FUNCTION: user login using $_POST
        public function Login($email, $password){ 
            $pass =  hash('sha256', $password);
            $query = "SELECT * FROM user WHERE email = '".$email."'";  
                $result = $this->db->select($query);
                if($result){   // check email
                    $user = $result->fetch_assoc();
                
                if ($user['password'] == $pass) { // check password
                    if ($user['user_level'] == 1) { // logged in admin goes to dashboard
                        $_SESSION['adm'] = $user;
                        header("Location: admin.php"); 
                    } else { 
                        $_SESSION['user'] = $user; 

                        if ($user['user_state'] == 0) { // check user state/banned user
                            session_unset();
    session_destroy();
                            echo "<script>
alert('This user (".$user['email'].") is banned. Please contact our admin through our contact page.');
window.location.href='login.php';
</script>";// if cleared: log user out and throw alert
                        } else {
                            header("Location: home.php"); // if cleared: logged in user goes to home
                        }
                        
                    }                   
                    
                } else { // check password returns error                   
             echo "<script>alert('Wrong password! Please try again')</script>";  
                }

                } else {  // check email returns error
                    echo "<script>alert('Email does not exist! Please try again')</script>";                      
                }  
              
            }  

        }
?>