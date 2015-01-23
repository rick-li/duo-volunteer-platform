<?php

class admin_etm_admin_translation {
	var $parent_id;
    var $pages;
    var $page;
    var $page_id;
    var $page_data;

    // Start up functions
	function admin_etm_admin_translation($parent_id){
		if(!empty($_GET['page_number']))
			$this->current_page = $_GET['page_number'];

        $this->pages = etm_tools_get_types();
        
        $this->parent_id = $parent_id; 
        $this->parent_menu_id = $parent_id.'-opt';      
		add_action($parent_id.'-options-menu', array(&$this,'admin_menu'), 50, 0);
			
	}

	// Create the difference menue
	function admin_menu(){
		if(isset($_GET['page'])){
	    	$this->page = $_GET['page'];
		}
        foreach($this->pages as $key => $pagedata){ 
        	$tmp_tag_view = 'etm_view_'.$pagedata['tag'];
        	$tmp_tag_translate = 'etm_translate_'.$pagedata['tag'];

			if(current_user_can($tmp_tag_view) or current_user_can($tmp_tag_translate) or current_user_can('manage_options')){
				if($this->page == $pagedata['id']){
					$this->page_id = $key;
					$this->page_data = $pagedata;
				}
				
				
				
				$this->plugin_page = add_submenu_page($this->parent_menu_id,$pagedata['menu_name'],$pagedata['menu_name'],$tmp_tag_view,$pagedata['id'],array(&$this,'chek_current_page') );		
            	add_action( 'admin_head-'. $this->plugin_page, array(&$this,'create_header') );
            	//add_action('admin_enqueue_scripts', array(&$this,'create_header2') );
            }
        }
	}
	
	// changes button on ice
	function fb_change_mce_buttons( $initArray ) {
    	$initArray["width"] = "100%";
$initArray['theme_advanced_buttons1']='bold,italic,strikethrough,|,bullist,numlist,blockquote,|,link,unlink,|,justifyleft,justifycenter,justifyright,|,spellchecker,fullscreen,wp_adv';
    	$initArray['theme_advanced_buttons2']='formatselect,underline,justifyfull,forecolor,|,removeformat,|,charmap,|,outdent,indent';
    	return $initArray;
    }

	// aktive pages body
	function chek_current_page(){
	   $wp_version_is_3_3 = etm_tools_version_check();
	    
	  /* add_filter('tiny_mce_before_init', array(&$this,'fb_change_mce_buttons'));

		if(!$wp_version_is_3_3) {
			if (function_exists('wp_tiny_mce')){
				wp_tiny_mce( false , // true makes the editor "teeny"
					array(
	    				"editor_selector" => "pp_readonly_content"
					)
				);
			}
		}
	   */

		?>

		<div class="wrap">
												
			<div id="input_translations" class="request-cont" style="display:none;"><div id="input_translations_content"></div></div>
			<div class="etm_icon etm_icon_<?php echo $this->page_data['texticonx'] ?>"></div><span><h2><?php echo $this->page_data['title']; ?></h2></span> 
			<div id="show_content_box" class="show_content_box">
				<div id="show_content_box_border" class="show_content_box_border">
					<div id="show_content_box_info" style="height:100%;" class="show_content_box_info"></div>
					<div id="show_content_box_info_post" class="show_content_box_info_post" style="height: 100%;display:none;">

						<table style="clear:both;" id="etm_table_1" height="100%" width="100%">
    						<tr><td width="100%"  colspan="3" valign="top"><h2 style="padding-top:0px"><span id="headertitle_post"></span></td>
    						</tr>
    						<tr style="text-align: left; height: 20px; line-height: 10px;">
    							<td width="49%"  valign="bottom">Default Header</td>
    							<td width="2%">&nbsp;</td>
    							<td width="49%" valign="bottom">Translated Header</td>
    						</tr>
    						<tr>
    							<td width="49%"  valign="top"><input type="text" enabled="false" disabled="disabled" style="clear:both;width:100%" id="post_default_header"></td>
    							<td width="2%">&nbsp;</td>
    							<td width="49%" valign="top"><input type="text" style="width:100%;clear:both;" id="post_translatede_header"></td>
    						</tr>
    						
    						<tr style="text-align: left; height: 20px; line-height: 10px;">
    							<td width="49%" valign="bottom">Default Body</td>
    							<td width="2%"></td>
    							<td width="49%" valign="bottom">Translated Body</td>
    						</tr>
    						
    						<tr height="100%" id="resize_pp_table_size">
    							<td width="49%" valign="top"><?php
									 if($wp_version_is_3_3){
									 	$settings = array( 'media_buttons' => false );
									 	wp_editor("pp_readonly_content", "pp_readonly_content",$settings);
									 } else {
									 	the_editor("", "pp_readonly_content", "pp_readonly_content", false);
									 	//echo '<textarea style="width:100%;height:100%;" class="pp_readonly_content" id="pp_readonly_content" name="pp_readonly_content"></textarea>'; 
									 }?> 
    							</td>
    							<td width="2%"></td>
    							<?php if($wp_version_is_3_3){ ?> 
    							<td width="49%" valign="top"><?php wp_editor("pp_translate_content", "pp_translate_content"); ?></td>
    							<?php } else { ?> 
    							<td width="49%" valign="top"><?php the_editor("", "pp_translate_content", "pp_translate_content", true); ?></td>
    							<?php }?> 
    						</tr>
    						<tr>
    							<td width="100%" valign="top" colspan="3"><div id="post_buttons"></div></td>
    						</tr>
    					</table>
    					
						<table style="clear:both;display:none;opacity: 0;" id="etm_table_2" height="100%" width="100%">
    						<tr><td width="100%"  colspan="3" valign="top"><h2 style="padding-top:0px"><span id="header_seo_post"></span></td>
    						</tr>
    						
    						
    						<tr style="text-align: left; height: 20px; line-height: 10px;">
    							<td width="49%"  valign="bottom">Default Focus Keyword</td>
    							<td width="2%">&nbsp;</td>
    							<td width="49%" valign="bottom">Translated Focus Keyword</td>
    						</tr>

    						<tr>
    							<td width="49%"  valign="top"><input type="text" enabled="false" disabled="disabled" style="clear:both;width:100%" id="post_default_focus_keyword"></td>
    							<td width="2%">&nbsp;</td>
    							<td width="49%" valign="top"><input type="text" style="width:100%;clear:both;" id="post_translatede_focus_keyword"></td>
    						</tr>
    						
    						<tr style="text-align: left; height: 20px; line-height: 10px;">
    							<td width="49%"  valign="bottom">Default SEO Title</td>
    							<td width="2%">&nbsp;</td>
    							<td width="49%" valign="bottom">Translated SEO Title</td>
    						</tr>

    						<tr>
    							<td width="49%"  valign="top"><input type="text" enabled="false" disabled="disabled" style="clear:both;width:100%" id="post_default_seo_title"></td>
    							<td width="2%">&nbsp;</td>
    							<td width="49%" valign="top"><input type="text" style="width:100%;clear:both;" id="post_translatede_seo_title"></td>
    						</tr>

    						<tr style="text-align: left; height: 20px; line-height: 10px;">
    							<td width="49%" valign="bottom">Default Meta Description</td>
    							<td width="2%"></td>
    							<td width="49%" valign="bottom">Translated Meta Description</td>
    						</tr>

    						<tr height="100%">
    							<td width="49%" valign="top"><textarea style="width:100%;height:100%;resize:none" class="pp_readonly_content" disabled="disabled" id="post_default_seo_meta_description"></textarea></td>
    							<td width="2%"></td>
    							<td width="49%" valign="top"><textarea style="width:100%;height:100%;resize:none" class="pp_readonly_content" id="post_translatede_meta_description"></textarea></td>
    						</tr>
    						<tr>
    							<td width="100%" valign="top" colspan="3"><div id="post_buttons2"></div></td>
    						</tr>
    					</table>
    					
    					
    					
                        <table style="clear:both;display:none;opacity: 0;" id="etm_table_3" height="100%" width="100%">
    						<tr>
    							<td width="100%"  colspan="3" valign="top"><h2 style="padding-top:0px"><span id="header_extra_post"></span></td>
    						</tr>
    						
    						
    						<tr style="text-align: left; height: 20px; line-height: 10px;">
    							<td width="49%" valign="bottom">Default Content Excerpts</td>
    							<td width="2%"></td>
    							<td width="49%" valign="bottom">Translated Excerpt</td>
    						</tr>
    						
    						<tr height="50%">
    							<td width="49%" valign="top"><textarea style="width:100%;height:100%;resize:none" class="pp_readonly_content" disabled="disabled" id="post_default_content_excerpts"></textarea></td>
    							<td width="2%"></td>
    							<td width="49%" valign="top"><textarea style="width:100%;height:100%;resize:none" class="pp_readonly_content" id="post_translatede_content_excerpts"></textarea></td>
    						</tr>
    						
    	
    						<tr style="text-align: left; height: 20px; line-height: 10px;">
    							<td width="49%" valign="bottom">Default Media Alternate Text</td>
    							<td width="2%"></td>
    							<td width="49%" valign="bottom">Translated Media Alternate Text</td>
    						</tr>	

    						<tr>
    							<td width="49%" valign="top"><input type="text" style="clear:both;width:100%" id="post_default_media_alternate_text"></td>
    							<td width="2%"></td>
    							<td width="49%" valign="top"><input type="text" style="clear:both;width:100%" id="post_translatedet_media_alternate_text"></td>
    						</tr>
    						
    						
    						<tr style="text-align: left; height: 20px; line-height: 10px;">
    							<td width="49%" valign="bottom">Default permalink</td>
    							<td width="2%"></td>
    							<td width="49%" valign="bottom">Translated permalink</td>
    						</tr> 
    						
    						<tr>
    							<td width="49%" valign="top"><input type="text" style="clear:both;width:100%" id="post_default_permalink"></td>
    							<td width="2%"></td>
    							<td width="49%" valign="top"><table width="100%"><tr><td width="2%"><?php echo trailingslashit(get_option('siteurl')); ?></td><td width="98%"><input type="text" style="clear:both;width:100%" id="post_translatedet_permalink"></td></tr></table></td>
    						</tr>                  

    						<tr>
    							<td width="100%" valign="top" colspan="3"><div id="post_buttons3"></div></td>
    						</tr>
    					</table>
					</div>
				</div>
			</div>
			<div id="loading_content" class="request-cont"></div>
        </div>
         <?php         					
    }
	
	
	// aktive page header
	function create_header(){
		global $easy_translation_manager_plugin;
	
		do_action("admin_head-post.php");
		do_action("admin_head-post-new.php");	
		wp_admin_css('thickbox');
		wp_enqueue_script('post');
		wp_enqueue_script('editor');
		wp_enqueue_script('editor-functions');
		add_thickbox();
		wp_enqueue_script('media-upload');
		wp_enqueue_script('word-count');
		wp_enqueue_script('media-upload');
		wp_enqueue_script('jquery-ui-core');
		wp_enqueue_script('jquery-ui-tabs');
	    wp_enqueue_style('thickbox');
		wp_enqueue_script( 'jquery-color' );
		wp_print_scripts('editor');
		wp_admin_css();
	
		$translatercode = '';
		if(!empty($easy_translation_manager_plugin->etm_tools_retrive['translator_yandex'])){
			$translatercode = $easy_translation_manager_plugin->etm_tools_retrive['translator_yandex'];
		}
	
		echo '<script> var EASY_TRANSLATION_MANAGER_URL = \''.EASY_TRANSLATION_MANAGER_URL.'\';
					   var EASY_TRANSLATION_MANAGER_WP_ADMIN = \''.admin_url() .'\';
					   var js_page_id = \''.$this->page_id.'\';
					   var js_page_data =\''.$this->page_data['tag'].'\';
					   var js_wp_version_is_3_3 =\''.etm_tools_version_check().'\';
					   var js_translation = \''.$translatercode.'\';
			  </script>
			  
			  <script>		  
			  	jQuery(document).ready(function($){
				jQuery(\'#insert-media-button\').click(function() {
				

				
				    wp.media.editor.open();
				
				    return false;       
				});
			  
			  });
			</script>  
			  
			  
			  <script type="text/javascript" src="'.EASY_TRANSLATION_MANAGER_URL.'js/translation_page.js"></script>
			  <link rel="stylesheet" type="text/css" href="'.EASY_TRANSLATION_MANAGER_URL.'css/admin_translation.css"></link>';
	}
}
?>