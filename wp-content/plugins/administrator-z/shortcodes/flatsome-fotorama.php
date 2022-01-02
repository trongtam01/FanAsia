<?php 
use Adminz\Admin\Adminz as Adminz;

add_action( 'wp_enqueue_scripts', function () {
	wp_register_style( 'adminz_fotorama_css', plugin_dir_url(ADMINZ_BASENAME).'assets/fotorama/fotorama.css');
   	wp_register_script( 'adminz_fotorama_js', plugin_dir_url(ADMINZ_BASENAME).'assets/fotorama/fotorama.js', array( 'jquery' ) );
},101 );

$fotorama_attributes = [
	'width'=>				['default'=> '', 			'type'=>'textfield', 	'heading'=> 'width',				'placeholder'=> ''],
	'minwidth'=>			['default'=> '', 			'type'=>'textfield', 	'heading'=> 'minwidth',				'placeholder'=> ''],
	'maxwidth'=>			['default'=> '', 			'type'=>'textfield', 	'heading'=> 'maxwidth',				'placeholder'=> ''],
	'height'=>				['default'=> '', 			'type'=>'textfield', 	'heading'=> 'height',				'placeholder'=> ''],
	'minheight'=>			['default'=> '', 			'type'=>'textfield', 	'heading'=> 'minheight',			'placeholder'=> ''],
	'maxheight'=>			['default'=> '', 			'type'=>'textfield', 	'heading'=> 'maxheight',			'placeholder'=> ''],
	'ratio'=>				['default'=> '', 			'type'=>'textfield', 	'heading'=> 'ratio',				'placeholder'=> '1/1'],
	'margin'=>				['default'=> '', 			'type'=>'textfield', 	'heading'=> 'margin',				'placeholder'=> ''],
	'glimpse'=>				['default'=> '', 			'type'=>'textfield', 	'heading'=> 'glimpse',				'placeholder'=> ''],
	'nav'=>					['default'=> 'thumbs',		'type'=>'textfield', 	'heading'=> 'nav',					'placeholder'=> ''],
	'navposition'=>			['default'=> '', 			'type'=>'textfield', 	'heading'=> 'navposition',			'placeholder'=> ''],
	'navwidth'=>			['default'=> '', 			'type'=>'textfield', 	'heading'=> 'navwidth',				'placeholder'=> ''],
	'thumbwidth'=>			['default'=> '', 			'type'=>'textfield', 	'heading'=> 'thumbwidth',			'placeholder'=> ''],
	'thumbheight'=>			['default'=> '64',			'type'=>'textfield', 	'heading'=> 'thumbheight',			'placeholder'=> ''],
	'thumbmargin'=>			['default'=> '', 			'type'=>'textfield', 	'heading'=> 'thumbmargin',			'placeholder'=> ''],
	'thumbborderwidth'=>	['default'=> '', 			'type'=>'textfield', 	'heading'=> 'thumbborderwidth',		'placeholder'=> ''],
	'allowfullscreen'=>		['default'=> 'true',		'type'=>'textfield', 	'heading'=> 'allowfullscreen',		'placeholder'=> ''],
	'fit'=>					['default'=> 'cover',		'type'=>'textfield', 	'heading'=> 'fit',					'placeholder'=> ''],
	'thumbfit'=>			['default'=> '', 			'type'=>'textfield', 	'heading'=> 'thumbfit',				'placeholder'=> ''],
	'transition'=>			['default'=> '', 			'type'=>'textfield', 	'heading'=> 'transition',			'placeholder'=> ''],
	'clicktransition'=>		['default'=> '', 			'type'=>'textfield', 	'heading'=> 'clicktransition',		'placeholder'=> ''],
	'transitionduration'=>	['default'=> '', 			'type'=>'textfield', 	'heading'=> 'transitionduration',	'placeholder'=> ''],
	'captions'=>			['default'=> '', 			'type'=>'textfield', 	'heading'=> 'captions',				'placeholder'=> ''],
	'hash'=>				['default'=> '', 			'type'=>'textfield', 	'heading'=> 'hash',					'placeholder'=> ''],
	'startindex'=>			['default'=> '', 			'type'=>'textfield', 	'heading'=> 'startindex',			'placeholder'=> ''],
	'loop'=>				['default'=> '', 			'type'=>'textfield', 	'heading'=> 'loop',					'placeholder'=> ''],
	'autoplay'=>			['default'=> '', 			'type'=>'textfield', 	'heading'=> 'autoplay',				'placeholder'=> ''],
	'stopautoplayontouch'=>	['default'=> '', 			'type'=>'textfield', 	'heading'=> 'stopautoplayontouch',	'placeholder'=> ''],
	'keyboard'=>			['default'=> '', 			'type'=>'textfield', 	'heading'=> 'keyboard',				'placeholder'=> ''],
	'arrows'=>				['default'=> '', 			'type'=>'textfield', 	'heading'=> 'arrows',				'placeholder'=> ''],
	'click'=>				['default'=> '', 			'type'=>'textfield', 	'heading'=> 'click',				'placeholder'=> ''],
	'swipe'=>				['default'=> '', 			'type'=>'textfield', 	'heading'=> 'swipe',				'placeholder'=> ''],
	'trackpad'=>			['default'=> '', 			'type'=>'textfield', 	'heading'=> 'trackpad',				'placeholder'=> ''],
	'shuffle'=>				['default'=> '', 			'type'=>'textfield', 	'heading'=> 'shuffle',				'placeholder'=> ''],
	'direction'=>			['default'=> '', 			'type'=>'textfield', 	'heading'=> 'direction',			'placeholder'=> ''],
	'spinner'=>				['default'=> '', 			'type'=>'textfield', 	'heading'=> 'spinner',				'placeholder'=> ''],
];	

add_action('ux_builder_setup', function () use ($fotorama_attributes){
	add_ux_builder_shortcode('adminz_fotorama', array(
		'info' => '{{ title }}',
        'name'      => __('Gallery Fotorama'),
        'category'  => Adminz::get_adminz_menu_title(),
        'thumbnail' =>  get_template_directory_uri() . '/inc/builder/shortcodes/thumbnails/' . 'slider.svg',
        'options' => array(
            'ids'             => array(
				'type'       => 'gallery',
				'heading'	=> __('Images'),
			),
			'note'=> array(
				'type'=>'group',
					'heading'=> 'JS Document',
				'description'=> "https://fotorama.io/docs/4/options/",
				'options' => $fotorama_attributes
			)
        ),
    ));
});

add_shortcode('adminz_fotorama', function ($atts) use ($fotorama_attributes){
		wp_enqueue_style( 'adminz_fotorama_css');		
		wp_enqueue_script( 'adminz_fotorama_js');				
		$defaultmap = ['ids'=>''];
		foreach ($fotorama_attributes as $key => $value) {
			$defaultmap[$key] = $value['default']; 
		}
		$map = shortcode_atts($defaultmap, $atts);
	    extract($map);
	    
    	

    	$thumbnail_size = apply_filters( 'woocommerce_gallery_image_size', 'full' );
    	if(is_array($thumbnail_size)){
    		if(!$map['ratio']){
	    		$map['ratio'] = $thumbnail_size[0]/$thumbnail_size[1]."/1";
    		}
    		if(!$map['thumbwidth']){
    			$map['thumbwidth'] = $map['thumbheight'] * ($thumbnail_size[0]/$thumbnail_size[1]);
    		}
    		
    	}

	    $datahtml = "";
	    foreach ($map as $key => $value) {
	    	if($value){
	    		$datahtml.= 'data-'.$key.'="'.$value.'" ';
	    	}
	    }
	    $ids = explode(',', $ids);
   
    	if(isset( $_POST['ux_builder_action'] )){
    		return wp_get_attachment_image($ids[0],'full',false, ['style'=>'display: inline-block; ']);
    	}    	
	    ob_start();
	    ?>    	    
		<div class="fotorama" <?php echo $datahtml; ?>>
			<?php 
			
			if(!empty($ids) and is_array($ids)){
				foreach ($ids as $key=>$id) {
					$fullimage = wp_get_attachment_image_src($id, 'full',false);
					$style= " ";
					//$style = ($key ==0)? " style='display: inline-block;' " : " ";					
					if(isset($fullimage[0])){
						echo '<div '.$style.' data-img="'.$fullimage[0].'"><a href="#"></a></div>';
					}
				}
			}
			?>
		</div>		
		<?php
	    return ob_get_clean();
	}
);
