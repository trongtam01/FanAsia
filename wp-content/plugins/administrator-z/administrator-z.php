<?php
/*
Plugin Name: Administrator Z
Description: Lots of tools for quick website setup.
Version: 2.7.7
Author: Quyle91
Author URI: https://quyle91.github.io/
License: GPLv2 or later
Text Domain: adminz
*/
define('ADMINZ_DIR', plugin_dir_path( __FILE__ )); 
define('ADMINZ_BASENAME', plugin_basename(__FILE__));
add_action( 'plugins_loaded', function () {
	new Adminz\Admin\Adminz;
	new Adminz\Admin\ADMINZ_PluginOptions;
	new Adminz\Admin\ADMINZ_DefaultOptions;
	new Adminz\Admin\ADMINZ_Enqueue;
	new Adminz\Admin\ADMINZ_ContactGroup;
	new Adminz\Admin\ADMINZ_Woocommerce;
	new Adminz\Admin\ADMINZ_Flatsome;
	new Adminz\Admin\ADMINZ_Elementor;
	new Adminz\Admin\ADMINZ_Import;
	new Adminz\Admin\ADMINZ_Mailer;		
	new Adminz\Admin\ADMINZ_OtherTools;	
	new Adminz\Admin\ADMINZ_Security;
	new Adminz\Admin\ADMINZ_Icons;	
	new Adminz\Admin\ADMINZ_Me;	
});
require_once( trailingslashit( ADMINZ_DIR ) . 'autoload/autoloader.php' ); 