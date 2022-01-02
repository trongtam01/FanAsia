<?php 
use Adminz\Admin\ADMINZ_Flatsome;
if(!isset(ADMINZ_Flatsome::$options['adminz_enable_zalo_support']) or ADMINZ_Flatsome::$options['adminz_enable_zalo_support'] !=="on" ) {
	return ;
}
use Adminz\Admin\Adminz as Adminz;

/*1.add zalo top header top*/
add_action('customize_register',function ( $wp_customize ) {
	$wp_customize->add_setting(
      	'follow_zalo', array('default' => 'https://zalo.me/#')
  	);
    $wp_customize->add_control('follow_zalo', array(
        'label'      => __('Zalo', 'adminz'),
        'section'    => 'follow', 
    ));
    $wp_customize->add_setting(
      	'follow_skype', array('default' => '#')
  	);
    $wp_customize->add_control('follow_skype', array(
        'label'      => __('Skype', 'adminz'),
        'section'    => 'follow', 
    ));
});
/*2.create new uxbuilder for zalo  */
add_action('ux_builder_setup', function(){
    add_ux_builder_shortcode('adminz_follow', array(
        'name'      => __('Follows (Zalo)'),
        'category'  => Adminz::get_adminz_menu_title(),
        'thumbnail' =>  get_template_directory_uri() . '/inc/builder/shortcodes/thumbnails/' . 'share' . '.svg',
        'options' => array(
            'title' => array(
	            'type' => 'textfield',
	            'heading' => 'Title',
	            'default' => '',
	        ),

	        'style' => array(
	            'type' => 'radio-buttons',
	            'heading' => __( 'Style' ),
	            'default' => 'outline',
	            'options' => array(
	                'outline' => array( 'title' => 'Outline' ),
	                'fill' => array( 'title' => 'Fill' ),
	                'small' => array( 'title' => 'Small' ),
	            ),
	        ),
	        'align' => array(
	            'type' => 'radio-buttons',
	            'heading' => __( 'Align' ),
	            'default' => '',
	            'options' => array(
	                '' => array( 'title' => 'Inline' ),
	                'left'   => array( 'title' => 'Left',   'icon' => 'dashicons-editor-alignleft'),
	                'center' => array( 'title' => 'Center', 'icon' => 'dashicons-editor-aligncenter'),
	                'right'  => array( 'title' => 'Right',  'icon' => 'dashicons-editor-alignright'),
	            ),
	        ),
	        'scale' => array(
	            'type' => 'slider',
	            'heading' => 'Scale',
	            'default' => '100',
	            'unit' => '%',
	            'max' => '300',
	            'min' => '50',
	        ),
	        'zalo' => array( 'type' => 'textfield','heading' => 'Zalo', 'default' => ''),
	        'skype' => array( 'type' => 'textfield','heading' => 'Skype', 'default' => ''),
	        'facebook' => array( 'type' => 'textfield','heading' => 'Facebook', 'default' => ''),
	        'instagram' => array( 'type' => 'textfield','heading' => 'Instagram', 'default' => ''),
	        'tiktok' => array( 'type' => 'textfield','heading' => 'TikTok', 'default' => ''),
	        'snapchat' => array( 'type' => 'image', 'heading' => __( 'SnapChat' )),
	        'twitter' => array( 'type' => 'textfield','heading' => 'Twitter', 'default' => ''),
	        'linkedin' => array( 'type' => 'textfield','heading' => 'Linkedin', 'default' => ''),
	        'email' => array( 'type' => 'textfield','heading' => 'Email', 'default' => ''),
	        'phone' => array( 'type' => 'textfield','heading' => 'Phone', 'default' => ''),
	        'pinterest' => array( 'type' => 'textfield','heading' => 'Pinterest', 'default' => ''),
	        'rss' => array( 'type' => 'textfield','heading' => 'RSS', 'default' => ''),
	        'youtube' => array( 'type' => 'textfield','heading' => 'Youtube', 'default' => ''),
	        'flickr' => array( 'type' => 'textfield','heading' => 'Flickr', 'default' => ''),
	        'vkontakte' => array( 'type' => 'textfield','heading' => 'VKontakte', 'default' => ''),
	        'px500' => array( 'type' => 'textfield','heading' => '500px', 'default' => ''),
	        'telegram' => array( 'type' => 'textfield','heading' => 'Telegram', 'default' => ''),
	        'discord' => array( 'type' => 'textfield','heading' => 'Discord', 'default' => ''),
			'twitch' => array( 'type' => 'textfield','heading' => 'Twitch', 'default' => ''),
	        'advanced_options' => require( get_template_directory()."/inc/builder/shortcodes/commons/advanced.php"),
        ),
    ));
});
add_shortcode('adminz_follow', 'adminz_follow_function');
/*3.customize function follow*/
add_action( 'init', function() {remove_shortcode( 'follow' ); },20 );
add_action( 'init', function(){
	add_shortcode('follow','adminz_follow_function');
	function adminz_follow_function($atts, $content = null) {
		extract(shortcode_atts(array(
			'title' => '',
			'class' => '',
			'visibility' => '',
			'style' => 'outline',
			'align' => '',
			'scale' => '',
			'twitter' => '',
			'facebook' => '',
			'pinterest' => '',
			'email' => '',
			'phone' => '',
			'instagram' => '',
			'tiktok' => '',
			'rss' => '',
			'linkedin' => '',
			'youtube' => '',
			'flickr' => '',
			'vkontakte' => '',
			'px500' => '',
			'telegram' => '',
			'twitch' => '',
			'discord' => '',
			'snapchat' => '',
			'zalo'=>'',
			'skype'=>'',
			// Depricated
			'size' => '',

		), $atts));
		ob_start();
		

		$wrapper_class = array('social-icons','follow-icons');
		if( $class ) $wrapper_class[] = $class;
		if( $visibility ) $wrapper_class[] = $visibility;
		if( $align ) {
			$wrapper_class[] = 'full-width';
			$wrapper_class[] = 'text-'.$align;
		}

		// Use global follow links if non is set individually.
		$has_custom_link = $twitter || $facebook || $instagram || $tiktok || $snapchat || $youtube || $pinterest || $linkedin || $px500 || $vkontakte || $telegram || $flickr || $email || $phone || $rss || $twitch || $discord;

		if ( ! $has_custom_link ) {
			$twitter   = get_theme_mod( 'follow_twitter' );
			$facebook  = get_theme_mod( 'follow_facebook' );
			$instagram = get_theme_mod( 'follow_instagram' );
			$tiktok    = get_theme_mod( 'follow_tiktok' );
			$snapchat  = get_theme_mod( 'follow_snapchat' );
			$youtube   = get_theme_mod( 'follow_youtube' );
			$pinterest = get_theme_mod( 'follow_pinterest' );
			$linkedin  = get_theme_mod( 'follow_linkedin' );
			$px500     = get_theme_mod( 'follow_500px' );
			$vkontakte = get_theme_mod( 'follow_vk' );
			$telegram  = get_theme_mod( 'follow_telegram' );
			$flickr    = get_theme_mod( 'follow_flickr' );
			$email     = get_theme_mod( 'follow_email' );
			$phone     = get_theme_mod( 'follow_phone' );
			$rss       = get_theme_mod( 'follow_rss' );
			$twitch    = get_theme_mod( 'follow_twitch' );
			$discord   = get_theme_mod( 'follow_discord' );
			$zalo      = get_theme_mod( 'follow_zalo' );
			$skype 	   = get_theme_mod( 'follow_skype' );
		}	
		if($size == 'small') $style = 'small';
		$style = get_flatsome_icon_class($style);

		// Scale
		if($scale) $scale = 'style="font-size:'.$scale.'%"';


		?>
	    <div class="<?php echo implode(' ', $wrapper_class); ?>" <?php echo $scale;?>>
	    	<?php if($title){?>
	    	<span><?php echo $title; ?></span>
			<?php }?>
			<?php if($zalo){?>
				<a href="<?php echo $zalo; ?>" target="_blank" data-label="Zalo" rel="noopener noreferrer nofollow" class="<?php echo $style; ?> zalo tooltip" title="<?php _e('Zalo','flatsome') ?>" aria-label="<?php esc_attr_e( 'Zalo', 'flatsome' ); ?>"><i class="icon-"><?php				
				echo Adminz::get_icon_html('zalo',['style'=>['width'=>'1.2em;','margin-bottom'=>'-2px','fill'=>'currentColor']]);
				?></i>
	    	</a>
			<?php }?>
	    	<?php if($facebook){?>
	    	<a href="<?php echo $facebook; ?>" target="_blank" data-label="Facebook" rel="noopener noreferrer nofollow" class="<?php echo $style; ?> facebook tooltip" title="<?php _e('Follow on Facebook','flatsome') ?>" aria-label="<?php esc_attr_e( 'Follow on Facebook', 'flatsome' ); ?>"><?php echo get_flatsome_icon('icon-facebook'); ?>
	    	</a>
			<?php }?>
			<?php if($instagram){?>
			    <a href="<?php echo $instagram; ?>" target="_blank" rel="noopener noreferrer nofollow" data-label="Instagram" class="<?php echo $style; ?>  instagram tooltip" title="<?php _e('Follow on Instagram','flatsome')?>" aria-label="<?php esc_attr_e( 'Follow on Instagram', 'flatsome' ); ?>"><?php echo get_flatsome_icon('icon-instagram'); ?>
			   </a>
			<?php }?>
		    <?php if ( $tiktok ) { ?>
			    <a href="<?php echo $tiktok; ?>" target="_blank" rel="noopener noreferrer nofollow" data-label="TikTok" class="<?php echo $style; ?> tiktok tooltip" title="<?php _e( 'Follow on TikTok', 'flatsome' ) ?>" aria-label="<?php esc_attr_e( 'Follow on TikTok', 'flatsome' ); ?>"><?php echo get_flatsome_icon( 'icon-tiktok' ); ?>
			    </a>
		    <?php } ?>
		    <?php if($skype){?>
				<a href="<?php echo $skype; ?>" target="_blank" data-label="Skype" rel="noopener noreferrer nofollow" class="<?php echo $style; ?> skype tooltip" title="<?php _e('Skype','flatsome') ?>" aria-label="<?php esc_attr_e( 'Skype', 'flatsome' ); ?>"><i class="icon-"><?php				
				echo Adminz::get_icon_html('skype',['style'=>['width'=>'1.2em;','margin-bottom'=>'-2px','fill'=>'currentColor']]);
				?></i>
	    	</a>
			<?php }?>
			<?php if($snapchat){?>
			    <a href="#" data-open="#follow-snapchat-lightbox" data-color="dark" data-pos="center" target="_blank" rel="noopener noreferrer nofollow" data-label="SnapChat" class="<?php echo $style; ?> snapchat tooltip" title="<?php _e('Follow on SnapChat','flatsome')?>" aria-label="<?php esc_attr_e( 'Follow on SnapChat', 'flatsome' ); ?>"><?php echo get_flatsome_icon('icon-snapchat'); ?>
			   </a>
			   <div id="follow-snapchat-lightbox" class="mfp-hide">
			   		<div class="text-center">
				   		<?php echo do_shortcode(flatsome_get_image($snapchat)) ;?>
				   		<p><?php _e('Point the SnapChat camera at this to add us to SnapChat.','flatsome'); ?></p>
			   		</div>
			   </div>
			<?php }?>
			<?php if($twitter){?>
		       <a href="<?php echo $twitter; ?>" target="_blank" data-label="Twitter" rel="noopener noreferrer nofollow" class="<?php echo $style; ?>  twitter tooltip" title="<?php _e('Follow on Twitter','flatsome') ?>" aria-label="<?php esc_attr_e( 'Follow on Twitter', 'flatsome' ); ?>"><?php echo get_flatsome_icon('icon-twitter'); ?>
		       </a>
			<?php }?>
			<?php if($email){?>
			     <a href="mailto:<?php echo $email; ?>" data-label="E-mail" rel="nofollow" class="<?php echo $style; ?>  email tooltip" title="<?php _e('Send us an email','flatsome') ?>" aria-label="<?php esc_attr_e( 'Send us an email', 'flatsome' ); ?>"><?php echo get_flatsome_icon('icon-envelop'); ?>
				</a>
			<?php }?>
		    <?php if($phone){?>
				<a href="tel:<?php echo $phone; ?>" target="_blank" data-label="Phone" rel="noopener noreferrer nofollow" class="<?php echo $style; ?>  phone tooltip" title="<?php _e('Call us','flatsome') ?>" aria-label="<?php esc_attr_e( 'Call us', 'flatsome' ); ?>"><?php echo get_flatsome_icon('icon-phone'); ?>
				</a>
		    <?php }?>
			<?php if($pinterest){?>
			       <a href="<?php echo $pinterest; ?>" target="_blank" rel="noopener noreferrer nofollow"  data-label="Pinterest"  class="<?php echo $style; ?>  pinterest tooltip" title="<?php _e('Follow on Pinterest','flatsome') ?>" aria-label="<?php esc_attr_e( 'Follow on Pinterest', 'flatsome' ); ?>"><?php echo get_flatsome_icon('icon-pinterest'); ?>
			       </a>
			<?php }?>
			<?php if($rss){?>
			       <a href="<?php echo $rss; ?>" target="_blank" rel="noopener noreferrer nofollow" data-label="RSS Feed" class="<?php echo $style; ?>  rss tooltip" title="<?php _e('Subscribe to RSS','flatsome') ?>" aria-label="<?php esc_attr_e( 'Subscribe to RSS', 'flatsome' ); ?>"><?php echo get_flatsome_icon('icon-feed'); ?></a>
			<?php }?>
			<?php if($linkedin){?>
			       <a href="<?php echo $linkedin; ?>" target="_blank" rel="noopener noreferrer nofollow" data-label="LinkedIn" class="<?php echo $style; ?>  linkedin tooltip" title="<?php _e('Follow on LinkedIn','flatsome') ?>" aria-label="<?php esc_attr_e( 'Follow on LinkedIn', 'flatsome' ); ?>"><?php echo get_flatsome_icon('icon-linkedin'); ?></a>
			<?php }?>
			<?php if($youtube){?>
			       <a href="<?php echo $youtube; ?>" target="_blank" rel="noopener noreferrer nofollow" data-label="YouTube" class="<?php echo $style; ?>  youtube tooltip" title="<?php _e('Follow on YouTube','flatsome') ?>" aria-label="<?php esc_attr_e( 'Follow on YouTube', 'flatsome' ); ?>"><?php echo get_flatsome_icon('icon-youtube'); ?>
			       </a>
			<?php }?>
			<?php if($flickr){?>
			       <a href="<?php echo $flickr; ?>" target="_blank" rel="noopener noreferrer nofollow" data-label="Flickr" class="<?php echo $style; ?>  flickr tooltip" title="<?php _e('Flickr','flatsome') ?>" aria-label="<?php esc_attr_e( 'Flickr', 'flatsome' ); ?>"><?php echo get_flatsome_icon('icon-flickr'); ?>
			       </a>
			<?php }?>
			<?php if($px500){?>
			     <a href="<?php echo $px500; ?>" target="_blank"  data-label="500px"  rel="noopener noreferrer nofollow" class="<?php echo $style; ?> px500 tooltip" title="<?php _e('Follow on 500px','flatsome') ?>" aria-label="<?php esc_attr_e( 'Follow on 500px', 'flatsome' ); ?>"><?php echo get_flatsome_icon('icon-500px'); ?>
				</a>
			<?php }?>
			<?php if($vkontakte){?>
			       <a href="<?php echo $vkontakte; ?>" target="_blank" data-label="VKontakte" rel="noopener noreferrer nofollow" class="<?php echo $style; ?> vk tooltip" title="<?php _e('Follow on VKontakte','flatsome') ?>" aria-label="<?php esc_attr_e( 'Follow on VKontakte', 'flatsome' ); ?>"><?php echo get_flatsome_icon('icon-vk'); ?>
			       </a>
			<?php }?>
			<?php if($telegram){?>
				<a href="<?php echo $telegram; ?>" target="_blank" data-label="Telegram" rel="noopener noreferrer nofollow" class="<?php echo $style; ?> telegram tooltip" title="<?php _e('Follow on Telegram','flatsome') ?>" aria-label="<?php esc_attr_e( 'Follow on Telegram', 'flatsome' ); ?>"><?php echo get_flatsome_icon('icon-telegram'); ?>
				</a>
			<?php }?>
			<?php if($twitch){?>
				<a href="<?php echo $twitch; ?>" target="_blank" data-label="Twitch" rel="noopener noreferrer nofollow" class="<?php echo $style; ?> twitch tooltip" title="<?php _e('Follow on Twitch','flatsome') ?>" aria-label="<?php esc_attr_e( 'Follow on Twitch', 'flatsome' ); ?>"><?php echo get_flatsome_icon('icon-twitch'); ?>
				</a>
			<?php }?>
			<?php if($discord){?>
				<a href="<?php echo $discord; ?>" target="_blank" data-label="Discord" rel="noopener noreferrer nofollow" class="<?php echo $style; ?> discord tooltip" title="<?php _e('Follow on Discord','flatsome') ?>" aria-label="<?php esc_attr_e( 'Follow on Discord', 'flatsome' ); ?>"><?php echo get_flatsome_icon('icon-discord'); ?>
				</a>
			<?php }?>
	     </div>

		<?php
		$content = ob_get_contents();
		ob_end_clean();
		$content = flatsome_sanitize_whitespace_chars( $content);
		return $content;
	};
},21);