<?php
$title = "Cart Review";
require_once('includes/initialize.php');
//$buyback_cartNeeded = true;

require_once 'header.php';
$total =0;
 $settings = Settings::get_site_settings();
 $shipBy = strftime(" %m/%d/%y",(strtotime("+".$settings->days_ship." days")));
  
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
	$fname = $_SESSION['first_name']; 
	$lname = $_SESSION['last_name'];
	$email = $_SESSION['email'];
}

if(isset($_POST['paymentType'])){
	$weight = Cart::get_weight($cart_id, $user_id);
	if(!$weight){
		$weight = 3;
	} 
	
	$shipParams = array('fname'=>$fname,
					'lname'=>$lname,
					'weight'=>$weight,
					);
	if($_POST['paymentType'] == 'paypal'){
		$pay_type = 1;
        $results = Checkout::paypal_checkout($cart_id,$user_id,$pay_type,trim($_POST['paypalEmail'])); 
		if($results &&  $results->create() && ($track = Checkout::rocket($shipParams))) {
			Checkout::send_email($track, $email,$shipBy);
			//$message=(strftime("Thank you.\n  An email will be sent with your shipping label and tracking number.  Please remember to ship your items by " .$shipBy )); 
			
		}else {
			$message="There was an error processing your request.  Please verify that you selected PayPal and filled in your email address.";
		}
	} elseif ($_POST['paymentType']== 'check') {
		$pay_type = 2;
		$params = array('cart_id'=>$cart_id,
						'user_id'=>$user_id,
						'pay_type'=>$pay_type,
						);
						
					$params2 = array('user_id'=>$user_id,
					'fname'=>$fname,
					'lname'=>$lname,
						'email'=>$email,
						'addr_1'=>$_POST['addr_1'],
						'addr_2'=>$_POST['addr_2'],
						'city'=>$_POST['city'],
						'state'=>$_POST['state'],
						'zip'=>$_POST['zip'],
						);
		$results = Checkout::check_checkout($params);
		$results2 = User::update_user($params2);
		if($results && $results->create() && ($track = Checkout::rocket($shipParams))&& $results2->update()) {
			Checkout::send_email($track, $email, $shipBy);
			//$message=(strftime("Thank you.\n  An email will be sent with your shipping label and tracking number.  Please remember to ship your items by  " .$shipBy ));  
		} else {
			$message="There was an error processing your request.  Please verify that you filled in all the required information.";
		}
	}
	
	$id = Checkout::get_checkout_id($cart_id, $user_id);
	$ship = strtotime("+".$settings->days_ship." days");
	$shipBy2=date("Y-m-d", $ship);
	
	$params = array('id'=>$id,
					'cart_id'=>$cart_id,
					'user_id'=>$user_id,
					'pay_type'=>$pay_type,
					'tracking'=>$track,
					'ship'=>$shipBy2,
					);
					
	
	$results = Checkout::update_tracking($params);
	if($results && $results->update_track()) {
		$session->message(strftime("Thank you.\n  An email will be sent with your shipping label and tracking number.  Please remember to ship your items by  " .$shipBy ));
		$mailNotification = Checkout::send_notification($cart_id,$email); 
		$cart_id = 0;
		$_SESSION['buyback_cartId']=$cart_id; 
		redirect_to('index.php');
	}
	
}

$cart = Cart::get_cart_contents($cart_id);

$max = count($cart);

?>
<h2><?php echo output_message($message); ?></h2>

 <div id="page" class="container"> 
            <h2>Review Cart</h2>
            <strong>Take a moment to look over the books you&#39;re selling before you finalize your order.</strong><br>
            <?php if (!empty($cart)): ?>
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
                                    <td ><img src="<?php echo $cart[$i]->image; ?>" width="110" height="110" alt="<?php echo $cart[$i]->title; ?>" class="cover"></td>
                                    <td  style="vertical-align:top">
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
                    <p><?php echo strftime("Must be shipped by  %m/%d/%y", (strtotime("+".$settings->days_ship." days"))); ?>.</p>
                    <?php
                        if($total < $settings->min_amount):
                            echo '<p><span class="warning">You must sell a minimum of $'. $settings->min_amount . 
                                    ' before you can checkout.</span></p>';
                        else:
                    ?>
                   <h3>Your Sale: $<?php echo number_format($total,2,'.','');?></h3>
            </div>                       
<form id ="buyback_processOrder" class="clear-from form-horizontal" method="POST" action="" role="form">
	 <input type="hidden" name="paymentType" value="none" checked="checked">
    <h2>Payment Information</h2>
    <fieldset>
        <h3>How would you like to be paid?</h3>
		<div class="radio">
			<label>
                <input type="radio" name="paymentType" value="paypal" id="paypal" data-toggle="collapse" data-target="#data-paid" checked="checked">
                PayPal
            </label>
        </div>
        <strong>Paid within 10 days of receipt of your book.</strong>
        <fieldset>  
        	 <div class="form-group">                            
                <label for="paypalEmail" class="col-sm-2 control-label">Your PayPal Email:
            	</label>
                    <div class="col-sm-10">
                    <input type="email" name="paypalEmail" id="paypalEmail" placeholder="PayPal Email" value="<?php echo $email; ?>">
                    </div>
			</div>
        </fieldset>
            <div class="radio">
			<label>
                <input type="radio" name="paymentType" value="check" id="check" data-toggle="collapse" data-target="#data-paid">
                Check
            </label>
            </div>                         
            <strong>Paid within 10 days of receipt of your book. <p> Please allow an additional 5-7 days for check to be delivered in the mail.</p></strong>
            
            <fieldset  id="data-paid" class="collapse">
                <legend>Your mailing address:</legend>
                <div class="form-group">
			    <label class="col-sm-2 control-label">First Name</label>
			    	<div class="col-sm-10">
			      	<p class="form-control-static"><?php echo  $fname;?></p>
			    	</div>
			  </div>
              <div class="form-group">
			    <label class="col-sm-2 control-label">Last Name</label>
			    	<div class="col-sm-10">
			      	<p class="form-control-static"><?php echo  $lname;?></p>
			    	</div>
			  </div>      
 	<div class="form-group">    
	    <label for="addr_1" class="col-sm-2 control-label">Address, line 1</label>
	    <div class="col-sm-10">
	    <input type="text" name="addr_1" >
	    </div>
    </div>
    <div class="form-group">    
	    <label for="addr_2" class="col-sm-2 control-label">Address, line 2</label>
	    <div class="col-sm-10">
	    <input type="text" name="addr_2" >
	    </div>
    </div>
     <div class="form-group">
         <label for="city"class="col-sm-2 control-label">City</label>
         <div class="col-sm-10">
            <input type="text" name="city" >
        </div>
    </div>
    <div class="form-group">
        <label for="state"class="col-sm-2 control-label">State</label>
        <div class="col-xs-4">
        <select class="form-control" name="state">
        	<option value=""></option>
        	<option value="AL">Alabama</option>
                <option value="AK">Alaska</option>
                <option value="AZ">Arizona</option>
                <option value="AR">Arkansas</option>
                <option value="CA">California</option>
                <option value="CO">Colorado</option>
                <option value="CT">Connecticut</option>
                <option value="DE">Delaware</option>
                <option value="FL">Florida</option>
                <option value="GA">Georgia</option>
                <option value="HI">Hawaii</option>
                <option value="ID">Idaho</option>
                <option value="IL">Illinois</option>
                <option value="IN">Indiana</option>
                <option value="IA">Iowa</option>
                <option value="KS">Kansas</option>
                <option value="KY">Kentucky</option>
                <option value="LA">Louisiana</option>
                <option value="ME">Maine</option>
                <option value="MD">Maryland</option>
                <option value="MA">Massachusetts</option>
                <option value="MI">Michigan</option>
                <option value="MN">Minnesota</option>
                <option value="MS">Mississippi</option>
                <option value="MO">Missouri</option>
                <option value="MT">Montana</option>
                <option value="NE">Nebraska</option>
                <option value="NV">Nevada</option>
                <option value="NH">New Hampshire</option>
                <option value="NJ">New Jersey</option>
                <option value="NM">New Mexico</option>
                <option value="NY">New York</option>
                <option value="NC">North Carolina</option>
                <option value="ND">North Dakota</option>
                <option value="OH">Ohio</option>
                <option value="OK">Oklahoma</option>
                <option value="OR">Oregon</option>
                <option value="PA">Pennsylvania</option>
                <option value="RI">Rhode Island</option>
                <option value="SC">South Carolina</option>
                <option value="SD">South Dakota</option>
                <option value="TN">Tennessee</option>
                <option value="TX">Texas</option>
                <option value="UT">Utah</option>
                <option value="VT">Vermont</option>
                <option value="VA">Virginia</option>
                <option value="WA">Washington</option>
                <option value="WV">West Virginia</option>
                <option value="WI">Wisconsin</option>
                <option value="WY">Wyoming</option>
    	</select>
    	</div>
	</div>
	<div class="form-group">
    	<label for="zip"class="col-sm-2 control-label">Zip Code</label>                     
        <div class="col-sm-10">                
            <input type="text" name="zip" >
        </div>
    </div>    
            </fieldset>
    </fieldset>
        <div class="bottom-sale">
        <input type="submit" value="Create Order" class="btn btn-success btn-default active" role="button">                
        <?php endif; ?>                    
        <a  href="cart.php" class="btn btn-primary btn-default " role="button">Return to your cart to make changes</a>
        </div>
        <?php else: ?>
            <p>Your cart is empty!</p>                    
        <?php endif; ?>                   
</form>
  </div>
  <br>
<?
include 'footer.php';
?>
