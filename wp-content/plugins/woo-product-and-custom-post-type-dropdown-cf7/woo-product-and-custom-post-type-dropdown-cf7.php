<?php
/**
* Plugin Name: Product And Custom Post type Dropdown CF7
* Description: Create post List and woocommerce product list in contact form 7. 
* Version: 1.0
* License: A "GNUGPLv3" license name 
*/

if (!defined('ABSPATH')) {
   die('-1');
}
if (!defined('CF7WPAPLOC_PLUGIN_NAME')) {
   define('CF7WPAPLOC_PLUGIN_NAME', 'Woo Product And Custom Post type Dropdown CF7');
}
if (!defined('CF7WPAPLOC_PLUGIN_VERSION')) {
   define('CF7WPAPLOC_PLUGIN_VERSION', '1.0.0');
}
if (!defined('CF7WPAPLOC_PLUGIN_FILE')) {
   define('CF7WPAPLOC_PLUGIN_FILE', __FILE__);
}
if (!defined('CF7WPAPLOC_PLUGIN_DIR')) {
   define('CF7WPAPLOC_PLUGIN_DIR',plugins_url('', __FILE__));
}
if (!defined('CF7WPAPLOC_BASE_NAME')) {
   define('CF7WPAPLOC_BASE_NAME', plugin_basename(CF7WPAPLOC_PLUGIN_FILE));
}
if (!defined('CF7WPAPLOC_DOMAIN')) {
   define('CF7WPAPLOC_DOMAIN', 'cf7wpaploc');
}


if (!class_exists('CF7WPAPLOC_main')) {
	add_action('plugins_loaded', array('CF7WPAPLOC_main', 'CF7WPAPLOC_instance'));
  	class CF7WPAPLOC_main {

    	protected static $CF7WPAPLOC_instance;
      public static function CF7WPAPLOC_instance() {
         if (!isset(self::$CF7WPAPLOC_instance)) {
            self::$CF7WPAPLOC_instance = new self();
            self::$CF7WPAPLOC_instance->init();
         }
         return self::$CF7WPAPLOC_instance;
      }


      function __construct() {
         include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
         add_action('admin_init', array($this, 'CF7WPAPLOC_check_plugin_state'));
      }


      function CF7WPAPLOC_check_plugin_state(){
         if ( !( is_plugin_active( 'contact-form-7/wp-contact-form-7.php' ) ) ) {
            set_transient( get_current_user_id() . 'cf7wpaplocerror', 'message' );
         }
      }


      function init() {
         add_action( 'admin_notices', array($this, 'CF7WPAPLOC_show_notice'));
         add_action('admin_enqueue_scripts', array($this, 'CF7WPAPLOC_load_admin_script_style'));
         add_action( 'wp_enqueue_scripts', array($this, 'CF7WPAPLOC_load_frontend_script_style') );
         add_filter( 'plugin_row_meta', array( $this, 'CF7WPAPLOC_plugin_row_meta' ), 10, 2 );
         add_filter( 'admin_footer', array( $this, 'CF7WPAPLOC_css_admin' ), 10, 2 );
         if ( is_plugin_active( 'woocommerce/woocommerce.php' ) )  {
            //Product Control
            include_once('admin/cf7wpaploc-product-control.php');
            //Product drop-down on Frontend
            include_once('frontend/cf7wpaploc-product.php');
         }
         //Post Control
         include_once('admin/cf7wpaploc-post-control.php');
         include_once('admin/cf7wpaploc_kit.php');
         //Post drop-down on Frontend
         include_once('frontend/cf7wpaploc-post.php');
      }


      function CF7WPAPLOC_plugin_row_meta( $links, $file ) {
         if ( CF7WPAPLOC_BASE_NAME === $file ) {
            $row_meta = array(
               'rating'    =>  '<a href="#" target="_blank">Documentation</a> | <a href="https://www.xeeshop.com/support-us/?utm_source=aj_plugin&utm_medium=plugin_support&utm_campaign=aj_support&utm_content=aj_wordpress" target="_blank">Support</a> | <a href="https://wordpress.org/support/plugin/woo-product-and-custom-post-type-dropdown-cf7/reviews/?filter=5" target="_blank"><img src="'.CF7WPAPLOC_PLUGIN_DIR.'/images/star.png" class="cf7paplist_rating_div"></a>',
            );
            return array_merge( $links, $row_meta );
         }
        	return (array) $links;
      } 


      function CF7WPAPLOC_css_admin() {
         ?>
	         <style type="text/css">
		         .cf7paplist_rating_div {
		            width: 10%;
		            vertical-align: middle;
		         }
	         </style>
         <?php
      }


      function CF7WPAPLOC_show_notice() {
         if ( get_transient( get_current_user_id() . 'cf7wpaplocerror' ) ) {
            deactivate_plugins( plugin_basename( __FILE__ ) );
            delete_transient( get_current_user_id() . 'cf7wpaplocerror' );
            echo '<div class="error"><p> This plugin is deactivated because it require <a href="plugin-install.php?tab=search&s=contact+form+7">Contact Form 7</a> plugin installed and activated.</p></div>';
         }
      }


      function CF7WPAPLOC_load_admin_script_style() {
         wp_enqueue_script( 'cf7wpaploc_admin_script', CF7WPAPLOC_PLUGIN_DIR . '/js/admin-ocwpcf7-js.js', false, '1.0.0' );
         wp_enqueue_style( 'cf7wpaploc_admin_style', CF7WPAPLOC_PLUGIN_DIR . '/css/admin-ocwpcf7-css.css', false, '1.0.0' );
      }


      function CF7WPAPLOC_load_frontend_script_style() {
         wp_enqueue_script( 'cf7wpaploc_frontend_script', CF7WPAPLOC_PLUGIN_DIR . '/js/frontend-ocwpcf7-js.js', false, '1.0.0' );
         wp_enqueue_script( 'cf7wpaploc_select2_script', CF7WPAPLOC_PLUGIN_DIR . '/js/select2.js', false, '1.0.0' );
         wp_enqueue_style( 'cf7wpaploc_select2_style', CF7WPAPLOC_PLUGIN_DIR . '/css/select2.css', false, '1.0.0' );
         wp_enqueue_style( 'cf7wpaploc_frontend_style', CF7WPAPLOC_PLUGIN_DIR . '/css/frontend-ocwpcf7-css.css', false, '1.0.0' );
      }    
   }
}
