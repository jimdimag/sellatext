<?php
require_once(LIB_PATH.DS.'database.php');

class Settings extends DatabaseObject {
    
    protected static $table_name = "sellatext_settings";
    protected static $db_fields = array('id','min_amount', 'qty_limit', 'days_expire', 'days_ship', 'hours_expire');
    public $id;
    public $min_amount;
    public $qty_limit;
    public $days_expire;
	public $days_ship;
	public $hours_expire;
	
	public function get_site_settings() {
		global $database;
		$sql = "select * from " .static::$table_name;
		$result_set = $database->query($sql);
		if($result_set) {
	    $row = $database->fetch_array($result_set);
			$settings = new Settings;
			$settings->id 			= $row['id']; 
			$settings->min_amount 	= $row['min_amount']; 
			$settings->qty_limit 	= $row['qty_limit']; 
			$settings->days_expire 	= $row['days_expire']; 
			$settings->days_ship 	= $row['days_ship']; 
			$settings->hours_expire = $row['hours_expire']; 
		}
		return $settings;
	}
	
} //class
	?>