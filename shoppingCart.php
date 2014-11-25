<?php
$title = "Cart Review";
require_once('includes/initialize.php');
//$buyback_cartNeeded = true;

require_once 'header.php';
$total =0;
 
if (isset($_SESSION['buyback_cartId'])){
 $cart_id = $_SESSION['buyback_cartId']; 
} elseif(isset($_SESSION['user_id'])){ 
	$results = Cart::get_users_cart($_SESSION['user_id']);
	$cart_id = $results->cart_id;  
}
if(!isset($_SESSION['user_id'])){ 
    $session->message("Please log in before you go through checkout.");
    redirect_to('login.php');
    exit;
} else {
	$user_id = $_SESSION['user_id'];
	$fname = $_SESSION['first_name']; echo "First name is: ".$fname;
	$lname = $_SESSION['last_name'];
}
//echo "cart id is: ".$cart_id;die;
/* $results = Cart::update_userId($cart_id,$user_id);
if($results && $results->update_user()) {
	$session->message("All ok.");
} else {
	$session->message("Please log in before you go through checkout.");
    redirect_to('login.php');
    exit;
}*/

$cart = Cart::get_cart_contents($cart_id);
$settings = Settings::get_site_settings();
$max = count($cart);

?>
<h2><?php echo output_message($message); ?></h2>

 <div id="page"> 
            <h2>Review Cart</h2>
            <strong>Take a moment to look over the books you&#39;re selling before you finalize your order.</strong><br>
            <?php if (!empty($cart)): ?>
            <table width="650" cellpadding="2" cellspacing="2" id="book-search">
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
<td style="vertical-align:top; text-align:center"><?php echo $cart[$i]->qty; ?></td>
<td style="vertical-align:top" class="total"><?php echo '$' .number_format(($cart[$i]->price * $cart[$i]->qty), 2); ?></td>
<td style="vertical-align:top; text-align:center">
    <a href="delete.php?cart_item_id=<?php echo $cart[$i]->id;?>">
    <img src="images/delete.gif" horizontal-align="center" title="Remove <?php echo $cart[$i]->title; ?>" alt="Remove <?php echo $cart[$i]->title; ?>">
    </a>
</td>                            
                                </tr>
<?php $total = $total+($cart[$i]->price * $cart[$i]->qty);
if($cart[$i]->user_id == 0) {
	$results = Cart::update_userId($cart_id,$user_id);
if($results && $results->update_user()) {
	$session->message("cart Updated");
}
}
?>
                            <?php endfor; ?>         
            </table>
            <div class="bottom-sale">
                    <p><?php echo strftime("Must be shipped by  %m/%d/%y", (strtotime("+".$settings->days_expire." days"))); ?>.
                    <?php
                        if($total < $settings->min_amount):
                            echo '<p><span class="warning">You must sell a minimum of $'. $settings->min_amount . 
                                    ' before you can checkout.</span></p>';
                        else:
                    ?>
                   <h3>Your Sale: $<?php echo number_format($total,2,'.','');?></h3>
            </div>                       
            <form id ="buyback_processOrder" class="clear-from" method="POST" action="processOrder.php">
                <input type="hidden" name="paymentType" value="none" checked="checked">
                <h2>Payment Information</h2>
                <fieldset>
                    <span class="question-form">How would you like to be paid?</span>
                    <ol>
                        <li>
                            <div class="radio-input">
                                    <input type="radio" name="paymentType" value="paypal" id="paypal">
                                    <label for="paypal">PayPal</label>
                            </div>
                                <strong>Paid within 10 days of receipt of your book.</strong>
                            <fieldset>                              
                                <ol class="subtitle">
                                    <li>
                                        <label cass="subtitle" for="paypalEmail">Your PayPal Email:</label>
                                        <input type="text" name="paypalEmail" id="paypalEmail" value="<?php echo $user['paypal_email']; ?>">
                                    </li>
                                </ol>
                            </fieldset>
                        </li>
                        <li class="row">
                            <div class="radio-input">
                                  <input type="radio" name="paymentType" value="check" id="check">
                            <label for="check">Check</label>
                            </div>                          
                                <strong>Paid within 10 days of receipt of your book. <p> Please allow an additional 
                                5-7 days for check to be delivered in the mail.</p></strong>
                            <fieldset  id="data-paid">
                                <legend>Your mailing address:</legend>
                                <ol>
                                    <li>
                                        <label for="first_name">First Name</label>
                                        <?php echo  $fname;?>
                                    </li>
                                    <li>
                                        <label for="last_name">Last Name</label>
                                        <input type="text" name="lastName" value="<?php echo $lname; ?>">
                                    </li>
                                    <li>
                                        <label for="address1">Address, line 1</label>
                                        <input type="text" name="address1" value="<?php echo $user['address1']; ?>">
                                    </li>
                                    <li>
                                        <label for="address2">Address, line 2</label>
                                        <input type="text" name="address2" value="<?php echo $user['address2']; ?>">
                                    </li>
                                    <li>
                                        <label for="city">City</label>
                                        <input type="text" name="city" value="<?php echo $user['city']; ?>">
                                    </li>
                                    <li>
                                        <label for="state">State</label>
                                        <?php //echo buyback_getStateList($user['state']); ?>
                                    </li>
                                    <li>
                                        <label for="zip">Zip Code</label>
                                        <input type="text" name="zip" value="<?php echo $user['zip']; ?>">
                                    </li>
                                </ol>
                            </fieldset>
                        </li>
                    </ol>
                </fieldset>
                    <div class="bottom-sale">
                    <input type="submit" value="Create Order">                
                    <?php endif; ?>                    
                    <a class="checkout" href="cart.php">Return to your cart to make changes</a>
                    </div>
                    <?php else: ?>
                        <p>Your cart is empty!</p>                    
                    <?php endif; ?>                   
            </form>
  </div>
<?
include 'footer.php';
?>
