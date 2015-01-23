<?php 
function etm_tools_get_types($specified = ''){
        $etm_types = array();
        $plugin_themes_show = get_option('etm_options_plugin_tran');
        
        
        if($plugin_themes_show == 'true'){
        
        //-------------------------------- ADD-ON Settings -------------------------
        $etm_columns_group = '';
		$etm_columns_group[] = array('backtitle'=>'title','title' => 'Title','col_width' => '25%', 'sorteble'=> true);
		$etm_columns_group[] = array('backtitle'=>'mainplugins','title' => 'Main Plugins','col_width' => '25%', 'sorteble'=> true);
		$etm_columns_group[] = array('backtitle'=>'folder','title' => 'Folder','col_width' => '25%', 'sorteble'=> true);
		$etm_columns_group[] = array('backtitle'=>'translationProcent','title' => 'Percent Translated','col_width' => '25%', 'sorteble'=> true);
        
        $etm_columns_single = '';
		$etm_columns_single[] = array('backtitle'=>'default_string','title' => 'Default Text String','col_width' => '30%', 'sorteble'=> true);
		$etm_columns_single[] = array('backtitle'=>'mo_tag','title' => 'Textdomain','col_width' => '10%', 'sorteble'=> true);
		$etm_columns_single[] = array('backtitle'=>'file','title' => 'File','col_width' => '25%', 'sorteble'=> true);
		$etm_columns_single[] = array('backtitle'=>'manual_added','title' => 'Input status','col_width' => '10%', 'sorteble'=> true);
		$etm_columns_single[] = array('backtitle'=>'languages','title' => 'Languages','col_width' => '20%', 'sorteble'=> false);
        $etm_columns_single[] = array('backtitle'=>'tools','title' => 'Tools','col_width' => '5%', 'sorteble'=> false);
        
        
        $etm_types[] = array ('name'=>'Addon',
                                               'tag' => 'addon',
                                               'id' => 'etm_addon_translations',
                                               'texticonx' => '6',
                                               'menu_name' => __('Add-on Translation','etm'),
                                               'title' => __('Add-on Translation','etm'),
                                               'descriptions_group' => __('Please choose the Add-on you want to translate.','etm'),
                                               'descriptions_single' => __('Our system has scanned your add-on folder and found the following strings ready for translation. If you see any rows marked with red this indicates that the text does not have a text domain, and will require manual action in order to be ready for translation.
If you have other strings that need translation you can add them manually.','etm'),
                                               'group_list_function' => 'etm_tools_get_addon',
                                               'single_list_function' => 'etm_tools_get_addon_folder',
                                               'etm_columns_single' => $etm_columns_single,
                                               'etm_columns_group' => $etm_columns_group,
                                               'etm_button_group_function' => "loadingContent(\'[PAGE]\',\'[FOLDER]\',\'[TITLE]\',\'false\',\'[SORTCOL]\',\'[SORTDIR]\')",
                                               'multi' => 'true');  
        //-------------------------------- Plugins Settings -------------------------
        
        
        $etm_columns_group = '';
		$etm_columns_group[] = array('backtitle'=>'title','title' => 'Title','col_width' => '25%', 'sorteble'=> true);
		$etm_columns_group[] = array('backtitle'=>'folder','title' => 'Folder','col_width' => '25%', 'sorteble'=> true);
		$etm_columns_group[] = array('backtitle'=>'auther','title' => 'Author','col_width' => '20%', 'sorteble'=> true);
		$etm_columns_group[] = array('backtitle'=>'version','title' => 'Version','col_width' => '10%', 'sorteble'=> true);
		$etm_columns_group[] = array('backtitle'=>'translationProcent','title' => 'Percent Translated','col_width' => '20%', 'sorteble'=> true);
        
        $etm_columns_single = '';
		$etm_columns_single[] = array('backtitle'=>'default_string','title' => 'Default Text String','col_width' => '30%', 'sorteble'=> true);
		$etm_columns_single[] = array('backtitle'=>'mo_tag','title' => 'Textdomain','col_width' => '10%', 'sorteble'=> true);
		$etm_columns_single[] = array('backtitle'=>'file','title' => 'File','col_width' => '25%', 'sorteble'=> true);
		$etm_columns_single[] = array('backtitle'=>'manual_added','title' => 'Input status','col_width' => '10%', 'sorteble'=> true);
		$etm_columns_single[] = array('backtitle'=>'languages','title' => 'Languages','col_width' => '20%', 'sorteble'=> false);
        $etm_columns_single[] = array('backtitle'=>'tools','title' => 'Tools','col_width' => '5%', 'sorteble'=> false);
        
        
        $etm_types[] = array ('name'=>'Plugins',
                                               'tag' => 'plugin',
                                               'id' => 'etm_plugins_translations',
                                               'texticonx' => '6',
                                               'menu_name' => __('Plugin Translation','etm'),
                                               'title' => __('Plugin Translation','etm'),
                                               'descriptions_group' => __('Please choose the plugin you want to translate.','etm'),
                                               'descriptions_single' => __('Our system has scanned your plugin folder and found the following strings ready for translation. If you see any rows marked with red this indicates that the text does not have a text domain, and will require manual action in order to be ready for translation.
If you have other strings that need translation you can add them manually.','etm'),
                                               'group_list_function' => 'etm_tools_get_plugins',
                                               'single_list_function' => 'etm_tools_get_plugins_themes_folder',
                                               'etm_columns_single' => $etm_columns_single,
                                               'etm_columns_group' => $etm_columns_group,
                                               'etm_button_group_function' => "loadingContent(\'[PAGE]\',\'[FOLDER]\',\'[TITLE]\',\'false\',\'[SORTCOL]\',\'[SORTDIR]\')",
                                               'multi' => 'true');
                    
        //-------------------------------- Themes Settings -------------------------
                    
                                  
        $etm_types[] = array ('name'=>'Themes',
                                               'tag' => 'theme',
                                               'id' => 'etm_themes_translations',
                                               'menu_name' => __('Theme Translation','etm'),
                                               'title' => __('Theme Translation','etm'),
                                               'texticonx' => '0',
                                               'descriptions_group' => __('Please choose the theme you want to translate.','etm'),
                                               'descriptions_single' => __('The system has auto scaned your theme folder and found the translation strings. Choose a language string to translate. If you have other strings that need translation you can add them manually.','etm'),
                                               'group_list_function' => 'etm_tools_get_themes',
                                               'single_list_function' => 'etm_tools_get_themes_folder',
                                               'etm_columns_single' => $etm_columns_single,
                                               'etm_columns_group' => $etm_columns_group,
                                               'etm_button_group_function' => "loadingContent(\'[PAGE]\',\'[FOLDER]\',\'[TITLE]\',\'false\',\'[SORTCOL]\',\'[SORTDIR]\')", 
                                               'multi' => 'false');  
                        
        }              
        //-------------------------------- Pages Settings -------------------------   
                        
        $etm_columns_group = '';
		$etm_columns_group[] = array('backtitle'=>'id','title' => 'Page id','col_width' => '10%', 'sorteble'=> true);
		$etm_columns_group[] = array('backtitle'=>'title','title' => 'Title','col_width' => '20%', 'sorteble'=> true);
		$etm_columns_group[] = array('backtitle'=>'auther','title' => 'Author','col_width' => '15%', 'sorteble'=> true);
		$etm_columns_group[] = array('backtitle'=>'date','title' => 'Date Created','col_width' => '15%', 'sorteble'=> true);
		$etm_columns_group[] = array('backtitle'=>'status','title' => 'Status','col_width' => '10%', 'sorteble'=> true);
        $etm_columns_group[] = array('backtitle'=>'tools','title' => 'Tools','col_width' => '10%', 'sorteble'=> false);	
		$etm_columns_group[] = array('backtitle'=>'languages','title' => 'Languages','col_width' => '20%', 'sorteble'=> false); 
                          
        $etm_columns_meta = '';
		$etm_columns_meta[] = array('backtitle'=>'meta_id','title' => 'Meta id','col_width' => '10%', 'sorteble'=> true);
		$etm_columns_meta[] = array('backtitle'=>'post_id','title' => 'Post id','col_width' => '10%', 'sorteble'=> true);
		$etm_columns_meta[] = array('backtitle'=>'meta_key','title' => 'Meta key','col_width' => '30%', 'sorteble'=> true);
		$etm_columns_meta[] = array('backtitle'=>'meta_value','title' => 'Meta value','col_width' => '30%', 'sorteble'=> true);	
		$etm_columns_meta[] = array('backtitle'=>'languages','title' => 'Languages','col_width' => '20%', 'sorteble'=> false);
                                             
        $etm_types[] = array ('name'=>'Pages',
                                               'tag' => 'page',
                                               'id' => 'etm_page_translations',
                                               'menu_name' => __('Page Translation','etm'),
                                               'title' => __('Pages Translation','etm'),
                                               'texticonx' => '5',
                                               'descriptions_group' => __('Please choose the page you want to translate.','etm'),
                                               'group_list_function' => 'etm_tools_get_pages',
                                               'group_list_meta_function' => 'etm_tools_get_meta',
                                               'etm_columns_group' => $etm_columns_group,
                                               'etm_columns_meta' => $etm_columns_meta,
                                               'multi' => 'false');  
       
        //-------------------------------- Posts Settings -------------------------   
        
        $etm_columns_group = '';
		$etm_columns_group[] = array('backtitle'=>'title','title' => 'Post type','col_width' => '50%', 'sorteble'=> true);
		$etm_columns_group[] = array('backtitle'=>'countPublic','title' => 'Public','col_width' => '10%', 'sorteble'=> true);
		$etm_columns_group[] = array('backtitle'=>'countDraft','title' => 'Draft','col_width' => '10%', 'sorteble'=> true);
		$etm_columns_group[] = array('backtitle'=>'countPending','title' => 'Pending','col_width' => '10%', 'sorteble'=> true);
		$etm_columns_group[] = array('backtitle'=>'countPrivate','title' => 'Private','col_width' => '10%', 'sorteble'=> true);
		$etm_columns_group[] = array('backtitle'=>'countOther','title' => 'Other','col_width' => '10%', 'sorteble'=> true);
		//$etm_columns_group[] = array('backtitle'=>'translationProcent','title' => 'Percent Translated','col_width' => '20%', 'sorteble'=> true);
       
        $etm_columns_group_tags = '';
		$etm_columns_group_tags[] = array('backtitle'=>'id','title' => 'Terms ID','col_width' => '10%', 'sorteble'=> true);
		$etm_columns_group_tags[] = array('backtitle'=>'name','title' => 'Title','col_width' => '23%', 'sorteble'=> true);
		$etm_columns_group_tags[] = array('backtitle'=>'slug','title' => 'Slug','col_width' => '23%', 'sorteble'=> true);
		$etm_columns_group_tags[] = array('backtitle'=>'description','title' => 'Description','col_width' => '23%', 'sorteble'=> true);
		$etm_columns_group_tags[] = array('backtitle'=>'languages','title' => 'Languages','col_width' => '20%', 'sorteble'=> false);
        
        
        
        $etm_columns_single = '';
		$etm_columns_single[] = array('backtitle'=>'id','title' => 'Post id','col_width' => '10%', 'sorteble'=> true);
		$etm_columns_single[] = array('backtitle'=>'title','title' => 'Title','col_width' => '20%', 'sorteble'=> true);
		$etm_columns_single[] = array('backtitle'=>'auther','title' => 'Author','col_width' => '20%', 'sorteble'=> true);
		$etm_columns_single[] = array('backtitle'=>'date','title' => 'Date Created','col_width' => '10%', 'sorteble'=> true);
		$etm_columns_single[] = array('backtitle'=>'status','title' => 'Status','col_width' => '10%', 'sorteble'=> true);
        $etm_columns_single[] = array('backtitle'=>'tools','title' => 'Tools','col_width' => '10%', 'sorteble'=> false);
        $etm_columns_single[] = array('backtitle'=>'languages','title' => 'Languages','col_width' => '20%', 'sorteble'=> false);
               
        $etm_columns_meta = '';
		$etm_columns_meta[] = array('backtitle'=>'meta_id','title' => 'Meta id','col_width' => '10%', 'sorteble'=> true);
		$etm_columns_meta[] = array('backtitle'=>'post_id','title' => 'Post id','col_width' => '10%', 'sorteble'=> true);
		$etm_columns_meta[] = array('backtitle'=>'meta_key','title' => 'Meta key','col_width' => '30%', 'sorteble'=> true);
		$etm_columns_meta[] = array('backtitle'=>'meta_value','title' => 'Meta value','col_width' => '30%', 'sorteble'=> true);	
		$etm_columns_meta[] = array('backtitle'=>'languages','title' => 'Languages','col_width' => '20%', 'sorteble'=> false);
       
       
       
        $etm_types[] = array ('name'=>'Posts',
                                               'tag' => 'post',
                                               'id' => 'etm_post_translations',
                                               'texticonx' => '9',
                                               'menu_name' => __('Post Translation','etm'),
                                               'title' => __('Post Translation','etm'),
                                               'descriptions_group' => __('Please choose the Post you want to translate.','etm'),
                                               'group_list_function_tags' => 'etm_tools_get_post_tags',
                                               'group_list_function' => 'etm_tools_get_posts_types',
                                               'single_list_function' => 'etm_tools_get_post_single',
                                               'group_list_meta_function' => 'etm_tools_get_meta',
                                               'etm_columns_single' => $etm_columns_single,
                                               'etm_columns_group_tags' => $etm_columns_group_tags,
                                               'etm_columns_group' => $etm_columns_group,
                                               'etm_columns_meta' => $etm_columns_meta,
                                               'etm_button_group_function' => "loadingContent(\'[PAGE]\',\'[FOLDER]\',\'[TITLE]\',\'false\',\'[SORTCOL]\',\'[SORTDIR]\')",
                                               'multi' => 'true'); 
                                   
        //-------------------------------- Menu Settings ------------------------- 


        $etm_columns_group = '';
		$etm_columns_group[] = array('backtitle'=>'id','title' => 'Menu ID','col_width' => '10%', 'sorteble'=> true);
		$etm_columns_group[] = array('backtitle'=>'title','title' => 'Title','col_width' => '55%', 'sorteble'=> true);
		$etm_columns_group[] = array('backtitle'=>'count','title' => 'Menu Items','col_width' => '15%', 'sorteble'=> true);
		$etm_columns_group[] = array('backtitle'=>'translationProcent','title' => 'Percent Translated','col_width' => '20%', 'sorteble'=> false);
        
        $etm_columns_single = '';
		$etm_columns_single[] = array('backtitle'=>'id','title' => 'Menu ID','col_width' => '10%', 'sorteble'=> true);
		$etm_columns_single[] = array('backtitle'=>'title','title' => 'Title','col_width' => '25%', 'sorteble'=> true);
		$etm_columns_single[] = array('backtitle'=>'desc','title' => 'Description','col_width' => '45%', 'sorteble'=> false);
		$etm_columns_single[] = array('backtitle'=>'languages','title' => 'Languages','col_width' => '20%', 'sorteble'=> false);


        $etm_types[] = array ('name'=>'Menu',
                                               'tag' => 'menu',
                                               'id' => 'etm_menu_translations',
                                               'menu_name' => __('Menu Translation','etm'),
                                               'title' => __('Menu Translation','etm'),
                                               'texticonx' => '12',
                                               'descriptions_group' => __('Please choose the Menu you want to translate.','etm'),
                                               'group_list_function' => 'etm_tools_get_menu_group',
                                               'single_list_function' => 'etm_tools_get_menu_single',
                                               'etm_columns_single' => $etm_columns_single,
                                               'etm_columns_group' => $etm_columns_group,
                                               'etm_button_group_function' => "loadingContent(\'[PAGE]\',\'[ID]\',\'[TITLE]\',\'false\',\'[SORTCOL]\',\'[SORTDIR]\')",
                                               'multi' => 'true'); 
                                               
                                               
                                             
        //-------------------------------- Option Settings -------------------------   
                        
        $etm_columns_group = '';
		$etm_columns_group[] = array('backtitle'=>'id','title' => 'Site ID','col_width' => '10%', 'sorteble'=> true);
		$etm_columns_group[] = array('backtitle'=>'title','title' => 'Key','col_width' => '20%', 'sorteble'=> true);
		$etm_columns_group[] = array('backtitle'=>'auther','title' => 'Value','col_width' => '30%', 'sorteble'=> true);
		$etm_columns_group[] = array('backtitle'=>'languages','title' => 'Languages','col_width' => '20%', 'sorteble'=> false); 
                                                       
        $etm_types[] = array ('name'=>'Site',
                                               'tag' => 'site_options',
                                               'id' => 'etm_site_options_translations',
                                               'menu_name' => __('Site Translation','etm'),
                                               'title' => __('Site Translation','etm'),
                                               'texticonx' => '5',
                                               'descriptions_group' => __('Please choose the site options you want to translate.','etm'),
                                               'group_list_function' => 'etm_tools_get_site_options',
                                               'etm_columns_group' => $etm_columns_group,
                                               'multi' => 'false');  
                                
                                
                                
                                               

        if(is_numeric($specified)){
          return $etm_types[$specified];  
        } else {
            return $etm_types;
        }
        
}

function etm_tools_subval_sort($a,$subkey,$sort) {
	$b = array();
	$c = array();

	if(!empty($a)){
		foreach($a as $k=>$v) {
			$b[$k] = strtolower($v[$subkey]);
		}
    	if($sort == 'asc'){
    	    arsort($b);
    	} else {
     	   asort($b);
    	}
		foreach($b as $key=>$val) {
	    	$a[$key]['keybackup'] = $key;
			$c[] = $a[$key];
		}
	}
	return $c;
}

function etm_tools_retrive_options($tmp_name = '') {
    $retrivede_data = get_option('etm_options'); 
    
    if(empty($tmp_name)){
    	if(!empty($retrivede_data)){
    		return $retrivede_data;
    	} else {
    		return '';
    	}
    } else {
    	if(!empty($retrivede_data[$tmp_name])){
    		return $retrivede_data[$tmp_name];
    	} else {
    		return '';
    	}
    }
}

function etm_tools_update_options($tmp_name,$value) {

    $sending_data = get_option('etm_options'); 
    $sending_data[$tmp_name] = $value; 

    update_option('etm_options',$sending_data); 
}


function etm_tools_retrive_aktiv_languages($tmp_name = '',$all_obj = true){
    $retrive_data = get_option('etm_options'); 
    $test_ip_array = str_replace (" ", "", $retrive_data['test_ip']);
    $test_ip_array = explode('|',$test_ip_array);
    
    if(!empty($tmp_name)){
        return $retrive_data['lang_'.$tmp_name];     
    } else {
        $return_array = array();
        if(!empty($retrive_data)){
        	foreach($retrive_data as $key => $tmp){
            	if(strlen($key) == 7 && $tmp>0){

                	$lang_code = substr($key, -2);
                	if(substr($key, 0,4) == 'lang' and !is_numeric($lang_code)){
						if($tmp == 2 || $all_obj || ( $tmp == 1 && !empty($test_ip_array) && in_array($_SERVER["REMOTE_ADDR"],$test_ip_array, true) )){
                    	$return_array[$lang_code] = $tmp; 
                		}
                	}
           		}    
        	} 
        }
        return $return_array;
    }  
}

function etm_tools_retrive_languages_data($array_data,$convert = false,$sort_flags = true){
    global $wpdb;
    $return_array = array();
    
    if($convert){
        foreach($array_data as $key => $tmp){
            $array_data[$key] = $key;    
        }
    }

   	foreach($array_data as $tmp){
 		$return_array[] = etm_languages_flags($tmp);
    }
    
    if($sort_flags){
	  return etm_tools_subval_sort( $return_array ,'english_name','desc');  
    } else {
	  return $return_array; 
    }
    
}

function etm_tools_create_icons_url($str,$size=0){
	global $easy_translation_manager_plugin;
	$position = EASY_TRANSLATION_MANAGER_URL.'images/flags/';
	
	if(!empty($easy_translation_manager_plugin->etm_tools_retrive['old_flags'])){
	    $size_array = array(16,24,32,48);
	    
	    if(!is_numeric($size))
	        $size = 0; 
	        
	    if($size > count($size_array)-1)
	        $size = count($size_array)-1;
	        
	    if($size < 0)
	        $size = 0;   
	        
	    $str = str_replace('[size]',$size_array[$size],$str);
    } else {
	    $str =  substr($str, 0,-11);
	    $str = $str.'.svg';
    }

    if(!empty($str)){
        return $position.$str;   
    } else {
        return '';
    }
    
}

function etm_tools_version_check(){
		global $wp_version;
	    $wp_version_p = explode("-", $wp_version);
	    if(empty($wp_version_p[0])){
	    	$wp_version_p[0] = $wp_version;
	    }
	    
	    $wp_version_is_3_3 = true;
	    
	    if($wp_version_p[0] < 3.3){
	    	$wp_version_is_3_3 = false;
	    }
	    
	    return $wp_version_is_3_3;
}

function etm_send_error_die($msg){
	die(json_encode(array('R'=>'ERR','MSG'=>$msg)));
}


 //-------------------------------- Language Settings (Flags) ------------------------- 


function etm_languages_translation($specified = ''){
$translation_array = array('ar','az','be','bg','bs','ca','cs','da','de','el','en','es','et','fi','fr','he','hr','hu','hy','id','is','it','ka','lt','lv','mk','ms','mt','nl','no','pl','pt','ro','ru','sk','sl','sq','sr','sv','th','tr','uk','us','vi','zh');
	if(empty($specified)){
		return false;
	}

	if(in_array($specified, $translation_array)){
		return true;
	}
	
	return false;	
}



function etm_languages_flags($specified = ''){

	$flag_array = array();

	// primære
	$flag_array['en'] = array('code' => 'en', 'org_name'=>'English', 'english_name'=>'English', 'icon'=>'England-[size].png', 'default_locale'=>'en_GB', 'default_pos'=> 0, 'primary_order' =>1);
	$flag_array['us'] = array('code' => 'us', 'org_name'=>'English', 'english_name'=>'American English', 'icon'=>'America-[size].png', 'default_locale'=>'en_US', 'default_pos'=> 1, 'primary_order' =>1);
	$flag_array['es'] = array('code' => 'es', 'org_name'=>'Español','english_name'=>'Spanish', 'icon'=>'Spanish-[size].png', 'default_locale'=>'es_ES','default_pos'=> 2, 'primary_order' =>1);
	$flag_array['zh'] = array('code' => 'zh', 'org_name'=>'中文', 'english_name'=>'Chinese', 'icon'=>'Chinese-[size].png', 'default_locale'=>'zh_CN', 'default_pos'=> 3, 'primary_order' =>1);
	$flag_array['fr'] = array('code' => 'fr', 'org_name'=>'Français', 'english_name'=>'French', 'icon'=>'France-[size].png', 'default_locale'=>'fr_FR', 'default_pos'=> 4,'primary_order' =>1);					  
	$flag_array['de'] = array('code' => 'de', 'org_name'=>'Deutsch','english_name'=>'German', 'icon'=>'Germany-[size].png', 'default_locale'=>'de_DE', 'default_pos'=> 5, 'primary_order' =>1);			  					  
	$flag_array['pt'] = array('code' => 'pt', 'org_name'=>'Português','english_name'=>'Portuguese','icon'=>'Portugal-[size].png','default_locale'=>'pt_PT','default_pos'=> 6,'primary_order' =>1);						  
	$flag_array['ru'] = array('code' => 'ru', 'org_name'=>'Pyccĸий','english_name'=>'Russian','icon'=>'Russian-[size].png','default_locale'=>'ru_RU','default_pos'=> 7,'primary_order' =>1);	  		
	$flag_array['ar'] = array('code' => 'ar', 'org_name'=>'العربية','english_name'=>'Arabic', 'icon'=>'Arabic-[size].png', 'default_locale'=>'ar', 'default_pos'=> 8,'primary_order' =>1 , 'rtl'=>true);	  	
	$flag_array['ja'] = array('code' => 'ja', 'org_name'=>'日本語','english_name'=>'Japanese', 'icon'=>'Japanese-[size].png','default_locale'=>'ja','default_pos'=> 9,'primary_order' =>1);					
						
	// secondær		
	$flag_array['lk'] = array('code' => 'lk', 'org_name'=>'සිංහල', 'english_name'=>'Sinhalese','icon'=>'Sri-Lanka-[size].png', 'default_locale'=>'si_LK', 'default_pos'=> 0,'primary_order' =>0);	
	$flag_array['fe'] = array('code' => 'fe', 'org_name'=>'French Canadian', 'english_name'=>'French Canadian','icon'=>'Canada-French-[size].png', 'default_locale'=>'fr_CA', 'default_pos'=> 0,'primary_order' =>0);								
	$flag_array['hy'] = array('code' => 'hy', 'org_name'=>'Հայերեն', 'english_name'=>'Armenian','icon'=>'Armenia-[size].png', 'default_locale'=>'hy', 'default_pos'=> 0,'primary_order' =>0);				
	$flag_array['bs'] = array('code' => 'bs', 'org_name'=>'Bosnian','english_name'=>'Bosnian','icon'=>'Bosnian-[size].png','default_locale'=>'bs_BA', 'default_pos'=> 0, 'primary_order' =>0);	
	$flag_array['bg'] = array('code' => 'bg', 'org_name'=>'Български','english_name'=>'Bulgarian', 'icon'=>'Bulgaria-[size].png','default_locale'=>'bg_BG', 'default_pos'=> 0,'primary_order' =>0);						   
	$flag_array['ca'] = array('code' => 'ca', 'org_name'=>'Català','english_name'=>'Catalan', 'icon'=>'Catalan-[size].png','default_locale'=>'ca', 'default_pos'=> 0,'primary_order' =>0);							   					   
	$flag_array['cs'] = array('code' => 'cs', 'org_name'=>'čeština','english_name'=>'Czech', 'icon'=>'Czech-[size].png','default_locale'=>'cs_CZ', 'default_pos'=> 0,'primary_order' =>0);
	$flag_array['cy'] = array('code' => 'cy', 'org_name'=>'Cymraeg','english_name'=>'Welsh', 'icon'=>'Welsh-[size].png','default_locale'=>'cy', 'default_pos'=> 0,'primary_order' =>0);						   
	$flag_array['da'] = array('code' => 'da', 'org_name'=>'Dansk','english_name'=>'Danish', 'icon'=>'Dansk-[size].png','default_locale'=>'da_DK', 'default_pos'=> 0,'primary_order' =>0);	
	$flag_array['el'] = array('code' => 'el', 'org_name'=>'Ελληνικά','english_name'=>'Greek', 'icon'=>'Greek-[size].png','default_locale'=>'el', 'default_pos'=> 0,'primary_order' =>0);		
	$flag_array['eo'] = array('code' => 'eo', 'org_name'=>'Esperanto','english_name'=>'Esperanto', 'icon'=>'Esperanto-[size].png','default_locale'=>'eo', 'default_pos'=> 0,'primary_order' =>0);								   
	$flag_array['et'] = array('code' => 'et', 'org_name'=>'Eesti','english_name'=>'Estonian', 'icon'=>'Estonia-[size].png','default_locale'=>'et', 'default_pos'=> 0,'primary_order' =>0);								   
	$flag_array['eu'] = array('code' => 'eu', 'org_name'=>'Euskera','english_name'=>'Basque', 'icon'=>'Euskal-[size].png','default_locale'=>'eu', 'default_pos'=> 0,'primary_order' =>0);
	$flag_array['fa'] = array('code' => 'fa', 'org_name'=>'فارسی','english_name'=>'Persian', 'icon'=>'Persian-[size].png','default_locale'=>'fa_IR', 'default_pos'=> 0,'primary_order' =>0);	
	$flag_array['fi'] = array('code' => 'fi', 'org_name'=>'suomi','english_name'=>'Finnish', 'icon'=>'Finnish-[size].png','default_locale'=>'fi', 'default_pos'=> 0,'primary_order' =>0);	
	$flag_array['ga'] = array('code' => 'ga', 'org_name'=>'Gaeilge','english_name'=>'Irish', 'icon'=>'Irish-[size].png','default_locale'=>'ga-IE', 'default_pos'=> 0,'primary_order' =>0);	
	$flag_array['he'] = array('code' => 'he', 'org_name'=>'עברית','english_name'=>'Hebrew', 'icon'=>'Hebrew-[size].png','default_locale'=>'he_IL', 'default_pos'=> 0,'primary_order' =>0, 'rtl'=>true);
	$flag_array['hi'] = array('code' => 'hi', 'org_name'=>'हिंदी','english_name'=>'Hindi', 'icon'=>'Hindi-[size].png','default_locale'=>'hi_IN', 'default_pos'=> 0,'primary_order' =>0);
	$flag_array['hr'] = array('code' => 'hr', 'org_name'=>'Hrvatski','english_name'=>'Croatian', 'icon'=>'Croatia-[size].png','default_locale'=>'hr', 'default_pos'=> 0,'primary_order' =>0);
	$flag_array['hu'] = array('code' => 'hu', 'org_name'=>'Magyar','english_name'=>'Hungarian', 'icon'=>'Hungary-[size].png','default_locale'=>'hu_HU', 'default_pos'=> 0,'primary_order' =>0);
	$flag_array['ib'] = array('code' => 'ib', 'org_name'=>'Bahasa indonesia','english_name'=>'Indonesian', 'icon'=>'Indonesia-[size].png','default_locale'=>'id_ID', 'default_pos'=> 0,'primary_order' =>0);
	$flag_array['is'] = array('code' => 'is', 'org_name'=>'Íslenska','english_name'=>'Icelandic', 'icon'=>'Icelandic-[size].png','default_locale'=>'is_IS', 'default_pos'=> 0,'primary_order' =>0);
	$flag_array['it'] = array('code' => 'it', 'org_name'=>'Italiano','english_name'=>'Italian', 'icon'=>'Italian-[size].png','default_locale'=>'it_IT', 'default_pos'=> 0,'primary_order' =>0);
	$flag_array['ko'] = array('code' => 'ko', 'org_name'=>'한국어','english_name'=>'Korean', 'icon'=>'Korean-[size].png','default_locale'=>'ko_KR', 'default_pos'=> 0,'primary_order' =>0);
	$flag_array['ku'] = array('code' => 'ku', 'org_name'=>'Kurdish','english_name'=>'Kurdish', 'icon'=>'Kurdish-[size].png','default_locale'=>'ku', 'default_pos'=> 0,'primary_order' =>0);
	$flag_array['la'] = array('code' => 'la', 'org_name'=>'Latine','english_name'=>'Latin', 'icon'=>'Latin-[size].png','default_locale'=>'la', 'default_pos'=> 0,'primary_order' =>0);
	$flag_array['lv'] = array('code' => 'lv', 'org_name'=>'Latviešu','english_name'=>'Latvian', 'icon'=>'Latvia-[size].png','default_locale'=>'lv', 'default_pos'=> 0,'primary_order' =>0);
	$flag_array['lt'] = array('code' => 'lt', 'org_name'=>'Lietuviškai','english_name'=>'Lithuanian', 'icon'=>'Lithuania-[size].png','default_locale'=>'lt_LT', 'default_pos'=> 0,'primary_order' =>0);
	$flag_array['mk'] = array('code' => 'mk', 'org_name'=>'Македонски','english_name'=>'Macedonian', 'icon'=>'Macedonia-[size].png','default_locale'=>'mk_MK', 'default_pos'=> 0,'primary_order' =>0);
	$flag_array['mt'] = array('code' => 'mt', 'org_name'=>'Malti','english_name'=>'Maltese', 'icon'=>'Maltese-[size].png','default_locale'=>'mt', 'default_pos'=> 0,'primary_order' =>0);	
	$flag_array['mo'] = array('code' => 'mo', 'org_name'=>'Moldova','english_name'=>'Moldavian', 'icon'=>'Moldova-[size].png','default_locale'=>'mo', 'default_pos'=> 0,'primary_order' =>0);
	$flag_array['mn'] = array('code' => 'mn', 'org_name'=>'Mongoljan','english_name'=>'Mongolian', 'icon'=>'Mongolia-[size].png','default_locale'=>'mn', 'default_pos'=> 0,'primary_order' =>0);
	$flag_array['ne'] = array('code' => 'ne', 'org_name'=>'Nepali','english_name'=>'Nepali', 'icon'=>'Nepal-[size].png','default_locale'=>'ne', 'default_pos'=> 0,'primary_order' =>0);
	$flag_array['nl'] = array('code' => 'nl', 'org_name'=>'Nederlands','english_name'=>'Dutch', 'icon'=>'Dutch-[size].png','default_locale'=>'nl_NL', 'default_pos'=> 0,'primary_order' =>0);
	$flag_array['nb'] = array('code' => 'nb', 'org_name'=>'Norsk','english_name'=>'Norwegian', 'icon'=>'Norwegian-[size].png','default_locale'=>'nb_NO', 'default_pos'=> 0,'primary_order' =>0);
	$flag_array['pa'] = array('code' => 'pa', 'org_name'=>'Punjabi','english_name'=>'Punjabi', 'icon'=>'Punjabi-[size].png','default_locale'=>'', 'default_pos'=> 0,'primary_order' =>0);
	$flag_array['pl'] = array('code' => 'pl', 'org_name'=>'Polski','english_name'=>'Polish', 'icon'=>'Polish-[size].png','default_locale'=>'pl_PL', 'default_pos'=> 0,'primary_order' =>0);
	$flag_array['qu'] = array('code' => 'qu', 'org_name'=>'Quechua','english_name'=>'Quechua', 'icon'=>'Quechua-[size].png','default_locale'=>'qu', 'default_pos'=> 0,'primary_order' =>0);
	$flag_array['ro'] = array('code' => 'ro', 'org_name'=>'Română','english_name'=>'Romanian', 'icon'=>'Romania-[size].png','default_locale'=>'ro_RO', 'default_pos'=> 0,'primary_order' =>0);	
	$flag_array['sl'] = array('code' => 'sl', 'org_name'=>'Slovenščina','english_name'=>'Slovenian', 'icon'=>'Slovenia-[size].png','default_locale'=>'sl_SI', 'default_pos'=> 0,'primary_order' =>0);	
	$flag_array['so'] = array('code' => 'so', 'org_name'=>'Somali','english_name'=>'Somali', 'icon'=>'Somalia-[size].png','default_locale'=>'so-SO', 'default_pos'=> 0,'primary_order' =>0);	
	$flag_array['sq'] = array('code' => 'sq', 'org_name'=>'ship','english_name'=>'Albanian', 'icon'=>'Albania-[size].png','default_locale'=>'sq_AL', 'default_pos'=> 0,'primary_order' =>0);	
	$flag_array['sr'] = array('code' => 'sr', 'org_name'=>'Srpski','english_name'=>'Serbian', 'icon'=>'Serbia-[size].png','default_locale'=>'sr_RS', 'default_pos'=> 0,'primary_order' =>0);		 
	$flag_array['sv'] = array('code' => 'sv', 'org_name'=>'Svenska','english_name'=>'Swedish', 'icon'=>'Sweden-[size].png','default_locale'=>'sv_SE', 'default_pos'=> 0,'primary_order' =>0);	
	$flag_array['ta'] = array('code' => 'ta', 'org_name'=>'Tamil','english_name'=>'Tamil', 'icon'=>'Tamil-[size].png','default_locale'=>'ta_LK', 'default_pos'=> 0,'primary_order' =>0);	
	$flag_array['th'] = array('code' => 'th', 'org_name'=>'ภาษาไทย','english_name'=>'Thai', 'icon'=>'Thai-[size].png','default_locale'=>'th', 'default_pos'=> 0,'primary_order' =>0);	
	$flag_array['tr'] = array('code' => 'tr', 'org_name'=>'Tϋrkçe','english_name'=>'Turkish', 'icon'=>'Turkey-[size].png','default_locale'=>'tr', 'default_pos'=> 0,'primary_order' =>0);	
	$flag_array['uk'] = array('code' => 'uk', 'org_name'=>'Українська','english_name'=>'Ukrainian', 'icon'=>'Ukrainian-[size].png','default_locale'=>'uk', 'default_pos'=> 0,'primary_order' =>0);	
	$flag_array['ur'] = array('code' => 'ur', 'org_name'=>'اردو','english_name'=>'Urdu', 'icon'=>'Urdu-[size].png','default_locale'=>'ur-IN', 'default_pos'=> 0,'primary_order' =>0);	
	$flag_array['uz'] = array('code' => 'uz', 'org_name'=>'ozbek','english_name'=>'Uzbek', 'icon'=>'Uzbek-[size].png','default_locale'=>'uz_UZ', 'default_pos'=> 0,'primary_order' =>0);
	$flag_array['vi'] = array('code' => 'vi', 'org_name'=>'Tiếng Việt','english_name'=>'Vietnamese', 'icon'=>'Vietnamese-[size].png','default_locale'=>'vi', 'default_pos'=> 0,'primary_order' =>0);
	$flag_array['yi'] = array('code' => 'yi', 'org_name'=>'Yiddish','english_name'=>'Yiddish', 'icon'=>'Yiddish-[size].png','default_locale'=>'yi', 'default_pos'=> 0,'primary_order' =>0);
	$flag_array['zu'] = array('code' => 'zu', 'org_name'=>'isiZulu','english_name'=>'Zulu', 'icon'=>'Zulu-[size].png','default_locale'=>'zu', 'default_pos'=> 0,'primary_order' =>0);
	$flag_array['kh'] = array('code' => 'kh', 'org_name'=>'Khmer','english_name'=>'Cambodian', 'icon'=>'Cambodja-[size].png','default_locale'=>'km', 'default_pos'=> 0,'primary_order' =>0);
	$flag_array['gl'] = array('code' => 'gl', 'org_name'=>'Kalaallisut','english_name'=>'Greenlandic', 'icon'=>'Greenland-[size].png','default_locale'=>'kl', 'default_pos'=> 0,'primary_order' =>0);
	$flag_array['fo'] = array('code' => 'fo', 'org_name'=>'Føroyskt','english_name'=>'Faroese', 'icon'=>'Faroese-[size].png','default_locale'=>'fo', 'default_pos'=> 0,'primary_order' =>0);
	$flag_array['az'] = array('code' => 'az', 'org_name'=>'Azeri','english_name'=>'Azerbaijani', 'icon'=>'Azerbaijan-[size].png','default_locale'=>'az', 'default_pos'=> 0,'primary_order' =>0);
	$flag_array['br'] = array('code' => 'br', 'org_name'=>'Português do Brasil','english_name'=>'Brazilian Portuguese', 'icon'=>'Brazil-[size].png','default_locale'=>'pt_BR', 'default_pos'=> 0,'primary_order' =>0);
	$flag_array['sk'] = array('code' => 'sk', 'org_name'=>'Slovenský jazyk','english_name'=>'Slovak', 'icon'=>'Slovak-[size].png','default_locale'=>'sk_SK', 'default_pos'=> 0,'primary_order' =>0);
	$flag_array['zw'] = array('code' => 'zw', 'org_name'=>'正體中文','english_name'=>'Traditional Chinese', 'icon'=>'Chinese-[size].png','default_locale'=>'zh_TW', 'default_pos'=> 0,'primary_order' =>0);
	$flag_array['zn'] = array('code' => 'zn', 'org_name'=>'香港','english_name'=>'Hong Kong', 'icon'=>'Hong_Kong-[size].png','default_locale'=>'zh_HK', 'default_pos'=> 0,'primary_order' =>0);

	$flag_array['mm'] = array('code' => 'mm', 'org_name'=>'Bahasa Melayu','english_name'=>'Malay', 'icon'=>'Malaysia-[size].png', 'default_locale'=>'ms_MY','default_pos'=> 0, 'primary_order' =>0);

	
	if(!empty($specified)){
		return $flag_array[$specified];
	} else {
		return $flag_array;
	}
}

?>