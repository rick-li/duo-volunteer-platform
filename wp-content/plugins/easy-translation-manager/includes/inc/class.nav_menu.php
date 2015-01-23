<?php
class easy_translation_manager_nav_menu {
	function easy_translation_manager_nav_menu(){
	    global $easy_translation_manager_plugin;
		add_action('admin_init', array(&$this,'add_menu_meta_box') );
		add_filter( 'wp_nav_menu_objects' , array(&$this,'wp_loginout_in_menu'), 10, 2);	
	}
	
	function wp_loginout_in_menu($data1 = '',$data2 = ''){
		if(!empty($data1)){
			foreach($data1 as $tmp_k => $tmp_d){
				$data_tmp = '';
				if(!empty($tmp_d->xfn)){
					$data_tmp = explode('-', $tmp_d->xfn);
				}
				
				if(!empty($data_tmp) && !empty($data_tmp[0]) && $data_tmp[0] == 'etm'){
					$data1[$tmp_k]->title = do_shortcode('[etm_menu flag="'.$data_tmp[1].'" display="'.$data_tmp[2].'" hidearrow="'.$data_tmp[3].'" layout="'.$data_tmp[4].'" ]');
					$data1[$tmp_k]->url = '';
					$data1[$tmp_k]->xfn = '';
				}
			}
		}
		return $data1;
			
	}

	function add_menu_meta_box() {
		add_meta_box( 'etm-menu_lang', __( 'ETM Language', 'default' ), array( $this, 'content_menu_meta_box'), 'nav-menus',    'side', 'high' );
		add_filter('wp_setup_nav_menu_item',array(&$this,'custom_nav_item')  );
	}
	
	
	function custom_nav_item($menu_item) {
		$data_tmp = '';
		if(!empty($menu_item->xfn)){
			$data_tmp = explode('-', $menu_item->xfn);
		}
		
		if(!empty($data_tmp[0]) && $data_tmp[0] == 'etm'){
			$menu_item->type = 'custom';
			$menu_item->etm_check = 'etm';
			$menu_item->type_label = 'ETM';
		}
		
	    return $menu_item;
	}




	
	function content_menu_meta_box() {
		wp_enqueue_script( 'etm-nav-menus', EASY_TRANSLATION_MANAGER_URL.'js/nav-menu.js', array(),'1.0.0');
		?>
		<div id="posttype-etm" class="posttypediv">
		<div id="tabs-panel-etm" class="tabs-panel tabs-panel-active">
			<ul id ="etm-ul" class="categorychecklist form-no-clear">
			<?php
			
			$menu_types = array('3'=>__('Bouncing List','etm'),
				   '4'=>__('Box Slide','etm'),	
				   '5'=>__('Rotating Bars','etm'),
				   '6'=>__('Fluid Grid','etm'),
				   '7'=>__('Responsive Circle','etm'),
				   '0'=>__('Basic Drop-down list','etm'),
				   '1'=>__('Side-by-Side','etm'),
				   '2'=>__('Side-by-Side (Remove current flag)','etm')			
			);
			
				foreach($menu_types as $_tmp_k => $_tmp_d){
					echo '<li>';
						echo '<label class="menu-item-title">';
							echo '<input type="checkbox" class="menu-item-checkbox" name="menu-item['.($_tmp_k+1).'][menu-item-object-id]" value="'.($_tmp_k+1).'">'.$_tmp_d;
						echo '</label>';
						echo '<input type="hidden" class="menu-item-type" name="menu-item['.($_tmp_k+1).'][menu-item-type]" value="etm">';
						echo '<input type="hidden" class="menu-item-xfn" name="menu-item['.($_tmp_k+1).'][menu-item-xfn]" value="etm-0-0-0-'.$_tmp_k.'">';
						echo '<input type="hidden" class="menu-item-title" name="menu-item['.($_tmp_k+1).'][menu-item-title]" value="'.$_tmp_d.'">';
						echo '<input type="hidden" class="menu-item-url" name="menu-item['.($_tmp_k+1).'][menu-item-url]" value="">';
						echo '<input type="hidden" class="menu-item-classes" name="menu-item['.($_tmp_k+1).'][menu-item-classes]" value="class">';
					echo '</li>';
				}
			?>
			</ul>
		</div>
		<p class="button-controls">
			<span class="list-controls">
				<a href="/wordpress/wp-admin/nav-menus.php?page-tab=all&amp;selectall=1#posttype-etm" class="select-all">Select All</a>
			</span>
			<span class="add-to-menu">
				<input type="submit" class="button-secondary submit-add-to-menu right" value="Add to Menu" name="add-post-type-menu-item" id="submit-posttype-etm">
				<span class="spinner"></span>
			</span>
		</p>
		</div>
		<?php 
	}	
	
}	
?>