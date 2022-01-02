<?php 
namespace Adminz\Helper;
use Adminz\Admin\ADMINZ_Woocommerce;

class ADMINZ_Helper_Woocommerce_Taxonomy{
	static $taxonomy_hien_tai;
	static $list_taxonomy_option;

	function __construct() {		
		if(!class_exists( 'WooCommerce' ) ) return;
		/*
		-- chuyen den function tuong ung
		self::$taxonomy_hien_tai = self::lay_toan_bo_taxonomy_hien_tai();		
		self::$list_taxonomy_option = self::lay_taxonomy_co_the_loc();*/
	}
	static function bo_loc_term_taxonomy($term,$taxonomy){
		// Chỉ cho phép: rated-1, rated-2 rated-3, rated-4, rated-5
		if(in_array($taxonomy,['product_visibility','rating_filter'])){
			if(!in_array($term->slug,['rated-1','rated-2','rated-3','rated-4','rated-5'])){
				return false;
			} }
		return true;		
	}
	static function lay_taxonomy_co_the_loc(){
		$taxonomies = get_object_taxonomies( 'product', 'objects' );
		unset($taxonomies['product_shipping_class']);
		unset($taxonomies['product_type']);
		
    	$taxonomies['title'] = (object) NULL; 
    	$taxonomies['title']->name = "title"; 
    	$taxonomies['title']->labels = (object) NULL;
    	$taxonomies['title']->labels->singular_name = 'Type to search';

    	$taxonomies['price'] = (object) NULL; 
    	$taxonomies['price']->name = "price"; 
    	$taxonomies['price']->labels = (object) NULL;
    	$taxonomies['price']->labels->singular_name = __('Filter by price','woocommerce');
    	
		return $taxonomies;
	}
	static function lay_toan_bo_taxonomy_hien_tai(){		
		$arr_queried = [];
		$get_queried_object = get_queried_object();
		if(!empty($get_queried_object) and isset($get_queried_object->taxonomy)){
			$arr_queried[self::thay_doi_term_slug_cho_link($get_queried_object->taxonomy)] = $get_queried_object->slug;
		}		
		$arr_request = [];
		if(!empty($_REQUEST) and is_array($_REQUEST)){
			foreach ($_REQUEST as $key => $value) {
				if($value){
					$arr_request[$key] = $value;
				}
			}
		}		
		$return = []; 
		$return = $arr_queried;
		if(!empty($arr_request) and is_array($arr_request)){
			foreach ($arr_request as $key => $value) {
				if(array_key_exists($key, $arr_queried)){
					$temp = explode(",",$value);
					$temp[] = $arr_queried[$key];
					$temp = array_unique($temp);
					$value = implode(",",$temp);				
				}
				$return[$key] = $value;
			}
		}
		if(!isset($return['post_type'])) $return['post_type'] = 'product';		
		return $return;
	}
	static function lay_taxonomy_hien_tai($taxonomy){
		$current_taxonomy = [];
		$current_taxonomy_ancestors = [];

		if(isset($_GET[$taxonomy]) and $_GET[$taxonomy]){
			$product_get_arr = explode(",",$_GET[$taxonomy]);
			foreach ($product_get_arr as $key => $value) {		
				$termobj = get_term_by( 'slug', $value,$taxonomy );
				$current_taxonomy[] = $termobj->term_id;				
				if(isset(get_ancestors($termobj->term_id,$taxonomy)[0])){
					$current_taxonomy_ancestors[] = get_ancestors($termobj->term_id,$taxonomy)[0];
				}				
			}
		}else{
			if(isset(get_queried_object()->term_id)){
				$current_taxonomy = get_queried_object()->term_id;
			}
			if($current_taxonomy){
				$current_taxonomy_ancestors = get_ancestors( $current_taxonomy, $taxonomy );
			}
		}
		return [$current_taxonomy,$current_taxonomy_ancestors];
	}	
	static function co_phai_term_hien_tai($term,$taxonomy){
		$taxonomy_hien_tai = self::lay_toan_bo_taxonomy_hien_tai();
		if(!isset($taxonomy_hien_tai[$taxonomy])) return false;
		if(!in_array($term,explode(",",$taxonomy_hien_tai[$taxonomy]))) return false; 
		return true;
	}
	static function lay_gia_tri_taxonomy_hien_tai($taxonomy){    		
	    if(isset(self::lay_toan_bo_taxonomy_hien_tai()[$taxonomy])){
	    	return self::lay_toan_bo_taxonomy_hien_tai()[$taxonomy];
	    }
	    return "";
	}
	static function thay_doi_gia_tri_term_slug($slug){
		if(in_array($slug, ['rated-1','rated-2','rated-3','rated-4','rated-5'])){
            return str_replace('rated-', '', $slug);
        }
        return $slug;
	}
	// hàm này thay đổi slug cho taxonomy theo giá trị term slug
	static function thay_taxonomy_slug_by_term_value($taxonomy,$current_term){
		if(strlen($current_term) == 1 ) {
            $taxonomy = "rating_filter";
        }
		return $taxonomy;
	}
	// hàm này thay đổi slug cho taxonomy để fix pa thành filter
	static function thay_doi_term_slug_cho_link($slug){
		if($slug == 'product_visibility') {
			$slug = 'rating_filter';
		}
		if(substr($slug, 0,3) == "pa_"){
            $slug = str_replace("pa_", "filter_", $slug);
        }
        return $slug;
	}	
	static function thay_doi_term_name($termname){
	    $transition_arr = [
	        'rated-1' => '1 star',
	        'rated-2' => '2 stars',
	        'rated-3' => '3 stars',
	        'rated-4' => '4 stars',
	        'rated-5' => '5 stars',
	    ];
	    foreach ($transition_arr as $key => $value) {
	        if($termname == $key){
	            $termname = $value;
	        }
	    }
	    return $termname;
	}
	static function thay_doi_taxonomy_label($taxobj){
		if($taxobj->name == 'product_visibility'){
            //return __('Visibility','woocommerce');
            return __('Product ratings','woocommerce');
        }
        if($taxobj->name == 'product_type'){
            return __('Product Type','woocommerce');
        }
        if($taxobj->name == 'product_tag'){
            return $taxobj->labels->name; 
        }
        if($taxobj->name == 'product_cat'){
            return __('Product categories','woocommerce');
        }
        if($taxobj->name == 'rating_filter'){
            return __('Product ratings','woocommerce');
        }
        return $taxobj->labels->singular_name;
	}
	static function chuyen_doi_term_sang_button_form($term,$taxonomy){
		if(!self::bo_loc_term_taxonomy($term,$taxonomy)){return ;}
		ob_start();
		$taxonomy2 = self::thay_doi_term_slug_cho_link($taxonomy);
		$termslug = self::thay_doi_gia_tri_term_slug($term->slug);
		
        /* check is rated number */
        $taxonomy2 = self::thay_taxonomy_slug_by_term_value($taxonomy2,$termslug);

        $active = "";
        if(self::co_phai_term_hien_tai($termslug,$taxonomy2)){
            $active = "active";
        }                                                

        ?>
        <label data-value="<?php echo $termslug;?>" data-tax="<?php echo $taxonomy2;?>" class="<?php echo $active;?>"> 
        	<?php echo self::thay_doi_term_name($term->name); ?>
        </label>
        <?php
        return ob_get_clean();
	}	
	static function chuyen_doi_term_option_select($term,$taxonomy, $i){
    	if(!self::bo_loc_term_taxonomy($term,$taxonomy)){return ;}
	    ob_start();    
	    $termname = $term->name;
	    $termname = self::thay_doi_term_name($termname);

	    $termslug = $term->slug;
	    $termslug = self::thay_doi_gia_tri_term_slug($termslug);

	    $selected = "";
	    $taxonomy = self::thay_doi_term_slug_cho_link($taxonomy);
	    $taxonomy = self::thay_taxonomy_slug_by_term_value($taxonomy,$termslug);



	    if(self::co_phai_term_hien_tai($termslug,$taxonomy)){
	    	$selected = "selected";
	    }
	    
    	echo '<option '.$selected.' value="'.$termslug.'" data-taxonomy="'.$taxonomy.'">'.$i." ".$termname.'</option>';
	    
	    
	    if(!empty($term->children) and is_array($term->children)){
	        $i .= "—";
	        foreach ($term->children as $key => $value) {
	            echo self::chuyen_doi_term_option_select($value,$taxonomy, $i);
	        }        
	    }
	    return ob_get_clean();
	}		
	static function chuyen_doi_term_sang_link_widget($term,$taxonomy,$query_type=""){		
		if(!self::bo_loc_term_taxonomy($term,$taxonomy)){return ;}
		ob_start();
		$taxonomy2 = self::thay_doi_term_slug_cho_link($taxonomy); 
        $termslug = self::thay_doi_gia_tri_term_slug($term->slug);

        /* check is rated number */
        $taxonomy2 = self::thay_taxonomy_slug_by_term_value($taxonomy2,$termslug);

        $active = "";
        if(self::co_phai_term_hien_tai($termslug,$taxonomy2)){
            $active = "active";
        }        
        $links = self::lay_link_term_widget($termslug,$taxonomy2,$query_type);        
        ?>
        <div style="display:inline-block; position: relative;">
        	
            <a href="<?php echo $links; ?>" class="<?php echo $active;?>"> 
            	<?php echo self::thay_doi_term_name($term->name); ?>
            </a>
        </div>
        <?php

    	return ob_get_clean();
	}
	static function chuyen_doi_min_max_price_sang_link_widget($value,$taxonomy_hien_tai){
		ob_start();		
		if(!isset($taxonomy_hien_tai['min_price'])) $taxonomy_hien_tai['min_price'] = 0;
		$active = "";
		if(
			(
				isset($taxonomy_hien_tai['min_price']) and $taxonomy_hien_tai['min_price'] == $value[0]) and 
				isset($taxonomy_hien_tai['max_price']) and ($taxonomy_hien_tai['max_price'] == $value[1]
			)or
			(
				!isset($taxonomy_hien_tai['max_price']) and !$value[1] and isset($taxonomy_hien_tai['min_price']) and $taxonomy_hien_tai['min_price'] == $value[0]
			)
		){
			$active = "active";
		}
		$links = self::lay_link_price_widget($value);

		
        if($value[0] == $value[1]){
            $text = __("All", 'woocommerce');
        }elseif(isset($value[2])){
        	$text = $value[2];
        }else{
        	$text = wc_price($value[0]) . " - ".wc_price($value[1]);
        }
		?>
        <div style="display:inline-block; position: relative;">
        	
            <a href="<?php echo $links; ?>" class="<?php echo $active;?>"> 
            	<?php echo $text; ?>
            </a>
        </div>
        <?php
		
		return ob_get_clean();
	}
	static function chuyen_doi_min_max_price_sang_menu_widget($value,$taxonomy_hien_tai){
		ob_start();
		if(!isset($taxonomy_hien_tai['min_price'])) $taxonomy_hien_tai['min_price'] = 0;
		$current = "";
		if(
			(
				isset($taxonomy_hien_tai['min_price']) and $taxonomy_hien_tai['min_price'] == $value[0]) and 
				isset($taxonomy_hien_tai['max_price']) and ($taxonomy_hien_tai['max_price'] == $value[1]
			)or
			(
				!isset($taxonomy_hien_tai['max_price']) and !$value[1] and isset($taxonomy_hien_tai['min_price']) and $taxonomy_hien_tai['min_price'] == $value[0]
			)
		){
			$current = "current-cat";
		}		
		$links = self::lay_link_price_widget($value);
		if($value[0] == $value[1]){
            $text = __("All", 'woocommerce');
        }elseif(isset($value[2])){
        	$text = $value[2];
        }else{
        	$text = wc_price($value[0]) . " - ".wc_price($value[1]);
        }
		?>
		<li class="cat-item <?php echo $current;?>">
	        <a href="<?php echo $links; ?>"> 
	        	<?php echo $text; ?>
	        </a>
    	</li>
        <?php		
		return ob_get_clean();
	}
	static function chuyen_doi_min_max_price_sang_dropdown($value,$taxonomy_hien_tai){
		ob_start();
		if(!isset($taxonomy_hien_tai['min_price'])) $taxonomy_hien_tai['min_price'] = 0;
		$selected = "";
		if(
			(
				isset($taxonomy_hien_tai['min_price']) and $taxonomy_hien_tai['min_price'] == $value[0]) and 
				isset($taxonomy_hien_tai['max_price']) and ($taxonomy_hien_tai['max_price'] == $value[1]
			)or
			(
				!isset($taxonomy_hien_tai['max_price']) and !$value[1] and isset($taxonomy_hien_tai['min_price']) and $taxonomy_hien_tai['min_price'] == $value[0]
			)
		){
			$selected = "selected";
		}
        ?>
        <option <?php echo $selected; ?>    
        value='<?php echo json_encode(['min_price'=>$value[0],'max_price'=>$value[1]]);?>'
        data-taxonomy="filter_price"> 
            <?php echo isset($value[2])? $value[2] : __("All", 'woocommerce'); ?>
        </option>
        <?php 
		return ob_get_clean();

	}
	static function lay_input_query_type($taxonomy,$currentvalue = "or"){		
		if(substr($taxonomy, 0,3) !== "pa_") return ;		
		ob_start();
		$name = self::chuyen_doi_taxonomy_sang_query_type($taxonomy);
		$value = $currentvalue;
		if(array_key_exists($name,$_GET)){
			$value = $_GET[$name];
		}
		?>
		<input 
            type="hidden" 
            class="query_type"
            name="<?php echo $name;?>" 
            placeholder="<?php echo $name;?>"
            value="<?php echo $value;?>"
        />
		<?php
		return ob_get_clean();
	}
	static function chuyen_doi_taxonomy_sang_query_type($taxonomy){
		if(substr($taxonomy, 0,3) !== "pa_") return ;
		$taxonomy = str_replace("pa_", "", $taxonomy);
		return "query_type_".$taxonomy;
	}
	static function sap_xep_lai_cha_con(Array &$cats, Array &$into, $parentId = 0) {
	    foreach ($cats as $i => $cat) {
	        if ($cat->parent == $parentId) {
	            $into[$cat->term_id] = $cat;
	            unset($cats[$i]);
	        }
	    }

	    foreach ($into as $topCat) {
	        $topCat->children = array();
	        self::sap_xep_lai_cha_con($cats, $topCat->children, $topCat->term_id);
	    }
	}
	static function lay_gia_lon_nho_tu_database() {
	    global $wpdb;
	 
	    $sql  = "SELECT min( FLOOR( price_meta.meta_value ) ) as min_price, max( CEILING( price_meta.meta_value ) ) as max_price FROM {$wpdb->posts} ";
	    $sql .= " LEFT JOIN {$wpdb->postmeta} as price_meta ON {$wpdb->posts}.ID = price_meta.post_id ";
	    $sql .= "   WHERE {$wpdb->posts}.post_type IN ('product')
	            AND {$wpdb->posts}.post_status = 'publish'
	            AND price_meta.meta_key IN ('_price')
	            AND price_meta.meta_value > '' ";
	    $prices = $wpdb->get_row( $sql ); // WPCS: unprepared SQL ok.
	    return [
	        'min' => floor( $prices->min_price ),
	        'max' => ceil( $prices->max_price )
	    ];
	}
	static function lay_link_price_widget($current_price){
		if(empty($current_price)) return; 


		$final_arr = self::lay_toan_bo_taxonomy_hien_tai();		

		if(isset($final_arr['min_price']) and $final_arr['min_price'] == $current_price[0]){
			unset($final_arr['min_price']);
		}else{
			$final_arr['min_price'] = $current_price[0];
		}

		if(isset($final_arr['max_price']) and $final_arr['max_price'] == $current_price[1]){
			unset($final_arr['max_price']);
		}else{
			$final_arr['max_price'] = $current_price[1];
		}

		if(
			$current_price[0] == $current_price[1] and 
			$current_price[0] == 0
		){
			unset($final_arr['min_price']);
			unset($final_arr['max_price']);
		}
				
		return add_query_arg($final_arr, wc_get_page_permalink( 'shop' ));


	}
	static function lay_link_term_widget($current_term, $taxonomy,$query_type=""){
		$current_term = self::thay_doi_gia_tri_term_slug($current_term);
        $taxonomy = self::thay_taxonomy_slug_by_term_value($taxonomy,$current_term);

		$final_arr = self::lay_toan_bo_taxonomy_hien_tai();


		// kiem tra co phai term hien tai
		if(isset($final_arr[$taxonomy])){
			
			$value = explode(",",$final_arr[$taxonomy]);
			unset($final_arr[$taxonomy]);

			if(in_array($current_term,$value)){			

				$index = array_search($current_term,$value);								
				unset($value[$index]);

				if(empty($value)){
					unset($value);
				}
			}else{
				$value[] = $current_term;
				$value = array_unique($value);				
			}

			// nếu còn giá trị term			
			if(isset($value)){
				array_unshift($final_arr, [$taxonomy=>implode(",",$value)]);
				$final_arr = self::them_query_type($final_arr,$taxonomy,$query_type);
			}else{
				$final_arr = self::bo_query_type($final_arr,$taxonomy,$query_type);
			}
		}else{			
			if($current_term){
				$final_arr[$taxonomy] = $current_term;
				$final_arr = self::them_query_type($final_arr,$taxonomy,$query_type);
			}			
		}		

		return add_query_arg($final_arr, wc_get_page_permalink( 'shop' ));
	}	
	static function lay_input_hidden_form_taxonomy_hien_tai($exclude = false){
		$taxonomy_hien_tai = self::lay_toan_bo_taxonomy_hien_tai();
		if(!empty($taxonomy_hien_tai) and is_array($taxonomy_hien_tai)){
			foreach ($taxonomy_hien_tai as $key => $value) {
				$key = self::thay_doi_term_slug_cho_link($key);
				if($key and $value){
					if($key!==$exclude){
						echo '<input type="hidden" placeholder="'.$key.'" name="'.$key.'" value="'.$value.'" />';
					}				
				}
			}	
		}
	}
	static function script_change_to_submit($id){
		ob_start();
		?>
		<script type="text/javascript">
			window.addEventListener('DOMContentLoaded', function() {
				(function($){
					$('#<?php echo $id;?> .change_to_redirect').on("change",function(){
				 		if($(this).val()){
			 				if($(this).find(":selected").data('taxonomy') == 'filter_price'){
					 			var data_price = JSON.parse($(this).find(":selected").val());
					 			var input_target_min_price = $(this).closest("form").find('input[name="min_price"]');
					 			var input_target_max_price = $(this).closest("form").find('input[name="max_price"]');

					 			if(!input_target_max_price.length>0){
						 			$(this).closest('form').prepend('<input type="hidden" name="max_price" />');				 			
						 		}
						 		var input_max_price = $(this).closest('form').find('input[name="max_price"]');
						 		input_max_price.val(data_price.max_price);

					 			if(!input_target_min_price.length>0){
						 			$(this).closest('form').prepend('<input type="hidden" name="min_price" />');				 			
						 		}
						 		var input_min_price = $(this).closest('form').find('input[name="min_price"]');
						 		input_min_price.val(data_price.min_price);
					 		}else{
					 			var query_type = $(this).data("query_type");
						 		var query_value = $(this).data("query_value");
						 		var input_query_type_target = $(this).closest('form').find('input[name="'+query_type+'"]');
						 		if(query_value && !input_query_type_target.length>0){
						 			$(this).closest('form').prepend('<input type="hidden" name="'+query_type+'" value="'+query_value+'" />');
						 		}

						 		var taxonomy_slug = $(this).find(":selected").data("taxonomy");
						 		var input_target = $(this).closest("form").find('input[name="'+taxonomy_slug+'"]');

						 		if(!input_target.length>0){
						 			$(this).closest('form').prepend('<input type="hidden" name="'+taxonomy_slug+'" />');
						 		}

						 		var input_target2 = $(this).closest("form").find('input[name="'+taxonomy_slug+'"]');
						 		input_target2.val($(this).val());

						 							 		
					 		}
				 		}else{
				 			var query_value = $(this).data('query_value');			 			
				 			$(this).closest("form").find('input[name="'+query_value+'"]').remove();

				 			var query_type = $(this).data('query_type');			 			
				 			$(this).closest("form").find('input[name="'+query_type+'"]').remove();

				 			var taxonomy_slug = $(this).data("taxonomy");	 			
				 			$(this).closest("form").find('input[name="'+taxonomy_slug+'"]').remove();

				 			

				 			
				 		}	
				 		this.form.submit();		 		
				 	});
				})(jQuery);
			});
		</script>
		<?php
		return ob_get_clean();
	}
	static function them_query_type($final_arr,$taxonomy,$query_type){
		if($query_type !=="" and substr($taxonomy,0,7) == "filter_"){
			$final_arr["query_type_".str_replace("filter_","",$taxonomy)]= $query_type;
		}
		return $final_arr;
	}
	static function bo_query_type($final_arr,$taxonomy,$query_type){
		if($query_type !=="" and substr($taxonomy,0,7) == "filter_"){
			unset($final_arr["query_type_".str_replace("filter_","",$taxonomy)]);
		}
		return $final_arr;
	}
	static function convert_thousand($value){
		$use_custom_thousand = [];
	    if(
	        isset(ADMINZ_Woocommerce::$options['filter_price_thousand_from']) and 
	        ADMINZ_Woocommerce::$options['filter_price_thousand_from'] and 
	        isset(ADMINZ_Woocommerce::$options['filter_price_thousand_to']) and 
	        ADMINZ_Woocommerce::$options['filter_price_thousand_to']
	    ){
	        $use_custom_thousand = [
	            array_reverse(explode("\n",ADMINZ_Woocommerce::$options['filter_price_thousand_from'])),
	            array_reverse(explode("\n",ADMINZ_Woocommerce::$options['filter_price_thousand_to'])),
	        ];
	    }
	    if(!empty($use_custom_thousand)){
	    	return str_replace($use_custom_thousand[0],$use_custom_thousand[1],$value);
		}else{
			return wc_price($value);
		}
	}
	static function get_price_range_2($global_filter_price){
		$price_range = [];
	    $price_range_2 = [];    
	    if(
	        ($global_filter_price == "on" or $global_filter_price == "true")
	        and ADMINZ_Woocommerce::$options['filter_price_values']
	        and ADMINZ_Woocommerce::$options['filter_price_display']
	    ){
	        $filter_price_values = explode("\n",ADMINZ_Woocommerce::$options['filter_price_values']);
	        $filter_price_display = explode("\n",ADMINZ_Woocommerce::$options['filter_price_display']);

	        if(
	            !empty($filter_price_display) and is_array($filter_price_display) and 
	            !empty($filter_price_values) and is_array($filter_price_values) and 
	            (count($filter_price_values) == count($filter_price_display))
	        ){
	            $temp = [];
	            foreach ($filter_price_values as $key => $value) {
	                $explode = explode("-",str_replace(" ", "", $value));
	                $temp = [
	                    isset($explode[0])? trim($explode[0]) : "",
	                    isset($explode[1])? trim($explode[1]) : "",
	                    $filter_price_display[$key]
	                ];
	                $price_range_2[] = $temp;
	            }
	        }
	        
	    }else{
	        $minmax = ADMINZ_Helper_Woocommerce_Taxonomy::lay_gia_lon_nho_tu_database();
	        if($minmax['min'] == 0 and $minmax['max'] == 0 ) return;
	        $min = $minmax['min'];
	        $max = $minmax['max'];
	        if(!isset($step) or !$step){
	            $step = round($max/10);
	        }
	        
	        for ($i=$min; $i <=$max ; $i+=$step) { 
	            $price_range[] = $i;
	        }
	        if(end($price_range)<$max){
	            array_pop($price_range);
	            $price_range[] =$max;
	        }
	        $price_range_2 = [];
	        if(!empty($price_range) and is_array($price_range)){
	            if($price_range[0]>0){
	                array_unshift($price_range, 0);
	            }
	            foreach ($price_range as $key=> $value) {

	                if(!isset($price_range[$key+1])){
	                    $value2 = "";
	                    $text = "> ".ADMINZ_Helper_Woocommerce_Taxonomy::convert_thousand($value);                                                       
	                }elseif(!$key){
	                    $value2= $price_range[$key+1];
	                    $text = "< ".ADMINZ_Helper_Woocommerce_Taxonomy::convert_thousand($price_range[$key+1]);                                       
	                }else{
	                    $value2= $price_range[$key+1];
	                    $text = $text = ADMINZ_Helper_Woocommerce_Taxonomy::convert_thousand($value). " - " .ADMINZ_Helper_Woocommerce_Taxonomy::convert_thousand($price_range[$key+1]);
	                }
	                $price_range_2[] = [
	                    $value,
	                    $value2,
	                    $text
	                ];

	            }
	        }
	    } 
	    return $price_range_2;
	}

}