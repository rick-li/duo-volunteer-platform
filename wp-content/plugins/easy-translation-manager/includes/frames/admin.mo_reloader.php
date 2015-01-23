<?php
global $wpdb,$userdata,$etm_folder,$etm_tag;

$etm_folder = $_POST['etm_folder'];
$etm_tag = $_POST['etm_tag'];

if(empty($etm_folder) || empty($etm_tag) ){
	etm_send_error_die('Missing parameter.'); 
}

if($etm_tag =='addon'){
 	$upload_dir = wp_upload_dir();
	$folder_url = $upload_dir['basedir']."/".$etm_folder."/";
} else {
	$folder_url = ABSPATH."wp-content/".$etm_tag."s/".$etm_folder."/";
}


etm_tools_recurseDir($folder_url,true);

$response = array(
    'R'	=> 'OK',
    'url' => $folder_url,
    'etm_tag' => $_POST['etm_tag'],
    'etm_folder' => $_POST['etm_folder']   
);

die(json_encode($response));
?>