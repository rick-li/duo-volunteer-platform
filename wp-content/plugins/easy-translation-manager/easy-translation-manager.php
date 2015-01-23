<?php
/**
Plugin Name: Easy Translation Manager for WordPress
Plugin URI: http://plugins.righthere.com/easy-translation-manager/
Description: Translate your WordPress powered website easier and faster than ever. Easy Translation Manager lets you translate Pages, Posts, Post Tags, Post Categories, Custom Post Types, Custom Fields, Permalinks, Menus, Plugins, Add-ons and Themes. Supports Custom Capabilities and is compatible with WordPress Multisite. Support for WordPress SEO by Yoast. 
Version: 4.0.1.54597
Author: Rasmus R. Sorensen (RightHere LLC)
Author URI: http://plugins.righthere.com
**/

if(defined('EASY_TRANSLATION_MANAGER_PATH')) throw new Exception( __('A duplicate of this plugin/add-on is already active.','etm') );

$upload_dir = wp_upload_dir();
define("EASY_TRANSLATION_MANAGER_PATH", ABSPATH . 'wp-content/plugins/' . basename(dirname(__FILE__)).'/includes/' );
define("EASY_TRANSLATION_MANAGER_OPTIONPANEL", ABSPATH . 'wp-content/plugins/' . basename(dirname(__FILE__)).'/' );  
define("EASY_TRANSLATION_MANAGER_URL", trailingslashit(get_option('siteurl')) . 'wp-content/plugins/' . basename(dirname(__FILE__)) . '/includes/' ); 

define("EASY_TRANSLATION_MANAGER_UPLOAD_URL_LANG",trailingslashit(get_option('siteurl')).'wp-content/languages');
define("EASY_TRANSLATION_MANAGER_UPLOAD_PATH_LANG",ABSPATH.'wp-content/languages');
define("EASY_TRANSLATION_MANAGER_UPLOAD_URL",trailingslashit(get_option('siteurl')).'wp-content/languages/etm_manager');
define("EASY_TRANSLATION_MANAGER_UPLOAD_PATH",ABSPATH.'wp-content/languages/etm_manager');

define("ETM_VERSION",'4.0.1'); 
define("ETM_SLUG", plugin_basename( __FILE__ ));

require_once(EASY_TRANSLATION_MANAGER_PATH.'inc/class.widgets.php');
require_once(EASY_TRANSLATION_MANAGER_PATH.'inc/class.flag_menu.php');
require_once(EASY_TRANSLATION_MANAGER_PATH.'inc/fn.tools.php');
require_once(EASY_TRANSLATION_MANAGER_PATH.'inc/fn.install.php');
require_once(EASY_TRANSLATION_MANAGER_PATH.'inc/class.flag_menu.php');
require_once(EASY_TRANSLATION_MANAGER_PATH.'inc/class.nav_menu.php'); 
require_once(EASY_TRANSLATION_MANAGER_PATH.'inc/class.easy_translation_manager.php'); 




register_activation_hook(__FILE__, 'etm_install'); 
register_deactivation_hook( __FILE__, 'etm_uninstall_capabilities' );
global $easy_translation_manager_plugin,$easy_translation_manager_flag_menu,$easy_translation_manager_nav_menu;
$easy_translation_manager_plugin = new easy_translation_manager_plugin();
$easy_translation_manager_flag_menu = new easy_translation_manager_flag_menu();
$easy_translation_manager_nav_menu = new easy_translation_manager_nav_menu();
?>