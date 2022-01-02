<?php 
use Adminz\Admin\Adminz as Adminz;
add_action('ux_builder_setup', 'adminz_lightbox');

function adminz_lightbox(){
    add_ux_builder_shortcode('adminz_lightbox', array(
    	'type'=>'container',
        'name'      => 'Custom Lightbox',
        'category'  => Adminz::get_adminz_menu_title(),
        'thumbnail' =>  get_template_directory_uri() . '/inc/builder/shortcodes/thumbnails/' . 'ux_banner' . '.svg',        
        'info'      => '{{ id }}',
        'options' => array(
        	'auto_open' => array(
	          'type' => 'select',
	          'heading' => __( 'Auto open','ux-builder' ),
	          'default' => 'false',
	          'options' => array(
	              'false' => 'False',
	              'true' => 'True',
	          )
	    	),
	    	'auto_show' => array(
	          'type' => 'select',
	          'heading' => __( 'Auto show','ux-builder' ),
	          'default' => 'once',
	          'options' => array(
	              'once' => 'Once',
	              'always' => 'Always',
	          )
	    	),
	    	'auto_timer' => array(
				'type'       => 'slider',
				'heading'	=> __('First open timer'),
				'default'    => 0,
				'min'	=> 0,
				'step'=> 500,
				'unit'=>"ms",
				'max'=> 10000
			),			
            'id' => array(
                'type'       => 'textfield',
                'heading'    => __('Lightbox ID'),
                'default' => "lightbox_".rand()
            ),
            'width' => array(
	            'type' => 'scrubfield',
	            'heading' => __( 'Width' ),
	            'default' => '650px',
	        ),
            'padding' => array(
                'type' => 'scrubfield',
	            'heading' => __( 'Padding' ),
	            'default' => '20px',
	            'min' => '0px'
            ),
            'block' => array(
	          'type' => 'select',
		      'heading' => __( 'Block', 'flatsome' ),
		      'config' => array(
		        'placeholder' => __( 'Select', 'flatsome' ),
		        'postSelect' => array(
		          'post_type' => array( 'blocks' )
		        ),
		      )
	    	),
	    	'close_bottom' => array(
                'type' => 'checkbox',
	            'heading' => __( 'Close on bottom' ),
	            'default' => 'false',
            ),
            'interval' => array(
            	'type' => 'group',
            	'heading'=> "Interval Open lighbox",
            	'options'=> array(
            		'reopen' => array(
		                'type' => 'checkbox',
			            'heading' => __( 'Interval Open' ),
			            'default' => 'false',
		            ),
		            'reopen_timer' => array(
						'type'       => 'slider',
						'heading'	=> __('Reopen timer'),				
						'default'    => 10,
						'min'	=> 1,
						'step'=> 1,
						'unit'=>"second",
						'max'=> 60
					),
            	)
            ),
            'note'=> array(
				'type'=>'group',
				'heading'=> 'Note **',
				'description'=> "Do not set position of this shortcode at the first of block. It will make error for Hover dom to show Uxbuilder editor",
				'options' => ""
			)
        ),
    ));
}
add_shortcode('adminz_lightbox', 'adminz_lightbox_shortcode');
function adminz_lightbox_shortcode($atts, $content = null ) {
	extract(shortcode_atts(array(
		'auto_open'=> "false",
		'auto_timer'=> '0',
		'auto_show'=> 'once',		
    	'id' => '',
    	'width'=> '650px',
    	'padding' => '20px',
    	'block' =>'',
    	'close_bottom'=>'false',
    	'reopen'=>0,
    	'reopen_timer'=>10
    ), $atts));    
    ob_start();
    
    $shortcode = '[lightbox_custom';
    if($auto_open == 'false'){
    	$auto_show= "false"; 
    }
    if($auto_open == 'true'){
    	$shortcode .= ' auto_open="'.$auto_open.'" auto_timer="'.$auto_timer.'" auto_show="'.$auto_show.'"';
    }
    $shortcode.= ' reopen="'.$reopen.'" reopen_timer="'.$reopen_timer.'"';
	$shortcode .= ' id="'.$id.'" width="'.$width.'" padding="'.$padding.'" close_bottom="'.$close_bottom.'" ';
	$shortcode.= ']';

	// shortcode content 
	if($block){
		$shortcode .= '[block id="'.$block.'"]';
	}	

	$shortcode .= $content;
	
	$shortcode .='[/lightbox_custom]';
	echo do_shortcode($shortcode);
    $return = ob_get_clean();
    return $return;
}

// Custom lightbox flatsome : extend from lightbox flatsome; custom icon and close button

function ux_lightbox_custom( $atts, $content = null ) {	
	extract( shortcode_atts( array(
		'id'         => rand(),
		'width'      => '650px',
		'padding'    => '20px',
		'class'      => '',
		'auto_open'  => false,
		'auto_timer' => '2500',
		'auto_show'  => '',
		'version'    => '1',		
		'close_bottom' => 'false',
		'reopen' => 'false',
		'reopen_timer' => 10
	), $atts ) );		
	ob_start();	
	?>
	<div id="<?php echo $id; ?>"
	     class="adminz_lightbox lightbox-by-id lightbox-content mfp-hide lightbox-white <?php echo $class; ?>"
	     style="max-width:<?php echo $width ?> ;padding:<?php echo $padding; ?>">
		<?php echo do_shortcode( $content ); ?>
		<?php if($close_bottom == "true"){?>
			<div class="close_on_bottom_<?php echo $id;?> text-shadow-2">
				<em style="cursor: pointer;" onClick="jQuery.magnificPopup.close();"><?php echo _e("Close");?></em>
			</div>
			<style type="text/css">
				.close_on_bottom_<?php echo $id;?>{opacity: 0.5; font-size: 0.8em; text-align:  right; font-weight: bolder;
					position: absolute; bottom: 15px; right: 15px; color:  #828282;}
				.close_on_bottom_<?php echo $id;?>:hover{opacity: 1;}
			</style>
		<?php } ?>		
	</div>
	<?php if ( $auto_open ) : ?>
		<script type="text/javascript">
			window.addEventListener('DOMContentLoaded', function() {
				(function($){
					'use strict';
					var closelightbox_custom = flatsomeVars.lightbox.close_markup;
					<?php if($close_bottom =='true') {echo 'closelightbox_custom = "";'; }?>
					var src = '#<?php echo $id; ?>';
					var cookieId = '<?php echo "lightbox_{$id}" ?>';
					var cookieValue = '<?php echo "opened_{$version}"; ?>';
					var timer = parseInt('<?php echo $auto_timer; ?>');
					var retimer = parseInt('<?php echo $auto_timer; ?>');
					var closeBtnInside = <?php echo json_encode(apply_filters('flatsome_lightbox_close_btn_inside',false)); ?>;
					var reopen = '<?php echo $reopen; ?>';				
					var reopen_timer = parseInt('<?php echo $reopen_timer*1000; ?>');				
					
					/*Auto open lightbox*/
					<?php if ( $auto_show == 'always' ) : ?>
					cookie(cookieId, false);
					<?php endif; ?>

					/*Run lightbox if no cookie is set*/
					if (cookie(cookieId) !== cookieValue) {

						general_lightbox(timer,closeBtnInside,closelightbox_custom);				

						/*Set cookie*/
						cookie(cookieId, cookieValue, 365);		

						/*Re open lightbox*/					
						
						if(reopen == 'true' && reopen_timer){												
							setInterval(function(){							
								if(!$.magnificPopup.instance.isOpen){
									general_lightbox(timer,closeBtnInside,closelightbox_custom);

								}
							}, reopen_timer);	
						}		
					}
					function general_lightbox(timer,closeBtnInside,closelightbox_custom){
						/*Ensure closing off canvas*/
						setTimeout(function () {
							jQuery.magnificPopup.close()
						}, timer - 350);

						/*Open lightbox*/
						setTimeout(function () {
							$.magnificPopup.open({
								midClick: true,
								removalDelay: 300,
								closeBtnInside: closeBtnInside,
								closeMarkup: closelightbox_custom,
								items: {
									src: src,
									type: 'inline'
								}
							})
						}, timer);
					}
				})(jQuery);
			});
		</script>
	<?php endif; ?>

	<?php
	$content = ob_get_contents();
	ob_end_clean();

	return apply_filters('adminz_output_debug',$content);
}

add_shortcode( 'lightbox_custom', 'ux_lightbox_custom' );