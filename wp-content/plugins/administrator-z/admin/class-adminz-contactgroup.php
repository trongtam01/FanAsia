<?php 
namespace Adminz\Admin;
use Adminz\Admin\Adminz as Adminz;
use Adminz\Helper\ADMINZ_Helper_Language;

class ADMINZ_ContactGroup extends Adminz {
	public $options_group = "adminz_contactgroup";
	public $title = "Contact Group";
	static $slug = "adminz_contactgroup";	
	public $locations = [];	
	static $options;
	function __construct() {		
		$this->title = $this->get_icon_html('call').$this->title;
		$this::$options = get_option('adminz_contactgroup', []);
		
		add_filter( 'adminz_setting_tab', [$this,'register_tab']);
		add_action(	'admin_init', [$this,'register_option_setting'] );
		add_action( 'init', array( $this, 'init' ) );		
		add_filter( 'nav_menu_item_title',[$this,'add_icon_to_nav'],1,4);
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
 	function init(){
 		if(is_admin()) return;
 		$menuids = $this->get_option_value('nav_asigned'); 		 		
 		$styles = $this->get_styles();
 		if(!empty($menuids) and is_array($menuids)){
 			foreach ($menuids as $key=>$menuid) { 				
 				//check menu assigned
 				if($menuid){
 					$style = $styles[$key];
 					$name = sanitize_title(self::$slug."_".$style['title']); 					
 					$css = $style['css'];
 					$js = $style['js'];
 					add_action('wp_enqueue_scripts', function() use ($css,$js,$name,$key ) {

 						wp_enqueue_style( $name, $css[0],[],false,$css[1] );
 						// js
 						if(is_array($js) and !empty($js)){
							foreach ($js as $ijs => $jsurl) {
								// check wp library script
								if($jsurl == wp_http_validate_url($jsurl)){									
									wp_enqueue_script($name, $jsurl, array('jquery'),null, true);
								}else{
									wp_enqueue_script($jsurl);
								}
	 						}
 						}
 					});
 					// call template
 					add_action('wp_footer', function() use ($menuid,$style) { 		 						
 						echo call_user_func([$this,$style['callback']],$menuid);
 					}); 					
 				}
 			}
 		}
 	}
	function callback_style1($menuid){
		if(is_admin() and is_blog_admin()) die;
		$items = $this->get_menu_items($menuid);
		if(!$items) return;
		ob_start();
		$value_animation =  $this->get_option_value("settings",'adminz_ctg_animation');
		$adminz_ctg_animation = $value_animation ? 'data-animate="'.$value_animation.'"' : '';
		echo '<div class="contactgroup_style1" '.$adminz_ctg_animation.'>';
		if(!empty($items)){
			foreach ($items as $item) {				
				$style = $item->xfn? ' background-color: #'.$item->xfn.';' : "";
		    	$icon = $this->get_icon($item->post_excerpt);
		    	$item->classes[] = 'item';
	    		$item->classes[] = $item->post_excerpt;
				echo '<a 
				href="'.$item->url.'"
				class="'.$this->get_item_class($item).'" 
				target="'.$item->target.'"		        
		        style="color: white;',$style,'"		        
		        >
		        '.$icon.'	
		        </a>';
			}
		}
		echo '</div>';
		return str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', ob_get_clean());		
	}
	function callback_style2($menuid){				
		if(is_admin() and is_blog_admin()) die;			
		$items = $this->get_menu_items($menuid);
		if(!$items) return;
		
		ob_start();
		if(!empty($items)){
			?>
			<div class="contact-group contactgroup_style2">
				<?php 
				$distinct = array();
				$itemcount1 = array(
					'href' => 'javascript:void(0)',
					'target' => "",										
					'title'=> ADMINZ_Helper_Language::get_pll_string('adminz_contactgroup[settings][contactgroup_title]','Quick contact')
				);		
				if(count($items) ==1){
					$itemcount1['href'] = $items[0]->url;
					$itemcount1['target'] = $items[0]->target;
					$itemcount1['title'] = $items[0]->title;
				}

				foreach ($items as $key => $item) {
					$distinct[] = $item->post_excerpt;
				}			
				$distinct = array_unique($distinct);
			 	?>
			    <div class="button-contact icon-loop-<?php echo count($distinct)?> item-count-<?php echo count($items); ?>">
			        <a href="<?php echo $itemcount1['href']; ?>" target="<?php echo $itemcount1['target']; ?>" class="icon-box icon-open">
			        	<span>
				            <?php 
				            foreach ($distinct as $item) {				            	
				            	$icon = $this->get_icon($item);
			            			echo '<span class="icon-box">
			            			'.$icon.'
			            			</span>';
				            }
				            ?>
			        	</span>
			    	</a>
			        <a href="javascript:void(0)" class="icon-box icon-close">
			        	<?php 
			        	echo  $this->get_icon('close');
			        	 ?>	            
			        </a>
			        <span class="button-over icon-box"></span>
			        <div class="text-box text-contact"><?php echo $itemcount1['title']; ?></div>
			    </div>
			    <?php if(count($items)>1){ ?>
			    <ul class="button-list">
			        <?php
			        foreach ($items as $key=> $item) {
			        	$style = $item->xfn? ' background-color: #'.$item->xfn.'; border-color: #'.$item->xfn.';' : "";			        	
			        	$icon = $this->get_icon($item->post_excerpt);
			        	echo '<li class="',$item->post_excerpt,' button-', $key,' ',implode(' ', $item->classes),'">
			                	<a href="', $item->url,'" target="',$item->target,'">
			                		<span 
			                		class="icon-box icon-', $key,'" 
			                		style="color: white;',$style,'"
			                		>
			                			'.$icon.'
			            			</span>';
			            		if ($this->get_item_title($item->title)){
						        	echo '<span class="text-box text-', $key,'" style="'.$style.'">'.$this->get_item_title($item->title).'</span>';
						        }
			                echo '</a>
			                </li>';
			        }
			        ?>
			    </ul>
				<?php }; ?>
				<style type="text/css">
					<?php $defaultcolor = $this->get_option_value('settings','contactgroup_color_code');?>
					.contact-group span,
					.contact-group .text-contact,
					.contact-group .button-over:after,
					.contact-group .button-over:before,
					.contact-group .button-contact .icon-close,
					.contact-group .button-contact .icon-open{
						background-color: <?php echo $defaultcolor; ?>;
					}
					.contact-group .text-box{
						border-color:  <?php echo $defaultcolor; ?>;
					}
					.contact-group .text-contact{
						border-color:  <?php echo $defaultcolor; ?>;
					}
					<?php if($this->get_option_value('settings','adminz_hide_title_mobile') == 'on'){ ?>
					.contactgroup_style2 .text-contact {
					  	display: none;
					}
					<?php } ?>
				</style>
			</div>
			<script type="text/javascript">
				window.addEventListener('DOMContentLoaded', function() {
					(function($){
						$(document).on("click",'.button-contact',function(){
							if(!$(this).hasClass('item-count-1')){
								$(this).closest(".contact-group").toggleClass('extend');
							}
						});
					})(jQuery);
				});
			</script>
			<?php
		}

		return apply_filters('adminz_output_debug',ob_get_clean());
	}
	function callback_style3($menuid){
		// get only first
		if(is_admin() and is_blog_admin()) die;
		$items = $this->get_menu_items($menuid);
		if(!$items) return;
		if(empty($items)) return;
		$item = $items[0];
		$color = $item->xfn? $item->xfn : '00aff2';
		$style = ' background-color: #'.$color.'; border-color: #'.$color.';';
		ob_start();
		if(!empty($items)){
			$value_animation =  $this->get_option_value("settings",'adminz_ctg_animation');
			$adminz_ctg_animation = $value_animation ? 'data-animate="'.$value_animation.'"' : '';
			?>			
			<div class="quick-alo-phone" <?php echo $adminz_ctg_animation; ?>>
				<?php if($this->get_item_title($item->title)){ ?>
					<div class="phone"><a href="<?php echo $item->url; ?>" class="number-phone"><?php echo $this->get_item_title($item->title); ?></a></div>
				<?php }else{
					?>
					<div style="margin-bottom: 50px;"></div>
					<?php
				} ?>
			  	<a 
			  	href="<?php echo $item->url; ?>"
			  	class="<?php echo implode(' ', $item->classes); ?>" 
				target="<?php echo $item->target; ?>"		        
		        style="color: white; <?php echo $style; ?>"		        
			  	>
				  	<div class="quick-alo-ph-circle"></div>
				  	<div class="quick-alo-ph-circle-fill"></div>
				  	<div class="quick-alo-ph-img-circle">
				  		<?php 
				  		echo $this->get_icon($item->attr_title);
				  		?>
				  	</div>
				</a>
			</div>	
			<?php if($this->get_option_value('settings','adminz_hide_title_mobile') == 'on'){ ?>
				<style type="text/css">				
					.quick-alo-phone .phone {
					  display: none;
					}
				</style>		
			<?php } ?>
			<?php
		}
		return apply_filters('adminz_output_debug',ob_get_clean());
	}
	function callback_style4($menuid){
		if(is_admin() and is_blog_admin()) die;
		$items = $this->get_menu_items($menuid);
		if(!$items) return;
		$value_animation =  $this->get_option_value("settings",'adminz_ctg_animation');
		$adminz_ctg_animation = $value_animation ? 'data-animate="'.$value_animation.'"' : '';
		ob_start();
		if(!empty($items)){
		?>
		<div class="admz_ctg4">
			<div class="inner">
			<?php 		
				$list_shortcode = [];

				foreach ($items as $key => $item) {					
					$is_html = (strpos($item->post_excerpt,"<") !== false);
					$is_shortcode = (strpos($item->post_excerpt,"[") !== false);

					if($is_html or $is_shortcode){
						$style = $item->xfn? 'background-color: #'.$item->xfn.';' : "background-color: white;";
						echo '<div id="admz_ctg4_'.$key.'" class="top hidden '.$this->get_item_class($item).'" style="'.$style.'" '.$adminz_ctg_animation.'>';
						echo do_shortcode($item->post_excerpt);
						echo '<span class="x">×</span>';
						echo '</div>';
						$list_shortcode[] = $key;
					}else{
						$style = "color: white; ";
						$style .= $item->xfn? 'background-color: #'.$item->xfn.';' : "";
						$icon = $this->get_icon($item->post_excerpt,['class'=>'main_icon']);
						$href = $item->url;
						if(in_array($key-1,$list_shortcode)){
							$href = "javascript:void(0);";
						}
						echo '<a 
						id="admz_ctg4_'.$key.'"
			    		href="'.$href.'"
						class="bottom '.$this->get_item_class($item).'" 
						target="'.$item->target.'"
				        style="',$style,'"
				        '.$adminz_ctg_animation.'
				        > '.$icon;
				        if ($this->get_item_title($item->title)){
				        	echo '<span class="">'.$this->get_item_title($item->title).'</span>';
				        }
				        echo '</a>';						
					}					
				} 				
				?>
			</div>
		</div>
		<?php if($this->get_option_value('settings','adminz_hide_title_mobile') == 'on'){  ?>
			<style type="text/css">
				.admz_ctg4 .inner .item span{
					display: none;
				}
				@media (max-width: 767px){
					.admz_ctg4 .item{
						padding-left: 0px;
					}
				}
				@media (max-width: 549px){
					.hide-for-small {
				    	display: none !important;
					}
				}
			</style>
		<?php } ?>


		<?php if($this->is_flatsome()){ ?>
		<script type="text/javascript">
			window.addEventListener('DOMContentLoaded', function() {
				(function($){
					if(! /Android|webOS|iPhone|iPad|Mac|Macintosh|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
						$(".admz_ctg4 .top.show_desktop").each(function(){
							var cookieid = $(this).attr("id");						
							if(cookie(cookieid) === null || cookie(cookieid) == 1){
								$(this).removeClass('hidden');
							}
						});
					};
					$(document).on("click", ".admz_ctg4 .bottom",function(){						
						var top = $(this).prev();						
						admz_cookie_close(top);
					});

					$(document).on("click", ".admz_ctg4 .x",function(){
						var top = $(this).closest('.top');
						admz_cookie_close(top);
					});
					function admz_cookie_close(top){
						var cookieid = top.attr("id");
						if(top.hasClass('top')){
							top.toggleClass('hidden');
							if(top.hasClass('hidden')){							
								cookie(cookieid,0,365);
							}else{
								cookie(cookieid,1,365);
							}							
							return false;
						}
					}
				})(jQuery);
			});				
		</script>
		<?php } ?>


		<?php
		}
		return apply_filters('adminz_output_debug',ob_get_clean());		
	}	
	function callback_style5($menuid){
		if(is_admin() and is_blog_admin()) die;
		$items = $this->get_menu_items($menuid);
		if(!$items) return;
		$value_animation =  $this->get_option_value("settings",'adminz_ctg_animation');
		$adminz_ctg_animation = $value_animation ? 'data-animate="'.$value_animation.'"' : '';
		ob_start();
		?>
		<div class="admz_ctg5" style="display: none;">
		<?php
		if(!empty($items)){
			foreach ($items as $key => $item) {
				$style = "";
				$style .= $item->xfn? 'background-color: #'.$item->xfn.';' : "";
				$icon = $this->get_icon($item->post_excerpt,['class'=>'main_icon']);
				echo '<a 
				id="admz_ctg4_'.$key.'"
	    		href="'.$item->url.'"
				class="bottom '.$this->get_item_class($item).'" 
				target="'.$item->target.'"
		        style="',$style,'"
		        '.$adminz_ctg_animation.'
		        > '.$icon;
		        if ($this->get_item_title($item->title)){
		        	echo '<span class="">'.$this->get_item_title($item->title).'</span>';
		        }
		        echo '</a>';
			}
		}
		?>
		</div>
		<style type="text/css">
			.admz_ctg5 .item {
				background-color:  <?php echo $this->get_option_value('settings','contactgroup_color_code'); ?>;
			}
			<?php if($this->get_option_value('settings','adminz_hide_title_mobile') == 'on'){  ?>			
				@media (max-width: 768px){
					.admz_ctg5 a span{display: none;}
				}			
			<?php } ?>
			<?php if($this->get_option_value('settings','fixed_bottom_mobile_hide_other') == 'on'){  ?>			
				@media (max-width: 768px){
					.contactgroup_style1,
					.contactgroup_style2,
					.quick-alo-phone,
					.admz_ctg4{display: none;}
				}			
			<?php } ?>
		</style>		
		<?php
		return apply_filters('adminz_output_debug',ob_get_clean());
	}
	function callback_style6($menuid){
		if(is_admin() and is_blog_admin()) die;
		$items = $this->get_menu_items($menuid);
		if(!$items) return;
		$value_animation =  $this->get_option_value("settings",'adminz_ctg_animation');
		$adminz_ctg_animation = $value_animation ? 'data-animate="'.$value_animation.'"' : '';
		ob_start();
		if(!empty($items)){
		?>
		<div class="admz_ctg6">
			<div class="inner">
			<?php 		
				$list_shortcode = [];

				foreach ($items as $key => $item) {
					$is_html = (strpos($item->post_excerpt,"<") !== false);
					$is_shortcode = (strpos($item->post_excerpt,"[") !== false);

					$style = "color: white;";
					$style .= $item->xfn? 'background-color: #'.$item->xfn.';' : "";
					$icon = $this->get_icon($item->post_excerpt,['class'=>'main_icon']);
					$href = $item->url;
					if(in_array($key-1,$list_shortcode)){
						$href = "javascript:void(0);";
					}
					echo '<a 
					id="admz_ctg6_'.$key.'"
		    		href="'.$href.'"
					class="bottom '.$this->get_item_class($item).'" 
					target="'.$item->target.'"
			        style="',$style,'"
			        '.$adminz_ctg_animation.'
			        > '.$icon;
			        if ($this->get_item_title($item->title)){
			        	echo '<span class="" style="opacity: 0; ">'.$this->get_item_title($item->title).'</span>';
			        }
			        echo '</a>';					
				} 				
				?>
			</div>
		</div>
		<script type="text/javascript">
			window.addEventListener('DOMContentLoaded', function() {
				(function($){					
					$( ".admz_ctg6 .inner .item" ).hover(
					  function() {
					    $(".admz_ctg6 .inner .item").removeClass('active');
					    $(this).addClass('active');
					  }, function() {
					    
					  }
					);
				})(jQuery);
			});				
		</script>
		<?php if($this->get_option_value('settings','adminz_hide_title_mobile') == 'on'){  ?>
			<style type="text/css">
				.admz_ctg6 .inner .item span{
					display: none;
				}
				@media (max-width: 767px){
					.admz_ctg6 .item{
						padding-left: 0px;
					}
				}
				@media (max-width: 549px){
					.hide-for-small {
				    	display: none !important;
					}
				}
			</style>
		<?php } 
		}
		return apply_filters('adminz_output_debug',ob_get_clean());		
	}	
	function get_item_class($item){		
		$return = $item->classes;
		if(!is_array($return)){
			$return = explode(" ", $return);
		}
		$return[] = 'item';
		$excerpt = str_replace(' ',"-",$item->post_excerpt);
		$excerpt = strip_tags($item->post_excerpt);
		$excerpt = preg_replace('/[^A-Za-z0-9\-]/', '', $excerpt);
		$return[] = $excerpt;		
		return implode(' ', array_filter($return));
	}
	function get_item_title($title){
		if($title == "0") return ; 
		return $title;
	}
 	function get_styles(){
 		$styles['callback_style5'] = array(			
			'callback' => 'callback_style5',
			'title'=>'Fixed bottom mobile',
			'css'=> [plugin_dir_url(ADMINZ_BASENAME).'assets/css/style5.css','(max-width: 768px)'],
			'js'=> [],			
			'description'=>''
		);
 		$styles['callback_style1']= array( 			
 			'callback' => 'callback_style1',
			'title'=>'Fixed right',
			'css'=> [plugin_dir_url(ADMINZ_BASENAME).'assets/css/style1.css','all'],
			'js'=> [],			
			'description'=>''
		);	
		$styles['callback_style2']= array( 			
 			'callback' => 'callback_style2',
			'title'=>'Left Expanding Group',
			'css'=> [plugin_dir_url(ADMINZ_BASENAME).'assets/css/style2.css','all'],
			'js'=> [plugin_dir_url(ADMINZ_BASENAME).'assets/js/style2.js'],			
			'description'=>''
		);	
		$styles['callback_style3']= array(			
			'callback' => 'callback_style3',
			'title'=>'Left single',
			'css'=> [plugin_dir_url(ADMINZ_BASENAME).'assets/css/style3.css','all'],
			'js'=> [/*'jquery-ui-core',*/ ],			
			'description'=>'Only first item menu to show'
		);
		$styles['callback_style4']= array(			
			'callback' => 'callback_style4',
			'title'=>'Left Expand',
			'css'=> [plugin_dir_url(ADMINZ_BASENAME).'assets/css/style4.css','all'],
			'js'=> [],			
			'description'=>'Allow shortcode into title attribute. To auto show, put <code>show_desktop</code> into classes'
		);
		$styles['callback_style6']= array(			
			'callback' => 'callback_style6',
			'title'=>'Left Expand Horizontal',
			'css'=> [plugin_dir_url(ADMINZ_BASENAME).'assets/css/style6.css','all'],
			'js'=> [],			
			'description'=>'Round button vertical and tooltip, put <code>active</code> into classes to show tooltip'
		);		
 		return apply_filters( 'nav_asigned', $styles);
 	}
 	function get_style_data($style_value){
 		$styles = $this->get_styles();
 		if(!empty($styles)){
 			foreach ($styles as $key => $style) {
	 			if(($style['value']) == $style_value){
	 				return $style;
				}
	 		}
 		} 	
 		return;	
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
	        		<th><h3>Assign menu</h3></th>
	        		<td></td>
	        	</tr>
	        	<?php 	        		
	        		$optionstyle = $this->get_option_value('nav_asigned');
	    			$styles = $this->get_styles();
	    			$menus = wp_get_nav_menus();
	    			$contactgroup_customnav =  json_decode($this->get_option_value('settings','custom_nav',''));
	    			if(!empty($contactgroup_customnav) and is_array($contactgroup_customnav)){
	    				foreach ($contactgroup_customnav as $key => $value) {
	    					$menus[] = [
	    						'term_id' => "adminz_".str_replace(" ","",$value[0]),
	    						'name' => "Custom - ".$value[0]
	    					];
	    				}
	    			}	    			
	    			foreach ($styles as $key => $value) {	    					    				
	    				?>
	    				<tr valign="top">
        					<th scope="row"><?php echo $value['title']; ?></th>
        					<td>
        						<select name="adminz_contactgroup[nav_asigned][<?php echo $key;?>]">
        							<option value="">- Not assigned -</option>
        							<?php
        							if (!empty($menus)){
    									foreach ($menus as $key2 => $menu) {
    										$menu = (array) $menu;
    										$selected = "";
    										if(isset($optionstyle[$key]) and $optionstyle[$key] == $menu['term_id']){
    											$selected = "selected";
    										}	    										
    										echo '<option ',$selected,' value="'.$menu['term_id'].'">',$menu['name'],'</option>';
    									}
        							}
        							?>        							
        						</select>
        						<span>
        							<?php echo $value['description']; ?>
        						</span>
        					</td>
        				</tr>
	    				<?php
	    			}
	        	?>
        	</table>
        	<div class="notice">
            <h4>How to add icon</h4> 
            <p>Choose icon: Type name icon into <code>Menu item</code> -> <code>Title attribute</code></p>
            <p>Icon code from <code>Administrator Z</code> -> <code>icon</code></p>
            <p>Background: Type color code into <code>Menu item</code> -> <code>XFN</code></p>
            <p>Remove contact title: Type "0" into Navigation Label</p>
        </div>  
        	<?php submit_button(); ?>
        	<table class="form-table">
	        	<tr valign="top">
	        		<th><h3>Menu contact creator </h3></th>
	        		<td>
	        			<em>Query too many menu can slow down the website. Use below function instead. </em>
	        		</td>
	        	</tr>
	        	<tr valign="top">
	        		<th>Menus</th>
	        		<td>
	        			<div class="contact_menus">
	        				<?php $contactgroup_customnav =  $this->get_option_value('settings','custom_nav','');?>
	        				<textarea name="adminz_contactgroup[settings][custom_nav]" style="display: none;"><?php echo $contactgroup_customnav ; ?></textarea>
		        			<div class="contact_menu_wrapper">
		        				<?php 
		        				$contactgroup_customnav = json_decode($contactgroup_customnav);
		        				if(!empty($contactgroup_customnav) and is_array($contactgroup_customnav)){
		        					foreach ($contactgroup_customnav as $key => $custom_nav) {
		        						?>
		        						<div class="contact_menu_item">
		        							<div>
		        								<p><strong>MENU NAME</strong></p>
		        								<input type="text" name="menu_name" value="<?php echo isset($custom_nav[0]) ? $custom_nav[0] : "" ;?>">
			        							<p>
			        								<button class="button remove_menu">Remove menu</button>
			        							</p>
		        							</div>
		        							<div>
		        								<p><strong>MENU ITEMS</strong></p>
		        								<?php 
		        								if(!empty($custom_nav[1]) and is_array($custom_nav[1])){
		        									?>
		        									<div class="menu_item_list">
		        									<?php
		        									foreach ($custom_nav[1] as $key => $value) {
		        										?>
							        					<div class="menu_item_info">
								        					<input value="<?php echo isset($value[0])? $value[0]: ""; ?>" type="text" name="url" placeholder="URL">

								        					<input value="<?php echo isset($value[1])? $value[1]: ""; ?>" type="text" name="title" placeholder="<?php echo __("Navigation Label"); ?>">

								        					<input value="<?php echo isset($value[2])? $value[2]: ""; ?>" type="text" name="post_excerpt" placeholder="<?php echo __("Title Attribute"); ?>">

								        					<select name="target">
								        						<option value="">Default link</option>
								        						<option value="_blank" <?php if($value[3] == '_blank') echo "selected " ?>><?php echo __("Open link in a new tab"); ?></option>
								        					</select>

								        					<input value="<?php echo isset($value[4])? $value[4]: ""; ?>" type="text" name="classes" placeholder="<?php echo __("CSS Classes"); ?>">

								        					<input value="<?php echo isset($value[5])? $value[5]: ""; ?>" type="text" name="xfn" placeholder="<?php echo __("Link Relationship (XFN)"); ?>">

								        					<input value="<?php echo isset($value[6])? $value[6]: ""; ?>" type="text" name="description" placeholder="<?php echo __("Description"); ?>">

								        					<button class="button remove_menu_item">Remove item</button>
								        					<button class="button up">Move Up</button>

							        					</div>
		        										<?php
		        									}
		        									?>
		        									</div>
		        									<button class="button add_new_menu_item">Add new item</button>
		        									<?php
		        								}
		        								?>
		        							</div>
		        						</div>
		        						<?php
		        					}
		        				}		        				
	        				 	?>
		        			</div>
		        			<p>
		        				<button class="button add_new_menu">Add new menu</button>
		        			</p>
	        			</div>
	        		</td>
	        	</tr>
 			</table>
 			<?php submit_button(); ?>
        	<table class="form-table">
	        	<tr valign="top">
	        		<th><h3>Config</h3></th>
	        		<td>
	        			
	        		</td>
	        	</tr>
	        	<tr valign="top">
	        		<th>Group title</th>
	        		<td>
	        			<p>
	        			<input type="text" name="adminz_contactgroup[settings][contactgroup_title]" value="<?php echo $this->get_option_value('settings','contactgroup_title','Quick contact');?>"> <code>Default Title </code></p>
	        			<p>
	        			<input type="text" name="adminz_contactgroup[settings][contactgroup_color_code]" value="<?php echo $this->get_option_value('settings','contactgroup_color_code','#1296d5');?>"> <code>Default color code</code> <small>Included # to code or leaver css color name is ok</small></p>
	        		</td>
	        	</tr>
	        	<?php 	        	
	        	if($this->is_flatsome()){ ?>
        		<tr valign="top">
	        		<th>Animation</th>
	        		<td>
	        			<?php 
	        			$value_animation =  $this->get_option_value("settings",'adminz_ctg_animation');
						$adminz_ctg_animation = $value_animation ? 'data-animate="'.$value_animation.'"' : '';
	        			?>
	        			<select name="adminz_contactgroup[settings][adminz_ctg_animation]">
	        				<option <?php if($adminz_ctg_animation == "") echo "selected"; ?> value="">None</option>
	        				<option <?php if($adminz_ctg_animation == "fadeInLeft") echo "selected"; ?> value="fadeInLeft">Fade In Left</option>
							<option <?php if($adminz_ctg_animation == "fadeInRight") echo "selected"; ?> value="fadeInRight">Fade In Right</option>
							<option <?php if($adminz_ctg_animation == "fadeInUp") echo "selected"; ?> value="fadeInUp">Fade In Up</option>
							<option <?php if($adminz_ctg_animation == "fadeInDown") echo "selected"; ?> value="fadeInDown">Fade In Down</option>
							<option <?php if($adminz_ctg_animation == "bounceIn") echo "selected"; ?> value="bounceIn">Bounce In</option>
							<option <?php if($adminz_ctg_animation == "bounceInUp") echo "selected"; ?> value="bounceInUp">Bounce In Up</option>
							<option <?php if($adminz_ctg_animation == "bounceInDown") echo "selected"; ?> value="bounceInDown">Bounce In Down</option>
							<option <?php if($adminz_ctg_animation == "bounceInLeft") echo "selected"; ?> value="bounceInLeft">Bounce In Left</option>
							<option <?php if($adminz_ctg_animation == "bounceInRight") echo "selected"; ?> value="bounceInRight">Bounce In Right</option>
							<option <?php if($adminz_ctg_animation == "blurIn") echo "selected"; ?> value="blurIn">Blur In</option>
							<option <?php if($adminz_ctg_animation == "flipInX") echo "selected"; ?> value="flipInX">Flip In X</option>
							<option <?php if($adminz_ctg_animation == "flipInY") echo "selected"; ?> value="flipInY">Flip In Y</option>
	        			</select>
	        		</td>
	        	</tr>
	        	<tr valign="top">
	        		<th>Hide menu item title on mobile</th>
	        		<td>
	        			<?php 
	        			$checked = "";
	        			if($this->check_option('settings','adminz_hide_title_mobile',"on")){
	        				$checked = "checked";
	        			}
	        			?>
	        			<input type="checkbox" name="adminz_contactgroup[settings][adminz_hide_title_mobile]" <?php echo $checked; ?>>
	        		</td>
	        	</tr>
	        	<tr valign="top">
	        		<th>Hide other if style fixed bottom mobile assigned</th>
	        		<td>
	        			<?php 	        			
	        			$checked = "";
	        			if($this->check_option('settings','fixed_bottom_mobile_hide_other',"on")){
	        				$checked = "checked";
	        			}
	        			?>
	        			<input type="checkbox" name="adminz_contactgroup[settings][fixed_bottom_mobile_hide_other]" <?php echo $checked; ?>>
	        		</td>
	        	</tr>
	        	<?php } ?>
        	</table>
        	<?php submit_button(); ?>        	
 			<script type="text/javascript">
 				window.addEventListener('DOMContentLoaded', function() {
					(function($){
						var contact_group_custom_nav_html = '<div class="contact_menu_item"> <div> <p><strong>MENU NAME</strong></p> <input type="text" name="menu_name"> <p><button class="button remove_menu">Remove menu</button></p> </div> <div> <p><strong>MENU ITEMS</strong></p> <div class="menu_item_list"> </div> <button class="button add_new_menu_item">Add new item</button> </div> </div>'; 

						var contact_group_custom_nav_item_html = '<div class="menu_item_info"> <input value="" type="text" name="url" placeholder="URL"> <input value="" type="text" name="title" placeholder="<?php echo __("Navigation Label"); ?>"> <input value="" type="text" name="post_excerpt" placeholder="<?php echo __("Title Attribute"); ?>"> <select name="target"> <option value="">Default link</option> <option value="_blank"><?php echo __("Open link in a new tab"); ?></option> </select> <input value="" type="text" name="classes" placeholder="<?php echo __("CSS Classes"); ?>"> <input value="" type="text" name="xfn" placeholder="<?php echo __("Link Relationship (XFN)"); ?>"> <input value="" type="text" name="description" placeholder="<?php echo __("Description"); ?>"> <button class="button remove_menu_item">Remove item</button> <button class="button up">Move Up</button> </div>'; 


						$("body").on("click",".contact_menus .add_new_menu",function(){
							$(this).closest("div").find(".contact_menu_wrapper").append(contact_group_custom_nav_html);
							adminz_contactgroup_custom_nav_update();
							return false;
						});	
						$("body").on("click",".contact_menus .remove_menu",function(){
							$(this).closest(".contact_menu_item").remove();
							adminz_contactgroup_custom_nav_update();
							return false;
						});	
						$("body").on("click",".contact_menus .add_new_menu_item",function(){
							$(this).prev(".menu_item_list").append(contact_group_custom_nav_item_html);
							adminz_contactgroup_custom_nav_update();
							return false;
						});						
						$("body").on("click",".contact_menus .remove_menu_item",function(){
							$(this).closest(".menu_item_info").remove();
							adminz_contactgroup_custom_nav_update();
							return false;
						});
						$("body").on("click",".contact_menus .button.up",function(){
							var current = $(this).closest('.menu_item_info');
							console.log(current);
							current.prev().insertAfter(current);
							adminz_contactgroup_custom_nav_update();
							return false;
						});
						$('body').on('keyup', '.contact_menus input', function() {
		        			adminz_contactgroup_custom_nav_update();					        			
		        		});
		        		$('body').on('change', '.contact_menus select', function() {
		        			adminz_contactgroup_custom_nav_update();					        			
		        		});

						function adminz_contactgroup_custom_nav_update(){
							var alldata = [];
							$('.contact_menu_wrapper .contact_menu_item').each(function(){
								var menu_data = [];
								var menu_item_data = [];
								var menu_name = $(this).find('input[name="menu_name"]').val();
								$(this).find(".menu_item_info").each(function(){
									var url					= $(this).find("input[name='url']").val();
									var title				= $(this).find("input[name='title']").val();
									var post_excerpt		= $(this).find("input[name='post_excerpt']").val();
									var target				= $(this).find("select[name='target']").val();
									var classes				= $(this).find("input[name='classes']").val();
									var xfn					= $(this).find("input[name='xfn']").val();
									var description			= $(this).find("input[name='description']").val();
									menu_item_data.push([url, title, post_excerpt, target, classes, xfn, description]); });

								menu_data = [menu_name,menu_item_data];
								alldata.push(menu_data);
							});
							
							$('textarea[name="adminz_contactgroup\[settings\]\[custom_nav\]"]').val(JSON.stringify(alldata));
						}
					})(jQuery);
				});
 			</script>
 			<style type="text/css">
 				@media (min-width:  783px){
	 				.contact_menus .contact_menu_item{
	 					display: flex; 		
	 					background:  #dfdfdf;
	 					margin: 10px 0px;
	 					padding:  10px;
	 					border-radius: 10px;
	 				}
	 				.contact_menus .contact_menu_item>div{
	 					margin-right:  10px;
	 				}
 				}
 				.contact_menus .button{margin-bottom: 5px;}
 				.contact_menus .menu_item_info input{
 					margin-bottom: 5px;
 				}
 				.contact_menus .menu_item_info{
 					margin-bottom:  5px;
 					background:  white;
 					margin-bottom: 10px;
 					padding:  10px;
 					border-radius: 10px;
 				}
 			</style>
	        
	    </form>	    
		<?php
		return ob_get_clean();
	}
	function add_icon_to_nav($title, $item, $args, $depth){
		if(is_admin()) return;
		if($item->attr_title){
			ob_start();		
			$attr['style'] = ["width"=> "1em","margin-right"=> "0.5em","vertical-align"=>"middle"];
			echo $this->get_icon_html($item->attr_title,$attr);
			$title =ob_get_clean().$title;
		}		
		return $title;
	}
	function get_menu_items($menuid){		
		if(substr($menuid,0,7) == "adminz_"){
			$return = [];
			$contactgroup_customnav =  json_decode($this->get_option_value('settings','custom_nav',''));			
			if (!empty($contactgroup_customnav) and is_array($contactgroup_customnav)) {
				foreach ($contactgroup_customnav as $key => $value) {					
					if(isset($value[0]) and ("adminz_".str_replace(" ","",$value[0]) == $menuid)){
						$return = $value[1];
					}
				}
			}
			if(!empty($return) and is_array($return)){
				$return2 = [];
				foreach ($return as $key => $value) {
					$tmp = (object) array();
					$tmp->url = $value[0];
		            $tmp->title = $value[1];
		            $tmp->post_excerpt = $value[2];
		            $tmp->target = $value[3];
		            $tmp->classes = $value[4];
		            $tmp->xfn = $value[5];
		            $tmp->description = $value[6];
		            $return2[] = $tmp;
				}				
				return $return2;
			}
			return false;
		}
		return wp_get_nav_menu_items($menuid);
	}
	function get_icon($name,$attr=[]){
		return Adminz::get_icon_html($name,$attr);
	}
 	function register_option_setting() { 	
 		register_setting( $this->options_group, 'adminz_contactgroup' );	    
	    ADMINZ_Helper_Language::register_pll_string('adminz_contactgroup[settings][contactgroup_title]',self::$slug,false);
	}
}