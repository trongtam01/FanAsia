<?php

if (!defined('ABSPATH'))
  exit;

if (!class_exists('WPACPTDCF7_Post')) {

    class WPACPTDCF7_Post {

        protected static $WPACPTDCF7_instance;

        public static function WPACPTDCF7_instance() {
          if (!isset(self::$WPACPTDCF7_instance)) {
            self::$WPACPTDCF7_instance = new self();
            self::$WPACPTDCF7_instance->init();
          }
          return self::$WPACPTDCF7_instance;
        }

        function init() {
            add_action( 'wpcf7_init',  array($this, 'wpacptdcf7_add_shortcode_post'));
            add_filter( 'wpcf7_validate_posts', array($this, 'wpacptdcf7_posts_validation_filter'), 10, 2 );
            add_filter( 'wpcf7_validate_posts*', array($this, 'wpacptdcf7_posts_validation_filter'), 10, 2 );
        }

        function wpacptdcf7_add_shortcode_post() {
            wpcf7_add_form_tag( array( 'posts', 'posts*' ),
                array($this, 'wpacptdcf7_posts_shortcode_handler'), true );
        }

        function wpacptdcf7_posts_shortcode_handler( $tag ) {
            $tag = new WPCF7_FormTag( $tag );
            if ( empty( $tag->name ) )
                return '';

            $validation_error = wpcf7_get_validation_error( $tag->name );

            $class = wpcf7_form_controls_class( $tag->type );

            if ( $validation_error )
                $class .= ' wpcf7-not-valid';

            $atts = array();

            $atts['class'] = $tag->get_class_option( $class );
            $atts['id'] = $tag->get_id_option();
            $atts['tabindex'] = $tag->get_option( 'tabindex', 'int', true );

            if ( $tag->is_required() )
                $atts['aria-required'] = 'true';

            $atts['aria-invalid'] = $validation_error ? 'true' : 'false';

            $multiple = $tag->has_option( 'multiple' );
            $include_blank = !$multiple ? $tag->has_option( 'include_blank' ) : false;
            $first_as_label = $tag->has_option( 'first_as_label' );
            $enable_search_box = $tag->has_option( 'enable_search_box' );
            $atts['allow-clear_post'] = $include_blank ? 'true' : 'false';
            //$atts['search-box_post'] = $enable_search_box ? 'true' : 'false';
           
            // Get Filter Option 
            $post_type = $tag->get_option( 'post_type' )[0];
            $orderby = $tag->get_option( 'orderby' )[0];
            $sortorder = $tag->get_option( 'sortorder' )[0];

            //Content Option
            $post_show_content = $tag->has_option( 'show_content' );
            if(!empty($tag->get_option( 'content_limit' )[0])){
                $post_content_limit = $tag->get_option( 'content_limit' )[0];
               
            }else{
                $post_content_limit =15;
            }            
            $post_content_limit_size = !$post_content_limit ? 15 : $post_content_limit;

            //Image Option
            $post_image_width =  80;
            

            if($post_type === 'post' && !empty($tag->get_option( 'post_category' )[0])){
                $wpacptdcf7_posts = get_posts( array(
                'post_type' => $post_type,
                'post_status' => 'publish',
                'numberposts' => -1,
                'orderby'=> $orderby,
                'order' => $sortorder,
                'category_name' => $tag->get_option( 'post_category' )[0],
                ) );
            } elseif($post_type === 'product'){
                $wpacptdcf7_posts = get_posts( array(
                'post_type' => $post_type,
                'post_status' => 'publish',
                'numberposts' => -1,
                'orderby'=> $orderby,
                'order' => $sortorder,
                ) );
            } elseif($post_type === 'post' && !empty($tag->get_option( 'post_tag' )[0])){
                $wpacptdcf7_posts = get_posts( array(
                'post_type' => $post_type,
                'post_status' => 'publish',
                'numberposts' => -1,
                'orderby'=> $orderby,
                'order' => $sortorder,
                'tag' => $tag->get_option( 'post_tag' )[0],
                ) );
            } elseif($post_type === 'page'){
                $wpacptdcf7_posts = get_posts( array(
                'post_type' => $post_type,
                'post_status' => 'publish',
                'numberposts' => -1,
                'orderby'=> $orderby,
                'order' => $sortorder,
                ) );
            } else if($post_type === 'attachment'){
                $wpacptdcf7_posts = get_posts( array(
                'post_type' => $post_type,
                'post_status' => 'any',
                'posts_per_page' => -1,
                'orderby'=> $orderby,
                'order' => $sortorder,
                ) );
            } else {
                $wpacptdcf7_posts = get_posts( array(
                'post_type' => $post_type,
                'post_status' => 'publish',
                'numberposts' => -1,
                'orderby'=> $orderby,
                'order' => $sortorder
                ) );
            }

            
            // Display product options
            $post_othervalue = array();
            $values = array();
            $selectedmvalue = array();
            
            foreach ( $wpacptdcf7_posts as $posts ) {
                
                // Set posts title
                if($posts->post_type == 'product' && is_plugin_active( 'woocommerce/woocommerce.php' ) ){
                    $product = wc_get_product( $posts->ID );
                    if(!empty($product->get_price())) {
                        $content = wc_price($product->get_price());
                    } else {
                         $content = '';
                    }

                } else {
                    if(has_excerpt($posts->ID)) {
                        $content = get_the_excerpt($posts->ID);
                    } else {
                        $content = get_post_field('post_content', $posts->ID);
                    }
                }
                if(($posts->post_type == 'post' || $posts->post_type == 'product' || $posts->post_type == 'attachment' || $posts->post_type == 'page') && !empty($posts->post_title)) {
                    if($posts->post_type == 'attachment'){
                        $image_attributes = wp_get_attachment_image_src( $posts->ID,'thumbnail');
                        $imagedata = $image_attributes[0];
                    } else {
                        $imagedata = get_the_post_thumbnail_url($posts->ID, 'thumbnail');
                    }

                    $selectedmvalue=array(
                                    'title' => $posts->post_title,
                                    'post_id' => $posts->ID,
                                    'image_width'=> $post_image_width,
                                    'types' => $posts->post_type);
                    
                    
                    if(!empty($imagedata)) {
                        $selectedmvalue['image_url'] =  $imagedata;
                    }else {
                        $selectedmvalue['image_url'] =  CF7WPAPLOC_PLUGIN_DIR .'/images/placeholder.jpg';
                    }
                    if($post_show_content){
                       $selectedmvalue['content'] =  $content; 
                    }
                    

                    $post_othervalue[] = $selectedmvalue;
                    $values[] = $posts->post_title;
                }else{
                    $imagedata = get_the_post_thumbnail_url($posts->ID, 'thumbnail');
                    $selectedmvalue=array(
                                    'title' => $posts->post_title,
                                    'post_id' => $posts->ID,
                                    'image_width'=> $post_image_width,
                                    'types' => $posts->post_type);
                    
                    
                    if(!empty($imagedata)) {
                        $selectedmvalue['image_url'] =  $imagedata;
                    }else {
                        $selectedmvalue['image_url'] =  CF7WPAPLOC_PLUGIN_DIR .'/images/placeholder.jpg';
                    }
                    if($post_show_content){
                       $selectedmvalue['content'] =  $content; 
                    }
                    

                    $post_othervalue[] = $selectedmvalue;
                    $values[] = $posts->post_title;
                }
            }

            $values = $values;
            $labels = array_values( $values );

            $shifted = false;
            $placeholder = apply_filters('wpcf7_'.$tag->name.'_placeholder', __('&mdash; Select &mdash;'), $tag->get_option('post-type', '', true), $tag);
            $html = '';
            $hangover = wpcf7_get_hangover( $tag->name );

            foreach ( $post_othervalue as $key => $value ) {
                $selected = false;
                if ($include_blank && $shifted == false) {
                    $item_empty = array(
                    'value' => '',
                    'data-id'=> -1,
                    'data-types' => '',
                    'data-image' => '',
                    'data-width' => '',
                    'data-content' => '',
                    'data-meta' => '',
                    'selected' => $selected ? 'selected' : '' );
                    
                    $item_empty = wpcf7_format_atts( $item_empty );

                    $label = $placeholder;

                    $html .= sprintf( '<option %1$s>%2$s</option>',
                        $item_empty, esc_html( $label ) );
                    $shifted = true;

                }

                if ( $hangover ) {
                    if ( $multiple ) {
                        $selected = in_array( esc_sql( $value['title'] ), (array) $hangover );
                    } else {
                        $selected = ( $hangover == esc_sql( $value['title'] ) );
                    }
                } else {
                    $defaults = array();
                    if ( ! $shifted && in_array( (int) $key + 1, (array) $defaults ) ) {
                        $selected = true;
                    } elseif ( $shifted && in_array( (int) $key, (array) $defaults ) ) {
                        $selected = true;
                    }
                }

                $defult_atts = array(
                'value' => $value['title'],
                'data-id'=> $value['post_id'],
                'data-types' => $value['types'],
                'data-width' => $value['image_width'],
                'selected' => $selected ? 'selected' : '' ); 
                if($value['image_url']){
                    $defult_atts['data-image'] =  $value['image_url'];
                }
                if(isset($value['content'])){
                    $defult_atts['data-content'] =  wp_trim_words( $value['content'], $post_content_limit_size);
                } 
                if(!empty($value['meta_data'])){
                    $defult_atts['data-meta'] = implode('|', $value['meta_data']);
                }
                $item_atts = $defult_atts;

                $item_atts = wpcf7_format_atts( $item_atts );

                $label = isset( $labels[$key] ) ? $labels[$key] : $value;

                $html .= sprintf( '<option %1$s>%2$s</option>',
                    $item_atts, esc_html( $label ) );
            }

            // if ( $multiple )
            //     $atts['multiple'] = 'multiple';

            $atts['placeholder'] = $placeholder;
            $atts['name'] = $tag->name . ( $multiple ? '[]' : '' );
            $atts = wpcf7_format_atts( $atts );

            $html = sprintf(
                '<span class="wpcf7-form-control-wrap %1$s"><select %2$s>%3$s</select>%4$s</span>',
                sanitize_html_class( $tag->name ), $atts, $html, $validation_error );
            if($post_type == 'product' && !is_plugin_active( 'woocommerce/woocommerce.php' )) {
                return '';
            } else {
            return $html;
            }
        }

        function wpacptdcf7_posts_validation_filter( $result, $tag ) {
            $tag = new WPCF7_FormTag( $tag );

            $name = $tag->name;

            if ( isset( $_POST[$name] ) && is_array( $_POST[$name] ) ) {
                foreach ( $_POST[$name] as $key => $value ) {
                    if ( '' === $value )
                        unset( $_POST[$name][$key] );
                }
            }

            $empty = ! isset( $_POST[$name] ) || empty( $_POST[$name] ) && '0' !== $_POST[$name];

            if ( $tag->is_required() && $empty ) {
                $result->invalidate( $tag, wpcf7_get_message( 'invalid_required' ) );
            }

            return $result;
        }

        
    }
    WPACPTDCF7_Post::WPACPTDCF7_instance();
}

