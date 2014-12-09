<?php
require_once(LIB_PATH.DS.'database.php');

class Cart extends DatabaseObject {
    
    protected static $table_name = "sellatext_cart";
    protected static $db_fields = array('id','cart_id', 'user_id', 'isbn', 'price', 'qty','title','image','binding','pages','author','weight','rank');
    public $id;
    public $cart_id;
    public $user_id;
    public $price;
	public $isbn;
	public $qty;
	public $title;
	public $image;
	public $binding;
	public $pages;
	public $author;
	public $weight;
	public $rank;
    
    
	public function isbn1013($isbn) {
	   $isbn = trim($isbn);
	   if(strlen($isbn) == 12){ // if number is UPC just add zero
	      $isbn13 = '0'.$isbn;}
	   else
	   {
	      $isbn2 = substr("978" . trim($isbn), 0, -1);
	      $sum13 = static::genchksum13($isbn2);
	      $isbn13 = "$isbn2$sum13";
	   }
   return ($isbn13);
}

public function genchksum13($isbn) {
   $isbn = trim($isbn);
   $tb = 0;
   for ($i = 0; $i <= 12; $i++)
   {
      $tc = substr($isbn, -1, 1);
      $isbn = substr($isbn, 0, -1);
      $ta = ($tc*3);
      $tci = substr($isbn, -1, 1);
      $isbn = substr($isbn, 0, -1);
      $tb = $tb + $ta + $tci;
   }
   
   $tg = ($tb / 10);
   $tint = intval($tg);
   if ($tint == $tg) { return 0; }
   $ts = substr($tg, -1, 1);
   $tsum = (10 - $ts);
   return $tsum;
}
	
    public function get_new_cart_id() { 
	global $database;
	$sql = "SELECT MAX(cart_id) as cart_id FROM " .static::$table_name;
    $result_set = $database->query($sql); 
	if($result_set) {
    $row = $database->fetch_array($result_set);
		$cart_id = $row['cart_id']+1; 
        //$this->cart_id = $database->insert_id();
        //$cart_id= array('cart_id'=>$cart_id, 'success'=>TRUE);
    } else {
        return FALSE;
    }
	
    return ($cart_id);
	}
	
	public function add_to_cart($cart_id,$isbn) { 
		global $database;
		$isbn = $database->escape_value($isbn);
		$isbn = str_replace("-", "", $isbn);
			if(strlen($isbn) != 13){
			$isbn = static::isbn1013($isbn);
			}
		$amazon = Amazon::get_info($isbn);
		
	//get cart id if there is not one
		if (empty($cart_id)) {
			$cart_id = static::get_new_cart_id();
		}
		if(!empty($amazon['payPrice']) && !empty($isbn)) {
			$cart = new Cart();
			$cart->price 	= $amazon['payPrice'];
			$cart->qty_limit= $qtyLimit;
			$cart->qty		= 1;
			$cart->cart_id 	= $cart_id;
			$cart->isbn 	= $isbn;
			$cart->title 	= $amazon['title'];
			$cart->binding 	= $amazon['binding'];
			$cart->image 	= $amazon['ImageURL'];
			$cart->pages 	= $amazon['pages'];
			$cart->author 	= $amazon['author'];
			$cart->weight 	= $amazon['weight'];
			$cart->rank		= $amazon['rank'];
		}
		return $cart;
	}
	
	public function get_cart_contents($cart_id) {
		global $database;
		if(!empty($cart_id)) {
			$result = static::find_all($cart_id);
			if($result) { 
				return $result;
			} else {
				return FALSE;
			}
		}
	}
	
	public function get_users_cart($user_id) {
		global $database;
		if(!empty($user_id)) {
			$result = static::find_by_id($user_id);
			if($result) { 
				return $result;
			} else {
				return FALSE;
			}
		}
	}

	public function remove_from_cart($id) { 
		global $database;
		if(!empty($id)){
			$cart = new Cart();
			$cart->id = $id;
		}
		return $cart;
	}
	
	public function remove_cart($cart_id) {
		global $database;
		if(!empty($cart_id)){
			$cart = new Cart();
			$cart->cart_id = $cart_id;
		}
		return $cart;
	}
	
	public function update_userId($cart_id, $user_id) {
		global $database;
		if(!empty($cart_id) && !empty($user_id)){
			$cart = new Cart();
			$cart->cart_id = $cart_id;
			$cart->user_id = $user_id;
		}
		return $cart;
	}
	
	public function get_weight($cart_id, $user_id) {
		global $database;
		$sql = "SELECT SUM(weight) as weight, id FROM " .static::$table_name;
		$sql .=" where cart_id = " .$cart_id;
		$sql .=" AND user_id= " .$user_id;
		$result_set = $database->query($sql); 
	if($result_set) {
    $row = $database->fetch_array($result_set);
		$weight = $row['weight']; 
        return ($weight);
    } else {
        return FALSE;
    }
	}//get_weight
	
	public function set_cart_qty($id,$qty) { 
		global $database;
		$sql = "SELECT isbn,rank FROM " .static::$table_name;
		$sql .=" WHERE id = " .$database->escape_value($id);
		$result_set = $database->query($sql); 
		if($result_set) {
	    $row = $database->fetch_array($result_set);
			$isbn = $row['isbn']; 
			$rank = $row['rank']; 
			//if cartt qty < qty limit then... else return false
			$qtyLimit = Qty::get_qty_limit($isbn,$rank);
				if($qty > $qtyLimit){
					
					return FALSE;
				} else {
					$cart = new Cart();
					$cart->id = $id;
					$cart->qty = $qty;
					return $cart;
					}//qty limit check
		}//result_set
	}//set_qty_limit
	
} // Class

?>