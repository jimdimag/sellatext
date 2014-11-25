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
		$_SESSION['user_id'] = $found_user->id; 
		$_SESSION['first_name'] = $found_user->fname; 
		$_SESSION['last_name'] = $found_user->lname; 
		
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
    $session->message("Username/password combination incorrect.");
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
		<form action="login.php" method="post">
		  <table>
		    <tr>
		      <td>Username:</td>
		      <td>
		        <input type="text" name="email" maxlength="30" value="<?php echo htmlentities($email); ?>" />
		      </td>
		    </tr>
		    <tr>
		      <td>Password:</td>
		      <td>
		        <input type="password" name="password" maxlength="30" value="<?php echo htmlentities($password); ?>" />
		      </td>
		    </tr>
		    <tr>
		      <td colspan="2">
		        <input type="submit" name="submit" value="Login" />
		      </td>
		    </tr>
		  </table>
		</form>
<br>
<?php require_once 'footer.php'; ?>
