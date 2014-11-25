<?php
$title = "process order";
require_once('includes/initialize.php');
require_once 'header.php';

 $user_id = $_SESSION['user_id'];
 $cart_id = $_SESSION['buyback_cartId'];
echo "user id is: " .$user_id;
echo"<br> cart id is: " .$cart_id;