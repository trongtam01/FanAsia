<?php 
namespace Adminz\Admin;

class ADMINZ_Me extends Adminz {
	public $options_group = "adminz_me";
	public $title = "Help & Support";
	static $slug = 'adminz_me';
	function __construct() {
		$this->title = $this->get_icon_html('headset').$this->title;
		add_filter( 'adminz_setting_tab', [$this,'register_tab']);		
	}
	function tab_html(){
		ob_start();
		?>
		<form method="post" action="options.php">
			<?php 
			settings_fields($this->options_group);
	        do_settings_sections($this->options_group);
			?>

			<table class="form-table">
				<tr valign="top">
	                <th scope="row">Translate</th>
	                <td>
	                	Compatibility with Poly Lang. All text, setting in plugin you can translate in <strong>Dashboard-> languages-> String translate</strong> and seach adminz

	                </td>
	            </tr>
	            <tr valign="top">
	                <th scope="row">About Author</th>
	                <td>
	                	<a target="_blank" class="adminz_me_link" href="https://quyle91.github.io/administratorz/">Documents</a>
	                	<a target="_blank" class="adminz_me_link small" href="https://wordpress.org/support/plugin/administrator-z/">Report bugs</a>	                	
	                	<a target="_blank" class="adminz_me_link small" href="https://quyle91.github.io">About me</a>
	                </td>
	            </tr>	            	            
	        </table>	
	        <style type="text/css">
	        	.adminz_me_link{
	        		display: inline-block; 
	        		margin-bottom: 5px; 
	        		padding: 10px; 	        		
	        		border:  1px solid gray;	        		
	        		font-size: 3em;
	        		text-decoration: none;	        		
	        		vertical-align: bottom;
	        	}
	        	.adminz_me_link.small{
	        		font-size: 1.5em;
	        	}
	        </style>		
		</form>
		<?php
		return ob_get_clean();
	}
	function register_tab($tabs) {
 		if(!$this->title) return;
        $tabs[] = array(
            'title' => $this->title,
            'slug' => self::$slug,
            'html' => $this->tab_html()
        );
        return $tabs;
    }
}