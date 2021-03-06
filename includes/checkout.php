<?php

require_once(LIB_PATH.DS.'database.php');
//require_once(LIB_PATH.DS.'initialize.php');

class Checkout extends DatabaseObject {
    
    protected static $table_name = "sellatext_checkout";
    protected static $db_fields = array('id','cart_id', 'user_id', 'pay_type', 'tracking', 'ship_by','status');//'email', 'addr_1','addr_2','city','state','zip',
    public $id;
    public $cart_id;
    public $user_id;
    public $pay_type;
	/*public $email;
	public $addr_1;
	public $addr_2;
	public $city;*/
	public $status;
	public $tracking;
	public $ship_by;
	
	public function get_checkout_id($cart_id, $user_id) {
		global $database;
		$sql = "SELECT MAX(id) as id from " .static::$table_name;
		$sql .= " where user_id = " .$user_id;
		$sql .=" AND cart_id = " .$cart_id;
		$result_set = $database->query($sql); 
			if($result_set) {
		    $row = $database->fetch_array($result_set);
				$id = $row['id']; 
		        return ($id);
			}
	}//get_checkout_id
	
	public function paypal_checkout($cart_id,$user_id,$pay_type,$email) {
		if(!empty($cart_id) && !empty($user_id)&& !empty($email)) {
			$checkout = new Checkout();
			$checkout->cart_id = $cart_id;
			$checkout->user_id = $user_id;
			$checkout->pay_type = $pay_type;
			$checkout->email = $email;
			
			return $checkout;
		} else {
			
			
			return FALSE;
		}
	} //paypal_checkout
	
	public function check_checkout($params) {
		if(!empty($params['cart_id']) && !empty($params['user_id'])&& !empty($params['pay_type'])) {
			$checkout = new Checkout();
			$checkout->cart_id = $params['cart_id'];
			$checkout->user_id = $params['user_id'];
			$checkout->pay_type = $params['pay_type'];
			return $checkout;
		} else {
			
			
			return FALSE;
		}
	} //check_checkout
	
	
	public function rocket($params)	{ 
		$shipment = new \RocketShipIt\Shipment('UPS');

		$shipment->setParameter('toCompany', 'SellAText.net');
		$shipment->setParameter('toPhone', '6038806400');
		$shipment->setParameter('toAddr1', '100 Factory St.');
		$shipment->setParameter('toCity', 'Nashua');
		$shipment->setParameter('toState', 'NH');
		$shipment->setParameter('toCode', '03060');
		/*if (isset($params['fname']) && isset($params['lname'])) {
			$shipment->setParamerter('fromName',$params['fname']." ".$params['lname']);
		}*/
		$package = new \RocketShipIt\Package('UPS');
		/*$package->setParameter('length','5');
		$package->setParameter('width','5');
		$package->setParameter('height','5');*/
		$package->setParameter('weight',$params['weight']);
		
		$shipment->addPackageToShipment($package);
		
		$label = $shipment->submitShipment();//echo $shipment->debug();
		if($label){
		$response = $label['pkgs'][0]['label_img'];
		$track = $label['pkgs'][0]['pkg_trk_num'];
		$fh = fopen('tracking/label'.$track.'.gif','w');
		fwrite($fh, base64_decode($response));
		fclose($fh);	
		return $track;
		} else {
			return FALSE;
		}
	}// rocket
	
	public function send_email($track,$email,$shipBy){
	$fileatt = "tracking/label".$track.".gif";
	$from = "SellAText.net";
	$messagehtml = "Thank You!\n Attached is your mailing label.\n  Your tracking number is: ".$track.".\n
	Please remember to ship your items by ".$shipBy;
	$to= $email;
	$subject = "Shipping label from SellAText";


	// generate and send email with csv attachment
	function mail_file( $to, $subject, $messagehtml, $from, $fileatt, $replyto="" ) {
        // handles mime type for better receiving
        $ext = strrchr( $fileatt , '.');
        $ftype = "";
        if ($ext == ".doc") $ftype = "application/msword";
        if ($ext == ".jpg") $ftype = "image/jpeg";
        if ($ext == ".gif") $ftype = "image/gif";
        if ($ext == ".zip") $ftype = "application/zip";
        if ($ext == ".pdf") $ftype = "application/pdf";
        if ($ftype=="") $ftype = "application/octet-stream";
         
        // read file into $data var
        $file = fopen($fileatt, "rb");
        $data = fread($file,  filesize( $fileatt ) );
        fclose($file);
 
        // split the file into chunks for attaching
        $content = chunk_split(base64_encode($data));
        $uid = md5(uniqid(time()));
	
        // build the headers for attachment and html
        $h = "From: $from\r\n";
        if ($replyto) $h .= "Reply-To: ".$replyto."\r\n";
        $h .= "MIME-Version: 1.0\r\n";
        $h .= "Content-Type: multipart/mixed; boundary=\"".$uid."\"\r\n\r\n";
        $h .= "This is a multi-part message in MIME format.\r\n";
        $h .= "--".$uid."\r\n";
        $h .= "Content-type:text/html; charset=iso-8859-1\r\n";
        $h .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
        $h .= $messagehtml."\r\n\r\n";
        $h .= "--".$uid."\r\n";
        $h .= "Content-Type: ".$ftype."; name=\"".basename($fileatt)."\"\r\n";
        $h .= "Content-Transfer-Encoding: base64\r\n";
        $h .= "Content-Disposition: attachment; filename=\"".basename($fileatt)."\"\r\n\r\n";
        $h .= $content."\r\n\r\n";
        $h .= "--".$uid."--";
 
        // send mail
        return mail( $to, $subject, strip_tags($messagehtml), str_replace("\r\n","\n",$h) ) ;
 
 
    }
		$mail = mail_file( $to, $subject, $messagehtml, $from, $fileatt, $replyto="" );
	}//send_email
	
	public function update_tracking($params){
		if(!empty($params['cart_id']) && !empty($params['user_id'])&& !empty($params['tracking'])) {
			$checkout = new Checkout();
			$checkout->id 		= $params['id'];
			$checkout->cart_id 	= $params['cart_id'];
			$checkout->user_id 	= $params['user_id'];
			$checkout->pay_type = $params['pay_type'];
			$checkout->tracking = $params['tracking'];
			$checkout->ship_by 	= $params['ship'];
			$checkout->status	= 1;
			
			return $checkout;
		} else {
			
			
			return FALSE;
		}
	}
	
public function send_notification($cart_id,$email) {
	$cart = Cart::get_cart_contents($cart_id);
	$max = count($cart);
	$to = "lreyes@bookcellaronline.com";//
	$subject = "New SellAText Order";
	$body = "There was a new order on SellAText.net for " .$email. "!\nThe items in the order are as follows:\n";
for ($i=0;$i<$max;$i++){
	$body .="Title: ".$cart[$i]->title." with a quantity of ".$cart[$i]->qty." and a price of ".'$' . number_format($cart[$i]->price, 2)."\n";
}

$body = wordwrap($body,70); 
if(mail($to, $subject, $body)) {
	return TRUE;
} else {
	return FALSE;
}
	
}
	
} //Class
?>