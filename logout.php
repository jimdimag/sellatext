<?php

require_once('includes/initialize.php');

    $session->logout();
	$session->message("You are now Logged out.");
    redirect_to("index.php");
	$_SESSION['buyback_cartId'] = "";
?>