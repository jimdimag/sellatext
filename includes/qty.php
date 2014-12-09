<?php

require_once(LIB_PATH.DS.'database.php');

class Qty extends DatabaseObject {
	
	protected static $table_isbn = array('follette_title'=>"isbn13",'amtext'=>"isbn13",'360text'=>"isbn",
										  'tichenor'=>"isbn13",'nebraska'=>"isbn13",'ingram'=>"isbn",'sterling'=>"isbn13",'book_winner'=>"isbn13",
										  'bookbyte'=>"isbn"); 
	
	public function get_qty_limit($isbn,$rank) {
		global $database;
		foreach (self::$table_isbn as $key =>$value) { 
			$sql = "select * from " .$key;
			$sql .= " where ".$value. "= " .$isbn;
			$result_set = $database->query($sql);
			if($result_set) {
    			$row = $database->fetch_array($result_set);
				$key1 = $key."_price";
				$key2 = "";
				switch ($key) {
					case 'follette_title':
						$price = $row['usedbuying_price'];
						break;
						
					case 'amtext':
						if(!empty($row['classcode'])){
							$price = $row['classcode'];
							$key1 = $key."_classcode";
						} else {
							$price = $row['price'];
							$key1 = $key."_classcode";
						}
						break;
					case '360text':	
						$price = $row['qty'];
						$key1 = $key."_qty";
					break;
						
					case 'tichenor':	
						$price = $row['activity'];
						$key1 = $key."_activity";
					break;	
						
					case 'nebraska':	
					$price = $row['price'];
					break;
						
					case 'ingram':	
						$price = $row['qty'];
						$key1 = $key."_qty";
					break;
					
					case 'sterling':	
						if(!empty($row['classcode'])){
							$price = $row['classcode'];
							$key1 = $key."_classcode";
						} else {
							$price = $row['price'];
							$key1 = $key."_classcode";
						}
					break;
					
					case 'book_winner':	
						$price = $row['qty'];
						$key1 = $key."_qty";
					break;
						
					case 'bookbyte':	
						$price = $row['quantity'];
						$key1 = $key."_quantity";
					break;
					default:
						
						break;
				}//switch
				$guidePrices[$key1]= $price;
				
				if(!empty($key2)) {
				$guidePrices[$key2]= $class;
				}
    		} else {
        		return FALSE;
    		}// result_set
		}//foreach
		$sql2 = "SELECT sum(i.quantity) as qty, sum(t.quantity) as tempQty";
		$sql2 .=" FROM inventory i";
		$sql2 .=" LEFT JOIN inv_temp t on i.isbn13 = t.isbn";
		$sql2 .=" WHERE i.isbn13 = " .$isbn;
		$result_set = $database->query($sql);
			if($result_set) {
    			$row = $database->fetch_array($result_set);
				$invQty = $row['qty'];
				$tempQty = $row['tempQty'];
				$haveQty = $invQty+$tempQty;
			}
		$qtyLimit = self::qty_limits($guidePrices,$rank);
		$totalAllowed = $qtyLimit - $haveQty;
		
		return $totalAllowed;
	}//get_qty_limit
	
	public function qty_limits($guidePrices,$rank) {
		
$follettLimit =  10;
$amtextLimit = array('B' => 5, 'C' => 75, 'D' => 100, 'E' => 150, 'NULL' => 5);
$nebraskaLimit = array('all' => 10);
$tichenorLimit = array('1' => 25, '2' => 25, '3' => 25, '4' => 25, '5' => 25, '6' => 25, '7' => 50, '8' => 50, '9' => 50, 'J' => 10, 'E' => 0);
$amazonLimit = array('1' => 20, '2' => 15, '3' => 15, '4' => 15, '5' => 12, '6' => 10, '7' => 5, '8'=> 2, 'NULL' => 2);
$sterlingLimit = array('Z' => 10, 'NULL' =>25);
$totalQtyLimit = 0;

	foreach ($guidePrices as $key => $value) {
	switch ($key) {
		case 'follett_title_price':
			if($value>0){
			$totalQtyLimit += $follettLimit;
			}
			break;
		
		case 'amtext_classcode':
			if($value == "B"){
				$totalQtyLimit += $amtextLimit['B'];
			}elseif($value == "C"){
				$totalQtyLimit += $amtextLimit['C'];
			}elseif($value == "D"){
				$totalQtyLimit += $amtextLimit['D'];
			}elseif($value == "E"){
				$totalQtyLimit += $amtextLimit['E'];
			}elseif( !empty($value) ){
				$totalQtyLimit += $amtextLimit['NULL'];
			}
			break;
			
		case 'tichenor_activity':
			if($value == "1"){
				$totalQtyLimit += $tichenorLimit['1'];
			}elseif($value == "2"){
				$totalQtyLimit += $tichenorLimit['2'];
			}elseif($value == "3"){
				$totalQtyLimit += $tichenorLimit['3'];
			}elseif($value == "4"){
				$totalQtyLimit += $tichenorLimit['4'];
			}elseif($value == "5"){
				$totalQtyLimit += $tichenorLimit['5'];
			}elseif($value == "6"){
				$totalQtyLimit += $tichenorLimit['6'];
			}elseif($value == "7"){
				$totalQtyLimit += $tichenorLimit['7'];
			}elseif($value == "8"){
				$totalQtyLimit += $tichenorLimit['8'];
			}elseif($value == "9"){
				$totalQtyLimit += $tichenorLimit['9'];
			}elseif($value == "J"){
				$totalQtyLimit += $tichenorLimit['J'];
			}elseif($value == "E"){
				$totalQtyLimit += $tichenorLimit['E'];
			}
				break;
		case 'sterling_classcode':
			if($value == "Z"){
			$totalQtyLimit += $sterlingLimit['Z'];
			}elseif($value >0 ){
				$totalQtyLimit += $sterlingLimit['NULL'];
			}
			break;
		case 'nebraska_price':
			if( !empty($value) ){
			$totalQtyLimit += $nebraskaLimit['all'];
			}
			break;
		case '360text_qty':
			if($value>0) {
			$totalQtyLimit += $value;
			}
			break;
			
		case 'ingram_qty':
			if($value>0) {
			$totalQtyLimit += $value;
			}
			break;
		
		case 'book_winner_qty':
			if($value>0){
			$totalQtyLimit += $value;
			}
			break;
			
		case 'bookbyte_qty':
			if($value>0) {
			$totalQtyLimit += $value;
			}
			break;
		default:
			
			break;
	}//switch

	}//foreach
//AMAZON LIMITS
	if($rank > 0 && $rank <= 15000){
		$totalQtyLimit += $amazonLimit['1'];
	}elseif($rank > 15000 && $rank <= 25000){
		$totalQtyLimit += $amazonLimit['2'];
	}elseif($rank > 25000 && $rank <= 75000){
		$totalQtyLimit += $amazonLimit['3'];
	}elseif($rank > 75000 && $rank <= 150000){
		$totalQtyLimit += $amazonLimit['4'];
	}elseif($rank > 150000 && $rank <= 300000){
		$totalQtyLimit += $amazonLimit['5'];
	}elseif($rank > 300000 && $rank <= 600000){
		$totalQtyLimit += $amazonLimit['6'];
	}elseif($rank > 600000 && $rank <= 800000){
		$totalQtyLimit += $amazonLimit['7'];
	} elseif($rank > 800000 || $rank == NULL){
		$totalQtyLimit += $amazonLimit['8'];
	}//amazon limits
return $totalQtyLimit;
	}//qty_limits
	
}//class