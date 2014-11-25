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
<div id="page"> 
	<h2><?php echo output_message($message); ?></h2>
            <h1>Register</h1>
            <form method="POST" action="" id ="user-register-form">
                <fieldset>
                    <ol>
                        <li>
                            <label for="firstName">First Name</label>
                            <input type="text" name="firstName" id="firstName" required="required">
                        </li>
                        <li>
                            <label for="lastName">Last Name</label>
                            <input type="text" name="lastName" id="lastName" required="required">
                        </li>
                        <li>
                            <label for="email">Email</label>
                            <input type="text" name="email" id="email" required="required">
                        </li>
                        <li>
                            <label for="password1">Password</label>
                            <input type="password" name="password1" id="password1" required="required">
                        </li>
                        <li>
                            <label for="password2">Repeat Password</label>
                            <input type="password" name="password2" id="password2" required="required">
                        </li>
                    </ol>
                </fieldset>
                <input class="green" type="submit" name="submit" value="Register">
            </form>
</div>


<?
require_once 'footer.php';
?>
