<?php
	
function booked_avatar($user_id,$size = 150){
	if (get_user_meta($user_id, 'avatar',true)):
		return wp_get_attachment_image( get_user_meta($user_id,'avatar',true), array($size,$size) );
	else :
		return get_avatar($user_id, $size);
	endif;
}

function booked_convertTime($time)
{
	settype($time, 'integer');
    if ($time < 1) {
        return;
    }
    $hours = lz(floor($time / 60));
    $minutes = lz(($time % 60));
    return $hours.':'.$minutes;
}

// lz = leading zero
function lz($num)
{
    return (strlen($num) < 2) ? "0{$num}" : $num;
}
	
function booked_pending_appts_count(){
	$args = array(
	   'posts_per_page' => -1,
	   'post_status' => 'draft',
	   'post_type' => 'booked_appointments',
	);
	$pending_count_query = new WP_Query($args);
	return $pending_count_query->found_posts;
}

function booked_mailer($to,$subject,$message){

	add_filter('wp_mail_content_type', 'booked_set_html_content_type');
	
	$booked_email_logo = get_option('booked_email_logo');
	if ($booked_email_logo):
		$logo = '<img src="'.$booked_email_logo.'" style="max-width:100%; height:auto; display:block; margin:10px 0 20px;">';
	else :
		$logo = '';	
	endif;
	
	$template = file_get_contents('email-templates/default.html', true);
	$filter = array('%content%','%logo%');
	$replace = array(wpautop($message),$logo);	
	$message = str_replace($filter, $replace, $template);
	
	wp_mail($to,$subject,$message);
	
	remove_filter('wp_mail_content_type','booked_set_html_content_type');
	
}

function booked_set_html_content_type() {
	return 'text/html';
}
	
function booked_registration_validation( $username, $email )  {
	global $reg_errors;
	$reg_errors = new WP_Error;
	$errors = array();
	
	if ( empty( $username ) || empty( $email ) ) {
	    $reg_errors->add('field', __('All fields are required to register.','booked'));
	}
	
	if ( 4 > strlen( $username ) ) {
	    $reg_errors->add( 'username_length', __('That username is too short; at least 4 characters is required.','booked'));
	}
	
	if ( username_exists( $username ) ) {
    	$reg_errors->add('user_name', __('That username already exists.','booked'));
    }
    
    if ( ! validate_username( $username ) ) {
	    $reg_errors->add( 'username_invalid', __('That username is not valid.'.$username,'booked'));
	}    
    
    if ( !is_email( $email ) ) {
	    $reg_errors->add( 'email_invalid', __('That email address is not valid.','booked'));
	}
	
	if ( email_exists( $email ) ) {
	    $reg_errors->add( 'email', __('That email is already in use.','booked'));
	}
	
	if ( is_wp_error( $reg_errors ) ) {
	
		foreach ( $reg_errors->get_error_messages() as $error ) {
	    	$errors[] = $error;
	    }
	
	}
	
	return $errors;

}