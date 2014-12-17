<?php

require_once(LIB_PATH.DS.'database.php');

class UserHistory extends DatabaseObject {
    
    protected static $table_name = "sellatext_checkout";
    protected static $db_fields = array('cart_id', 'user_id', 'pay_type', 'email', 'addr_1','addr_2','city','state','zip','tracking','submitted','ship_by','price','status');
    //public $id;
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
	public $submitted;
	public $ship_by;
	public $price;
	public $status;
	
	public function get_history($user_id) {
	global $database;
		if(!empty($user_id)) {
			$sql = "SELECT c.cart_id,pay_type,tracking,submitted,ship_by,(sum(price*qty)) as price,s.status ";
			$sql .="FROM sellatext_cart c ";
			$sql .="LEFT JOIN sellatext_checkout on c.cart_id=sellatext_checkout.cart_id ";
			$sql .="LEFT JOIN sellatext_status s on sellatext_checkout.status = s.id ";
			$sql .="WHERE c.user_id = " . $user_id;
			$sql .=" GROUP BY c.cart_id";
			$result_array = static::find_by_sql($sql);
		return !empty($result_array) ? $result_array : false;
		}
	}// get_history
	

}//CartHistory

?>