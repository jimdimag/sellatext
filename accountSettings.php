<?php

$title = "My Account";
require_once('includes/initialize.php');
//$buyback_cartNeeded = true;

require_once 'header.php';

if(!($session->is_logged_in())) {
	$session->message("You need to log in to view this page.");
    redirect_to('login.php');
    exit;
}

/*$user = getApiData('get_customer', array('customer_id'=>$_SESSION['buyback_userId']));
$result = getApiData('get_customer_order_history', array('customer_id'=>$_SESSION['buyback_userId']));
$orderHistory = $result['order_history'];*/
?>
<div id="page" class="container"> 
                            <h1>Your Account Details</h1>
                            <div id="navPass">
                                <ul>
                                    <li><a href="accountSettingsEdit.php">Edit Account</a></li>
                                    <li><a href="forgotPassword.php">Change Password</a></li>
                                </ul>
                            </div><br><br>
                            <dl>
                                <dt>Name:&nbsp;</dt>
                                <dd><?php echo $user['Customer']['first_name'].' '.$user['Customer']['last_name']; ?></dd>

                                <dt>Email:&nbsp; </dt>
                                <dd><?php echo $user['Customer']['email']; ?></dd>

                                <dt>Phone:&nbsp; </dt>
                                <dd><?php echo buyback_formatPhoneNumber($user['Customer']['phone']); ?></dd>

                                <dt>Address:&nbsp; </dt>
                                <dd>
                                <?php
                                    echo $user['Customer']['address1'];
                                    if(!empty($user['Customer']['address2'])){
                                        echo '<br>'.$user['Customer']['address2'];
                                    }
                                    echo '<br>'.$user['Customer']['city'].', '.$user['Customer']['state'].' '.$user['Customer']['zip'];
                                ?>
                                </dd>
                            </dl>
        <?php if(!empty($orderHistory)): ?>
                        <h2>Your BuyBack History</h2>
                        <table class="blue-table">
                                <tr>
                                        <th>Order Number</th>
                                        <th>Mailing Label</th>
                                        <th>Submitted</th>
                                        <th>Ship By</th>
                                        <th>Status</th>
                                        <th>Quote</th>
                                        <th>Paid</th>
                                </tr>
                        <?php foreach($orderHistory as $order): ?>
                                <tr class="<?php echo $order['status']; ?>">
                                        <td><a href="<?php echo $buybackPageNames['orderDetails']; ?>?id=<?php echo $order['id']; ?>">#<?php echo $order['id']; ?></a></td>
                                        <td><a href="<?php echo $order['shipping_label']; ?>">Link</a></td>
                                        <td><?php echo date('F j, Y ', strtotime($order['created'])); ?></td>
                                        <td><?php echo date('F j, Y ', strtotime($order['ship_by'])); ?></td>
                                        <td><?php echo $order['status']; ?></td>
                                        <td>$<?php echo $order['amount_quoted']; ?></td>
                                        <td><?php echo $order['amount_paid'] != '' ? '$'.$order['amount_paid'] : '--'; ?></td>
                                </tr>
                        <?php endforeach; ?>
                        </table>
        <?php else: ?>
            <h3>Your BuyBack History</h3>
            <p>You have never placed an order!</p>
        <?php endif; ?>
</div>
<?
include 'footer.php';
?>
