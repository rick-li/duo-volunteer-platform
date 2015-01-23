<?php
class unrealhuman_shower {
     
    var $default_id = 0;
    var $interval = 25;
    var $default_name = '';
    var $page = 0;
    
    var $button_sort_string;
    var $sort_col = '';
    var $sort_dir = '';
    
    var $css_data = '';
    
    var $retrive_fn = '';
    var $retrive_total = 0;
    var $etm_folder = '';
    var $post_tag = '';

    var $table_top;
    
    var $button_string;
    var $button_col;
    
    var $content;
    
    var $title;
    
    var $description;
    
    var $seachfield;
        
    // --------------------------- setup table --------------------------------
    function setup_table($data = ''){
        if(!empty($data) && is_object($data)){
            if(!empty($data->interval))
                $this->interval = $data->interval;
            
            if(!empty($data->page))
                $this->page = $data->page;

            if(!empty($data->sort_col))
                $this->sort_col = $data->sort_col;
                
            if(!empty($data->sort_dir))
                $this->sort_dir = $data->sort_dir; 
                
            if(!empty($data->retrive_fn))
                $this->retrive_fn = $data->retrive_fn; 
                
            if(!empty($data->retrive_total))
                $this->retrive_total = $data->retrive_total; 
                
            if(!empty($data->etm_folder))
                $this->etm_folder = $data->etm_folder; 
                
            if(!empty($data->post_tag))
                $this->post_tag = $data->post_tag;      
                
        }
    }
    
    function setup_title_description($title = '', $description = ''){
        if(!empty($title) ){
    		$this->title = $title;
    	}        

        if(!empty($description) ){
			$this->description =$description;
        } 
    } 
    
    function setup_seach_bar($tmp_data1,$tmp_data2,$tmp_data3,$tmp_data4,$tmp_data5,$tmp_data6){
	    $this->seachfield = true;
    }
       
    
    function setup_css ($css_data){
        $this->css_data = $css_data;
    }
    
    function set_table_data ($table_top,$table_data = ''){
        $this->table_top = $table_top;
    }
    
    
    function set_button($f_string,$f_col=''){
        $this->button_string = $f_string;
        $this->button_col = $f_col;
    }
    
    function set_sort_button($f_sort_string,$f_sort_colon=''){
        $this->button_sort_string = $f_sort_string;
    } 
    
    // --------------------------- show table --------------------------------
    function init($default_id = ''){
    	if(!empty($default_id)){
    		$this->default_id = $default_id;
    	}
        if ( !empty($this->css_data) && file_exists($this->css_data ) ) {
            echo '<link type="text/css" rel="stylesheet" href="' . $this->css_data.'" />';
        } else {
            echo '<link type="text/css" rel="stylesheet" href="' . plugins_url('default.css', __FILE__).'" />';
        }
        
        $this->create_script();
    }   
    
    
    
    // --------------------------- create table --------------------------------
    
    
    function create_script(){
        ?>
        <script>
            function loadScript(url, callback){
                var script = document.createElement("script")
                script.type = "text/javascript";
            
                if (script.readyState){  //IE
                    script.onreadystatechange = function(){
                        if (script.readyState == "loaded" || script.readyState == "complete"){
                            script.onreadystatechange = null;
                            callback();
                        }
                    };
                } else {  //Others
                    script.onload = function(){
                        callback();
                    };
                }
            
                script.src = url + '?date='+ new Date().getTime();
                document.getElementsByTagName("head")[0].appendChild(script);
            }


            
            loadScript("<?php echo plugins_url('tableJQ2.js', __FILE__); ?>", function(){
			  var uh_sh<?php echo $this->default_id; ?>; 
              var tmp = {'button_string':'<?php echo $this->button_string; ?>',
            		     'button_col':'<?php echo $this->button_col; ?>',
            		     'interval':'<?php echo $this->interval; ?>',
            		     'page':'<?php echo $this->page; ?>',
            		     'sort_col':'<?php echo $this->sort_col; ?>',
            		     'sort_dir':'<?php echo $this->sort_dir; ?>',
            		     'default_id':'<?php echo $this->default_id; ?>',
            		     'default_name':'<?php echo $this->default_name; ?>',
            		     'seachfield':'<?php echo $this->seachfield; ?>',
            		     'retrive_fn':'<?php echo $this->retrive_fn; ?>',
            		     'total':'<?php echo $this->retrive_total; ?>',
            		     'etm_folder':'<?php echo $this->etm_folder; ?>',
            		     'post_tag':'<?php echo $this->post_tag; ?>',
            		     'EASY_TRANSLATION_MANAGER_URL':'<?php echo EASY_TRANSLATION_MANAGER_URL; ?>',
            		     'EASY_TRANSLATION_MANAGER_WP_ADMIN':'<?php echo admin_url(); ?>',
            		     'table_top_array':<?php print json_encode(json_encode($this->table_top)); ?>,
            		     'current_object':uh_sh<?php echo $this->default_id; ?>}
            
				uh_sh<?php echo $this->default_id; ?> = new tableJQ();
				set_default(<?php echo $this->default_id; ?>,uh_sh<?php echo $this->default_id; ?>);	
				uh_sh<?php echo $this->default_id; ?>.tableJQset_data(tmp);
				tmp = '';
				uh_sh<?php echo $this->default_id; ?>.tableJQinit();
      });

        </script> 
        <?php
        
        unset($this->button_string);
        unset($this->button_col);        
        unset($this->interval);        
        unset($this->page);        
        unset($this->sort_col);
        unset($this->sort_dir);        
        unset($this->table_top);        
        unset($this->table_data);          
        
        echo '<div style="display: block;padding-bottom: 60px;clear:Both;position: relative;">';
        
        if(!empty($this->title)){
        	echo '<div>'.$this->title.'</div>';
        }
        if(!empty($this->description)){
        	echo '<div style="margin-bottom: -20px;">'.$this->description.'</div>';
        }    
        
        echo '<div id="uh_sh'.$this->default_name.$this->default_id.'indexTop" class="tableJQindex"></div>';
        echo '<div id="uh_sh'.$this->default_name.$this->default_id.'main"></div>';
        echo '<div id="uh_sh'.$this->default_name.$this->default_id.'indexBottom" class="tableJQindex"></div></div>';
        
    }
}

?>