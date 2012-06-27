<?php
   /*
   Plugin Name: Ascending Posts Plugin
   Plugin URI: http://www.flyplugins.com
   Description: This plugin adds a feature to a post category to allow the posts in that particular category to be displayed in ascending or descending order by date.
   Version: 1.2
   Author: Fly Plugins
   Author URI: http://www.flyplugins.com
   License: GPL2
   */


require_once('fly_plugins_tools.php');		
add_action('admin_menu', 'fly_plugin_menu');
function fly_plugin_menu() {
	add_options_page('Ascending Post Configuration', 'Ascending Post' , 8, __FILE__, 'admin_page');
}

function admin_page() {
?>
<body>
<div class="wrap"><a href="http://www.flyplugins.com">
	<div id="fly-icon" style="background: url(<?php echo plugins_url('',__FILE__); ?>/images/fly32x32.png) no-repeat;" class="icon32"><br /></div></a>
<h2>Ascending Post Configuration</h2> <br />
				<div class="postbox-container" style="width:70%;">
					<div class="metabox-holder">	
						<div class="meta-box-sortables">
						In order to change the order of a category from descending to ascending by date, follow these simple instructions:
						<ol>
						<li>Create a new category or if you want to modify a currently existing category skip to step #2.</li>
						<li><strong>Click edit</strong> under the newly created category, or <strong>click edit</strong> under the already existing category.</li>
						<li>Next to the "Sort Order" option, select <strong>"ascending"</strong> or <strong>"descending"</strong> from the drop down menu based upon your choice for that particular category. By default, "descending" is selected which is the default order for WordPress.</li>
						</div>
					</div>
				</div>
				<div class="postbox-container" style="width:20%;">
					<div class="metabox-holder">	
						<div class="meta-box-sortables">
							<?php
								$flydisplay = new Fly_Plugin_Admin();
								$flydisplay->donate();
								$flydisplay->plugin_support();
								$flydisplay->fly_news(); 
							?>
						</div>
					</div>
				</div>
</div>
</body>
<?php						
}
/* Start Ascending Posts */
 	function ascending_post($wp_query) {
 		global $cat, $cat_meta;
 		if( is_archive() || is_category() ) {
 			if (isset( $cat_meta[$cat]['order'] ) &&  $cat_meta[$cat]['order'] == 'ASC' ) {
 				query_posts($querystring . "cat=".$cat."&order=ASC");
 			}
 		}
 	} 
add_action('wp_head', 'ascending_post');
/* End Ascending Posts */

/* Add Admin Post Ascending Option */
add_action ( 'edit_category_form_fields', 'extra_category_fields' );
	function extra_category_fields( $tag ) {
 		global $theme_css, $cat_meta;
 			$t_id = $tag->term_id;
 			$curr_meta = $cat_meta[$t_id];
 			$direction=( $curr_meta && isset( $curr_meta['order'] ) && $curr_meta['order'] ) ? $curr_meta['order'] : "DESC";

$sort_order = array(
	'DESC' => array(
 	'value' => 'DESC',
 	'label' => 'Descending'
 	),
 	'ASC' => array(
 	'value' => 'ASC',
 	'label' => 'Ascending'
 	),
);
?>
<tr>
 	<th scope="row" valign="top"><label for="cat_meta[order]"><?php _e('Sort Order'); ?></label></th>
 	<td>
 		<select id="cat_meta[order]" name="cat_meta[order]">
<?php
	foreach ( $sort_order as $option ) :
     	$label = $option['label'];
     	$selected = '';
     		if ( $direction && $direction ==  $option['value'] ) $selected = 'selected="selected"';
     			echo '<option style="padding-right: 10px;" value="' . esc_attr( $option['value'] ) . '" ' . $selected . '>' . $label . '</option>';
   	endforeach;
?>
 		</select>
 	</td>
</tr>
<?php
}
add_action ( 'edited_category', 'save_extra_category_fields');
	function save_extra_category_fields( $term_id ) {
 		global $cat_meta;
 			if ( isset( $_POST['cat_meta'] ) ) {
   				$t_id = $term_id;
   				$cat_keys = array_keys($_POST['cat_meta']);
   				$curr_meta = array();
   			foreach ($cat_keys as $key){
     			if (isset($_POST['cat_meta'][$key])){
       				$curr_meta[$key] = $_POST['cat_meta'][$key];
     			}
   			}
   		$cat_meta[$t_id] = $curr_meta;
   		update_option( "theme_cat_meta", $cat_meta );
 	}
}
$cat_meta = get_option("theme_cat_meta");
/* End - Add Admin Post Ascending Option */
?>