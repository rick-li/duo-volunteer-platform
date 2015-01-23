<?php

function etm_install($network_aktivatede){
    global $wpdb; 

	etm_install_capabilities();

    	$queries[] =  "CREATE TABLE IF NOT EXISTS `#__etm_plugin_index` (
  		`id` int(11) NOT NULL auto_increment,
  		`folder_name` varchar(255) NOT NULL,
  		`category_type` varchar(255) NOT NULL,
  		`default_string` text character set utf8 collate utf8_unicode_ci NOT NULL,
  		`mo_tag` varchar(255) NOT NULL,
  		`manual_added` int(1) NOT NULL,
  		`file` varchar(255) NOT NULL,
  		`create_date` timestamp NOT NULL default CURRENT_TIMESTAMP,
  		`create_ip` char(32) default NULL,
  		`create_user` int(11) default NULL,
  		`deleted` int(1) default '0',
  		PRIMARY KEY  (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

    	$queries[] =  "CREATE TABLE IF NOT EXISTS `#__etm_plugin_string` (
  		`id` int(11) NOT NULL auto_increment,
  		`lang_code` varchar(2) NOT NULL,
  		`lang_index_id` int(25) NOT NULL,
  		`translatede_string` text character set utf8 collate utf8_unicode_ci NOT NULL,
  		`create_date` timestamp NOT NULL default CURRENT_TIMESTAMP,
  		`create_ip` char(32) default NULL,
  		`create_user` int(11) default NULL,
  		`deleted` int(1) default '0',
  		PRIMARY KEY  (`id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";



		$queries[] =  "ALTER TABLE `#__etm_plugin_index` ADD `default_string2` TEXT NOT NULL AFTER `default_string`";


		foreach($queries as $query){
			$query = str_replace('#__', $wpdb->prefix, $query);
        	$wpdb->query($query);
		}
		update_option('etm_options_plugin_tran','true');
}

function etm_sub_page_installation($check_install){
	global $wpdb;
	
	if (version_compare($check_install, '3.0.5','<')) {
		$queries[] =  "ALTER TABLE `#__etm_plugin_index` ADD `default_string2` TEXT NOT NULL AFTER `default_string`";
		
		$queries[] =  "ALTER TABLE `#__etm_plugin_string` ADD `translatede_string2` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER `translatede_string` ";
		foreach($queries as $query){
			$query = str_replace('#__', $wpdb->prefix, $query);
	    	$wpdb->query($query);
		}
	}
	if (version_compare($check_install, '4.0.1','<')) {
		$queries[] =  "ALTER TABLE `#__etm_plugin_index` ADD `default_placeholder` TEXT NOT NULL AFTER `default_string2`";
		foreach($queries as $query){
			$query = str_replace('#__', $wpdb->prefix, $query);
	    	$wpdb->query($query);
		}
	}
	$option_value_check = $wpdb->get_var("SELECT option_value FROM ".$wpdb->prefix.'options WHERE option_name="etm_version" limit 1');

    $langs_active = etm_tools_retrive_aktiv_languages(); 
    foreach($langs_active as $key_lang => $data_lang){
   	    $getval_menus = '';
        $getval_menus = get_option('ect_tran_menu_'.$key_lang);
    
        if(!empty($getval_menus)){
            foreach($getval_menus as $key_menu => $getval_menu){
        	   if(!is_object($getval_menu)){
        	       $getval_menus[$key_menu] = '';
        	       $getval_menus[$key_menu]->title = $getval_menu;
        	   }
            }
    	   update_option('ect_tran_menu_'.$key_lang,$getval_menus);     
        }
        
   	    $getval_terms = '';
        $getval_terms = get_option('ect_tran_terms_'.$key_lang);
        
        if(!empty($getval_terms)){
            foreach($getval_terms as $key_term => $getval_term){
        	   if(!is_object($getval_term)){
        	       $getval_terms[$key_term] = '';
        	       $getval_terms[$key_term]->name = $getval_term;
        	   }
            }
    	   update_option('ect_tran_terms_'.$key_lang,$getval_terms);
        }
    }
    
	if(empty($option_value_check)){
        $sql_post = "SELECT post_id,lang_code,translatede_header,translatede_body FROM  {$wpdb->prefix}etm_post WHERE deleted != 1";
        $sqldata_posts = $wpdb->get_results($sql_post);
    
        foreach($sqldata_posts as $sqldata_post) {
            update_post_meta($sqldata_post->post_id, 'ect_tran_title_'.$sqldata_post->lang_code, $sqldata_post->translatede_header);
            update_post_meta($sqldata_post->post_id, 'ect_tran_content_'.$sqldata_post->lang_code, $sqldata_post->translatede_body);           
        }
    
    
        $sql_terms = "SELECT terms_id,lang_code,translatede_string,terms_type FROM  {$wpdb->prefix}etm_post_terms WHERE deleted != 1";
        $sqldata_termss = $wpdb->get_results($sql_terms);
        
        foreach($sqldata_termss as $sqldata_terms) {
           $getval = '';
	       $getval = get_option('ect_tran_terms_'.$sqldata_terms->lang_code); 
           $getval[$sqldata_terms->terms_id] = $sqldata_terms->translatede_string;
       	   update_option('ect_tran_terms_'.$sqldata_terms->lang_code,$getval);
        }
    
    
        $sql_menu = "SELECT menu_id,lang_code,translatede_string FROM  {$wpdb->prefix}etm_menu WHERE deleted != 1";
        $sqldata_menus = $wpdb->get_results($sql_menu); 

        foreach($sqldata_menus as $sqldata_menu) {
           $getval = '';
	       $getval = get_option('ect_tran_menu_'.$sqldata_menu->lang_code); 
	       $getval[$sqldata_menu->menu_id] = $sqldata_menu->translatede_string;
	       update_option('ect_tran_menu_'.$sqldata_menu->lang_code,$getval);
        }   
    }
    
	etm_install_capabilities();

	$option_value = $wpdb->get_var("SELECT option_value FROM ".$wpdb->prefix.'options WHERE option_name="etm_options" limit 1');
	
	if(empty($option_value)){
		$wpdb->insert( 
			$wpdb->prefix.'options', 
			array( 'option_name' => 'etm_options', 
				   'option_value' => 'a:265:{s:7:"GP_name";s:2:"la";s:7:"test_ip";s:0:"";s:16:"browser_languash";s:1:"0";s:16:"default_language";s:2:"en";s:11:"hide_auther";s:0:"";s:12:"hide_plugins";s:1:"0";s:16:"hide_plugins_all";s:0:"";s:20:"hide_plugins_akismet";s:0:"";s:25:"hide_plugins_allow-images";s:0:"";s:33:"hide_plugins_easy-contextual-help";s:0:"";s:28:"hide_plugins_easy-pagination";s:0:"";s:37:"hide_plugins_easy-translation-manager";s:0:"";s:22:"hide_plugins_hello.php";s:0:"";s:20:"hide_plugins_jetpack";s:0:"";s:32:"hide_plugins_jquery-website-tour";s:0:"";s:31:"hide_plugins_pages-by-user-role";s:0:"";s:23:"hide_plugins_private-wp";s:0:"";s:26:"hide_plugins_support-forum";s:0:"";s:43:"hide_plugins_timthumb-vulnerability-scanner";s:0:"";s:25:"hide_plugins_wp-dbmanager";s:0:"";s:32:"hide_plugins_wp-status-dashboard";s:0:"";s:10:"hide_theme";s:1:"0";s:15:"hide_themes_all";s:0:"";s:24:"hide_themes_PlatformBase";s:0:"";s:23:"hide_themes_PlatformPro";s:0:"";s:25:"hide_themes_Twenty+Eleven";s:0:"";s:18:"hide_pages_publish";s:1:"0";s:21:"hide_page_publish_all";s:0:"";s:19:"hide_page_publish_2";s:0:"";s:18:"hide_pages_pending";s:1:"0";s:21:"hide_page_pending_all";s:0:"";s:16:"hide_pages_draft";s:1:"0";s:19:"hide_page_draft_all";s:0:"";s:18:"hide_pages_private";s:1:"0";s:21:"hide_page_private_all";s:0:"";s:21:"hide_pages_attachment";s:1:"0";s:24:"hide_page_attachment_all";s:0:"";s:22:"hide_page_attachment_2";s:0:"";s:18:"hide_pages_inherit";s:1:"0";s:21:"hide_page_inherit_all";s:0:"";s:25:"hide_posts_status_publish";s:1:"0";s:28:"hide_post_status_publish_all";s:0:"";s:26:"hide_post_status_publish_1";s:0:"";s:25:"hide_posts_status_pending";s:1:"0";s:28:"hide_post_status_pending_all";s:0:"";s:23:"hide_posts_status_draft";s:1:"0";s:26:"hide_post_status_draft_all";s:0:"";s:25:"hide_posts_status_private";s:1:"0";s:28:"hide_post_status_private_all";s:0:"";s:28:"hide_posts_status_attachment";s:1:"0";s:31:"hide_post_status_attachment_all";s:0:"";s:29:"hide_post_status_attachment_3";s:0:"";s:29:"hide_post_status_attachment_1";s:0:"";s:25:"hide_posts_status_inherit";s:1:"0";s:28:"hide_post_status_inherit_all";s:0:"";s:21:"hide_posts_types_post";s:1:"0";s:25:"hide_posts_types_post_all";s:0:"";s:23:"hide_posts_types_post_1";s:0:"";s:27:"hide_posts_types_attachment";s:1:"0";s:31:"hide_posts_types_attachment_all";s:0:"";s:23:"hide_posts_types_echelp";s:1:"0";s:27:"hide_posts_types_echelp_all";s:0:"";s:25:"hide_posts_types_echelpms";s:1:"0";s:29:"hide_posts_types_echelpms_all";s:0:"";s:20:"hide_posts_types_jwt";s:1:"0";s:24:"hide_posts_types_jwt_all";s:0:"";s:26:"hide_posts_types_jwt-slide";s:1:"0";s:30:"hide_posts_types_jwt-slide_all";s:0:"";s:10:"hide_menus";s:1:"0";s:13:"hide_menu_all";s:0:"";s:14:"limit_interval";s:2:"50";s:22:"sort_group_list_plugin";s:5:"title";s:32:"sort_group_list_direction_plugin";s:4:"decs";s:23:"sort_single_list_plugin";s:14:"default_string";s:33:"sort_single_list_direction_plugin";s:4:"decs";s:21:"sort_group_list_theme";s:5:"title";s:31:"sort_group_list_direction_theme";s:4:"decs";s:22:"sort_single_list_theme";s:14:"default_string";s:32:"sort_single_list_direction_theme";s:4:"decs";s:20:"sort_group_list_page";s:2:"id";s:30:"sort_group_list_direction_page";s:4:"decs";s:20:"sort_group_list_post";s:5:"title";s:30:"sort_group_list_direction_post";s:4:"decs";s:21:"sort_single_list_post";s:2:"id";s:31:"sort_single_list_direction_post";s:4:"decs";s:20:"sort_group_list_menu";s:2:"id";s:30:"sort_group_list_direction_menu";s:4:"decs";s:21:"sort_single_list_menu";s:2:"id";s:31:"sort_single_list_direction_menu";s:4:"decs";s:15:"desing_menu_pos";s:1:"0";s:16:"desing_menu_info";s:1:"0";s:21:"desing_menu_flag_size";s:1:"0";s:16:"desing_menu_type";s:1:"3";s:15:"desing_menu_css";s:0:"";s:7:"lang_en";s:1:"2";s:7:"lang_us";s:1:"0";s:7:"lang_es";s:1:"0";s:7:"lang_zh";s:1:"0";s:7:"lang_fr";s:1:"0";s:7:"lang_de";s:1:"0";s:7:"lang_pt";s:1:"0";s:7:"lang_ru";s:1:"0";s:7:"lang_ar";s:1:"0";s:7:"lang_ja";s:1:"0";s:7:"lang_sq";s:1:"0";s:7:"lang_hy";s:1:"0";s:7:"lang_eu";s:1:"0";s:7:"lang_bs";s:1:"0";s:7:"lang_bg";s:1:"0";s:7:"lang_ca";s:1:"0";s:7:"lang_hr";s:1:"0";s:7:"lang_cs";s:1:"0";s:7:"lang_da";s:1:"0";s:7:"lang_nl";s:1:"0";s:7:"lang_eo";s:1:"0";s:7:"lang_et";s:1:"0";s:7:"lang_fi";s:1:"0";s:7:"lang_el";s:1:"0";s:7:"lang_he";s:1:"0";s:7:"lang_hi";s:1:"0";s:7:"lang_hu";s:1:"0";s:7:"lang_is";s:1:"0";s:7:"lang_id";s:1:"0";s:7:"lang_ga";s:1:"0";s:7:"lang_it";s:1:"0";s:7:"lang_ko";s:1:"0";s:7:"lang_ku";s:1:"0";s:7:"lang_la";s:1:"0";s:7:"lang_lv";s:1:"0";s:7:"lang_lt";s:1:"0";s:7:"lang_mk";s:1:"0";s:7:"lang_mt";s:1:"0";s:7:"lang_mo";s:1:"0";s:7:"lang_mn";s:1:"0";s:7:"lang_ne";s:1:"0";s:7:"lang_nb";s:1:"0";s:7:"lang_fa";s:1:"0";s:7:"lang_pl";s:1:"0";s:7:"lang_pa";s:1:"0";s:7:"lang_qu";s:1:"0";s:7:"lang_ro";s:1:"0";s:7:"lang_sr";s:1:"0";s:7:"lang_sl";s:1:"0";s:7:"lang_so";s:1:"0";s:7:"lang_sv";s:1:"0";s:7:"lang_ta";s:1:"0";s:7:"lang_th";s:1:"0";s:7:"lang_tr";s:1:"0";s:7:"lang_uk";s:1:"0";s:7:"lang_ur";s:1:"0";s:7:"lang_uz";s:1:"0";s:7:"lang_vi";s:1:"0";s:7:"lang_cy";s:1:"0";s:7:"lang_yi";s:1:"0";s:7:"lang_zu";s:1:"0";s:18:"rtl_front_page_css";s:7:"rtl.css";s:9:"old_flags";s:1:"0";s:16:"deactivate_seach";s:1:"0";s:26:"fade_none_translation_menu";s:1:"0";s:13:"use_permalink";s:1:"0";s:15:"change_wp_admin";s:1:"1";s:23:"default_language_wp_etm";s:2:"en";s:19:"hide_elements_pages";s:0:"";s:19:"hide_elements_posts";s:0:"";s:18:"hide_elements_tags";s:0:"";s:24:"hide_elements_categories";s:0:"";s:19:"hide_elements_menus";s:0:"";s:21:"hide_elements_default";s:0:"";s:24:"hide_plugins_modal-login";s:0:"";s:24:"hide_plugins_woocommerce";s:0:"";s:26:"hide_plugins_wordpress-seo";s:0:"";s:31:"hide_plugins_wp-multibyte-patch";s:0:"";s:19:"hide_themes_classic";s:0:"";s:19:"hide_themes_default";s:0:"";s:26:"hide_themes_twentyfourteen";s:0:"";s:26:"hide_themes_twentythirteen";s:0:"";s:24:"hide_themes_twentytwelve";s:0:"";s:24:"hide_posts_types_product";s:1:"0";s:34:"hide_posts_types_product_variation";s:1:"0";s:27:"hide_posts_types_shop_order";s:1:"0";s:34:"hide_posts_types_shop_order_refund";s:1:"0";s:28:"hide_posts_types_shop_coupon";s:1:"0";s:29:"hide_posts_types_shop_webhook";s:1:"0";s:21:"sort_group_list_addon";s:5:"title";s:31:"sort_group_list_direction_addon";s:4:"decs";s:22:"sort_single_list_addon";s:14:"default_string";s:32:"sort_single_list_direction_addon";s:4:"decs";s:23:"desing_menu_lang_string";s:13:"[ORG] ([ENG])";s:17:"desing_menu_align";s:9:"alignleft";s:17:"desing_menu_width";s:0:"";s:23:"desing_menu_pos_overlay";s:1:"0";s:24:"desing_menu_type_overlay";s:1:"3";s:29:"desing_menu_flag_size_overlay";s:1:"0";s:24:"desing_menu_info_overlay";s:1:"1";s:28:"desing_menu_lockdonw_overlay";s:5:"fixed";s:29:"desing_menu_placement_overlay";s:7:"topleft";s:28:"desing_menu_xpostion_overlay";s:1:"0";s:28:"desing_menu_ypostion_overlay";s:1:"0";s:35:"desing_menu_backgroundalpha_overlay";s:1:"0";s:35:"desing_menu_backgroundcolor_overlay";s:7:"#ffffff";s:28:"desing_menu_boxalpha_overlay";s:1:"0";s:28:"desing_menu_boxcolor_overlay";s:7:"#000000";s:16:"showonlyonpostID";s:0:"";s:25:"desing_menu_pos_dashboard";s:1:"1";s:26:"desing_menu_type_dashboard";s:1:"3";s:31:"desing_menu_flag_size_dashboard";s:1:"0";s:26:"desing_menu_info_dashboard";s:1:"1";s:27:"desing_menu_align_dashboard";s:9:"alignleft";s:27:"desing_menu_title_dashboard";s:29:"Select your wp-admin Language";s:27:"desing_menu_width_dashboard";s:0:"";s:7:"lang_az";s:1:"0";s:7:"lang_br";s:1:"0";s:7:"lang_kh";s:1:"0";s:7:"lang_fo";s:1:"0";s:7:"lang_fe";s:1:"0";s:7:"lang_gl";s:1:"0";s:7:"lang_zn";s:1:"0";s:7:"lang_ib";s:1:"0";s:7:"lang_lk";s:1:"0";s:7:"lang_sk";s:1:"0";s:7:"lang_zw";s:1:"0";s:9:"flag_sort";a:1:{i:0;s:2:"en";}s:17:"translator_yandex";s:0:"";s:19:"seo_plugin_by_yoast";s:1:"0";s:11:"domain_list";a:1:{s:2:"en";s:0:"";}s:16:"domain_list_fast";a:1:{s:0:"";s:2:"en";}s:27:"hide_plugins_righthere-menu";s:0:"";s:11:"hide_menu_8";s:0:"";s:21:"desing_menu_hidearrow";s:1:"0";s:29:"desing_menu_hidearrow_overlay";s:1:"0";s:30:"desing_menu_pixel_jump_overlay";s:1:"0";s:31:"desing_menu_hidearrow_dashboard";s:1:"0";s:18:"hide_themes_canvas";s:0:"";s:15:"hide_themes_duo";s:0:"";s:29:"hide_posts_types_wooframework";s:0:"";s:22:"hide_posts_types_slide";s:0:"";s:26:"hide_posts_types_portfolio";s:0:"";s:24:"hide_plugins_fusion-core";s:0:"";s:24:"hide_plugins_LayerSlider";s:0:"";s:22:"hide_plugins_revslider";s:0:"";s:29:"hide_themes_Avada-Child-Theme";s:0:"";s:17:"hide_themes_Avada";s:0:"";s:25:"hide_themes_Jupiter-child";s:0:"";s:29:"hide_themes_Karma-Child-Theme";s:0:"";s:17:"hide_themes_Karma";s:0:"";s:17:"hide_themes_awake";s:0:"";s:19:"hide_themes_infocus";s:0:"";s:19:"hide_themes_jupiter";s:0:"";s:19:"hide_themes_salient";s:0:"";s:25:"hide_themes_betheme-child";s:0:"";s:19:"hide_themes_betheme";s:0:"";s:24:"hide_themes_bridge-child";s:0:"";s:18:"hide_themes_bridge";s:0:"";s:26:"hide_themes_brooklyn-child";s:0:"";s:20:"hide_themes_brooklyn";s:0:"";s:19:"hide_themes_dt-the7";s:0:"";s:18:"hide_themes_enfold";s:0:"";s:26:"hide_themes_flatsome-child";s:0:"";s:20:"hide_themes_flatsome";s:0:"";s:20:"hide_themes_u-design";s:0:"";s:23:"hide_posts_types_client";s:0:"";s:22:"hide_posts_types_offer";s:0:"";s:23:"hide_posts_types_layout";s:0:"";s:28:"hide_posts_types_testimonial";s:0:"";s:31:"hide_plugins_custom-widget-area";s:0:"";}',
					'autoload' => 'yes' 
				), 
				array( 
					'%s', 
					'%s',
					'%s' 
				) 
			);
	}
	
	if (version_compare($check_install, '4.0.0','<')) {
		update_option('etm_version',ETM_VERSION);
		wp_redirect( admin_url( 'admin.php?page=etm-opt&pop_open_tabs=languages-fields'));
		die();
	}

    update_option('etm_version',ETM_VERSION);
	return true;
}


function etm_install_capabilities(){
	$caps = array();
	$caps[] = 'etm_show_menu';
	$caps[] = 'etm_translate_addon';
	$caps[] = 'etm_translate_post';
	$caps[] = 'etm_translate_category';
	$caps[] = 'etm_translate_post_tag';
	$caps[] = 'etm_translate_page';
	$caps[] = 'etm_translate_menu';
	$caps[] = 'etm_translate_seo';
	$caps[] = 'etm_translate_theme';
	$caps[] = 'etm_translate_plugin';
	$caps[] = 'etm_options';
	$caps[] = 'etm_license';
	$caps[] = 'etm_string_create';
	$caps[] = 'etm_view_addon';
	$caps[] = 'etm_view_post';
	$caps[] = 'etm_view_page';
	$caps[] = 'etm_view_menu';
	$caps[] = 'etm_view_seo';
	$caps[] = 'etm_view_theme';	  
	$caps[] = 'etm_view_plugin';
	$caps[] = 'etm_view_site_options';
	$caps[] = 'etm_translate_site_options';		 	
	//--
	$WP_Roles = new WP_Roles();	
	foreach($caps as $cap){
		$WP_Roles->add_cap( 'administrator', $cap );
	}
}

function etm_uninstall_capabilities(){
	$caps = array();
	$caps[] = 'etm_show_menu';
	$caps[] = 'etm_translate_addon';
	$caps[] = 'etm_translate_post';
	$caps[] = 'etm_translate_category';
	$caps[] = 'etm_translate_post_tag';
	$caps[] = 'etm_translate_page';
	$caps[] = 'etm_translate_menu';
	$caps[] = 'etm_translate_seo';
	$caps[] = 'etm_translate_theme';
	$caps[] = 'etm_translate_plugin';
	$caps[] = 'etm_options';
	$caps[] = 'etm_license';
	$caps[] = 'etm_string_create';
	$caps[] = 'etm_view_addon';
	$caps[] = 'etm_view_post';
	$caps[] = 'etm_view_page';
	$caps[] = 'etm_view_seo';
	$caps[] = 'etm_view_menu';	
	$caps[] = 'etm_view_theme';	  
	$caps[] = 'etm_view_plugin';
	$caps[] = 'etm_view_site_options';
	$caps[] = 'etm_translate_site_options';	
	//--
	$WP_Roles = new WP_Roles();	
	foreach($caps as $cap){
		$WP_Roles->remove_cap( 'administrator', $cap );
	}
	
	update_option('etm_options_plugin_tran','');
	update_option('etm_options_install','');
}
?>