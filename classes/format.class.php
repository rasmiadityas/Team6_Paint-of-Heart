<?php 

class Format{

  public function formatDate($date){
   echo date("F j, Y g:i a", strtotime($date));
  }

  public function textShorten($text, $limit=200 ){
    $text = $text ." ";
    $text = substr($text, 0, $limit);
    $text = substr($text, 0, strrpos($text, " "));  //text......
    $text = $text ."........";
    return $text;
    }

    public function validation($data){
      $data = trim($data); //avoid space
      $data = stripslashes($data);
      $data = htmlspecialchars($data);
      return $data;
    }

    public function title(){
      $path  = $_SERVER['SCRIPT_FILENAME'];           //index.php contact.php
      $title = basename($path, '.php');              // .php bad
      $title = str_replace('_', ' ', $title);        //contact_us.php = Contact Us

      if ($title == 'index') {                       // must be used ==
        $title = 'home';
      }elseif ($title == 'contact'){                 // must be used ==
        $title = 'contact';
      }
      return $title = ucwords($title);               // ucfirst() first character uppercase
                                                // ucwords()every words first character uppercase
    }
}
?>