<?php
class Session{

  public static function init(){
    session_start();
  }

  public static function set($key, $val){  //Session::set("userid", $value['id']);
    $_SESSION[$key] = $val ;   
    }

    public static function get($key){  //Session::get("userid")
      if (isset($_SESSION[$key])) {
        return $_SESSION[$key];
      }else{
        return false;
      }
    }

    public static function checkSession(){
      self::init(); // or session_start(); to call init() method
      if (self::get("adminLogin") == false) {
      self::destroy();
       header("location: login.php");
      }
    }

      public static function checkLogin(){
      self::init(); 
      if (self::get("adminLogin") == true) {
       header("location: index.php");
      }
    }

    public static function destroy(){
      session_destroy();
      header("location: login.php");
    }
}
?>