<?php

defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);

defined('SITE_ROOT') ? null : define('SITE_ROOT',DS.'home3'.DS.'sellatex'.DS.'public_html'.DS.'new');
defined('LIB_PATH') ? null : define('LIB_PATH', SITE_ROOT.DS.'includes');

// Load config file first
require_once(LIB_PATH.DS."config.php");
require_once(LIB_PATH.DS."amazon_config.php");

//Load basic functions so avail for all files
require_once(LIB_PATH.DS."functions.php");
require_once(LIB_PATH.DS."password.php");

//Load core objects
require_once(LIB_PATH.DS."session.php");
require_once(LIB_PATH.DS."database.php");
require_once(LIB_PATH.DS."database_object.php");
require_once(LIB_PATH.DS."getMatchingProduct.php");
require_once(LIB_PATH.DS."amazonPricingMWS.php");
require_once(LIB_PATH.DS."settings.php");

//Load database related classes
require_once(LIB_PATH.DS."user.php");
require_once(LIB_PATH.DS."cart_id.php");

?>