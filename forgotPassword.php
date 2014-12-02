<?php

$title = "Forgot Password";
require_once('includes/initialize.php');
require_once 'header.php';


if(isset($_SESSION['buyback_userId'])){
    $session->message("You are already logged in.");
    redirect_to('cart.php');
    exit;
}

if(isset($_POST['email'])){
    
    $results = User::get_id(trim($_POST['email']));
    if($results){  
             $id = $results->user_id;
		     
         // Generate the password, save it to the API, and send it.
        $genPassword = User::generatePassword();
        $results = User::set_password($id,$genPassword);
        
        if($results){                       
            $to = $_POST['email'];
            $subject = 'Your new SellAText.net password';
            $message1 = 'Your new temporary password: '.$genPassword;
            if(mail($to, $subject, $message1)){
                $message="Check your mail. Password sent!";
                header('Location: '.$buybackPageNames['forgotPassword']);
                exit();
            } else {
                $message="An error occurred, and your email was not sent.";
            }
        } else {
            $message="An error occurred, and your Password was not reset.";
        }
    } else {
       $message="An error occurred, we could not find your email address.  Are you sure that you  have registered?";
    }
    
}


?>
<h2><?php echo output_message($message); ?></h2>
<div id="page" class="container"> 
            <form  id="user-forgot-pass" method="POST" action="" role="form">                                
                <h1>Reset Password</h1>
                <strong>Did you forget your password?</strong>
                <p><strong> Not to worry.</strong></p>
                <br><p>Enter in the email address you used to register with, and we&#39;ll send you a new temporary password.</p>
                <fieldset>
                    <div class="form-group"> 
                            <label for="email"class="col-sm-2 control-label">Email Address</label>
                            <div class="col-sm-10">  
                            <input type="email" name="email" id="email" required="required">
                        </div>
                    </div>
                </fieldset>
                <div class="form-group"> 
			    	<div class="col-sm-10">     
					<input type="submit" name="submit" value="Reset Password" class="btn btn-primary btn-default" role="button">
			     	</div> 
 				</div>
            </form>
</div>



<?
require_once 'footer.php';
?>
