<?php

$title="Account Edit";
require_once('includes/initialize.php');
require_once 'header.php';

if(!isset($_SESSION['user_id'])){
    $session->message('You are not authorized to visit that page. Please log in.');
    redirect_to('login.php');
    exit;
}
$user_id = $_SESSION['user_id'];
	$fname = $_SESSION['first_name']; 
	$lname = $_SESSION['last_name'];
	$email = $_SESSION['email'];
$customer = User::get_user($user_id);

if(isset($_POST['submit'])){
	$params = array('user_id'=>$user_id,
					'fname'=>$fname,
					'lname'=>$lname,
					'email'=>$email,
					'addr_1'=>isset($_POST['addr_1']) ? $_POST['addr_1'] : null,
					'addr_2'=>isset($_POST['addr_2']) ? $_POST['addr_2'] : null,
					'city'=>isset($_POST['city']) ? $_POST['city'] : null,
					'state'=>isset($_POST['state']) ? $_POST['state'] : null,
					'zip'=>isset($_POST['zip']) ?  $_POST['zip']: null,
					'phone'=>isset($_POST['phone']) ? $_POST['phone'] : null,
					);
	$result = User::edit_user($params);
	if($result && $result->update()) {
		$message = "Your information was updated successfully!";
	} else {
		$message = "There was an error saving your information.  Please try again later.";
	}
}
?>
<h2><?php echo output_message($message); ?></h2>
<div id="page" class="container"> 
                    <h1>Edit Account Details</h1>
                        <div id="navPass">
                            <ul>
                                <li><a href="accountSettings.php">Account Settings</a></li>
                                <li><a href="changePassword.php">Change Password</a></li>
                            </ul>
                        </div><br><br>
                    <form class="clear-from form-horizontal" method="POST" action="" role="form">
        <input type="hidden" name="change" value="none" checked="checked">
                        <fieldset>
<div class="form-group">
	<label class="col-sm-2 control-label">First Name</label>
	<div class="col-sm-10">
		<input type="text" name="firstName" id="firstName" required="required" value="<?php echo $customer[0]->fname; ?>">
	</div>
</div>
                                
<div class="form-group">
	<label class="col-sm-2 control-label">Last Name</label>
	<div class="col-sm-10">
    	<input type="text" name="lastName" id="lastName" required="required" value="<?php echo $customer[0]->lname; ?>">
	</div>
</div>
                                
<div class="form-group">
	<label class="col-sm-2 control-label">Email</label>
	<div class="col-sm-10">
		<input type="text" name="email" id="email" required="required" value="<?php echo $customer[0]->email; ?>">
	</div>
</div>
<div class="form-group">
	<label class="col-sm-2 control-label">Phone</label>
	<div class="col-sm-10">
	    <input type="phone" name="phone" id="phone"value="<?php echo$customer[0]->phone;?>">
	</div>
</div>
<div class="form-group">    
    <label for="addr_1" class="col-sm-2 control-label">Address, line 1</label>
    <div class="col-sm-10">
        <input type="text" name="addr_1" id="addr_1" value="<?php echo $customer[0]->addr_1;?>">
	</div>
</div>
<div class="form-group">    
    <label for="addr_2" class="col-sm-2 control-label">Address, line 2</label>
    <div class="col-sm-10">
		<input type="text" name="addr_2" id="addr_2" value="<?php echo $customer[0]->addr_2; ?>">
 	</div>
</div>
 <div class="form-group">
     <label for="city"class="col-sm-2 control-label">City</label>
     <div class="col-sm-10">
		<input type="text" name="city" id="city" value="<?php echo $customer[0]->city; ?>"> 
	</div>
</div>
<div class="form-group">
        <label for="state"class="col-sm-2 control-label">State</label>
        <div class="col-xs-4">
        <select class="form-control" name="state">
        	<option value="<?php echo $customer[0]->state; ?>" selected="selected"><?php echo $customer[0]->state; ?></option>
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
            <input type="text" name="zip" value="<?php echo $customer[0]->zip; ?>">
        </div>
    </div>    
                        </fieldset>
                        <input type="submit" name="submit" value="Change Account Details" class="btn btn-success btn-default active" role="button">    
                    </form>
</div>

<?
require_once 'footer.php';
?>