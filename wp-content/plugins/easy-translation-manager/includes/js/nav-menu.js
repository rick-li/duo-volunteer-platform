var etm_checkup_time = false;

jQuery(document).ready(function($){
	etm_setup_menu_structure();
	jQuery( ".metabox-holder #submit-posttype-etm").click(function() {
		etm_checkup_time = false;
		etm_check_up_if_loaded();
	});
	jQuery( "#etm-menu_lang").removeClass('hide-if-js');
});



function etm_check_up_if_loaded(){
	setTimeout(function(){
    	etm_setup_menu_structure();
    	console.log('test');
    	if(etm_checkup_time == false){
	    	etm_check_up_if_loaded();
    	}
    },300); 
}


function etm_setup_menu_structure(){
	jQuery(document).ready(function($){
		jQuery('#wpbody-content .menu-edit #menu-to-edit .menu-item').each(function(){
			if(jQuery(this).find('.menu-item-bar .menu-item-handle .item-controls .item-type').html() == 'ETM' && jQuery(this).find('.edit-menu-item-flagsize').length == 0){
						
				var settings_location = jQuery(this).find('.menu-item-settings');
				jQuery(settings_location).find('.field-url').hide();
				jQuery(settings_location).find('.description').hide();			
				jQuery(settings_location).find('.field-xfn').hide();
				jQuery(settings_location).find('.field-css-classes').hide();	
				jQuery(settings_location).find('.field-link-target').hide();	
				
				
				
				flag_text = '<p class="description description-thin"><label>Flag size<br><select onchange="etm_change_drop_down(this,1,\'.field-xfn .edit-menu-item-xfn\');return false;" class="widefat edit-menu-item-flagsize" id="edit-menu-item-flagsize-124">';
				flag_text += '<option value="0">Small</option>';
				flag_text += '<option value="1">Medium</option>';
				flag_text += '<option value="2">Large</option>';
				flag_text += '<option value="3">X-Large</option>';
				flag_text += '</select></label></p>';
				
				display_text = '<p class="description description-thin"><label>Display type<br><select onchange="etm_change_drop_down(this,2,\'.field-xfn .edit-menu-item-xfn\');return false;" class="widefat edit-menu-item-displaytype" id="edit-menu-item-displaytype-124">';
				display_text += '<option value="0">Show flag and text</option>';
				display_text += '<option value="1">Show only flag</option>';
				display_text += '<option value="2">Show only text</option>';
				display_text += '</select></label></p>';
				
				hide_arrow_text = '<p class="description description-wide"><label><input onchange="etm_change_drop_down(this,3,\'.field-xfn .edit-menu-item-xfn\');return false;" type="checkbox" class="edit-menu-item-hide-arrow" id="edit-menu-item-hide-arrow-124" value="1"> Hide arrow<br></label></p>';
	
				jQuery(settings_location).prepend(hide_arrow_text);
				jQuery(settings_location).prepend(display_text);
				jQuery(settings_location).prepend(flag_text);
				
				
				tmp_values = jQuery(settings_location).find('.field-xfn .edit-menu-item-xfn').val();
				tmp_values = tmp_values.split("-"); 
				
				if(tmp_values[1] > 0 ){
					jQuery(settings_location).find('.edit-menu-item-flagsize').val(tmp_values[1]);
				}
				if(tmp_values[2] > 0 ){
					jQuery(settings_location).find('.edit-menu-item-displaytype').val(tmp_values[2]);
				}
	
				if(tmp_values[3] == 1 ){
					jQuery(settings_location).find('.edit-menu-item-hide-arrow').attr('checked', true);
				}
				
				etm_checkup_time = true;	
			}	
		}) 
	});
}

function etm_change_drop_down(tmp_this,tmp_pos,tmp_return){
	tmp_values = jQuery(tmp_this).parent().parent().parent().find('.field-xfn .edit-menu-item-xfn').val();
	tmp_values = tmp_values.split("-");
	
	if(tmp_pos == 3){
		if(jQuery(tmp_this).is(':checked')){
			tmp_values[tmp_pos] = 1
		} else {
			tmp_values[tmp_pos] = 0
		}
	} else {
		tmp_values[tmp_pos] = jQuery(tmp_this).val();	
	}
	
	return_string = '';
	for(var i=0;i<tmp_values.length;i++){
		if(return_string != ''){
			return_string += '-';
		}
		return_string += tmp_values[i];
	}
	jQuery(tmp_this).parent().parent().parent().find('.field-xfn .edit-menu-item-xfn').val(return_string);
}