<?php
/*
Plugin Name: Simnor Shortcodes
Plugin URI: http://simonmakes.com/plugins/simnor-shortcodes/
Description: A user friendly shortcodes plugin.
Version: 1.8
Author: Simon North
Author URI: http://simonmakes.com
*/

/* Variables */
$sn_simnor_shortcodes_path = dirname(__FILE__);
$sn_simnor_shortcodes_main_file = dirname(__FILE__).'/simnor-shortcodes.php';
$sn_simnor_shortcodes_directory = plugin_dir_url($sn_simnor_shortcodes_main_file);
$sn_simnor_shortcodes_name = "Simnor Shortcodes";

/* Add shortcodes scripts file */
function simnor_shortcodes_add_scripts() {
	global $sn_simnor_shortcodes_directory, $sn_simnor_shortcodes_path;
	if(!is_admin()) {
		
		/* Includes */
		include($sn_simnor_shortcodes_path.'/includes/shortcodes.php');

		wp_enqueue_style('simnor_shortcodes', $sn_simnor_shortcodes_directory.'/includes/shortcodes.css');
		
		wp_enqueue_script('jquery');
		wp_register_script('simnor_shortcodes_js', $sn_simnor_shortcodes_directory.'/includes/shortcodes.js', 'jquery');
		wp_enqueue_script('simnor_shortcodes_js');
		
	} else {
		
		wp_enqueue_style( 'wp-color-picker' );
	    wp_enqueue_script( 'wp-color-picker' );
	    		
	}
	
	/* Font Awesome */
	wp_enqueue_style('simnor_shortcodes_fontawesome', $sn_simnor_shortcodes_directory.'/fonts/fontawesome/css/font-awesome.min.css');
	wp_enqueue_style('simnor_shortcodes_fontello', $sn_simnor_shortcodes_directory.'/fonts/fontello/css/fontello.css');
	
}
add_filter('init', 'simnor_shortcodes_add_scripts');

/* Add button to TinyMCE */
add_action('admin_head', 'simnor_shortcodes_addbuttons');
function simnor_shortcodes_addbuttons() {
    global $typenow;
    // check user permissions
    if ( !current_user_can('edit_posts') && !current_user_can('edit_pages') ) {
   	return;
    }
	// check if WYSIWYG is enabled
	if ( get_user_option('rich_editing') == 'true') {
		add_filter("mce_external_plugins", "add_simnor_shortcodes_tinymce_plugin");
		add_filter('mce_buttons', 'register_simnor_shortcodes_button');
	}
}
function add_simnor_shortcodes_tinymce_plugin($plugin_array) {
   	$plugin_array['simnor_shortcodes_button'] = plugins_url( '/includes/tinymce_button.js', __FILE__ );
   	return $plugin_array;
}
function register_simnor_shortcodes_button($buttons) {
   array_push($buttons, "simnor_shortcodes_button");
   return $buttons;
}