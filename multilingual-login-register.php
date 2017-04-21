<?php
# Silence is golden.
/*
Plugin Name: Multilingual Login Register
Plugin URI: http://www.twinklecomputers.com
Description: This is Login and register
Author: Twinkle Computer's
Version: 1.0
Author URI: http://www.twinklecomputers.com
License: GPL2
*/
session_start();
include_once(plugin_dir_path(__FILE__)."/admin/license-manager.php");

include_once(plugin_dir_path(__FILE__)."/admin/php-mo.php");

include_once(plugin_dir_path(__FILE__)."function.php");
if( !function_exists('get_plugin_data') ){
	require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}


   
    
	

 
?>