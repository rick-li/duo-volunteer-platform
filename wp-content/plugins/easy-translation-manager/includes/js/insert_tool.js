jQuery(document).ready(function($){
	if( $('#etm-insert-tool-trigger').length > 0 && $('#etm-insert-tool').length > 0){
		$('#etm-insert-tool-trigger').click(function(e){
			$('#etm-insert-tool').css('top', $(document).scrollTop() );
			$('#etm_chart_selected').val('').change();	
			$('#etm-insert-tool').fadeIn();		
		});
		
		$('#etm-insert-tool').find('.etm-close-icon-a').click(function(e){
			$('#etm-insert-tool').fadeOut();	
		});
	}
});


function etm_input_change_value(){
	jQuery(document).ready(function($){
		var etm_border = jQuery('#etm_border').val();
		var etm_vspace = jQuery('#etm_vspace').val();
		var etm_hspace = jQuery('#etm_hspace').val();
		var str = '';
	
		if(etm_border != '' && !isNaN(etm_border)){
			str += 'border: '+etm_border+'px solid black;';
		}	
		if(etm_vspace != '' && etm_hspace == '' && !isNaN(etm_vspace)){
			str += 'margin-top: '+etm_vspace+'px; margin-bottom: '+etm_vspace+'px;';
		} else if(etm_vspace == '' && etm_hspace != '' && !isNaN(etm_hspace)){
			str += 'margin-right: '+etm_hspace+'px; margin-left: '+etm_hspace+'px;';
		} else if(etm_vspace != '' && etm_hspace != '' && !isNaN(etm_hspace) && !isNaN(etm_vspace)){
			if(etm_vspace == etm_hspace){
				str += 'margin: '+etm_hspace+'px;';
			} else {
				str += 'margin: '+etm_vspace+'px '+etm_hspace+'px;';
			}
		}
	
		jQuery('#etm_style').val(str);
	
	});
}


function insert_etm_shortcode(){
	jQuery(document).ready(function($){
	
		var etm_width = jQuery('#etm_desing_menu_width').val();                 
		var etm_aligment = jQuery('#etm_desing_menu_alignment option:selected').val();		
		var etm_display = jQuery('#etm_desing_menu_info option:selected').val();	 	
		var etm_flag = jQuery('#etm_desing_menu_flag_size option:selected').val();		
		var etm_layout = jQuery('#etm_desing_menu_type').val();		
		var etm_hidearrow = jQuery('#etm_hidearrow').is(':checked');	
    
	
    	var str = '';
		var str_class = '';
		var str_style = '';
		var str_radio_class = ''; 
		
		str = jQuery('#etm_shortcode').html();
		str = str.replace(']','');
		
		str_class = jQuery('#etm_class').val();
		str_style = jQuery('#etm_style').val();
		
		str_radio_class = jQuery("#etm_radio_list input[name=etm_align]:checked").val();

		if(str_class != ''){
			str_class = str_class + ' ';
			str += ' class="'+str_class+'" ';
		}


		if(str_style != ''){
			str += ' style="' +str_style+'" ';
		}	
		
		if(etm_width != ''){
			str += ' width="' +etm_width+'" ';
		}		
		
		if(str_radio_class != ''){
			str += ' aligment="' +str_radio_class+'" ';
		}		
		
		if(etm_display != ''){
			str += ' display="' +etm_display+'" ';
		}
		
		if(etm_flag != ''){
			str += ' flag="' +etm_flag+'" ';
		}		
		
		if(etm_layout != ''){
			str += ' layout="' +etm_layout+'" ';
		}	
		if(etm_hidearrow != ''){
			str += ' hidearrow="1" ';
		}
			
		
		str += ']';

		if(str){
			send_to_editor(str);
			var ed;
			if ( typeof tinyMCE != 'undefined' && ( ed = tinyMCE.activeEditor ) && !ed.isHidden() ) {
				ed.setContent(ed.getContent());
			}
			$('#etm-insert-tool').fadeOut();
		} else {
			alert('Pleas choose a gallery');
		}
	});
}
