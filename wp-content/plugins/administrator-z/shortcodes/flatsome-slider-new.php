<?php 
use Adminz\Admin\Adminz as Adminz;
add_action('ux_builder_setup', function(){
	$template = '<div id="slider-{{::$id}}" class="slider-wrapper relative slider-type-{{ shortcode.options.type }} {{ shortcode.options.visibility }} {{ shortcode.options.class }}">

    <div class="slider slider-auto-height slider-nav-{{ shortcode.options.navPos }}  slider-nav-{{ shortcode.options.navSize }} slider-style-{{ shortcode.options.style }} slider-nav-{{ shortcode.options.navColor }} slider-nav-{{ shortcode.options.navStyle }} slider-nav-dots-{{ shortcode.options.bulletStyle }} is-draggable"
    ng-class="{\'slider-show-nav\' : shortcode.options.hideNav}">
        <content></content>
    </div>

    <style scope="scope">
    	#slider-{{::$id}} {margin-bottom: {{ shortcode.options.margin }}; }
    	#slider-{{::$id}} { background-color: {{ shortcode.options.bgColor }}; }
        #slider-{{::$id}} .slider > *{ max-width: {{ shortcode.options.slideWidth }}!important; }
	    #slider-{{::$id}} .slider > *{margin-left:  0px ; margin-right:  0px; }
    </style>
</div>
';
	add_ux_builder_shortcode('adminz_slider_custom', array(
        'type' => 'container',
	    'name' => __( 'Custom Slider' ),
	    'category'  => Adminz::get_adminz_menu_title(),
	    'message' => __( 'Add slides here' ),
	    //'directives' => array( 'ux-slider' ),
	    //'allow' => array( 'ux_banner','ux_image','section','row','ux_banner_grid','logo'),
	    'template' => $template,
	    'thumbnail' =>  flatsome_ux_builder_thumbnail( 'slider' ),
	    /*'scripts' => array(
		    'flatsome-masonry-js' => get_template_directory_uri() .'/assets/libs/packery.pkgd.min.js',
		  ),*/
	    'tools' => 'shortcodes/ux_slider/ux-slider-tools.directive.html',
	    'wrap'   => false,
	    'info' => '{{ label }}',
	    'priority' => -1,

	    'toolbar' => array(
	        'show_children_selector' => true,
	        'show_on_child_active' => true,
	    ),

	    'children' => array(
	        'inline' => true,
	        'addable_spots' => array( 'left', 'right' )
	    ),

	    /*
	    'presets' => array(
	        array(
	            'name' => __( 'Default' ),
	            'content' => '[ux_slider]',
	        ),
	        array(
	            'name' => __( 'Simple Banner' ),
	            'content' => '[ux_slider][ux_banner][/ux_slider]',
	        ),
	    ), */

	    'options' => array(
	        'label' => array(
	            'type' => 'textfield',
	            'heading' => 'Admin label',
	            'placeholder' => 'Enter admin label...'
	        ),
	        'type' => array(
	          'type' => 'select',
	          'heading' => 'Type',
	          'default' => 'slide',
	          'options' => array(
	            'slide' => 'Slide',
	            'fade' => 'Fade',
	          ),
	        ),
	        'layout_options' => array(
	          'type' => 'group',
	          'heading' => __( 'Layout' ),
	          'options' => array(
	            'style' => array(
	              'type' => 'select',
	              'heading' => 'Style',
	              'default' => 'normal',
	              'options' => array(
	                  'normal' => 'Default',
	                  'container' => 'Container',
	                  'focus' => 'Focus',
	                  'shadow' => 'Shadow',
	              ),
	              'conditions' => 'type !== "fade"',
	            ),
	            'slide_width' => array(
	              'type' => 'scrubfield',
	              'heading' => 'Slide item Width',
	              'description' => 'Width in Percent',
	              'responsive' => true,
	              'unit'=> "%",	              
	              'min' => '0',	              
	            ),
	            'slide_item_padding' => array(
	              'type' => 'scrubfield',
	              'heading' => 'Slide item Padding',
	              'description' => 'Width in Px',	              
	              'default' => '',
	              'min' => '0',	              
	            ),
	            'slide_align' => array(
	              'type' => 'select',
	              'heading' => 'Slide Align',
	              'default' => 'left',
	              'options' => array(
	                  'center' => 'Center',
	                  'left' => 'Left',
	                  'right' => 'Right',
	              ),
	              'conditions' => 'type !== "fade"',
	            ),
	            'bg_color' => array(
	              'type' => 'colorpicker',
	              'heading' => __('Bg Color'),
	              'format' => 'rgb',
	              'position' => 'bottom right',
	              'helpers' => require( get_template_directory()."/inc/builder/shortcodes/helpers/colors.php" ),
	            ),
	            'margin' => array(
	              'type' => 'scrubfield',
	              'responsive' => true,
	              'heading' => __('Margin'),
	              'default' => '0px',
	              'min' => 0,
	              'max' => 100,
	              'step' => 1
	            ),
	            'infinitive' => array(
	                'type' => 'radio-buttons',
	                'heading' => __('Infinitive'),
	                'default' => 'true',
	                'options' => array(
	                    'false'  => array( 'title' => 'Off'),
	                    'true'  => array( 'title' => 'On'),
	                ),
	            ),
	            'freescroll' => array(
	                'type' => 'radio-buttons',
	                'heading' => __('Free Scroll'),
	                'default' => 'false',
	                'options' => array(
	                    'false'  => array( 'title' => 'Off'),
	                    'true'  => array( 'title' => 'On'),
	                ),
	            ),
	            'draggable' => array(
	                'type' => 'radio-buttons',
	                'heading' => __('Draggable'),
	                'default' => 'true',
	                'options' => array(
	                    'false'  => array( 'title' => 'Off'),
	                    'true'  => array( 'title' => 'On'),
	                ),
	            ),
	            'parallax' => array(
	                'type' => 'slider',
	                'heading' => 'Parallax',
	                'unit' => '+',
	                'default' => 0,
	                'max' => 10,
	                'min' => 0,
	            ),
	            'mobile' => array(
	                'type' => 'radio-buttons',
	                'heading' => __('Show for Mobile'),
	                'default' => 'true',
	                'options' => array(
	                    'false'  => array( 'title' => 'Off'),
	                    'true'  => array( 'title' => 'On'),
	                ),
	            ),
	            /*'columns' => array(
	                'type' => 'slider',
	                'heading' => 'Columns',                
	                'default' => 1,
	                'responsive' => true,
	                'max' => '8',
	                'min' => '1',
	            ),*/
	          ),
	        ),

	        'nav_options' => array(
	          'type' => 'group',
	          'heading' => __( 'Navigation' ),
	          'options' => array(
	            'hide_nav' => array(
	                'type' => 'radio-buttons',
	                'heading' => __('Always Visible'),
	                'default' => '',
	                'options' => array(
	                    ''  => array( 'title' => 'Off'),
	                    'true'  => array( 'title' => 'On'),
	                ),
	            ),
	            'nav_pos' => array(
	              'type' => 'select',
	              'heading' => 'Position',
	              'default' => '',
	              'options' => array(
	                  '' => 'Inside',
	                  'outside' => 'Outside',
	              )
	            ),
	           'nav_size' => array(
	              'type' => 'select',
	              'heading' => 'Size',
	              'default' => 'large',
	              'options' => array(
	                  'large' => 'Large',
	                  'normal' => 'Normal',
	              )
	            ),
	            'arrows' => array(
	              'type' => 'radio-buttons',
	              'heading' => __('Arrows'),
	              'default' => 'true',
	              'options' => array(
	                'false'  => array( 'title' => 'Off'),
	                'true'  => array( 'title' => 'On'),
	                ),
	            ),
	            'nav_style' => array(
	              'type' => 'select',
	              'heading' => __('Arrow Style'),
	              'default' => 'circle',
	              'options' => array(
	                  'circle' => 'Circle',
	                  'simple' => 'Simple',
	                  'reveal' => 'Reveal',
	              )
	            ),
	            'nav_color' => array(
	                'type' => 'radio-buttons',
	                'heading' => __('Arrow Color'),
	                'default' => 'light',
	                'options' => array(
	                    'dark'  => array( 'title' => 'Dark'),
	                    'light'  => array( 'title' => 'Light'),
	                ),
	            ),

	            'bullets' => array(
	              'type' => 'radio-buttons',
	              'heading' => __('Bullets'),
	              'default' => 'true',
	              'options' => array(
	                  'false'  => array( 'title' => 'Off'),
	                  'true'  => array( 'title' => 'On'),
	              ),
	            ),
	            'bullet_style' => array(
	              'type' => 'select',
	              'heading' => 'Bullet Style',
	              'default' => 'circle',
	              'options' => array(
	                'circle' => 'Circle',
	                'dashes' => 'Dashes',
	                'dashes-spaced' => 'Dashes (Spaced)',
	                'simple' => 'Simple',
	                'square' => 'Square',
	            )
	           ),
	          ),
	        ),
	        'slide_options' => array(
	          'type' => 'group',
	          'heading' => __( 'Auto Slide' ),
	          'options' => array(
	            'auto_slide' => array(
	                'type' => 'radio-buttons',
	                'heading' => __('Auto slide'),
	                'default' => 'true',
	                'options' => array(
	                    'false'  => array( 'title' => 'Off'),
	                    'true'  => array( 'title' => 'On'),
	                ),
	            ),
	            'timer' => array(
	                'type' => 'textfield',
	                'heading' => 'Timer (ms)',
	                'default' => 6000,
	            ),
	            'pause_hover' => array(
	                'type' => 'radio-buttons',
	                'heading' => __('Pause on Hover'),
	                'default' => 'true',
	                'options' => array(
	                    'false'  => array( 'title' => 'Off'),
	                    'true'  => array( 'title' => 'On'),
	                ),
	            ),
	          ),
	        ),
	        'advanced_options' => require( get_template_directory()."/inc/builder/shortcodes/commons/advanced.php"),
	    ),
    ));
});
function shortcode_ux_slider_custom($atts, $content=null) {

    extract( shortcode_atts( array(
        '_id' => 'slider-'.rand(),
        'timer' => '6000',
        'bullets' => 'true',
        'visibility' => '',
        'class' => '',
        'type' => 'slide',
        'bullet_style' => '',
        'auto_slide' => 'true',
        'auto_height' => 'false',
        'bg_color' => '',
        'slide_align' => 'left',
        'style' => 'normal',
        'slide_width' => '25%',
        'slide_width__md' => '',
        'slide_width__sm' => '',        
        'slide_item_padding'=> '15px',
        'arrows' => 'true',
        'pause_hover' => 'true',
        'hide_nav' => '',
        'nav_style' => 'circle',
        'nav_color' => 'light',
        'nav_size' => 'large',
        'nav_pos' => '',
        'infinitive' => 'true',
        'freescroll' => 'false',
        'parallax' => '0',
        'margin' => '',
        'margin__md' => '',
        'margin__sm' => '',
        'columns' => '1',
        'height' => '',
        'rtl' => 'false',
        'draggable' => 'true',
        'friction' => '0.6',
        'selectedattraction' => '0.1',
        'threshold' => '10',

        // Derpicated
        'mobile' => 'true',

    ), $atts ) );    
    
    // Stop if visibility is hidden
    if($visibility == 'hidden') return;
    if($mobile !==  'true' && !$visibility) {$visibility = 'hide-for-small';}

    ob_start();

    $wrapper_classes = array('slider-wrapper', 'relative');
    if( $class ) $wrapper_classes[] = $class;
    if( $visibility ) $wrapper_classes[] = $visibility;
    $wrapper_classes = implode(" ", $wrapper_classes);

    $classes = array('slider');

    if ($type == 'fade') $classes[] = 'slider-type-'.$type;

    // Bullet style
    if($bullet_style) $classes[] = 'slider-nav-dots-'.$bullet_style;

    // Nav style
    if($nav_style) $classes[] = 'slider-nav-'.$nav_style;

    // Nav size
    if($nav_size) $classes[] = 'slider-nav-'.$nav_size;

    // Nav Color
    if($nav_color) $classes[] = 'slider-nav-'.$nav_color;

    // Nav Position
    if($nav_pos) $classes[] = 'slider-nav-'.$nav_pos;

    // Add timer
    if($auto_slide == 'true') $auto_slide = $timer;

    // Add Slider style
    if($style) $classes[] = 'slider-style-'.$style;

    // Always show Nav if set
    if($hide_nav ==  'true') {$classes[] = 'slider-show-nav';}

    // Slider Nav visebility
    $is_arrows = 'true';
    $is_bullets = 'true';

    if($arrows == 'false') $is_arrows = 'false';
    if($bullets == 'false') $is_bullets = 'false';

    if(is_rtl()) $rtl = 'true';

    $classes = implode(" ", $classes);

    // Inline CSS.
	$css_args = array(
		'bg_color' => array(
			'attribute' => 'background-color',
			'value'     => $bg_color,
		),
	);

	$args = array(
		'margin' => array(
			'selector' => '',
			'property' => 'margin-bottom',
		)
	);	
?>
<?php 
// custom width and padding
 	$args_respondsive_item = array(
		'slide_width' => array(
			'selector' => '',
			'property' => 'max-width',
			'unit'     => '%',
		),
	);
 	echo ux_builder_element_style_tag( $_id ." .flickity-slider > *", $args_respondsive_item, $atts ); 	
 	?>
 	<style type="text/css">
 		<?php if($slide_item_padding){ ?>
 		#<?php echo $_id;?> .slider{margin-left:  -<?php echo $slide_item_padding;?>; margin-right:  -<?php echo $slide_item_padding;?>;}
		#<?php echo $_id;?> .flickity-slider >*{padding-left:  <?php echo $slide_item_padding;?>; padding-right:  <?php echo $slide_item_padding;?>;}
		<?php } ?>
 		#<?php echo $_id; ?> .slider:not(.flickity-enabled){
 			visibility: hidden;
 		}
 	</style>
<div class="<?php echo $wrapper_classes; ?>" id="<?php echo $_id; ?>" <?php echo get_shortcode_inline_css($css_args); ?>>
    <div class="<?php echo $classes; ?>"
        data-flickity-options='{
            "cellAlign": "<?php echo $slide_align; ?>",
            "imagesLoaded": true,
            "lazyLoad": 1,
            "freeScroll": <?php echo $freescroll; ?>,
            "wrapAround": <?php echo $infinitive; ?>,
            "autoPlay": <?php echo $auto_slide;?>,
            "pauseAutoPlayOnHover" : <?php echo $pause_hover; ?>,
            "prevNextButtons": <?php echo $is_arrows; ?>,
            "contain" : true,
            "adaptiveHeight" : <?php echo $auto_height;?>,
            "dragThreshold" : <?php echo $threshold ;?>,
            "percentPosition": true,
            "pageDots": <?php echo $is_bullets; ?>,
            "rightToLeft": <?php echo $rtl; ?>,
            "draggable": <?php echo $draggable; ?>,
            "selectedAttraction": <?php echo $selectedattraction; ?>,
            "parallax" : <?php echo $parallax; ?>,
            "friction": <?php echo $friction; ?>,
            "groupCells": "<?php echo $slide_width; ?>"
        }'
        >
        <?php echo do_shortcode( $content ); ?>
 	</div>

 	<div class="loading-spin dark large centered"></div>
 	
	<?php echo ux_builder_element_style_tag( $_id, $args, $atts ); ?>
</div>

<?php
    $content = ob_get_contents();
    ob_end_clean();
    return apply_filters('adminz_output_debug',$content);
}
add_shortcode("adminz_slider_custom", "shortcode_ux_slider_custom");

