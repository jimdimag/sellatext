<?php
require_once(LIB_PATH.DS.'database.php');

class Cart extends DatabaseObject {
    
    protected static $table_name = "sellatext_cart";
    protected static $db_fields = array('id','cart_id', 'user_id', 'isbn', 'price', 'qty','title','image','binding','pages','author','weight');
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
		$amazon = Amazon::get_info($isbn);
	//get cart id if there is not one
		if (empty($cart_id)) {
			$cart_id = static::get_new_cart_id();
		}
		if(!empty($amazon['payPrice']) && !empty($isbn)) {
			$cart = new Cart();
			$cart->price = $amazon['payPrice'];
			$cart->qty = $amazon['qty'];
			$cart->cart_id =$cart_id;
			$cart->isbn = $isbn;
			$cart->title = $amazon['title'];
			$cart->binding = $amazon['binding'];
			$cart->image = $amazon['ImageURL'];
			$cart->pages = $amazon['pages'];
			$cart->author = $amazon['author'];
			$cart->weight = $amazon['weight'];
			/*if(isset($cart->id)) { 
        		static::create($cart);
    		}*/
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
	
} // Class

?>