<?php 
use Adminz\Admin\Adminz as Adminz;
add_action('ux_builder_setup', 'adminz_quick_order_uxbuilder');
add_action('wp_ajax_nopriv_adminz_quick_order_price_ajax', 'adminz_quick_order_price_ajax');
add_action('wp_ajax_adminz_quick_order_price_ajax', 'adminz_quick_order_price_ajax');
add_action('wp_ajax_nopriv_adminz_quick_order_ajax', 'adminz_quick_order_ajax');
add_action('wp_ajax_adminz_quick_order_ajax', 'adminz_quick_order_ajax');
add_shortcode('adminz_quick_order','adminz_quick_order');

function adminz_send_order_details($order){
	do_action( 'woocommerce_before_resend_order_emails', $order, 'customer_invoice' );	
	WC()->payment_gateways();
	WC()->shipping();
	WC()->mailer()->customer_invoice( $order );	
	$order->add_order_note( __( 'Order details manually sent to customer.', 'woocommerce' ), false, true );
	do_action( 'woocommerce_after_resend_order_email', $order, 'customer_invoice' );	
}
function adminz_send_order_details_admin($order){
	do_action( 'woocommerce_before_resend_order_emails', $order, 'new_order' );
	WC()->payment_gateways();
	WC()->shipping();
	add_filter( 'woocommerce_new_order_email_allows_resend', '__return_true' );
	WC()->mailer()->emails['WC_Email_New_Order']->trigger( $order->get_id(), $order, true );
	remove_filter( 'woocommerce_new_order_email_allows_resend', '__return_true' );
	do_action( 'woocommerce_after_resend_order_email', $order, 'new_order' );
}
function adminz_quick_order_uxbuilder(){	
	add_ux_builder_shortcode('adminz_quick_order', array(		
		'name'      => __('Product','woocommerce'). " Quick Order",
		'category'  => Adminz::get_adminz_menu_title(),
		'thumbnail' =>  get_template_directory_uri() . '/inc/builder/shortcodes/thumbnails/' . 'woo_products' . '.svg',
		'info' => '{{ title }}',
	    'options' => array(
	    	'product_id' => array(
			  'type' => 'select',
			  'heading' => 'Product',			  
			  'default' => '',
			  'param_name'=>'id',
			  'config' => array(			      
		            'placeholder' => 'Select..',
		            'postSelect' => array(
		                'post_type' => array('product')
		            ),
			  )
			),
	    )
	) );
}
function adminz_quick_order_price_ajax(){
	if(!isset($_POST['price']) or !isset($_POST['qty'])){ die;}
	wp_send_json_success(wc_price($_POST['price']*$_POST['qty']));
	wp_die();
}
function adminz_quick_order_ajax(){
	if(!isset($_POST['product'])){ die;}
	
	extract(shortcode_atts(array(
        'product' => "",
        'qty' => 1,
        'coupon' => '',
        'payment_method'=>'bacs'
    ), $_POST['product']));	

    $args = [];
    if(get_current_user_id()){    	
   		$args['customer_id'] = get_current_user_id();
    }
   	$new_order = wc_create_order($args);   	

   	$new_order->add_product( wc_get_product($product), $qty );
   	$new_order->set_address( $_POST['billing'], 'billing' );
   	$new_order->set_address( $_POST['shipping'], 'shipping' );
   	$new_order->set_customer_note($_POST['additional']['order_comments']);
   	//$order->update_status("completed", 'TEST', TRUE);

   	$new_order->set_payment_method($payment_method);
	
	$new_order->apply_coupon($coupon);	
	$new_order->calculate_totals();
	$total = $new_order->get_total();
	$order_id = $new_order->save();

	// test mode
	if($_POST['is_test'] == "true"){
		$new_order->remove_coupon($coupon);
		wp_delete_post($order_id,true);
		wp_send_json_success(__("Total:","woocommerce")." ".wc_price($total));
	}else{
		// send email
		adminz_send_order_details($new_order);
		adminz_send_order_details_admin($new_order);
		wp_send_json_success($new_order->get_checkout_order_received_url());
	}		
	wp_die();
}

function adminz_quick_order($atts){
    if(!Adminz::is_woocommerce()) return;
    if(!Adminz::is_flatsome()){        
        wp_enqueue_style( 'get_flatsome_col_css',plugin_dir_url(ADMINZ_BASENAME).'assets/css/flatsome_col.css', array(), '1.0', $media = 'all' );
    }

	extract(shortcode_atts(array(
        'product_id' => "",        
    ), $atts));	
	
	$htmlid = rand();
		ob_start();		
		?>
	    <style type="text/css">	    
	    #html<?php echo $htmlid;?> .adminz_order_total_review{margin-top:  15px;}
	    #html<?php echo $htmlid;?> .woocommerce-billing-fields{border-top: none;} 
	    #html<?php echo $htmlid;?> .woocommerce-form-coupon-toggle{border-bottom: 1px dashed #ddd;}
	    #html<?php echo $htmlid;?> .buynow_popup,
	    #html<?php echo $htmlid;?> button[name="add-to-cart"], .adminz_quick_order .single_add_to_cart_button,
	    #html<?php echo $htmlid;?> .shipping_address,
	    #html<?php echo $htmlid;?> #place_order,
	    #html<?php echo $htmlid;?> .redirect_to_checkout{display: none;}
	    #html<?php echo $htmlid;?> #payment{margin-top:  30px; }
	    #html<?php echo $htmlid;?> .wc_payment_methods li{list-style: none; margin-left:  0px !important;
	    }
	    </style>
	    <script type="text/javascript">
	    	window.addEventListener('DOMContentLoaded', function() {
	    		(function($) {
		            $( document.body ).on( 'click', '#html<?php echo $htmlid;?> a.showcoupon', function(){
			            $( '.checkout_coupon' ).slideToggle( 0, function() {
			                $( '.checkout_coupon' ).find( ':input:eq(0)' ).trigger( 'focus' );
			            });
			            return false;
			        });
		        	$( document.body ).on( 'change', '#html<?php echo $htmlid;?> input[name="payment_method"]', function(){
			            $('#html<?php echo $htmlid;?> input[name="payment_method"]').closest("li").find(".payment_box").hide();
			            if($(this).prop("checked")){
			            	$(this).closest("li").find(".payment_box").show();
			            }
			        });
			        $( document.body ).on( 'change', '#html<?php echo $htmlid;?> #ship-to-different-address-checkbox', function(){	        	
			        	$( this ).closest("#ship-to-different-address").next(".shipping_address").slideToggle( 0, function() {});
			        });

			        var parent = $("#html<?php echo $htmlid;?>");
			        adminz_quick_order_price_ajax(parent);
			        
		        	$("#html<?php echo $htmlid;?> .input-text.qty").change(function(){       			        		
		        		adminz_quick_order_price_ajax(parent);
					});
					function adminz_quick_order_price_ajax(parent){
			        	var qty = parent.find(".input-text.qty").val();
			        	var price = parent.find('input[name="adminz_product_price"]').val();
		        		var variation_id = parent.find('input.variation_id');

		        		if(variation_id.length && variation_id.val()>0){
		        			variation_id.val();	        			
		        			var product_variations = ($('.variations_form').data('product_variations'));
		        			for (var i = 0; i < product_variations.length; i++) {
		        				if(variation_id.val() == product_variations[i].variation_id){
		        					price = (product_variations[i].display_price);
		        				}
		        			}
		        		}	        		
		        		if(!price || !qty) return;
		        		$.ajax({
	                        type : "post",
	                        dataType : "json",
	                        url : '<?php echo admin_url('admin-ajax.php'); ?>',
	                        data : {
	                            action:'adminz_quick_order_price_ajax',
	                            price: price,
	                            qty: qty				        	
	                        },       
	                        context: this,                 
	                        beforeSend: function(){
	                        	$(".buttons_added").find("input").attr("disabled","disabled");
	                        	$("#html<?php echo $htmlid;?>").find(".adminz_order_total_review").html('<?php echo __("Loading&hellip;","woocommerce")?>')
	                        },
	                        success: function(response) { 
	                        	var html = $.parseHTML( "<p class='lead'>"+response.data+"</p>" );	
				        		$("#html<?php echo $htmlid;?>").find(".adminz_order_total_review").empty();		        		
				        		$("#html<?php echo $htmlid;?>").find(".adminz_order_total_review").append(html);
	                        	$(".buttons_added").find("input").removeAttr("disabled");
	                        },
	                        error: function( jqXHR, textStatus, errorThrown ){
	                        	console.log( 'Administrator Z: The following error occured: ' + textStatus, errorThrown );
	                        }
	                    });
			        }
					$( document.body ).on( 'click', '#html<?php echo $htmlid;?> button[name="apply_coupon"]', function(){		        	
		        		quick_order_ajax(parent,true);
			            return false;
			        });
					$( document.body ).on( 'click', '#html<?php echo $htmlid;?> .adminz_quick_order_submit', function(){					
			        	quick_order_ajax(parent,false);
			        });
			        
		        	function quick_order_ajax(parent,is_test = true){	     
		        		var product = parent.find("input[name='adminz_product_id']").val();
		        		var variation_id = parent.find('input.variation_id');
		        		if(variation_id.length && variation_id.val()>0){
		        			product = variation_id.val();
		        		}	        			        		
						var qty = parent.find(".input-text.qty").val();
						if(!product) return;	  
						if(!qty) return;

		        		var coupon = parent.find("input[name='coupon_code']").val(); 
		        		var payment_method = parent.find("input[name='payment_method']:checked").val(); 	        		

		        		var billing = {};
		        		parent.find(".woocommerce-billing-fields").find('input,select,textarea').each(function(){
		        			var name = $(this).attr('name').replace("billing_","");
		        			billing[name] = $(this).val();	        			
		        		});
		        		var shipping = {};
		        		var use_shipping = parent.find("#ship-to-different-address-checkbox").prop('checked');
		        		if(use_shipping){
		        			parent.find(".woocommerce-shipping-fields").find('input,select,textarea').each(function(){
			        			var name = $(this).attr('name').replace("shipping_","");
			        			shipping[name] = $(this).val();	        			
			        		});
		        		};
		        		var additional = {};
		        		parent.find(".woocommerce-additional-fields").find('input,select,textarea').each(function(){
		        			additional[$(this).attr('name')] = $(this).val();
		        		});

						$.ajax({
	                        type : "post",
	                        dataType : "json",
	                        url : '<?php echo admin_url('admin-ajax.php'); ?>',
	                        data : {
	                            action:'adminz_quick_order_ajax',
	                            is_test: is_test,
					        	product: {
					        		product: product,
				        			qty: qty,
				        			coupon: coupon,
				        			payment_method: payment_method
				        		},
				        		billing: billing,
				        		shipping: shipping,
				        		additional: additional
	                        },       
	                        context: this,                 
	                        beforeSend: function(){
	                        	$(".buttons_added").find("input").attr("disabled","disabled");
	                        	$("#html<?php echo $htmlid;?>").find(".adminz_order_total_review").html('<?php echo __("Loading&hellip;","woocommerce")?>')
	                        },
	                        success: function(response) { 
	                        	if(is_test){
	                        		var html = $.parseHTML( "<p class='lead'>"+response.data+"</p>" );	
					        		$("#html<?php echo $htmlid;?>").find(".adminz_order_total_review").empty();		        		
					        		$("#html<?php echo $htmlid;?>").find(".adminz_order_total_review").append(html);
		                        	$(".buttons_added").find("input").removeAttr("disabled");
	                        	}else{
	                        		window.location.href = response.data;
	                        	}
	                        },
	                        error: function( jqXHR, textStatus, errorThrown ){
	                        	console.log( 'Administrator Z: The following error occured: ' + textStatus, errorThrown );
	                        }
	                    });
					};
		        })(jQuery);
	    	});
	    </script>
	    <?php
	    $cssjs = ob_get_clean();
	    add_action('wp_footer',function() use ($cssjs){
	    	echo $cssjs;
	    });
	    ?>

	    <?php
	    ob_start();
	    $args = array(
	        'p'=>$product_id,
	        'post_type'   => 'product',
	        'post_status' => 'publish',
	        'posts_per_page' => 1,
	    );
	    $the_query = new WP_Query( $args );	    

	    if ( $the_query->have_posts() ) {
	        while ( $the_query->have_posts() ) : $the_query->the_post();
	            ?>
	            <div id="html<?php echo $htmlid; ?>" class="row woocommerce adminz_quick_order">
	            	
	                <div class="product-gallery col large-6">
	                    <?php 
	                    global $product;
	                    woocommerce_template_single_title();
	                    woocommerce_template_single_rating();
	                    echo get_the_post_thumbnail();
	                    echo "<div style='height:30px;'></div>";
	                    echo $product->get_price_html();
	                    woocommerce_template_single_add_to_cart();
	                    ?>
	                    <input type="hidden" name="adminz_product_id" value="<?php echo get_the_ID();?>">	                    
	                    <input type="hidden" name="adminz_product_price" value="<?php if($product->get_sale_price()){echo $product->get_sale_price(); }else{echo $product->get_regular_price(); } ?>">
	                </div>
	                <div class="col large-6">
	                    <?php 
	                    $checkout = WC()->checkout();
	                    $checkout->checkout_form_billing();
	                    $checkout->checkout_form_shipping();
	                    ?>
	                    <?php woocommerce_checkout_coupon_form(); ?>	                    
	                    <p class="adminz_order_total_review"></p>	
	                    <?php
	                    if ( WC()->cart->needs_payment() ) {
							$available_gateways = WC()->payment_gateways()->get_available_payment_gateways();
							WC()->payment_gateways()->set_current_gateway( $available_gateways );
						} else {
							$available_gateways = array();
						}
	                    wc_get_template(
							'checkout/payment.php',
							array(
								'checkout'           => WC()->checkout(),
								'available_gateways' => $available_gateways,
								'order_button_text'  => apply_filters( 'woocommerce_order_button_text', __( 'Place order', 'woocommerce' ) ),
							)
						);
						if(Adminz::is_flatsome()){
							echo do_shortcode('[button text="'.__("Place order","woocommerce").'" color="secondary" size="" expand="true" icon="icon-shopping-bag" class="adminz_quick_order_submit" icon_pos="left"  link=""]');
						}else{
							echo "<button class='adminz_quick_order_submit'>".__("Place order","woocommerce")."</button>";
						}
	                     ?>
	                </div>
	            </div>
	            <?php
	        endwhile;
	    } else {
	        echo __( 'No products found' );
	    }
	    wp_reset_postdata();		
		?>
		<?php
		return apply_filters('adminz_output_debug',ob_get_clean());
}