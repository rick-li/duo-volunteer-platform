<?php
add_action('wp_loaded','etm_when_wp_is_loaded');
function etm_when_wp_is_loaded(){
	global $wpdb,$userdata,$total_found;
	
	$tmp_array = array('etm_tools_get_pages','etm_tools_get_meta','etm_tools_get_post_tags','etm_tools_get_posts_types','etm_tools_get_post_single','etm_tools_get_menu_group','etm_tools_get_menu_single','etm_tools_get_plugins','etm_tools_get_plugins_themes_folder','etm_tools_get_themes','etm_tools_get_themes_folder','etm_tools_get_addon','etm_tools_get_addon_folder','etm_tools_get_site_options');
	
	$current_data = '';
	$total_found = 0;
	
	if(!empty($_POST['retrive_fn']) and in_array($_POST['retrive_fn'], $tmp_array)){
		$current_data = $_POST['retrive_fn']();
	}
	
	die(json_encode(array('R'=>'OK','TOTAL'=>$total_found,'TMPDATA'=>$current_data)));
}
?>