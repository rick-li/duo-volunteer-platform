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

    var $table_data;
    var $table_top;
    
    var $button_string;
    var $button_col;
    
    var $content;
    
    var $title;
    
    var $description;
    
    var $seachfeild;
        
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
	    
	    if(empty($_POST['seachtitle'])){
		    $_POST['seachtitle'] = '';
	    }

	    $this->seachfeild = '<input ondblclick="seachloadingdb();" type="text" class="seachtypecgm" onkeyup="seachloadingeach(this);" value="'.$_POST['seachtitle'].'" id="seachtypecgm" ><input value="Search" class="button-secondary" onClick="seachloadingContent('.$tmp_data1.',--'.$tmp_data2.'--,--'.$tmp_data3.'--,--'.$tmp_data4.'--,--'.$tmp_data5.'--,--'.$tmp_data6.'--);return false" type="submit">';
    }
       
    
    function setup_css ($css_data){
        $this->css_data = $css_data;
    }
    
    function set_table_data ($table_top,$table_data = ''){
        $this->table_top = $table_top;
        $this->table_data = $table_data;
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
            
                script.src = url;
                document.getElementsByTagName("head")[0].appendChild(script);
            }
            
            
            
            var button_string<?php echo $this->default_id; ?> = '<?php echo $this->button_string; ?>';
            var button_col<?php echo $this->default_id; ?> = '<?php echo $this->button_col; ?>';  
            
            var interval<?php echo $this->default_id; ?> = '<?php echo $this->interval; ?>';
            var page<?php echo $this->default_id; ?> = '<?php echo $this->page; ?>';
            var sort_col<?php echo $this->default_id; ?> = '<?php echo $this->sort_col; ?>';
            var sort_dir<?php echo $this->default_id; ?> = '<?php echo $this->sort_dir; ?>'; 
            var default_id<?php echo $this->default_id; ?> = '<?php echo $this->default_id; ?>'; 
            var default_name<?php echo $this->default_id; ?> = '<?php echo $this->default_name; ?>';             
            var seachfeild<?php echo $this->default_id; ?> = '<?php echo $this->seachfeild; ?>';         
            
            var EASY_TRANSLATION_MANAGER_URL<?php echo $this->default_id; ?> = '<?php echo EASY_TRANSLATION_MANAGER_URL; ?>';
            var table_top_array<?php echo $this->default_id; ?>;
            var table_data_array<?php echo $this->default_id; ?>;
            jQuery(document).ready(function($){
                table_top_array<?php echo $this->default_id; ?> = $.parseJSON(<?php print json_encode(json_encode($this->table_top)); ?>);
                table_data_array<?php echo $this->default_id; ?> = $.parseJSON(<?php print json_encode(json_encode($this->table_data)); ?>);
                
                if(!table_top_array<?php echo $this->default_id; ?>)
                	table_top_array<?php echo $this->default_id; ?> = [];
                
                if(!table_data_array<?php echo $this->default_id; ?>)
                	table_data_array<?php echo $this->default_id; ?> = [];
                  
            });
            
            
            
            
            
            loadScript("<?php echo plugins_url('tableJQ1.js', __FILE__); ?>", function(){
				var uh_sh<?php echo $this->default_id; ?>;
				uh_sh<?php echo $this->default_id; ?> = new tableJQ();
uh_sh<?php echo $this->default_id; ?>.tableJQset_data(button_string<?php echo $this->default_id; ?>,
button_col<?php echo $this->default_id; ?>,
interval<?php echo $this->default_id; ?>,
page<?php echo $this->default_id; ?>,
sort_col<?php echo $this->default_id; ?>,
sort_dir<?php echo $this->default_id; ?>,
default_id<?php echo $this->default_id; ?>,
EASY_TRANSLATION_MANAGER_URL<?php echo $this->default_id; ?>,
table_top_array<?php echo $this->default_id; ?>,
table_data_array<?php echo $this->default_id; ?>,seachfeild<?php echo $this->default_id; ?>);
				set_default(default_id<?php echo $this->default_id; ?>,uh_sh<?php echo $this->default_id; ?>);
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
        
        echo '<div style="display: block;padding-bottom: 60px;">';
        
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