<?php 
use Adminz\Admin\Adminz as Adminz;
use Adminz\Admin\ADMINZ_Woocommerce as ADMINZ_Woocommerce;
use Adminz\Helper\ADMINZ_Helper_Woocommerce_Taxonomy; 


add_action('ux_builder_setup', 'adminz_woo_form');

function adminz_woo_form(){        
    
    if(!Adminz::is_woocommerce()) return;    
    $optionattr2 = ADMINZ_Woocommerce::get_arr_attributes();    
    $tax_arr = ADMINZ_Woocommerce::get_arr_tax();    
    $tax_arr['view_more'] = "View more";
    $tax_arr['submit'] = "Submit";    
    

    $settings =  array(
        'name'      => "Search ".__('Product','woocommerce'),
        'category'  => Adminz::get_adminz_menu_title(),
        'thumbnail' =>  get_template_directory_uri() . '/inc/builder/shortcodes/thumbnails/' . 'search' . '.svg',
        'info'      => '{{ id }}',
        'options' => array(
            'taxgroup'=>array(
                'type' => 'group',
                'heading'   =>'Fields',
                'options'=>   [
                    'fields'=> [
                        'type' => 'select',
                        'param_name' => 'slug',
                        'default'=> 'title,submit',
                        'heading' => 'Select taxonomies',
                        'config' => array(
                            'multiple' => true,
                            'sortable'    => true,
                            'placeholder' => 'Select..',
                            'options' => $tax_arr
                        ),
                    ],
                    'query_type_or'=>array(
                        'type' => 'select',
                        'param_name' => 'slug',
                        'heading' => 'Query type OR',
                        'description'=> 'Only for Product attributes',
                        'config' => array(
                            'multiple' => true,
                            'placeholder' => 'Select..',
                            'options' => $optionattr2
                        ), 
                    ),
                    'fixed_field'=>  [
                        'type' => 'select',
                        'param_name' => 'slug',
                        'heading' => 'Fixed field',
                        'options' => $tax_arr
                    ],
                ]        
            ),
            'appearance'=>array(
                'type' => 'group',
                'heading'   =>'Appearance'  ,
                'options'=> [
                    'style'=>array(
                        'type' => 'select',
                        'param_name' => 'slug',
                        'heading' => 'Use button field',
                        'config' => array(
                            'multiple' => true,
                            'placeholder' => 'Select..',
                            'options' => $tax_arr
                        ), 
                    ),  
                    'field_before_title'=>array(
                        'type' => 'select',
                        'param_name' => 'slug',
                        'heading' => 'Field before title',
                        'options' => $tax_arr
                    ), 
                    'field_after_title'=>array(
                        'type' => 'select',
                        'param_name' => 'slug',
                        'heading' => 'Field after title',
                        'options' => $tax_arr
                    ), 
                    'global_filter_price'=>[
                        'type'=>'checkbox',
                        'heading'=> 'Filter price step',
                        'description'=> 'Use global steps in Settings in Tool-> administrator Z-> woocommerce',
                        'default'=> ''
                    ],                   
                    'step'=>array(
                        'type' => 'textfield',
                        'heading'   =>'Step',
                        'default'=> '',
                        'conditions' => 'filter_price == ""',
                    ),                     
                    'selectall'=>array(
                        'type' => 'textfield',
                        'heading'   =>'Select all text',
                        'default'=> __("Select all",'woocommerce')
                    ),
                    'search_placeholder'=>array(
                        'type' => 'textfield',
                        'heading'   =>'Select placeholder',
                        'default'=> __( 'Enter a search term and press enter', 'woocommerce' )
                    ),
                    'selectnone'=>array(
                        'type' => 'textfield',
                        'heading'   =>'Select none text',
                        'default'=> "",                        
                    ),
                    'item_col_large' => array(
                        'type'       => 'slider',
                        'heading'    => 'Items per row',
                        'description' => 'On Large screen',
                        'default'    => '3',
                        'min'   => 1,
                        'max'   => 12
                    ),
                    'item_col_small' => array(
                        'type'       => 'slider',
                        'heading'    => 'Items per row',
                        'description' => 'On Small screen',
                        'default'    => '1',
                        'min'   => 1,
                        'max'   => 12
                    ),
                    'field_col_12'=>array(
                        'type' => 'select',
                        'param_name' => 'slug',
                        'heading' => 'Field col 12 cols',
                        'description'=> 'Choose field to 12 cols',
                        'config' => array(
                            'multiple' => true,
                            'placeholder' => 'Select..',
                            'options' => $tax_arr
                        ),       
                    ),
                    'field_view_more'=>array(
                        'type' => 'select',
                        'param_name' => 'slug',
                        'heading' => 'Field view more',
                        'description'=> 'Showing when click toggle button',
                        'config' => array(
                            'multiple' => true,
                            'placeholder' => 'Select..',
                            'options' => $tax_arr
                        ),       
                    ),
                    'view_more_text_1'=>array(
                        'type' => 'textfield',
                        'heading'   =>'View more text 1',
                        'default'=> 'view more'
                    ),
                    'view_more_text_2'=>array(
                        'type' => 'textfield',
                        'heading'   =>'View more text 2',
                        'default'=> 'view less'
                    ),
                    'closerow_before'=>array(
                        'type' => 'select',
                        'param_name' => 'slug',
                        'heading' => 'Close row before',
                        'description'=> 'Choose field to close row',
                        'config' => array(
                            'multiple' => true,
                            'placeholder' => 'Select..',
                            'options' => $tax_arr
                        ),       
                    ),                    
                ],
            ),
        ),
    );
    $tax_arr_fixed_value = [];
    if(!empty($tax_arr) and is_array($tax_arr)){
        foreach ($tax_arr as $key => $value) {
            $terms = get_terms($key);
            $tmp = [];

            if(!is_wp_error($terms) and !empty($terms) and is_array($terms)){
                foreach ($terms as $key2 => $value) {
                    $tmp[$value->slug] = $value->name;
                }
            }
            $tax_arr_fixed_value[$key] = $tmp;
        }
    }
    if(!empty($tax_arr_fixed_value) and is_array($tax_arr_fixed_value)){
        foreach ($tax_arr_fixed_value as $key => $value) {
            $settings['options']['taxgroup']['options']['fixed_'.$key] = array(
                        'type' => 'select',
                        'param_name' => 'slug',
                        'heading' => 'Fixed value',
                        'conditions' => 'fixed_field == "'.$key.'"',
                        'options' => $value
                    );
        }
    }
    add_ux_builder_shortcode('adminz_woo_form',$settings);
}
add_shortcode('adminz_woo_form', 'adminz_woo_form_shortcode');
function adminz_woo_form_shortcode($atts, $content = null ) {           
    
    global $wpdb;
    $defaultatts = array(
        '_id'=>rand(),
        'item_col_large'=> '3',
        'item_col_small'=> '1',
        'global_filter_price'=>'',
        'step'=>'',     
        'field_before_title'=>'',
        'field_after_title'=>'',
        'style'=>'',
        'selectnone'=> "" ,
        'search_placeholder'=> __( 'Enter a search term and press enter', 'woocommerce' ),
        'selectall'=> __("Select all",'woocommerce'),
        'fields'=> 'title,submit',
        'query_type_or'=>'',
        'fixed_field'=>'',
        // value for fixed field
        'closerow_before'=>'',
        'field_col_12'=>'',   
        'field_view_more'=> '', 
        'view_more_text_1'=> 'view more',
        'view_more_text_2'=> 'view less'    
    );
    $tax_arr = ADMINZ_Woocommerce::get_arr_tax();    
    if(!empty($tax_arr) and is_array($tax_arr)){
        foreach ($tax_arr as $key => $value) {
            $defaultatts['fixed_'.$key] = '';
        }
    }

    extract(shortcode_atts($defaultatts, $atts));    
    $fields = explode(",", $fields);         
    $field_col_12 = explode(',', $field_col_12);
    $field_view_more = $field_view_more? explode(',',$field_view_more) : [];
    $style = explode(",", $style);
    
    $taxonomy_hien_tai = ADMINZ_Helper_Woocommerce_Taxonomy::lay_toan_bo_taxonomy_hien_tai();    
    ob_start();
    
    ?>
    <style type="text/css"> .adminz_woo_form .absolute>*{margin-bottom: 0px;}.adminz_woo_form select{width: 100%; } .adminz_woo_form .col.nopadding{padding-bottom: 0px; } .adminz_woo_form .listcheckbox label{font-size: 0.8em !important; display: inline-block; border: 1px solid currentColor; opacity: .8; margin: 0 3px 5px 0; padding: 2px 5px; border-radius: 3px; cursor: pointer; font-weight: normal; transition: background-color 0.3s ease; } .adminz_woo_form .listcheckbox label span{font-weight: normal; } .adminz_woo_form .listcheckbox label.active{opacity: 1; border-color: var(--primary-color); background-color: var(--primary-color); color: #cacaca; } .adminz_woo_form .listcheckbox label.active *{color: inherit; } .text-center .adminz_woo_form *{text-align:  inherit; } .adminz_woo_form .viewmore .icon-angle-up{display: none;}.adminz_woo_form .viewmore.toggled .icon-angle-up{display: inline-block;} .adminz_woo_form .viewmore.toggled .icon-angle-down{display: none;} @media (min-width:  550px){.adminz_woo_form .field-title{display: flex; flex-direction: row-reverse; } .adminz_woo_form .field-title.has-left-title .relative .relative>*{margin-left: -1px;} } .adminz_woo_form i.v-center{margin-top: -.4em} @media (max-width:  549px){.adminz_woo_form .absolute.v-center.right{position: static !important; transform:  unset !important; } } </style>
    <form id="<?php echo $_id; ?>" class="adminz_woo_form adminz_custom_col mb-0" method="get" action="<?php echo wc_get_page_permalink( 'shop' ); ?>">
        <div class="row row-nopaddingbottom">
            <?php 
            if($fields){
                // sap xep lai fields theo view_more de khi click de~ nhin
                if(!empty($field_view_more) and is_array($field_view_more)){
                    foreach ($field_view_more as $key => $value) {
                        if(in_array($value,$fields)){
                            unset($fields[array_search($value, $fields)]);
                            $fields[]  = $value;
                        }
                    }
                }                
                // luon luon chuyen viewmore và submit xuong duoi cung
                if(in_array('view_more',$fields)){
                    unset($fields[array_search('view_more', $fields)]);
                }
                $fields[] = 'view_more';
                if(in_array('submit',$fields)){
                    unset($fields[array_search('submit', $fields)]);
                }
                $fields[] = 'submit';
                

                foreach ($fields as $key => $taxonomy) {
                    if(in_array($taxonomy, explode(',', $closerow_before))){
                        echo '</div><div class="row">';
                    }

                    $item_col_class = "col small-".(12/$item_col_small). " large-".(12/$item_col_large);                    

                    if(in_array($taxonomy, $field_col_12)){
                        $item_col_class = "col small-12 large-12";
                    }
                    if(in_array($taxonomy,$field_view_more)){
                        $item_col_class.= " hidden col_view_more";
                    }
                    switch ($taxonomy) {
                        case 'title':
                            ?>                            
                            <div class="<?php echo $item_col_class; ?>">
                                <div class="col-inner">
                                    <div class="field-title <?php if($field_before_title){ echo 'has-left-title';} ?>">
                                        <div class="relative full-width">
                                            <div class="relative">
                                                <input type="text" name="s" class="" placeholder="<?php echo $search_placeholder; ?>" style="padding-left:  40px;" value="<?php 
                                        echo isset($taxonomy_hien_tai['s'])? $taxonomy_hien_tai['s'] : "";  ?>" />
                                                <i class="icon-search absolute v-center left op-4" style="margin-left: 15px;" ></i>
                                            </div>
                                            <?php 
                                            if($field_after_title){
                                                $after_class = "";
                                                if($field_after_title == 'submit' or $field_after_title == 'view_more'){
                                                    $after_class = "hide-for-small";
                                                }
                                                echo '<div class="absolute v-center right '.$after_class.'">';
                                                echo adminz_woo_form_get_sub_field($field_after_title,$item_col_class,$taxonomy,$style,$global_filter_price,$step, $selectnone,$query_type_or,$view_more_text_1,$view_more_text_2,$field_view_more,$field_after_title,$field_before_title);
                                                echo '</div>';
                                            }
                                            ?>                                        
                                        </div>
                                        <?php 
                                        if($field_before_title){
                                            echo '<div class="left-title">';
                                            echo adminz_woo_form_get_sub_field($field_before_title,$item_col_class,$taxonomy,$style,$global_filter_price,$step, $selectnone,$query_type_or,$view_more_text_1,$view_more_text_2,$field_view_more,$field_after_title,$field_before_title);
                                            echo '</div>';
                                        }                                        
                                        ?>
                                    </div>                                    
                                </div>
                            </div>
                            <?php
                            break;
                        case 'price':
                            echo adminz_woo_form_get_field_price($item_col_class,$global_filter_price,$step,$style,$selectnone);
                            break;
                        case 'submit': 
                            echo adminz_woo_form_get_field_submit($item_col_class,$field_after_title,true); 
                            break;
                        case 'view_more':                              
                            echo adminz_woo_form_get_field_view_more($item_col_class, $view_more_text_1,$view_more_text_2,$field_view_more,$field_after_title,$field_before_title,true);
                            break;
                        default:
                            echo adminz_woo_form_get_field_taxonomy($item_col_class,$taxonomy,$style,$selectnone,$query_type_or);
                        break;
                    }                    
                }                
            }
            ?>
        </div>  
        <?php 
        // for fixed field
        if(isset($fixed_field) and $fixed_field ) {
            if(isset(${'fixed_'.$fixed_field}) and ${'fixed_'.$fixed_field}){
                ?>
                <input type="hidden" name="<?php echo $fixed_field;?>" value="<?php echo ${'fixed_'.$fixed_field} ;?>">
                <?php
            }
        }

        ?>
        <input type="hidden" name="post_type" value="product">
    </form>
    <script type="text/javascript">
        window.addEventListener('DOMContentLoaded', function() {
            (function($){
                var adminz_woo_form = $('<?php echo "#".$_id; ?>.adminz_woo_form');
                $(adminz_woo_form).submit(function() {
                    $(this).find("input,select,textarea").each(function(){
                        if($(this).val() == ""){
                            $(this).prop('disabled', true);
                        };
                        if($(this).hasClass('query_type')){
                            if($(this).siblings('.target_value').val() == ""){
                                $(this).prop('disabled', true);
                            }
                        }
                    });                
                });
                $(adminz_woo_form).find('.listcheckbox label').each(function(){
                    $(this).click(function(e){
                        e.preventDefault();
                        $(this).toggleClass('active');
                    });                
                });
                $(adminz_woo_form).find(".filter_price .listcheckbox label").each(function(){
                    $(this).click(function(e){
                        e.preventDefault();
                        var from = $(this).data("from");
                        var to = $(this).data("to");
                        $(adminz_woo_form).find('input[name="min_price"]').val(from);
                        $(adminz_woo_form).find('input[name="max_price"]').val(to);
                    });
                });
                $(adminz_woo_form).find(".filter_price select").on('change keyup',function(e){
                    e.preventDefault();
                    var min_price = ($(this).find(':selected').data('min_price'));
                    var max_price = ($(this).find(':selected').data('max_price'));
                    $(adminz_woo_form).find('input[name="min_price"]').val(min_price);
                    $(adminz_woo_form).find('input[name="max_price"]').val(max_price);
                });
                $(adminz_woo_form).find('.listcheckbox.tax label').each(function(){
                    $(this).click(function(e){
                        e.preventDefault();                    
                        var current_data_tax = $(this).data('tax');                    
                        var tax_value = '';
                        $(this).closest(".listcheckbox.tax").find("label").each(function(){                        
                            if($(this).hasClass('active') & ($(this).data("tax") == current_data_tax)){
                                tax_value +=$(this).data("value")+",";
                            };
                        });                    
                        tax_value = tax_value.slice(0, -1);
                        $(this).closest(".col").find("input[name='"+current_data_tax+"']").val(tax_value);
                    });
                });
                $(adminz_woo_form).find('.viewmore').on('click',function(){
                    $(this).closest(adminz_woo_form).find('.col_view_more').toggleClass('hidden');
                    var current_text = $(this).find('span').text();
                    $(this).find('span').text($(this).data('text'));
                    $(this).data('text',current_text);
                    $(this).toggleClass('toggled');
                });
                $(adminz_woo_form).find('button[type="reset"]').on("click",function(e){
                    $(this).closest(adminz_woo_form).find('input[type="hidden"]').val("");
                    $(this).closest(adminz_woo_form).find('input[name="post_type"]').val("product");
                });
            })(jQuery);
        });
    </script>
    <?php  
    add_action('wp_footer',function (){   
        if(!Adminz::is_flatsome()){
            wp_enqueue_style( 'adminz_custom_col',plugin_dir_url(ADMINZ_BASENAME).'assets/css/adminz_custom_col.css', array(), '1.0', 'all' );
        }       
    });
    $return = apply_filters('adminz_output_debug',ob_get_clean());
    return $return;
}
function adminz_woo_form_get_sub_field($field,$item_col_class,$taxonomy,$style,$global_filter_price,$step, $selectnone,$query_type_or,$view_more_text_1,$view_more_text_2,$field_view_more,$field_after_title,$field_before_title){   
    echo '<div class="row row-collapse">';
    switch ($field) {
        case 'title':
            echo 'not supportted';
            break;
        case 'price':
            echo adminz_woo_form_get_field_price($item_col_class,$global_filter_price,$step,$style,$selectnone);
            break;
        case 'submit':
            echo adminz_woo_form_get_field_submit($item_col_class,$field_after_title,false);
            break;
        case 'view_more':
            echo adminz_woo_form_get_field_view_more($item_col_class,$view_more_text_1,$view_more_text_2,$field_view_more,$field_after_title,$field_before_title,false);
            break;
        default:
            echo adminz_woo_form_get_field_taxonomy($item_col_class,$field,$style,$selectnone,$query_type_or);
            break;
    }
    echo '</div>';
}
function adminz_woo_form_get_field_view_more($item_col_class,$view_more_text_1,$view_more_text_2,$field_view_more,$field_after_title,$field_before_title,$is_main_field =true){
    ob_start();   
    if(!empty($field_view_more) and is_array($field_view_more)){
        if($field_after_title == 'view_more' and $is_main_field){
            $item_col_class .= " show-for-small";
        }
    ?>
    <div class="<?php echo $item_col_class; ?>">
        <p>
        <button type="button" class="button is-link  is-smaller op-6 viewmore" data-text="<?php echo $view_more_text_2; ?>"><span><?php echo $view_more_text_1; ?></span> <i class="icon-angle-up"></i> <i class="icon-angle-down"></i></button>
        <button type="reset" class="button is-link  is-smaller op-6 reset">
            Reset
            <?php echo Adminz::get_icon_html('sync-alt',['style'=>['width'=>'.9em;']]); ?>
        </button>                                    
        </p>
    </div>
    <?php
    }
    return ob_get_clean();
}
function adminz_woo_form_get_field_submit($item_col_class,$field_after_title,$is_main_field=true){
    ob_start();
    if($field_after_title == 'submit' and $is_main_field){
        $item_col_class .= " show-for-small";
    }
    ?>
    <div class="<?php echo $item_col_class; ?>">                                    
        <button type="submit" class="ux-search-submit submit-button secondary button icon " aria-label="<?php echo __( 'Submit', 'flatsome' ); ?>">
            <?php echo __( 'Search', 'woocommerce' ); ?>                                     
        </button>   
    </div>
    <?php
    return ob_get_clean();
}
function adminz_woo_form_get_field_taxonomy($item_col_class,$taxonomy,$style,$selectnone,$query_type_or){
    ob_start();    
    $taxobj = get_taxonomy($taxonomy);    
    $get_terms = get_terms($taxonomy,array('hide_empty' => false));    
    $taxonomy2 = ADMINZ_Helper_Woocommerce_Taxonomy::thay_doi_term_slug_cho_link($taxonomy); 
    $label = ADMINZ_Helper_Woocommerce_Taxonomy::thay_doi_taxonomy_label($taxobj);
    
    ?>
    <div class="<?php echo $item_col_class; ?>">                                
        <?php                                 
        if(in_array($taxonomy, $style)){                                    
            ?>
            <strong><?php echo $label; ?></strong> 
            <input type="hidden" class="target_value" placeholder="<?php echo $taxonomy2;?>" name="<?php echo $taxonomy2;?>" value="<?php
            echo ADMINZ_Helper_Woocommerce_Taxonomy::lay_gia_tri_taxonomy_hien_tai($taxonomy2);
            ?>">                                    
            
            <div class="listcheckbox tax">
                <?php 
                if(!empty($get_terms) and is_array($get_terms)){
                    foreach ($get_terms as $key => $term) {
                        echo ADMINZ_Helper_Woocommerce_Taxonomy::chuyen_doi_term_sang_button_form($term,$taxonomy2);
                    }
                }
                 ?>
            </div>                                    
            <?php                              
        }else{
            $categoryHierarchy = array();             
            if(is_array($get_terms)){                
                ADMINZ_Helper_Woocommerce_Taxonomy::sap_xep_lai_cha_con($get_terms, $categoryHierarchy);
            }            
            ?>
            <select name="<?php echo $taxonomy2;?>" class="target_value">
                <option value="" ><?php echo $selectnone; ?> <?php echo $label;?></option>
                <?php 
                if(!empty($categoryHierarchy) and is_array($categoryHierarchy)){
                    foreach ($categoryHierarchy as $key => $term) {
                        echo ADMINZ_Helper_Woocommerce_Taxonomy::chuyen_doi_term_option_select($term,$taxonomy2," ");
                    }
                }
                ?>
            </select>
            <?php
        }                                
        if(in_array($taxonomy2,explode(",",$query_type_or))){                                    
                echo ADMINZ_Helper_Woocommerce_Taxonomy::lay_input_query_type($taxonomy);
            }
        ?>
    </div>
    <?php
    return ob_get_clean();
}
function adminz_woo_form_get_field_price($item_col_class,$global_filter_price,$step,$style,$selectnone){
    ob_start();
    
    $price_range_2 = ADMINZ_Helper_Woocommerce_Taxonomy::get_price_range_2($global_filter_price);  
    if(in_array('price', $style)){
        if(!empty($price_range_2) and is_array($price_range_2)){ ?>
        <div class="<?php echo $item_col_class; ?> filter_price">
            <strong><?php echo __('Filter by price','woocommerce'); ?>:</strong></br>                                    
            <div class="listcheckbox"> 
                <?php 
                array_unshift($price_range_2, [0,0]);
                
                foreach ($price_range_2 as $key => $value) {
                    $text = $value[2];
                    if($value[0] == $value[1]){
                        $text = __("All", 'woocommerce');
                    }

                    $class="";
                    if(
                        isset($taxonomy_hien_tai['min_price']) and 
                        $taxonomy_hien_tai['min_price'] == $value[0] and 
                        isset($taxonomy_hien_tai['max_price']) and
                        $taxonomy_hien_tai['max_price'] == $value[1]
                    ){
                        $class="active";
                    }
                    ?>
                    <label data-from="<?php echo $value[0]? trim($value[0]) : "" ;?>" data-to="<?php echo trim($value[1])? $value[1] : "";?>" class="<?php echo $class;?>">
                        <?php echo $text; ?>
                    </label>
                    <?php   
                }
                 ?>
                
            </div>                                    
        </div>
        <?php } ?>
        <?php
    }else{                                
        ?>
        <div class="<?php echo $item_col_class; ?> filter_price">
            <select>
                <option value="" ><?php echo $selectnone; ?> <?php echo __('Filter by price','woocommerce'); ?></option>
                <?php
                foreach ($price_range_2 as $key => $value) {
                    $selected = "";
                    if(
                        isset($taxonomy_hien_tai['min_price']) and 
                        isset($taxonomy_hien_tai['max_price']) and 
                        $value[0] == $taxonomy_hien_tai['min_price'] and 
                        $value[1] == $taxonomy_hien_tai['max_price']
                    ){
                        $selected = "selected";
                    }
                    $text = $value[2];
                    ?>
                    <option <?php echo $selected; ?> data-min_price="<?php echo trim($value[0]); ?>" data-max_price="<?php echo trim($value[1]); ?>"> 
                        <?php echo $text; ?>
                    </option>
                    <?php   
                }
                ?>
            </select>
        </div>
        <?php
    }
    ?>
    <input type="hidden" name="min_price" value="<?php echo isset($taxonomy_hien_tai['min_price'])? $taxonomy_hien_tai['min_price'] : ""; ?>">
    <input type="hidden" name="max_price" value="<?php echo isset($taxonomy_hien_tai['max_price'])? $taxonomy_hien_tai['max_price'] : ""; ?>">
    <?php
    return ob_get_clean();
}
