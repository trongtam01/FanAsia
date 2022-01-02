<?php

if (!defined('ABSPATH'))
  exit;

if (!class_exists('WPACPTDCF7_Product_Control')) {

    class WPACPTDCF7_Product_Control {

        protected static $WPACPTDCF7_instance;
   
        function init() {
            if ( is_admin() ) {
                add_action( 'admin_init', array($this, 'wpacptdcf7_add_products_tag_generator_menu'), 25 );
            }
        }

        function wpacptdcf7_add_products_tag_generator_menu() {
            if (class_exists('WPCF7_TagGenerator')){
                $tag_generator = WPCF7_TagGenerator::get_instance();
                $tag_generator->add( 'products', __( 'WooCommerce Products drop-down menu', CF7WPAPLOC_DOMAIN ),
                array($this, 'wpacptdcf7_tag_products_generator_menu') );
            }
        }

        function wpacptdcf7_tag_products_generator_menu( $contact_form, $args = '' ) {
            $args = wp_parse_args( $args, array() );
            $type = 'products';
            $description = __( "Generate a form-tag for a WooCommerce Products drop-down menu. For more details, see %s.", CF7WPAPLOC_DOMAIN ); ?>
            <div class="control-box">
                <fieldset>
                    <legend><?php echo esc_html( $description ) ; ?></legend>

                    <table class="form-table">
                    <tbody>
                        <tr>
                        <th scope="row"><?php echo esc_html( __( 'Field type', CF7WPAPLOC_DOMAIN ) ); ?></th>
                        <td>
                            <fieldset>
                            <legend class="screen-reader-text"><?php echo esc_html( __( 'Field type', CF7WPAPLOC_DOMAIN ) ); ?></legend>
                            <label><input type="checkbox" name="required" /> <?php echo esc_html( __( 'Required field', CF7WPAPLOC_DOMAIN ) ); ?></label>
                            </fieldset>
                        </td>
                        </tr>
                        <tr>
                        <th scope="row"><label for="<?php echo esc_attr( $args['content'] . '-name' ); ?>"><?php echo esc_html( __( 'Name', CF7WPAPLOC_DOMAIN ) ); ?></label></th>
                        <td><input type="text" name="name" class="tg-name oneline" id="<?php echo esc_attr( $args['content'] . '-name' ); ?>" /></td>
                        </tr>
                        <tr>
                        <th scope="row"><label>Select Filter Option</label></th>
                            <td>
                                <select name="filter_options" id="filter_options">
                                    <option value="">--- Select Option ---</option>
                                    <option value="category">Category</option>
                                    <option value="tags">Tags</option>
                                    <option value="featured">Featured Product</option>
                                    <option value="bestselling">Best Selling Product</option>
                                </select>
                            </td>
                        </tr>
                        <tr id="hide_cat_box">
                        <th scope="row"><label><?php echo esc_html( __( 'Category', CF7WPAPLOC_DOMAIN ) ); ?></label></th>
                        <td>
                                <?php
                                    $orderby = 'name';
                                    $order = 'asc';
                                    $hide_empty = true;
                                    $cat_args = array(
                                        'orderby'    => $orderby,
                                        'order'      => $order,
                                        'hide_empty' => $hide_empty,
                                    );
                                     
                                    $product_categories = get_terms( 'product_cat', $cat_args );
                                     
                                    if( !empty($product_categories) ){
                                    foreach ($product_categories as $key => $category) {
                                ?>
                                <input type="radio" name="category" value="<?php echo $category->name;?>" class="option"> <?php echo $category->name;?>
                                <?php } } ?>
                        </td>
                        </tr>
                        <tr id="hide_tags_box">
                        <th scope="row"><label><?php echo esc_html( __( 'Tags', CF7WPAPLOC_DOMAIN ) ); ?></label></th>
                        <td>
                                <?php
                                    $tag_terms = get_terms( 'product_tag' );
                                    if ( ! empty( $tag_terms ) && ! is_wp_error( $tag_terms ) ){
                                      foreach ( $tag_terms as $tag_term ) {
                                ?>
                               <input type="radio" name="tags" value="<?php echo $tag_term->name;?>" class="option"> <?php echo $tag_term->name;?>
                                <?php } } ?>
                        </td>
                        </tr>
                        <tr><th><a href="#" target="_blank" class="oc_pro_link">Go Pro</a></th></tr>
                        <tr class="OCWPCF7_fetures">
                            <th scope="row"><?php echo esc_html( __( 'Options', CF7WPAPLOC_DOMAIN ) ); ?></th>
                            <td>
                                <fieldset>
                                <label><input type="checkbox" name="multiple" class="option" /> <?php echo esc_html( __( 'Allow multiple selections', CF7WPAPLOC_DOMAIN ) ); ?></label><br />
                                <label><input type="checkbox" name="include_blank" class="option" /> <?php echo esc_html( __( 'Insert a blank item as the first option', CF7WPAPLOC_DOMAIN ) ); ?></label>
                                <label><input type="checkbox" name="enable_search_box" class="option" /> <?php echo esc_html( __( 'Enable Search box on List Dropdown.', CF7WPAPLOC_DOMAIN ) ); ?></label>
                                </fieldset>
                            </td>
                        </tr>
                        <tr class="OCWPCF7_fetures">
                            <th scope="row"><?php echo esc_html( __( 'Metadata', CF7WPAPLOC_DOMAIN ) ); ?></th>
                            <td>
                                <fieldset>
                                <input type="text" name="meta_data" class="meta_data oneline option" id="<?php echo esc_attr( $args['content'] . '-meta_data' ); ?>" />
                                <br>
                                <span class="description">
                                    <?php echo esc_html( __( 'Use pipe-separated post attributes (e.g.sku|review|date|time|slug|author|category|tags|meta_key) per field.', CF7WPAPLOC_DOMAIN ) ); ?>
                                </span>
                                </fieldset>
                            </td>
                        </tr>
                        <tr class="OCWPCF7_fetures">
                            <th scope="row"><?php echo esc_html( __( 'Image Options', CF7WPAPLOC_DOMAIN ) ); ?></th>
                            <td>
                                <fieldset>
                                <label><input type="checkbox" name="show_image" class="option" checked/> <?php echo esc_html( __( 'Show Or Hide Image', CF7WPAPLOC_DOMAIN ) ); ?></label><br />
                                <label><input type="number" name="image_size" class="image_size oneline option" id="<?php echo esc_attr( $args['content'] . '-image_size' ); ?>"  min="0" placeholder="80"/> <?php echo esc_html( __( 'Custom Image Size (Width)', CF7WPAPLOC_DOMAIN ) ); ?></label>
                                </fieldset>
                            </td>
                        </tr>
                        <tr class="OCWPCF7_fetures">
                            <th scope="row"><?php echo esc_html( __( 'Content Options', CF7WPAPLOC_DOMAIN ) ); ?></th>
                            <td>
                                <fieldset>
                                <label><input type="checkbox" name="show_p_price" class="option" checked/> <?php echo esc_html( __( 'Show Or Hide Product Price', CF7WPAPLOC_DOMAIN ) ); ?></label>
                                </fieldset>
                            </td>
                        </tr>
                        
                        <th scope="row"><label for="<?php echo esc_attr( $args['content'] . '-id' ); ?>"><?php echo esc_html( __( 'Id attribute', CF7WPAPLOC_DOMAIN ) ); ?></label></th>
                        <td><input type="text" name="id" class="idvalue oneline option" id="<?php echo esc_attr( $args['content'] . '-id' ); ?>" /></td>
                        </tr>
                        <tr>
                        <th scope="row"><label for="<?php echo esc_attr( $args['content'] . '-class' ); ?>"><?php echo esc_html( __( 'Class attribute', CF7WPAPLOC_DOMAIN ) ); ?></label></th>
                        <td><input type="text" name="class" class="classvalue oneline option" id="<?php echo esc_attr( $args['content'] . '-class' ); ?>" /></td>
                        </tr>
                        <input type="radio" name="featured" value="featured" class="option" id="featured_pro" style="display: none">
                        <input type="radio" name="bestselling" value="bestselling" class="option" id="bestselling_pro" style="display: none">
                    </tbody>
                    </table>
                </fieldset>
            </div>
            <div class="insert-box">
                <input type="text" name="<?php echo $type; ?>" class="tag code" readonly="readonly" onfocus="this.select()" />

                <div class="submitbox">
                <input type="button" class="button button-primary insert-tag" value="<?php echo esc_attr( __( 'Insert Tag', CF7WPAPLOC_DOMAIN ) ); ?>" />
                </div>

                <br class="clear" />

                <p class="description mail-tag"><label for="<?php echo esc_attr( $args['content'] . '-mailtag' ); ?>"><?php echo sprintf( esc_html( __( "To use the value input through this field in a mail field, you need to insert the corresponding mail-tag (%s) into the field on the Mail tab.", CF7WPAPLOC_DOMAIN ) ), '<strong><span class="mail-tag"></span></strong>' ); ?><input type="text" class="mail-tag code hidden" readonly="readonly" id="<?php echo esc_attr( $args['content'] . '-mailtag' ); ?>" /></label></p>
            </div>
            <?php
        }

        public static function WPACPTDCF7_instance() {
          if (!isset(self::$WPACPTDCF7_instance)) {
            self::$WPACPTDCF7_instance = new self();
            self::$WPACPTDCF7_instance->init();
          }
          return self::$WPACPTDCF7_instance;
        }
    }
    WPACPTDCF7_Product_Control::WPACPTDCF7_instance();
}
