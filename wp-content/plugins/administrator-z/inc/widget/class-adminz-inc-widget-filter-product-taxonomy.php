<?php 
namespace Adminz\Inc\Widget;
use WP_Widget;
use Adminz\Inc\Walker\ADMINZ_Inc_Walker_Filter_Product_Taxonomy;
use Adminz\Helper\ADMINZ_Helper_Woocommerce_Taxonomy;
use Adminz\Admin\ADMINZ_Woocommerce;
use Adminz\Admin\Adminz as Adminz;


class ADMINZ_Inc_Widget_Filter_Product_Taxonomy  extends WP_Widget {
	function __construct() {		
    	$widget_ops = [ 
	      	'classname' => 'adminz_woocommerce_filter_taxonomy', 
	      	'description' => __("A list or dropdown of product categories.",'woocommerce'). " search by title, price, custom taxonomy, rating star, product attributes, product tag.",
	      	'customize_selective_refresh' => true
	    	];
	    $control_ops = ['id_base' => 'adminz_woocommerce_filter_taxonomy' ];
	    $title = __("Filter products",'woocommerce'). " - NEW";
	    parent::__construct( 
	    	'adminz_woocommerce_filter_taxonomy', 
	    	$title,
	    	$widget_ops, 
	    	$control_ops 
	    );
	    add_action( 'widgets_init', function (){
	    	register_widget( $this );
	    } );
  	}
  	function form( $instance ) {  	
  		parent::form( $instance );	
  		$default = array(
			'title' => __("Product categories",'woocommerce'),
			'taxonomy'=> 'product_cat',
			'style' => 'list',
			'step' => '',
			'global_filter_price'=>'',
			'query_type'=>''
		);
		$instance = wp_parse_args( (array) $instance, $default);

		$title = esc_attr( $instance['title'] );
		$taxonomy = esc_attr( $instance['taxonomy'] );
		$style = esc_attr( $instance['style'] );
		$step = esc_attr( $instance['step']);
		$global_filter_price = esc_attr( $instance['global_filter_price']);
		$query_type = esc_attr( $instance['query_type']);

		
		?>
		<p>
			<?php echo __('Title',"woocommerce"); ?>
			<input type="text" name="<?php echo $this->get_field_name('title');?>" class="widefat" value="<?php echo $title; ?>">
		</p>
		<p>
			<?php 
				echo "Select taxonomy";
				$taxonomies = ADMINZ_Helper_Woocommerce_Taxonomy::lay_taxonomy_co_the_loc();
				if(!empty($taxonomies) and is_array($taxonomies)){
			?>
			<select name="<?php echo $this->get_field_name('taxonomy');?>" class="widefat">
			<?php			
			foreach ($taxonomies as $key => $value) {
		        $label = $value->labels->singular_name;
		        $label = ADMINZ_Helper_Woocommerce_Taxonomy::thay_doi_taxonomy_label($value);

		        $tax_arr[$value->name] =$label;
		        $selected = "";
		        if($value->name == $taxonomy){
		        	$selected = "selected";
		        }
		        echo '<option '.$selected.' value="'.$value->name.'">'.$label.'</option>';
		    }
			}else{
				echo ": <strong>Not found taxonomy</strong>";
			}
			?>
			</select>
		</p>
		<p>
			<?php echo __('Display type',"woocommerce"); ?>
			<select	name="<?php echo $this->get_field_name('style');?>" class="widefat">
				<option value="list" <?php if($style == 'list') echo 'selected'; ?>><?php echo __("List","woocommerce");?></option>
				<option value="dropdown" <?php if($style == 'dropdown') echo 'selected'; ?>><?php echo __("Dropdown","woocommerce");?></option>
				<option value="button" <?php if($style == 'button') echo 'selected'; ?>><?php echo __("Bulk select"). " ". __("Button text","woocommerce");?></option>
			</select>
		</p>	
		<p>
			<?php echo __('Filter by price','woocommerce'); ?>: Price steps
			<input type="number" name="<?php echo $this->get_field_name('step');?>" class="widefat" value="<?php echo $step; ?>">
			<div>				
				<label><input type="checkbox" name="<?php echo $this->get_field_name('global_filter_price');?>" class="widefat" <?php if($global_filter_price == "on") echo "checked"; ?>> Use global step | tool-> Administrator Z -> Woocommerce</label>
			</div>
		</p>
		<p>
			<?php echo __("Query type","woocommerce"); ?>: Attribute
			<select name="<?php echo $this->get_field_name('query_type');?>" class="widefat">
				<option <?php if($query_type == "") echo "selected"; ?> value=""><?php echo __("AND","woocommerce");?></option>
				<option <?php if($query_type == "or") echo "selected"; ?> value="or"><?php echo __("OR","woocommerce");?></option>
			</select>
		</p>	
		<?php
	}
	function update( $new_instance, $old_instance ) {
	    parent::update( $new_instance, $old_instance );
		$instance = $old_instance;

		$instance['title'] = strip_tags($new_instance['title']);
		$instance['taxonomy'] = strip_tags($new_instance['taxonomy']);
		$instance['style'] = strip_tags($new_instance['style']);
		$instance['step'] = strip_tags($new_instance['step']);
		$instance['global_filter_price'] = strip_tags($new_instance['global_filter_price']);
		$instance['query_type'] = strip_tags($new_instance['query_type']);

		return $instance;
  	}
  	function widget($args, $instance) {
  		
  		$taxonomy_hien_tai = ADMINZ_Helper_Woocommerce_Taxonomy::lay_toan_bo_taxonomy_hien_tai();  		
	    extract( $args );
	    if(empty($instance['title'])) $instance['title'] = __("Product categories",'woocommerce');
		$title = apply_filters('widget_title', $instance['title'] );
		$taxonomy = isset($instance['taxonomy'])? $instance['taxonomy'] : "product_cat";
		$style = isset($instance['style'])? $instance['style'] : "";
		$step = isset($instance['step']) ? $instance['step'] : 10;
		$global_filter_price = isset($instance['global_filter_price']) ? $instance['global_filter_price'] : '';
		$query_type = isset($instance['query_type']) ? $instance['query_type'] : "";


		ob_start();

		echo $before_widget;
		//In tiêu đề widget
		echo $before_title.$title.$after_title;
	    
		// Nội dung trong widget
		$id = "form_".rand();
		

		switch ($taxonomy){
			case "title":
				?>
				<form role="search" method="get" class="woocommerce-product-search searchform" action="<?php echo wc_get_page_permalink( 'shop' ); ?>">
					<div class="flex-row relative">
						<div class="flex-col ">
							<label class="screen-reader-text" for="woocommerce-product-search-field-<?php echo isset( $index ) ? absint( $index ) : 0; ?>"><?php esc_html_e( 'Search for:', 'woocommerce' ); ?></label>
							<input type="search" class="search-field" placeholder="<?php echo esc_attr__( 'Search products&hellip;', 'woocommerce' ); ?>" value="<?php echo get_search_query(); ?>" name="s" />
						</div>
						<div class="flex-col ">
							<button class="button primary icon" type="submit" value="<?php echo esc_attr_x( 'Search', 'submit button', 'woocommerce' ); ?>">	
								<?php 								
								echo Adminz::get_icon_html('search');
								 ?>
							</button>
						</div>
					</div>
					<?php ADMINZ_Helper_Woocommerce_Taxonomy::lay_input_hidden_form_taxonomy_hien_tai("s"); ?>					
				</form>
				<?php
			break;
			case "price":

				// copy from shortcode/woocommerce-form
				$price_range_2 = ADMINZ_Helper_Woocommerce_Taxonomy::get_price_range_2($global_filter_price);		
				array_unshift($price_range_2,["",""]);		
				switch ($style) {
					case 'dropdown':
						$taxonomy2 =  ADMINZ_Helper_Woocommerce_Taxonomy::thay_doi_term_slug_cho_link($taxonomy);
						?>
						<form method="get" action="<?php echo wc_get_page_permalink( 'shop' ); ?>" class="adminz_form_filter_prices" id="<?php echo $id; ?>"> 
							<select class="change_to_redirect">
								<option value="" >—— <?php echo __('Filter by price','woocommerce'); ?></option>
								<?php
                                    foreach ($price_range_2 as $key => $value) {                                    	
                                        echo ADMINZ_Helper_Woocommerce_Taxonomy::chuyen_doi_min_max_price_sang_dropdown($value,$taxonomy_hien_tai);  
                                    }
                                ?>
							</select>
							<?php ADMINZ_Helper_Woocommerce_Taxonomy::lay_input_hidden_form_taxonomy_hien_tai(); ?>
						</form>						
						<?php
						echo ADMINZ_Helper_Woocommerce_Taxonomy::script_change_to_submit($id);
						break;
					break;
					case 'button':
						?>
						<div class="tagcloud adminz_product_cat">
						<?php	

							foreach ($price_range_2 as $key => $value) {
								echo ADMINZ_Helper_Woocommerce_Taxonomy::chuyen_doi_min_max_price_sang_link_widget($value,$taxonomy_hien_tai); 
							}
		                ?>
		            	</div>
		                <?php
		                echo $this->lay_button_style_css();
					break;
					default:
						?> 
						<ul class="product-categories adminz_product_cat"> 
						<?php						
						foreach ($price_range_2 as $key => $value) {
							echo ADMINZ_Helper_Woocommerce_Taxonomy::chuyen_doi_min_max_price_sang_menu_widget($value,$taxonomy_hien_tai);
						}
						?>
						</ul>
						<?php
					break;
				}
			break;
			default:

				switch ($style) {
					case 'dropdown':
						$taxonomy2 =  ADMINZ_Helper_Woocommerce_Taxonomy::thay_doi_term_slug_cho_link($taxonomy);			
									
						?>
						<form method="get" action="<?php echo wc_get_page_permalink( 'shop' ); ?>" class="adminz_form_filter_taxonomy" id="<?php echo $id; ?>"> 
							<?php
							$get_terms = get_terms($taxonomy);
							$categoryHierarchy = array();
							if(is_array($get_terms)){
				                ADMINZ_Helper_Woocommerce_Taxonomy::sap_xep_lai_cha_con($get_terms, $categoryHierarchy);
				            }

							$taxobj = get_taxonomy($taxonomy);
							$label = ADMINZ_Helper_Woocommerce_Taxonomy::thay_doi_taxonomy_label($taxobj);

						    $data_query_type = "";						    
						    if(substr($taxonomy,0,3) == "pa_"){
						    	$data_query_type= 'data-query_type="query_type_'.str_replace("pa_","",$taxonomy).'"';
						    }

							?>
							<select class="change_to_redirect" <?php echo $data_query_type;?> data-taxonomy="<?php echo $taxonomy2;?>" data-query_value="<?php echo $query_type;?>">
								<option value="" >—— <?php echo $label;?></option>
								<?php 
		                        if(!empty($categoryHierarchy) and is_array($categoryHierarchy)){
		                            foreach ($categoryHierarchy as $key => $term) {
		                                echo ADMINZ_Helper_Woocommerce_Taxonomy::chuyen_doi_term_option_select($term,$taxonomy2," ");
		                            }
		                        }
		                        ?>
							</select>
							<?php 
							 ?>
							<?php ADMINZ_Helper_Woocommerce_Taxonomy::lay_input_hidden_form_taxonomy_hien_tai(); ?>
						</form>
						<?php
						echo ADMINZ_Helper_Woocommerce_Taxonomy::script_change_to_submit($id);
						break;
					case 'button':
						?>
						<div class="tagcloud adminz_product_cat">
						<?php
						$get_terms = get_terms($taxonomy);  
						
						if(!empty($get_terms) and is_array($get_terms)){
		                    foreach ($get_terms as $key => $term) {
		                    	echo ADMINZ_Helper_Woocommerce_Taxonomy::chuyen_doi_term_sang_link_widget($term,$taxonomy,$query_type);	
		                    }
		                }
		                ?>		               
		            	</div>
		                <?php
		                echo $this->lay_button_style_css();
						break;
					default:
						$get_current_taxonomy_and_ancestors = ADMINZ_Helper_Woocommerce_Taxonomy::lay_taxonomy_hien_tai($taxonomy);
						$current_category = $get_current_taxonomy_and_ancestors[0];
						$current_category_ancestors = $get_current_taxonomy_and_ancestors[1];	

						$taxonomy2 =  ADMINZ_Helper_Woocommerce_Taxonomy::thay_doi_term_slug_cho_link($taxonomy);
						

						$list_args['taxonomy'] 					= $taxonomy;
						$list_args['walker']                     = new ADMINZ_Inc_Walker_Filter_Product_Taxonomy($taxonomy2,$query_type);
						$list_args['title_li']                   = '';
						$list_args['pad_counts']                 = false;
						$list_args['show_option_none']           = __( 'No product categories exist.', 'woocommerce' );
						$list_args['current_category'] 			= $current_category;
						$list_args['current_category_ancestors'] = $current_category_ancestors;
						$list_args['max_depth']                  = 0;

						echo '<ul class="product-categories adminz_product_cat">';
						wp_list_categories( apply_filters( 'woocommerce_product_categories_widget_args', $list_args ) );
						echo '</ul>';

						break;
				}
			break;
		}		
		?>
		<style type="text/css">
			.adminz_product_cat li:not(.current-cat) .woocommerce-Price-amount{font-weight: normal; color:  inherit;}

		</style>
		<?php
		// Kết thúc nội dung trong widget
		echo $after_widget;

		echo apply_filters('adminz_output_debug',ob_get_clean());
  	}

  	function lay_button_style_css(){
  		ob_start();
  		?>
		 <style type="text/css"> 
		 .adminz_product_cat.tagcloud a *{
		 	color:  inherit;
		 	font-weight: normal;
		 }
		.adminz_product_cat.tagcloud a.active, 
		.adminz_product_cat.tagcloud a.active *, 
		.adminz_product_cat.tagcloud a:hover,
		.adminz_product_cat.tagcloud a:hover *{
			opacity:  1; 
			background: var(--primary-color) !important; 
			color: white;
		} </style>
		<?php
  		return ob_get_clean();
  	}
}