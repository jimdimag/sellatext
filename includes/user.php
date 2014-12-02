<?php
//require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . "database.php");
require_once(LIB_PATH.DS.'database.php');

class User extends DatabaseObject{
 
protected static $table_name = "sellatext_users"; 
//protected static $db_fields = array('id' 'email', 'password','fname','lname','addr_1','addr_2','city','state','zip','phone'); 
public $id;  
public $email;
public $password;
public $fname;
public $lname;
/*public $addr_1;
  public $addr_2;
  public $city;
  public $state;
  public $zip;
  public $phone */
public static function authenticate($email="", $password="") {
       global $database;
       $email = $database->escape_value($email);
       $password = $database->escape_value($password);
       
       $sql  = "SELECT * FROM ".static::$table_name;
    $sql .= " WHERE email = '{$email}' ";
    $sql .= "AND password = '{$password}' ";
    $sql .= "LIMIT 1";
	
       /*$result_set = $database->query($sql);
      $row = $database->fetch_array($result_set);
	  $hash = $row['password'];
	  if (password_verify($password, $hash)) { echo "They Match!!";
		return !empty($row) ? array_shift($row) : false;
    } else {
        $message = "Could not Authenticate.";
		return $message;
    }*/
    $result_array = static::find_by_sql($sql);
	//$hash = $result_array['password']; echo "hash is: ".$hash;
		return !empty($result_array) ? array_shift($result_array) : false;
   }

public function full_name() {
    if(isset($this->fname) && isset($this->lname)) {
        return $this->fname." " .$this->lname;
    } else {
        return "";
    }
}

public function check_user_exists($email){
	global $database;
	$sql = "select * from ".static::$table_name." where email='".$database->escape_value($email)."' limit 1";
	if($database->query($sql)) {
		return TRUE;
	} else {
		return FALSE;
	}
}

public function get_id($email) {
	global $database;
	$email = $database->escape_value($email);
	$sql = "select * from ".static::$table_name." where email='".$database->escape_value($email)."' limit 1";
	$result_set = $database->query($sql);
	if($result_set) {
    $row = $database->fetch_array($result_set);
		$user_id = $row['id']; 
	} else {
		return FALSE;
	}
	return $user_id;
}

public function register_user($params) {
	global $database;
	$email = $database->escape_value($params['email']);
	//$user_exists = static::check_user_exists($email);
	//if(!(user_exists)){
    $password = $database->escape_value($params['password']);
		//$password = password_hash($password, PASSWORD_DEFAULT);
    $fname = $database->escape_value($params['first_name']);
	$lname = $database->escape_value($params['last_name']);
	
	$sql = "INSERT INTO " .static::$table_name." (email,password,fname,lname)";
    $sql .= "VALUES ('".$email."','".$password."','".$fname."','".$lname;
    $sql .= "')"; 
    if($database->query($sql)) {
        $id = $database->insert_id();
        return $id;
    } else {
        return FALSE;
    }
	/*} else {
		$message = "User already exists, please log in.";
		return $message;
	}*/
}
 
	public function generatePassword($length=9, $strength=0) {
	    $vowels = 'aeuy';
	    $consonants = 'bdghjmnpqrstvz';
	    if ($strength & 1) {
	        $consonants .= 'BDGHJLMNPQRSTVWXZ';
	    }
	    if ($strength & 2) {
	        $vowels .= "AEUY";
	    }
	    if ($strength & 4) {
	        $consonants .= '23456789';
	    }
	    if ($strength & 8) {
	        $consonants .= '@#$%';
	    }
	
	    $password = '';
	    $alt = time() % 2;
	    for ($i = 0; $i < $length; $i++) {
	        if ($alt == 1) {
	            $password .= $consonants[(rand() % strlen($consonants))];
	            $alt = 0;
	        } else {
	            $password .= $vowels[(rand() % strlen($vowels))];
	            $alt = 1;
	        }
	    }
    return $password;
	} 
	
public function set_password() {
	
}  

}
?>