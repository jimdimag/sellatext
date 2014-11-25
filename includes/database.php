<?php
//require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . "config.php");
require_once(LIB_PATH.DS."config.php");

class MySQLDatabase {
    
  private $conn;
  public $last_query;
  private $magic_quotes_active;
  private $real_escape_string_exists;
  
  function __construct() {
      $this->open_connection();
      $this->magic_quotes_active = get_magic_quotes_gpc();
        $this->real_escape_string_exists= function_exists( "mysqli_real_escape_string" ); 
  }
  public function open_connection() { 
      $this->conn = mysqli_connect(DB_SERVER, DB_USER, DB_PASS);
       
       if (!$this->conn) {
           die("Database connection failed: " .mysqli_connect_error($this->conn));
       } else {
           $db_select = mysqli_select_db($this->conn, DB_NAME);
           if(!$db_select) {
               die("Database selection failed: " .mysqli_connect_error($this->conn));
           }
       }
   }
  
  public function close_connection() {
      if(isset($this->conn)) {
          mysqli_close($this->conn);
          unset($this->conn);
      }
  } 

  public function query ($sql)  {
      $this->last_query = $sql;
      $result = mysqli_query($this->conn,$sql);
      $this->confirm_query($result);
      return $result;
  }
  
  public function escape_value( $value ) {
        
        if( $this->real_escape_string_exists ) { // PHP v4.3.0 or higher
            // undo any magic quote effects so mysql_real_escape_string can do the work
            if( $this->magic_quotes_active ) { $value = stripslashes( $value ); }
            $value = mysqli_real_escape_string($this->conn, $value );
        } else { // before PHP v4.3.0
            // if magic quotes aren't already on then add slashes manually
            if( !$this->magic_quotes_active ) { $value = addslashes( $value ); }
            // if magic quotes are active, then the slashes already exist
        }
        return $value;
    }
  
  public function fetch_array($result_set) {
      return mysqli_fetch_array($result_set);
  }
  
  public function num_rows($result_set) {
      return mysqli_num_rows($result_set); 
  }
  
  public function insert_id()  {
      return mysqli_insert_id($this->conn);
  }
  
  public function affected_rows()  {
      return mysqli_affected_rows($this->conn);
  }
  
  private function confirm_query($result) {
      if(!$result) {
        $output = "Database query failed: " .mysqli_error($this->conn). "<br>";
          $output .="Last Query: " .$this->last_query;
          die($output);  
      }
  }

}


$database = new MySQLDatabase();
$db =& $database;

?>