<?php 
use Adminz\Admin\Adminz as Adminz;

add_action( 'wp_enqueue_scripts', function () {
   	wp_register_style( 'adminz_fix_flickity_css', plugin_dir_url(ADMINZ_BASENAME).'assets/flickity/custom_flickity.css');
   	wp_register_style( 'adminz_flickity_css', plugin_dir_url(ADMINZ_BASENAME).'assets/flickity/flickity.min.css');
   	wp_register_script( 'adminz_flickity_config' , plugin_dir_url(ADMINZ_BASENAME).'assets/flickity/adminz_flickity_config.js', array('adminz_flickity_js'));
	wp_register_script( 'adminz_flickity_js', plugin_dir_url(ADMINZ_BASENAME).'assets/flickity/flickity.pkgd.min.js', array('jquery'));
},101 );

$flickity_attributes = [
	'draggable'=> [					'default'=> 'true', 	'heading'=>'draggable', 'type'=>'textfield' , 'jsvar'=>'draggable'],
	'freescroll'=> [				'default'=> 'false', 	'heading'=>'freeScroll', 'type'=>'textfield' , 'jsvar'=>'freeScroll'],
	'contain'=> [					'default'=> 'true', 	'heading'=>'contain', 'type'=>'textfield' , 'jsvar'=>'contain'],
	'wraparound'=> [				'default'=> 'true', 	'heading'=>'wrapAround', 'type'=>'textfield' , 'jsvar'=>'wrapAround'],
	'groupcells'=> [				'default'=> 'false', 	'heading'=>'groupCells', 'type'=>'textfield' , 'jsvar'=>'groupCells'],
	'autoplay'=> [					'default'=> 'false', 	'heading'=>'autoPlay', 'type'=>'textfield' , 'jsvar'=>'autoPlay'],
	'pauseautoplayonhover'=> [		'default'=> 'false', 	'heading'=>'pauseAutoPlayOnHover', 'type'=>'textfield' , 'jsvar'=>'pauseAutoPlayOnHover'],
	'adaptiveheight'=> [			'default'=> 'true', 	'heading'=>'adaptiveHeight', 'type'=>'textfield' , 'jsvar'=>'adaptiveHeight'],
	'whatcss'=> [					'default'=> 'false', 	'heading'=>'whatCSS', 'type'=>'textfield' , 'jsvar'=>'whatCSS'],
	'asnavfor'=> [					'default'=> '', 		'heading'=>'asNavFor', 'type'=>'textfield' , 'jsvar'=>'asNavFor'],
	'selectedattraction'=> [		'default'=> '0.025', 	'heading'=>'selectedAttraction', 'type'=>'textfield' , 'jsvar'=>'selectedAttraction'],
	'friction'=> [					'default'=> '0.28', 	'heading'=>'friction', 'type'=>'textfield' , 'jsvar'=>'friction'],
	'imagesloaded'=> [				'default'=> 'true', 	'heading'=>'imagesLoaded', 'type'=>'textfield' , 'jsvar'=>'imagesLoaded'],
	'lazyload'=> [					'default'=> 'true', 	'heading'=>'lazyLoad', 'type'=>'textfield' , 'jsvar'=>'lazyLoad'],
	'cellselector'=> [				'default'=> '', 		'heading'=>'cellSelector', 'type'=>'textfield' , 'jsvar'=>'cellSelector'],
	'initialindex'=> [				'default'=> '0', 		'heading'=>'initialIndex', 'type'=>'textfield' , 'jsvar'=>'initialIndex'],
	'accessibility'=> [				'default'=> 'true', 	'heading'=>'accessibility', 'type'=>'textfield' , 'jsvar'=>'accessibility'],
	'setgallerysize'=> [			'default'=> 'true', 	'heading'=>'setGallerySize', 'type'=>'textfield' , 'jsvar'=>'setGallerySize'],
	'resize'=> [					'default'=> 'true', 	'heading'=>'resize', 'type'=>'textfield' , 'jsvar'=>'resize'],
	'cellalign'=> [					'default'=> 'left', 	'heading'=>'cellAlign', 'type'=>'textfield' , 'jsvar'=>'cellAlign'],
	'percentposition'=> [			'default'=> 'false', 	'heading'=>'percentPosition', 'type'=>'textfield' , 'jsvar'=>'percentPosition'],
	'righttoleft'=> [				'default'=> 'false', 	'heading'=>'rightToLeft', 'type'=>'textfield' , 'jsvar'=>'rightToLeft'],
	'prevnextbuttons'=> [			'default'=> 'true', 	'heading'=>'prevNextButtons', 'type'=>'textfield' , 'jsvar'=>'prevNextButtons'],
	'pagedots'=> [					'default'=> 'false', 	'heading'=>'pageDots', 'type'=>'textfield' , 'jsvar'=>'pageDots'],
	'arrowshape'=> [				'default'=> '', 		'heading'=>'arrowShape', 'type'=>'textfield' , 'jsvar'=>'arrowShape'],
];

add_action('ux_builder_setup', function () use($flickity_attributes) {
	$option = array(
		'info' => '{{ heading }}',
        'name'      => __('Gallery Flickity'),
        'category'  => Adminz::get_adminz_menu_title(),
        'thumbnail' =>  get_template_directory_uri() . '/inc/builder/shortcodes/thumbnails/' . 'slider.svg',
        'options' => array(
            'ids'             => array(
				'type'       => 'gallery',
				'heading'	=> __('Images'),
			),
			'ratio'=> array(
				'type' => 'textfield',
				'heading' => 'Aspect ratio',
				'default' => '56.25%',
				'placeholder'=> "56.25%"
			),
			'thumbnailscol'=> array(
				'type'=>'slider',
				'min'=> 1,
				'max'=> 12,
				'default'=>4,
				'heading'=> 'Columns count',
				'description'=> 'If using thumbnails, this value is used for the number of small images'
			),
			'usethumbnails'=>array(
                'type' => 'checkbox',
                'heading'   =>'Use small thumbnails',
                'default' => 'true'
            )
        ),
    );
    if(Adminz::is_woocommerce()){
    	$option['options']['is_lightbox'] = array(
                'type' => 'checkbox',
                'heading'   =>'photoswipe Lightbox',
                'default' => 'false'
            );
    }        
    $option['options']['note'] = array(
				'type'=>'group',
				'heading'=> 'JS Document',
				'description'=> "https://flickity.metafizzy.co/options.html",
				'options' => $flickity_attributes
			);
	add_ux_builder_shortcode('adminz_flickity', $option);
});

add_shortcode('adminz_flickity', function ($atts) use($flickity_attributes){
	wp_enqueue_script( 'adminz_flickity_config');

	if(!in_array('Flatsome', [wp_get_theme()->name, wp_get_theme()->parent_theme])){		
		wp_enqueue_style( 'adminz_flickity_css');
	}	
	$mapdefault = [		
		'ids'    => '',
        'usethumbnails'=>true,
        'thumbnailscol' => 4,
        'thumbnail_padding'=> 15,
        'is_lightbox'=>"false",
        'ratio' =>''
	];

	foreach ($flickity_attributes as $key => $value) {
		$mapdefault[$key] = $value['default'];
	}
	$map = shortcode_atts($mapdefault, $atts);		
	$randomclass = "adminz_flickity".wp_rand();
	$map['asnavfor'] = $map['asnavfor']? $map['asnavfor'] : ".".$randomclass;
	extract($map);

	if($is_lightbox == 'true' and Adminz::is_woocommerce()){
		wp_enqueue_style('photoswipe');
		wp_enqueue_style('photoswipe-default-skin');
		wp_enqueue_script('photoswipe');
		wp_enqueue_script('photoswipe-ui-default');
		add_action('wp_footer', function (){
			wc_get_template( 'single-product/photoswipe.php' );
		});
	}

	$thumbnail_size = apply_filters( 'woocommerce_gallery_image_size', 'full' );

	if(isset( $_POST['ux_builder_action'] )){
		$idss = explode(',', $ids);
		if($ids and is_array($idss) and !empty($idss) ){
			return wp_get_attachment_image($idss[0],$thumbnail_size,false,['style'=>'width: 100% !important;']);
		}
	}

    ob_start();    
    $data_flickity = [];    
    foreach ($map as $key => $value) {
    	if($value){
    		if(!($value == 'true' or $value == 'false' or is_bool($value))){
    			$value = '"'.$value.'"';
			}			
			$jskey = isset($flickity_attributes[$key]['jsvar']) ?  $flickity_attributes[$key]['jsvar'] : $key;
			$data_flickity[]='"'.$jskey.'":'.$value.'';
    	}
    }
    ?> 
    <style type="text/css">
		.<?php echo $randomclass; ?>:not(.flickity-enabled){
			-js-display: flex; display: -webkit-box; display: -ms-flexbox; -webkit-box-orient: horizontal; -webkit-box-direction: normal; -ms-flex-flow: row wrap; flex-flow: row wrap; white-space: nowrap; overflow-y: hidden; overflow-x: hidden; width: auto;
		}
		.<?php echo $randomclass; ?>:not(.flickity-enabled)>.col{
			display: inline-block;
		}
		.<?php echo $randomclass; ?> .col.colnav>div{
			webkit-transition: background-position 0.3s ease-in-out;
		    -moz-transition: background-position 0.3s ease-in-out;
		    -o-transition: background-position 0.3s ease-in-out;
		    -ms-transition: background-position 0.3s ease-in-out;
		    transition: background-position 0.3s ease-in-out;
		}
		.<?php echo $randomclass; ?> .colnav:hover>div,
		.<?php echo $randomclass; ?> .is-nav-selected>div{
			background-position:  60% 60% !important;
		}
	</style>   
    <div class="adminz_flickity slider mb-half <?php echo $randomclass; ?> row" data-adminz='{<?php echo implode(",", $data_flickity ); ?>}' >
	  <?php 
		$idss = explode(',', $ids);

		$padding_top = "56.25%";
		$thumbnail_size = apply_filters( 'woocommerce_gallery_image_size', 'full' );
    	if(is_array($thumbnail_size)){
    		$padding_top = ($thumbnail_size[1]/$thumbnail_size[0]*100)."%";
    	}
    	if($ratio){
    		$padding_top = $ratio;
    	}
		if($ids and is_array($idss) and !empty($idss) ){
			foreach ($idss as $id) {
				$bigcol = $usethumbnails ? 1 : $thumbnailscol;
				$fullimage = wp_get_attachment_image_src($id, 'full',false);
				?>
				<div class="col" style="width: <?php echo (100/$bigcol); ?>% !important;">					
					<div 
						class="open-gallery"
						style="
							padding-top: <?php echo $padding_top;?>; 
							background-image: url(<?php echo $fullimage[0];?>); 
							background-size: cover;
						    background-repeat: no-repeat;
						    background-position: center center;
						"
						data-href ="<?php echo $fullimage[0]; ?>"
						data-title ="<?php echo get_post_field( 'post_title', $id ); ?>"
						data-src ="<?php echo $fullimage[0]; ?>"
						data-width ="<?php echo $fullimage[1]; ?>"
						data-height ="<?php echo $fullimage[2]; ?>"
						>
						</div>
				</div>
				<?php		
			}
		}
		?>
	</div>
	<!-- thumbnails -->
	<?php if($usethumbnails){		
	$data_flickity2 = [];
	$map['contain'] = "true";
	$map['wrapAround'] = "false";
	$map['pagedots'] = 'false';
	$map['prevnextbuttons'] = 'false';
	foreach ($map as $key => $value) {
    	if($value){
    		if(!($value == 'true' or $value == 'false' or is_bool($value))){
    			$value = '"'.$value.'"';
			}			
    		$jskey = isset($flickity_attributes[$key]['jsvar']) ?  $flickity_attributes[$key]['jsvar'] : $key;
			$data_flickity2[]='"'.$jskey.'":'.$value.'';
    	}
    }
	?>	
	<div class="adminz_flickity slider product-thumbnails row <?php echo $randomclass; ?>" data-adminz='{<?php echo implode(",", $data_flickity2 ); ?>}'>
	  <?php 	  
		$idss = explode(',', $ids);
		if($ids and is_array($idss) and !empty($idss) ){
			foreach ($idss as $key=> $id) {
				$thumbimg = wp_get_attachment_image_src($id, $thumbnail_size,false);
				?>
				<div class="col colnav" style="width: <?php echo (100/$thumbnailscol); ?>% !important;">
					<div style="
						padding-top: <?php echo $padding_top;?>; 
						background-image: url(<?php echo $thumbimg[0];?>); 
						background-size: cover;
					    background-repeat: no-repeat;
					    background-position: center center;
					"></div>
				</div>
				<?php				
 			}
		}
		?>
	</div>
	<style type="text/css">
		
		.adminz_flickity.mb-half div{width:  100%;}
		.adminz_flickity .col{padding: 0 <?php echo $thumbnail_padding;?>px; }		
	</style>
	<?php if($is_lightbox == 'true' and Adminz::is_woocommerce()){
		?>

		<script type="text/javascript">
			window.addEventListener('DOMContentLoaded', function() {
				'use strict';
				(function($) {
				  let galleryArray<?php echo $randomclass;?> = [];
				  $('.<?php echo $randomclass;?>.adminz_flickity.mb-half').find('.col').each(function() {			  	  	
			    	var $link = $(this).find('.open-gallery'),
				      item = {
				        src: $link.data('href'),
				        w: $link.data('width'),
				        h: $link.data('height'),
				        title: $link.data('title') 
				      };
				    galleryArray<?php echo $randomclass;?>.push(item);
				  });		  
				  $('.<?php echo $randomclass;?> .open-gallery').click(function(event) {
				    event.preventDefault();
				    var $pswp = $('.pswp')[0],
				      options = {
				        index: $(this).closest('.col').index(),
				        bgOpacity: 0.6,
				        showHideOpacity: true
				      };
				    var gallery = new PhotoSwipe($pswp, PhotoSwipeUI_Default, galleryArray<?php echo $randomclass;?>, options);
				    gallery.init();
				  });
				}(jQuery));
			});
		</script>
		<?php
	} ?>			
	<?php }
    return apply_filters('adminz_output_debug',ob_get_clean());
});

