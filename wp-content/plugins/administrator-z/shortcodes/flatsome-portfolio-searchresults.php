<?php 
use Adminz\Admin\Adminz as Adminz;
use Adminz\Helper\ADMINZ_Helper_Custom_Portfolio as ADMINZ_Helper_Custom_Portfolio;
use Adminz\Admin\ADMINZ_Flatsome as ADMINZ_Flatsome;

// builder
add_action('ux_builder_setup', function (){
    $repeater_col_spacing = 'normal';
    $repeater_columns = '4';
    $repeater_type = 'slider'; 
    $default_text_align = "left";
    $repeater_col_spacing = "normal";   
    $options = array(
    'portfolio_meta' => array(
        'type' => 'group',
        'heading' => __( 'Appearance' ),
        'options' => array(
            'style' => array(
                'type' => 'select',
                'heading' => __( 'Style' ),
                'default' => 'bounce',
                'options' => require( get_template_directory().'/inc/builder/shortcodes/values/box-layouts.php' )
            ),

            'lightbox' => array(
                'type' => 'radio-buttons',
                'heading' => __('Lightbox'),
                'default' => '',
                'options' => array(
                    ''  => array( 'title' => 'Off'),
                    'true'  => array( 'title' => 'On'),
                ),
            ),

            'lightbox_image_size' => array(
                'type'       => 'select',
                'heading'    => __( 'Lightbox Image Size' ),
                'conditions' => 'lightbox == "true"',
                'default'    => '',
                'options'    => array(
                    ''          => 'Default',
                    'large'     => 'Large',
                    'medium'    => 'Medium',
                    'thumbnail' => 'Thumbnail',
                    'original'  => 'Original',
                )
            ),

            'posts_per_page' => array(
                'type' => 'textfield',
                'heading' => 'Posts per page',
                //'conditions' => 'ids == ""',
                'default' => get_option('posts_per_page'),
            ),
            'offset' => array(
                'type' => 'textfield',
                'heading' => 'Offset',
                //'conditions' => 'ids == ""',
                'default' => '',
            ),

            'orderby' => array(
                'type' => 'select',
                'heading' => __( 'Order By' ),
                'default' => 'menu_order',
                //'conditions' => 'ids == ""',
                'options' => array(
                    'title' => 'Title',
                    'name' => 'Name',
                    'date' => 'Date',
                    'menu_order' => 'Menu Order',
                )
            ),
            'order' => array(
                'type' => 'select',
                'heading' => __( 'Order' ),
                //'conditions' => 'ids == ""',
                'default' => 'desc',
                'options' => array(
                  'desc' => 'DESC',
                  'asc' => 'ASC',
                )
            ),            
        ),
    ),
    'portfolio_tax' => array(
        'type' => 'group',
        'heading' => __( 'Filter Taxonomies' ),
        'options' => array(
            'cat' => array(
                'type' => 'select',
                'heading' => 'Fixed Category',
                //'conditions' => 'ids == ""',            
                'param_name' => 'slug',
                'config' => array(
                    'multiple'=> true,
                    'placeholder' => 'Select..',
                    'termSelect' => array(
                        'post_type' => 'featured_item',
                        'taxonomies' => 'featured_item_category'
                    ),
                )
            ),
            'tag' => array(
                'type' => 'select',
                'heading' => 'Fixed tag',
                //'conditions' => 'ids == ""',            
                'param_name' => 'slug',
                'config' => array(
                    'multiple'=> true,
                    'placeholder' => 'Select..',
                    'termSelect' => array(
                        'post_type' => 'featured_item',
                        'taxonomies' => 'featured_item_tag'
                    ),
                )
            )
        )
    ),
    'portfolio_metakey' => array(
        'type' => 'group',
        'heading' => __( 'Filter Meta Value' ),
        'options' => array()
    ),
    'layout_options' => require( get_template_directory().'/inc/builder/shortcodes/commons/repeater-options.php' ),
    'layout_options_slider' => require( get_template_directory().'/inc/builder/shortcodes/commons/repeater-slider.php' ),
    );

    $custom_tax = ADMINZ_Helper_Custom_Portfolio::get_featured_custom_tax(['featured_item_category','featured_item_tag']);
    if(!empty($custom_tax) and is_array($custom_tax)){
        foreach ($custom_tax as $key => $value) {
            $options['portfolio_tax']['options'][$key] = array(
                'type' => 'select',
                'heading' => "Fixed ". $value,
                'default'=>'',
                //'conditions' => 'ids == ""',
                'config' => array(
                    'placeholder' => 'Select..',
                    'termSelect' => array(
                        'post_type' => 'featured_item',
                        'taxonomies' => $key
                    ),
                )
            );
        }        
    }

    $meta_key_builder = ADMINZ_Helper_Custom_Portfolio::get_list_meta_key_builder('featured_item');
    $list_all = $meta_key_builder['list_all'];
    $list_metakey = $meta_key_builder['list_metakey'];

    if(!empty($list_metakey)){
        $options['portfolio_metakey']['options']['fixed_metakey'] = [
            'type' => 'select',
            'heading' => "Fixed Meta key",
            //'conditions' => 'ids == ""',
            'options'=> $list_metakey
        ];
    }
    if(!empty($list_all)){
        foreach($list_all as $metakey=> $values){            
            $options['portfolio_metakey']['options']['fixed_metakey_'.$metakey] = [
                'type' => 'select',
                'heading' => "Fixed: ".$metakey,
                'conditions' => 'fixed_metakey == "'.$metakey.'"',
                'options'=> $values
            ];
        }            
    }

    $subtitle_alt = array_merge(
        ADMINZ_Flatsome::get_arr_tax(),
        ADMINZ_Flatsome::get_arr_meta_key(),
        ['product_count'=>'product_count']
    );
    $options['portfolio_meta']['options']['before_title'] = [
        'type' => 'select',
        'param_name' => 'slug',
        'heading' => 'Before title',                        
        'config' => array(
            'multiple' => true,
            'placeholder' => 'Select..',
            'options' => $subtitle_alt
        ),
    ];
    $options['portfolio_meta']['options']['after_title'] = [
        'type' => 'select',
        'param_name' => 'slug',
        'heading' => 'After title',                        
        'config' => array(
            'multiple' => true,
            'placeholder' => 'Select..',
            'options' => $subtitle_alt
        ),
    ];
    $box_styles = require( get_template_directory().'/inc/builder/shortcodes/commons/box-styles.php' );
    $options = array_merge($options, $box_styles);

    $advanced = array('advanced_options' => require( get_template_directory().'/inc/builder/shortcodes/commons/advanced.php'));
    $options = array_merge($options, $advanced);

    add_ux_builder_shortcode('adminz_flatsome_portfolios_search_result', array(
        'name'      => "Custom ". ADMINZ_Helper_Custom_Portfolio::$customname,
        'category'  => Adminz::get_adminz_menu_title(),
        'thumbnail' =>  get_template_directory_uri() . '/inc/builder/shortcodes/thumbnails/' . 'portfolio' . '.svg',
        'scripts' => array(
            'flatsome-masonry-js' => get_template_directory_uri() .'/assets/libs/packery.pkgd.min.js',
            'flatsome-isotope-js' => get_template_directory_uri() .'/assets/libs/isotope.pkgd.min.js',
        ),
        'info'      => '{{ id }}',
        'options' => $options
    ));
});




// content shortcode
add_shortcode('adminz_flatsome_portfolios_search_result', function ($atts, $content = null, $tag = '' ) {        
    $default_atts = array(
        'filter' => '',
        'filter_nav' => 'line-grow',
        'filter_align' => 'center',
        '_id' => 'portfolio-'.rand(),
        'link' => '',
        'class' => '',
        'visibility' => '',
        'orderby' => 'menu_order',
        'order' => '',
        'offset' => '',
        'exclude' => '',
        'number'  => '999',
        'ids' => '',
        'cat' => '',
        'tag'=> '',
        'lightbox' => '',
        'lightbox_image_size' => 'original',
        'posts_per_page'=> get_option('posts_per_page'),
        'before_title'=> '',
        'after_title'=>'',
        // Layout
        'style' => '',
        'columns' => '4',
        'columns__sm' => '',
        'columns__md' => '',
        'col_spacing' => 'small',
        'type' => 'slider', // slider, row, masonery, grid
        'width' => '',
        'grid' => '1',
        'grid_height' => '600px',
        'grid_height__md' => '500px',
        'grid_height__sm' => '400px',
        'slider_nav_style' => 'reveal',
        'slider_nav_position' => '',
        'slider_nav_color' => '',
        'slider_bullets' => 'false',
        'slider_arrows' => 'true',
        'auto_slide' => 'false',
        'infinitive' => 'true',
        'depth' => '',
        'depth_hover' => '',

         // Box styles
        'animate' => '',
        'text_pos' => '',
        'text_padding' => '',
        'text_bg' => '',
        'text_color' => '',
        'text_hover' => '',
        'text_align' => 'left',
        'text_size' => '',
        'image_size' => 'medium',
        'image_mask' => '',
        'image_width' => '',
        'image_radius' => '',
        'image_height' => '100%',
        'image_hover' => '',
        'image_hover_alt' => '',
        'image_overlay' => '',

        // Deprecated
        'height' => '',
    );
    ob_start();    


    $custom_tax = ADMINZ_Helper_Custom_Portfolio::get_featured_custom_tax(['featured_item_category','featured_item_tag']);
    if(!empty($custom_tax) and is_array($custom_tax)){
        foreach ($custom_tax as $key => $value) {
            $default_atts[$key] = "";
        }        
    }

    $meta_key_builder = ADMINZ_Helper_Custom_Portfolio::get_list_meta_key_builder('featured_item');
    $list_all = $meta_key_builder['list_all'];
    $list_metakey = $meta_key_builder['list_metakey'];
    if(!empty($list_metakey)){
        $default_atts['fixed_metakey'] = '';
    }
    if(!empty($list_all)){
        foreach($list_all as $metakey=> $values){
            $default_atts['fixed_metakey_'.$metakey] = '';
        }            
    }
    

    extract(shortcode_atts($default_atts, $atts));

    $taxonomies = get_object_taxonomies( 'featured_item', 'objects' );    
    $tax_arr = [];
    if(!empty($taxonomies) and is_array($taxonomies)){
        foreach ($taxonomies as $key => $value) {
            $tax_arr[] = $key;
        }
    }
    $meta_keys = ADMINZ_Flatsome::adminz_get_all_meta_keys('featured_item');
    $key_arr = [];
    if(!empty($meta_keys) and is_array($meta_keys)){
        foreach ($meta_keys as $value) {
            if($value){
                $key_arr[] = $value;
            }            
        }
    }  
    $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1; 
    if(isset($_GET['zpaged'])){$paged = $_GET['zpaged']; }

    $args = [
        'post_type'=> ['featured_item'],    
        'posts_per_page' => get_option( 'posts_per_page' ),
        'paged' => $paged,
        'tax_query' => [
            'relation'=> 'AND',            
        ],
        'meta_query' => [
            'relation'=> 'AND',            
        ],
    ];    
    
    if(!empty($_GET) and is_array($_GET)){        
        foreach ($_GET as $key => $value) {
            if(in_array($key,$tax_arr)){
                $args['tax_query'][] = [
                    'taxonomy' => $key,
                    'field' => 'slug',
                    'terms' => explode(",",$value),
                    'include_children' => true,
                    'operator' => 'IN'
                ];
            }
            if(in_array($key,$key_arr)){
                $args['meta_query'][] = [
                    'key' => $key,
                    'value' => $value,
                    'type' => 'CHAR',                                                    
                    'compare' => '='
                ];
            }
            if($key == "search"){
                $args['s'] = $value;
            }
        }
    }   
    if($order){
        $args['order'] = $order;
    }
    if($orderby){
        $args['orderby'] = $orderby;
    }
    if($offset){
        $args['offset'] = $offset;
    }
    if($posts_per_page){
        $args['posts_per_page'] = $posts_per_page;
    }
    if($cat){
        $args['tax_query'][] = [
            'taxonomy' => "featured_item_category",
            'field' => 'id',
            'terms' => explode(",",$cat),
            'include_children' => true,
            'operator' => 'IN'
        ];
    }
    if($tag){
        $args['tax_query'][] = [
            'taxonomy' => "featured_item_tag",
            'field' => 'id',
            'terms' => explode(",",$tag),
            'include_children' => true,
            'operator' => 'IN'
        ];
    }
    if(!empty($custom_tax) and is_array($custom_tax)){
        foreach ($custom_tax as $key => $value) {
            
            if($$key){
                $args['tax_query'][] = [
                    'taxonomy' => $key,
                    'field' => 'id',
                    'terms' => explode(",",$$key),
                    'include_children' => true,
                    'operator' => 'IN'
                ];
            }
        }        
    }
    if(isset($fixed_metakey)){
        $metavalue = isset(${'fixed_metakey__'.$fixed_metakey})? ${'fixed_metakey__'.$fixed_metakey} : false;
        if($metavalue){
            $args['meta_query'][] = [
                'key'=> $fixed_metakey,
                'value'=>$metavalue,
                'compare'=> '='
            ];
        }
    }
    
    if($before_title){
        $before_title = explode(',',$before_title);        
    }
    if($after_title){
        $after_title = explode(',',$after_title);        
    }






    if($height && !$image_height) $image_height = $height;

      // Get Default Theme style
      if(!$style) $style = flatsome_option('portfolio_style');

      // Fix old
      if($tag == 'featured_items_slider') $type = 'slider';

        // Set Classes.
        $wrapper_class = array( 'portfolio-element-wrapper', 'has-filtering' );
        $classes_box   = array( 'portfolio-box', 'box', 'has-hover' );
        $classes_image = array();
        $classes_text  = array( 'box-text' );

      // Fix Grid type
      if($type == 'grid'){
        $columns = 0;
        $current_grid = 0;
        $grid = flatsome_get_grid($grid);
        $grid_total = count($grid);
        flatsome_get_grid_height($grid_height, $_id);
      }

        // Wrapper classes.
        if ( $visibility ) $wrapper_class[] = $visibility;

      // Set box style
      if($style) $classes_box[] = 'box-'.$style;
      if($style == 'overlay') $classes_box[] = 'dark';
      if($style == 'shade') $classes_box[] = 'dark';
      if($style == 'badge') $classes_box[] = 'hover-dark';
      if($text_pos) $classes_box[] = 'box-text-'.$text_pos;
      if($style == 'overlay' && !$image_overlay) $image_overlay = true;

      // Set image styles
      if($image_hover)  $classes_image[] = 'image-'.$image_hover;
      if($image_hover_alt)  $classes_image[] = 'image-'.$image_hover_alt;
      if($image_height)  $classes_image[] = 'image-cover';

      // Text classes
      if($text_hover) $classes_text[] = 'show-on-hover hover-'.$text_hover;
      if($text_align) $classes_text[] = 'text-'.$text_align;
      if($text_size) $classes_text[] = 'is-'.$text_size;
      if($text_color == 'dark') $classes_text[] = 'dark';

      $css_col = array(
        array( 'attribute' => 'border-radius', 'value' => $image_radius, 'unit' => '%'),
      );

      $css_args_img = array(
        array( 'attribute' => 'border-radius', 'value' => $image_radius, 'unit' => '%'),
        array( 'attribute' => 'width', 'value' => $image_width, 'unit' => '%' ),
      );

      $css_image_height = array(
        array( 'attribute' => 'padding-top', 'value' => $image_height),
      );

      $css_args = array(
            array( 'attribute' => 'background-color', 'value' => $text_bg ),
            array( 'attribute' => 'padding', 'value' => $text_padding ),
      );


     if($animate) {$animate = 'data-animate="'.$animate.'"';}

     echo '<div id="' . $_id . '" class="' . implode( ' ', $wrapper_class ) . '">';

     // Add filter
     if($filter && $filter != 'disabled' && empty($cat) && $type !== 'grid' && $type !== 'slider' && $type !== 'full-slider'){
      // TODO: Get categories for filtering.
      wp_enqueue_script('flatsome-isotope-js');
      ?>
      <div class="container mb-half">
      <ul class="nav nav-<?php echo $filter;?> nav-<?php echo $filter_align ;?> nav-<?php echo $filter_nav;?> nav-uppercase filter-nav">
        <li class="active"><a href="#" data-filter="*"><?php echo __('All','flatsome'); ?></a></li>
        <?php
          $tax_terms = get_terms('featured_item_category');
          foreach ($tax_terms as $key => $value) {
             ?><li><a href="#" data-filter="[data-terms*='<?php echo "&quot;" . $value->name . "&quot;"; ?>']"><?php echo $value->name; ?></a></li><?php
          }
        ?>
      </ul>
      </div>
      <?php
    } else{
      $filter = false;
    }

    // Repeater options
    $repeater['id'] = $_id;
    $repeater['tag'] = $tag;
    $repeater['type'] = $type;
    $repeater['style'] = $style;
    $repeater['class'] = $class;
    $repeater['visibility'] = $visibility;
    $repeater['slider_style'] = $slider_nav_style;
    $repeater['slider_nav_color'] = $slider_nav_color;
    $repeater['slider_nav_position'] = $slider_nav_position;
    $repeater['slider_bullets'] = $slider_bullets;
    $repeater['auto_slide'] = $auto_slide;
    $repeater['infinitive'] = $infinitive;
    $repeater['row_spacing'] = $col_spacing;
    $repeater['row_width'] = $width;
    $repeater['columns'] = $columns;
    $repeater['columns__sm'] = $columns__sm;
    $repeater['columns__md'] = $columns__md;
    $repeater['depth'] = $depth;
    $repeater['depth_hover'] = $depth_hover;
    $repeater['filter'] = $filter;
    









    $the_query = new WP_Query($args);
    // Get repeater structure
    get_flatsome_repeater_start($repeater);
    
    if ( $the_query->have_posts() ) : ?>     
        
        <?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
        <?php 
            $link = get_permalink(get_the_ID());

            $has_lightbox = '';
            if($lightbox == 'true'){
                $link = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), $lightbox_image_size );
                $link = $link[0];
                $has_lightbox = 'lightbox-gallery';
            }

            $image = get_post_thumbnail_id();
            $classes_col = array('col');

            // Add Columns for Grid style
            if($type == 'grid'){
                if($grid_total > $current_grid) $current_grid++;
                $current = $current_grid-1;

                $classes_col[] = 'grid-col';
                if($grid[$current]['height']) $classes_col[] = 'grid-col-'.$grid[$current]['height'];

                if($grid[$current]['span']) $classes_col[] = 'large-'.$grid[$current]['span'];
                if($grid[$current]['md']) $classes_col[] = 'medium-'.$grid[$current]['md'];

                // Set image size
                if($grid[$current]['size']) $image_size = $grid[$current]['size'];
            }

            ?>
            <?php         
            $data_term = strip_tags( get_the_term_list( get_the_ID(), 'featured_item_category', "[&quot;", "&quot;,&quot;", "&quot;]" ) );
            $after_title_text = strip_tags( get_the_term_list( get_the_ID(), 'featured_item_category', "",", " ) );
            $before_title_text = "";

            $get_arr_tax = ADMINZ_Flatsome::get_arr_tax();
            $get_arr_meta_key = ADMINZ_Flatsome::get_arr_meta_key();            


            // before title 
            $before_title_text = ADMINZ_Helper_Custom_Portfolio::change_sub_title(
                get_the_ID(), 
                $before_title_text, 
                $before_title, 
                $get_arr_tax, 
                $get_arr_meta_key
            );

            $after_title_text = ADMINZ_Helper_Custom_Portfolio::change_sub_title(
                get_the_ID(), 
                $after_title_text, 
                $after_title, 
                $get_arr_tax, 
                $get_arr_meta_key
            );

            ?>
            <div class="<?php echo implode(' ', $classes_col); ?>" data-terms="<?php echo $data_term; ?>" <?php echo $animate; ?>>
                <div class="col-inner" <?php echo get_shortcode_inline_css($css_col); ?>>
                    <a href="<?php echo $link; ?>" class="plain <?php echo $has_lightbox; ?>">
                        <div class="<?php echo implode(' ', $classes_box); ?>">
                            <div class="box-image" <?php echo get_shortcode_inline_css( $css_args_img ); ?>>
                                <div class="<?php echo implode(' ', $classes_image); ?>" <?php echo get_shortcode_inline_css($css_image_height); ?>>
                                <?php echo wp_get_attachment_image($image, $image_size); ?>
                                <?php if($image_overlay) { ?>
                                    <div class="overlay" style="background-color:<?php echo $image_overlay; ?>"></div>
                                <?php } ?>
                                <?php if($style == 'shade'){ ?>
                                    <div class="shade"></div>
                                <?php } ?>
                                </div>
                            </div>
                            <div class="<?php echo implode(' ', $classes_text); ?>" <?php echo get_shortcode_inline_css( $css_args ); ?>>
                                <div class="box-text-inner">
                                    <p  class="uppercase portfolio-box-category is-xsmall op-6">
                                        <span>
                                            <?php echo $before_title_text; ?>
                                        </span>
                                    </p>                                    
                                    <h6 class="uppercase portfolio-box-title"><?php the_title(); ?></h6>
                                    <p class="uppercase portfolio-box-category is-xsmall op-6">
                                        <span class="show-on-hover">
                                            <?php  echo $after_title_text;?>
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>          

            <?php endwhile; ?>
        <?php wp_reset_postdata(); ?>   
    







    <?php 
    get_flatsome_repeater_end($repeater);
    echo '</div>';
    $args = array(
    'image_width' => array(
      'selector' => '.box-image',
      'property' => 'width',
      'unit' => '%',
    ),
    'text_padding' => array(
      'selector' => '.box-text',
      'property' => 'padding',
    ),
    );
    echo ux_builder_element_style_tag($_id, $args, $atts);
    ?>








    <?php else : ?>
        <p><?php _e( 'Nothing Found','flatsome' ); ?></p>
    <?php endif;









    // paged - copied from flatsome/ inc/ structure/ structure-posts.php
    $prev_arrow = is_rtl() ? get_flatsome_icon('icon-angle-right') : get_flatsome_icon('icon-angle-left');
    $next_arrow = is_rtl() ? get_flatsome_icon('icon-angle-left') : get_flatsome_icon('icon-angle-right');
    $total = $the_query->max_num_pages;    
    if( $total > 1 )  {
        if( !$current_page = get_query_var('paged') )
            $current_page = 1;
        if( get_option('permalink_structure') ) {
            $format = 'page/%#%/';
        } else {
             $format = '&paged=%#%';
        }
        $pages = paginate_links(array(
            'base'          => '%_%',
            'format'        => '?zpaged=%#%',
            'current'       => max( 1, $paged ),
            'total'         => $total,
            'mid_size'      => 3,
            'type'          => 'array',
            'prev_text'     => $prev_arrow,
            'next_text'     => $next_arrow,
        ) );        
        if( is_array( $pages ) ) {            
            echo '<ul class="page-numbers nav-pagination links text-center">';
            foreach ( $pages as $page ) {
                $page = str_replace("page-numbers","page-number",$page);
                echo "<li>$page</li>";
            }
           echo '</ul>';
        }
    }
    return ob_get_clean();
    
});
