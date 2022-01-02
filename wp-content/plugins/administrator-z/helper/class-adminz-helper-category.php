<?php 
namespace Adminz\Helper;
class ADMINZ_Helper_Category{
	public $enabled;
	function __construct() {
		$this->tinymce_category_helper();
	}
	function tinymce_category_helper(){
		if($this->enabled) return;
		remove_filter( 'pre_term_description', 'wp_filter_kses' );
		remove_filter( 'term_description', 'wp_kses_data' );
		add_filter( 'term_description', 'do_shortcode');
		add_action ( 'edited_terms', function ( $term_id ) {
		    if ( isset( $_POST['tag_meta'] ) ) {
		        $t_id = $term_id;
		        $tag_meta = get_option( "tag_$t_id");
		        $tag_keys = array_keys ($_POST['tag_meta']);
		            foreach ($tag_keys as $key){
		            if (isset($_POST['tag_meta'][$key])){
		                $tag_meta[$key] =stripslashes_deep($_POST['tag_meta'][$key]);
		            }
		        }
		        update_option( "tag_$t_id", $tag_meta );
		    }
		});     

		add_filter('deleted_term_taxonomy', function ($term_id) {
		    if($_POST['taxonomy'] == 'category'):
		        $tag_extra_fields = get_option(Category_Extras);
		        unset($tag_extra_fields[$term_id]);
		        update_option(Category_Extras, $tag_extra_fields);
		    endif;
		});

		add_action('admin_head', function () {
		    $screen = get_current_screen();
		    if ( $screen->id == 'edit-category' ) {?>
		    <script type="text/javascript">
		        jQuery(function($) {
		        $('#wp-description-wrap').hide();
		        }); 
		    </script> <?php
		    } 
		    if ( $screen->base == 'term' ) {?>
		    <script type="text/javascript">
		        jQuery(function($) {
		        $('.term-description-wrap').hide();
		        }); 
		    </script> <?php }
		});		
		$this->enabled = 1;
	}
}