<?php

class Session {
    
    private $logged_in=FALSE;
    public $user_id;
    public $message;
	public $cart_id;
    
    function __construct() {
        session_start();
        $this->check_message();
        $this->check_login();
		
    }
    
    public function is_logged_in() {
        return $this->logged_in;
    }
    
    public function login($user) {
        //database should find user based on username/password
        if($user) {
            $this->user_id = $_SESSION['user_id'] = $user->id;
            $this->logged_in = TRUE;
        }
    }

    public function logout() {
        unset($_SESSION['user_id']);
        unset($this->user_id);
		unset($_SESSION['buyback_cartId']);
		unset($this->cart_id);
		session_destroy();
        $this->logged_in = FALSE;
    }
    
    public function message($msg="") {
        if(!empty($msg)) {
            $_SESSION['message'] = $msg;
        } else {
            return $this->message;
        }
    }
    
    private function check_login() {
        if(isset($_SESSION['user_id'])) {
            $this->user_id = $_SESSION['user_id'];
            $this->logged_in = true;
        } else {
            unset($this->user_id);
            $this->logged_in = false;
        }
    }

    private function check_message() {
    //is there a message stored in the session?
    if(isset($_SESSION['message'])) {
        //add it as an attribure and erase stored version
        $this->message = $_SESSION['message'];
        unset($_SESSION['message']);
    } else {
        $this->message = "";
    }
  }
	

}

$session = new Session();
$message = $session->message();

?>