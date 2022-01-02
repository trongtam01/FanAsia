<?php 
namespace Adminz\Admin;
use Adminz\Helper\ADMINZ_Helper_Custom_Portfolio;
use Adminz\Helper\ADMINZ_Helper_Language;
use Flatsome_Default;

class ADMINZ_Flatsome extends Adminz {
	public $options_group = "adminz_flatsome";
	public $title = 'Flatsome';
	static $slug  = 'adminz_flatsome';
	static $flatsome_actions = ['flatsome_absolute_footer_primary','flatsome_absolute_footer_secondary','flatsome_account_links','flatsome_after_404','flatsome_after_account_user','flatsome_after_blog','flatsome_after_body_open','flatsome_after_breadcrumb','flatsome_after_footer','flatsome_after_header','flatsome_after_header_bottom','flatsome_after_page','flatsome_after_page_content','flatsome_after_product_images','flatsome_after_product_page','flatsome_before_404','flatsome_before_blog','flatsome_before_breadcrumb','flatsome_before_comments','flatsome_before_footer','flatsome_before_header','flatsome_before_page','flatsome_before_page_content','flatsome_before_product_images','flatsome_before_product_page','flatsome_before_product_sidebar','flatsome_before_single_product_custom','flatsome_blog_post_after','flatsome_blog_post_before','flatsome_breadcrumb','flatsome_cart_sidebar','flatsome_category_title','flatsome_category_title_alt','flatsome_footer','flatsome_header_background','flatsome_header_elements','flatsome_header_wrapper','flatsome_portfolio_title_after','flatsome_portfolio_title_left','flatsome_portfolio_title_right','flatsome_product_box_actions','flatsome_product_box_after','flatsome_product_box_tools_bottom','flatsome_product_box_tools_top','flatsome_product_image_tools_bottom','flatsome_product_image_tools_top','flatsome_product_title','flatsome_product_title_tools','flatsome_products_after','flatsome_products_before','flatsome_sale_flash','flatsome_woocommerce_shop_loop_images'];
	public $adminz_theme_locations = [
		'desktop'=>[
			'additional-menu' => 'Additional Menu', 
			'another-menu' => 'Another Menu', 
			'extra-menu' => 'Extra Menu' 
		],
		'sidebar' => [
			'additional-menu-sidebar' => 'Additional Menu - Sidebar', 
			'another-menu-sidebar' => 'Another Menu - Sidebar', 
			'extra-menu-sidebar' => 'Extra Menu - Sidebar' 
		]		
	];
	static $options;
	
	function __construct() {		
		if(!$this->is_flatsome()) return;
		$this->title = $this->get_icon_html('flatsome').$this->title;
		
		$this::$options = get_option('adminz_flatsome', []);
		add_filter( 'adminz_setting_tab', [$this,'register_tab']);
		add_action(	'admin_init', [$this,'register_option_setting'] );
		add_action( 'admin_init', function () {remove_action( 'admin_notices', 'flatsome_maintenance_admin_notice' ); });
		add_action( 'init', [$this, 'add_shortcodes'] );		
		add_filter( 'flatsome_text_formats',[$this,'custom_text_format']);

		add_action( 'wp_enqueue_scripts', [$this,'enqueue_package'],999);		
		add_action( 'wp_head', [$this,'adminz_fix_css'], 999 );// vi la custom text nen phai dat hook la wp_head 999		
		add_action( 'wp_footer', [$this,'adminz_fix_js'],999);
		
		add_action( 'init', [$this,'adminz_register_my_menus']);
		add_filter( 'flatsome_header_element', [$this,'adminz_register_header_element']);
		add_action( 'flatsome_header_elements', [$this,'adminz_do_header_element']);

		new ADMINZ_Helper_Custom_Portfolio();

 		$this->flatsome_filter_hook();
 		$this->flatsome_action_hook(); 		 		

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
	function enqueue_package(){		
		$choose = $this->get_option_value('adminz_choose_stylesheet');
		foreach ($this->get_packages() as $key => $value) {
			if($value['slug'] == $choose){
				wp_enqueue_style( 'flatsome_css_pack',$value['url']);
			}
		} 		
 	}
	function adminz_fix_css(){
		ob_start();
		?>
		<style id="adminz_flatsome_fix" type="text/css">
			/*Custom class*/
			:root{
				--secondary-color:  <?php echo get_theme_mod('color_secondary', Flatsome_Default::COLOR_SECONDARY ); ?>;
				--success-color:  <?php echo get_theme_mod('color_success', Flatsome_Default::COLOR_SUCCESS ); ?>;
				--alert-color:  <?php echo get_theme_mod('color_alert', Flatsome_Default::COLOR_ALERT ); ?>;
			}
			.row-nopaddingbottom .flickity-slider>.col,
			.row-nopaddingbottom>.col,
			.nopadding,.nopaddingbottom{
				padding-bottom: 0 !important;
			}
			.no-marginbottom, .no-marginbottom h1, .no-marginbottom h2, .no-marginbottom h3, .no-marginbottom h4, .no-marginbottom h5, .no-marginbottom h6{
				margin-bottom: 0px;
			}
			.row .section{
				padding-left: 15px;
				padding-right: 15px;
			}
			.sliderbot{
				position: absolute;
				left:0;
				bottom: 0;
			}
			.bgr-size-auto .section-bg.bg-loaded{	
			    background-size: auto !important;
			}
			.adminz_button>i,.adminz_button.reveal-icon>i{display: inline-flex;}

			h1 strong, h2 strong, h3 strong, h4 strong, h5 strong, h6 strong {
				font-weight: 900;
			}				
			/*fix*/
			.footer-1, .footer-2{
				background-size: 100%;
    			background-position: center;
			}
			@media (max-width: 549px){
				.section-title a{
					margin-left: unset !important;
					margin-top:  15px;
					margin-bottom: 15px;
					padding-left:  0px;
				}
			}
			.footer-primary{
				padding: 7.5px 0;
			}
			@media (max-width:  549px){
				.absolute-footer .container{
					display: flex;
				    flex-direction: column-reverse
				}
			}
			.mfp-close{
			    mix-blend-mode: unset;
			}
			.sliderbot .img-inner{
				border-radius: 0;
			}
			.dark .nav-divided>li+li>a:after{
				border-left: 1px solid rgb(255 255 255 / 65%);
			}
			.adminz_navigation_wrapper .sub-menu{
				z-index: 22;
			}
			.page-checkout li.wc_payment_method,
			li.list-style-none{
				list-style: none;
				margin-left: 0px !important;
			}
			.mfp-content .nav.nav-sidebar>li{
				width: calc(100% - 20px );
			}
			.mfp-content .nav.nav-sidebar>li:not(.header-social-icons) a{
				padding-left: 10px;
			}
			.mfp-content .nav.nav-sidebar>li.html{
				padding-left:  0px;
				padding-right:  0px;
			}
			.mfp-content .nav.nav-sidebar>li.header-contact-wrapper ul li ,
			.mfp-content .nav.nav-sidebar>li.header-contact-wrapper ul li a,
			.mfp-content .nav.nav-sidebar>li.header-newsletter-item a{
				padding-left:  0px;
			}
			.nav-tabs>li>a{background-color: rgb(241 241 241);}
			.portfolio-single-page ul li{
				margin-left: 1.3em;
			}
			<?php $product_gallery_col = $this->get_option_value('adminz_flatsome_woocommerce_product_gallery'); if(!in_array($product_gallery_col,[0,4])){?>
				.product-gallery .product-thumbnails .col{
					width:  <?php echo 100/$product_gallery_col; ?>%;
				} <?php } ?>
			<?php $mobile_overlay_bg = get_theme_mod('mobile_overlay_bg'); if($mobile_overlay_bg){ ?>
				.main-menu-overlay{
					background: #0b0b0b;
				}
				.main-menu-overlay+ .off-canvas .mfp-content{
					background: <?php echo $mobile_overlay_bg; ?>
				}
			<?php } ?> <?php $enable_sidebar_divider = get_theme_mod('blog_layout_divider'); if(!$enable_sidebar_divider){?>
			body.page .col-divided,
			body.single-product .row-divided>.col+.col:not(.large-12){
				border-right: none;
				border-left: none;
			} <?php } ?>
			<?php if($this->get_option_value('adminz_enable_vertical_blog_post_mobile') == "on"){  ?>
			@media (max-width:  549px){
				.box-blog-post{
					display: flex;
				}
				.col:not(.grid-col) .box-blog-post .box-image{
					width: 25% !important;
					max-width: 25% !important;					
					margin:  15px 0px 15px 0px;
					position: relative !important;
				}
				.col:not(.grid-col) .box-blog-post .box-text{
					text-align: left !important;
					position: relative !important;
					padding-left: 15px !important;
					padding-right: 15px !important;
				}
				.box-overlay .box-text, .box-shade .box-text{
					padding-top:  0px !important;
					margin-top:  0px !important;
				}
				.box-blog-post .image-cover{
					padding-top:  100% !important;
				}	
				.has-shadow .col:not(.grid-col) .post-item .box-blog-post .box-image,
				.has-shadow .col:not(.grid-col) .box-blog-post .box-image{
					margin-left:  15px;
				}
				.box-blog-post.box-shade .box-text{

				}
				<?php add_filter( 'body_class', function( $classes ) {return array_merge( $classes, array( 'adminz_enable_vertical_blog_post_mobile' ) ); } ); ?>
			}
			<?php } ?>

			/*woocommerce*/				
			<?php if($this->get_option_value('adminz_enable_vertical_product_mobile') == "on"){  ?>
				@media (max-width:  549px){
					.product-small{
						display: flex;
					}
					.product-small .box-image{
						width: 25% !important;
						max-width: 25% !important;						
						margin:  15px 0px 15px 0px;
					}
					.has-shadow .product-small .box-image{
						margin-left:  15px;
					}
					.product-small .box-text{
						text-align: left;
						padding:  15px;
					}					
					<?php add_filter( 'body_class', function( $classes ) {return array_merge( $classes, array( 'adminz_enable_vertical_product_mobile' ) ); } ); ?>
				}
			<?php } ?>
			<?php if($this->get_option_value('adminz_enable_vertical_product_related_mobile') == "on"){  ?>
				@media (max-width:  549px){
					.related .product-small{
						display: flex;
					}
					.related .product-small .box-image{
						width: 25% !important;
						max-width: 25% !important;						
						margin:  15px 0px 15px 0px;
					}
					.related .has-shadow .product-small .box-image{
						margin-left:  15px;
					}
					.related .product-small .box-text{
						text-align: left;
						padding:  15px;
					}
				}
			<?php } ?>
			.related-products-wrapper>h3{
				max-width: unset;
			}
			<?php if($this->get_option_value('fix_product_image_box_vertical') == "on"){  ?>
				@media (min-width: 532px){
					.related-products-wrapper .box-vertical .box-image,
					.has-box-vertical .col .box-image{
						width: 25% !important;
						min-width: unset !important;
					}
				}
			<?php } ?>
			/*contact form 7*/
			input[type=submit].is-xsmall{font-size: .7em; }
			input[type=submit].is-smaller{font-size: .75em; }
			input[type=submit].is-mall{font-size: .8em; }
			input[type=submit]{font-size: .97em; }
			input[type=submit].is-large{font-size: 1.15em; }
			input[type=submit].is-larger{font-size: 1.3em; }
			input[type=submit].is-xlarge{font-size: 1.5em; }
			.wpcf7-form{ margin-bottom: 0px; }
			/*zalo icon*/
			.button.zalo:not(.is-outline), .button.zalo:hover{
				color: #006eab !important;
			}
			/*cf7*/
			@media (max-width:  549px){
				.flex-row.form-flat.medium-flex-wrap{
					align-items: flex-start;
				}
				.flex-row.form-flat.medium-flex-wrap .ml-half{
					margin-left:  0px !important;
				}
			}
			.archive-page-header{
				display: none;
			}
		</style>
		<?php
		echo apply_filters( 'adminz_output_debug', ob_get_clean() );
	}
	function adminz_fix_js(){
		if($this->get_option_value('adminz_enable_hover_tab') == "on"){
		?>
		<script type="text/javascript">
			window.addEventListener('DOMContentLoaded', function() {
				(function($){
					$('#masthead .menu-item .has-block .tabbed-content .nav li').on("mouseover", function(event){
					var myindex =  $(this).index() +1;
					var tab_panels = $(this).closest(".tabbed-content").find(".tab-panels");
					tab_panels.find(".panel").removeClass("active");
					tab_panels.find(".panel:nth-child("+myindex+")").addClass("active");
				});
				})(jQuery);
			});
		</script>
		<?php
		}
	}
	function add_shortcodes(){
		$shortcodefiles = glob(ADMINZ_DIR.'shortcodes/flatsome*.php');
		if(!empty($shortcodefiles)){
			foreach ($shortcodefiles as $file) {
				require_once $file;
			}
		}
	}
	function flatsome_action_hook(){		
		static $called = false;
		if($called){ return; }

		$adminz_flatsome_action_hook = $this->get_option_value('adminz_flatsome_action_hook');

		if(!empty($adminz_flatsome_action_hook) and is_array($adminz_flatsome_action_hook)){			
			foreach ($adminz_flatsome_action_hook as $action => $shortcode) {
				if($shortcode){
					add_action($action,function() use ($shortcode){
						echo do_shortcode(html_entity_decode($shortcode));
					});
				}				
			}
		}
		if($this->get_option_value('adminz_flatsome_test_all_hook') == "on"){
			foreach (self::$flatsome_actions as $action) {
				add_action($action, function() use ($action){
					echo do_shortcode('[adminz_test content="'.$action.'"]');
				});
			}
		}
		$flatsome_hook_data = json_decode($this->get_option_value('adminz_flatsome_custom_hook'));		
		if(!empty($flatsome_hook_data) and is_array($flatsome_hook_data)){
			foreach ($flatsome_hook_data as $value) {
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
		$called = true;
	}
	function flatsome_filter_hook(){		
		$btn_inside = $this->get_option_value('adminz_flatsome_lightbox_close_btn_inside');
		if( $btn_inside == 'on'){
			add_filter( 'flatsome_lightbox_close_btn_inside', '__return_true' );
		}
		$btn_close = $this->get_option_value('adminz_flatsome_lightbox_close_button');		
		if($btn_close){
			add_filter( 'flatsome_lightbox_close_button', function ( ) use ($btn_close){
				$html = '<button title="%title%" type="button" style="fill:white; display: grid; padding: 5px;" class="mfp-close">';
				$html .= $this->get_icon_html($btn_close );
				$html .= '</button>';
				return $html;
			});
		}
		if(is_admin()){			
			$adminz_use_mce_button = $this->get_option_value('adminz_use_mce_button');
			if($adminz_use_mce_button){
				add_filter("mce_buttons", function ($buttons){
			        array_push($buttons,
			            "alignjustify",
			            "subscript",
			            "superscript"
			        );
			        return $buttons;
			    });
				add_filter("mce_buttons_2", function ($buttons){
			        array_push($buttons,
			            "fontselect",
			            "cleanup"
			        );
			        return $buttons;
			    }, 9999);
			}
		}		
		$viewport = $this->get_option_value('adminz_flatsome_viewport_meta');				
		if($viewport =="on"){
			add_filter( 'flatsome_viewport_meta',function (){ __return_null();});
		}
	}
	function adminz_register_my_menus() {
		foreach ($this->adminz_theme_locations as $key => $value) {
			register_nav_menus($value);	
		}		
	}
	function adminz_register_header_element($arr){

		foreach ($this->adminz_theme_locations as $navtype => $navgroup) {
			foreach ($navgroup as $key => $value) {
				$arr[$key] = $value;	
			}			
		}
		return $arr;
	}
	function adminz_do_header_element($slug){
		foreach ($this->adminz_theme_locations as $navtype => $navgroup) {
			foreach ($navgroup as $key => $value) {
				$walker	= 'FlatsomeNavDropdown';
				if ($navtype == 'sidebar') $walker = 'FlatsomeNavSidebar';
				
				if($slug == $key){
					flatsome_header_nav($key,$walker);
				}
			}
			
		}
	}
	function custom_text_format($arr){
		foreach ($arr as $key => $value) {
			if($value['title'] == 'List Styles'){
				$arr[$key]['items'][] = array(
	              'title' => 'Style List - None',
	              'selector' => 'li',
	              'classes' => 'list-style-none',
	            );
			}
			if($value['title'] == 'Text Background'){
				$arr[$key]['items'][] = array(
	              'title' => 'Text shadow 1',
	              'inline' => 'span',
	              'classes' => 'text-shadow-1',
	            );
	            $arr[$key]['items'][] = array(
	              'title' => 'Text shadow 2',
	              'inline' => 'span',
	              'classes' => 'text-shadow-2',
	            );
	            $arr[$key]['items'][] = array(
	              'title' => 'Text shadow 3',
	              'inline' => 'span',
	              'classes' => 'text-shadow-3',
	            );
	            $arr[$key]['items'][] = array(
	              'title' => 'Text shadow 4',
	              'inline' => 'span',
	              'classes' => 'text-shadow-4',
	            );
	            $arr[$key]['items'][] = array(
	              'title' => 'Text shadow 5',
	              'inline' => 'span',
	              'classes' => 'text-shadow-5',
	            );
			}
		}
		return $arr;
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
	        		<th><h3>UX builder</h3></th>
	        		<td>Some shortcode from ux builder has beed added. Open Ux builder to show</td>
	        	</tr>	
	        	<tr valign="top">
					<th scope="row">
						<h3>Stylesheet CSS package</h3>
					</th>
				</tr>
	            <tr valign="top">
	                <th scope="row">Choose style</th>
	                <td>
	                	<?php 						
						$choose = $this->get_option_value('adminz_choose_stylesheet');
	                	 ?>
	                	<select name="adminz_flatsome[adminz_choose_stylesheet]">
	                	<?php
                		foreach ($this->get_packages() as $pack) {
                			$seleted = ($choose == $pack['slug']) ? "selected" : "";
                			?>
                			<option <?php echo $seleted; ?> value="<?php echo $pack['slug'] ?>"><?php echo $pack['name']; ?></option>
                			<?php
                		}
                	 	?>
                	 	</select>
	                </td>
	            </tr>	            
	        	<tr valign="top">
	        		<th><h3>Flatsome config</h3></th>
	        		<td></td>
	        	</tr>
	        	<tr valign="top">
	        		<th>Editor</th>
	        		<td>
	        			<label>
	        				<?php 
	        				$checked = "";
	        				if($this->check_option('adminz_use_mce_button',false,"on")){
	        					$checked = "checked";
	        				}
	        				?>
	                		<input type="checkbox" name="adminz_flatsome[adminz_use_mce_button]" <?php echo $checked; ?>> Enable Tiny MCE editor
	                	</label><br>
	        		</td>
	        	</tr>
	        	<tr valign="top">	        		
	        		<th>
	        			Lightbox close button inside
	        		</th>
	        		<td>
	        			<input type="checkbox" <?php echo $this->check_option('adminz_flatsome_lightbox_close_btn_inside',false,"on") ? 'checked' : ''; ?>  name="adminz_flatsome[adminz_flatsome_lightbox_close_btn_inside]"/>
	        		</td>
	        	</tr>
	        	<tr valign="top">	        		
	        		<th>
	        			Lightbox close button icon
	        		</th>
	        		<td>
	        			<input type="text" value="<?php echo $this->get_option_value('adminz_flatsome_lightbox_close_button');?>"  name="adminz_flatsome[adminz_flatsome_lightbox_close_button]"/>
	        			<small>Example: close-round or svg url| Default: close</small>
	        		</td>
	        	</tr>
	        	<tr valign="top">	        		
	        		<th>
	        			Disable Meta viewport
	        		</th>
	        		<td>
	        			<input type="checkbox" <?php echo $this->check_option('adminz_flatsome_viewport_meta',false,"on") ? 'checked' : ''; ?>  name="adminz_flatsome[adminz_flatsome_viewport_meta]"/>
	        		</td>
	        	</tr>
	        	<tr valign="top">	        		
	        		<th>
	        			Flatsome woocommerce product gallery 
	        		</th>
	        		<td>
	        			<input type="text" value="<?php echo $this->get_option_value('adminz_flatsome_woocommerce_product_gallery');?>"  name="adminz_flatsome[adminz_flatsome_woocommerce_product_gallery]"/>
	        			<small>Small thumbnails in product gallery</small>
	        		</td>
	        	</tr>	
	        	<tr valign="top">	        		
	        		<th>
	        			Vertical Posts box on mobile
	        		</th>
	        		<td>
	        			<input type="checkbox" <?php echo $this->check_option('adminz_enable_vertical_blog_post_mobile',false,"on") ? 'checked' : ''; ?>  name="adminz_flatsome[adminz_enable_vertical_blog_post_mobile]"/>
	        			<small>Enable: Keep vertical in mobile | default post thumbnail width: 25%</small>
	        		</td>
	        	</tr>
	        	<?php if($this->is_woocommerce()){ ?>
	        	<tr valign="top">	        		
	        		<th>
	        			Vertical Product box on mobile
	        		</th>
	        		<td>
	        			<input type="checkbox" <?php echo $this->check_option('adminz_enable_vertical_product_mobile',false,"on") ? 'checked' : ''; ?>  name="adminz_flatsome[adminz_enable_vertical_product_mobile]"/>
	        			<small>Enable: Keep vertical in mobile | default product thumbnail width: 25%</small>
	        		</td>
	        	</tr>
	        	<tr valign="top">	        		
	        		<th>
	        			Vertical Product related on mobile
	        		</th>
	        		<td>
	        			<input type="checkbox" <?php echo $this->check_option('adminz_enable_vertical_product_related_mobile',false,"on") ? 'checked' : ''; ?>  name="adminz_flatsome[adminz_enable_vertical_product_related_mobile]"/>
	        			<small>Enable: Vertical on mobile | default product thumbnail width: 25%</small>
	        		</td>
	        	</tr>
	        	<tr valign="top">	        		
	        		<th>
	        			Fix product vertical thumbnail size desktop
	        		</th>
	        		<td>
	        			<input type="checkbox" <?php echo $this->check_option('fix_product_image_box_vertical',false,"on") ? 'checked' : ''; ?>  name="adminz_flatsome[fix_product_image_box_vertical]"/>
	        			<small>Enable: Fixed product box image thumbnail width: 25%</small>
	        		</td>
	        	</tr>
	        	<?php } ?>
	        	<tr valign="top">	        		
	        		<th>
	        			Enable Zalo, skype icon support
	        		</th>
	        		<td>
	        			<input type="checkbox" <?php echo $this->check_option('adminz_enable_zalo_support',false,"on") ? 'checked' : ''; ?>  name="adminz_flatsome[adminz_enable_zalo_support]"/>
	        			<small>Enable: Add new builder with zalo follow icon</small>
	        		</td>
	        	</tr>
	        	<tr valign="top">
	        		<th><h3>Portfolio</h3></th>
	        		<td></td>
	        	</tr>
	        	<tr valign="top">	        		
	        		<th>
	        			<?php echo __( 'Portfolio', 'flatsome-admin' ); ?> rename
	        		</th>
	        		<td>	
	        			<input type="text" value="<?php echo $this->get_option_value('adminz_flatsome_portfolio_name');?>"  name="adminz_flatsome[adminz_flatsome_portfolio_name]"/>        			
	        			<small>First you can try with Customize->Portfolio->Custom portfolio page <a href="https://www.youtube.com/watch?v=3cl6XCUjOPI">Link</a></small>        			
	        		</td>
	        	</tr>
	        	<tr valign="top">	        		
	        		<th>
	        			<?php echo  __( 'Portfolio Categories', 'flatsome-admin' ); ?> rename
	        		</th>
	        		<td>
	        			<input type="text" value="<?php echo $this->get_option_value('adminz_flatsome_portfolio_category');?>"  name="adminz_flatsome[adminz_flatsome_portfolio_category]"/>
	        		</td>
	        	</tr>
	        	<tr valign="top">	        		
	        		<th>
	        			<?php echo __( 'Portfolio Tags', 'flatsome-admin' ); ?> rename
	        		</th>
	        		<td>
	        			<input type="text" value="<?php echo $this->get_option_value('adminz_flatsome_portfolio_tag');?>"  name="adminz_flatsome[adminz_flatsome_portfolio_tag]"/>
	        		</td>
	        	</tr>	        	
	        	<?php if($this->is_woocommerce()){ ?>        		
	        	<tr valign="top">	        		
	        		<th>
	        			Sync portfolio with product
	        		</th>
	        		<td>
	        			<?php
	        			$portfolio_tax = $this->get_option_value('adminz_flatsome_portfolio_product_tax');
	        			$taxonomies = get_object_taxonomies( 'product', 'objects' );	        			
	        			if(!empty($taxonomies) and is_array($taxonomies)){
	        				echo "<select name='adminz_flatsome[adminz_flatsome_portfolio_product_tax]'>";
	        				echo "<option value=''>-- Select --</option>";
	        				foreach ($taxonomies as $key => $value) {
	        					$checked = ($portfolio_tax == $key)? "selected" : "";
	        					echo "<option value='".$key."' ".$checked.">".$value->label."</option>";
	        				}
	        				echo "</select>";
        				}
	        			?> Choose your taxonomy
	        			<small>
	        				<ul>
	        					<li>1. Move to trash all portfolio, then restore/ pulish all to general product taxonomy and </li>
	        					<li>2. When you create new portfolio. It will create new term for your custom taxonomy and sync them.</li>
	        					<li>Note: If using bulk edit. You need re-save portfolio again.</li>
	        				</ul>	        				
	        			</small>
	        		</td>
	        	</tr>
	        	<tr valign="top">	        		
	        		<th>
	        			Product list in portfolio
	        		</th>
	        		<td>
	        			<textarea cols="70" rows="1" name="adminz_flatsome[adminz_add_products_after_portfolio_title]"><?php echo $this->get_option_value('adminz_add_products_after_portfolio_title');?></textarea>
	        			</br>
	        			<strong><code>[adminz_flatsome_portfolio_product_list title='' columns = 2 depth = 1 depth_hover= 2 image_width=25 text_align= 'left' style= 'vertical' type='row']</code></strong>
	        			<p>
	        				<input type="checkbox" name="adminz_flatsome[adminz_add_products_after_portfolio]" <?php if($this->get_option_value('adminz_add_products_after_portfolio') == "on") echo "checked"; ?> />
	        				Auto add products after portfolio content
	        			</p>
	        		</td>
	        	</tr>
	        	<tr valign="top">	        		
	        		<th>
	        			Portfolio info box in product page
	        		</th>
	        		<td>
	        			<strong><code>[adminz_flatsome_product_portfolio_info  title= '' show_producs_sync_portfolio= '' title_small= 'Same Portfolio' columns = 2 depth = 1 depth_hover= 2 image_width=25 text_align= 'left' style= 'vertical' type='row']</code></strong>
	        		</td>
	        	</tr>
	        	<?php } ?>
	        	<tr valign="top">	        		
	        		<th>
	        			Portfolio search form
	        		</th>
	        		<td>
	        			<strong><code>[adminz_flatsome_portfolios_form]</code></strong>
	        			<div>Uxbuilder supported</div>
	        		</td>
	        	</tr>
	        	<tr valign="top">	        		
	        		<th>
	        			Portfolio search results pages
	        		</th>
	        		<td>
	        			<strong><code>[adminz_flatsome_portfolios_search_result]</code></strong>Shortcode attributes: 
	        			<div>Uxbuilder supported</div>
	        		</td>
	        	</tr>
	        		        	
	        	
	        </table>
	        <?php submit_button(); ?>
	        <table class="form-table">		
	        	<tr valign="top">
	        		<th><h3>Flatsome Actions hook</h3></th>
	        		<td></td>
	        	</tr>
	        	<tr valign="top">	        		
	        		<th>
	        			List action hooks
	        		</th>
	        		<td>
	        			<p>type <code>[adminz_test]</code> to test</p>	        			
	        		</td>
	        	</tr>
	        	<tr valign="top">
	        			<th>
		        			
		        		</th>	
		        		<td>    
				        	<?php 
				        	$adminz_flatsome_action_hook = $this->get_option_value('adminz_flatsome_action_hook');
				        	foreach (self::$flatsome_actions as $key => $value) {
				        		?>
				        		<div>
				        			<textarea cols="70" rows="1" name="adminz_flatsome[adminz_flatsome_action_hook][<?php echo $value;?>]"><?php echo isset($adminz_flatsome_action_hook[$value]) ? $adminz_flatsome_action_hook[$value] : "";?></textarea><small><?php echo $value; ?></small>
				        		</div>
				        		<?php
				        	}
			        	 	?>
			        	 	<input type="checkbox" name="adminz_flatsome[adminz_flatsome_test_all_hook]" <?php if($this->get_option_value('adminz_flatsome_test_all_hook') == "on") echo "checked"; ?>><small>Test all hook</small>
			        	</td> 
	        	 </tr>
	        	 <tr valign="top">
					<th scope="row">
						Custom action hooks						
					</th>
					<td>
						<p>type <code>[adminz_test]</code> to test</p>		
						<?php $flatsome_hook_data = $this->get_option_value('adminz_flatsome_custom_hook'); ?>
						<textarea style="display: none;" cols="70" rows="10" name="adminz_flatsome[adminz_flatsome_custom_hook]"><?php echo $flatsome_hook_data; ?></textarea> </br>
						<div>
							<textarea cols="70" rows="1" disabled>Shortcode</textarea> 
							<textarea cols="70" rows="1" disabled>Action hook</textarea> 
							<textarea cols="20" rows="1" disabled>Priority</textarea>
							<textarea cols="20" rows="1" disabled>Conditional</textarea>
						</div>
						<div class="adminz_flatsome_custom_hook">
							<?php 
							$flatsome_hook_data = json_decode($flatsome_hook_data);							
							if(!empty($flatsome_hook_data) and is_array($flatsome_hook_data)){
								foreach ($flatsome_hook_data as $key => $value) {
									$value[0] = isset($value[0])? $value[0] : "";
									$value[1] = isset($value[1])? $value[1] : "";
									$value[2] = isset($value[2])? $value[2] : "";
									$value[3] = isset($value[3])? $value[3] : "";
									echo '<div class="item" style="margin-bottom: 5px;">
										<textarea cols="70" rows="1" name="" placeholder="[your shortcode]">'.$value[0].'</textarea>
										<textarea cols="70" rows="1" name="" placeholder="your action hook">'.$value[1].'</textarea>
										<textarea cols="20" rows="1" name="" placeholder="your priority">'.$value[2].'</textarea>
										<textarea cols="20" rows="1" name="" placeholder="your conditional">'.$value[3].'</textarea>
										<button class="button adminz_flatsome_custom_hook_remove" >Remove</button>
									</div>';
								}
							}
							?>							
						</div>
						<button class="button" id="adminz_flatsome_custom_hook_add">Add new</button>
						<script type="text/javascript">
							window.addEventListener('DOMContentLoaded', function() {
								(function($){
									var custom_woo_hooks_item = '<div class="item" style="margin-bottom: 5px;"> <textarea cols="70" rows="1" name="" placeholder="[your shortcode]"></textarea> <textarea cols="70" rows="1" name="" placeholder="your action hook"></textarea> <textarea cols="20" rows="1" name="" placeholder="your priority"></textarea> <textarea cols="20" rows="1" name="" placeholder="your conditional"></textarea> <button class="button adminz_flatsome_custom_hook_remove" >Remove</button> </div>'; $("body").on("click","#adminz_flatsome_custom_hook_add",function(){
									$(".adminz_flatsome_custom_hook").append(custom_woo_hooks_item);
										adminz_flatsome_custom_hook_update();
										return false;
									});
									$("body").on("click",".adminz_flatsome_custom_hook_remove",function(){
										$(this).closest(".item").remove();
										adminz_flatsome_custom_hook_update();
										return false;
									});
									$('body').on('keyup', '.adminz_flatsome_custom_hook .item textarea', function() {
					        			adminz_flatsome_custom_hook_update();					        			
					        		});
									function adminz_flatsome_custom_hook_update(){
										var data_js = $('textarea[name="adminz_flatsome\[adminz_flatsome_custom_hook\]"]').val();

										var alldata = [];
										$('.adminz_flatsome_custom_hook .item').each(function(){
											var itemdata = [];
											var shortcode 	= $(this).find('textarea:nth-child(1)').val();
											var hook 		= $(this).find('textarea:nth-child(2)').val();
											var priority 	= $(this).find('textarea:nth-child(3)').val(); 
											var conditional 	= $(this).find('textarea:nth-child(4)').val(); 
											itemdata = [shortcode,hook,priority,conditional];	
											alldata.push(itemdata);																					
										});
										$('textarea[name="adminz_flatsome\[adminz_flatsome_custom_hook\]"]').val(JSON.stringify(alldata));
									}
								})(jQuery);
							});
						</script>
					</td>
				</tr>
	        </table>
	        <?php submit_button(); ?>
	        <table class="form-table">
	        	<tr valign="top">
	        		<td><h3>Flatsome Css classes cheatsheet</h3></td>
	        		<td>
	        		</td>
	        	</tr>
	        	<?php 
	        	$classcheatsheet = [
	        		'Row'=> [
	        			['align-middle', 'align-top', 'align-bottom', 'align-center', 'align-right', 'align-equal'],
	        			['row-collapse', 'row-small', 'row-large', 'row-reverse', 'row-isotope', 'row-grid', 'row-masonry', 'row-divided'],
	        			['row-box-shadow-1', 'row-box-shadow-2', 'row-box-shadow-3', 'row-box-shadow-4', 'row-box-shadow-5'],
	        			['row-box-shadow-1-hover', 'row-box-shadow-2-hover', 'row-box-shadow-3-hover', 'row-box-shadow-4-hover', 'row-box-shadow-5-hover'],
	        			['row-dashed','row-solid'],
	        			['is-full-height'],
	        			['dark'],
	        			['flex-row','flex-row-start','flex-row-center','flex-row-col','flex-wrap','flex-grow']
	        		],
	        		'Col'=> [
	        			['col-fit', 'col-first', 'col-last', 'col-border', 'col-divided'],
	        			['large-1', 'large-2', 'large-3', '...' ,'large-12'],
	        			['small-1', 'small-2', 'small-3', '...', 'small-12 '],
	        			['medium-1', 'medium-2', 'medium-3', '...', 'medium -12 '],
	        			['pull-right','pull-left'],
	        			['flex-col','flex-left','flex-center','flex-right'],	        			
	        		],
	        		'Size'=> [
	        			['is-xxxlarge','is-xxlarge','is-xlarge','is-larger','is-large','is-small','is-smaller','is-xsmall','is-xxsmall']
	        		],
	        		'Font'=> [
	        			['is-normal','is-bold','is-thin','is-italic','is-uppercase','is-alt-font']
	        		],
	        		'Button'=> [
	        			['button','is-form','is-link','is-outline','is-underline','checkout','alt','disabled']
	        		],
	        		'Margin'=> [
        				['mb', 'mt', 'mr', 'ml'],
        				['mb-0', 'ml-0', 'mr-0', 'mt-0', 'mb-half', 'mt-half', 'mr-half', 'ml-half']
	        		],
	        		'Padding'=> [
	        			['pb', 'pt'],
	        			['pb-half', 'pt-half', 'pb-0', 'pt-0', 'no-margin', 'no-padding']
	        		],
	        		'Text'=> [
	        			['text-shadow','text-shadow-1','text-shadow-2','text-shadow-3','text-shadow-4','text-shadow-5'],
	        			['text-center','text-right','text-left'],
	        			['text-box','text-box-square','text-box-circle'],
	        			['text-bordered-white','text-bordered-primary','text-bordered-dark'],
	        			['text-boarder-top-bottom-white','text-boarder-top-bottom-dark','text-boarder-top-bottom-white','text-boarder-top-bottom-dark']
	        		],
	        		'Border'=>[
	        			['has-border','round','bb','bt','bl','br'],
	        			['is-border','is-dashed','is-dotted'],
	        			['has-border','dashed-border','success-border']
	        		],
	        		'Color'=> [
	        			['primary-color','secondary-color','success-color','alert-color']
	        		],
	        		'Background'=> [
	        			['bg-fill','bg-top'],
	        			['bg-primary-color','bg-secondary-color','bg-success-color','bg-alert-color','is-transparent']
	        		],
	        		'Position'=> [
	        			['relative','absolute','fixed'],
	        			['top','right','left','bottom','v-center','h-center'],
	        			[
	        				'lg-x5','lg-x15','lg-x25','...','lg-x95',
	        				'lg-y5','lg-y15','lg-y25','...','lg-y95',
	        				'lg-x0','lg-x10','lg-x20','...','lg-x100',
	        				'lg-y0','lg-y10','lg-y20','...','lg-y100',
	        				'lg-x50','lg-y50'
	        			],
	        			[
							'md-x5','md-x15','md-x25','...','md-x95',
							'md-y5','md-y15','md-y25','...','md-y95',
							'md-x0','md-x10','md-x20','...','md-x100',
							'md-y0','md-y10','md-y20','...','md-y100',
							'md-x50','md-y50'
						],
	        			[
							'x5','x15','x25','...','x95',
							'y5','y15','y25','...','y95',
							'x0','x10','x20','...','x100',
							'y0','y10','y20','...','y100',
							'x50','y50'
						],
						['z-1', 'z-2', 'z-3', 'z-4', 'z-5', 'z-top', 'z-top-2', 'z-top-3']
	        		],
	        		'Opacity' => [
	        			['op-4','op-5','op-6','op-7','op-8']
	        		],
	        		'Icon'=> [
	        			['icon-lock', 'icon-user-o', 'icon-line', 'icon-chat', 'icon-user', 'icon-shopping-cart', 'icon-tumblr', 'icon-gift', 'icon-phone', 'icon-play', 'icon-menu', 'icon-equalizer', 'icon-shopping-basket', 'icon-shopping-bag', 'icon-google-plus', 'icon-heart-o', 'icon-heart', 'icon-500px', 'icon-vk', 'icon-angle-left', 'icon-angle-right', 'icon-angle-up', 'icon-angle-down', 'icon-twitter', 'icon-envelop', 'icon-tag', 'icon-star', 'icon-star-o', 'icon-facebook', 'icon-feed', 'icon-checkmark', 'icon-plus', 'icon-instagram', 'icon-tiktok', 'icon-pinterest', 'icon-search', 'icon-skype', 'icon-dribbble', 'icon-certificate', 'icon-expand', 'icon-linkedin', 'icon-map-pin-fill', 'icon-pen-alt-fill', 'icon-youtube', 'icon-flickr', 'icon-clock', 'icon-snapchat', 'icon-whatsapp', 'icon-telegram', 'icon-twitch', 'icon-discord']
	        		],
	        		'Custom'=> [
	        			['--secondary-color', '--success-color', '--alert-color'],
	        			['text-primary', 'text-secondary', 'text-success', 'alert-color'],
	        			['row-nopaddingbottom','nopaddingbottom'],
	        			['sliderbot'],
	        			['bgr-size-auto']
	        		]
	        	];
	        	foreach ($classcheatsheet as $key => $value) {
	        		?>
	        		<tr valign="top">
		        		<th><?php echo $key; ?></th>
		        		<td>
		        			<?php foreach ($value as $classes) {
		        				echo "<p>";
		        				foreach ($classes as $class) {
		        					echo " <code>.$class</code>";
		        				}
		        				echo "</p>";
		        			} ?>
		        		</td>
		        	</tr>
	        		<?php
	        	}
	        	?>
	        	
	        		        	
	        </table>
        </form>
        <?php
		return ob_get_clean();
	}
	static function get_arr_tax($post_type = 'featured_item'){
		$taxonomies = get_object_taxonomies( $post_type, 'objects' );
	    $tax_arr = [];    
	    $tax_arr['search'] = "Type to search";
	    if(!empty($taxonomies) and is_array($taxonomies)){
	        foreach ($taxonomies as $key => $value) {
	            $tax_arr[$key] = $value->label;
	        }
	    }
	    return $tax_arr;
	}
	static function get_arr_meta_key($post_type = 'featured_item'){
		$meta_keys = self::adminz_get_all_meta_keys($post_type);
	    $key_arr = [];
	    if(!empty($meta_keys) and is_array($meta_keys)){
	        foreach ($meta_keys as $value) {
	            if($value){
	                $key_arr[$value] = $value;
	            }            
	        }
	    }
	    return $key_arr;
	}
	
	static function adminz_get_all_meta_keys($post_type = 'post', $exclude_empty = true, $exclude_hidden = true){
	    global $wpdb;
	    $query = "
	        SELECT DISTINCT($wpdb->postmeta.meta_key) 
	        FROM $wpdb->posts 
	        LEFT JOIN $wpdb->postmeta 
	        ON $wpdb->posts.ID = $wpdb->postmeta.post_id 
	        WHERE $wpdb->posts.post_type = '%s'
	    ";
	    if($exclude_empty) 
	        $query .= " AND $wpdb->postmeta.meta_key != ''";
	    if($exclude_hidden) 
	        $query .= " AND $wpdb->postmeta.meta_key NOT RegExp '(^[_0-9].+$)' ";        
	    $meta_keys = $wpdb->get_col($wpdb->prepare($query, $post_type));

	    return $meta_keys;
	}
	function get_packages(){
		return [
			[
				'name'=>'Choose style',
				'slug'=>'',
				'url' => ''
			],
			[
				'name'=>'Round',
				'slug'=>'pack1',
				'url' => plugin_dir_url(ADMINZ_BASENAME).'assets/css/pack/1.css'
			],
			[
				'name'=>'Grid & border',
				'slug'=>'pack2',
				'url' => plugin_dir_url(ADMINZ_BASENAME).'assets/css/pack/2.css'
			]
			
		];
	}
	
	function register_option_setting() {		
		register_setting( $this->options_group, 'adminz_flatsome' );
	    
    	ADMINZ_Helper_Language::register_pll_string('adminz_flatsome[adminz_add_products_after_portfolio]',self::$slug,false );
 		ADMINZ_Helper_Language::register_pll_string('adminz_flatsome[adminz_add_products_after_portfolio_title]',self::$slug,false );

 		ADMINZ_Helper_Language::register_pll_string('adminz_flatsome[adminz_flatsome_portfolio_product_tax]',self::$slug,false);
	    ADMINZ_Helper_Language::register_pll_string('adminz_flatsome[adminz_flatsome_portfolio_name]',self::$slug,false);
	    ADMINZ_Helper_Language::register_pll_string('adminz_flatsome[adminz_flatsome_portfolio_category]',self::$slug,false);
	    ADMINZ_Helper_Language::register_pll_string('adminz_flatsome[adminz_flatsome_portfolio_tag]',self::$slug,false);




	}	
}