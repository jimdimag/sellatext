<?php 
ini_set('display_errors',"1");
$title="Home";
require_once('includes/initialize.php');
 include ('header.php'); 

/*if(isset($_GET['cart_id'])) {
	$_SESSION['buyback_cartId'] = $_GET['cart_id'];
} else {
  $buyback_cartNeeded=true;
}
        

if($buyback_cartNeeded===true) {
	if(!isset($_SESSION['buyback_cartId'])){
		$cart = new Cart(); 
		$result = $cart->get_new_cart_id();
		if($result['success']==true) {
			$_SESSION['buyback_cartId'] = $result['cart_id']; 
		} else {
			$msg->add('e', array_pop($results['message']));
		}
	}
	$buyback_cartId = $_SESSION['buyback_cartId']; 
}
$buyback_userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;*/
 ?>
<div class="container">
<div class="row">
	<h2><?php echo output_message($message); ?></h2>
	<?php
require_once ('search.php');
?>
<p><b>SellAText.net</b> is the best place to sell your unwanted text books.  At <b>SellAText.net</b> we will even buy your <b>Instructor Edition (IE)</b> text books.</p>
<p>Please be sure to refer to our conditioning guide so you know what we will accept and what discounts may be taken due to the condition of the book.</p>
<p>Getting paid for your unwanted textbooks is as easy as 1..2..3..</p>
    <ol>
        <li>Enter in the ISBN number of the used text book you wish to sell us.</li>
        <li>When all the text books are entered, proceed to the checkout, select how you want to get paid.
        At <b>SellAText.net</b> we give you the choice of using PayPal or getting a check in the mail</li>
        <li>Print out the pre-paid shipping label and send the text books to us.  Upon verification of the condition of the book(s), <b>SellAText.net</b> will pay you.</li>
    </ol>
</div>
<br><br>
<?php include ('footer.php'); ?>