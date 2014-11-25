<?php
$buyback_cartNeeded = true;
require_once('includes/initialize.php');

if(isset($_GET['cart_item_id'])){ 
    $results = Cart::remove_from_cart($_GET['cart_item_id']);
    if($results && $results->delete()) {
			$session->message("Item Deleted!");
            
        } else {
            $session->message("An error occurred, and your book was not removed from the cart. Check your ISBN and try again.");
    }
}

redirect_to('cart.php');
exit;
?>
