<?php
$title="Register";
require_once('includes/initialize.php');
require_once 'header.php';


if(isset($_POST['email'])){
    if($_POST['password1'] == $_POST['password2']){
        $params = array(
            'first_name' => $_POST['firstName'],
            'last_name' => $_POST['lastName'],
            'email' => $_POST['email'],
            'password' => $_POST['password1']
        );
        $registrationResults = User::register_user($params);
        if($registrationResults){
            $_SESSION['buyback_userId'] = $registrationResults['id'];
            $session->message("Registration successful!");
            if($_SESSION['buyback_cartId'] > 0){
                redirect_to('cart.php');
                exit;
            } else {
                redirect_to('index.php');
                exit;
            }
        } else {
        	$session->message("There was an error registering your account");
        }
    } else {
      $session->message("Your passwords do not match. Please try again.");
    }
}


?>
<div id="page" class="container"> 
	<h2><?php echo output_message($message); ?></h2>
            <h1>Register</h1>
            <form method="POST" action="" id ="user-register-form" role="form">
                <fieldset>
                    <div class="form-group">   
                            <label for="firstName"class="col-sm-2 control-label">First Name</label>
                            <div class="col-sm-10">
                            <input type="text" name="firstName" id="firstName" required="required">
                            </div>
                    </div>
                        
                	<div class="form-group">   
                            <label for="lastName"class="col-sm-2 control-label">Last Name</label>
                            <div class="col-sm-10">
                            <input type="text" name="lastName" id="lastName" required="required">
                            </div>
                    </div>
                        
                    <div class="form-group">   
                            <label for="email"class="col-sm-2 control-label">Email</label>
                            <div class="col-sm-10">
                            <input type="email" name="email" id="email" required="required">
                            </div>
                    </div>
                        
                    <div class="form-group">   
                        <label for="password" class="col-sm-2 control-label">Password:</label>
    					<div class="col-sm-10">
                        <input type="password" name="password1" id="password1" required="required">
                        </div>
                    </div>
                        
                    <div class="form-group">   
                            <label for="password2"class="col-sm-2 control-label">Repeat Password</label>
                            <div class="col-sm-10">
                            <input type="password" name="password2" id="password2" required="required">
                            </div>
                    </div>
                        
                </fieldset>
                <div class="form-group"> 
			    	<div class="col-sm-10">     
					<input type="submit" name="submit" value="Register"class="btn btn-primary btn-default" role="button">
			     	</div> 
 				</div>
            </form>
</div>


<?
require_once 'footer.php';
?>
