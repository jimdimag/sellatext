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
$user_id = $_SESSION['user_id'];

/*$user = getApiData('get_customer', array('customer_id'=>$_SESSION['buyback_userId']));*/
$customer = User::get_user($user_id);
$orderHistory = UserHistory::get_history($user_id);
$max = count($orderHistory);
?>
<div id="page" class="container"> 
                            <h1>Your Account Details</h1>
                            <div id="navPass">
                                <ul>
                                    <li><a href="accountSettingsEdit.php">Edit Account</a></li>
                                    <li><a href="changePassword.php">Change Password</a></li>
                                </ul>
                            </div><br><br>
                            <dl>
                                <dt>Name:&nbsp;</dt>
                                <dd><?php echo $customer[0]->fname.' '.$customer[0]->lname; ?></dd>

                                <dt>Email:&nbsp; </dt>
                                <dd><?php echo  $customer[0]->email; ?></dd>

                                <dt>Phone:&nbsp; </dt>
                                <dd><?php echo $customer[0]->phone; ?></dd>

                                <dt>Address:&nbsp; </dt>
                                <dd>
                                <?php
                                    echo $customer[0]->addr_1;
                                    if(!empty($customer[0]->addr_2)){
                                        echo '<br>'.$customer[0]->addr_2;
                                    }
                                    echo '<br>'.$customer[0]->city.', '.$customer[0]->state.' '.$customer[0]->zip;
                                ?>
                                </dd>
                            </dl>
        <?php if(!empty($orderHistory)): ?>
                        <h2>Your BuyBack History</h2>
                        <table class="table">
                                <tr>
                                        <th>Order Number</th>
                                        <th>Mailing Label</th>
                                        <th>Submitted</th>
                                        <th>Ship By</th>
                                        <th>Status</th>
                                        <th>Quote</th>
                                        <th>Paid</th>
                                </tr>
                        <?php for ($i=0;$i<$max;$i++): ?>
                                <tr class="<?php echo $orderHistory[$i]->status; ?>">
                                        <td><a href="orderDetails.php?id=<?php echo $orderHistory[$i]->cart_id ?>">#<?php echo $orderHistory[$i]->cart_id; ?></a></td>
                                        <td><a href="tracking/label"<?php echo $orderHistory[$i]->tracking; ?>".gif">Link</a></td>
                                        <td><?php echo $orderHistory[$i]->submitted; ?></td>
                                        <td><?php echo date('F j, Y ', strtotime($orderHistory[$i]->ship_by)); ?></td>
                                        <td><?php echo $orderHistory[$i]->status; ?></td>
                                        <td>$<?php echo $orderHistory[$i]->price; ?></td>
                                        <td><?php //echo $order['amount_paid'] != '' ? '$'.$order['amount_paid'] : '--'; ?></td>
                                </tr>
                        <?php endfor; ?>
                        </table>
        <?php else: ?>
            <h3>Your BuyBack History</h3>
            <p>You have never placed an order!</p>
        <?php endif; ?>
</div>
<?
include 'footer.php';
?>
