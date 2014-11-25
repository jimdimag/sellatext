<?php

require_once('includes/initialize.php');

if (isset($_SESSION['buyback_cartId'])){
 $cart_id = $_SESSION['buyback_cartId']; 
}

if($_POST['command'] == 'add'){
        $results = Cart::add_to_cart($cart_id,trim($_POST['isbn']));
		if($results && $results->create()) {
			$session->message("Item Added!");
			$_SESSION['buyback_cartId']=$results->cart_id;
			$cart_id = $_SESSION['buyback_cartId']; 
			return $results;
		}

    } else if($_POST['command'] == 'emptyCart'){
        $results = Cart::remove_cart($cart_id);
        if($results && $results->delete_cart()){
            $session->message("Cart emptied!");
        } else {
            $session->message("An error occurred and your cart was not emptied.");
        }
	}
?>