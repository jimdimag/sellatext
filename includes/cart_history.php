<?php

require_once(LIB_PATH.DS.'database.php');

class CartHistory extends DatabaseObject {
    
    protected static $table_name = "sellatext_checkout";
    protected static $db_fields = array('id','cart_id', 'user_id', 'pay_type', 'email', 'addr_1','addr_2','city','state','zip','tracking');
    public $id;
    public $cart_id;
    public $user_id;
    public $pay_type;
	public $email;
	public $addr_1;
	public $addr_2;
	public $city;
	public $state;
	public $zip;
	public $tracking;
	
	public function get_history($user_id) {
	global $database;
		if(!empty($user_id)) {
			$result = static::find_all($user_id);
			if($result) { 
				return $result;
			} else {
				return FALSE;
			}
		}
	}// get_history
	
}//CartHistory