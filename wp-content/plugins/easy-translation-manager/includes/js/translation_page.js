//Default start
loadingContent(0,0);

// save date when need updates
var backup_previussubjecpage;
var backup_foldername;
var backup_object_title;
var backup_firsttime;
var backup_sort_type;
var backup_sort_dir;
var htmlAktive;
var aktive_resizer = false;
var etm_scroll_postion = 0;
	
// update list		
function opdaterLoaderContent(){					loadingContent(backup_previussubjecpage,backup_foldername,backup_object_title,backup_firsttime,backup_sort_type,backup_sort_dir);
}
	
function seachloadingContent(_previussubjecpage,foldername,object_title,firsttime,_sort_type,_sort_dir){	
jQuery(document).ready(function($){
loadingContent(_previussubjecpage,foldername,object_title,firsttime,_sort_type,_sort_dir,jQuery('#seachtypecgm').val());
});
}


function seachloadingeach(tmp_this){
	jQuery('.seachtypecgm').val(jQuery(tmp_this).val());
}
	
function seachloadingdb(){
	jQuery('.seachtypecgm').val('');
}	
	
jQuery(document).ready(function($){
	if(jQuery('body').hasClass('etm-settings_page_etm_themes_translations') || jQuery('body').hasClass('etm-settings_page_etm_plugins_translations') || jQuery('body').hasClass('etm-settings_page_etm_addon_translations')){
		jQuery(window).on('beforeunload', function() {
			etm_goodbye();
			window.setTimeout(function(){return false;},1000);
		});
	}
});	
		
	
		
// create list
function loadingContent(_previussubjecpage,foldername,object_title,firsttime,_sort_type,_sort_dir,seachtitle){
	var _url;
	

    
    if(_previussubjecpage == '--ss--'){
        _previussubjecpage = jQuery('.tablejq_page').val()-1;  
           
    } else {
        backup_previussubjecpage = _previussubjecpage; 
    }
    
	
	backup_foldername = foldername; 
	backup_object_title = object_title;
	backup_firsttime = firsttime;
	backup_sort_type = _sort_type
	backup_sort_dir = _sort_dir;
	
	jQuery(document).ready(function($){
		jQuery('#editor-toolbar').hide();
		jQuery('#ed_toolbar').hide();
		jQuery('#quicktags').hide();
		jQuery('#quicktags').css('opacity','0.0');
		
		jQuery('#loading_content').fadeTo('fast', 0.0, function() {
			jQuery('#loading_content').html('<img src="'+EASY_TRANSLATION_MANAGER_URL+'images/loader.gif">');
			jQuery('#loading_content').fadeTo('fast', 1.0, function() {
				if(foldername){
				_url = EASY_TRANSLATION_MANAGER_WP_ADMIN+'?etm_data=true&etm_fn=page_loader_2';	
				} else {
				_url = EASY_TRANSLATION_MANAGER_WP_ADMIN+'?etm_data=true&etm_fn=page_loader_1';	  
				}
				
				jQuery.post(_url,{sort_type:_sort_type,sort_dir:_sort_dir,folder:foldername,previussubjecpage:_previussubjecpage,title:object_title,type:js_page_id,firsttime:firsttime,seachtitle:seachtitle},function(data){
					jQuery('#loading_content').fadeTo('fast', 0.0, function() {
						if(data){
							jQuery('#loading_content').html(data);
							jQuery('#loading_content').delay(432).fadeTo('fast', 1.0);	
						}
					});	
				});
			});
		});	
	});	
}  
		
// show pop op box		
function showPopOpControl(tmp_id,tmp_lang,tmp_type,taxonomy){
	jQuery(document).ready(function($){
	
		if(jQuery('.mp-pusher').length > 0){
		
			etm_scroll_postion = jQuery(window).scrollTop();
			jQuery('#wpwrap .mp-container').css('width','100%').css('width','100%').css('height','100%').css('min-height','100%').css('position','absolute').css('top','0px').css('bottom','0px').css('left','0px').css('right','0px');
			jQuery('#wpwrap .mp-container .mp-pusher .scroller').css('width','100%').css('width','100%').css('height','100%').css('min-height','100%').css('position','absolute').css('top','0px').css('bottom','0px').css('left','0px').css('right','0px');
			jQuery('#wpwrap .mp-container .mp-pusher .scroller .scroller-inner').css('height','100%').css('min-height','100%');
			jQuery('#wpwrap .mp-container .mp-pusher .scroller .scroller-inner #wpfooter').hide();	
			jQuery(window).scrollTop(etm_scroll_postion);
		}
	
		var _url;
		jQuery('#show_content_box_info').html('');
			defaultStartPopOpControl();
			
			jQuery('#show_content_box_border').fadeTo('fast', 0.0, function() {
				aktive_resizer = 1;
				resize_table();

				inputLoaderPopOpContol();
						
				jQuery('#show_content_box').fadeTo('fast', 1.0);
				jQuery('#show_content_box_border').delay(10).fadeTo('fast', 1.0, function() {
					jQuery.post(get_right_url(tmp_type),{tmp_id:tmp_id,tmp_lang:tmp_lang,tmp_type:tmp_type,taxonomy:taxonomy},function(data){
						jQuery('#show_content_box_info').fadeTo('fast', 0.0, function() {
							if(data.R == 'OK'){
								if(tmp_type == 'post' || tmp_type == 'page'){
								
									jQuery('#show_content_box_info').hide();

									jQuery('#post_buttons').html(data.buttons);
									
									jQuery('#header_seo_post').html('');
									jQuery('#header_seo_post').html(data.default_heade2);
									jQuery('#post_default_focus_keyword').val('');
									jQuery('#post_default_focus_keyword').val(data.yoast_wpseo_focuskw);
									jQuery('#post_translatede_focus_keyword').val('');
									jQuery('#post_translatede_focus_keyword').val(data.tran_focuskw);
									
									
									jQuery('#post_default_seo_title').val('');
									jQuery('#post_default_seo_title').val(data.yoast_wpseo_title);
									jQuery('#post_translatede_seo_title').val('');
									jQuery('#post_translatede_seo_title').val(data.tran_title);

									
									jQuery('#post_default_seo_meta_description').val('');
									jQuery('#post_default_seo_meta_description').val(data.yoast_wpseo_metadesc);
									jQuery('#post_translatede_meta_description').val('');
									jQuery('#post_translatede_meta_description').val(data.tran_metadesc);
								
									jQuery('#post_default_permalink').val('');
									jQuery('#post_default_permalink').val(data.default_permalink);
									jQuery('#post_translatedet_permalink').val('');
									jQuery('#post_translatedet_permalink').val(data.tran_permalink);
	
									jQuery('#post_buttons2').html(data.buttons2);
									
                                    
									jQuery('#header_extra_post').html('');
									jQuery('#header_extra_post').html(data.header_extra_post); 


									jQuery('#post_default_content_excerpts').val('');
									jQuery('#post_default_content_excerpts').val(data.post_default_content_excerpts); 
                                    
									jQuery('#post_translatede_content_excerpts').val('');
									jQuery('#post_translatede_content_excerpts').val(data.post_translatede_content_excerpts); 

									jQuery('#post_default_media_alternate_text').val('');
									jQuery('#post_default_media_alternate_text').val(data.post_default_media_alternate_text); 
                                    
									jQuery('#post_translatedet_media_alternate_text').val('');
									jQuery('#post_translatedet_media_alternate_text').val(data.post_translatedet_media_alternate_text); 
             
									jQuery('#post_buttons3').html('');
									jQuery('#post_buttons3').html(data.post_buttons3);



									//setupTinymcePopOpContol();
									setHTMLTinymcePopOpContol(true);
									jQuery('#headertitle_post').html('');
									jQuery('#headertitle_post').html(data.langed_string);
									jQuery('#post_default_header').val('');
									jQuery('#post_default_header').val(data.default_header);
									jQuery('#post_translatede_header').val('');
									jQuery('#post_translatede_header').val(data.translations_header);

									setContentTinymcePopOpContol(data.default_body,data.translations_body)		
									setHTMLTinymcePopOpContol(true);
									checkforchanges_beforeclose(true);
									jQuery('#show_content_box_info_post').fadeTo('slow', 1.0,function(){

										
                                                                                window.setTimeout(function(){
setHTMLTinymcePopOpContol(false);

},500);
									});
									

									if(data.DIR == 'rtl'){
										jQuery('#pp_readonly_content').css('textAlign','right');
										jQuery('#pp_translate_content').css('textAlign','right');
										jQuery('#post_default_header').css('textAlign','right');
										jQuery('#post_translatede_header').css('textAlign','right');
										jQuery('#post_default_focus_keyword').css('textAlign','right');
										jQuery('#post_default_seo_title').css('textAlign','right');
										jQuery('#post_default_seo_meta_description').css('textAlign','right');
										jQuery('#post_translatede_focus_keyword').css('textAlign','right');
										jQuery('#post_translatede_seo_title').css('textAlign','right');
										jQuery('#post_translatede_meta_description').css('textAlign','right');

										if(typeof(tinyMCE) !== 'undefined' && typeof(tinyMCE.get('pp_readonly_content')) !== 'undefined' && typeof(tinyMCE.get('pp_translate_content')) !== 'undefined' ){
											tinyMCE.get('pp_readonly_content').getBody().dir = 'rtl';	
											tinyMCE.get('pp_translate_content').getBody().dir = 'rtl';
										}	
									} else {
										jQuery('#pp_readonly_content').css('textAlign','left');
										jQuery('#pp_translate_content').css('textAlign','left');
										jQuery('#post_default_header').css('textAlign','left');
										jQuery('#post_translatede_header').css('textAlign','left');
										jQuery('#post_default_focus_keyword').css('textAlign','left');
										jQuery('#post_default_seo_title').css('textAlign','left');
										jQuery('#post_default_seo_meta_description').css('textAlign','left');
										jQuery('#post_translatede_focus_keyword').css('textAlign','left');
										jQuery('#post_translatede_seo_title').css('textAlign','left');
										jQuery('#post_translatede_meta_description').css('textAlign','left');
										
										if(typeof(tinyMCE) !== 'undefined' && typeof(tinyMCE.get('pp_readonly_content')) !== 'undefined' && typeof(tinyMCE.get('pp_translate_content')) !== 'undefined' ){
											tinyMCE.get('pp_readonly_content').getBody().dir = 'ltr';	
											tinyMCE.get('pp_translate_content').getBody().dir = 'ltr';
										}	
									}
                                    
								} else {																										jQuery('#show_content_box_info').show();								
									jQuery('#show_content_box_info').html(data.RETURNDATA);
									jQuery('#show_content_box_info').fadeTo('slow', 1.0);
									checkforchanges_beforeclose(false);
									
								}
								resize_table();
								
                                window.setTimeout(function(){resize_table()},5000);
							} else {
								alert(data.MSG);
								cancelPopOpControl();  
							}
						});
				},'json');  
			});	
		});
	});	
}


// save data from popOpbox
function savePopOpControl(tmp_id,tmp_lang,tmp_translationpos,tmp_type,tmp_H,tmp_B){
	jQuery(document).ready(function($){
		var temp_extra = ''
		if(tmp_type == 'post' || tmp_type == 'page'){
            setHTMLTinymcePopOpContol(false);
			tmp_B = tinyMCE.get('pp_translate_content').getContent();
			tmp_H = jQuery(tmp_H).val();
		}
		
		
		if(tmp_type == 'meta'){
			if(jQuery('#etm_multi_array_data').length != 0) {
			 	temp_extra = jQuery('#etm_multi_array_data').serialize();
			 	temp_extra = '&multidata=true&' + temp_extra;
			}
		}
		
		
		var translation_str = jQuery(tmp_translationpos).val();
		var translation_str_ex1 = jQuery('#translations_inputtext_extra1').val();        
		var translation_str_ex2 = jQuery('#translations_inputtext_extra2').val();        
		var translation_str_ex3 = jQuery('#translations_inputtext_extra3').val();  
		     
		var backup_save = jQuery('#show_content_box_info').html();
		var backup_save_post = jQuery('#show_content_box_info_post').html();
		
		jQuery('#show_content_box_info_post').fadeTo('fast', 0.0);
		jQuery('#show_content_box_info').fadeTo('fast', 0.0, function() {
				inputLoaderPopOpContol();
				jQuery('#show_content_box_info').fadeTo('slow', 1.0, function() {
					jQuery.post(get_right_url(tmp_type)+temp_extra,{tmp_id:tmp_id,tmp_lang:tmp_lang,tmp_translations:translation_str,tmp_translations_ex1:translation_str_ex1,tmp_translations_ex2:translation_str_ex2,tmp_translations_ex3:translation_str_ex3,tmp_type:tmp_type,translatede_header:tmp_H,translatede_body:tmp_B,tran_focuskw :jQuery('#post_translatede_focus_keyword').val(),tran_title:jQuery('#post_translatede_seo_title').val(),tran_metadesc:jQuery('#post_translatede_meta_description').val(),tran_permalink:jQuery('#post_translatedet_permalink').val(),content_excerpts:jQuery('#post_translatede_content_excerpts').val(),attachment_image_alt:jQuery('#post_translatedet_media_alternate_text').val(),status:'save'},function(data){
						jQuery('#show_content_box_info').fadeTo('slow', 0.0, function() {
							if(data.R == 'OK'){ 
							
								if(tmp_type == 'menu'){
									tmp_id = tmp_H
								}
							
								if(data.INFOCON == '1'){
									jQuery('.icon_lang_'+tmp_id+'_'+tmp_lang).css({ opacity: 1});	
								} else {
									jQuery('.icon_lang_'+tmp_id+'_'+tmp_lang).css({ opacity: 0.5});	 
								}
								showSuccessMessage(data.MSG); 
							} else {
								showErrorMessage(tmp_type,data.MSG,backup_save); 
							}
						});
				},'json');  
			});	
		});
	});	
}
		
var pop_op_box;
var translations_header;
var translations_body;

function checkforchanges_beforeclose(tmp_pop_op_box){
	pop_op_box = tmp_pop_op_box;

	if(tmp_pop_op_box){
        setHTMLTinymcePopOpContol(false);
        if(typeof(tinyMCE) !== 'undefined' && typeof(tinyMCE.get('pp_translate_content')) !== 'undefined'  && tinyMCE.get('pp_translate_content') != null){
		  translations_body = tinyMCE.get('pp_translate_content').getContent();
        }
        translations_header = jQuery('#post_translatede_header').val();
	} else {
		translations_body = jQuery('#translations_inputtext').val();
	}
}

function checkforchanges_beforeclose_check(){
	if(pop_op_box){
		var tmp_translations_body = tinyMCE.get('pp_translate_content').getContent();
		var tmp_translations_header = jQuery('#post_translatede_header').val();
		
		if(tmp_translations_body != translations_body || tmp_translations_header != translations_header){
			return true;
		} else {
			return false;
		}
		
	} else {
		var tmp_translations_body = jQuery('#translations_inputtext').val();
		
		if(tmp_translations_body != translations_body){
			return true;
		} else {
			return false;
		}		
	}
}
	
		 
		
// cancel pop op	
function cancelPopOpControl(){

	if(checkforchanges_beforeclose_check()){
		if (!confirm("Are you sure you want to cancel ?")){
			return '';
		};
	}

	jQuery(document).ready(function($){
		jQuery('#show_content_box').fadeTo('slow', 0.0); 
		jQuery('#show_content_box_info_post').fadeTo('fast', 0.0);
		jQuery('#show_content_box_info').fadeTo('slow', 0.0, function() {
			endSessionPopOpContol();
		});
	});
}

// delete manual added strings
function deleteManualControl(tmp_title,tmp_id,tmp_type){
	if (confirm("Do you want to delete all translation with "+ tmp_title +" ?")){
		jQuery(document).ready(function($){
			jQuery.post(get_right_url(tmp_type),{tmp_id:tmp_id,status:'deleteManuals'},function(data){
				alert(data.MSG);
				if(data.R == 'OK'){ 
					opdaterLoaderContent();
				}
			},'json');  
		});		
	}			  
} 
	
// delete transaltion		
function deletePopOpControl(tmp_id,tmp_lang,tmp_type){
	if (confirm("Do you want to delete this translation?")){
		jQuery(document).ready(function($){
			jQuery('#show_content_box_info_post').fadeTo('fast', 0.0);
			jQuery('#show_content_box_info').fadeTo('fast', 0.0, function() {
				inputLoaderPopOpContol();
				jQuery('#show_content_box_info').fadeTo('slow', 1.0, function() {
					jQuery.post(get_right_url(tmp_type),{tmp_id:tmp_id,tmp_lang:tmp_lang,status:'delete'},function(data){
						jQuery('#show_content_box_info').fadeTo('slow', 0.0, function() {
							if(data.R == 'OK'){ 
								jQuery('.icon_lang_'+tmp_id+'_'+tmp_lang).css({ opacity: 0.5});	
								showSuccessMessage(data.MSG);
							} else {
								showErrorMessage(tmp_type,data.MSG,backup_save);
							}
						});
					},'json');	
				});	
			});
		});	
	}			  
} 		 


function redigerManualControl(tmp_string,tmp_mo,tmp_id,tmp_tag,tmp_file){ 
	openManualInput(tmp_tag,'',tmp_string,tmp_mo,tmp_file,tmp_id);

}

// open manaual string	
function openManualInput(tmp_tag,tmp_folder,tmp_string,tmp_mo,tmp_file,tmp_id){
	jQuery(document).ready(function($){
		aktive_resizer = 1;
		resize_table();
	
		defaultStartPopOpControl();
		jQuery('#show_content_box_info').fadeTo('slow', 0.0, function() {
		
			if(tmp_id > 0){
				var content_info = '<table style="clear:both;" height="100%" width="100%"><tr><td width="100%"  valign="top"><h2 style="padding-top:0px"><h2>Edit manual default string</h2></td></tr>'; 
			} else {
				var content_info = '<table style="clear:both;" height="100%" width="100%"><tr><td width="100%"  valign="top"><h2 style="padding-top:0px"><h2>Add manual default string</h2></td></tr>'; 
			}			
			
			
			if(tmp_mo == 'Variable textdomain'){
				tmp_mo = '';
			}				
			
			if(tmp_file === undefined){
				tmp_file = '';
			}	   
			if(tmp_mo === undefined){
				tmp_mo = '';
			}
			if(tmp_string === undefined){
				tmp_string = '';
			}    
    
			content_info += '<tr style="text-align: left; height: 20px; line-height: 10px;"><td width="100%"  valign="bottom">File place</td></tr>';
			content_info += '<tr><td width="100%" valign="bottom"><input style="width: 100%; padding: 0px; margin: 0px;" id="file_inputtext" value="'+tmp_file+'"></td></tr>';
			
			content_info += '<tr style="text-align: left; height: 20px; line-height: 10px;"><td width="100%"  valign="bottom">Textdomain (*)</td></tr>';
			content_info += '<tr><td width="100%" valign="bottom"><input style="width: 100%; padding: 0px; margin: 0px;" id="mo_inputtext" value="'+tmp_mo+'"></td></tr>';
			
			content_info += '<tr style="text-align: left; height: 20px; line-height: 10px;"><td width="100%"  valign="bottom">Default string (*)</td></tr>';
			content_info += '<tr height="100%"><td width="100%" valign="bottom"><textarea style="height:100%;width:100%" id="default_inputtext">'+tmp_string+'</textarea></td></tr>';		
			
			content_info += '<tr><td width="100%" valign="bottom"><div style="float:right;padding-top:10px"><input type="submit" onClick="cancelPopOpControl()" value="Cancel" class="button-secondary" name="Cancel"><input onClick="saveManualInput(\''+tmp_tag+'\',\''+tmp_folder+'\',\'#default_inputtext\',\'#file_inputtext\',\'#mo_inputtext\',\''+tmp_id+'\')" type="submit" class="button-primary" value="Save" name="Save"></div></td></tr></table>';


			jQuery('#show_content_box_info').html(content_info);
			jQuery('#show_content_box_info').fadeTo('slow', 1.0);
			jQuery('#show_content_box').fadeTo('slow', 1.0);
			jQuery('#show_content_box_border').fadeTo('slow', 1.0);
			resize_table();
	 
		}); 
	});	
}
			
//Save manual string
function saveManualInput(tmp_tag,tmp_folder,tmp_defaulttext,tmp_filetext,tmp_motext,tmp_id){
	jQuery(document).ready(function($){
		var default_str = jQuery(tmp_defaulttext).val();
		var file_str = jQuery(tmp_filetext).val();
		var mo_str = jQuery(tmp_motext).val();
		var backup_save = jQuery('#show_content_box_info').html();
		
		jQuery('#show_content_box_info').fadeTo('fast', 0.0, function() {
			inputLoaderPopOpContol();
			jQuery('#show_content_box_info').fadeTo('slow', 1.0, function() {
				var _url = EASY_TRANSLATION_MANAGER_WP_ADMIN+'?etm_data=true&etm_fn=mo_controle';
				var tmp_status = 'create';
				if(tmp_id>0){
					tmp_status = 'update';
				}
				
				jQuery.post(_url,{tag:tmp_tag,folder:tmp_folder,tmp_defaulttext:default_str,tmp_filetext:file_str,tmp_motext:mo_str,id:tmp_id,status:tmp_status},function(data){
					jQuery('#show_content_box_info').fadeTo('slow', 0.0, function() {
						if(data.R == 'OK'){ 
							showSuccessMessage(data.MSG,true);
						} else {
							showErrorMessage('',data.MSG,backup_save);
						}
					});
				},'json');  
			});	
		});
	});	
}		


function updateListFiles(tmp_content,etm_tag,etm_folder){ 
	jQuery(document).ready(function($){
		var backup_tmp_content = jQuery(tmp_content).html();
		jQuery(tmp_content).fadeTo('fast', 0.0, function() {
			jQuery(tmp_content).html('<img style="float:left" src="'+EASY_TRANSLATION_MANAGER_URL+'images/loader.gif"><span style="float: left; margin-left: 5px; line-height: 15px;">Loading </span>');
			jQuery(tmp_content).fadeTo('slow', 1.0, function() {
			
			
			
				var _url = EASY_TRANSLATION_MANAGER_WP_ADMIN+'?etm_data=true&etm_fn=mo_reloader';
				jQuery.post(_url,{etm_tag:etm_tag,etm_folder:etm_folder},function(data){
					jQuery(tmp_content).fadeTo('slow', 0.0, function() {
						if(data.R == 'OK'){ 
							jQuery(tmp_content).html('<span>List have been reloaded</span>');
							jQuery(tmp_content).fadeTo('slow', 1.0, function() {
								jQuery(tmp_content).delay(1000).fadeTo('slow', 0.0, function() {
									opdaterLoaderContent();
								});
							});	
						} else {
							jQuery(tmp_content).html('<span>Error contact support</span>');
							jQuery(tmp_content).fadeTo('slow', 1.0, function() {
								jQuery(tmp_content).delay(1000).fadeTo('slow', 0.0, function() {
									jQuery(tmp_content).html(backup_tmp_content);
									jQuery(tmp_content).fadeTo('slow', 1.0);
								});
							});	
						}
					});
				},'json'); 
			});	
		});
	});	
}	



function updateMoFiles(tmp_content){
	jQuery(document).ready(function($){
		var backup_tmp_content = jQuery(tmp_content).html();
		jQuery(tmp_content).fadeTo('fast', 0.0, function() {
			jQuery(tmp_content).html('<img style="float:left" src="'+EASY_TRANSLATION_MANAGER_URL+'images/loader.gif"><span style="float: left; margin-left: 5px; line-height: 15px;">Loading </span>');
			jQuery(tmp_content).fadeTo('slow', 1.0, function() {
				var _url = EASY_TRANSLATION_MANAGER_WP_ADMIN+'?etm_data=true&etm_fn=mo_generator';
				jQuery.post(_url,function(data){
					jQuery(tmp_content).fadeTo('slow', 0.0, function() {
						if(data.R == 'OK'){ 
							jQuery(tmp_content).html('<span>New mo files has been generated</span>');
							jQuery(tmp_content).fadeTo('slow', 1.0, function() {
								jQuery(tmp_content).delay(1000).fadeTo('slow', 0.0, function() {
									jQuery(tmp_content).html(backup_tmp_content);
									jQuery(tmp_content).fadeTo('slow', 1.0);
								});
							});	
						} else {
							jQuery(tmp_content).html('<span>Error contact support</span>');
							jQuery(tmp_content).fadeTo('slow', 1.0, function() {
								jQuery(tmp_content).delay(1000).fadeTo('slow', 0.0, function() {
									jQuery(tmp_content).html(backup_tmp_content);
									jQuery(tmp_content).fadeTo('slow', 1.0);
								});
							});	
						}
					});
				},'json'); 
			});	
		});
	});	
}	
				
// ------------------------- extra function to controle pop op functions -------------------
// set to default mode
function defaultStartPopOpControl(){
	jQuery('#show_content_box').fadeTo('fast', 0.0); 
	jQuery('#show_content_box_info').fadeTo('fast', 1.0);
	jQuery('#show_content_box_info_post').fadeTo('fast', 0.0);
	jQuery('#show_content_box_info_post').hide();
}	

// end a session
function endSessionPopOpContol(){
	if(jQuery('.mp-pusher').length > 0){
		jQuery('#wpwrap .mp-container').css('position','relative');
		jQuery('#wpwrap .mp-container .mp-pusher .scroller').css('position','relative');
		jQuery('#wpwrap .mp-container .mp-pusher .scroller .scroller-inner #wpfooter').show();
		jQuery(window).scrollTop(etm_scroll_postion);
	}

						aktive_resizer = false;
	jQuery('#show_content_box_info_post').hide(); 
	jQuery('#show_content_box_info').hide();
	jQuery('#show_content_box').hide();
	jQuery('#show_content_box_info').html('');
	jQuery('.show_content_box_border').width(600).css("margin-left",'-300px');
}

//show success messages
function showSuccessMessage(MSG,opdate_list){
	/*jQuery('#show_content_box_info').html('<span class="etm_middle_align_objects">'+MSG+'</span>');
	jQuery('#show_content_box_info').fadeTo('slow', 1.0, function() {
		jQuery('#show_content_box').delay(1000).fadeTo('slow', 0.0, function() {*/
			endSessionPopOpContol();
			if(opdate_list){
				opdaterLoaderContent();
			}
		/*});
	});	*/
}

// show error message
function showErrorMessage(tmp_type,MSG,backup_save){
	jQuery('#show_content_box_info').html(MSG);
	jQuery('#show_content_box_info').fadeTo('slow', 1.0, function() {
		jQuery('#show_content_box_info').delay(2000).fadeTo('slow', 0.0, function() {
			if(tmp_type == 'post' || tmp_type == 'page'){
				jQuery('#show_content_box_info_post').fadeTo('slow', 1.0);
			} else { 
				jQuery('#show_content_box_info').html(backup_save);
				jQuery('#show_content_box_info').fadeTo('slow', 1.0);
			}
		});		
	}); 	
} 
							
//
function get_right_url(tmp_type){
	var _url;
	if(tmp_type == 'plugin' || tmp_type == 'theme'  || tmp_type == 'addon'){
		_url = EASY_TRANSLATION_MANAGER_WP_ADMIN+'?etm_data=true&etm_fn=mo_controle';
	} else if(tmp_type == 'post_tag' || tmp_type == 'category'){	
		_url = EASY_TRANSLATION_MANAGER_WP_ADMIN+'?etm_data=true&etm_fn=terms_controle';
	} else if(tmp_type == 'menu'){
		_url = EASY_TRANSLATION_MANAGER_WP_ADMIN+'?etm_data=true&etm_fn=menu_controle';
	} else if(tmp_type == 'meta'){
		_url = EASY_TRANSLATION_MANAGER_WP_ADMIN+'?etm_data=true&etm_fn=meta_controle';
	} else if(tmp_type == 'site_options'){
		_url = EASY_TRANSLATION_MANAGER_WP_ADMIN+'?etm_data=true&etm_fn=site_controle';
	} else {
		_url = EASY_TRANSLATION_MANAGER_WP_ADMIN+'?etm_data=true&etm_fn=post_controle';
	}

	return _url;
}

				
// set loading icon.
function inputLoaderPopOpContol(){
	jQuery('#show_content_box_info').html('<img class="etm_middle_align_objects" src="'+EASY_TRANSLATION_MANAGER_URL+'images/loader.gif">');
}

// aktivates stinymce pop op controle
function setupTinymcePopOpContol(){
		//setHTMLTinymcePopOpContol(false);
		//setHTMLTinymcePopOpContol(true);
		//setContentTinymcePopOpContol('','')
	if(!js_wp_version_is_3_3){
		jQuery('#pp_translate_content').width('100%');
		jQuery('#pp_translate_content').css('resize','vertical'); 
	} else {
		jQuery('#pp_translate_content_ifr').height('440px');
		jQuery('#pp_readonly_content_ifr').height('440px');
	}
}

// changes the html mode to preview mode
function setHTMLTinymcePopOpContol(boolean_var){
	if(!js_wp_version_is_3_3){
		if(boolean_var){
			switchEditors.go('pp_readonly_content', 'html');
			switchEditors.go('pp_translate_content', 'html');
			htmlAktive = true;
		} else {
			switchEditors.go('pp_readonly_content', 'tinymce');
			switchEditors.go('pp_translate_content', 'tinymce');
			htmlAktive = false;
		}
	} else {
		if(boolean_var){
			switchEditors.go('pp_readonly_content', 'html');
			switchEditors.go('pp_translate_content', 'html');
			htmlAktive = true;
		} else {
			switchEditors.go('pp_readonly_content', 'tmce');
			switchEditors.go('pp_translate_content', 'tmce');
			htmlAktive = false;
		}
	}
}

// inputs data to tinymce
function setContentTinymcePopOpContol(default_body,translations_body){

	if(default_body != null){
		if(tinyMCE.get('pp_readonly_content') != undefined){
			tinyMCE.get('pp_readonly_content').setContent(default_body)
		}
		jQuery('#pp_readonly_content').val(default_body);
	} else {
		if(tinyMCE.get('pp_readonly_content') != undefined){
			tinyMCE.get('pp_readonly_content').setContent('')
		}
		jQuery('#pp_readonly_content').val('');
	}
	if(translations_body != null){
		if(tinyMCE.get('pp_translate_content') != undefined){
			tinyMCE.get('pp_translate_content').setContent(translations_body);
		}
		jQuery('#pp_translate_content').val(translations_body);
	} else {
		if(tinyMCE.get('pp_translate_content') != undefined){
			tinyMCE.get('pp_translate_content').setContent('');
		}
		jQuery('#pp_translate_content').val('');
	}
}

// switch buttons view
function switch_html_preview(textarea1,textarea2,button_click){
	jQuery(document).ready(function($){
		if(htmlAktive){
			jQuery(button_click).val('HTML MODE');
			setHTMLTinymcePopOpContol(false);
		} else{
			jQuery(button_click).val('VISUAL MODE');
			setHTMLTinymcePopOpContol(true);
		}
	});
}

//
function etm_switch_seo(from_tmp,to_tmp){
	jQuery(document).ready(function($){
		jQuery(from_tmp).fadeTo('fast', 0.0, function() {
			jQuery(from_tmp).hide();
			jQuery(to_tmp).show();
			jQuery(to_tmp).fadeTo('fast', 1.0);
			
		});
	});
}



// Copy button
function copiePopOpControl(tmp_Hfrom,tmp_Hto){
	jQuery(document).ready(function($){
		if(tinyMCE.get('pp_readonly_content').getContent()){
			tinyMCE.get('pp_translate_content').setContent(tinyMCE.get('pp_readonly_content').getContent());
			jQuery('#pp_translate_content').val(jQuery('#pp_readonly_content').val())
		}
		jQuery(tmp_Hto).val(jQuery(tmp_Hfrom).val());
	});
}
			 	 
function etm_goodbye() {
		var _url = EASY_TRANSLATION_MANAGER_WP_ADMIN+'?etm_data=true&etm_fn=mo_generator';
		jQuery.post(_url,function(data){
		});
}

function googleTranslateEtm(tmp_to,tmp_from){
	if(jQuery('#etm_default_inputtext').length > 0){
		googleTranslateEtm_loading(tmp_to,tmp_from,'#etm_default_inputtext','#translations_inputtext');
	}
	
	if(jQuery('#etm_default_inputtext_extra1').length > 0){
		googleTranslateEtm_loading(tmp_to,tmp_from,'#etm_default_inputtext_extra1','#translations_inputtext_extra1');
	}	
	
	if(jQuery('#etm_default_inputtext_extra3').length > 0){
		googleTranslateEtm_loading(tmp_to,tmp_from,'#etm_default_inputtext_extra3','#translations_inputtext_extra3');
	}		
	
	
	
}
	
	
function googleTranslateEtm_loading(tmp_to,tmp_from,tmp_string_org,tmp_return){

	tmp_string = encodeURIComponent(jQuery(tmp_string_org).html());

	if(typeof(tmp_string) == 'undefined' || tmp_string == '' || tmp_string == 'undefined' || tmp_string == undefined){
		tmp_string = encodeURIComponent(jQuery(tmp_string_org).val());
	}
	
	if(typeof(tmp_string) == 'undefined' || tmp_string == '' || tmp_string == 'undefined' || tmp_string == undefined){
		return '';
	}

	urlFormat = "https://translate.yandex.net/api/v1.5/tr.json/translate?key="+js_translation+"&text="+tmp_string;

	jQuery.get(urlFormat+'&lang='+tmp_to,function(data){
		googleTranslateEtm_datahandleling(data,tmp_to,tmp_from,tmp_string_org,tmp_return);
	}).fail(function(data){
		if(typeof(data.responseJSON) != 'undefined'){
			googleTranslateEtm_datahandleling(data.responseJSON,tmp_to,tmp_from,tmp_string_org,tmp_return);
		} else {
			alert('Error');
		}
		
	}); 
}

function googleTranslateEtm_datahandleling(data,tmp_to,tmp_from,tmp_string_org,tmp_return){
	if(typeof(data) != 'undefined'){
		if(data.code == 200){
			if(typeof(data.text[0]) != 'undefined'){
				jQuery(tmp_return).val(data.text[0]);
			}
		} else if(data.code == 401){
			alert('Invalid API key.');
		} else if(data.code == 402){
			alert('This API key has been blocked.');
		} else if(data.code == 403){
			alert('You have reached the daily limit for requests (including calls of the detect method).');
		} else if(data.code == 404){
			alert('You have reached the daily limit for the volume of translated text (including calls of the detect method).');
		} else if(data.code == 413){
			alert('The text size exceeds the maximum.');
		} else if(data.code == 422){		
			googleTranslateEtm_loading(tmp_from+'-'+tmp_to,tmp_from,tmp_string_org,tmp_return);
		} else if(data.code == 501){
			alert('The specified translation direction is not supported.');
		} else {
			alert('Error 2');
		}
	} else {
		alert('Problem retrieving data (server temporarily unreachable).');
	}
}


	
	
	
var etm_height_temp = 0;
var etm_height_temp_toolbar_readonly = 0;
var etm_height_temp_toolbar_translate = 0;
var button_click_temp_first_time = true;
function resize_table(){
		jQuery(document).ready(function($){
		
			if(button_click_temp_first_time){
				jQuery('#pp_readonly_content_wp_adv').click(resize_table);
				jQuery('#pp_translate_content_wp_adv').click(resize_table);
				jQuery('#pp_readonly_content-tmce').attr('onclick','switchEditors.switchto(this);resize_table();');
				jQuery('#pp_readonly_content-html').attr('onclick','switchEditors.switchto(this);resize_table();');
				jQuery('#pp_translate_content-tmce').attr('onclick','switchEditors.switchto(this);resize_table();');
				jQuery('#pp_translate_content-html').attr('onclick','switchEditors.switchto(this);resize_table();');
				jQuery('#pp_readonly_content').css("resize", "none");
				jQuery('#pp_translate_content').css("resize", "none");
				
				jQuery('#wp-pp_readonly_content-media-buttons').css("overflow", "hidden");
				jQuery('#wp-pp_readonly_content-media-buttons').css("text-align", "left");
				jQuery('#wp-pp_readonly_content-media-buttons').css("white-space", "nowrap");	
				jQuery('#wp-pp_translate_content-media-buttons').css("overflow", "hidden");
				jQuery('#wp-pp_translate_content-media-buttons').css("text-align", "left");
				jQuery('#wp-pp_translate_content-media-buttons').css("white-space", "nowrap");
				
				jQuery("#show_content_box_border").click(function(){ return false; });
				jQuery("#show_content_box").click(cancelPopOpControl);			

				button_click_temp_first_time = false;
			}
			
			jQuery('#pp_readonly_content_resize').remove();
			jQuery('#pp_translate_content_resize').remove();
			jQuery('#wp-pp_readonly_content-media-buttons').width(jQuery('#pp_readonly_content-tmce').position().left-5);
			jQuery('#wp-pp_translate_content-media-buttons').width(jQuery('#pp_translate_content-tmce').position().left-5);
			jQuery('#qt_pp_translate_content_toolbar').css("text-align", "left");
			jQuery('#qt_pp_readonly_content_toolbar').css("text-align", "left");
	
	
			if(jQuery(window).height() > 520){
				temp_string = (jQuery(window).height()*0.90)*0.5;
				jQuery('.show_content_box_border').css('height','90%').css("margin-top", "-" + temp_string + "px"); 
			} else {
				jQuery('.show_content_box_border').height('420').css("margin-top", "-210px"); 
			}	

			if(aktive_resizer == 1){
				if(jQuery("#wp-pp_translate_content-wrap #qt_pp_translate_content_toolbar").is(":visible") == true) {
					etm_height_temp_toolbar_translate = jQuery('#wp-pp_translate_content-wrap #qt_pp_translate_content_toolbar').height();
				} else {
					etm_height_temp_toolbar_translate = jQuery('#wp-pp_translate_content-wrap .mce-toolbar-grp').height();
				}
				
				if(jQuery("#wp-pp_readonly_content-wrap #qt_pp_readonly_content_toolbar").is(":visible") == true) {
						etm_height_temp_toolbar_readonly = jQuery('#wp-pp_readonly_content-wrap #qt_pp_readonly_content_toolbar').height();
				} else {
						etm_height_temp_toolbar_readonly = jQuery('#wp-pp_readonly_content-wrap .mce-toolbar-grp').height();
				}


			
				etm_height_temp = jQuery('.show_content_box_border').height() - 300;
				jQuery('#pp_translate_content_ifr').height(etm_height_temp + 56 - etm_height_temp_toolbar_translate);		
				jQuery('#pp_readonly_content_ifr').height(etm_height_temp + 56 - etm_height_temp_toolbar_readonly);
				jQuery('#pp_translate_content').height(etm_height_temp + 56 - etm_height_temp_toolbar_translate);		
				jQuery('#pp_readonly_content').height(etm_height_temp + 56 - etm_height_temp_toolbar_readonly);				
				jQuery('#post_default_content_excerpts').height(etm_height_temp);		
				jQuery('#post_translatede_content_excerpts').height(etm_height_temp);	
				
				jQuery('#post_default_seo_meta_description').height(etm_height_temp);	
				jQuery('#post_translatede_meta_description').height(etm_height_temp);




				if(jQuery(window).width() > 1000){
					temp_string = (jQuery(window).width()*0.90)*0.5;
					jQuery('.show_content_box_border').css('width','90%').css("margin-left", "-" + temp_string + "px");
				} else {
					jQuery('.show_content_box_border').width('900').css("margin-left",'-450px');
				}
				
				if (navigator.appVersion.indexOf("MSIE") != -1){
					jQuery('#show_content_box_info textarea').each(function(){
						jQuery(this).css('height',(jQuery(this).parent().height()-10)+'px');	
					})
				}

				
			} else {
				etm_height_temp = jQuery('.show_content_box_border').height() - 150;
				jQuery('#show_content_box_info textarea').height(etm_height_temp/2);
			}
			
		});
}	
	
window.onresize = function(event) {
	if(aktive_resizer){
		resize_table();
	}
}