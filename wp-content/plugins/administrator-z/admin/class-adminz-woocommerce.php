<?php 
namespace Adminz\Admin;
use Adminz\Admin\Adminz as Adminz;
use Adminz\Helper\ADMINZ_Helper_Woocommerce_Cart;
use Adminz\Inc\Widget\ADMINZ_Inc_Widget_Filter_Product_Taxonomy;
use Adminz\Helper\ADMINZ_Helper_Language;
use Adminz\Helper\ADMINZ_Helper_Woocommerce_Taxonomy; 
use Adminz\Helper\ADMINZ_Helper_ADMINZ_Helper_Category;


class ADMINZ_Woocommerce extends Adminz {	
	public $options_group = "adminz_woocommerce";
	public $title = "Woocommerce";
	static $slug = 'adminz_woocommerce';
	static $options;
	static $action_hooks = [
		'woocommerce_account_content','woocommerce_account_dashboard','woocommerce_account_navigation','woocommerce_add_payment_method_form_bottom','woocommerce_after_account_navigation','woocommerce_after_add_to_cart_button','woocommerce_after_add_to_cart_form','woocommerce_after_add_to_cart_quantity','woocommerce_after_available_downloads','woocommerce_after_cart','woocommerce_after_cart_contents','woocommerce_after_cart_table','woocommerce_after_cart_totals','woocommerce_after_customer_login_form','woocommerce_after_edit_account_address_form','woocommerce_after_edit_account_form','woocommerce_after_lost_password_confirmation_message','woocommerce_after_lost_password_form','woocommerce_after_main_content','woocommerce_after_mini_cart','woocommerce_after_my_account','woocommerce_after_quantity_input_field','woocommerce_after_reset_password_form','woocommerce_after_shipping_calculator','woocommerce_after_shop_loop','woocommerce_after_shop_loop_item','woocommerce_after_shop_loop_item_title','woocommerce_after_single_product','woocommerce_after_single_product_summary','woocommerce_after_single_variation','woocommerce_after_variations_form','woocommerce_archive_description','woocommerce_auth_page_footer','woocommerce_auth_page_header','woocommerce_before_account_navigation','woocommerce_before_account_orders_pagination','woocommerce_before_add_to_cart_button','woocommerce_before_add_to_cart_form','woocommerce_before_add_to_cart_quantity','woocommerce_before_available_downloads','woocommerce_before_cart','woocommerce_before_cart_collaterals','woocommerce_before_cart_contents','woocommerce_before_cart_table','woocommerce_before_cart_totals','woocommerce_before_customer_login_form','woocommerce_before_edit_account_address_form','woocommerce_before_edit_account_form','woocommerce_before_lost_password_confirmation_message','woocommerce_before_lost_password_form','woocommerce_before_main_content','woocommerce_before_mini_cart','woocommerce_before_mini_cart_contents','woocommerce_before_my_account','woocommerce_before_quantity_input_field','woocommerce_before_reset_password_form','woocommerce_before_shipping_calculator','woocommerce_before_shop_loop','woocommerce_before_shop_loop_item','woocommerce_before_shop_loop_item_title','woocommerce_before_single_product','woocommerce_before_single_product_summary','woocommerce_before_single_variation','woocommerce_before_variations_form','woocommerce_cart_actions','woocommerce_cart_collaterals','woocommerce_cart_contents','woocommerce_cart_coupon','woocommerce_cart_has_errors','woocommerce_cart_is_empty','woocommerce_cart_totals_after_order_total','woocommerce_cart_totals_after_shipping','woocommerce_cart_totals_before_order_total','woocommerce_cart_totals_before_shipping','woocommerce_checkout_after_customer_details','woocommerce_checkout_after_order_review','woocommerce_checkout_after_terms_and_conditions','woocommerce_checkout_before_customer_details','woocommerce_checkout_before_order_review','woocommerce_checkout_before_order_review_heading','woocommerce_checkout_before_terms_and_conditions','woocommerce_checkout_billing','woocommerce_checkout_order_review','woocommerce_checkout_shipping','woocommerce_checkout_terms_and_conditions','woocommerce_edit_account_form','woocommerce_edit_account_form_end','woocommerce_edit_account_form_start','woocommerce_edit_account_form_tag','woocommerce_login_form','woocommerce_login_form_end','woocommerce_login_form_start','woocommerce_lostpassword_form','woocommerce_mini_cart_contents','woocommerce_no_products_found','woocommerce_pay_order_after_submit','woocommerce_pay_order_before_submit','woocommerce_proceed_to_checkout','woocommerce_product_after_tabs','woocommerce_product_meta_end','woocommerce_product_meta_start','woocommerce_product_thumbnails','woocommerce_register_form','woocommerce_register_form_end','woocommerce_register_form_start','woocommerce_register_form_tag','woocommerce_resetpassword_form','woocommerce_review_order_after_cart_contents','woocommerce_review_order_after_order_total','woocommerce_review_order_after_payment','woocommerce_review_order_after_shipping','woocommerce_review_order_after_submit','woocommerce_review_order_before_cart_contents','woocommerce_review_order_before_order_total','woocommerce_review_order_before_payment','woocommerce_review_order_before_shipping','woocommerce_review_order_before_submit','woocommerce_share','woocommerce_shop_loop','woocommerce_shop_loop_item_title','woocommerce_sidebar','woocommerce_single_product_summary','woocommerce_single_variation','woocommerce_widget_shopping_cart_after_buttons','woocommerce_widget_shopping_cart_before_buttons','woocommerce_widget_shopping_cart_buttons','woocommerce_widget_shopping_cart_total',

	];

	function __construct() {
		if(!class_exists( 'WooCommerce' ) ) return;
		add_filter( 'adminz_setting_tab', [$this,'register_tab']);
		add_action(	'admin_init', [$this,'register_option_setting'] );
		add_action( 'init', array( $this, 'add_shortcodes') );		
		$this->title = $this->get_icon_html('woocommerce').$this->title;
		$this::$options = get_option('adminz_woocommerce', []);

		new ADMINZ_Inc_Widget_Filter_Product_Taxonomy;
		$this->woocommerce_fire_action_hooks();
		$this->woocommerce_filter_hooks();
		$this->woocommerce_config();
	}
	function register_tab($tabs) {
 		if(!$this->title) return;
        $tabs[] = array(
            'title' => $this->title,
            'slug' => self::$slug,
            'html' => $this->tab_html()
        );
        return $tabs;
    }
	function add_shortcodes(){
		$shortcodefiles = glob(ADMINZ_DIR.'shortcodes/woocommerce*.php');
		if(!empty($shortcodefiles)){
			foreach ($shortcodefiles as $file) {
				require_once $file;
			}
		}
	}
	function woocommerce_config(){
		
		if ($this->check_option('adminz_woocommerce_ajax_add_to_cart_single_product',false,"on")){			
			new ADMINZ_Helper_Woocommerce_Cart;
		}

		$atc_btn_text = ADMINZ_Helper_Language::get_pll_string('adminz_woocommerce[adminz_woocommerce_ajax_add_to_cart_text]');
		if($atc_btn_text){			
			add_filter( 'woocommerce_product_single_add_to_cart_text', function ($a) use ($atc_btn_text){return $atc_btn_text; });
			add_filter( 'woocommerce_product_add_to_cart_text', function ($a) use ($atc_btn_text){return $atc_btn_text; });
			add_action( 'wp_head', function (){
				echo '<style type="text/css"> .single_add_to_cart_button::before {content: "\e908"; margin-left: -.15em; margin-right: .4em; font-weight: normal; font-family: "fl-icons" !important;} </style>';
			}, 999 );
		}
		
		if ($this->check_option('adminz_woocommerce_remove_add_to_cart_button',false,"on")){			
			add_action( 'wp_head', function (){
				echo '<style type="text/css"> .single_add_to_cart_button {display: none;} </style>';
			}, 999 );
		}

		if ($this->check_option('adminz_woocommerce_ajax_add_to_cart_redirect_checkout',false,"on")){			
			add_filter( 'woocommerce_add_to_cart_redirect', function (){
				return wc_get_checkout_url();
			});
			add_filter( 'woocommerce_product_add_to_cart_url', function ( $add_to_cart_url, $product ){ 
				if( $product->get_sold_individually() // if individual product
				&& WC()->cart->find_product_in_cart( WC()->cart->generate_cart_id( $product->id ) ) // if in the cart
				&& $product->is_purchasable() // we also need these two conditions
				&& $product->is_in_stock() ) {
					$add_to_cart_url = wc_get_checkout_url();
				}			 
				return $add_to_cart_url;			 
			}, 10, 2 );
			add_filter( 'woocommerce_add_cart_item_data', function ( $cart_item_data, $product_id, $variation_id ) {
			    global $woocommerce;
			    $woocommerce->cart->empty_cart();
			    return $cart_item_data;
			} , 10,  3);
			add_filter( 'wc_add_to_cart_message_html', '__return_false' );
			add_action('wp_footer',function(){
				?>
				<style type="text/css">
					#wrapper>.message-wrapper {
					    display:none !important;
					}
				</style>
				<?php
			});
		}

		
		if ($this->check_option('adminz_woocommerce_remove_quanity',false,true)){					
			add_filter( 'woocommerce_is_sold_individually',function ( $return, $product ) {
				return true;
			}, 10, 2 );
		}

		$atc_buynow_text = ADMINZ_Helper_Language::get_pll_string('adminz_woocommerce[adminz_woocommerce_add_buy_now_text]');
		if($atc_buynow_text){			
			$atc_buynow_position = $this->get_option_value('adminz_woocommerce_add_buy_now_hook');
			add_action( $atc_buynow_position, function () use($atc_buynow_text){
				global $product;
				$id = $product->get_id();
				if( $product->is_type( 'variable' ) ){
					if($this->is_flatsome()){
						echo do_shortcode('[button text="'.$atc_buynow_text.'" color="primary" size="" expand="true" icon="icon-shopping-bag" class="redirect_to_checkout disabled" icon_pos="left"  link="'.wc_get_checkout_url().'?add-to-cart='.$id.'"]');
					}else{
						echo '<a href="'.wc_get_checkout_url().'?add-to-cart='.$id.'" target="_self" class="button primary expand redirect_to_checkout disabled"> <i class="icon-shopping-bag"></i>  <span>'.$atc_buynow_text.'</span> </a>';
					}
				    ?>

				    <?php ob_start(); ?>
				    <script type="text/javascript">
				    	window.addEventListener('DOMContentLoaded', function() {
							(function($){
								$(document).on("change","input.variation_id",function(){
									get_link($(".redirect_to_checkout"));
								});
								$(document).on("change",".input-text.qty",function(){
									get_link($(".redirect_to_checkout"));
								});							
								get_link($(".redirect_to_checkout"));
								function get_link(target){
									var link = '<?php echo wc_get_checkout_url(); ?>';
									var parent = $(".product form.variations_form");										
									if(parent.length){
										var qty = parent.find(".input-text.qty").val();
										var varid = parent.find('input[name="variation_id"]').val();										
										target.addClass('disabled');
										target.attr("href","javascript:void(0)");
										if(qty>0 && varid>0){

											var href = "";								
											if(varid){
												href += '&add-to-cart='+ varid; 
											}								
											if(qty){
												href += '&quantity='+ qty; 
											}									
											var new_href = link+"?"+href;												
											target.attr("href",new_href);
											target.removeClass('disabled');
										}
									}								
								}
							})(jQuery);
						});				
					</script>		
					<?php 
					$script = ob_get_clean();
					add_action('wp_footer',function ()use($script){ echo $script;});
					 ?>	
					<?php
				}
				elseif( $product->is_type( 'simple' ) ){
					if($this->is_flatsome()){
						echo do_shortcode('[button text="'.$atc_buynow_text.'" color="primary" size="" expand="true" icon="icon-shopping-bag" class="redirect_to_checkout" icon_pos="left"  link="'.wc_get_checkout_url().'?add-to-cart='.$id.'"]');
					}else{
						echo '<a href="'.wc_get_checkout_url().'?add-to-cart='.$id.'" target="_self" class="button primary expand redirect_to_checkout"> <i class="icon-shopping-bag"></i>  <span>'.$atc_buynow_text.'</span> </a>';
					}
				  	?>
				  	<?php ob_start(); ?>
					<script type="text/javascript">
						window.addEventListener('DOMContentLoaded', function() {
							(function($){
								$(".input-text.qty").change(function(){
								  $("a.redirect_to_checkout").attr("href", "<?php echo wc_get_checkout_url(); ?>?add-to-cart=<?php echo $id; ?>" + "&quantity=" + $(this).val());
								});
							})(jQuery);
						});
					</script>		
					<?php 
					$script = ob_get_clean();
					add_action('wp_footer',function ()use($script){ echo $script;});
					 ?>
					<?php
				}
			}, 20 );
			if(isset($_GET['add-to-cart'])){
				add_filter( 'woocommerce_add_cart_item_data', function ( $cart_item_data, $product_id, $variation_id ) {
				    global $woocommerce;
				    $woocommerce->cart->empty_cart();
				    return $cart_item_data;
				} , 10,  3);
				add_filter( 'wc_add_to_cart_message_html', '__return_false' );			
			}	
			add_action('wp_footer',function(){
				?>
				<style type="text/css">
					#wrapper>.message-wrapper {
					    display:none !important;
					}
				</style>
				<?php
			});		
		}

		$atc_buynow_popup_text = ADMINZ_Helper_Language::get_pll_string('adminz_woocommerce[adminz_woocommerce_add_buy_now_popup_text]');
		if($atc_buynow_popup_text){
			$lightboxid = "lightbox".rand();

			if($this->is_flatsome()){		
				$hook = $this->get_option_value('adminz_woocommerce_add_buy_now_hook');
				if($hook){
					add_action($hook,function () use($atc_buynow_popup_text,$lightboxid){
						echo do_shortcode('[button text="'.$atc_buynow_popup_text.'" color="primary" size="" expand="true" icon="icon-shopping-basket" class="buynow_popup" icon_pos="left"  link="#'.$lightboxid.'"]');
					});
					add_action('wp_footer',function() use ($lightboxid){
						echo do_shortcode('[adminz_lightbox auto_open="false" auto_show="always" id="'.$lightboxid.'" width="1000px" block=""][adminz_quick_order product_id="'.get_the_ID().'"][/adminz_lightbox]');
					});	
				}
			}else{
				// general magnific popup files
				add_action('wp_enqueue_scripts',function(){
					wp_enqueue_style('adminz_magnific_popup_style','https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.css',[], '1.1.0', $media = 'all' );					
					wp_enqueue_script('adminz_magnific_popup_script','https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js',['jquery'],'1.1.0',true);
				});			
				// general lightbox
				$add_buy_now = $this->get_option_value('adminz_woocommerce_add_buy_now_hook');
				if($add_buy_now){
					add_action($add_buy_now,function () use($atc_buynow_popup_text,$lightboxid){					
						echo '<a href="#'.$lightboxid.'" class="button buynow_popup"><i class="icon-shopping-basket"></i>'.$atc_buynow_popup_text.'</a>';										
					});
				}				
				add_action('wp_footer',function() use ($atc_buynow_popup_text,$lightboxid){
					echo do_shortcode('<div id="'.$lightboxid.'" class="white-popup mfp-hide">[adminz_quick_order product_id="'.get_the_ID().'"]</div>');
				});
				// css for lightbox
				add_action('wp_footer',function(){
					ob_start();
					?>
					<script type="text/javascript">
						window.addEventListener('DOMContentLoaded', function() {
							(function($){
								$('.buynow_popup').magnificPopup({
								  type:'inline',
								  midClick: true
								});
							})(jQuery);
						});
					</script>
					<style type="text/css">
						.white-popup {
						  position: relative;
						  background: #FFF;
						  padding: 20px;
						  width:auto;
						  max-width: 100%;
						  width:  1000px;
						  margin: 20px auto;						  
						}
					</style>
					<?php
					echo apply_filters('adminz_output_debug',ob_get_clean());
				});
			}	
				
		}		
		if($this->check_option('adminz_woocommerce_simple_checkout_field',false,"on")){		
			add_filter( 'woocommerce_checkout_fields' , [$this,'custom_remove_woo_checkout_fields']);
		}
		if($this->check_option('adminz_woocommerce_remove_select_woo',false,"on")){				
			add_action( 'wp_enqueue_scripts', function(){
				wp_dequeue_style( 'selectWoo' );
		        wp_deregister_style( 'selectWoo' );
		 
		        wp_dequeue_script( 'selectWoo');
		        wp_deregister_script('selectWoo');
			}, 100 );
		}
		if($this->check_option('adminz_woocommerce_fix_gallery_image_size',false,"on")){				
			add_filter('woocommerce_gallery_image_size',function (){
				return array_values(wc_get_image_size( 'thumbnail' ));
			});
		}
		$empty_price_html = ADMINZ_Helper_Language::get_pll_string('adminz_woocommerce[adminz_woocommerce_empty_price_html]');
		if($empty_price_html){
			add_filter('woocommerce_empty_price_html', function() use ($empty_price_html){
				return do_shortcode($empty_price_html);
			});
		}
		if($this->is_flatsome()){
			$is_tooltip = $this->get_option_value('adminz_tooltip_products');
			if($is_tooltip){
				add_action('woocommerce_before_shop_loop_item',function(){
					global $product;	
					if($product->get_short_description()){
						echo '<div class="admz_product_tooltip product-short-description" style="top: 10px; left: 10px; right: auto; display: none; ">'.$product->get_short_description().'</div>';
					}
				});
				add_action('wp_footer',function(){
					ob_start();
					?>
					<style type="text/css">
						.admz_product_tooltip {position: absolute; background: white; z-index: 99; width:  300px; padding:  10px; box-shadow: 1px 2px 7px #23232363; } 
						.products .col-inner{position: relative; }
						.admz_product_tooltip ul li, .product-short-description ul li{list-style: none; }
					</style>
					<script type="text/javascript">
						window.addEventListener('DOMContentLoaded', function() {
							(function($){
								if(! /Android|webOS|iPhone|iPad|Mac|Macintosh|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
									var html_dom_adminz_tooltip = ".products .col-inner, .product-small .col-inner";
								    $(html_dom_adminz_tooltip).mousemove(function( event ) {
									  	var need_left = event.pageX - ($(this).offset().left) + 20;
									  	var need_top = event.pageY - ($(this).offset().top) -20;									  	
									  	var need_tooltip = $(this).find(".admz_product_tooltip");
									  	if( (event.pageX + need_tooltip.width() + 20) > $(document).width() ){
										  	need_left = need_left - need_tooltip.width() - 40;
									  	}
									  	$(this).find(".admz_product_tooltip").css("display","inline").css("left",need_left).css("top",need_top);
									});
									$(html_dom_adminz_tooltip).mouseleave(function() {
										$(this).find(".admz_product_tooltip").css("display","none");
								    });
								}
							})(jQuery)
						});
					</script>
					<?php
					echo apply_filters('adminz_output_debug',ob_get_clean());
				});
			}
		}
		if($this->check_option('enable_product_cat_tinymce',false,"on")){		
			add_filter('product_cat_edit_form_fields', function ($tag) {
			    ?>
			    
			        <tr class="form-field">
			            <th scope="row" valign="top"><label for="description"><?php _e('Description'); ?></label></th>
			            <td>
			                <?php  
			                $settings = array('wpautop' => true, 'media_buttons' => true, 'quicktags' => true, 'textarea_rows' => '15', 'textarea_name' => 'description' ); 
			                wp_editor(html_entity_decode($tag->description , ENT_QUOTES, 'UTF-8'), 'description1', $settings); ?>   
			                <br />
			                <span class="description"><?php _e('The description is not prominent by default; however, some themes may show it.'); ?></span>
			            </td>   
			        </tr>         
			    
			    <?php
			});	
			new ADMINZ_Helper_ADMINZ_Helper_Category;
		}

		if($this->get_option_value('adminz_woocommerce_from_currency_formatting')){
			$from_currency = explode(",",$this->get_option_value('adminz_woocommerce_from_currency_formatting'));
			$to_currency = explode(",",$this->get_option_value('adminz_woocommerce_to_currency_formatting'));
			add_filter('woocommerce_currency_symbol', function ( $currency_symbol, $currency ) use ($from_currency, $to_currency) {
				if(!empty($from_currency) and is_array($from_currency)){
					foreach ($from_currency as $k=> $from) {
						if($currency == $from) {
					 		$currency_symbol = " ".$to_currency[$k]." ";
					 	}
					}
				}			 	
			 	return $currency_symbol;
			}, 10, 2);
		}
	}
	function woocommerce_fire_action_hooks(){
		$adminz_woocommerce_action_hook = $this->get_option_value('adminz_woocommerce_action_hook');
		if(!empty($adminz_woocommerce_action_hook) and is_array($adminz_woocommerce_action_hook)){			
			foreach ($adminz_woocommerce_action_hook as $action => $shortcode) {
				if($shortcode){
					add_action($action,function() use ($shortcode){
						echo do_shortcode(html_entity_decode($shortcode));
					});
				}				
			}
		}
		if($this->check_option('adminz_woocommerce_test_all_hook')){		
			foreach (self::$action_hooks as $action) {
				add_action($action, function() use ($action){
					echo do_shortcode('[adminz_test content="'.$action.'"]');
				});
			}
		}

		$woo_hook_data = json_decode($this->get_option_value('adminz_woocoommerce_custom_hook'));
		if(!empty($woo_hook_data) and is_array($woo_hook_data)){
			foreach ($woo_hook_data as $value) {
				$value[2] = $value[2]? $value[2] : 0;
				add_action($value[1],function() use ($value){					
					$condition = true;
					if(!empty($value[3])){
						$condition = call_user_func($value[3]);
					}
					if($condition){
						echo html_entity_decode(do_shortcode($value[0])); 
					}
				},$value[2]);
				
			}
		}
		
	}
	function woocommerce_filter_hooks(){		
		if($this->check_option('adminz_woocommerce_description_readmore',false,"on")){			
			add_action( 'woocommerce_before_single_product', function () {				
			    ob_start();
				?>				
				<style type="text/css">	
									
					#tab-description:not(.toggled){
						max-height: 70vh;    		
			    		overflow: hidden;
					}
					#tab-description{
						position: relative;
			    		padding-bottom: 50px;
					}
					#tab-description::after
					,#tab-description .adminz_readmore_description{
						content:  "";
						position: absolute;
			    		bottom: 0px;    		
			    		text-align: center;
			    		width: 100%;
			    		left: 0px;
			    		padding-top: 90px;
					}
					#tab-description .adminz_readmore_description{
						z-index: 1;
					}
					#tab-description:not(.toggled)::after					{
						background-image: -webkit-linear-gradient(bottom, white 40%, transparent 100%);    		
					}
					#main.dark #tab-description::after{
			    		background-image: -webkit-linear-gradient(bottom, #333 40%, transparent 100%);
			    	}
			    	<?php $content_bg = get_theme_mod('content_bg'); if($content_bg){?>
			    		#tab-description:not(.toggled)::after{
				    		background-image: -webkit-linear-gradient(bottom, <?php echo $content_bg;?> 40%, transparent 100%) !important;
				    	}
					<?php }?>
			    	#tab-description .adminz_readmore_description .button{
			    		margin: 0;
			    	}
				</style>
				<script type="text/javascript">
					window.addEventListener('DOMContentLoaded', function() {
						(function($){
							var adminz_readmore_description = $('<div class="adminz_readmore_description"><div class="button white"><i class="icon-angle-down"></i>'+'<?php echo __("Read more...") ;?>'+'</div></div>');
							$("#tab-description").append(adminz_readmore_description);
							$(document).on('click','.adminz_readmore_description .button',function(){
								jQuery(this).find("i").toggleClass('icon-angle-down');
								jQuery(this).find("i").toggleClass('icon-angle-up');
								jQuery(this).closest('#tab-description').toggleClass('toggled');
							});
						})(jQuery);
					});
				</script>
				<?php
				echo apply_filters('adminz_output_debug',ob_get_clean());
			});

		}
	}
	function tab_html(){
		ob_start();
		?>
		<form method="post" action="options.php">
			<?php 
			settings_fields($this->options_group);
	        do_settings_sections($this->options_group);
			?>
			<table class="form-table">				
				<tr valign="top">
					<th scope="row">
						<h3>Single Product</h3>
					</th>
				</tr>
				<tr valign="top">
					<th scope="row">
						Empty price html
					</th>
					<td>
						<label>
							<textarea cols="70" rows="1" type="text" name="adminz_woocommerce[adminz_woocommerce_empty_price_html]"><?php echo $this->get_option_value('adminz_woocommerce_empty_price_html'); ?></textarea> Leave blank for not use
						</label>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						Fix Gallery image size
					</th>
					<td>
						<label>
							<input type="checkbox" name="adminz_woocommerce[adminz_woocommerce_fix_gallery_image_size]" <?php if($this->get_option_value('adminz_woocommerce_fix_gallery_image_size') =="on") echo "checked"; ?>> Enable
							<div>
							<small>| Note 1*: Only images that are larger than woocommerce's settings are effective</small></br>
							<small>| Note 2*: If this function is enabled <strong>after</strong> uploading the thumbnail: Require use <code>Regenerate Thumbnails</code> to reset thumbnails size after change </small></br> </div>
						</label>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						Collapse description and create Readmore button
					</th>
					<td>
						<label>
							<input type="checkbox" name="adminz_woocommerce[adminz_woocommerce_description_readmore]" <?php if($this->get_option_value('adminz_woocommerce_description_readmore') =="on") echo "checked"; ?>> Enable
							
						</label>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<h3>Products Category </h3>
					</th>
				</tr>
				<tr valign="top">
					<th scope="row">
						Description tiny mce editor
					</th>
					<td>
						<label>
							<input type="checkbox" name="adminz_woocommerce[enable_product_cat_tinymce]" <?php if($this->get_option_value('enable_product_cat_tinymce') =="on") echo "checked"; ?>> Enable
							
						</label>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<h3>Products list </h3>
					</th>
				</tr>
				<?php if($this->is_flatsome()){ ?>
				<tr valign="top">
					<th scope="row">
						Tooltip box on hover 
					</th>
					<td>
						<label>
							<input type="checkbox" name="adminz_woocommerce[adminz_tooltip_products]" <?php if($this->get_option_value('adminz_tooltip_products') =="on") echo "checked"; ?>> Enable
							
						</label>
					</td>
				</tr>
				<?php } ?>

				<tr valign="top">
					<th scope="row">
						<h3>Add to cart</h3>
					</th>
				</tr>
				<tr valign="top">
					<th scope="row">
						Add to cart text
					</th>
					<td>
						<label>
							<input type="text" name="adminz_woocommerce[adminz_woocommerce_ajax_add_to_cart_text]" value="<?php echo $this->get_option_value('adminz_woocommerce_ajax_add_to_cart_text'); ?>">							
						</label>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						Remove add to cart button
					</th>
					<td>
						<label>
							<input type="checkbox" name="adminz_woocommerce[adminz_woocommerce_remove_add_to_cart_button]" <?php if($this->get_option_value('adminz_woocommerce_remove_add_to_cart_button')) echo "checked"; ?>> Enable
							
						</label>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						Redirect to checkout
					</th>
					<td>
						<label>
							<input type="checkbox" name="adminz_woocommerce[adminz_woocommerce_ajax_add_to_cart_redirect_checkout]" <?php if($this->get_option_value('adminz_woocommerce_ajax_add_to_cart_redirect_checkout') =="on") echo "checked"; ?>> Enable
							<?php 
							$current_setting = $this->get_option_value('woocommerce_enable_ajax_add_to_cart');
							if($current_setting == "yes"){
								echo "<mark> You need disable Ajax in Woocommerce setting for this option.</mark>";
							}
							?>							
						</label>
					</td>
				</tr>	
				<tr valign="top">
					<th scope="row">
						Ajax in single product
					</th>
					<td>
						<label>
							<input type="checkbox" name="adminz_woocommerce[adminz_woocommerce_ajax_add_to_cart_single_product]" <?php if($this->get_option_value('adminz_woocommerce_ajax_add_to_cart_single_product') =="on") echo "checked"; ?>> Enable
							<?php 
							$current_setting = $this->get_option_value('woocommerce_enable_ajax_add_to_cart');
							if($current_setting == "no"){
								echo "<mark> You need enable Ajax in Woocommerce setting for this option.</mark>";
							}
							?>
						</label>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						Buy now redirect checkout
					</th>
					<td>
						<label>							
							<input type="text" name="adminz_woocommerce[adminz_woocommerce_add_buy_now_text]" value="<?php echo $this->get_option_value('adminz_woocommerce_add_buy_now_text'); ?>">
						</label>
					</td>
				</tr>				
				<tr valign="top">
					<th scope="row">
						Buy now Popup
					</th>
					<td>
						<label>
							<input type="text" name="adminz_woocommerce[adminz_woocommerce_add_buy_now_popup_text]" value="<?php echo $this->get_option_value('adminz_woocommerce_add_buy_now_popup_text'); ?>"> <small>Note: Currently only support for basic payment method</small>
						</label>
					</td>
				</tr>				
				<tr valign="top">
					<th scope="row">
						Buy now position
					</th>
					<td>
						<label>							
							<select name="adminz_woocommerce[adminz_woocommerce_add_buy_now_hook]">
								<?php 
								$hooklist = [
									'woocommerce_single_product_summary', 
									'woocommerce_before_add_to_cart_form', // 
									'woocommerce_before_variations_form', //
									'woocommerce_before_add_to_cart_button', 
									'woocommerce_before_single_variation', 
									'woocommerce_single_variation', 
									'woocommerce_before_add_to_cart_quantity', 
									'woocommerce_after_add_to_cart_quantity', 
									'woocommerce_after_single_variation', 
									'woocommerce_after_add_to_cart_button', 
									'woocommerce_after_variations_form', 
									'woocommerce_after_add_to_cart_form',
								 	'woocommerce_product_meta_start', 
									'woocommerce_product_meta_end', 
									'woocommerce_share'
								];
								$current = $this->get_option_value( 'adminz_woocommerce_add_buy_now_hook' );
								foreach ($hooklist as $value) {									
									if($value == $current) {$selected = "selected";} else{ $selected = "";}
									echo '<option '.$selected.' value="'.$value.'">'.$value.'</option>';
								}
								 ?>
							</select>
						</label>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						Remove quanity field
					</th>
					<td>
						<label>
							<input type="checkbox" name="adminz_woocommerce[adminz_woocommerce_remove_quanity]" <?php if($this->get_option_value('adminz_woocommerce_remove_quanity') =="on") echo "checked"; ?>> Enable
						</label>
					</td>					
				</tr>
			</table>
			<?php submit_button(); ?>
			<table class="form-table">		
				<tr valign="top">
					<th scope="row">
						<h3>Checkout</h3>
					</th>
				</tr>
				<tr valign="top">
					<th scope="row">
						Simple checkout field
					</th>
					<td>
						<label>
							<input type="checkbox" name="adminz_woocommerce[adminz_woocommerce_simple_checkout_field]" <?php if($this->get_option_value('adminz_woocommerce_simple_checkout_field') == "on") echo 'checked'; ?>>
							
							<?php 
							if(empty(WC()->payment_gateways->get_available_payment_gateways())){
								echo '<mark>Next, you need to set at least 1 payment method for the ordering function to work.</mark>';
							};
							 ?>
					 	</label>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<h3>Product filter</h3>
					</th>
				</tr>
				<tr valign="top">
					<th scope="row">
						Remove Select Woo
					</th>
					<td>
						<input type="checkbox" name="adminz_woocommerce[adminz_woocommerce_remove_select_woo]" <?php if($this->get_option_value('adminz_woocommerce_remove_select_woo') == "on") echo "checked"; ?>> Select 2 script in sidebar filter
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<h3>Products Search Form</h3>
					</th>
				</tr>
				<tr valign="top">
					<th scope="row">
						Shortcode
					</th>
					<td>
						<p>Shortcode example: <code>[adminz_woo_form <b>fields</b>="product_visibility,product_cat,product_tag,pa_color,pa_size,new_taxonomy,title,price,submit" <b>style</b>="product_visibility,product_cat,product_tag,pa_color,pa_size,new_taxonomy,title,price,submit" <b>field_col_12</b>="title" <b>closerow_before</b>="product_visibility,submit"]</code></p>
						<p>Description:</p>
						<p>fields: Type your taxonomies to show field</p>
						<p>style: Taxonomies what you want to show as button style. Default is dropdown</p>
						<p>field_col_12: display field as full width</p>
						<p>closerow_before: add line break before this field</p>
						<h4>Widget </h4>
						<p>Find widget name as: <b> <?php echo __("Filter products",'woocommerce'). " - NEW"; ?></b></p>
						<p>Use widget in your shop page | Product search results page </p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						Price filter Milestones
					</th>
					<td>
						<table>
							<tr>
								<td style="width: 50%;">
									<p>Values</p>
									<textarea cols=70 rows=4 name="adminz_woocommerce[filter_price_values]" placeholder="0- 5000&#10;5000- 8000&#10;8000- 10000&#10;10000"><?php echo $this->get_option_value('filter_price_values'); ?></textarea> 									
								</td>
								<td style="width: 50%;">
									<p>Display</p>
									<textarea cols=70 rows=4 name="adminz_woocommerce[filter_price_display]" placeholder="<5 thousands&#10;5 thousands - 8 thousands&#10;8 thousands - 10 thousands&#10;> 10 thousands"><?php echo $this->get_option_value('filter_price_display'); ?></textarea>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<h3>Currency </h3>
					</th>
				</tr>
				<tr valign="top">
					<th scope="row">
						Change currency formatting
					</th>
					<td>
						<p>
							<input type="text" name="adminz_woocommerce[adminz_woocommerce_from_currency_formatting]" value="<?php echo $this->get_option_value('adminz_woocommerce_from_currency_formatting'); ?>" placeholder="VND,USD">  Currencies key
						</p>
						<p>
							<input type="text" name="adminz_woocommerce[adminz_woocommerce_to_currency_formatting]" value="<?php echo $this->get_option_value('adminz_woocommerce_to_currency_formatting'); ?>" placeholder="Vnđ,Dollars"> Currencies formatings.
						</p>
						<p>
							<button class="button" onclick="jQuery('#adminz_change_currenct_formating').toggle(); return false;">Show all currency</button>
						</p>						
						<div class="hidden" id="adminz_change_currenct_formating"><?php echo "<pre>";print_r(get_woocommerce_currencies());echo "</pre>"; ?></div>

					</td>
				</tr>
			</table>
			<?php submit_button(); ?>
			<table class="form-table">	
				<tr valign="top">
					<th scope="row">
						<h3>Template hook</h3>
					</th>
				</tr>
				<tr valign="top">
					<th scope="row">
						List action hooks						
					</th>
					<td>
						<p>type <code>[adminz_test]</code> to test</p>
						<?php 
			        	$adminz_woocommerce_action_hook = $this->get_option_value('adminz_woocommerce_action_hook');			        	
			        	foreach (self::$action_hooks as $key => $value) {
			        		?>
			        		<div>
			        			<textarea cols="70" rows="1" name="adminz_woocommerce[adminz_woocommerce_action_hook][<?php echo $value;?>]"><?php echo isset($adminz_woocommerce_action_hook[$value]) ? $adminz_woocommerce_action_hook[$value] : "";?></textarea><small><?php echo $value; ?></small>
			        		</div>
			        		<?php
			        	}
		        	 	?>
					<input type="checkbox" name="adminz_woocommerce[adminz_woocommerce_test_all_hook]" <?php if($this->get_option_value('adminz_woocommerce_test_all_hook') == "on") echo "checked"; ?>><em>Test all hook</em>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						Custom action hooks						
					</th>
					<td>
						<p>type <code>[adminz_test]</code> to test</p>		
						<?php $woo_hook_data = $this->get_option_value('adminz_woocoommerce_custom_hook'); ?>
						<textarea style="display: none;" cols="70" rows="10" name="adminz_woocommerce[adminz_woocoommerce_custom_hook]"><?php echo $woo_hook_data; ?></textarea> </br>
						<div>
							<textarea cols="70" rows="1" disabled>Shortcode</textarea> 
							<textarea cols="70" rows="1" disabled>Action hook</textarea> 
							<textarea cols="20" rows="1" disabled>Priority</textarea>
							<textarea cols="20" rows="1" disabled>Conditional</textarea>
						</div>
						<div class="adminz_woocoommerce_custom_hook">
							<?php 
							$woo_hook_data = json_decode($woo_hook_data);							
							if(!empty($woo_hook_data) and is_array($woo_hook_data)){
								foreach ($woo_hook_data as $key => $value) {
									$value[0] = isset($value[0])? $value[0] : "";
									$value[1] = isset($value[1])? $value[1] : "";
									$value[2] = isset($value[2])? $value[2] : "";
									$value[3] = isset($value[3])? $value[3] : "";
									echo '<div class="item" style="margin-bottom: 5px;">
										<textarea cols="70" rows="1" name="" placeholder="[your shortcode]">'.$value[0].'</textarea>
										<textarea cols="70" rows="1" name="" placeholder="your action hook">'.$value[1].'</textarea>
										<textarea cols="20" rows="1" name="" placeholder="your priority">'.$value[2].'</textarea>
										<textarea cols="20" rows="1" name="" placeholder="your conditional">'.$value[3].'</textarea>
										<button class="button adminz_woocoommerce_custom_hook_remove" >Remove</button>
									</div>';
								}
							}
							?>							
						</div>
						<button class="button" id="adminz_woocoommerce_custom_hook_add">Add new</button>
						<script type="text/javascript">
							window.addEventListener('DOMContentLoaded', function() {
								(function($){
									var custom_woo_hooks_item = '<div class="item" style="margin-bottom: 5px;"> <textarea cols="70" rows="1" name="" placeholder="[your shortcode]"></textarea> <textarea cols="70" rows="1" name="" placeholder="your action hook"></textarea> <textarea cols="20" rows="1" name="" placeholder="your priority"></textarea> <textarea cols="20" rows="1" name="" placeholder="your conditional"></textarea><button class="button adminz_woocoommerce_custom_hook_remove" >Remove</button> </div>'; 
									$("body").on("click","#adminz_woocoommerce_custom_hook_add",function(){
										$(".adminz_woocoommerce_custom_hook").append(custom_woo_hooks_item);
										adminz_woocoommerce_custom_hook_update();
										return false;
									});
									$("body").on("click",".adminz_woocoommerce_custom_hook_remove",function(){
										$(this).closest(".item").remove();
										adminz_woocoommerce_custom_hook_update();
										return false;
									});
									$('body').on('keyup', '.adminz_woocoommerce_custom_hook .item textarea', function() {
					        			adminz_woocoommerce_custom_hook_update();					        			
					        		});
									function adminz_woocoommerce_custom_hook_update(){
										var data_js = $('textarea[name="adminz_woocommerce\[adminz_woocoommerce_custom_hook\]"]').val();

										var alldata = [];
										$('.adminz_woocoommerce_custom_hook .item').each(function(){
											var itemdata = [];
											var shortcode 	= $(this).find('textarea:nth-child(1)').val();
											var hook 		= $(this).find('textarea:nth-child(2)').val();
											var priority 	= $(this).find('textarea:nth-child(3)').val(); 
											var conditional 	= $(this).find('textarea:nth-child(4)').val(); 
											itemdata = [shortcode,hook,priority,conditional];	
											alldata.push(itemdata);																					
										});
										$('textarea[name="adminz_woocommerce\[adminz_woocoommerce_custom_hook\]"]').val(JSON.stringify(alldata));
									}
								})(jQuery);
							});
						</script>
					</td>
				</tr>
			</table>
			
			<?php submit_button(); ?>
		</form>
		<?php
		return ob_get_clean();
	}
	static function get_arr_attributes(){
		$listattr = wc_get_attribute_taxonomies();		
	    $optionattr = [];
	    $optionattr2 = [];
	    if(!empty($listattr) and is_array($listattr)){
	        foreach ($listattr as $key => $value) {
	            $optionattr[$value->attribute_name] = $value->attribute_label;
	            $optionattr2["filter_".$value->attribute_name] = $value->attribute_label;
	        }
	    }
	    return $optionattr2;
	}
	static function get_arr_tax($hide_default = false){
		// hide default: exclude category, tag, to return 
	    $taxonomies = ADMINZ_Helper_Woocommerce_Taxonomy::lay_taxonomy_co_the_loc();
	    $tax_arr = [];
	    if(!$hide_default){
	    	$tax_arr = [""=>"--"];
	    }	    
	    if(!empty($taxonomies) and is_array($taxonomies)){
		    foreach ($taxonomies as $key => $value) {
		        $label = $value->labels->singular_name;

		        if(ADMINZ_Helper_Woocommerce_Taxonomy::thay_doi_taxonomy_label($value)){
		            $label = ADMINZ_Helper_Woocommerce_Taxonomy::thay_doi_taxonomy_label($value);
		        }
		        if($hide_default){
		        	if(!in_array($value->name,['product_cat','product_tag','title','price'])){
		        		$tax_arr[$value->name] =$label;
		        	}
		        }else{
		        	$tax_arr[$value->name] =$label;
		        }
		        
		    }
	    }
	    return $tax_arr;
	}
	
	function custom_remove_woo_checkout_fields( $fields ) {
	  //unset($fields['billing']['billing_phone']);
	    $fields['billing']['billing_phone']['placeholder'] = __('Phone Number','woocommerce');
	    $fields['billing']['billing_phone']['priority'] = 10;
	    $fields['billing']['billing_phone']['class'] = ['form-row-last'];
	    unset($fields['billing']['billing_phone']['class'][1]);
	    //unset($fields['billing']['billing_first_name']);
	    unset($fields['billing']['billing_email']);
	    unset($fields['billing']['billing_last_name']);
	    unset($fields['billing']['billing_company']);
	    //unset($fields['billing']['billing_address_1']);
	    $fields['billing']['billing_address_1']['class'] = ['form-row-wide'];
	    unset($fields['billing']['billing_address_2']);
	    //unset($fields['billing']['billing_city']);
	    $fields['billing']['billing_city']['placeholder'] = __('City name.','woocommerce');
	    unset($fields['billing']['billing_postcode']);
	    $fields['billing']['billing_city']['required'] = 0;
	    //unset($fields['billing']['billing_country']);
	    unset($fields['billing']['billing_state']); 
	    unset($fields['account']['account_username']);
	    unset($fields['account']['account_password']);
	    unset($fields['account']['account_password-2']);
	    $fields['billing']['billing_first_name']['label'] = __('Name (Last, First)','woocommerce');
	    $fields['billing']['billing_first_name']['placeholder'] = __('Name (Last, First)','woocommerce');
	    //$fields['billing']['billing_first_name']['class'][0] = "form-row-wide";

	    unset($fields['shipping']['shipping_email']);
	    unset($fields['shipping']['shipping_last_name']);
	    unset($fields['shipping']['shipping_company']);
	    //unset($fields['shipping']['shipping_address_1']);
	    
	    $fields['shipping']['shipping_address_1']['required'] = 0;
	    $fields['shipping']['shipping_address_1']['class'] = ['form-row-wide'];
	    unset($fields['shipping']['shipping_address_2']);
	    //unset($fields['shipping']['shipping_city']);
	    $fields['shipping']['shipping_city']['placeholder'] = __('City name.','woocommerce');
	    unset($fields['shipping']['shipping_postcode']);
	    //unset($fields['shipping']['shipping_country']);
	    unset($fields['shipping']['shipping_state']);   
	    unset($fields['account']['account_username']);
	    $fields['shipping']['shipping_first_name']['label'] = __('Name (Last, First)','woocommerce');
	    $fields['shipping']['shipping_first_name']['placeholder'] = __('Name (Last, First)','woocommerce');
	    $fields['shipping']['shipping_first_name']['class'] = ["form-row-wide"];

	    ?>
	    <style type="text/css">
			#billing_country_field,#shipping_country_field {display: none; }
			.address-field{ width: 100% !important;}
		</style>
	    <?php
	    return $fields;
	}
	function register_option_setting() {		
		register_setting( $this->options_group, 'adminz_woocommerce');

		
		ADMINZ_Helper_Language::register_pll_string('adminz_woocommerce[adminz_woocommerce_empty_price_html]',self::$slug,false);
		ADMINZ_Helper_Language::register_pll_string('adminz_woocommerce[adminz_woocommerce_ajax_add_to_cart_text]',self::$slug,false);
		ADMINZ_Helper_Language::register_pll_string('adminz_woocommerce[adminz_woocommerce_add_buy_now_text]',self::$slug,false);
		ADMINZ_Helper_Language::register_pll_string('adminz_woocommerce[adminz_woocommerce_add_buy_now_popup_text]',self::$slug,false);
	}
}