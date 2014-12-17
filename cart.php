<?php
$title = "Cart";
require_once('includes/initialize.php');
$buyback_cartNeeded = true;
$total=0;

require_once 'header.php';

//if (isset($_SESSION['buyback_cartId'])){
 $cart_id = $_SESSION['buyback_cartId']; 
//} else {
	//$cart_id=0;echo "<br>cart id got set as: ".$_SESSION['buyback_cartId']; 
//}
if(isset($_SESSION['user_id']) && $cart_id==0){ 
	$results = Cart::get_users_cart($_SESSION['user_id']);
	$cart_id = $results->cart_id; 
}
if(isset($_POST['command'])){
    if($_POST['command'] == 'addItemToCart'){
        $results = Cart::add_to_cart($cart_id,trim($_POST['isbn']));
		if($results && $results->create()) {
			$message="Item Added!";
			$_SESSION['buyback_cartId']=$results->cart_id;
			$cart_id = $_SESSION['buyback_cartId']; 
			
		}

    } else if($_POST['command'] == 'emptyCart'){
        $results = Cart::remove_cart($cart_id);
        if($results && $results->delete_cart()){
            $session->message("Cart emptied!");
        } else {
            $session->message("An error occurred and your cart was not emptied.");
        }
		
    } else if($_POST['command'] == 'updateCart'){
        foreach($_POST['qty'] as $id => $quantity){ 
        	//if($quantity < $cart->qty){ 
            $results = Cart::set_cart_qty($id, $quantity);
            if($results && $results->update_cart()){
            	$message="Cart updated!";
		        } else {
		            $session->message("Sorry, we can not take that many items. Your cart was not updated!");
		        }
           
        } //for each
    }
}


/*if(isset($_GET['isbn13'])){ 
    $results = Cart::add_to_cart($cart_id,trim($_POST['isbn']));

        if($results['success']){
            $msg->add('s', 'Item added!');
        } else {
			$msg->add('e', array_pop($results['message']));
        }<?php echo $cart[$i]->id; ?>)
}
*/
$cart = Cart::get_cart_contents($cart_id);
$settings = Settings::get_site_settings();

?>
<div class="container" id="page">
<h2><?php echo output_message($message); ?></h2>
<br/>
<h1>Your Cart</h1>

<?php $max = count($cart);
if (!empty($cart)): ?>
    
	<?php
require_once 'search.php'; 
?>
                    <form action="" method="POST">
                        <input type="hidden" name="command" value="updateCart">
                        <h1 class="entry-title">Result</h1>
                        
                        <table width="650" cellpadding="2" cellspacing="2" id="book-search" class="table">
			    <tr style="color:#FFF;">
                <th height="30" bgcolor="#666666">&nbsp;</th>
                <th bgcolor="#666666">Product</th>
                <th bgcolor="#666666">Price Each</th>
                <th bgcolor="#666666">Quantity</th>
                <th bgcolor="#666666">Total Price</th>
                <th bgcolor="#666666">Remove</th>
            </tr>

                            <?php for ($i=0;$i<$max;$i++):?>
                                <tr valign="top">                                
                                    <td width="135"><img src="<?php echo $cart[$i]->image; ?>" width="125" height="100" alt="<?php echo $cart[$i]->title; ?>" class="cover"></td>
                                    <td width="300" style="vertical-align:top">
                                        <strong><?php echo $cart[$i]->title; ?></strong>
                                        <br><?php if(!empty($cart[$i]->author)){?>
                                        by <?php echo $cart[$i]->author; }?>
                                        <br><?php echo $cart[$i]->binding; ?>: <?php echo $cart[$i]->pages; ?> pages
                                        <?php
                                        if (!empty($cart[$i]->isbn)) {
                                            echo '<br>ISBN-13: ' . $cart[$i]->isbn;
                                        }
                                        if (!empty($cart[$i]->isbn10)) {
                                            echo '<br>ISBN-10: ' . $cart[$i]->isbn10;
                                        }
                                        ?>                                    
                                    </td>
<td style="vertical-align:top"><?php echo '$' . number_format($cart[$i]->price, 2); ?></td>
<td style="vertical-align:top; text-align:center"><input type="text" name="qty[<?php echo $cart[$i]->id; ?>]" class="qty" value="<?php echo $cart[$i]->qty;?>" style="width: 35px;" ></td>

<td style="vertical-align:top" class="total"><?php echo '$' .number_format(($cart[$i]->price * $cart[$i]->qty), 2); ?></td>
<td style="vertical-align:top; text-align:center">
    <a href="delete.php?cart_item_id=<?php echo $cart[$i]->id;?>">
    <img src="images/delete.gif" horizontal-align="center" title="Remove <?php echo $cart[$i]->title; ?>" alt="Remove <?php echo $cart[$i]->title; ?>">
    </a>
</td>                            
                                </tr>
<?php $total = $total+($cart[$i]->price * $cart[$i]->qty);?>
                            <?php endfor; ?>
                            
                        </table>          
                        <input type="submit" id="updateCart" value="Update Cart" class="btn btn-primary btn-default" role="button">              
                    </form>
                    <div id="buycart" class="bottom-sale">
                            <h3><p><?php echo strftime("Must be shipped by  %m/%d/%y", (strtotime("+".$settings->days_ship." days"))); ?>.</h3>
                            <?php
                                if ($total < $settings->min_amount) {
                                    echo '<br>Minimum $' . $settings->min_amount . ' sale required!';
                                }
                            ?></p>
                            <h3>We pay you: $<?php echo number_format($total,2,'.','');?></h3>
                            <h3>Instructor&rsquo;s edition will be paid at 30% less than quoted price, and annotated editions 90% less.</h3>
                            
                            
                            
                            <form action="" method="post">
                            	 
                                <input type="hidden" name="command" value="emptyCart">
                                <input type="submit" value="Empty Cart" class="btn btn-warning btn-default" role="button">
                                <?php if ($total > $settings->min_amount): ?>
                                <a href="shoppingCart.php" class="btn btn-success btn-default active" role="button">Proceed to Checkout</a>
                            <?php else: ?>
                                <p><input type="button" value="Proceed to Checkout" disabled="disabled">
                                    <br><span class="warning">You must sell a minimum of 
                                    $<?php echo $settings->min_amount; ?> before you can 
                                    checkout.</span></p>
                            <?php endif; ?>
                            </form>
<?php if(isset($cart[$i]->user_id)) {$_SESSION['user_id'] = $cart[$i]->user_id;}
if(isset($cart_id)) {$_SESSION['buyback_cartId'] = $cart_id;}
?>
                            
                    </div>                
    </div>


<?php else: ?>
    <div id="page">
            <?php
require_once 'search.php';
?>   
                    <!--<h1 class="entry-title">Cart</h1>-->
                    <table width="644" cellspacing="2" cellpadding="2" class="table">
                        <tbody><tr style="background-color:#3399CC; color:#FFFFFF; font-weight:bold;">
                        <td>&nbsp;</td>
                        <td>Book</td>
                        <td>Price</td>
                        <td>Qty</td>
                        <td>&nbsp;</td>
                        </tr>
                        <tr style="background-color:#3399CC; color:#FFFFFF;">
                        <td align="right" colspan="4"><b>Total</b>&nbsp;&nbsp;</td>
                        <td><b>0</b></td>                      
                        </tbody>
                    </table>
   </div>
<?php endif; ?>
<BR><BR>

<?
include 'footer.php';
?>
