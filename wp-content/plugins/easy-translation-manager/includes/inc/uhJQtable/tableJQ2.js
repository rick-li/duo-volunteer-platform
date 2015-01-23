 var uh_sh0,uh_sh1,uh_sh2,uh_sh3,uh_sh4,uh_sh5,uh_sh6,uh_sh7,uh_sh8,uh_sh9,uh_sh10;
 
 function set_default(default_id,object){
 	if(default_id == 0){
 		uh_sh0 = object;
 	} else if(default_id == 1){
 		uh_sh1 = object;
 	} else if(default_id == 2){ 
 		uh_sh2 = object; 
 	} else if(default_id == 3){
 		uh_sh3 = object; 	
 	} else if(default_id == 4){ 	
 		uh_sh4 = object; 
 	} else if(default_id == 5){ 
 		uh_sh5 = object; 	
 	} else if(default_id == 6){
 		uh_sh6 = object; 	
 	} else if(default_id == 7){
 		uh_sh7 = object; 	
 	} else if(default_id == 8){ 
 		uh_sh8 = object; 	
 	} else if(default_id == 9){ 
 		uh_sh9 = object; 	
	} else {
 		uh_sh10 = object;
	}	 	 	
 		
 }
 
 function tableJQ(div_set_id) {
 
 	var cData = new Object();
 	var self = this;
 	var table_data_array = new Array();
 	var first_time_load = true;
   

   this.tableJQinit = function(){
		create_display();
   }
   
   this.tableJQset_data = function(object){
	   self.cData = object;
	   
	   self.cData.seachtext = '';
	   
     if (typeof self.cData.default_name != 'undefined'){
	      self.cData.default_name = 'uh_sh';  
     }  
     
     if (typeof self.cData.default_name != 'undefined' && self.cData.default_name == ''){
	      self.cData.default_name = 'uh_sh';  
     }   
	   
	   
     if (typeof self.cData.default_id != 'undefined'){
	      self.cData.default_id = parseFloat(self.cData.default_id);  
     } 
     if (typeof self.cData.interval != 'undefined'){
	      self.cData.interval = parseFloat(self.cData.interval);  
     } 
     if (typeof self.cData.page != 'undefined'){
	      self.cData.page = parseFloat(self.cData.page);  
     } 
     
     if (typeof self.cData.total != 'undefined'){
	      self.cData.total = parseFloat(self.cData.total);  
     } 
	 
	 if (typeof self.cData.table_top_array != 'undefined'){
	    jQuery(document).ready(function($){
	    	self.cData.table_top_array = $.parseJSON(self.cData.table_top_array);
	    });  
	 } 
	 
     if (typeof self.cData.seachfield != 'undefined'){
	    self.cData.seachfield = self.cData.seachfield.replace(/--/g,"'");  
     } 
   }   
   
    this.retrive_page = function(){
        return self.cData.page;
        
    }

    this.change_page = function(tmp_page){
        self.cData.page = tmp_page;
		create_display();
        
    }
    
    this.sortArray = function(sort_col,sort_dir){
    	self.cData.sort_col = sort_col;
    	self.cData.sort_dir = sort_dir;
    	self.cData.page = 0;
		create_display();
    }
    
    function change_page_number(tmp_page){
    	if(isNumber(tmp_page)){
        	var tmp_array_total = self.cData.total;
        	var tmp_max_pages = Math.ceil(tmp_array_total/(self.cData.interval));
        	tmp_page = tmp_page-1;
        	
        	if(tmp_page > tmp_max_pages-1){
        		tmp_page = tmp_max_pages-1;
        	}
        
    	    if(tmp_page < 0){
    	    	tmp_page = 0;
       		}        
       		
        	self.cData.page = parseInt(tmp_page);
			create_display();
    	} else {
    		alert('Only numbers');
    	}
    }
    
    this.seach = function(tmp1,tmp2,tmp3,tmp4,tmp5,tmp6){
        jQuery(document).ready(function($){
			self.cData.seachtext = jQuery('.seachtypecgm').val();
			self.cData.page = 0;
			create_display();
	    })
    }
    
    function create_display(){

        jQuery(document).ready(function($){
        	if(self.first_time_load == false){
            	jQuery('#'+self.cData.default_name+self.cData.default_id+'main').parent().prepend('<div id="loadering" style="left: 0px; position: absolute; display: block; right: 0px; z-index: 500; top: 0px; bottom: 60px; background-color: rgba(241, 241, 241, 0.5);"><div style="width: 32px; height: 32px; margin-left: 10px; margin-top: 15px;"><img src="'+EASY_TRANSLATION_MANAGER_URL+'images/loader.gif"></div></div>'); 
        	}
        	
        	self.first_time_load = false;

        	jQuery.post(self.cData.EASY_TRANSLATION_MANAGER_WP_ADMIN+'?etm_data=true&etm_fn=page_loader_data',{'page':self.cData.page,'interval':self.cData.interval
        	,'sort_col':self.cData.sort_col,'sort_dir':self.cData.sort_dir,'etm_folder':self.cData.etm_folder,'post_tag':self.cData.post_tag,'retrive_fn':self.cData.retrive_fn,'seachtitle':self.cData.seachtext},function(data){
    			
    			if(data.R == 'OK'){
    				jQuery('#'+self.cData.default_name+self.cData.default_id+'main').parent().find('#loadering').remove();
	    			self.cData.total = parseInt(data.TOTAL);
	    			self.table_data_array = data.TMPDATA;
	    			create_tables();
	    			
    			}
    		},'json');
        });
    }
    
    function create_seach(){
	   return '<input type="text" class="seachtypecgm" onkeyup="seachloadingeach(this);" value="'+self.cData.seachtext+'" id="seachtypecgm" ><input value="Search" class="button-secondary" onClick="uh_sh'+self.cData.default_id+'.seach();return false" type="submit">';
    }
    
    
    function create_tables(){
        var tmp_content = create_data_fream();
        jQuery(document).ready(function($){
            $('#'+self.cData.default_name+self.cData.default_id+'main').html(tmp_content).css({'position' : 'relative', 'z-index' : '200', 'clear' : 'both'}); 
        });

        var tmp_index = create_index();  
        jQuery(document).ready(function($){
            $('#'+self.cData.default_name+self.cData.default_id+'indexTop').html(tmp_index).css({'z-index' : '300' , 'margin-bottom' : '5px'});
            
            if(typeof self.cData.seachfield != 'undefined' && self.cData.seachfield != ''){
            	$('#'+self.cData.default_name+self.cData.default_id+'indexTop').css({'margin-top' : '-30px'});
            }
            
            $('#'+self.cData.default_name+self.cData.default_id+'indexBottom').html(tmp_index); 
        });
    }

	function isNumber(o) {
    	return !isNaN(o);
	}


   

   
    function create_index(){

        var tmp_array_total = self.cData.total;
        var tmp_max_pages = Math.ceil(tmp_array_total/(self.cData.interval));
        var tmp_min;
        var tmp_max;
        var tmp_content = '';
        var tmp_func = self.cData.default_name+self.cData.default_id+'.change_page'
        var tmp_class = 'index_div'
        
        tmp_content += '<div class="tableJQunrealhuman-index">';
        
        tmp_content += '<span style="-moz-user-select: none;-webkit-user-select: none;" onselectstart="return false;" class="tableJQunrealhuman-index-num">';
        
        if(typeof self.cData.seachfield != 'undefined' && self.cData.seachfield != ''){
	       	 tmp_content += create_seach(); 
        }

        tmp_content += '</span>';
        
        tmp_content += '<span style="-moz-user-select: none;-webkit-user-select: none;" onselectstart="return false;" class="tableJQunrealhuman-index-num">';
        tmp_content += tmp_array_total +  ' items';
        tmp_content += '</span>';
        
        if(self.cData.page > 0){
        	tmp_content += '<span style="-moz-user-select: none;-webkit-user-select: none;" onselectstart="return false;" onclick="'+tmp_func+'(0)" class="tableJQunrealhuman-index-first-page">«</span>'; 
			tmp_content += '<span style="-moz-user-select: none;-webkit-user-select: none;" onselectstart="return false;" onclick="'+tmp_func+'('+(self.cData.page-1)+')" class="tableJQunrealhuman-index-prev-page">‹</span>'; 
        } else {
        	tmp_content += '<span style="-moz-user-select: none;-webkit-user-select: none;" onselectstart="return false;" class="tableJQunrealhuman-index-first-page-disabled">«</span>'; 
			tmp_content += '<span style="-moz-user-select: none;-webkit-user-select: none;" onselectstart="return false;" class="tableJQunrealhuman-index-prev-page-disabled">‹</span>'; 
        }
        
        tmp_content += '<span style="-moz-user-select: none;-webkit-user-select: none;" onselectstart="return false;" class="paging-input"><input class="tablejq_page" type="text" size="1" value="'+parseInt(self.cData.page+1)+'" onChange="'+self.cData.default_name+self.cData.default_id+'.change_page_number(this.value);" > of <span class="total-pages">'+tmp_max_pages+'</span></span>'; 
        
        if(self.cData.page+1 < tmp_max_pages){
            tmp_content += '<span style="-moz-user-select: none;-webkit-user-select: none;" onselectstart="return false;"  onclick="'+tmp_func+'('+parseInt(self.cData.page+1)+')" class="tableJQunrealhuman-index-next-page">›</span>'; 
            tmp_content += '<span style="-moz-user-select: none;-webkit-user-select: none;" onselectstart="return false;"  onclick="'+tmp_func+'('+parseInt(tmp_max_pages-1)+')" class="tableJQunrealhuman-index-last-page">»</span>';  
        } else {
            tmp_content += '<span style="-moz-user-select: none;-webkit-user-select: none;" onselectstart="return false;"   class="tableJQunrealhuman-index-next-page-disabled">›</span>'; 
            tmp_content += '<span style="-moz-user-select: none;-webkit-user-select: none;" onselectstart="return false;"   class="tableJQunrealhuman-index-last-page-disabled">»</span>'; 
        }
        
        tmp_content += '</div>';
        
        
        return tmp_content;
    }
   
   function create_data_fream(){
   		var tmp_str_2 = '';
        var count = 0;
        var tmp_content = '<table class="tableJQunrealhuman">';
        tmp_content += create_top_fream();
        
        
        if(self.table_data_array != ''){
            for (key in self.table_data_array) {
                count++;
                
                var row = self.table_data_array[key];
				if(typeof row == 'undefined'){
					
				} else if(typeof row['noteditible'] != 'undefined' && row['noteditible'] == '2'){  
                    tmp_content +='<tr class="table_row_vaiable_texdomain_edible"';                
                } else if(typeof row['noteditible'] != 'undefined' && row['noteditible'] == '1'){  
                    tmp_content +='<tr class="table_row_none_edible"';
                } else {
                    tmp_content +='<tr class="table_row_edible" onMouseOver="jQuery(this).attr(\'class\',\'table_row_edible_hover\');" onMouseOut="jQuery(this).attr(\'class\',\'table_row_edible\');" ';

                    if(self.cData.button_string != ''){
                    
                    	var button_string_tmp = self.cData.button_string;
                    	for (key in row){
                    		var tmp = row[key];
                    		if(typeof tmp != 'undefined' && tmp != null && !form_input_is_numeric(tmp)){
                    			tmp = tmp.replace('\'', "&rsquo;");
                    			tmp = tmp.replace('"', '&quot;');
                    		}
            				button_string_tmp = button_string_tmp.replace("["+key.toUpperCase()+"]", tmp);
            			}
                     
            			button_string_tmp = button_string_tmp.replace("[PAGE]", self.cData.page);
            			button_string_tmp = button_string_tmp.replace("[SORTDIR]", self.cData.sort_dir);
            			button_string_tmp = button_string_tmp.replace("[SORTCOL]", self.cData.sort_col);
                    
                            tmp_content +=' style="cursor:pointer;" onclick="'+button_string_tmp+'" ';  
                    } 
                    
                }
				if(typeof row != 'undefined'){
	                tmp_content += ' >';
	                    for(var j=0;j<self.cData.table_top_array.length;j++){
	                       tmp_str_2 = ''
	                       tmp_str_2 = row[self.cData.table_top_array[j]['backtitle']];
	                     	if(tmp_str_2 != 0){
	                     		if(!tmp_str_2){
	                     			tmp_str_2 = ''
	                     		}
	                     	}
	                    
						   tmp_content +='<td width="'+self.cData.table_top_array[j]['col_width']+'">'+tmp_str_2+'</td>'; 
	    				}
	                tmp_content +='</tr>';
                }
            }
		} else {
			tmp_content +='<tr><td colspan="'+(self.cData.table_top_array.length)+'">No records.</td></tr>';
		}
        
        
        tmp_content += '</table>';  
        tmp_content += '</div>'; 
        
		return tmp_content;
    } 

    function create_top_fream(){
        var tmp_content = '<thead><tr>';
            for(var i=0;i<self.cData.table_top_array.length;i++){
            	tmp_content += create_sort_buttons(self.cData.table_top_array[i]);
            }											
  		tmp_content += '</tr></thead>';
        
        return tmp_content;
    }

    function create_sort_buttons(set_col){
        var icon = '';
        var text = '';
        
        if(self.cData.sort_col != ''){
            if(set_col['backtitle'].toLowerCase() == self.cData.sort_col.toLowerCase()){
                if(self.cData.sort_dir == 'asc'){
                    icon = '<img style="margin-bottom:-2px" src="'+self.cData.EASY_TRANSLATION_MANAGER_URL+'images/asc.png">'; 
                } else {
                    icon = '<img style="margin-bottom:-2px" src="'+self.cData.EASY_TRANSLATION_MANAGER_URL+'images/desc.png">';
                }
            } else {
        		icon = '<img style="margin-bottom:-2px" src="'+self.cData.EASY_TRANSLATION_MANAGER_URL+'images/none_sort.png">';
        	}
        } else {
        	icon = '<img style="margin-bottom:-2px" src="'+self.cData.EASY_TRANSLATION_MANAGER_URL+'images/none_sort.png">';
        }


		text += '<th style="-moz-user-select: none;-webkit-user-select: none;" onselectstart="return false;"    width="'+set_col['col_width']+'" '; 


        if(set_col['sorteble']){
            
            	text += ' class="headrow_on" ';
            	
                if(self.cData.sort_dir.toLowerCase() == 'asc' && set_col['backtitle'].toLowerCase() == self.cData.sort_col.toLowerCase()){
                    tmp_sort_dir = 'desc';
                } else {
                    tmp_sort_dir = 'asc';  
                }
                
                tmp_backtitle = set_col['backtitle']
                if(tmp_backtitle == 'id'){
	               tmp_backtitle = 'ID'; 
                }
                
           text += 'onClick="'+self.cData.default_name+self.cData.default_id+'.sortArray(\''+tmp_backtitle+'\',\''+tmp_sort_dir+'\');"';
        } else {
			text += ' class="headrow_off" ';
        }
        
        
        
        
        
        text += ' >';
        
        if(icon != ''){
        	text += icon + ' ';
        }
        text += set_col['title'];
        
        text += '</th>';
        
        return text;              
    }
    
  function form_input_is_numeric(input){
    return !isNaN(input);
  }
  
}
 
