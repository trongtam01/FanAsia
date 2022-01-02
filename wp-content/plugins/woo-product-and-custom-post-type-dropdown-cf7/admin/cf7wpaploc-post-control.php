<?php

if (!defined('ABSPATH'))
  exit;

if (!class_exists('WPACPTDCF7_Post_Control')) {

    class WPACPTDCF7_Post_Control {

        protected static $WPACPTDCF7_instance;

        public static function WPACPTDCF7_instance() {
          if (!isset(self::$WPACPTDCF7_instance)) {
            self::$WPACPTDCF7_instance = new self();
            self::$WPACPTDCF7_instance->init();
          }
          return self::$WPACPTDCF7_instance;
        }

        function init() {
           if ( is_admin() ) {
                add_action( 'admin_init', array($this, 'wpacptdcf7_add_post_control_generator_menu'), 25 );
            }
        }

        function wpacptdcf7_add_post_control_generator_menu() {
            if (class_exists('WPCF7_TagGenerator')){
                $tag_generator = WPCF7_TagGenerator::get_instance();
                $tag_generator->add( 'posts', __( 'Posts drop-down menu', CF7WPAPLOC_DOMAIN ),
                    array($this, 'wpacptdcf7_post_control_generator_menu') );
            }
        }

        function wpacptdcf7_post_control_generator_menu( $contact_form, $args = '' ) {
            $args = wp_parse_args( $args, array() );
            $type = 'posts';
            $description = __( "Generate a form-tag for a group of post drop-down menu. For more details, see %s.", CF7WPAPLOC_DOMAIN ); ?>
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
                            <th scope="row"><label>Post Type</label></th>
                            <td>
                                <?php
                                    $ocwpcf7_args = array(
                                                    'public'   => true
                                                );
                                    $ocwpcf7_output = 'names'; // names or objects, note names is the default
                                    $ocwpcf7_operator = 'and'; // 'and' or 'or'
                                    $ocwpcf7_post_types = get_post_types( $ocwpcf7_args, $ocwpcf7_output, $ocwpcf7_operator ); 
                                    foreach ( $ocwpcf7_post_types  as $ocwpcf7_post_type ) { ?>
                                        <label>
                                            <input type="radio" name="post_type" value="<?php echo $ocwpcf7_post_type; ?>" class="option" id="<?php echo $ocwpcf7_post_type.'_ocwpcf7'; ?>"  <?php if($ocwpcf7_post_type == 'post'){echo "checked";} ?>>
                                            <?php echo $ocwpcf7_post_type; ?>
                                        </label>
                                    <?php } ?>
                            </td>
                        </tr>
                        <tr id='hide_filter_cat_box'>
                            <th scope="row"><label><?php echo esc_html( __( 'Filter Option', CF7WPAPLOC_DOMAIN ) ); ?></label></th>
                            <td>
                                <select name="filter_post_options" id="filter_post_options">
                                    <option value=""><?php echo esc_html( __( '--- Select Option ---', CF7WPAPLOC_DOMAIN ) ); ?></option>
                                    <option value="category"><?php echo esc_html( __( 'Category', CF7WPAPLOC_DOMAIN ) ); ?></option>
                                    <option value="tags"><?php echo esc_html( __( 'Tags', CF7WPAPLOC_DOMAIN ) ); ?></option>
                                </select>
                            </td>
                        </tr>
                        <tr id='hide_post_cat_box' style="display: none">
                            <th scope="row"><label><?php echo esc_html( __( 'Category', CF7WPAPLOC_DOMAIN ) ); ?></label></th>
                            <td>
                                <?php
                                    $cat_x = 1;
                                    $ocwpcf7_categories = get_categories( array(
                                                            'orderby' => 'name',
                                                            'order'   => 'ASC'
                                                        ) ); 
                                    foreach ( $ocwpcf7_categories  as $ocwpcf7_category ) { ?>
                                        <label>
                                            <input type="radio" name="post_category" value="<?php echo $ocwpcf7_category->slug; ?>" class="option"> <?php echo $ocwpcf7_category->name; ?><br>
                                        </label>
                                    <?php }  ?>
                            </td>
                        </tr>
                        <tr id='hide_post_tags_box' style="display: none">
                            <th scope="row"><label><?php echo esc_html( __( 'Tags', CF7WPAPLOC_DOMAIN ) ); ?></label></th>
                            <td>
                                <?php
                                    $ocwpcf7_tags = get_tags();
                                    $tags_x = 1;
                                    foreach ( $ocwpcf7_tags  as $ocwpcf7_tag ) { ?>
                                        <label>
                                            <input type="radio" name="post_tag" value="<?php echo $ocwpcf7_tag->slug; ?>" class="option">
                                                <?php echo $ocwpcf7_tag->name; ?><br>
                                        </label>
                                <?php } ?>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label><?php echo esc_html( __( 'Order by', CF7WPAPLOC_DOMAIN ) ); ?></label></th>
                            <td>
                                <label>
                                    <input type="radio" name="orderby" value="date" class="option" checked><?php echo esc_html( __( 'Date', CF7WPAPLOC_DOMAIN ) ); ?>
                                </label>
                                <label>
                                    <input type="radio" name="orderby" value="id" class="option"><?php echo esc_html( __( 'Order by post ID', CF7WPAPLOC_DOMAIN ) ); ?>
                                </label>
                                <label>
                                    <input type="radio" name="orderby" value="author" class="option"><?php echo esc_html( __( 'Author', CF7WPAPLOC_DOMAIN ) ); ?>
                                </label>
                                <label>
                                    <input type="radio" name="orderby" value="random" class="option"><?php echo esc_html( __( 'Random order', CF7WPAPLOC_DOMAIN ) ); ?>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label><?php echo esc_html( __( 'Sort order', CF7WPAPLOC_DOMAIN ) ); ?></label></th>
                            <td>
                                <label>
                                    <input type="radio" name="sortorder" value="DESC" class="option" checked><?php echo esc_html( __( 'Descending', CF7WPAPLOC_DOMAIN ) ); ?>
                                </label>
                                <label>
                                    <input type="radio" name="sortorder" value="ASC" class="option"><?php echo esc_html( __( 'Ascending', CF7WPAPLOC_DOMAIN ) ); ?>
                                </label>
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
                                    <?php echo esc_html( __( 'Use pipe-separated post attributes (e.g.date|time|slug|author|category|tags|meta_key) per field.', CF7WPAPLOC_DOMAIN ) ); ?>
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
                        <tr>
                            <th scope="row"><?php echo esc_html( __( 'Content Options', CF7WPAPLOC_DOMAIN ) ); ?></th>
                            <td>
                                <fieldset>
                                <label><input type="checkbox" name="show_content" class="option" checked/> <?php echo esc_html( __( 'Show Or Hide Content', CF7WPAPLOC_DOMAIN ) ); ?></label><br />
                                <input type="number" name="content_limit" class="content_limit oneline option" id="<?php echo esc_attr( $args['content'] . '-content_limit' ); ?>"  min="0" placeholder="15"/>
                                <br>
                                <span class="description">
                                 <?php echo esc_html( __( 'Define the number of words for the excerpt. Default "15"', CF7WPAPLOC_DOMAIN ) ); ?>
                                </span>
                                </fieldset>
                            </td>
                        </tr class="OCWPCF7_fetures">
                        <tr>
                        <th scope="row"><label for="<?php echo esc_attr( $args['content'] . '-id' ); ?>"><?php echo esc_html( __( 'Id attribute', CF7WPAPLOC_DOMAIN ) ); ?></label></th>
                        <td><input type="text" name="id" class="idvalue oneline option" id="<?php echo esc_attr( $args['content'] . '-id' ); ?>" /></td>
                        </tr>
                        <tr>
                        <th scope="row"><label for="<?php echo esc_attr( $args['content'] . '-class' ); ?>"><?php echo esc_html( __( 'Class attribute', CF7WPAPLOC_DOMAIN ) ); ?></label></th>
                        <td><input type="text" name="class" class="classvalue oneline option" id="<?php echo esc_attr( $args['content'] . '-class' ); ?>" /></td>
                        </tr>
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
    }
    WPACPTDCF7_Post_Control::WPACPTDCF7_instance();
}
