<?php

$title="change Password";
require_once('includes/initialize.php');
require_once 'header.php';

if(!isset($_SESSION['user_id'])){
    $session->message('You are not authorized to visit that page. Please log in.');
    redirect_to('login.php');
    exit;
}
$user_id = $_SESSION['user_id'];
if(isset($_POST['password1'])){
    if($_POST['password1'] == $_POST['password2']){
        $result = User:: set_password($user_id, $_POST['password1']);
		if($result) {
			$message = "Password was successfully changed!";
		}
	} else {
		$message = "Your Passwords do not match.  Please try again.";
	}
}
?>

<div id="page" class="container">
	 <h2><?php echo output_message($message); ?></h2>
            <form  id="user-forgot-pass" method="POST" action="" role="form">                                
                <h1>Change Password</h1>
                
                <fieldset>
                    <div class="form-group">   
                        <label for="password" class="col-sm-2 control-label">New Password:</label>
    					<div class="col-sm-10">
                        <input type="password" name="password1" id="password1" required="required">
                        </div>
                    </div>
                        
                    <div class="form-group">   
                            <label for="password2"class="col-sm-2 control-label">Repeat New Password</label>
                            <div class="col-sm-10">
                            <input type="password" name="password2" id="password2" required="required">
                            </div>
                    </div>
                </fieldset>
                <div class="form-group"> 
			    	<div class="col-sm-10">     
					<input type="submit" name="submit" value="Change Password" class="btn btn-primary btn-default" role="button">
			     	</div> 
 				</div>
            </form>
</div>



<?
require_once 'footer.php';
?>