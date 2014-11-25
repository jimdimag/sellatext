<?php
require_once(LIB_PATH.DS.'database.php');

class DatabaseObject extends MySQLDatabase {
   
   protected static $table_name = "sellatext_users"; 
   protected static $db_fields = array('id', 'email', 'password', 'first_name', 'last_name');
    
    // Common Database Methods
public static function find_all($id=0) {
	global $database;
   return static::find_by_sql("SELECT * FROM ".static::$table_name." where cart_id=".$database->escape_value($id));
      
   } 
   
public static function find_by_id($id=0) {
      global $database;
      $result_array = static::find_by_sql("select * from ".static::$table_name." where user_id=".$database->escape_value($id));
      return !empty($result_array) ? array_shift($result_array) : FALSE;
   }
   
public static function find_by_sql($sql="") {
       global $database;
       $result_set = $database->query($sql);
       $object_array = array();
       while ($row = $database->fetch_array($result_set)) {
           $object_array[] = static::instantiate($row);
       }
       return $object_array;
   }

public static function count_all() {
    global $database;
    $sql = "SELECT COUNT(*) FROM " .static::$table_name;
    $result_set = $database->query($sql);
    $row = $database->fetch_array($result_set);
    return array_shift($row);
}

private static function instantiate($record) {
    $class_name = get_called_class();
    $object = new $class_name;
    /*$object->id         = $record['id'];
$object->username   = $record['username'];
$object->password   = $record['password'];
$object->first_name = $record['first_name'];
$object->last_name  = $record['last_name']; */
    
    foreach ($record as $attribute => $value) {
        if($object->has_attribute($attribute)) {
            $object->$attribute = $value;
        }
    }
    return $object; 
  }

private function has_attribute($attribute) {
    $object_vars = static::attributes();
    return array_key_exists($attribute, $object_vars);
}

protected function attributes() {
    //return get_object_vars($this);
    $attributes = array();
    foreach (static::$db_fields as $field) {
        if(property_exists($this, $field)) {
            $attributes[$field] = $this->$field;
        }
    }
    return $attributes;
}

protected function sanitized_attributes() {
    global $database;
    $clean_attributes = array();
    //Sanitize values before submitting
    foreach(static::attributes() as $key=>$value) {
        $clean_attributes[$key] = $database->escape_value($value);
    }
    return $clean_attributes; 
}

public function save() {
    return isset($this->id) ? $this->update() : $this->create();
}

public function create() { 
    global $database; 
    $attributes = static::sanitized_attributes(); 
    $sql = "INSERT INTO " .static::$table_name." (";
    $sql .= join(", ", array_keys($attributes));
    $sql .= ") VALUES ('";
    $sql .= join("', '", array_values($attributes));
    $sql .= "')"; 
    if($database->query($sql)) {
        $this->id = $database->insert_id();
        return TRUE;
    } else {
        return FALSE;
    }
}

public function update() {
   global $database;
   $attributes = $this->sanitized_attributes();
   foreach($attributes as $key=>$value) {
       $attribute_pairs[] = "{$key}='{$value}'";
   }
   $sql = "UPDATE " .static::$table_name." SET "; 
   $sql .= join(", ", $attribute_pairs);
    $sql .= " WHERE id =" .$database->escape_value($this->id);
    $database->query($sql);
    return ($database->affected_rows() == 1) ? TRUE : FALSE;
}

public function update_cart(){
	global $database;
	$sql = "UPDATE " .static::$table_name." SET qty= " .$database->escape_value($this->qty);
	$sql .= " WHERE id= " . $database->escape_value($this->id);
	$database->query($sql);
    return ($database->affected_rows() >= 1) ? TRUE : FALSE;
}
    
public function delete() {
    global $database;
    $sql = "DELETE FROM " .static::$table_name." ";
    $sql .= "WHERE ID=" . $database->escape_value($this->id);
    $sql .= " LIMIT 1";
    $database->query($sql);
    return ($database->affected_rows() == 1) ? TRUE : FALSE;
}

public function delete_cart() {
	global $database;
    $sql = "DELETE FROM " .static::$table_name." ";
    $sql .= "WHERE cart_id=" . $database->escape_value($this->cart_id);
    $database->query($sql);
    return ($database->affected_rows() >= 1) ? TRUE : FALSE;
}

public function update_user() {
	global $database;
	$sql = "UPDATE " .static::$table_name." SET user_id= " .$database->escape_value($this->user_id);
	$sql .= " WHERE cart_id= " . $database->escape_value($this->cart_id);
	$database->query($sql);
    return ($database->affected_rows() >= 1) ? TRUE : FALSE;
	
}
    
}

?>