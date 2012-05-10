<?php
if ( defined('ABSPATH') ){
    require_once(ABSPATH . 'wp-load.php');
}
else{
    require_once('../../../wp-load.php');
}


// Set the multi-language file, english is the standard.
load_plugin_textdomain( 'the-welcomizer', false, dirname( plugin_basename( __FILE__ ) ).'/languages/' ); 
        
require_once(dirname(__FILE__).'/includes/twiz.class.php'); 
require_once(dirname(__FILE__).'/includes/twiz.ajax.class.php'); 

$myTwizAjax  = new TwizAjax();
header("Content-type: text/javascript"); 
echo($myTwizAjax->getAjaxHeader());
?>