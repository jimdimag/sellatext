<?php

$title = "Order Details";
require_once('includes/initialize.php');


require_once 'header.php';

$cart = Cart::get_cart_contents($_GET['id']);
?>
<div class="container" id="page">
<h2><?php echo output_message($message); ?></h2>
<br/>
<h1>Your Order Details</h1>

<?php $max = count($cart);
if (!empty($cart)): ?>
    
	<a href="accountSettings.php">Back to Your Account</a>
                    <form action="" method="POST">
                        <input type="hidden" name="command" value="updateCart">
                        <h1 class="entry-title">Result</h1>
                        <table width="650" cellpadding="2" cellspacing="2" id="book-search" class="table">
			    <tr style="color:#FFF;">
                <th height="30" bgcolor="#666666">&nbsp;</th>
                <th bgcolor="#666666">Product</th>
                <th bgcolor="#666666">Price Each</th>
                <th bgcolor="#666666">Quantity</th>
                <th bgcolor="#666666">Total Price</th>
                

                            <?php for ($i=0;$i<$max;$i++):?>
                                <tr valign="top">                                
                                    <td width="135"><img src="<?php echo $cart[$i]->image; ?>" width="125" height="100" alt="<?php echo $cart[$i]->title; ?>" class="cover"></td>
                                    <td width="300" style="vertical-align:top">
                                        <strong><?php echo $cart[$i]->title; ?></strong>
                                        <br><?php if(!empty($cart[$i]->author)){?>
                                        by <?php echo $cart[$i]->author; }?>
                                        <br><?php echo $cart[$i]->binding; ?>: <?php echo $cart[$i]->pages; ?> pages
                                        <?php
                                        if (!empty($cart[$i]->isbn)) {
                                            echo '<br>ISBN-13: ' . $cart[$i]->isbn;
                                        }
                                        if (!empty($cart[$i]->isbn10)) {
                                            echo '<br>ISBN-10: ' . $cart[$i]->isbn10;
                                        }
                                        ?>                                    
                                    </td>
<td style="vertical-align:top"><?php echo '$' . number_format($cart[$i]->price, 2); ?></td>
<td style="vertical-align:top; text-align:center"><?php echo $cart[$i]->qty;?></td>

<td style="vertical-align:top" class="total"><?php echo '$' .number_format(($cart[$i]->price * $cart[$i]->qty), 2); ?></td>
                         
                                </tr>
<?php $total = $total+($cart[$i]->price * $cart[$i]->qty);?>
                            <?php endfor; ?>
                            
                        </table>  
                        <h3>We pay you: $<?php echo number_format($total,2,'.','');?></h3>                     
                    </form>
  <?php endif; ?>                 
<?
include 'footer.php';
?>