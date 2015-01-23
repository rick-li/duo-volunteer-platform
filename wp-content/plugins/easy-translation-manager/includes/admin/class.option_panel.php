<?php

class etm_options {
	var $screen_title = 'Options';
	var $screen_menu = 'Options';    
	var $plugin_id;
	var $tdom = 'etm';
	
	function etm_options($parent_id){
		$this->plugin_id = $parent_id.'-opt';
		
		//add_submenu_page($parent_id,$this->screen_menu,$this->screen_menu,0,$this->plugin_id,array(&$this,'test'));
		add_filter("pop_admin_head_{$this->plugin_id}",array(&$this,'create_header'),10,1);
		add_filter("pop-options_{$this->plugin_id}",array(&$this,'options'),10,1);
		add_action('pop_handle_save',array(&$this,'pop_handle_save11'));
		
        remove_all_actions('wp_ajax_pop_uploader-'.$this->plugin_id);
        add_action('wp_ajax_pop_uploader-'.$this->plugin_id, array(&$this,'etm_pop_uploader'));					
	} 
	
	function pop_handle_save11($temp){
		$values = array();
		$get_option_data = etm_tools_retrive_options();	

		if(!empty($_POST) && (empty($_POST['default_language']) || empty($_POST['lang_'.$_POST['default_language']]))){
			foreach($_POST as $tmp_key => $tmp_data){
				if ( isset($_POST[$tmp_key]) && !empty($_POST[$tmp_key]) && !empty($tmp_data) && substr($tmp_key,0,5) == 'lang_' && strlen($tmp_key) == 7){
					if(!in_array(substr($tmp_key,-2), $values)){
						$get_option_data['default_language'] = substr($tmp_key,-2);
						break;
					}
				}
			}	
		}
		if(empty($_POST['default_language_wp_etm']) || empty($_POST['lang_'.$_POST['default_language_wp_etm']])){
			$get_option_data['default_language_wp_etm'] = $get_option_data['default_language'];
		}

		if ( isset($_POST['flag_sort']) && !empty($_POST['flag_sort']) ){
			$values = $_POST['flag_sort'];
			
			foreach($values as $tmp_key => $tmp_data){
				if ( isset($_POST['lang_'.$tmp_data]) && empty($_POST['lang_'.$tmp_data]) ){
					unset($values[$tmp_key]);
				}
			}
			
			$values = array_values($values);
			
			foreach($_POST as $tmp_key => $tmp_data){
				if ( isset($_POST[$tmp_key]) && !empty($_POST[$tmp_key]) && substr($tmp_key,0,5) == 'lang_'){
					if(!in_array(substr($tmp_key,-2), $values)){
						$values[] = substr($tmp_key,-2);
					}
				}
			}
			$values = array_values($values);
			
			$get_option_data['flag_sort'] = $values;
		}	
		
		if ( isset($_POST['domain_list']) && !empty($_POST['domain_list']) ){
			$get_domain_list = $_POST['domain_list'];
			$return_array = array();
			
			
			foreach($get_domain_list as $tmp_key => $tmp_data){
				if ( isset($_POST['lang_'.$tmp_key]) && !empty($_POST['lang_'.$tmp_key]) ){
					$return_array[$tmp_data] = $tmp_key;
				}
			}

			$get_option_data['domain_list_fast'] = $return_array;
		}
		
		
		
		
		update_option('etm_options',$get_option_data); 
	}
	
	function flag_layout_sort($flag_sorts = array()){
		global $easy_translation_manager_plugin;
    	if(!empty($easy_translation_manager_plugin->etm_tools_retrive['flag_sort'])){
    		$flag_sorts= $easy_translation_manager_plugin->etm_tools_retrive['flag_sort'];
    	}

	    if(!empty($flag_sorts)){
		    $flag_sorts = etm_tools_retrive_languages_data($flag_sorts,false,false);
	    } else {
	    	$active_langs = etm_tools_retrive_aktiv_languages('',true);
	    	$active_langs = array_keys($active_langs);
		    $flag_sorts = etm_tools_retrive_languages_data($active_langs,false,true);
	    }
		return $flag_sorts;
	}
	
	
	
	function flag_layout($tab,$i,$o,&$save_fields){
	
		$languasheds = $this->flag_layout_sort();
	
		$str='<div id="" class="flags_order_holder">';

		foreach($languasheds as $languashed){
		
			
		
		    $url_icon = etm_tools_create_icons_url($languashed['icon'],1); 
			$tpl = '<div class="flags_order_item sco-%s">'; 
			$tpl.= '<img style="margin-top:-3px;padding-right:5px;float:left" height="24" src="%s"><div>%s (%s)<input type="hidden" id="flags_order_%s" name="flag_sort[]" value="%s" /></div>';
			$tpl.= '</div>';
			
			$str .= sprintf($tpl,$languashed['code'],$url_icon,$languashed['org_name'],$languashed['english_name'],$languashed['code'],$languashed['code']);		
		}
		$str.='</div>';
		if(true===$o->save_option){
			$save_fields[]='flag_sort';	
		}
		return $str;		
		
	}
	
	function domain_list($tab,$i,$o,&$save_fields){
		$languasheds = $this->flag_layout_sort();
	
		$str='<div id="" class="domain_list_holder">';

		foreach($languasheds as $languashed){
		    $url_icon = etm_tools_create_icons_url($languashed['icon'],1); 
		    
		    $value_old = '';
		    
		    if(!empty($o->existing_options['domain_list'][$languashed['code']])){
		    	$value_old =$o->existing_options['domain_list'][$languashed['code']];
		    }
		    
			$tpl = '<div class="flags_order_item sco-%s" style="margin-bottom: 2px; line-height: 36px; vertical-align: middle;">'; 
			$tpl.= '<img title="%s (%s)" style="padding-right: 5px; display: inline-block;" height="24" src="%s"><input style="width: 340px; display: inline-block; vertical-align: super;" type="text" id="flags_order_%s" name="domain_list[%s]" value="%s" />';
			$tpl.= '</div>';
			
			$str .= sprintf($tpl,$languashed['code'],$languashed['org_name'],$languashed['english_name'],$url_icon,$languashed['code'],$languashed['code'],$value_old);	
	
		}
			
			if(true===$o->save_option){
				$save_fields[]='domain_list[]';	
			}
		
		$str.='</div>';

		return $str;		
		
	}	

	function etm_pop_uploader(){
        require_once(EASY_TRANSLATION_MANAGER_PATH.'inc/class.fileupload.php');
		$allowedExtensions = array();
		$sizeLimit = 10 * 1024 * 1024;
		$uploader = new etm_qqFileUploader($allowedExtensions, $sizeLimit);
		$result = $uploader->handleUpload();
		echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);		
		die();
	}
        
	function options($t){
        global $wpdb,$wpseo_sitemaps,$wpseo_front;
        
        $unified_css_style = 'width:200px;float: right;margin-right: 10px;';
        
		$i = count($t);
		//-------------------------	Generelle Settings	
		$i++;
		$t[$i] = (object)array();
		$t[$i] = (object)array();
		$t[$i]->id 			= 'Generalte-settings'; 
		$t[$i]->label 		= __('General Settings','etm');//title on tab
		$t[$i]->right_label	= __('Input data','etm');//title on tab
		$t[$i]->page_title	= __('General Settings','etm');//title on content
		$t[$i]->theme_option = true;
		$t[$i]->plugin_option = true;
		
				
		$temp = array();
		
		$temp[] = (object)array(
			'type'=>'subtitle',
			'label'=>__('General Settings','etm')
		);		
		
		$temp[] = (object)array(
				'id'	=> 'GP_name',
				'type'	=> 'text',
				'label'	=> __('Get / post tags','etm'),
				'description' => __('For multible GET/POST tag then seperate by | eg. lang|la|Language <br />','etm'),
				'el_properties'=>array('style'=>$unified_css_style),
				'save_option'=>true,
				'load_option'=>true
			);
		$temp[] = (object)array(
				'id'	=> 'test_ip',
				'type'	=> 'text',
				'label'	=> __('Language test IP','etm'),
				'description' => __('For multiple IP addresses seperate by | eg. 192.168.2.1|192.168.2.2. <br />','etm'),
				'el_properties'=>array('style'=>$unified_css_style),
				'save_option'=>true,
				'load_option'=>true
			);	
			
		$temp[] = (object)array(
				'id'	=> 'rtl_front_page_css',
				'type'	=> 'text',
				'label'	=> __('RTL frontpage css','etm'),
				'default' => 'rtl.css',
				'description' => __('ONLY CHANGE THIS IF YOUR RTL.CSS IS LOCATED IN A SUB FOLDER OR HAS A DIFFERENT NAME. This allow you to change the location of the rtl.css file in your theme folder.','etm'),
				'el_properties'=>array('style'=>$unified_css_style),
				'save_option'=>true,
				'load_option'=>true
			);
			

		$temp[] = (object)array(
				'id'		=> 'use_permalink',
				'label'		=> __('Use Permalink','etm'),
				'type'		=> 'onoff',
				'default'   => false,
				'description'=>  __('Use the custom language permalink','etm'),
				'el_properties'	=> array(),
				'save_option'=>true,
				'load_option'=>true
		);
		
		$temp[] = (object)array(
				'id'		=> 'deactivate_seach',
				'label'		=> __('Deactivate Search','etm'),
				'type'		=> 'onoff',
				'default'   => false,
				'description'=>  __('Select Yes to disable the language specific search feature.','etm'),
				'el_properties'	=> array(),
				'save_option'=>true,
				'load_option'=>true
		);		
		
		$temp[] = (object)array(
			'type'	=> 'clear',
		);
		
		$temp[] = (object)array(
			'type'=>'subtitle',
			'label'=>__('Flag Settings','etm')
		);
		
		$temp[] = (object)array(
				'id'		=> 'old_flags',
				'label'		=> __('Use PNG flags','etm'),
				'type'		=> 'onoff',
				'default'	=> false,
				'description'=> __('By default we use SVG (Scalable Vector Graphics) flags for the language selector. The SVG flags can be resized to any size and looks good on all devices. Prior to version 4.0 we used PNG images for the flags.','etm'),
                'save_option'=>true,
				'load_option'=>true
		);	

		$temp[] = (object)array(
				'id'		=> 'fade_none_translation_menu',
				'label'		=> __('Active or Inactive flags','etm'),
				'type'		=> 'onoff',
				'default'   => false,
				'description'=> __('If you have content that is not translated into all activated languages, you can choose to show a "inactive" flag if a page, post is not available in a specific language.','etm'),
                'save_option'=>true,
				'load_option'=>true
		);	 
        

		$temp[] = (object)array(
			'type'	=> 'clear',
		);
		
		$temp[] = (object)array(
			'type'=>'subtitle',
			'label'=>__('Language Settings','etm')
		);
      
		$temp[] = (object)array(
				'id'		=> 'change_wp_admin',
				'label'		=> __('Change wp-admin Language','etm'),
				'type'		=> 'onoff',
				'default'   => false,
				'description'=>  sprintf(__('This feature changes the default language if the .mo file(s) are installed. At the moment the .mo file(s) has to be install manually.<br><br> 1. Download the .mo file(s)','etm') . ' <a target="_black" href="http://codex.wordpress.org/WordPress_in_Your_Language">'.__('here','etm').'</a><br>2. Upload the .mo file(s) to the /wp-content/languages/ folder (Create the folder if needed)',__('Yes','etm')),
				'el_properties'	=> array('OnClick'=>'javascript:yesno_panel(this,\'.change_wp_admin\');'),
                'hidegroup'	=> '#hide_change_wp_admin_group',
                'save_option'=>true,
				'load_option'=>true
		);	
		

		$temp[] = (object)array(
				'id'	=> 'hide_change_wp_admin_group',
				'el_properties'=>array('class'=>'hide_group'),
				'type'=>'div_start'
		);

        $temp[] = (object)array(
				'id'	=> 'upload_files',
				'type'	=>'fileuploader',
				'label'	=> __('Upload files','etm'),
				'description'=> __('Upload all your language files to the /wp-content/languages/ folder.','etm'),
				'save_option'=>false,
				'load_option'=>false
		);
        
		$temp[] = (object)array(
		  'id'=>'hide_change_wp_admin_group',
				'type'=>'div_end'
		);
		
		$temp[] = (object)array(
				'id'		=> 'browser_languash',
				'label'		=> __('Browser language','etm'),
				'type'		=> 'onoff',
				'default'   => false,
				'description'=>  sprintf(__('Choose %s to set the translation language to the browser default language','etm'),__('Yes','etm')),
				'el_properties'	=> array(),
				'save_option'=>true,
				'load_option'=>true
		);	
		

        $temp_retrive_array_sort = etm_tools_subval_sort( etm_languages_flags() ,'english_name','desc');
		$languasheds_tmp = $this->flag_layout_sort();
		$temp_array_default = array();
		$main_array_default = array();
		$secoundary_array_default = array();
			
		foreach($temp_retrive_array_sort as $tmp_flag){
			if($tmp_flag['primary_order'] == 1){
				$main_array_default[] = $tmp_flag;
			} else {
				$secoundary_array_default[] = $tmp_flag;
			}
		}	
		
		foreach($languasheds_tmp as $tmp_flag){
			$temp_array_default[$tmp_flag['code']] = $tmp_flag['org_name'].' ('.$tmp_flag['english_name'].')';
		}	
		
		if(empty($temp_array_default)){
			$temp_array_default = array('' => '-- No Languages selected --');
		}
			

		$temp[] = (object)array(
				'id'	=> 'default_language',
				'type'	=> 'select',
				'label'	=> __('Default Language','etm'),
				'options'=> $temp_array_default,
				'description'=>__('Select default Language frontend <br>Only languages enabled in the Language tab will be visible in the drop down.','etm'),
				'el_properties'=>array('class'=>'dropdown2','style'=>$unified_css_style),
				'default'=>'0',
				'save_option'=>true,
				'load_option'=>true
			);		
            
		$temp[] = (object)array(
				'id'	=> 'default_language_wp_etm',
				'type'	=> 'select',
				'label'	=> __('Default Language wp-admin','etm'),
				'options'=> $temp_array_default,
				'description'=>__('Select default Language backend <br>Only languages enabled in the Language tab will be visible in the drop down.','etm'),
				'el_properties'=>array('class'=>'dropdown2','style'=>$unified_css_style),
				'default'=>'0',
				'save_option'=>true,
				'load_option'=>true
			);	
            
            
		$temp[] = (object)array(
			'type'=>'subtitle',
			'label'=>__('Hide Elements when not translated','etm'),
			'description'=>__('The plugin will display the default language if a element is not translated, but you can choose to hide elements not translated by checking one or multiple.','etm')	
		);
        
		$temp[] =(object)array(
				'id'		=> 'hide_elements_pages',
				'label'		=> __('Pages','etm'),
				'type'		=> 'checkbox',
				'save_option'=>true,
				'load_option'=>true
				);
            
		$temp[] =(object)array(
				'id'		=> 'hide_elements_posts',
				'label'		=> __('Posts','etm'),
				'type'		=> 'checkbox',
				'save_option'=>true,
				'load_option'=>true
				);
                
		$temp[] =(object)array(
				'id'		=> 'hide_elements_tags',
				'label'		=> __('Tags','etm'),
				'type'		=> 'checkbox',
				'save_option'=>true,
				'load_option'=>true
				);

		$temp[] =(object)array(
				'id'		=> 'hide_elements_categories',
				'label'		=> __('Categories','etm'),
				'type'		=> 'checkbox',
				'save_option'=>true,
				'load_option'=>true
				);          
            
		$temp[] =(object)array(
				'id'		=> 'hide_elements_menus',
				'label'		=> __('Menus','etm'),
				'type'		=> 'checkbox',
				'save_option'=>true,
				'load_option'=>true
				);      
                       
		$temp[] =(object)array(
				'id'		=> 'hide_elements_default',
				'label'		=> __('Default language','etm'),
				'type'		=> 'checkbox',
				'save_option'=>true,
				'load_option'=>true
				);
            
		$t[$i]->options = $temp;
		
		
	
		//------------------------- Manage view -------------------
		
		$i++;
		$t[$i] = (object)array();
		$t[$i]->id 			= 'manage-view-listing'; 
		$t[$i]->label 		= __('Item Availability','etm');//title on tab
		$t[$i]->right_label	= __('Select available items for translation','etm');//title on tab
		$t[$i]->page_title	= __('Select available items for translation','etm');//title on content
		$t[$i]->theme_option = true;
		$t[$i]->plugin_option = true;
		$temp = '';
		
		$temp[] = (object)array(
			'type'	=> 'subtitle',
			'label'	=> __('Item Availability','etm')
		);
		
		$temp[] = (object)array(
			'id'=>'hide_auther',
			'label'=> __('Hide author','etm'),
			'type'=>'checkbox',
			'description'=>__('Removes all author names in the tables','etm'),
			'save_option'=>true,
			'load_option'=>true
		);
		$temp[] = (object)array(
			'type'	=> 'clear',
		);
		
		//------- Plugins -----		
		
		$plugins = get_plugins();
		$temp[] = (object)array(
			'type'=>'subtitle',
			'label'=>__('Hide Plugin','etm'),
			'description'=>__('As default all items will be available for translation. If you switch the status to "Yes" and tick off a check box the item will be unavailable for translation.','etm')	
		);
			
		$temp[] =(object)array(
				'id'		=> 'hide_plugins',
				'label'		=> __('Plugin','etm'),
				'type'		=> 'onoff',
				'default'   => false,
				'el_properties'	=> array('OnClick'=>'javascript:yesno_panel(this,\'.hide_plugins\');'),
				'hidegroup'	=> '#hide_plugins_group',
				'save_option'=>true,
				'load_option'=>true
				);
		
		$temp[] = (object)array(
				'id'	=> 'hide_plugins_group',
				'el_properties'=>array('class'=>'hide_group'),
				'type'=>'div_start'
		);
		
		$temp[] = (object)array(
				'id'=>'hide_plugins_all',
				'label'=> '<b>Hide all plugins</b>',
				'type'=>'checkbox',
				'el_properties'=>array('class'=>'hide_all'),
				'save_option'=>true,
				'load_option'=>true
		);		
		
		foreach($plugins as $key => $plugin){
			$key_array = explode('/',$key);
			
			$temp[] = (object)array(
					'id'=>'hide_plugins_'.urlencode($key_array[0]),
					'label'=> $plugin['Name'],
					'type'=>'checkbox',
					'save_option'=>true,
					'load_option'=>true
			);
		}
		
		$temp[] = (object)array(
			'id'=>'hide_plugins_group',
			'type'=>'div_end'
		);	
		//------- themes -----

		$wp_version_is_3_3 = etm_tools_version_check();

		if($wp_version_is_3_3){
			$themes = wp_get_themes();	
		} else {
			$themes = get_themes();	
		}

		
		
		$temp[] = (object)array(
			'type'=>'subtitle',
			'label'=>__('Hide Theme','etm')	
		);		
		
		$temp[] =(object)array(
				'id'		=> 'hide_theme',
				'label'		=> __('Theme','etm'),
				'type'		=> 'onoff',
				'default'   => false,
				'el_properties'	=> array('OnClick'=>'javascript:yesno_panel(this,\'.hide_theme\');'),
				'hidegroup'	=> '#hide_themes_group',
				'save_option'=>true,
				'load_option'=>true
		);
		
		$temp[] = (object)array(
				'id'	=> 'hide_themes_group',
				'el_properties'=>array('class'=>'hide_group'),
				'type'=>'div_start'
		);
		
		$temp[] = (object)array(
				'id'=>'hide_themes_all',
				'label'=> '<b>Hide all themes</b>',
				'type'=>'checkbox',
				'el_properties'=>array('class'=>'hide_all'),
				'save_option'=>true,
				'load_option'=>true
		);
		
		foreach($themes as $key => $theme){
			$key_array = explode('/',$key);
			
			$temp[] = (object)array(
				'id'		=> 'hide_themes_'.urlencode($key_array[0]),
				'label'		=> $theme['Name'],
				'type'		=> 'checkbox',
				'save_option'=>true,
				'load_option'=>true
			);	
		}

		$temp[] = (object)array(
			'id'=>'hide_themes_group',
			'type'=>'div_end'
		);
		
		// ----- pages ----
	
		$temp[] = (object)array(
			'type'=>'subtitle',
			'label'=>__('Hide Page','etm')	
		);		
		
		$array_pages = array('publish','pending','draft','private','attachment','inherit');
		foreach($array_pages as $array_page){
		
			$temp[] =(object)array(
				'id'		=> 'hide_pages_'.urlencode($array_page),
				'label'		=> 'Status '.$array_page,
				'type'		=> 'onoff',
				'default'   => false,
				'save_option'=>true,
				'load_option'=>true
			);
		}

		// ------- post status -------
		
		

		$temp[] = (object)array(
			'type'=>'subtitle',
			'label'=>__('Hide Post Status','etm')	
		);	
			
		$array_pages = array('publish','pending','draft','private','attachment','inherit');	
		foreach($array_pages as $array_page){
			$temp[] =(object)array(
				'id'		=> 'hide_posts_status_'.$array_page,
				'label'		=> __('Status','etm').' '.$array_page,
				'type'		=> 'onoff',
				'default'   => false,
				'save_option'=>true,
				'load_option'=>true
			);

			
		}
			
			
		//----- hide post types
		
				$post_types = get_post_types();
		$temp[] = (object)array(
			'type'=>'subtitle',
			'label'=>__('Hide Post Types','etm')	
		);	
			
		foreach($post_types as $post_type){
			if($post_type != 'page' && $post_type != 'nav_menu_item' && $post_type != 'revision'){
				$temp[] =(object)array(
					'id'		=> 'hide_posts_types_'.$post_type,
					'label'		=> __('Type','etm').' '.$post_type,
					'type'		=> 'onoff',
					'default'   => false,
					'save_option'=>true,
					'load_option'=>true
				);
			
			}
		}	
			
		//---- Menu	
		
		$menu_names = wp_get_nav_menus();
		$temp[] = (object)array(
			'type'=>'subtitle',
			'label'=>__('Hide Menu','etm')	
		);	
			
		$temp[] =(object)array(
				'id'		=> 'hide_menus',
				'label'		=> __('Menu','etm'),
				'type'		=> 'onoff',
				'default'   => false,
				'el_properties'	=> array('OnClick'=>'javascript:yesno_panel(this,\'.hide_menus\');'),
				'hidegroup'	=> '#hide_menu_group',
				'save_option'=>true,
				'load_option'=>true
				);
			
		
		$temp[] = (object)array(
				'id'	=> 'hide_menu_group',
				'el_properties'=>array('class'=>'hide_group'),
				'type'=>'div_start'
		);
		
		$temp[] = (object)array(
				'id'=>'hide_menu_all',
				'label'=> '<b>Hide all menus</b>',
				'type'=>'checkbox',
				'el_properties'=>array('class'=>'hide_all'),
				'save_option'=>true,
				'load_option'=>true
		);		
		
		foreach($menu_names as $menu_name){
			
			$temp[] = (object)array(
					'id'=>'hide_menu_'.$menu_name->term_id,
					'label'=> $menu_name->name,
					'type'=>'checkbox',
					'save_option'=>true,
					'load_option'=>true
			);
		}
		
		$temp[] = (object)array(
			'id'=>'hide_menu_group',
			'type'=>'div_end'
		);	

		$t[$i]->options = $temp;
		
		//-------------------------	Listing Settings		
		$i++;
		$t[$i] = (object)array();
		$t[$i]->id 			= 'controle-listing'; 
		$t[$i]->label 		= __('List Settings','etm');//title on tab
		$t[$i]->right_label	= __('Set defaults for listings','etm');//title on tab
		$t[$i]->page_title	= __('List Configuration','etm');//title on content
		$t[$i]->theme_option = true;
		$t[$i]->plugin_option = true;
		
		
		
		$temp = array();
		
		$temp[] = (object)array(
			'type'	=> 'subtitle',
			'label'	=> __('List Settings','etm')
		);
		
		
		$temp[] = (object)array(
				'id'	=> 'limit_interval',
				'type'	=>'range',
				'label'	=> __('Number of objects shown per page : ','etm'),
				'description' => __('Number of object shown pr page','etm'),
				'min'	=> 10,
				'max'	=> 100,
				'step'	=> 1,
				'default'=>25,
				'save_option'=>true,
				'load_option'=>true
			);

			$getmenudatas = etm_tools_get_types();
			$count = 0;
			
			foreach($getmenudatas as $getmenudata){
				$temp[] = (object)array(
					'type'	=> 'clear',
				);
				$temp[] = (object)array(
					'id'	=> 'namesortlist'.$count,
					'type'	=> 'subtitle',
					'label'	=> __($getmenudata['name'],'etm')
				);
				
				$etm_columns = array();
				$etm_columns_tmp = array();
				$etm_columns = $getmenudata['etm_columns_group'];

				
				foreach($etm_columns as $etm_column){
					if($etm_column['sorteble'] == 'true'){
						$etm_columns_tmp[$etm_column['backtitle']] = $etm_column['title'];
					}
				}
		
				if(!empty($etm_columns_tmp) && count($etm_columns_tmp)>0){
					$temp[] = (object)array(
						'id'	=> 'sort_group_list_'.$getmenudata['tag'],
						'type'	=> 'select',
						'label'	=> __('First table sort by : ','etm'),
						'options'=> $etm_columns_tmp,
						'el_properties'=>array('class'=>'dropdown1','style'=>'width:165px'),
						'default'=>'2',
						'save_option'=>true,
						'load_option'=>true
					);
		
					$temp[] = (object)array(
						'id'	=> 'sort_group_list_direction_'.$getmenudata['tag'],
						'type'	=> 'select',
						'label'	=> __('First table direction : ','etm'),
						'options'=> array(
							'decs'=>'Descending (A..Z)',
							'asc'=>'Ascending (Z...A)'
						),
						'el_properties'=>array('class'=>'dropdown1','style'=>'width:165px'),
						'default'=>'2',
						'save_option'=>true,
						'load_option'=>true
					);
				}
				
				
				
				$etm_columns = array();
				$etm_columns_tmp = array();
				if(!empty($getmenudata['etm_columns_single'])){
					$etm_columns = $getmenudata['etm_columns_single'];
				}
				

				if(!empty($etm_columns) && count($etm_columns)>0){
					foreach($etm_columns as $etm_column){
						if($etm_column['sorteble'] == 'true'){
							$etm_columns_tmp[$etm_column['backtitle']] = $etm_column['title'];
						}
					}
				}
				if(!empty($etm_columns_tmp) && count($etm_columns_tmp)>0){
					$temp[] = (object)array(
						'id'	=> 'sort_single_list_'.$getmenudata['tag'],
						'type'	=> 'select',
						'label'	=> __('Second table sort by : ','etm'),
						'options'=> $etm_columns_tmp,
						'el_properties'=>array('class'=>'dropdown2','style'=>'width:165px'),
						'default'=>'2',
						'save_option'=>true,
						'load_option'=>true
					);
		
					$temp[] = (object)array(
						'id'	=> 'sort_single_list_direction_'.$getmenudata['tag'],
						'type'	=> 'select',
						'label'	=> __('Second table direction : ','etm'),
						'options'=> array(
							'decs'=>'Descending (A..Z)',
							'asc'=>'Ascending (Z...A)'
						),
						'el_properties'=>array('class'=>'dropdown1','style'=>'width:165px'),
						'default'=>'2',
						'save_option'=>true,
						'load_option'=>true
					);
				}
				$count++;
			}		
				
			$temp[] = (object)array(
				'type'	=> 'clear',
			);	
			
		$t[$i]->options = $temp;
		
		
		//--------------- show desing
		
		$i++;
		$t[$i] = (object)array();
		$t[$i]->id 			= 'select-design-listing'; 
		$t[$i]->label 		= __('Selector','etm');//title on tab
		$t[$i]->right_label	= __('Set defaults for design and layout','etm');//title on tab
		$t[$i]->page_title	= __('Design and Layout','etm');//title on content
		$t[$i]->theme_option = true;
		$t[$i]->plugin_option = true;
		
		$temp = array();
		
		$temp[] = (object)array(
			'type'	=> 'subtitle',
			'label'	=> __('Language Selector Settings','etm')
		);  
		
		$temp[] = (object)array(
				'id'	=> 'desing_menu_lang_string',
				'type'	=> 'text',
				'label'	=> __('Text layout','etm'),
				'description' => __('Set the layout for english text use [ENG] and the original text use [ORG]','etm'),
				'default' => '[ORG] ([ENG])',
				'el_properties'=>array('style'=>'width: 200px; margin-bottom: 20px;'),
				'save_option'=>true,
				'load_option'=>true
			);	
			
			
		$temp[] = (object)array(
			'type'=>'subtitle',
			'label'=>__('Menu Placement (do_action)','etm')	
		);
		

		$unified_css_style = 'width:185px;';
		
		
		$temp[] = (object)array(
				'id'	=> 'desing_menu_pos',
				'type'	=> 'onoff',
				'label'	=> __('Show Language Bar','etm'),
				'el_properties'	=> array('OnClick'=>'javascript:yesno_panel(this,\'.desing_menu_pos\');'),
				'description'=>__('Add a tag to your theme where you want the language bar to appear "&lt;?php do_action("etm_languagemenu") ?&gt;".','etm'),
				'default'=>true,
				'hidegroup'	=> '#hide_desing_menu_group',
				'save_option'=>true,
				'load_option'=>true
			);		
		
		$temp[] = (object)array(
				'id'	=> 'hide_desing_menu_group',
				'el_properties'=>array('class'=>'hide_group'),
				'type'=>'div_start'
		);
		
		$temp[] = (object)array(
				'id'	=> 'desing_menu_type',
				'type'	=> 'select',
				'label'	=> __('Select layout style','etm'),
				'options'=> array(
					'3'=>__('Bouncing List','etm'),
					'4'=>__('Box Slide','etm'),	
					'5'=>__('Rotating Bars','etm'),
					'6'=>__('Fluid Grid','etm'),
					'7'=>__('Responsive Circle','etm'),
					'0'=>__('Basic Drop-down list','etm'),
					'1'=>__('Side-by-Side','etm'),
					'2'=>__('Side-by-Side (Remove current flag)','etm')			
				),
				'description'=>__('Select formation types','etm'),
				'el_properties'=>array('class'=>'dropdown2','style'=>$unified_css_style),
				'default'=>'3',
				'save_option'=>true,
				'load_option'=>true
		);	
		
		$temp[] = (object)array(
				'id'	=> 'desing_menu_flag_size',
				'type'	=> 'select',
				'label'	=> __('Flag size','etm'),
				'options'=> array(
					'0'=>__('Small','etm'),
					'1'=>__('Medium','etm'),
					'2'=>__('Large','etm'),
					'3'=>__('X-Large','etm')
				),
				'description'=>__('Select flag size','etm'),
				'el_properties'=>array('class'=>'dropdown2','style'=>$unified_css_style),
				'default'=>'0',
				'save_option'=>true,
				'load_option'=>true
		);
			
		$temp[] = (object)array(
				'id'	=> 'desing_menu_info',
				'type'	=> 'select',
				'label'	=> __('Select Display Type','etm'),
				'options'=> array(
					'0'=>__('Show flag and text','etm'),
					'1'=>__('Show only flag','etm'),
					'2'=>__('Show only text','etm')					
				),
				'description'=>__('Select string type','etm'),
				'el_properties'=>array('class'=>'dropdown2','style'=>$unified_css_style),
				'default'=>'0',
				'save_option'=>true,
				'load_option'=>true
		);
		
		$temp[] = (object)array(
				'id'	=> 'desing_menu_hidearrow',
				'type'	=> 'onoff',
				'default'   => false,
				'label'	=> __('Hide arrow','etm'),
				'description'=>__('Hide the arrow to the side of the menu','etm'),
				'save_option'=>true,
				'load_option'=>true
			);
		
		$temp[] = (object)array(
				'id'	=> 'desing_menu_align',
				'type'	=> 'select',
				'label'	=> __('Select Alignment','etm'),
				'options'=> array(
					'alignleft'=>__('Left','etm'),
					'aligncenter'=>__('Center','etm'),
					'alignright'=>__('Right','etm')
										
				),
				'description'=>__('Select alignment','etm'),
				'el_properties'=>array('class'=>'dropdown2','style'=>$unified_css_style),
				'default'=>'0',
				'save_option'=>true,
				'load_option'=>true
		);
		
		$temp[] = (object)array(
				'id'	=> 'desing_menu_width',
				'type'	=> 'text',
				'label'	=> __('Width','etm'),
				'description' => __('Set the width of the bar in % or px','etm'),
				'el_properties'=>array('style'=>$unified_css_style),
				'save_option'=>true,
				'load_option'=>true
			);	
			
		$temp[] = (object)array(
			'id'=>'hide_desing_menu_group',
			'type'=>'div_end'
		);	
		
			$temp[] = (object)array(
				'type'	=> 'clear',
			);	
			
			
$temp[] = (object)array(
			'type'=>'subtitle',
			'label'=>__('Menu Placement Overlay','etm')	
	);	
				
				
	$temp[] = (object)array(
				'id'	=> 'desing_menu_pos_overlay',
				'type'	=> 'onoff',
				'label'	=> __('Language bar as overlay','etm'),
				'el_properties'	=> array('OnClick'=>'javascript:yesno_panel(this,\'.desing_menu_pos_dashboard\');'),
				'description'=>__('Adds language selector bear to the public part of the website,','etm'),
				'default'=>true,
				'hidegroup'	=> '#hide_overlay_desing_menu_group',
				'save_option'=>true,
				'load_option'=>true
			);		
		
		$temp[] = (object)array(
				'id'	=> 'hide_overlay_desing_menu_group',
				'el_properties'=>array('class'=>'hide_group'),
				'type'=>'div_start'
		);
		

		$temp[] = (object)array(
				'id'	=> 'desing_menu_type_overlay',
				'type'	=> 'select',
				'label'	=> __('Select layout style','etm'),
				'options'=> array(
					'3'=>__('Bouncing List','etm'),
					'5'=>__('Rotating Bars','etm'),
					'6'=>__('Fluid Grid','etm'),
					'7'=>__('Responsive Circle','etm'),
					'0'=>__('Basic Drop-down list','etm'),
					'1'=>__('Side-by-Side','etm'),
					'2'=>__('Side-by-Side (Remove current flag)','etm')			
				),
				'description'=>__('Select formation types','etm'),
				'el_properties'=>array('class'=>'dropdown2','style'=>$unified_css_style),
				'default'=>'3',
				'save_option'=>true,
				'load_option'=>true
			);
			
			
		
		$temp[] = (object)array(
				'id'	=> 'desing_menu_flag_size_overlay',
				'type'	=> 'select',
				'label'	=> __('Flag size','etm'),
				'options'=> array(
					'0'=>__('Small','etm'),
					'1'=>__('Medium','etm'),
					'2'=>__('Large','etm'),
					'3'=>__('X-Large','etm')
				),
				'description'=>__('Select flag size','etm'),
				'el_properties'=>array('class'=>'dropdown2','style'=>$unified_css_style),
				'default'=>'0',
				'save_option'=>true,
				'load_option'=>true
		);	
			
		$temp[] = (object)array(
				'id'	=> 'desing_menu_info_overlay',
				'type'	=> 'select',
				'label'	=> __('Select Display Type','etm'),
				'options'=> array(
					'0'=>__('Show flag and text','etm'),
					'1'=>__('Show only flag','etm'),
					'2'=>__('Show only text','etm')					
				),
				'description'=>__('Select string type','etm'),
				'el_properties'=>array('class'=>'dropdown2','style'=>$unified_css_style),
				'default'=>'0',
				'save_option'=>true,
				'load_option'=>true
		);

		$temp[] = (object)array(
				'id'	=> 'desing_menu_lockdonw_overlay',
				'type'	=> 'select',
				'label'	=> __('Select Lock Type','etm'),
				'options'=> array(
					'absolute'=>__('Absolute','etm'),
					'fixed'=>__('Fixed','etm')
				),
				'description'=>__('Select lockdown','etm'),
				'el_properties'=>array('class'=>'dropdown2','style'=>$unified_css_style),
				'default'=>'0',
				'save_option'=>true,
				'load_option'=>true
			);	
			

		$temp[] = (object)array(
				'id'	=> 'desing_menu_placement_overlay',
				'type'	=> 'select',
				'label'	=> __('Select Display Type','etm'),
				'options'=> array(
					'topleft'=>__('Top Left','etm'),
					'topright'=>__('Top Right','etm'),
					'sidetopleft'=>__('Side Top Left','etm'),
					'sidetopright'=>__('Side Top Right','etm'),
					'sidebottomleft'=>__('Side Bottom Left','etm'),
					'sidebottomright'=>__('Side Bottom Right','etm'),
					'bottomleft'=>__('Bottom Left','etm'),
					'bottomright'=>__('Bottom Right','etm')	
				),
				'description'=>__('Select postion','etm'),
				'el_properties'=>array('class'=>'dropdown2','style'=>$unified_css_style),
				'default'=>'0',
				'save_option'=>true,
				'load_option'=>true
		);	
		
		$temp[] = (object)array(
				'id'	=> 'desing_menu_hidearrow_overlay',
				'type'	=> 'onoff',
				'label'	=> __('Hide arrow','etm'),
				'description'=>__('Hide the arrow to the side of the menu','etm'),
				'default'=>false,
				'save_option'=>true,
				'load_option'=>true
			);
			
		$temp[] = (object)array(
				'id'	=> 'desing_menu_pixel_jump_overlay',
				'type'	=> 'onoff',
				'label'	=> __('Y jump to allow wp bar','etm'),
				'description'=>__('When wp admin bar shown will et drop the same number of pixel down','etm'),
				'default'=>false,
				'save_option'=>true,
				'load_option'=>true
			);
		
		$temp[] = (object)array(
				'id'	=> 'desing_menu_xpostion_overlay',
				'type'	=>'range',
				'label'	=> __('X postion','etm'),
				'description' => __('Set x postion extra','etm'),
				'min'	=> 0,
				'max'	=> 500,
				'step'	=> 1,
				'default'=>0,
				'save_option'=>true,
				'load_option'=>true
		);	
		
					
		$temp[] = (object)array(
				'id'	=> 'desing_menu_ypostion_overlay',
				'type'	=>'range',
				'label'	=> __('Y postion','etm'),
				'description' => __('Set y postion extra','etm'),
				'min'	=> 0,
				'max'	=> 500,
				'step'	=> 1,
				'default'=>0,
				'save_option'=>true,
				'load_option'=>true
		);		
			
		$temp[] = (object)array(
				'id'	=> 'desing_menu_backgroundalpha_overlay',
				'type'	=>'range',
				'label'	=> __('Background alpha','etm'),
				'description' => __('Set background alpha','etm'),
				'min'	=> 0,
				'max'	=> 1,
				'step'	=> 0.01,
				'default'=>0,
				'save_option'=>true,
				'load_option'=>true
		);
		
		
		$temp[] = (object)array(
				'id'	=> 'desing_menu_backgroundcolor_overlay',
				'type'	=> 'farbtastic',
				'label'	=> __('Background color','etm'),
				'default' => '#ffffff',
				'description' => __('set background color','etm'),
				'save_option'=>true,
				'load_option'=>true
			);
		
			
			
		$temp[] = (object)array(
				'id'	=> 'desing_menu_boxalpha_overlay',
				'type'	=>'range',
				'label'	=> __('Boxshadow alpha','etm'),
				'description' => __('Set Boxshadow alpha','etm'),
				'min'	=> 0,
				'max'	=> 1,
				'step'	=> 0.01,
				'default'=>0,
				'save_option'=>true,
				'load_option'=>true
			);
			
		$temp[] = (object)array(
				'id'	=> 'desing_menu_boxcolor_overlay',
				'type'	=> 'farbtastic',
				'label'	=> __('Boxshadow color','etm'),
				'default' => '#000000',
				'description' => __('set background color','etm'),
				'save_option'=>true,
				'load_option'=>true
			);
			
		$temp[] = (object)array(
				'id'	=> 'showonlyonpostID',
				'type'	=> 'text',
				'label'	=> __('Show on following IDs','etm'),
				'default' => '',
				'description' => __('f empty it will show on all pages and posts. Enter multiple post id and separate by comma:  2,23,45,89.','etm'),
				'el_properties'=>array('style'=>$unified_css_style),
				'save_option'=>true,
				'load_option'=>true
			);	
			
		$temp[] = (object)array(
			'id'=>'hide_overlay_desing_menu_group',
			'type'=>'div_end'
		);
			
			
			$temp[] = (object)array(
				'type'	=> 'clear',
			);			
			
	$temp[] = (object)array(
			'type'=>'subtitle',
			'label'=>__('Menu Placement Dashboard','etm')	
	);	
			
			
	$temp[] = (object)array(
				'id'	=> 'desing_menu_pos_dashboard',
				'type'	=> 'onoff',
				'label'	=> __('Dashboard Language Bar','etm'),
				'el_properties'	=> array('OnClick'=>'javascript:yesno_panel(this,\'.desing_menu_pos_dashboard\');'),
				'description'=>__('Add a language bar to the dashboard,','etm'),
				'default'=>true,
				'hidegroup'	=> '#hide_dashboard_desing_menu_group',
				'save_option'=>true,
				'load_option'=>true
			);		
		
		$temp[] = (object)array(
				'id'	=> 'hide_dashboard_desing_menu_group',
				'el_properties'=>array('class'=>'hide_group'),
				'type'=>'div_start'
		);
		
		$temp[] = (object)array(
				'id'	=> 'desing_menu_type_dashboard',
				'type'	=> 'select',
				'label'	=> __('Select layout style','etm'),
				'options'=> array(
					'3'=>__('Bouncing List','etm'),
					'4'=>__('Box Slide','etm'),	
					'5'=>__('Rotating Bars','etm'),
					'6'=>__('Fluid Grid','etm'),
					'7'=>__('Responsive Circle','etm'),
					'0'=>__('Basic Drop-down list','etm'),
					'1'=>__('Side-by-Side','etm'),
					'2'=>__('Side-by-Side (Remove current flag)','etm')				
				),
				'description'=>__('Select formation types','etm'),
				'el_properties'=>array('class'=>'dropdown2','style'=>$unified_css_style),
				'default'=>'3',
				'save_option'=>true,
				'load_option'=>true
		);	
			
		$temp[] = (object)array(
				'id'	=> 'desing_menu_flag_size_dashboard',
				'type'	=> 'select',
				'label'	=> __('Select flag size','etm'),
				'options'=> array(
					'0'=>__('Small','etm'),
					'1'=>__('Medium','etm'),
					'2'=>__('Large','etm'),
					'3'=>__('X-Large','etm')
				),
				'description'=>__('Select flag size','etm'),
				'el_properties'=>array('class'=>'dropdown2','style'=>$unified_css_style),
				'default'=>'0',
				'save_option'=>true,
				'load_option'=>true
		);	
			
		$temp[] = (object)array(
				'id'	=> 'desing_menu_info_dashboard',
				'type'	=> 'select',
				'label'	=> __('Select Display Type','etm'),
				'options'=> array(
					'0'=>__('Show flag and text','etm'),
					'1'=>__('Show only flag','etm'),
					'2'=>__('Show only text','etm')					
				),
				'description'=>__('Select string type','etm'),
				'el_properties'=>array('class'=>'dropdown2','style'=>$unified_css_style),
				'default'=>'0',
				'save_option'=>true,
				'load_option'=>true
		);
		
		$temp[] = (object)array(
				'id'	=> 'desing_menu_align_dashboard',
				'type'	=> 'select',
				'label'	=> __('Select Alignment','etm'),
				'options'=> array(
					'alignleft'=>__('Left','etm'),
					'aligncenter'=>__('Center','etm'),
					'alignright'=>__('Right','etm')
										
				),
				'description'=>__('Select alignment','etm'),
				'el_properties'=>array('class'=>'dropdown2','style'=>$unified_css_style),
				'default'=>'0',
				'save_option'=>true,
				'load_option'=>true
		);
		
		$temp[] = (object)array(
				'id'	=> 'desing_menu_hidearrow_dashboard',
				'type'	=> 'onoff',
				'label'	=> __('Hide arrow','etm'),
				'description'=>__('Hide the arrow to the side of the menu','etm'),
				'default'=>false,
				'save_option'=>true,
				'load_option'=>true
			);
			
		$temp[] = (object)array(
				'id'	=> 'desing_menu_title_dashboard',
				'type'	=> 'text',
				'label'	=> __('Metabox header','etm'),
				'description' => __('Dashboard header text','etm'),
				'el_properties'=>array('style'=>$unified_css_style),
				'save_option'=>true,
				'load_option'=>true
			);	
		
		$temp[] = (object)array(
				'id'	=> 'desing_menu_width_dashboard',
				'type'	=> 'text',
				'label'	=> __('Width','etm'),
				'description' => __('Set the width of the bar in % or px','etm'),
				'el_properties'=>array('style'=>$unified_css_style),
				'save_option'=>true,
				'load_option'=>true
			);	
			
		$temp[] = (object)array(
			'id'=>'hide_dashboard_desing_menu_group',
			'type'=>'div_end'
		);
			
		/*$temp[] = (object)array(
				'id'	=> 'desing_menu_css',
				'type'	=> 'text',
				'label' => 'Custom CSS ',
				'description'	=> __('Add custom CSS. Standard CSS location here. ','etm') . '<a href="'.EASY_TRANSLATION_MANAGER_URL.'css/etm-style.css" target="_blank" >'.EASY_TRANSLATION_MANAGER_URL.'css/etm-style.css</a>',
				'el_properties'=>array('style'=>'width: 186px; margin-left: 15px;'),
				'save_option'=>true,
				'load_option'=>true
			);*/	

		$t[$i]->options = $temp;
		
		
		//------ Languages
		$i++;
		$t[$i] = (object)array();
		$t[$i]->id 			= 'languages-fields'; 
		$t[$i]->label 		= __('Languages','etm');//title on tab
		$t[$i]->right_label	= __('Select available languages','etm');//title on tab
		$t[$i]->page_title	= __('Language','etm');//title on content
		$t[$i]->theme_option = true;
		$t[$i]->plugin_option = true;
        
		$temp = array();
		$temp[] = (object)array(
			'id'	=> 'languisha_start',
			'type'	=> 'subtitle',
			'label'	=> __('Most Common Languages','etm')
		);        
        
        
		$main_array_default = etm_tools_subval_sort($main_array_default,'default_pos','decs');
		$secoundary_array_default = etm_tools_subval_sort($secoundary_array_default,'english_name','decs');
        
        $count = 0;
        foreach($main_array_default as $key=>$tmpdata){

            $url_icon = etm_tools_create_icons_url($tmpdata['icon'],1);

            if(!empty($url_icon)){
            	if($count == 0){
            		 $url_icon = '<img style="padding-bottom: 10px;margin-top:-3px;padding-right:5px;float:left" height="24" src="'.$url_icon.'">';   
            	} else {
            		 $url_icon = '<img style="margin-top:-3px;padding-right:5px;float:left" height="24" src="'.$url_icon.'">';   
            	}
                
            }
            $count = 1;
            
            $description = __('Get/post tags','etm'). ' : ' . $tmpdata['code'] . (empty($tmpdata['default_locale']) == false ? ' (' . $tmpdata['default_locale'] . ')' : ''); 
            
			$temp[] = (object)array(
				'id'	=> 'languish_'.$tmpdata['code'],
				'type'	=> 'subtitle',
				'label'	=> $url_icon.'   <div style=float:left>'. $tmpdata['org_name'].' ('.$tmpdata['english_name'].')</div>'
			);
        
            $temp[] = (object)array(
				'id'	=> 'lang_'.$tmpdata['code'],
				'type'	=> 'radio',
				'label'	=> '',
                'description'=>$description,
				'options'=> array('0'=>'<span style="margin-right:20px">'.__('Deactivate','etm').'</span>','1'=>'<span style="margin-right:20px">'.__('Only test ip','etm').'</span>','2'=>'<span style="margin-right:20px">'.__('Activate','etm').'</span>'),
				'el_properties'=>array('class'=>'selectedlangages'),
				'default'=>'0',
				'save_option'=>true,
				'load_option'=>true
			);
        }

		$temp[] = (object)array(
				'type'	=> 'submit',
				'label'	=> __('Save','etm'),
				'class' => 'button-primary submit_etm_first',
				'save_option'=>false,
				'load_option'=>false
			);
        
		$temp[] = (object)array(
			'id'	=> 'languisha_middel',
			'type'	=> 'subtitle',
			'label'	=> '<div style="margin-top: 30px;">'.__('Alphabetically Sorted','etm').'</div>'
		);
        
        $count = 0;
        
        foreach($secoundary_array_default as $key=>$tmpdata){

            $url_icon = etm_tools_create_icons_url($tmpdata['icon'],1);

            if(!empty($url_icon)){
            	if($count == 0){
            		 $url_icon = '<img style="padding-bottom: 10px;margin-top:-3px;padding-right:5px;float:left" height="24" src="'.$url_icon.'">';   
            	} else {
            		 $url_icon = '<img style="margin-top:-3px;padding-right:5px;float:left" height="24" src="'.$url_icon.'">';   
            	}
                
            }
            $count = 1;
            
            
 			$description = __('Get/post tags','etm'). ' : ' . $tmpdata['code'] . (empty($tmpdata['default_locale']) == false ? ' (' . $tmpdata['default_locale'] . ')' : '');
            
             
			$temp[] = (object)array(
				'id'	=> 'languish_'.$tmpdata['code'],
				'type'	=> 'subtitle',
				'label'	=> $url_icon.'   <div style=float:left>'. $tmpdata['org_name'].' ('.$tmpdata['english_name'].')</div>'
			);
            
            $temp[] = (object)array(
				'id'	=> 'lang_'.$tmpdata['code'],
				'type'	=> 'radio',
				'label'	=> '',
                'description'=>$description,
				'options'=> array('0'=>'<span style="margin-right:20px">'.__('Deactivate','etm').'</span>','1'=>'<span style="margin-right:20px">'.__('Only test ip','etm').'</span>','2'=>'<span style="margin-right:20px">'.__('Activate','etm').'</span>'),
				'el_properties'=>array('class'=>'selectedlangages'),
				'default'=>'0',
				'save_option'=>true,
				'load_option'=>true
			);
        }
        
        $t[$i]->options = $temp;	
		
        
		
		//------ Sort Languages
		$i++;
		$t[$i] = (object)array();
		$t[$i]->id 			= 'languages-sort-fields'; 
		$t[$i]->label 		= __('Reorder','etm');//title on tab
		$t[$i]->right_label	= __('Drag and drop to reorder languages','etm');//title on tab
		$t[$i]->page_title	= __('Language Order','etm');//title on content
		$t[$i]->theme_option = true;
		$t[$i]->plugin_option = true;
        
		$temp = array();
		$temp[] = (object)array(
			'id'	=> 'languisha_order_start',
			'type'	=> 'subtitle',
			'label'	=> __('Drag and drop to reorder languages','etm')
		);   
		
		
		$temp[] =(object)array(
				'id'			=> 'flag_sort',
				'name'			=> 'flag_sort[]',
				'label'			=> __('Flag order','rhsco'),
				'description'	=> sprintf('<p>%s</p><p>%s</p>',
					__('Drag and reorder the flags. Flags on the top are printed first.','etm'),
					__('Only enabled flags appear on this tab.','etm')
				),
				'type'			=> 'callback',
				'callback'		=> array(&$this,'flag_layout'),
				'save_option'	=>true,
				'load_option'	=>true
			);

		$t[$i]->options = $temp;
		
		
		//------ Multiple Domain Names
		$i++;
		$t[$i] = (object)array();
		$t[$i]->id 			= 'multiple-domain-names'; 
		$t[$i]->label 		= __('Multiple Domain','etm');//title on tab
		$t[$i]->right_label	= __('Enter domain name for each language','etm');//title on tab
		$t[$i]->page_title	= __('Multiple Domain Names','etm');//title on content
		$t[$i]->theme_option = true;
		$t[$i]->plugin_option = true;
        
		$temp = array();
		$temp[] = (object)array(
			'id'	=> 'languisha_order_start',
			'type'	=> 'subtitle',
			'label'	=> __('Multiple Domain Names','etm')
		);   
		
		
		$temp[] =(object)array(
				'id'			=> 'domain_list',
				'name'			=> 'domain_list[]',
				'label'			=> __('Multiple Domain','rhsco'),
				'description'	=> __('For languages in different domains to work, all languages need to point to the same WordPress installation. You need to set the DNS settings to do this.','etm').'<br>'.
								   __('Enter the domain names including http or https for the language you want to use specific domains.','etm').'<br><br>'.__('Example','etm').':<br>http://my-domain.com<br>http://my-domain.es<br>http://my-domain.de<br><br>'.
								   __('If nothing is entered the default domain is used','etm'),
				'type'			=> 'callback',
				'callback'		=> array(&$this,'domain_list'),
				'save_option'	=>true,
				'load_option'	=>true
			);
		
		$t[$i]->options = $temp;   
        
        //------------------------- Plugins -------------------
            $i++;
            $t[$i] = (object)array();
            $t[$i]->id 			= 'extra-plugins'; 
            $t[$i]->label 		= __('Other Plugins','etm');//title on tab
            $t[$i]->right_label	= __('Enable support for other plugins','etm');//title on tab
            $t[$i]->page_title	= __('Enable support for other plugins','etm');//title on content
            $t[$i]->theme_option = true;
            $t[$i]->plugin_option = true;
            $temp = array();
		
			$temp[] = (object)array(
				'id'	=> 'languisha_order_start',
				'type'	=> 'subtitle',
				'label'	=> __('Automated Translation with Yandex','etm')
			);   
		
			
            $temp[] =(object)array(
				'id'		=> 'translator_yandex',
				'label'		=> __('Translation API key','etm'),
				'type'		=> 'text',
				'description' => __('We are using Yandex Linguistic technologies for automated translation service. This is available for Themes, Plugins, Add-ons and Menus, and is a free service.  
You need a API key to use the service. <a target="_blank" href="http://api.yandex.com/translate/">Click to get API Key</a>','etm').'', 
				'el_properties'=>array('style'=>'width:258px;'),
          
				'save_option'=>true,
				'load_option'=>true
				);
		
 			$temp[] = (object)array(
				'type'	=> 'clear',
			);	
		
			$temp[] = (object)array(
				'id'	=> 'seo_plugin_by_yoast_start',
				'type'	=> 'subtitle',
				'label'	=> __('WordPress SEO by Yoast','etm')
			);
			

			
            $temp[] =(object)array(
				'id'		=> 'seo_plugin_by_yoast',
				'label'		=> __('WordPress SEO by Yoast.','etm'),
				'type'		=> 'onoff',
				'default'   => false,
                'description'=>__('If you have this plugin installed, you can enable Easy Translation Manager support for it.','etm'),               
				'save_option'=>true,
				'load_option'=>true
				);
  
            $t[$i]->options = $temp;

        //------------------------- Plugins -------------------
            $i++;
            $t[$i] = (object)array();
            $t[$i]->id 			= 'extra-plugins2'; 
            $t[$i]->label 		= __('','etm');//title on tab
            $t[$i]->right_label	= __('','etm');//title on tab
            $t[$i]->page_title	= __('','etm');//title on content
            $t[$i]->theme_option = true;
            $t[$i]->plugin_option = true;
            $temp = array();

            $t[$i]->options = $temp;

		return $t;
	}
	
	function create_header(){
			wp_print_scripts('jquery-ui-sortable');
        ?>
        <script>
		jQuery(document).ready(function($){
			if(jQuery('.flags_order_holder').sortable){
				jQuery('.flags_order_holder').sortable();	
			}
		});
        
        function setAllToValue(toValue){
           jQuery(document).ready(function($){  
                jQuery('#languages-fields').find('input[class="selectedlangages"]').each(function(index) {
                    
                    if(jQuery(this).val() == toValue){
                        jQuery(this).attr('checked', true);
                    } else {
                        jQuery(this).attr('checked', false);
                    }
                });
           });
        }
		//this would output in the head secction.
        </script>
        <?php
	}

}
?>