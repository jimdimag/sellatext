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
<div id="page" class="container non-printable"> 
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
            <td><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal1" data-whatever="<?php echo $orderHistory[$i]->tracking; ?>"><?php echo $orderHistory[$i]->tracking; ?></button>
                    	<!--<a data-toggle="modal" data-target="#myModal"><?php echo $orderHistory[$i]->tracking; ?></a>--></td>
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
<div class="modal fade printable" id="myModal1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close non-printable" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title non-printable" id="myModalLabel">Mailing Label</h4>
      </div>
      <div class="modal-body">
      	
        <img  id="track" src="tracking/label1ZE32V850395032849.gif" height="500px" width="500px">
      </div>
      <div class="modal-footer">
      	<button type="button" class="btn btn-default non-printable" id="print" onclick="window.print()">Print Label</button>
        <button type="button" class="btn btn-default non-printable" data-dismiss="modal">Close</button>
        
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->   
<?
include 'footer.php';
?>
