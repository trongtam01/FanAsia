<?php 
use Adminz\Admin\Adminz as Adminz;
add_action('ux_builder_setup', function(){
	add_ux_builder_shortcode('adminz_video', array(
        'name'      => __('HTML5 Video'),
        'category'  => Adminz::get_adminz_menu_title(),
        'inline' => true,
        'info'      => '{{ text }}',
        'thumbnail' =>  get_template_directory_uri() . '/inc/builder/shortcodes/thumbnails/' . 'ux_video' . '.svg',
        'options' => array(
    	  	'url'             => array(
				'type'       => 'textfield',				
				'heading'    => 'Video Url',
				'default'    => '',
				'auto_focus' => true,
			),    
			'type'             => array(
				'type'       => 'select',				
				'heading'    => 'Type',
				'default'    => 'video/mp4',
				'options'=>[
					'video/mp4'=>'video/mp4',
					'viveo/webm'=>'viveo/webm',
					'video/ogg'=>'video/ogg',
				]
			), 
			'crossorigin'             => array(
				'type'       => 'select',				
				'heading'    => 'Crossorigin',
				'default'    => '',
				'options'=>[
					''=>'Default',
					'anonymous'=>'anonymous',
					'use-credentials'=>'use-credentials',
				]
			), 
			'preload'             => array(
				'type'       => 'select',				
				'heading'    => 'Preload',
				'default'    => '',
				'options'=>[
					''=>'Default',
					'metadata'=>'metadata',
					'auto'=>'auto',
					'none'=>'none'
				]
			),
			'width'             => array(
				'type'       => 'textfield',				
				'heading'    => 'Width',
				'default'    => '100%',
			), 
			'height'             => array(
				'type'       => 'textfield',				
				'heading'    => 'Height',
				'default'    => '',
			), 
			'autoplay'             => array(
				'type'       => 'checkbox',				
				'heading'    => 'Autoplay',
				'default'    => 'true',
			), 
			'autopictureinpicture'             => array(
				'type'       => 'checkbox',				
				'heading'    => 'Auto picture in picture',
				'default'    => '',
			), 
			'loop'             => array(
				'type'       => 'checkbox',				
				'heading'    => 'Loop',
				'default'    => 'true',
			),
			'playsinline'             => array(
				'type'       => 'checkbox',				
				'heading'    => 'Playinline',
				'default'    => 'true',
			),
			'muted'             => array(
				'type'       => 'checkbox',				
				'heading'    => 'Muted',
				'default'    => 'true',
			),
			'controls'             => array(
				'type'       => 'checkbox',				
				'heading'    => 'Controls',
				'default'    => '',
			),
			'class'             => array(
				'type'       => 'textfield',				
				'heading'    => 'Classes',
				'default'    => 'video-background',
			),     
			'visibility'  => array(
			    'type' => 'select',
			    'heading' => 'Visibility',
			    'default' => '',
			    'options' => array(
			        ''   => 'Visible',
			        'hidden'  => 'Hidden',
			        'hide-for-medium'  => 'Only for Desktop',
			        'show-for-small'   =>  'Only for Mobile',
			        'show-for-medium hide-for-small' =>  'Only for Tablet',
			        'show-for-medium'   =>  'Hide for Desktop',
			        'hide-for-small'   =>  'Hide for Mobile',
			    ),
			),
        ),
    ));
});
add_shortcode('adminz_video', function($atts){	
	extract(shortcode_atts(array(
		'url' 	=>'',
		'type' 	=>'video/mp4',
		'width' 	=>'100%',
		'height'=>'',
		'autoplay' 	=>"true",
		'autopictureinpicture'=>'',
		'loop' 	=>"true",
		'muted'=> "true",
		'class' 	=>'video-background',
		'controls'=> "",
		'crossorigin'=>'',
		'preload'=>'',
		'playsinline'=>'',
		'visibility'=>''
    ), $atts));    
    if($visibility){
		$class.= " ".$visibility;
	}
    ob_start();

    ?>
    <div class="adminz_video_wrapper">
    <video 
    	width="<?php if($width) echo $width;?>" 
    	height="<?php if($height) echo $height;?>" 
    	<?php if($crossorigin) echo 'crossorigin="'.$crossorigin.'"' ;?> 
    	<?php if($preload) echo 'preload="'.$preload.'"' ;?> 
    	<?php if($autoplay) echo "autoplay";?> 
    	<?php if($autopictureinpicture) echo "autopictureinpicture";?> 
    	<?php if($loop) echo "loop"; ?> 
    	<?php if($muted) echo "muted"; ?> 
    	<?php if($playsinline) echo "playsinline"; ?> 
    	<?php if($controls) echo 'controls';?> 
    	class="<?php echo $class;?>">
		<source src="<?php echo $url;?>" type="<?php echo $type;?>">
	</video>
	</div>
    <?php
    return apply_filters('adminz_output_debug',ob_get_clean());
	
});
