<?php
$title="Log in";
require_once("includes/initialize.php");
if($session->is_logged_in()) {
  if($_SESSION['buyback_cartId'] > 0){
                redirect_to('cart.php');
                exit;
  }
	}
require_once 'header.php';


// Remember to give your form's submit tag a name="submit" attribute!
if (isset($_POST['submit'])) { // Form has been submitted.

  $email = trim($_POST['email']);
  $password = trim($_POST['password']);
  
  // Check database to see if username/password exist.
	$found_user = User::authenticate($email, $password);
	
  if ($found_user) {
  	
    $session->login($found_user);
		log_action('Login', "{$found_user->email } logged in.");
		$_SESSION['user_id'] = $found_user['id']; 
		$_SESSION['first_name'] = $found_user['fname']; 
		$_SESSION['last_name'] = $found_user['lname'];
		$_SESSION['email'] = $found_user['email']; 
		
    if(($_SESSION['buyback_cartId']) && ($_SESSION['user_id'])){
    	$results = Cart::update_userId($_SESSION['buyback_cartId'],$_SESSION['user_id']);
		if($results && $results->update_user()) {
			redirect_to('shoppingCart.php');
		                exit;
		}
                
            } else {
                redirect_to('index.php');
                exit;
            }
  } else {
    // username/password combo was not found in the database
    $message="Username/password combination incorrect.";
	echo $message;
  }
  
} else { // Form has not been submitted.
  $email = "";
  $password = "";
}

?>
<div class="container">
		<h2>Login</h2>
		<?php echo output_message($message); ?>
			<ul>
                <li><a href="forgotPassword.php">Forget Your Password?</a></li>
                <li><a href="register.php">Register</a></li>
            </ul>
		<form action="" method="post" role="form">
		  
    <div class="form-group">    
	    <label for="username" class="col-sm-2 control-label">Email:</label>
	    <div class="col-sm-10">
	    <input type="email" name="email" maxlength="30" value="<?php echo htmlentities($email); ?>">
	    </div>
    </div>
		      
	<div class="form-group">    
	    <label for="password" class="col-sm-2 control-label">Password:</label>
	    <div class="col-sm-10">
	    <input type="password" name="password" maxlength="30" value="<?php echo htmlentities($password); ?>">
	    </div>
    </div>	 
<div class="form-group"> 
    	<div class="col-sm-10">     
		<input type="submit" name="submit" value="Login"class="btn btn-primary btn-default" role="button">
	     </div> 
 </div>
		</form>
		</div>
<br><br>
<?php require_once 'footer.php'; ?>
