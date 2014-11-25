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
            $message = 'Your new temporary password: '.$genPassword;
            if(mail($to, $subject, $message)){
                $session->message("Check your mail. Password sent!");
                header('Location: '.$buybackPageNames['forgotPassword']);
                exit();
            } else {
                $session->message("An error occurred, and your email was not sent.");
            }
        } else {
            $session->message("An error occurred, and your Password was not reset.");
        }
    } else {
        $session->message("An error occurred, we could not find your email address.  Are you sure that you  have registered?");
    }
    
}


?>
<h2><?php echo output_message($message); ?></h2>
<div id="page"> 
            <form  id="user-forgot-pass" method="POST" action="">                                
                <h1>Reset Password</h1>
                <strong>Did you forget your password?</strong>
                <p><strong> Not to worry.</strong></p>
                <br><p>Enter in the email address you used to register with, and we&#39;ll send you a new temporary password.</p>
                <fieldset>
                    <ol>
                        <li>
                            <label for="email">Email Address</label>
                            <input type="text" name="email" id="email" required="required">
                        </li>
                    </ol>
                </fieldset>
                <input type="submit" name="submit" value="Reset Password">
            </form>
</div>



<?
require_once 'footer.php';
?>
