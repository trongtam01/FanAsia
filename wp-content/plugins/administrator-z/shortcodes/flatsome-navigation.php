<?php 
use Adminz\Admin\Adminz as Adminz;
function adminz_navigation(){
    add_ux_builder_shortcode('adminz_navigation', array(
        'name'      => __('Navigation Seletor'),
        'category'  => Adminz::get_adminz_menu_title(),
        'thumbnail' =>  get_template_directory_uri() . '/inc/builder/shortcodes/thumbnails/' . 'nav' . '.svg',
        'options' => array(
        	'name' => array(
	          	'type' => 'textfield',
	          	'heading' => __( 'Menu name','ux-builder' ),
	          	'default'=> 'MENU'
	    	),
	    	'nav' => array(
	          'type' => 'select',
	          'heading' => __( 'Choose Navigation','ux-builder' ),	          
	          'param_name' => 'slug',
		        'config' => array(
		            'multiple' => false,
		            'placeholder' => 'Select..',
		            'termSelect' => array(
		                'taxonomies' => 'nav_menu'
		            ),
		        )
	    	),
	    	'appearance'=>[
	    		'type'	=> 'group',
	    		'heading'=>	'Appearance',
	    		'options'=> [
			    	'destop_appearance'=>array(
		                'type' => 'select',
		                'heading'   =>'Desktop appearance',
		                'default' => 'horizontal',
		                'options'=> [
		                	'horizontal'=> 'Horizontal',
		                	'vertical' => 'Vertical',
		                	'name_left'=> 'Horizontal name in left',	
		                	'button_toggle'=> 'Button toggle',
		                	'button_with_link'=> 'Button with link'	                	
		                ]
		            ),    	
			    	'mobile_appearance'=>array(
		                'type' => 'select',
		                'heading'   =>'Mobile appearance',
		                'default' => 'horizontal',
		                'options'=> [
		                	'horizontal'=> 'Horizontal',
		                	'vertical' => 'Vertical',
		                	'name_left'=> 'Horizontal name in left',
		                	'button_toggle'=> 'Button toggle',
		                	'button_with_link'=> 'Button with link'
		                ]
		            ),
			    	'style' => array(
			          'type' => 'select',
			          'heading' => __( 'Nav Style','ux-builder' ),
			          'default' => '',
			          'options' => array(
			          		''=> "Default",
			              	'divided' => 'Divided',
							'line' => 'Line',
							'line-grow' => 'Line grow',
							'line-bottom' => 'Line bottom',
							'box' => 'Box',
							'outline' => 'Outline',
							'pills' => 'Pills',
							'tabs' => 'Tabs',
			          )
			    	),
			    	'size' => array(
			          'type' => 'select',
			          'heading' => __( 'Nav Size','ux-builder' ),
			          'default' => 'default',
			          'options' => array(
			              	'xsmall' => 'Xsmall',
			              	'small'	=> 'Small',
			              	'default'	=> 'Default',
			              	'medium'	=> 'Medium',
			              	'large'	=> 'Large',
			              	'xlarge'	=> 'Xlarge',
			          )
			    	),
			    	'spacing' => array(
			          'type' => 'select',
			          'heading' => __( 'Nav Spacing','ux-builder' ),			          
			          'default' => 'default',
			          'options' => array(
			              	'xsmall' => 'Xsmall',
			              	'small'	=> 'Small',
			              	'default'	=> 'Default',
			              	'medium'	=> 'Medium',
			              	'large'	=> 'Large',
			              	'xlarge'	=> 'Xlarge',
			          )
			    	),
			    	'uppercase' => array(
			          'type' => 'select',
			          'heading' => __( 'Uppercase','ux-builder' ),
			          'default' => 'normal',
			          'options' => array(
			              	'uppercase' => 'Uppercase',
			              	'normal' => 'Normal',
			              	'captilizer' => 'Captilizer'
			          )
			    	),
			    	'horizontal_align' => array(
			          'type' => 'select',
			          'heading' => __( 'Items align','ux-builder' ),
			          'default' => 'left',			          
			          'options' => array(
			              'left' => 'Left',
			              'right' => 'Right',
			              'center'=> 'Center',
			          )
			    	),
	    		]
	    	],	
			'other'=>[
				'type' =>'group',
				'heading' => 'Other',
				'options' => [
					'menu_mobile_link' => array(
			          	'type' => 'textfield',
			          	'heading' => __( 'Link','ux-builder' ),
			          	'default'=> '',			          	
			    	),
			    	'menu_mobile_link_text' => array(
			          	'type' => 'textfield',
			          	'heading' => __( 'Link text','ux-builder' ),
			          	'default'=> 'View more',			          	
			    	),
					'toggle' => array(
			          'type' => 'select',
			          'heading' => __( 'Vertical Items toggled','ux-builder' ),	          
			          'default' => 'no',
			          'options' => array(
			              'no' => 'No',
			              'yes' => 'Yes',
			          )
			    	),
			    	'class' => array(
			          'type' => 'textfield',
			          'heading' => __( 'Class','ux-builder' ),
			    	),
				]
			],
	    	
        ),
    ));
}
add_action('ux_builder_setup', 'adminz_navigation');

function adminz_navigation_shortcode($atts){
	add_filter('nav_menu_css_class', 'adminz_add_additional_class_on_li', 1, 3);
	add_filter('walker_nav_menu_start_el', 'add_description_to_menu', 10, 4);
    extract(shortcode_atts(array(
    	'name'=> 'MENU',
    	'nav'    => '2',
    	'destop_appearance'=>'horizontal',
    	'mobile_appearance' => 'horizontal',
    	'menu_mobile_link'=> '',
    	'menu_mobile_link_text'=> 'View more',                
        'uppercase' => 'normal',	
        'style' => '',
        'toggle' => 'no',
        'horizontal_align' => 'left',        
        'size' => 'default',
        'spacing' => 'default',
        'class'=> 'adminz_navigation_custom'
    ), $atts));
    $id = "adminz_navigation".rand();
    $ul_class = 'nav-'.$horizontal_align.' nav-'.$style.' nav-'.$uppercase.' nav-size-'.$size.' nav-spacing-'.$spacing." ".$class;
    $walker  = 'FlatsomeNavDropdown';

    // template for vertical
    ob_start();
    $argsmain = array(
    	'menu'              => $nav,
	    'menu_class'	=> "menu ".$ul_class,
	    'container'      => false,
	    'items_wrap'        => '<ul id="%1$s" class="%2$s">%3$s</ul>',
	    'walker'         => '',
	    'add_li_class'  => '',
    );
    if($toggle=='yes'){
    	$argsmain ['add_li_class'] = 'active';
    }
    wp_nav_menu($argsmain);
    $template_vertical = ob_get_clean();

    // tempalte for horizontal
    ob_start();
    $argsmain = array(
    	'menu'              => $nav,
	    'menu_class'	=> "header-nav header-nav-main nav ".$ul_class,
	    'container'      => false,
	    'items_wrap'        => '<ul id="%1$s" class="%2$s">%3$s</ul>',
	    'walker'         => new $walker(),
	    'add_li_class'  => '',
    );
    wp_nav_menu($argsmain);

    $template_horizontal = ob_get_clean();

    // template for name left
    ob_start();
    ?>
    <div style='display:flex;align-items: center;'>
		<div class="navhead is-<?php echo $size;?>">
			<strong><?php echo $name; ?></strong>
		</div>
		<?php
		$argsmain = array(
	    	'menu'              => $nav,
		    'menu_class'	=> 'header-nav header-nav-main nav nav-right nav-'.$style.' nav-'.$uppercase.' nav-size-'.$size.' nav-spacing-'.$spacing." ".$class,
		    'container'      => false,
		    'items_wrap'        => '<ul id="%1$s" class="%2$s">%3$s</ul>',
		    'walker'         => new $walker(),
		    'add_li_class'  => '',
	    );
	    wp_nav_menu($argsmain);
		?>
		<style type="text/css">#<?php echo $id; ?> .header-nav{width: unset; margin-left: auto;}</style>
	</div>
    <?php
    $template_name_left = ob_get_clean();

    // template for button toggle
    ob_start();
    ?>
    <div class="nav-head">
		<div class="navhead is-<?php echo $size;?>"><strong><?php echo $name; ?></strong></div> 
		<button class="button is-link is-<?php echo $size;?> mb-0 mr-0"><i class="icon-angle-down"></i></button>
	</div>
	<div class="hidden">
		<?php 
			$argsmain = array(
		    	'menu'              => $nav,
			    'menu_class'	=> "menu ".$ul_class,
			    'container'      => false,
			    'items_wrap'        => '<ul id="%1$s" class="%2$s">%3$s</ul>',
			    'walker'         => '',
			    'add_li_class'  => '',
		    );
		    if($toggle=='yes'){
		    	$argsmain ['add_li_class'] = 'active';
		    }
		    wp_nav_menu($argsmain);
		?>
	</div>
    <?php
    $template_button_toggle = ob_get_clean();

    // template for button with link    
    ob_start();
    ?>
    <div class="nav-head">
		<div class="navhead is-<?php echo $size;?>"><strong><?php echo $name; ?></strong></div> 
		<a href="<?php echo $menu_mobile_link? $menu_mobile_link : "#" ; ?> ">
			<?php echo $menu_mobile_link_text? "<small>".$menu_mobile_link_text."</small>"." " : ""; ?>
			<i class="icon-angle-right" style="vertical-align: middle;"></i>
		</a>
	</div>
    <style type="text/css">#<?php echo $id; ?> .header-nav{width: unset; margin-left: auto;}</style>
    <?php

    $template_button_with_link = ob_get_clean();

    ob_start();
    ?>
    <div class="hide-for-small">
    	<?php 
    	switch ($destop_appearance) {
    		case 'vertical':
    			echo $template_vertical;
    			break;
    		case 'name_left':
	    		echo $template_name_left;
    			break;
			case 'button_toggle':
				echo $template_button_toggle;
				break;
			case 'button_with_link':
				echo $template_button_with_link;
				break;
    		default:
    			echo $template_horizontal;
    			break;
    	}
    	?>
    </div>
    <!-- mobile-->
    <div class="show-for-small">
    	<?php 
    	switch ($mobile_appearance) {
    		case 'vertical':
    			echo $template_vertical;
    			break;
    		case 'name_left';
    			echo $template_name_left;
    			break;
			case 'button_toggle':
				echo $template_button_toggle;
				break;
			case 'button_with_link':
				echo $template_button_with_link;
				break;
    		default:
    			echo $template_horizontal;
    			break;
    	}
    	?>
    </div>
    <script type="text/javascript">
    	window.addEventListener('DOMContentLoaded', function() {
    		(function($) {
    			$('#<?php echo $id;?> .nav-head .button').each(function(){
					$(this).on("click",function(){					
						var parent = $(this).closest(".nav-head");					
						var target = parent.next().toggleClass('hidden');
						
					});
				});
			})(jQuery);    		
    	});		
	</script>

    <?php


    
    remove_filter('nav_menu_css_class', 'adminz_add_additional_class_on_li', 1, 3);
	remove_filter('walker_nav_menu_start_el', 'add_description_to_menu', 10, 4);
	?>
	<style type="text/css">
		.adminz_navigation_wrapper .nav-head{display: flex; justify-content: space-between; align-items: center;}
		#<?php echo $id;?>>.show-for-small{	padding-top: 10px; padding-bottom: 10px;}
		#<?php echo $id;?> .navhead{display:  inline-block; width: unset; vertical-align:  middle; margin-bottom:  0px;}
		.col-inner #<?php echo $id;?> .sub-menu li{ margin-left: 0 ; }
		.adminz_navigation_wrapper ul.nav-center li{text-align:  center;}
	</style>	
	<?php
    $html = ob_get_clean();
    return "<div id='".$id."' class='adminz_navigation_wrapper'>".$html."</div>";
}
add_shortcode('adminz_navigation', 'adminz_navigation_shortcode');

function adminz_add_additional_class_on_li($classes, $item, $args) {
    if(isset($args->add_li_class)) {
        $classes[] = $args->add_li_class;
    }
    return $classes;
}
function add_description_to_menu($item_output, $item, $depth, $args) {
	
   	if (strlen($item->description) > 0 ) {
      	$item_output .= sprintf('<p class="description">%s</p>', esc_html($item->description)); 
   	}   
   return $item_output;
}
