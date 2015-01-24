<?php 

//--------------------------------------- Addon --------------------------------------------
//--------------------------------------------------------------------------------------------
function etm_tools_get_addon()
{ 
    global $etm_tag,$easy_translation_manager_plugin,$total_found;
    $upload_dir = wp_upload_dir();
    if(!empty($_POST['etm_folder'])){
		$etm_folder = $_POST['etm_folder'];
	}
	
    if(!empty($_POST['post_tag'])){
		$etm_tag = $_POST['post_tag'];
	}


	$intervalt = 10;
	$page = 0;
	$count = 0;

	if(!empty($_POST['interval'])){
	    $intervalt = $_POST['interval']; 
    }
    
    if(!empty($_POST['page'])){
	    $page = $_POST['page'];
    }	


    $sort_dir = false;
    $sort_col = 'title';
    if(!empty($_POST['sort_col'])){
    	$sort_col = $_POST['sort_col'];
    }

    
    if(!empty($_POST['sort_dir']) and $_POST['sort_dir'] == 'asc'){
    	$sort_dir = true;
    }
    
    
    if (!function_exists('get_plugins'))
    	require_once (ABSPATH."wp-admin/includes/plugin.php");

    $tmp_data_array = array();
	$plugins = get_plugins();
	
	$plugins_add = array();
    $languashed = etm_tools_retrive_languages_data(etm_tools_retrive_aktiv_languages(),true); 
    
	foreach($plugins as $key => $plugin)
	{
		$folder = explode("/",$key);
		$plugins_add[] = utf8_decode(strip_tags($folder[0]));
	}
	
	if(!empty($plugins_add)){
		foreach($plugins_add as $ps_add){
			if(file_exists($upload_dir['basedir'].'/'.$ps_add)){	
				if ($handle = opendir($upload_dir['basedir'].'/'.$ps_add)) {
				    while ($entry = readdir($handle)) {
				    	if(!is_dir($entry)){
				    	
				    		if(($page*$intervalt) <= $count and (($page+1)*$intervalt) > $count){
				
				        		$tmp_data_array[] = array('title'=>utf8_decode(strip_tags($entry)),
				                                  'mainplugins'=>utf8_decode(strip_tags($ps_add)),
				                                  'folder' => $ps_add.'/'.$entry,
				                                  'translationProcent' => etm_tools_check_lang($languashed,$etm_tag,$ps_add.'/'.$entry));   
				        	}
						$count++;
				    	}
				    }
				    closedir($handle);
				}
				
			}
		}
	}
	
	etm_aasort($tmp_data_array,$sort_col,$sort_dir);
	$total_found = $count;
    return $tmp_data_array;
}

function etm_tools_get_addon_folder($firstime = true){
	return etm_tools_get_plugins_themes_folder($firstime,'addons');
}

//--------------------------------------- Plugins --------------------------------------------
//--------------------------------------------------------------------------------------------

// controles the first selection
function etm_tools_get_plugins()
{ 
    global $etm_tag,$easy_translation_manager_plugin,$total_found;
    
    if(!empty($_POST['etm_folder'])){
		$etm_folder = $_POST['etm_folder'];
	}
	
    if(!empty($_POST['post_tag'])){
		$etm_tag = $_POST['post_tag'];
	}


	$intervalt = 10;
	$page = 0;
	$count = 0;

	if(!empty($_POST['interval'])){
	    $intervalt = $_POST['interval']; 
    }
    
    if(!empty($_POST['page'])){
	    $page = $_POST['page'];
    }	


    $sort_dir = false;
    $sort_col = 'title';
    if(!empty($_POST['sort_col'])){
    	$sort_col = $_POST['sort_col'];
    }
    
    if($sort_col == 'title'){
	    $sort_col ='Title';
    }
    if($sort_col == 'auther'){
	    $sort_col ='AuthorName';
    }  
    if($sort_col == 'version'){
	    $sort_col ='Version';
    } 
    
    
    
    if(!empty($_POST['sort_dir']) and $_POST['sort_dir'] == 'asc'){
    	$sort_dir = true;
    }
    
    
    if (!function_exists('get_plugins'))
    	require_once (ABSPATH."wp-admin/includes/plugin.php");

    $tmp_data_array = array();
	$plugins = get_plugins();
    $languashed = etm_tools_retrive_languages_data(etm_tools_retrive_aktiv_languages(),true); 
    
    if(empty($easy_translation_manager_plugin->etm_tools_retrive['hide_plugins']) || empty($easy_translation_manager_plugin->etm_tools_retrive['hide_plugins_all'])){

		
    	foreach($plugins as $key => $plugin)
    	{
    		$folder = explode("/",$key);
    		$plugins[$key]['folder'] = utf8_decode(strip_tags($folder[0]));
    	}
		
    	etm_aasort($plugins,$sort_col,$sort_dir);
    
		$total_found = count($plugins);
    
    	foreach($plugins as $key => $plugin)
    	{
	        if(empty($easy_translation_manager_plugin->etm_tools_retrive['hide_plugins_'. urlencode($plugin['folder'])])){
        		if(($page*$intervalt) <= $count and (($page+1)*$intervalt) > $count){

	        		$tmp_data_array[] = array('title'=>utf8_decode(strip_tags($plugin['Title'])),
	                                  'authorName'=>utf8_decode(strip_tags($plugin['Author'])),
	                                  'auther'=>utf8_decode(strip_tags($plugin['AuthorName'])),
	                                  'version'=>utf8_decode(strip_tags($plugin['Version'])),
	                                  'folder' => $plugin['folder'],
	                                  'translationProcent' => etm_tools_check_lang($languashed,$etm_tag,$plugin['folder']));   
	        	}
			$count++;
	        } else {
			$total_found--;
			}
		
		}

    }
    return $tmp_data_array;
}

// controles the secound selection
function etm_tools_get_plugins_themes_folder($firstime = true,$foldertypepos= 'plugins',$tmp_reload=false)
{
    global $wpdb,$etm_folder,$etm_tag,$total_found;
    
    
    
    if(!empty($_POST['etm_folder'])){
		$etm_folder = $_POST['etm_folder'];
	}
	
    if(!empty($_POST['post_tag'])){
		$etm_tag = $_POST['post_tag'];
	}


	$intervalt = 10;
	$page = 0;
	$count = 0;

	
	if($foldertypepos == 'addons'){
		 $upload_dir = wp_upload_dir();
		 $folder_url = $upload_dir['basedir']."/".$etm_folder."/";
		 	 
	} else {
	   	 $folder_url = ABSPATH."wp-content/".$foldertypepos."/".$etm_folder."/";
	}



    
    if($tmp_reload){
        etm_tools_recurseDir($folder_url,true);
    }
    
    
	if(!empty($_POST['seachtitle'])){
   	 	/*$sql_tmp = "SELECT SQL_CALC_FOUND_ROWS pindex.* FROM  {$wpdb->prefix}etm_plugin_index pindex, {$wpdb->prefix}etm_plugin_string pstring WHERE pindex.deleted = 0 and pindex.category_type ='".$etm_tag."' and pindex.folder_name ='".$etm_folder."' and ((pindex.id = pstring.lang_index_id and pstring.translatede_string LIKE '%".$_POST['seachtitle']."%') or (pindex.default_string LIKE '%".$_POST['seachtitle']."%')) group by pindex.id";
   	 	*/
   	 	
   	 	$sql_tmp_extra = "SELECT pindex.id FROM  {$wpdb->prefix}etm_plugin_index pindex, {$wpdb->prefix}etm_plugin_string pstring WHERE pindex.deleted = 0 and pindex.category_type ='".$etm_tag."' and pindex.folder_name ='".$etm_folder."' and pindex.id = pstring.lang_index_id and pstring.translatede_string LIKE '%".$_POST['seachtitle']."%' group by pindex.id";
   	 	
   	 	$sqldata_tmp_extra = $wpdb->get_col($sql_tmp_extra);

   	 	if(!empty($sqldata_tmp_extra)){
	   	 	$sqldata_tmp_extra = ' or pindex.id in ('.implode(',', $sqldata_tmp_extra).')';
   	 	} else {
	   	 	$sqldata_tmp_extra = '';
   	 	}
	   	 	
   	 	$sql_tmp = "SELECT SQL_CALC_FOUND_ROWS pindex.* FROM  {$wpdb->prefix}etm_plugin_index pindex WHERE pindex.deleted = 0 and pindex.category_type ='".$etm_tag."' and pindex.folder_name ='".$etm_folder."' and pindex.default_string LIKE '%".$_POST['seachtitle']."%'".$sqldata_tmp_extra." group by pindex.id";	
	} else {
		$sql_tmp = "SELECT * FROM  {$wpdb->prefix}etm_plugin_index WHERE deleted = 0 and category_type ='".$etm_tag."' and folder_name ='".$etm_folder."'";
		$total_found = $wpdb->get_var( "SELECT count(*) FROM  {$wpdb->prefix}etm_plugin_index WHERE deleted = 0 and category_type ='".$etm_tag."' and folder_name ='".$etm_folder."' limit 1");
	}
	

   if(!empty($_POST['sort_col'])){
    	$sql_tmp .= ' ORDER BY '. $_POST['sort_col'];
    }
    
    
    
 
    if(!empty($_POST['sort_dir'])){
    	 if($_POST['sort_dir'] == 'decs'){
    	 	$_POST['sort_dir'] = 'desc';
    	 }
    
    
    	$sql_tmp .= ' ' . $_POST['sort_dir'];
    }
   
    if(!empty($_POST['interval'])){
	    if(!empty($_POST['page'])){
		    $sql_tmp .= ' LIMIT ' .($_POST['interval']*$_POST['page']).',';
	    } else {
		    $sql_tmp .= ' LIMIT ' .($_POST['interval']*0).',';
	    }	 
	    
	    $sql_tmp .= ' ' . $_POST['interval'];   
    }
    

    
    $sqldata_tmp = $wpdb->get_results($sql_tmp);

    if(empty($sqldata_tmp) && empty($_POST['seachtitle'])){
        etm_tools_recurseDir($folder_url,true);
        $sqldata_tmp = $wpdb->get_results($sql_tmp);
        $total_found = $wpdb->get_var( "SELECT count(*) FROM  {$wpdb->prefix}etm_plugin_index WHERE deleted = 0 and category_type ='".$etm_tag."' and folder_name ='".$etm_folder."' limit 1");
    }
    
	if(!empty($_POST['seachtitle'])){
		$total_found = $wpdb->get_var("SELECT FOUND_ROWS()");   	 	
    }    
    
    
    $return_array = array();
    
    $languashed = etm_tools_retrive_languages_data(etm_tools_retrive_aktiv_languages(),true); 
    if(count($sqldata_tmp) > 0)
    {  
        foreach($sqldata_tmp as $tmp)
        {

            $sqllangcheck = "SELECT lang_code FROM {$wpdb->prefix}etm_plugin_string WHERE translatede_string!='' and lang_index_id ='".$tmp->id."'";
            $sqllangcheckdata = $wpdb->get_results($sqllangcheck);

            if(strlen($tmp->default_string) > 100)
            {
                $strings = substr($tmp->default_string, 0,100) . '...'; 
            } else {
                $strings = $tmp->default_string;
            } 
            
           $strings = utf8_decode($strings);
            
            
            $return_manual = ' ';
            
            if($tmp->manual_added == 1 or $tmp->mo_tag == 'Variable textdomain'){
	            $return_manual .= '<img style="cursor: pointer; height: 14px; width: 14px; margin-bottom: 1px; margin-right: 5px;" src="'.EASY_TRANSLATION_MANAGER_URL.'images/edit.png" onclick="redigerManualControl(\''.htmlentities($strings).'\',\''.$tmp->mo_tag.'\',\''.$tmp->id.'\',\''.$etm_tag.'\',\''.$tmp->file.'\')">';
            }
            
            if($tmp->manual_added == 1){
	            $return_manual .= '<img style="cursor: pointer;" src="'.EASY_TRANSLATION_MANAGER_URL.'images/delete.png" onclick="deleteManualControl(\''.$strings.'\',\''.$tmp->id.'\',\''.$etm_tag.'\')">';
            }
            
            $retrun_answer = '0';   
            $return_lang = etm_tools_create_languages_click_link($languashed,$tmp->id,$sqllangcheckdata,$etm_tag);
            if(empty($tmp->mo_tag)){
	            $retrun_answer = '1';
	            $return_lang = '<span style="color:#cccccc">No textdomain!</span>';
            } else if($tmp->mo_tag == 'Variable textdomain'){
	            $retrun_answer = '2';
	            $return_lang = '<span style="color:#cccccc">Variable textdomain!</span>';
	            $tmp->mo_tag = '';
            }
            
            if(!empty($tmp->default_placeholder)){
	            $tmp->mo_tag .= ' ('.$tmp->default_placeholder.')';
            }
            

            $return_array[] = array('default_string'=>htmlentities($strings),
                'id' => $tmp->id,
                'mo_tag' => $tmp->mo_tag,
                'file' => $tmp->file,
                'manual_added' => ($tmp->manual_added == 1 ? 'Manuel':'Auto'),
                'languages' => $return_lang,
                'tools' => $return_manual,
                'noteditible' => $retrun_answer
            );   
        }
    }
    return $return_array;
}


//--------------------------------------- Themes --------------------------------------------
//-------------------------------------------------------------------------------------------

// controles the first selection
function etm_tools_get_themes()
{
    global $etm_tag,$easy_translation_manager_plugin,$total_found,$wp_version;
    
    if(!empty($_POST['etm_folder'])){
		$etm_folder = $_POST['etm_folder'];
	}
	
    if(!empty($_POST['post_tag'])){
		$etm_tag = $_POST['post_tag'];
	}


	$intervalt = 10;
	$page = 0;
	$count = 0;

	if(!empty($_POST['interval'])){
	    $intervalt = $_POST['interval']; 
    }
    
    if(!empty($_POST['page'])){
	    $page = $_POST['page'];
    }	


    $sort_dir = false;
    $sort_col = 'title';
    if(!empty($_POST['sort_col'])){
    	$sort_col = $_POST['sort_col'];
    }
    
    if($sort_col == 'title'){
	    $sort_col ='Title';
    }
    if($sort_col == 'auther'){
	    $sort_col ='AuthorName';
    }  
    if($sort_col == 'version'){
	    $sort_col ='Version';
    } 
    
    
    
    if(!empty($_POST['sort_dir']) and $_POST['sort_dir'] == 'asc'){
    	$sort_dir = true;
    }

    if (!function_exists('get_themes'))
    	require_once (ABSPATH."wp-admin/includes/themes.php");

    $languashed = etm_tools_retrive_languages_data(etm_tools_retrive_aktiv_languages(),true); 
    $tmp_data_array = array();
    
    
	if (version_compare($wp_version, '3.4', '>=')) {
		$themes = wp_get_themes();
	} else {
		$themes = get_themes();
	}
    
    
	
    
    if(empty($easy_translation_manager_plugin->etm_tools_retrive['hide_theme']) || empty($easy_translation_manager_plugin->etm_tools_retrive['hide_themes_all'])){
    
    
    	foreach($themes as $key => $theme)
    	{
    		$folder = explode("/",$key);
    		$themes[$key]['folder'] = utf8_decode(strip_tags($folder[0]));
    	}
    
    	etm_aasort($themes,$sort_col,$sort_dir);
    
		$total_found = count($themes);
    	
    	foreach($themes as $key => $theme){
       	 	if(($page*$intervalt) <= $count and (($page+1)*$intervalt) > $count){
	       	 	if(empty($easy_translation_manager_plugin->etm_tools_retrive['hide_theme']) || empty($easy_translation_manager_plugin->etm_tools_retrive['hide_themes_'.urlencode($folder[0])])){
	        		$tmp_data_array[] = array('title'=>utf8_decode(strip_tags($theme['Title'])),
	            		'authorName'=>utf8_decode(strip_tags($theme['Author'])),
	            		'auther'=>utf8_decode(strip_tags($theme['Author Name'])),
	            		'version'=>utf8_decode(strip_tags($theme['Version'])),
	            		'folder' => $theme['Template'],
	            		'translationProcent' => etm_tools_check_lang($languashed,$etm_tag,$theme['Template']));
	            } 
	         } 
	         $count++; 
    	}
    }
    if(!empty($easy_translation_manager_plugin->etm_tools_retrive['sort_group_list'])){
    	$etm_sort_group_list = $easy_translation_manager_plugin->etm_tools_retrive['sort_group_list']; 
    }
    if(!empty($easy_translation_manager_plugin->etm_tools_retrive['sort_group_list_direction'])){
   		$etm_sort_group_list_direction = $easy_translation_manager_plugin->etm_tools_retrive['sort_group_list_direction'];
    }

    if(!empty($etm_sort_group_list)){
        $tmp_data_array = etm_tools_subval_sort($tmp_data_array,$etm_sort_group_list,$etm_sort_group_list_direction);
    }    

    return $tmp_data_array;
}

// controles the secound selection
function etm_tools_get_themes_folder($firstime = true){
    return etm_tools_get_plugins_themes_folder($firstime,'themes');
}


//---------------------------------------------- Pages -------------------------------------------------
//------------------------------------------------------------------------------------------------------

// controles the first selection
function etm_tools_get_pages(){
    global $etm_tag,$wpdb,$easy_translation_manager_plugin,$total_found,$wp_query;
	$status_tmp = array('publish','pending','draft','private','static','object','attachment','inherit','future');
	$status_tmp2 = array();
	foreach($status_tmp as $key => $status_t){
		if(empty($easy_translation_manager_plugin->etm_tools_retrive['hide_pages_'.$status_t]) || (!empty($easy_translation_manager_plugin->etm_tools_retrive['hide_pages_'.$status_t]) && empty($easy_translation_manager_plugin->etm_tools_retrive['hide_page_'.$status_t.'_all']))){
			$status_tmp2[] = $status_t;
		}
	}



	$args = array('posts_per_page' => 10,   'post_type'=>'page','post_status' =>  $status_tmp2);
    $tmp_data_array = array();
    
    
    if(!empty($_POST['interval'])){
	    $args['posts_per_page'] = $_POST['interval'];
	    
	    if(!empty($_POST['page'])){
		    $args['offset'] = ($_POST['interval']*$_POST['page']);
	    }	    
	    
    }
    
    if(!empty($_POST['post_tag'])){
    	$args['post_type'] = $_POST['post_tag'];
    }
    
    if(!empty($_POST['sort_col'])){
    	$args['orderby'] = $_POST['sort_col'];
    }

    if(!empty($_POST['sort_dir'])){
    	$args['order'] = $_POST['sort_dir'];
    }


    if(!empty($_POST['seachtitle'])){
		$args['s'] = $_POST['seachtitle'];
	}
	
    if(!empty($_POST['post_tag'])){
		$etm_tag = $_POST['post_tag'];
	}
    
	$page_types = $wp_query->query( $args );
	
	
	$total_found = $wp_query->found_posts;
	

	if(!empty($_POST['seachtitle'])){
   		 $sql_tmp2 = "SELECT pm.post_id  FROM {$wpdb->prefix}postmeta pm, {$wpdb->prefix}posts pp WHERE (pm.meta_key LIKE '%ect_tran_content%' or pm.meta_key LIKE '%ect_tran_title%') and pm.meta_value LIKE '%".$_POST['seachtitle']."%' and pm.post_id = pp.ID and pp.post_type = 'page' group by pm.post_id";
    
   		 $sqldata_tmp2 = $wpdb->get_results($sql_tmp2);
    	 if($page_types){
		 	foreach($page_types as $tmp){   
			 	foreach($sqldata_tmp2 as $sqldata_tmp_k => $sqldata_tmp_d){
					if($tmp->ID == $sqldata_tmp_d->post_id){
						unset($sqldata_tmp2[$sqldata_tmp_k]);
					}
			 	}
			}
		}
		
		if(!empty($sqldata_tmp2)){
			$tmp123_array = '';
			foreach($sqldata_tmp2 as $sqldata_tmp_d){$tmp123_array[] = $sqldata_tmp_d->post_id;}
			$page_array2= query_posts(array('post__in'=>$tmp123_array,'posts_per_page' => -1,   'post_type'=>'page'));
		}

		if(!empty($page_array2)){$page_types = array_merge($page_types,$page_array2);}	
	}
	
	
	
	
    $languashed = etm_tools_retrive_languages_data(etm_tools_retrive_aktiv_languages(),true); 
	$current_aktive_lang = etm_tools_retrive_aktiv_languages();
	if($page_types){
		foreach ($page_types as $page_type ) {
    		
    		$sqllangcheckdata = '';
			foreach($current_aktive_lang as $tmp_key_lang => $tmp_lang){
				$tmp_var_check_header = get_post_meta($page_type->ID, 'ect_tran_title_'.$tmp_key_lang, true);   
				$tmp_var_check_body = get_post_meta($page_type->ID, 'ect_tran_content_'.$tmp_key_lang, true); 

				if(!empty($tmp_var_check_header) || !empty($tmp_var_check_body)){
					if(empty($sqllangcheckdata[$tmp_key_lang])){
    					$sqllangcheckdata[$tmp_key_lang] = (object)array();
					}
					$sqllangcheckdata[$tmp_key_lang]->lang_code = $tmp_key_lang;
				}
			}
			if($page_type->post_type == 'page'){
				if(empty($tmp)){
					$tmp = (object)array();
				}
			
				if(empty($tmp->post_name)){
					$tmp->post_name = '';
				}			
	   	 		$user_info = get_userdata( $page_type->post_author );
    			$tmp_data_array[] = array('id' => $page_type->ID,
					'title'=> ($page_type->post_title !='' ? $page_type->post_title : $tmp->post_name) ,
					'auther'=> $user_info->first_name .  ", " . $user_info->last_name,			
					'date'=> $page_type->post_date,
					'status'=> $page_type->post_status,			
					'languages' => etm_tools_create_languages_click_link($languashed,$page_type->ID,$sqllangcheckdata,$etm_tag),
					'tools' => '<input type="submit" value="Custom fields" onclick="loadingContent(\'--ss--\',\'meta_'.$page_type->ID.'\',\'Custom fields\',\'false\',\''.$_POST['sort_col'].'\',\''.$_POST['sort_dir'].'\');return false;" class="button-secondary">',
					'editible' => 'true');  
			} else {
				$total_found -= 1;
			}
		}
	}
	
    return $tmp_data_array;
}

//---------------------------------------------- Meta --------------------------------------------------
//------------------------------------------------------------------------------------------------------

// controles the first selection

function etm_tools_get_meta(){
    global $etm_tag,$wpdb,$meta_id,$total_found;

    if(!empty($_POST['etm_folder'])){
		$etm_folder = $_POST['etm_folder'];
		
		$tmp = explode('_', $etm_folder);
		$meta_id = $tmp[1];
		$etm_tag = $tmp[0];
	}
	
    if(!empty($_POST['post_tag'])){
		$etm_tag = $_POST['post_tag'];
	}


	$tmp_data_array = array();
    $languashed = etm_tools_retrive_languages_data(etm_tools_retrive_aktiv_languages(),true); 

	$current_aktive_lang = etm_tools_retrive_aktiv_languages();
    $sql_tmp = "SELECT * FROM {$wpdb->prefix}postmeta WHERE post_id='".$meta_id."' and meta_key != '_edit_lock' and meta_key != '_edit_last'  and meta_key NOT LIKE '%_yoast_%' and SUBSTRING(meta_key, -3, 1) != '_' and SUBSTRING(meta_key, 0, 4) != 'ect_' and SUBSTRING(meta_key, 0, 4) != 'etm_' ";
    

    
    
    if(!empty($_POST['sort_col'])){
	    if(in_array($_POST['sort_col'], array('meta_id','post_id','meta_key','meta_value'))){
			$sql_tmp .= ' ORDER BY '. $_POST['sort_col'];
	    } else {
		   $sql_tmp .= ' ORDER BY '.  'meta_id'; 
	    }
    	
    }

    if(!empty($_POST['sort_dir'])){
    	if($_POST['sort_dir'] =='decs'){
	    	$sql_tmp .= ' desc'; 	
    	} else {
			$sql_tmp .= ' ' . $_POST['sort_dir'];   	
    	}

    }
    
    
     if(!empty($_POST['interval'])){
	    if(!empty($_POST['page'])){
		    $sql_tmp .= ' LIMIT ' .($_POST['interval']*$_POST['page']).',';
	    } else {
		    $sql_tmp .= ' LIMIT ' .($_POST['interval']*$_POST['page']).',';
	    }	 
	    
	    $sql_tmp .= ' ' . $_POST['interval']; 
    }
    

   	$page_types = $wpdb->get_results($sql_tmp);
   	
   	$total_found = $wpdb->get_var("SELECT COUNT(meta_id) FROM {$wpdb->prefix}postmeta WHERE post_id='".$meta_id."' and meta_key != '_edit_lock' and meta_key != '_edit_last'  and meta_key NOT LIKE '%_yoast_%' and SUBSTRING(meta_key, -3, 1) != '_' and SUBSTRING(meta_key, 0, 4) != 'ect_' and SUBSTRING(meta_key, 0, 4) != 'etm_' "); 	
   	
   	
   	

	if($page_types){
	   foreach ($page_types as $page_type ) {


	       $sqllangcheckdata = '';
	
			foreach($current_aktive_lang as $tmp_key_lang => $tmp_lang){
				$tmp_var_check = get_post_meta($page_type->post_id, $page_type->meta_key.'_'.$tmp_key_lang, true);   

				if(!empty($tmp_var_check)){
				
					if(empty($sqllangcheckdata[$tmp_key_lang])){
						$sqllangcheckdata[$tmp_key_lang] = (object) array();
					}
				
					$sqllangcheckdata[$tmp_key_lang]->lang_code = $tmp_key_lang;
				}
			}

                if(strlen($page_type->meta_value) > 100)
        		{
            		$page_type->meta_value = substr($page_type->meta_value, 0,100) . '...'; 
        		}
				$tmp_data_array[] = array('id' => $page_type->post_id,
					'meta_id'=> $page_type->meta_id,
					'post_id'=> $page_type->post_id,			
					'meta_key'=> $page_type->meta_key,
					'meta_value'=> strip_tags($page_type->meta_value),			
					'languages' => etm_tools_create_languages_click_link($languashed,$page_type->meta_id,$sqllangcheckdata,'meta'),
					'tools' => '',
					'editible' => 'true');  
		}
	}
	
    return $tmp_data_array;
}



//---------------------------------------------- Post --------------------------------------------------
//------------------------------------------------------------------------------------------------------

// controles the first selection
function etm_tools_get_posts_types(){
    // echo '=== etm_tools_get_posts_types ===';
    global $etm_tag,$wpdb,$easy_translation_manager_plugin,$total_found;

    $tmp_data_array = array();
	$post_types=get_post_types(); 
	
    $languashed = etm_tools_retrive_languages_data(etm_tools_retrive_aktiv_languages(),true); 

	$intervalt = 10;
	$page = 0;

	if(!empty($_POST['interval'])){
	    $intervalt = $_POST['interval']; 
    }
    
    if(!empty($_POST['page'])){
	    $page = $_POST['page'];
    }	

	if(!empty($post_types['page'])){
		unset($post_types['page']);
	}
	if(!empty($post_types['nav_menu_item'])){
		unset($post_types['nav_menu_item']);
	}
	if(!empty($post_types['revision'])){
		unset($post_types['revision']);
	}
	
    $etm_folder = '';
    if(!empty($_POST['etm_folder'])){
		$etm_folder = $_POST['etm_folder'];
	}
	$etm_tag = '';
    if(!empty($_POST['post_tag'])){
		$etm_tag = $_POST['post_tag'];
	}	
	
    $sort_dir = false;
    $sort_col = 'title';
    if(!empty($_POST['sort_col'])){
    	$sort_col = $_POST['sort_col'];
    }

    if(!empty($_POST['sort_dir']) and $_POST['sort_dir'] == 'asc'){
    	$sort_dir = true;
    }
	
	$count = 0;
	

	if($post_types){
	
		$total_found = count($post_types);
	
		foreach ($post_types as $key_test => $post_type ) {
			
			if(($page*$intervalt) <= $count and (($page+1)*$intervalt) > $count){
				$count_pages = wp_count_posts($post_type);	
				
				$publish = $count_pages->publish;
				$draft = $count_pages->draft;
				$pending = $count_pages->pending;
				$private = $count_pages->private;
				$other = 0;

				unset($count_pages->publish);
        		unset($count_pages->draft);
        		unset($count_pages->pending);
        		unset($count_pages->private);
				
				foreach($count_pages as $tmp){
					if($tmp > 0){
						$other += $tmp;
					}
				}
				
		
    			$tmp_data_array[] = array('title'=>utf8_decode(strip_tags($post_type)),
    							  'folder'=>$post_type,
                            	  'countPublic'=>$publish,
                        	      'countDraft'=>$draft,
                    	          'countPending'=>$pending,
                	              'countPrivate'=>$private,
            	                  'countOther'=>$other);                            
        	                      //'translationProcent' =>  etm_tools_check_lang($languashed,$etm_tag,$post_type)
        	                      


        	}
        	$count++;
		}
	}
	

    
    
	etm_aasort($tmp_data_array,$sort_col,$sort_dir);
    return $tmp_data_array;
}

// controles the secound selection
function etm_tools_get_post_single($firstime = true){
    global $wpdb,$etm_folder,$etm_tag,$easy_translation_manager_plugin,$total_found,$wp_query;
    $tmp_data_array = array();
    $languashed = etm_tools_retrive_languages_data(etm_tools_retrive_aktiv_languages(),true); 
	$current_aktive_lang = etm_tools_retrive_aktiv_languages();
 	$return_array = array();
 
	$status_tmp = array('publish','pending','draft','private','static','object','attachment','inherit','future');
	$status_tmp2 = array();
	foreach($status_tmp as $key => $status_t){
		if(empty($easy_translation_manager_plugin->etm_tools_retrive['hide_posts_status_'.$status_t]) || empty($easy_translation_manager_plugin->etm_tools_retrive['hide_post_status_'.$status_t.'_all'])){
			$status_tmp2[] = $status_t;
		}
	}
	
	    if(!empty($_POST['etm_folder'])){
		$etm_tag = $_POST['etm_folder'];
	}
	
    if(!empty($_POST['etm_folder'])){
		$etm_folder = $_POST['etm_folder'];
	}
 
	$args = array('posts_per_page' => 9999999999, 'post_status' => $status_tmp2 ,  'post_type'=>$etm_folder);
	
    if(!empty($_POST['interval'])){
	    $args['posts_per_page'] = $_POST['interval'];
	    
	    if(!empty($_POST['page'])){
		    $args['offset'] = ($_POST['interval']*$_POST['page']);
	    }	    
	    
    }
    
    
    if(!empty($_POST['sort_col'])){
    	$args['orderby'] = $_POST['sort_col'];
    }

    if(!empty($_POST['sort_dir'])){
    	$args['order'] = $_POST['sort_dir'];
    }


    if(!empty($_POST['seachtitle'])){
		$args['s'] = $_POST['seachtitle'];
	}
	
	


	$posts_array = $wp_query->query( $args );
	$total_found = $wp_query->found_posts;
		
	
	if(!empty($_POST['seachtitle'])){
   		 $sql_tmp = "SELECT pm.post_id  FROM {$wpdb->prefix}postmeta pm, {$wpdb->prefix}posts pp WHERE (pm.meta_key LIKE '%ect_tran_content%' or pm.meta_key LIKE '%ect_tran_title%') and pm.meta_value LIKE '%".$_POST['seachtitle']."%' and pm.post_id = pp.ID and pp.post_type = '".$etm_folder."' group by pm.post_id";
    
   		 $sqldata_tmp = $wpdb->get_results($sql_tmp);
    	 if($posts_array){
		 	foreach($posts_array as $tmp){   
			 	foreach($sqldata_tmp as $sqldata_tmp_k => $sqldata_tmp_d){
					if($tmp->ID == $sqldata_tmp_d->post_id){
						unset($sqldata_tmp[$sqldata_tmp_k]);
					}
			 	}
			}
		}
		
		
		if(!empty($sqldata_tmp)){
			$tmp123_array = '';
			foreach($sqldata_tmp as $sqldata_tmp_d){$tmp123_array[] = $sqldata_tmp_d->post_id;}
			$posts_array2= query_posts(array('post__in'=>$tmp123_array));
		}
		
		if(!empty($posts_array2)){$posts_array = array_merge($posts_array,$posts_array2);}		
	}
	
	
	
	if($posts_array){
    	foreach($posts_array as $tmp){                              
		    $sqllangcheckdata = '';	
			foreach($current_aktive_lang as $tmp_key_lang => $tmp_lang){
				$tmp_val = get_post_meta($tmp->ID, 'ect_tran_content_'.$tmp_key_lang, true);
					
				if(!empty($tmp_val)){
					if(empty($sqllangcheckdata[$tmp_key_lang])){
	    				$sqllangcheckdata[$tmp_key_lang] = (object)array();
    				}
					$sqllangcheckdata[$tmp_key_lang]->lang_code = $tmp_key_lang;
				}
			}


			if($tmp->post_type == $etm_folder){
				$user_info = get_userdata( $tmp->post_author );
				$return_array[] = array('id' => $tmp->ID,
					'title'=> ($tmp->post_title !='' ? $tmp->post_title : $tmp->post_name) ,
					'auther'=> $user_info->first_name .  ", " . $user_info->last_name,			
					'date'=> $tmp->post_date,			
					'status'=> $tmp->post_status,	
					'languages' => etm_tools_create_languages_click_link($languashed,$tmp->ID,$sqllangcheckdata,'post'),
					'tools' => '<input type="submit" value="Custom fields" onclick="loadingContent(\'--ss--\',\'meta_'.$tmp->ID.'_'.$etm_folder.'\',\'Custom fields\',\'false\',\'title\',\'decs\');return false;" class="button-secondary">',
					'editible' => 'true'
				); 
			} else {
				$total_found--;
			}
    	}
    }
    return $return_array;
}

// controles the secound selection
function etm_tools_get_post_tags(){
    global $wpdb,$etm_folder,$etm_tag,$total_found;
	$current_aktive_lang = etm_tools_retrive_aktiv_languages();




	
    if(!empty($_POST['etm_folder'])){
		$etm_folder = $_POST['etm_folder'];
	}
	
    if(!empty($_POST['post_tag'])){
		$etm_tag = $_POST['post_tag'];
	}


	$intervalt = 10;
	$page = 0;
	$count = 0;

	if(!empty($_POST['interval'])){
	    $intervalt = $_POST['interval']; 
    }
    
    if(!empty($_POST['page'])){
	    $page = $_POST['page'];
    }	


    $sort_dir = false;
    $sort_col = 'title';
    if(!empty($_POST['sort_col'])){
    	$sort_col = $_POST['sort_col'];
    }
    
    if($sort_col == 'ID' or $sort_col == 'id'){
	    $sort_col ='term_id';
    }

    

    if(!empty($_POST['sort_dir']) and $_POST['sort_dir'] == 'asc'){
    	$sort_dir = true;
    }
    


	$return_array = array();
    $tmp_data_array = array();
    $languashed = etm_tools_retrive_languages_data(etm_tools_retrive_aktiv_languages(),true); 
    
    if($etm_folder == 'post_tag'){
    	$posttags =  get_tags(array('hide_empty'=>'0'));

    } else if($etm_folder == 'category'){
        $taxonomies=get_taxonomies('','names');
        $posttags = array();
    
        foreach ($taxonomies as $taxonomy ) {
            if($taxonomy != 'post_tag' && !empty($taxonomy)){  
        	    $posttags_temp =  get_categories(array('hide_empty' => 0,'taxonomy' => $taxonomy));
                if(is_array($posttags_temp) && !empty($posttags_temp)){
                    $posttags = array_merge($posttags, $posttags_temp);
                }
            }  
        } 
    }
	if ($posttags) {

         $posttags_only_one = array();
	     foreach($posttags as $tmp_change){
	       $posttags_only_one[$tmp_change->term_id] = (array)$tmp_change;
	     }
        unset($posttags);
         
		etm_aasort($posttags_only_one,$sort_col,$sort_dir);
        
    	foreach($posttags_only_one as $tmp){
    		$sqllangcheckdata = array();
    	
    		foreach($current_aktive_lang as $tmp_key_lang => $tmp_lang){
    			$getval = get_option('ect_tran_terms_'.$tmp_key_lang,true); 

    			if(!empty($getval[$tmp['term_id']])){
    				if(empty($sqllangcheckdata[$tmp_key_lang])){
	    				$sqllangcheckdata[$tmp_key_lang] = (object)array();
    				}
    				
    			
					$sqllangcheckdata[$tmp_key_lang]->lang_code = $tmp_key_lang;
    			}
    		}
    		
   			
			if(($page*$intervalt) <= $count and (($page+1)*$intervalt) > $count){
				$return_array[] = array('id' => $tmp['term_id'],
					'name'=> $tmp['name'],
					'slug'=> $tmp['slug'],			
					'count'=> $tmp['count'],			
					'description'=> $tmp['description'],	
					'languages' => etm_tools_create_languages_click_link($languashed,$tmp['term_id'],$sqllangcheckdata,$etm_folder,$tmp['taxonomy']),
					'tools' => '',
					'editible' => 'true'
				);  
			}
			$count++;
    	}
	}
	
	$total_found = count($posttags_only_one);
	
    return $return_array;
}


//---------------------------------------------- Menu --------------------------------------------------
//------------------------------------------------------------------------------------------------------


// controles the first selection
function etm_tools_get_menu_group (){
    global $etm_tag,$wpdb,$easy_translation_manager_plugin,$total_found;
    
    if(!empty($_POST['etm_folder'])){
		$etm_folder = $_POST['etm_folder'];
	}
	
    if(!empty($_POST['post_tag'])){
		$etm_tag = $_POST['post_tag'];
	}


	$intervalt = 10;
	$page = 0;
	$count = 0;

	if(!empty($_POST['interval'])){
	    $intervalt = $_POST['interval']; 
    }
    
    if(!empty($_POST['page'])){
	    $page = $_POST['page'];
    }	

    $sort_dir = false;
    $sort_col = 'term_id';
    if(!empty($_POST['sort_col'])){
    	$sort_col = $_POST['sort_col'];
    }
    
    if($sort_col == 'id' or $sort_col == 'ID' ){
	    $sort_col = 'term_id';
    }
 
    if($sort_col == 'title'){
	    $sort_col = 'name';
    }
 

    if(!empty($_POST['sort_dir']) and $_POST['sort_dir'] == 'asc'){
    	$sort_dir = true;
    }
    
    
    $return_array = array();
    $menu_name = wp_get_nav_menus();
    $languashed = etm_tools_retrive_languages_data(etm_tools_retrive_aktiv_languages(),true); 
    if(empty($easy_translation_manager_plugin->etm_tools_retrive['hide_menus']) || empty($easy_translation_manager_plugin->etm_tools_retrive['hide_menu_all'])){
		if($menu_name){
		
			$total_found = count($menu_name);
			
			
			etm_aasort_object($menu_name,$sort_col,$sort_dir);
    		foreach($menu_name as $tmp){
    			if(($page*$intervalt) <= $count and (($page+1)*$intervalt) > $count){
					if(empty($easy_translation_manager_plugin->etm_tools_retrive['hide_menus']) || empty($easy_translation_manager_plugin->etm_tools_retrive['hide_menu_'.$tmp->term_id])){
						$return_array[] = array('id' => $tmp->term_id,
						'folder'=> $tmp->slug,
						'title'=> $tmp->name,
						'count'=> $tmp->count,
						'translationProcent' =>  etm_tools_check_lang($languashed,$etm_tag,$tmp->slug),  		
						'editible' => 'true'
						);   
					}
				}
				$count++;
    		}
    	}
    }

    return $return_array;	

}

// controles the secound selection
function etm_tools_get_menu_single (){
    global $etm_tag,$etm_folder,$wpdb,$total_found;
    
	
    if(!empty($_POST['etm_folder'])){
		$etm_folder = $_POST['etm_folder'];
	}
	
    if(!empty($_POST['post_tag'])){
		$etm_tag = $_POST['post_tag'];
	}


	$intervalt = 10;
	$page = 0;
	$count = 0;

	if(!empty($_POST['interval'])){
	    $intervalt = $_POST['interval']; 
    }
    
    if(!empty($_POST['page'])){
	    $page = $_POST['page'];
    }	

    $sort_dir = false;
    $sort_col = 'title';
    if(!empty($_POST['sort_col'])){
    	$sort_col = $_POST['sort_col'];
    }

    if(!empty($_POST['sort_dir']) and $_POST['sort_dir'] == 'asc'){
    	$sort_dir = true;
    }

    
    
    
    

    $tmp_data_array = array();
	$menu_name= wp_get_nav_menu_items($etm_folder);
    $languashed = etm_tools_retrive_languages_data(etm_tools_retrive_aktiv_languages(),true);
   	$current_aktive_lang = etm_tools_retrive_aktiv_languages();  
    $return_array = array(); 
     
     
	if($menu_name){
	
		etm_aasort_object($menu_name,$sort_col,$sort_dir);
	
		$total_found = count($menu_name);
	
    	foreach($menu_name as $tmp){
    		if(($page*$intervalt) <= $count and (($page+1)*$intervalt) > $count){
	    		$sqllangcheckdata = '';
	    		$seach_true = false;
	    		foreach($current_aktive_lang as $tmp_key_lang => $tmp_lang){
	    			$tmp_val = get_option('ect_tran_menu_'.$tmp_key_lang);	
	    			
	    			if(!empty($tmp_val[$tmp->ID])){
	    				if(empty($sqllangcheckdata[$tmp_key_lang])){
		    				$sqllangcheckdata[$tmp_key_lang] = (object)array();
	    				}
	    			
						$sqllangcheckdata[$tmp_key_lang]->lang_code = $tmp_key_lang;
						if(!empty($_POST['seachtitle'])){
							if ((!empty($tmp_val[$tmp->ID]->title) and strlen(strstr(strtolower($tmp_val[$tmp->ID]->title),strtolower($_POST['seachtitle'])))>0) or 
							(!empty($tmp_val[$tmp->ID]->attr_title) and strlen(strstr(strtolower($tmp_val[$tmp->ID]->attr_title),strtolower($_POST['seachtitle'])))>0) or 
							(!empty($tmp_val[$tmp->ID]->url) and strlen(strstr(strtolower($tmp_val[$tmp->ID]->url),strtolower($_POST['seachtitle'])))>0) or 
							(!empty($tmp_val[$tmp->ID]->description) and strlen(strstr(strtolower($tmp_val[$tmp->ID]->description),strtolower($_POST['seachtitle'])))>0)) {
								$seach_true = true;
							}
						}
	    			}
	    		}	
	    		
	    	
	            if(strlen($tmp->description) > 100)
	            {
	                $strings = substr($tmp->description, 0,100) . '...'; 
	            } else {
	                $strings = $tmp->description;
	            }        	
	            
	    		if(!empty($_POST['seachtitle'])){
					if ((!empty($tmp->title) and strlen(strstr(strtolower($tmp->title),strtolower($_POST['seachtitle'])))>0) or 
					(!empty($tmp->description) and strlen(strstr(strtolower($tmp->description),strtolower($_POST['seachtitle'])))>0)) {
						$seach_true = true;
					}
				}
	            
	            
	    	
	            if((!empty($_POST['seachtitle']) and $seach_true) or empty($_POST['seachtitle'])){
					$return_array[] = array('default_string'=>$strings,
						'id' => $tmp->ID,
						'title'=> $tmp->title,
						'desc'=> $strings,
						'languages' => etm_tools_create_languages_click_link($languashed,$tmp->ID.'_'.$tmp->object_id.'_'.$etm_folder,$sqllangcheckdata,$etm_tag),	
						'editible' => 'true'
					);   
				}
			}
    	}
    	$count++;
    }
    return $return_array;

}


//---------------------------------------------- Site -------------------------------------------------
//------------------------------------------------------------------------------------------------------

// controles the first selection
function etm_tools_get_site_options(){
    global $etm_tag,$wpdb,$easy_translation_manager_plugin,$total_found,$wp_query;
	$status_tmp = array('admin_email','blogname','blogdescription','date_format','time_format','start_of_week');
    if(!empty($_POST['etm_folder'])){
		$etm_folder = $_POST['etm_folder'];
	}
	
    if(!empty($_POST['post_tag'])){
		$etm_tag = $_POST['post_tag'];
	}

	$intervalt = 10;
	$page = 0;
	$count = 0;
    $tmp_data_array = array();
	
	$sql_tmp = "SELECT * FROM  {$wpdb->prefix}options WHERE option_name in ('".implode("','", $status_tmp)."')";

	if(!empty($_POST['sort_col']) && $_POST['sort_col'] == 'id'){
		$_POST['sort_col'] = 'option_id';
	} else if(!empty($_POST['sort_col']) && $_POST['sort_col'] == 'title'){
		$_POST['sort_col'] = 'option_name';
	} else if(!empty($_POST['sort_col']) && $_POST['sort_col'] == 'auther'){
		$_POST['sort_col'] = 'option_value';
	} else {
		$_POST['sort_col'] = 'option_name';
	}



    if(!empty($_POST['sort_col'])){
    	$sql_tmp .= ' ORDER BY '. $_POST['sort_col'];
    }
    
    if(!empty($_POST['sort_dir'])){
    	 if($_POST['sort_dir'] == 'decs'){
    	 	$_POST['sort_dir'] = 'desc';
    	 }

    	$sql_tmp .= ' ' . $_POST['sort_dir'];
    }
   
    if(!empty($_POST['interval'])){
	    if(!empty($_POST['page'])){
		    $sql_tmp .= ' LIMIT ' .($_POST['interval']*$_POST['page']).',';
	    } else {
		    $sql_tmp .= ' LIMIT ' .($_POST['interval']*0).',';
	    }	 
	    
	    $sql_tmp .= ' ' . $_POST['interval'];   
    }
    

    
    $sqldata_tmp = $wpdb->get_results($sql_tmp);
    $total_found = count($status_tmp);

    $languashed = etm_tools_retrive_languages_data(etm_tools_retrive_aktiv_languages(),true); 
	$current_aktive_lang = etm_tools_retrive_aktiv_languages();
	if($sqldata_tmp){
		foreach ($sqldata_tmp as $id_seach) {
    	
    		$site_type_lang = array();
    		
    		$sqllangcheckdata = '';
			foreach($current_aktive_lang as $tmp_key_lang => $tmp_lang){
				$tmp_var_get = get_option('etm_'.$id_seach->option_name.'_'.$tmp_key_lang,'');   
				
				if(!empty($tmp_var_get)){
					if(empty($site_type_lang[$tmp_key_lang])){
    					$site_type_lang[$tmp_key_lang] = (object)array();
					}
					$site_type_lang[$tmp_key_lang]->lang_code = $tmp_key_lang;
				}
			}		
			

			$tmp_data_array[] = array('id' => $id_seach->option_id,
				'title'=> $id_seach->option_name,
				'auther' => $id_seach->option_value,	
				'languages' => etm_tools_create_languages_click_link($languashed,$id_seach->option_id,$site_type_lang,$etm_tag),
				'editible' => 'true');  
			
		}
	}
	
    return $tmp_data_array;
}



//--------------------------------------- special functions --------------------------------------------
//------------------------------------------------------------------------------------------------------

// scan dir/files for __ or _e tags
function etm_tools_recurseDir($dir,$removedata = false) {
	if($removedata){
	
		global $wpdb,$etm_folder,$etm_tag;

    	$sql_tmp = "SELECT lang_index_id FROM  {$wpdb->prefix}etm_plugin_string WHERE 1 group by lang_index_id";
    	$sqldata_tmp = $wpdb->get_results($sql_tmp);
    	
    	$tmp_string = '';
    	
    	if(!empty($sqldata_tmp)){
    		foreach($sqldata_tmp as $tmpdd){
    			if(!empty($tmp_string)){
    				$tmp_string .= ',';
    			}	
    			$tmp_string .= $tmpdd->lang_index_id;
    		}
    		
    		$tmp_string = " and id NOT IN (".$tmp_string.")";
    	}
    	$sql_tmp2 = "DELETE FROM {$wpdb->prefix}etm_plugin_index WHERE manual_added='0' and category_type ='".$etm_tag."' and folder_name ='".$etm_folder."' ".$tmp_string;
    	$sqldata_tmp = $wpdb->query($sql_tmp2);
    	
	}
    	

	if(is_dir($dir)) {

		if($dh = opendir($dir)){
			while($file = readdir($dh)){
				if($file != '.' && $file != '..' && $file != '.svn'){
					if(is_dir($dir . $file)){
						etm_tools_recurseDir($dir . $file . '/');
					}else{
					   
                       $type = explode('.',$file) ;
                       $type = $type[(count($type)-1)];
                       if($type == 'php'){
                            etm_tools_save_to_array($dir,$file);
                       }	 
			 		}
				}
	 		}
		}
 		closedir($dh);         
     	}
}

// save scanede files into sql
// save scanede files into sql
function  etm_tools_save_to_array($dir,$file) {
    global $etm_folder,$wpdb,$etm_tag,$userdata;
    $fh = file_get_contents(($dir.$file));
    $matches_all = '';
	$array_two_step = array('translate','__','_e','esc_attr__','esc_attr_e','esc_html__','esc_html_e');
    $array_tree_step = array('_x','_ex','esc_attr_x','esc_html_x','_n_noop');
	$array_four_step = array('_n','_nx_noop');
	$array_five_step = array('_nx');
    
    $ss_s = '(    |   |  | |)';
    $ss_ms = '(\"|\'|\$|)';
    $ss_me = '(\"\,|\'\,|\" \,|\' \,|\"  \,|\'  \,|\"   \,|\'   \,)';
    $ss_mee = '(\"\)|\'\)|\" \)|\' \)|\"  \)|\'  \)|\"   \)|\'   \)|\)| \)|  \)|   \))';
    $ss_c = '(.*?)';
    
    
   
    preg_match_all("/(translate|__|_e|_n|_x|_ex|_nx|esc_attr__|esc_attr_e|esc_attr_x|esc_html__|esc_html_e|esc_html_x|_n_noop|_nx_noop)\($ss_c\)$ss_s(\;|\,|\.|\:|\+|\r|\n|\r\n)/", $fh, $matches_all, PREG_SET_ORDER);
     
    if(!empty($matches_all)){
    	$temp_file = explode($etm_folder.'/',($dir.$file),2);
	    foreach($matches_all as $matche_n){
	    	$matches_single = '';
	    	$default_string = '';
	    	$default_string2 = '';
	    	$default_placeholder = '';
	    	$mo_tag = '';
	    	if(!empty($matche_n[1])&& !empty($matche_n[2])){
	    		$matche_n[2] = '('.$matche_n[2].')';
	    	
    	
	    	
		    	if( in_array($matche_n[1],$array_two_step)){
					preg_match_all("/\($ss_s$ss_ms(.*?)$ss_me$ss_s$ss_ms(.*?)$ss_mee/", $matche_n[2], $matches_single, PREG_SET_ORDER);	

					if(!empty($matches_single)){
						$matches_single = $matches_single[0];
					}

					if(!empty($matches_single['7']) && $matches_single['6'] != '$'){
						$mo_tag = $matches_single['7'];
					} else {
						$mo_tag = 'Variable textdomain';
					}
					
					if(!empty($matches_single['3']) && $matches_single['2'] != '$'){
						$default_string = $matches_single['3'];
					}
					
		    	} else if(in_array($matche_n[1],$array_tree_step)){
					preg_match_all("/\($ss_s$ss_ms(.*?)$ss_me$ss_s$ss_ms(.*?)$ss_me$ss_s$ss_ms(.*?)$ss_mee/", $matche_n[2], $matches_single, PREG_SET_ORDER);	

					if(!empty($matches_single)){
						$matches_single = $matches_single[0];
					}
					


					if(!empty($matches_single['11']) && $matches_single['10'] != '$'){
						$mo_tag = $matches_single['11'];
					} else {
						$mo_tag = 'Variable textdomain';
					}
					
					if(!empty($matches_single['3']) && $matches_single['2'] != '$'){
						$default_string = $matches_single['3'];
					}	
					
					if(!empty($matches_single['7']) && $matches_single['6'] != '$'){
						if($matche_n[1] == '_n_noop'){
							$default_string2 = $matches_single['7'];
						} else {
							$default_placeholder = $matches_single['7'];
						}
					}	
		    	} else if(in_array($matche_n[1],$array_four_step)){
					preg_match_all("/\($ss_s$ss_ms(.*?)$ss_me$ss_s$ss_ms(.*?)$ss_me$ss_s$ss_ms(.*?)$ss_me$ss_s$ss_ms(.*?)$ss_mee/", $matche_n[2], $matches_single, PREG_SET_ORDER);	

					if(!empty($matches_single)){
						$matches_single = $matches_single[0];
					}

					if(!empty($matches_single['15']) && $matches_single['14'] != '$'){
						$mo_tag = $matches_single['15'];
					} else {
						$mo_tag = 'Variable textdomain';
					}
					
					if(!empty($matches_single['3']) && $matches_single['2'] != '$'){
						$default_string = $matches_single['3'];
					}	
					
					if(!empty($matches_single['7']) && $matches_single['6'] != '$'){
						$default_string2 = $matches_single['7'];
					}
					
					if(!empty($matches_single['11']) && $matches_single['10'] != '$' && $matche_n[1] == '_nx_noop'){
						$default_placeholder = $matches_single['11'];
					}		    	
		    	}else if(in_array($matche_n[1],$array_five_step)){
					preg_match_all("/\($ss_s$ss_ms(.*?)$ss_me$ss_s$ss_ms(.*?)$ss_me$ss_s$ss_ms(.*?)$ss_me$ss_s$ss_ms(.*?)$ss_me$ss_s$ss_ms(.*?)$ss_mee/", $matche_n[2], $matches_single, PREG_SET_ORDER);	

					if(!empty($matches_single)){
						$matches_single = $matches_single[0];
					}

					if(!empty($matches_single['19']) && $matches_single['18'] != '$'){
						$mo_tag = $matches_single['19'];
					} else {
						$mo_tag = 'Variable textdomain';
					}
					
					if(!empty($matches_single['3']) && $matches_single['2'] != '$'){
						$default_string = $matches_single['3'];
					}	
					
					if(!empty($matches_single['7']) && $matches_single['6'] != '$'){
						$default_string2 = $matches_single['7'];
					}
					
					if(!empty($matches_single['15']) && $matches_single['14'] != '$'){
						$default_placeholder = $matches_single['15'];
					}		    	
		    	}	
		    	if(!empty($default_string)){
		    	
		            $user_count = $wpdb->get_row("SELECT id,mo_tag,folder_name,default_string2,default_placeholder FROM {$wpdb->prefix}etm_plugin_index WHERE default_string='".$default_string."' and BINARY default_string='".$default_string."' and default_string2='".$default_string2."' and BINARY default_string2='".$default_string2."' and default_placeholder='".$default_placeholder."' and BINARY default_placeholder='".$default_placeholder."' and mo_tag='".$mo_tag."' and category_type ='".$etm_tag."' and folder_name='".$etm_folder."' LIMIT 1");
		            
		            if(empty($user_count)){
		            	
		                $sql = "INSERT INTO {$wpdb->prefix}etm_plugin_index (default_string,default_string2,default_placeholder,folder_name,mo_tag,category_type,file,manual_added,create_user,create_ip) 
		                	  VALUES ('".$default_string."','".$default_string2."','".$default_placeholder."','".$etm_folder."','".$mo_tag."','".$etm_tag."','".$etm_folder.'/'.$temp_file[1]."','0',".$userdata->ID.",'".$_SERVER['REMOTE_ADDR']."')";
		                	  
		                $wpdb->query($sql);             
		            } else if((empty($user_count->mo_tag) or $user_count->mo_tag == 'Variable textdomain') and !empty($user_count->id)){
		                $sql = "UPDATE {$wpdb->prefix}etm_plugin_index SET mo_tag='".$mo_tag."' WHERE id='".$user_count->id."'";
		                $wpdb->query($sql); 
		            } else if(((empty($user_count->default_string2) && !empty($default_string2)) or (empty($user_count->default_placeholder) && !empty($default_placeholder))) and !empty($user_count->id)){
			            $sql = "UPDATE {$wpdb->prefix}etm_plugin_index SET default_placeholder='".$default_placeholder."',default_string2='".$default_string2."'  WHERE id='".$user_count->id."'";
			            $wpdb->query($sql); 
		            }
		    	}
	    	}
	    }
    }

    
    
    
   
    
    
    //preg_match_all('/_n\((( |)(\"|\'|)(.*?)(\"|\'|)( |))\,(( |)(\"|\'|)(.*?)(\"|\'|)( |))\,(( |)(\"|\'|)(.*?)(\"|\'|)( |))\,(( |)(\"|\'|)(.*?)(\"|\'|)( |))\)/', $fh, $matches_n, PREG_SET_ORDER);
    
    /*foreach($matches_n as $matche_n){   
        if(!empty($matche_n)){
            $string_m = array();
            if(!empty($matche_n[4]) and !empty($matche_n[3]) and !empty($matche_n[5])){
            	$string_m[] = $matche_n[4];
            }
            if(!empty($matche_n[10]) and !empty($matche_n[9]) and !empty($matche_n[11])){
            	$string_m[] = $matche_n[10];
            }            
            
            $postion = '';
            
            if(!empty($matche_n[22]) and !empty($matche_n[21]) and !empty($matche_n[23])){
            	$postion = $matche_n[22];
            } else if(!empty($matche_n[22])){
	            $postion = 'Variable textdomain';
            }
            $temp_file = explode($etm_folder.'/',($dir.$file),2);
            
            if(!empty($string_m) && count($string_m)==2){
		            $user_count = $wpdb->get_var("SELECT id,mo_tag,folder_name FROM {$wpdb->prefix}etm_plugin_index WHERE default_string='".$string_m[0]."' and BINARY default_string='".$string_m[0]."' and default_string2='".$string_m[1]."' and BINARY default_string2='".$string_m[1]."' and mo_tag='".$postion."' and category_type ='".$etm_tag."' and folder_name='".$etm_folder."' LIMIT 1");
		            if(empty($user_count)){
		            	
		                $sql = "INSERT INTO {$wpdb->prefix}etm_plugin_index (default_string,default_string2,folder_name,mo_tag,category_type,file,manual_added,create_user,create_ip) 
		                	  VALUES ('".$string_m[0]."','".$string_m[1]."','".$etm_folder."','".$postion."','".$etm_tag."','".$etm_folder.'/'.$temp_file[1]."','0',".$userdata->ID.",'".$_SERVER['REMOTE_ADDR']."')";
		                	  
		                $wpdb->query($sql);             
		            } else if((empty($user_count[0]->mo_tag) or $user_count[0]->mo_tag == 'Variable textdomain') and !empty($user_count[0]->id)){
		                $sql = "UPDATE {$wpdb->prefix}etm_plugin_index SET mo_tag='".$postion."' WHERE id='".$user_count[0]->id."'";
		                $wpdb->query($sql); 
		            }
            }
        }  
    }*/ 
    
    //preg_match_all('/(\_e|\_\_|translate|esc_attr_e)\(( |)(\"|\')(.*?)(\"|\')( |)(,( |)(\"|\'|)(.*?)(\"|\'|)( |)|( |))\)/', $fh, $matches_other, PREG_SET_ORDER);

    /*foreach($matches_other as $matche){   
        if(!empty($matche)){
            $string = '';
            if(!empty($matche[4])){
            	$string = $matche[4];
            }
            $postion = '';
            
            if(!empty($matche[10]) and !empty($matche[9]) and !empty($matche[11])){
            	$postion = $matche[10];
            } else if(!empty($matche[10])){
	            $postion = 'Variable textdomain';
            }
            $temp_file = explode($etm_folder.'/',($dir.$file),2);
            
            
            $user_count = $wpdb->get_var("SELECT id,mo_tag,folder_name FROM {$wpdb->prefix}etm_plugin_index WHERE default_string='".$string."' and BINARY default_string='".$string."' and mo_tag='".$postion."' and category_type ='".$etm_tag."' and folder_name='".$etm_folder."' LIMIT 1");
            
            if(empty($user_count)){
            	
                $sql = "INSERT INTO {$wpdb->prefix}etm_plugin_index (default_string,folder_name,mo_tag,category_type,file,manual_added,create_user,create_ip) 
                	  VALUES ('".$string."','".$etm_folder."','".$postion."','".$etm_tag."','".$etm_folder.'/'.$temp_file[1]."','0',".$userdata->ID.",'".$_SERVER['REMOTE_ADDR']."')";
                	  
                $wpdb->query($sql);             
            } else if((empty($user_count[0]->mo_tag) or $user_count[0]->mo_tag == 'Variable textdomain') and !empty($user_count[0]->id)){
                $sql = "UPDATE {$wpdb->prefix}etm_plugin_index SET mo_tag='".$postion."' WHERE id='".$user_count[0]->id."'";
                $wpdb->query($sql); 
            }
        }  
    }*/  
}





// Create string for languashed field
function etm_tools_check_lang($languashed,$category_type,$folder){
    global $wpdb;
	$langed_string = '';
	$current_aktive_lang = etm_tools_retrive_aktiv_languages();
	if($languashed){
		$flag_array = array();
		if($category_type == 'plugin' or $category_type ==  'theme'  or $category_type ==  'addon'){
			$sql_flag_tag = " SELECT tl.lang_code , count(tl.lang_code) as lang_count FROM  {$wpdb->prefix}etm_plugin_index as ti, {$wpdb->prefix}etm_plugin_string as tl WHERE ti.mo_tag != '' and ti.deleted = 0 and ti.category_type ='".$category_type."' and ti.folder_name = '".$folder."' and tl.lang_index_id = ti.id and tl.translatede_string != '' group by lang_code";
        
			
			$sqldata_flag_tag = $wpdb->get_results($sql_flag_tag);

			foreach($sqldata_flag_tag as $tmp){
				$flag_array[$tmp->lang_code] = $tmp->lang_count;
			}
		
			$sqltotal_count = "SELECT count(id) `total_count`  ,count(NULLIF(mo_tag, '')) `active_count` FROM  {$wpdb->prefix}etm_plugin_index WHERE deleted = 0 and category_type ='".$category_type."' and folder_name ='".$folder."'";
			$sqldatatotal_count = $wpdb->get_results($sqltotal_count);
			$total_count = $sqldatatotal_count[0]->active_count;
        } else if($category_type == 'post'){
			$args = array('posts_per_page' => 9999999999, 'post_status' => array('publish','pending','draft','private','static','object','attachment','inherit','future') ,  'post_type'=>$folder);
			$posts_array= query_posts($args);
			if($posts_array){
				$total_count = count($posts_array);
				$tmparray = '';
    			foreach($posts_array as $tmp){
    				foreach($current_aktive_lang as $lang_key => $tmp_lang){
    					$tmp_val = get_post_meta($tmp->ID, 'ect_tran_content_'.$lang_key, true);
    						
    					if(!empty($tmp_val[$tmp->ID]) and !empty($flag_array[$lang_key])){
							$flag_array[$lang_key] += 1;
    					} else if(!empty($tmp_val[$tmp->ID]) and empty($flag_array[$lang_key])){
	    					$flag_array[$lang_key] = 1;
    					}
    				}
    			}
        	}		
        } else if($category_type == 'menu'){
			$menu_name= wp_get_nav_menu_items($folder);
			if($menu_name){
				$total_count = count($menu_name);
				$tmparray = '';
    			foreach($menu_name as $tmp){
   					foreach($current_aktive_lang as $lang_key => $tmp_lang){
    					$tmp_val = get_option('ect_tran_menu_'.$lang_key);
    						
    					if(!empty($tmp_val[$tmp->ID]) and !empty($flag_array[$lang_key])){
							$flag_array[$lang_key] += 1;
    					} else if(!empty($tmp_val[$tmp->ID]) and empty($flag_array[$lang_key])){
	    					$flag_array[$lang_key] = 1;
    					}
    				}	
    			}
        	}	
        }
        
        
		if(!empty($flag_array) and !empty($total_count) and $total_count>0)
		{
			$langed_string = etm_tools_check_lang_createstring($total_count,$languashed,$flag_array);
		}
	}

	if(empty($langed_string))
		$langed_string = '<span style="color:#cccccc">none</span>';

	return $langed_string;
}

function etm_tools_check_lang_createstring($total_amount,$languashed,$flag_array){
	$string_retun = '';
	foreach($languashed as $langedtemp){
		if($total_amount>0){
        	$string_retun .= '<div style="float:left"><img class="icon_lang_'.$langedtemp['code'].'" style="float:left;margin-bottom:-4px;cursor: pointer;height:16px;" title="'.$langedtemp['org_name'] . ' ('. $langedtemp['english_name'] . ')" src="'.etm_tools_create_icons_url($langedtemp['icon']).'" ><span style="   float: left;padding-left: 2px;width: 35px;">';
			if(!empty($flag_array[$langedtemp['code']]) && $flag_array[$langedtemp['code']]>0){	
				if(!empty($total_amount) && $total_amount>0 ){
					$string_retun .= round(($flag_array[$langedtemp['code']]/$total_amount)*100).'%';
				} else {
					$string_retun .= '100%';
				}
			} else {
				$string_retun .= '0%';
			}
			$string_retun .= '</span></div>';
		}
	}
	return $string_retun;
}


// create string to selcect types 
function etm_tools_create_languages_click_link($lang,$id,$checkData,$type,$taxonomy = ''){
	$return_string = '';
	
	if(!empty($lang)){
    foreach($lang as $langtmp)
    {
    	$return_string .= '<img class="icon_lang_'.$id.'_'.$langtmp['code'].'" style="cursor: pointer;padding-right:7px;height:16px;';
		$not_translatede = true;
		
		
		
		if(!empty($checkData)){
       		foreach($checkData as $checkDataTmp)
        	{
        		if($checkDataTmp->lang_code == $langtmp['code']){
            		$not_translatede = false;
                	break;
            	}
        	}     
   		}	 
        if($not_translatede){
        	$return_string .= 'opacity:0.5;filter:alpha(opacity=50);';
   		}    
        	$return_string .= '" onclick="showPopOpControl(\''.$id.'\',\''.$langtmp['code'].'\',\''.$type.'\',\''.$taxonomy.'\')" title="'.$langtmp['org_name'] . ' ('. $langtmp['english_name'] . ')" src="'.etm_tools_create_icons_url($langtmp['icon']).'" >';    
	
	}
	}
	return $return_string;
}

function etm_aasort_object(&$array, $key,$dir = false) {
	    $sorter=array();
	    $ret=array();
	    foreach ($array as $ii => $va) {
	        $sorter[$ii]=$va->$key;
	    }
	    if($dir){
		    arsort($sorter);
	    } else {
		    asort($sorter);
	    }
	    
	    foreach ($sorter as $ii => $va) {
	        $ret[]=$array[$ii];
	    }
	    $array=$ret;
}

function etm_aasort (&$array, $key,$dir = false) {
	    $sorter=array();
	    $ret=array();
	    reset($array);
	    foreach ($array as $ii => $va) {
	        $sorter[$ii]=$va[$key];
	    }
	    if($dir){
		    arsort($sorter);
	    } else {
		    asort($sorter);
	    }
	    
	    foreach ($sorter as $ii => $va) {
	        $ret[]=$array[$ii];
	    }
	    $array=$ret;
}
?>