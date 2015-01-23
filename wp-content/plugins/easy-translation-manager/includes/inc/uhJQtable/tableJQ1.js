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
 
 function check_default(default_id,default_name){
 	alert(uh_sh);
 } 
 
 function tableJQ(div_set_id) {
 
 	var default_name = 'uh_sh';
 	var default_id = ''; 	
	var uh_sh;
    var button_string = '';
    var button_col = '';
    var seachfield = '';
            
    var interval = '';
    var page = '';
    var sort_col = '';
    var sort_dir = '';
            
    var EASY_TRANSLATION_MANAGER_URL = '';
    var table_top_array = '';
    var table_data_array = '';
   

   this.tableJQinit = function(tmp_uh_sh){
   		uh_sh = tmp_uh_sh;
        myObjectBubbleSort2(table_data_array,sort_col,sort_dir);
		create_display();
        
   }
   
   this.tableJQset_data = function(tmp_button_string,tmp_button_col,tmp_interval,tmp_page,tmp_sort_col,tmp_sort_dir,tmp_default_id,tmp_EASY_TRANSLATION_MANAGER_URL,tmp_table_top_array,tmp_table_data_array,tmp_seachfield){

	seachfield = tmp_seachfield
	
	button_string = tmp_button_string;
	button_col = tmp_button_col;
	interval = tmp_interval;
	page = tmp_page;
	sort_col = tmp_sort_col;
	sort_dir = tmp_sort_dir;
	default_id = tmp_default_id;	
	
	default_name
	EASY_TRANSLATION_MANAGER_URL = tmp_EASY_TRANSLATION_MANAGER_URL;
	table_top_array = tmp_table_top_array;
	table_data_array = tmp_table_data_array;
        
   
        
        seachfield = seachfield.replace(/--/g,"'"); 
        
        
        
        
   }   
   
    this.retrive_page = function(){
        return page;
        
    }
   
   
    this.change_page = function(tmp_page){
        page = tmp_page;
		create_display();
        
    }
    
    function change_page_number(tmp_page){
    	if(isNumber(tmp_page)){
        	var tmp_array_total = table_data_array.length;
        	var tmp_max_pages = Math.ceil(tmp_array_total/(interval));
        	tmp_page = tmp_page-1;
        	
        	if(tmp_page > tmp_max_pages-1){
        		tmp_page = tmp_max_pages-1;
        	}
        
    	    if(tmp_page < 0){
    	    	tmp_page = 0;
       		}        
        
        
    	
        	page = parseInt(tmp_page);
			create_display();
    	} else {
    		alert('Only numbers');
    	}

    }
    
    
    
    function create_display(){
        var tmp_content = create_data_fream();
        jQuery(document).ready(function($){
            $('#'+default_name+default_id+'main').html(tmp_content); 
        });

        var tmp_index = create_index2();  
        jQuery(document).ready(function($){
            $('#'+default_name+default_id+'indexTop').html(tmp_index);
            $('#'+default_name+default_id+'indexBottom').html(tmp_index); 
        });
    }
  
   
    this.sortArray = function(set_sort_col,set_sort_dir){
        sort_col = set_sort_col;
        sort_dir = set_sort_dir;
        myObjectBubbleSort2(table_data_array,set_sort_col,set_sort_dir);
        var tmp_content = create_data_fream();
        jQuery(document).ready(function($){
            $('#'+default_name+default_id+'main').html(tmp_content); 
        });
   }
   
   
	function isNumber(o) {
    	return !isNaN(o);
	}


   
	function myObjectBubbleSort2(arr,type_index,type_dir){
		if (arr){
        	var length = arr.length;
        	if(length > 0 && arr[0][type_index] != undefined){ 
        		for (var i=0; i<(length+1); i++){
            		for (var j=1; j<length; j++){
            		
            		var tmp1;
            		var tmp2;
            		
            		
            		
            		if(isNumber(arr[j][type_index])){
            			tmp1 = parseInt(arr[j][type_index]);
            		} else {
            			tmp1 = arr[j][type_index].toLowerCase();
            		}
            		
            		if(isNumber(arr[j-1][type_index])){

            			tmp2 = parseInt(arr[j-1][type_index]);
            		} else {
            			tmp2 = arr[j-1][type_index].toLowerCase();
            		}		
      
                		if(type_dir == 'asc'){
                    		if (tmp1 > tmp2) {
                    		    var dummy = arr[j-1];
                        		arr[j-1] = arr[j];
                        		arr[j] = dummy;
                    		}
                		} else {
                    		if (tmp1 < tmp2) {
                    		    var dummy = arr[j-1];
                        		arr[j-1] = arr[j];
                        		arr[j] = dummy;
                 		   	}
                		}
            		}
        		}
        	}
        }  
	}
   
   
    function myObjectBubbleSort(arrayName,type_index,type_dir) {
        var length = arrayName.length
        for (var i=0; i<(length-1); i++){
            for (var j=i+1; j<length; j++){
                if(type_dir == 'asc'){
                    if (arrayName[j][type_index] > arrayName[i][type_index]) {
                        var dummy = arrayName[i];
                        arrayName[i] = arrayName[j];
                        arrayName[j] = dummy;
                    }
                } else {
                    if (arrayName[j][type_index] < arrayName[i][type_index]) {
                        var dummy = arrayName[i];
                        arrayName[i] = arrayName[j];
                        arrayName[j] = dummy;
                    }
                }

            }
        }
    }
    
   
    function create_index2(){

        var tmp_array_total = table_data_array.length;
        var tmp_max_pages = Math.ceil(tmp_array_total/(interval));
        var tmp_min;
        var tmp_max;
        var tmp_content = '';
        var tmp_func = default_name+default_id+'.change_page'
        var tmp_class = 'index_div'
        
        tmp_content += '<div class="tableJQunrealhuman-index">';
        
        tmp_content += '<span style="-moz-user-select: none;-webkit-user-select: none;" onselectstart="return false;" class="tableJQunrealhuman-index-num">';
        tmp_content += seachfield;
        tmp_content += '</span>';
        
        tmp_content += '<span style="-moz-user-select: none;-webkit-user-select: none;" onselectstart="return false;" class="tableJQunrealhuman-index-num">';
        tmp_content += tmp_array_total +  ' items';
        tmp_content += '</span>';
        
        if(page > 0){
        	tmp_content += '<span style="-moz-user-select: none;-webkit-user-select: none;" onselectstart="return false;" onclick="'+tmp_func+'(0)" class="tableJQunrealhuman-index-first-page">«</span>'; 
			tmp_content += '<span style="-moz-user-select: none;-webkit-user-select: none;" onselectstart="return false;" onclick="'+tmp_func+'('+(page-1)+')" class="tableJQunrealhuman-index-prev-page">‹</span>'; 
        } else {
        	tmp_content += '<span style="-moz-user-select: none;-webkit-user-select: none;" onselectstart="return false;" class="tableJQunrealhuman-index-first-page-disabled">«</span>'; 
			tmp_content += '<span style="-moz-user-select: none;-webkit-user-select: none;" onselectstart="return false;" class="tableJQunrealhuman-index-prev-page-disabled">‹</span>'; 
        }
        
        tmp_content += '<span style="-moz-user-select: none;-webkit-user-select: none;" onselectstart="return false;" class="paging-input"><input class="tablejq_page" type="text" size="1" value="'+parseInt(page+1)+'" onChange="'+default_name+default_id+'.change_page_number(this.value);" > of <span class="total-pages">'+tmp_max_pages+'</span></span>'; 
        
        if(page+1 < tmp_max_pages){
            tmp_content += '<span style="-moz-user-select: none;-webkit-user-select: none;" onselectstart="return false;"  onclick="'+tmp_func+'('+parseInt(page+1)+')" class="tableJQunrealhuman-index-next-page">›</span>'; 
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
        
        var tmp_array_total = table_data_array.length;
   
        var tmp_min = interval*(page);
        var tmp_max = interval*(page+1)
        if(tmp_max>= tmp_array_total){
            tmp_max = tmp_array_total;
        }
        
        
        if(table_data_array != ''){
            for(var i=tmp_min; i<tmp_max;i++){
                count++;
                
                var row = table_data_array[i];


                if(row['noteditible'] == '2'){  
                    tmp_content +='<tr class="table_row_vaiable_texdomain_edible"';                
                } else if(row['noteditible'] == '1'){  
                    tmp_content +='<tr class="table_row_none_edible"';
                } else {
                    tmp_content +='<tr class="table_row_edible" onMouseOver="jQuery(this).attr(\'class\',\'table_row_edible_hover\');" onMouseOut="jQuery(this).attr(\'class\',\'table_row_edible\');" ';

                    if(button_string != ''){
                    
                    	var button_string_tmp = button_string;
                    	for (key in row){
                    		var tmp = row[key];
                    		if(tmp != null && !form_input_is_numeric(tmp)){
                    			tmp = tmp.replace('\'', "&rsquo;");
                    			tmp = tmp.replace('"', '&quot;');
                    		}
            				button_string_tmp = button_string_tmp.replace("["+key.toUpperCase()+"]", tmp);
            			}
                     
            			button_string_tmp = button_string_tmp.replace("[PAGE]", page);
            			button_string_tmp = button_string_tmp.replace("[SORTDIR]", sort_dir);
            			button_string_tmp = button_string_tmp.replace("[SORTCOL]", sort_col);
                    
                            tmp_content +=' style="cursor:pointer;" onclick="'+button_string_tmp+'" ';  
                    } 
                    
                }

                tmp_content += ' >';
                    for(var j=0;j<table_top_array.length;j++){
                       tmp_str_2 = ''
                       tmp_str_2 = row[table_top_array[j]['backtitle']];
                     	if(tmp_str_2 != 0){
                     		if(!tmp_str_2){
                     			tmp_str_2 = ''
                     		}
                     	}
                    
					   tmp_content +='<td width="'+table_top_array[j]['col_width']+'">'+tmp_str_2+'</td>'; 
    				}
                tmp_content +='</tr>';
            }
		} else {
			tmp_content +='<tr><td colspan="'+(table_top_array.length)+'">No records.</td></tr>';
		}
        
        
        tmp_content += '</table>';  
        tmp_content += '</div>'; 
        
		return tmp_content;
    } 

    function create_top_fream(){
        var tmp_content = '<thead><tr>';
            for(var i=0;i<table_top_array.length;i++){
            	tmp_content += create_sort_buttons(table_top_array[i]);
            }											
  		tmp_content += '</tr></thead>';
        
        return tmp_content;
    }

    function create_sort_buttons(set_col){
        var icon = '';
        var text = '';
        
        if(sort_col != ''){
            if(set_col['backtitle'].toLowerCase() == sort_col.toLowerCase()){
                if(sort_dir == 'asc'){
                    icon = '<img style="margin-bottom:-2px" src="'+EASY_TRANSLATION_MANAGER_URL+'images/asc.png">'; 
                } else {
                    icon = '<img style="margin-bottom:-2px" src="'+EASY_TRANSLATION_MANAGER_URL+'images/desc.png">';
                }
            } else {
        		icon = '<img style="margin-bottom:-2px" src="'+EASY_TRANSLATION_MANAGER_URL+'images/none_sort.png">';
        	}
        } else {
        	icon = '<img style="margin-bottom:-2px" src="'+EASY_TRANSLATION_MANAGER_URL+'images/none_sort.png">';
        }


		text += '<th style="-moz-user-select: none;-webkit-user-select: none;" onselectstart="return false;"    width="'+set_col['col_width']+'" '; 


        if(set_col['sorteble']){
            
            	text += ' class="headrow_on" ';
            	
                if(sort_dir.toLowerCase() == 'asc' && set_col['backtitle'].toLowerCase() == sort_col.toLowerCase()){
                    tmp_sort_dir = 'desc';
                } else {
                    tmp_sort_dir = 'asc';  
                }
           text += 'onClick="'+default_name+default_id+'.sortArray(\''+set_col['backtitle']+'\',\''+tmp_sort_dir+'\');"';
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
 
