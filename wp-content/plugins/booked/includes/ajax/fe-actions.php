<?php

add_action('get_header', 'bp_fe_ajax_callbacks', 11);
function bp_fe_ajax_callbacks() {
	
	if (isset($_GET['action']) && $_GET['action'] == 'add_appt' && isset($_GET['customer_type']))
	{
	
		$date = $_GET['date'];
		$timestamp = $_GET['timestamp'];
		$timeslot = $_GET['timeslot'];
		$customer_type = $_GET['customer_type'];
		
		$calendar_id = (isset($_GET['calendar_id']) ? $_GET['calendar_id'] : false);
		$calendar_id = array($calendar_id);
		$calendar_id = array_map( 'intval', $calendar_id );
		$calendar_id = array_unique( $calendar_id );
		
		$time_format = get_option('time_format');
		$date_format = get_option('date_format');
		$appointment_default_status = get_option('booked_new_appointment_default','draft');
		
		// Get custom field data (new in v1.2)
		$custom_fields = json_decode(stripslashes(get_option('booked_custom_fields')),true);
		$custom_field_data = array();
		$cf_meta_value = '';
		
		if (!empty($custom_fields)):
		
			$previous_field = false;
		
			foreach($custom_fields as $field):
			
				$field_name = $field['name'];
				$field_title = $field['value'];
			
				$field_title_parts = explode('---',$field_name);
				if ($field_title_parts[0] == 'radio-buttons-label' || $field_title_parts[0] == 'checkboxes-label'):
					$current_group_name = $field_title;
				elseif ($field_title_parts[0] == 'single-radio-button' || $field_title_parts[0] == 'single-checkbox'):
					// Don't change the group name yet
				else :
					$current_group_name = $field_title;
				endif;
				
				$current_group_name = htmlentities($current_group_name);
				
				if ($field_name != $previous_field){
				
					if (isset($_GET[$field_name]) && $_GET[$field_name]):
					
						$field_value = $_GET[$field_name];
						if (is_array($field_value)){
							$field_value = implode(', ',$field_value);
						}
						$custom_field_data[$current_group_name] = htmlentities($field_value);
					
					endif;
					
					$previous_field = $field_name;
				
				}
			
			endforeach;
			
			if (!empty($custom_field_data)):
				foreach($custom_field_data as $label => $value):
					$cf_meta_value .= '<p class="cf-meta-value"><strong>'.$label.'</strong><br>'.$value.'</p>';
				endforeach;
			endif;
		
		endif;
		// END Get custom field data
		
		if ($customer_type == 'current'):
			$user_id = $_GET['user_id'];
			
			// Create a new appointment post for a current customer
			$new_post = array(
				'post_title' => date_i18n($date_format,$timestamp).' @ '.date_i18n($time_format,$timestamp).' (User: '.$user_id.')',
				'post_content' => '',
				'post_status' => $appointment_default_status,
				'post_date' => date('Y',strtotime($date)).'-'.date('m',strtotime($date)).'-01 00:00:00',
				'post_author' => $user_id,
				'post_type' => 'booked_appointments'
			);
			$post_id = wp_insert_post($new_post);
			
			update_post_meta($post_id, '_appointment_timestamp', $timestamp);
			update_post_meta($post_id, '_appointment_timeslot', $timeslot);
			update_post_meta($post_id, '_appointment_user', $user_id);
			update_post_meta($post_id, '_cf_meta_value', $cf_meta_value);
			
			if (!empty($calendar_id)): wp_set_object_terms($post_id,$calendar_id,'booked_custom_calendars'); endif;
			
			// Send an email to the Admin?
			$email_content = get_option('booked_admin_appointment_email_content');
			$email_subject = get_option('booked_admin_appointment_email_subject');
			if ($email_content && $email_subject):
				$admin_email = get_option( 'admin_email' );
				$user_name = get_user_meta( $user_id, 'first_name', true );
				$tokens = array('%name%','%date%','%time%','%customfields%');
				$replacements = array($user_name,date_i18n($date_format,$timestamp),date_i18n($time_format,$timestamp),$cf_meta_value);
				$email_content = str_replace($tokens,$replacements,$email_content);
				$email_subject = str_replace($tokens,$replacements,$email_subject);
				booked_mailer( $admin_email, $email_subject, $email_content );
			endif;
			
			echo $date;
			exit;
			
		else :
	
			$first_name = $_GET['first_name'];
			$last_name = $_GET['last_name'];
			$email = $_GET['email'];
			$phone = $_GET['phone'];
			$password = wp_generate_password();
			
			if ($last_name): $username = $first_name.$last_name; else : $username = $first_name; endif;
			$username = strtolower(preg_replace("/&([a-z])[a-z]+;/i", "$1", htmlentities($username)));
			$errors = booked_registration_validation($username,$email);
			
			if (!empty($errors)):
				$rand = rand(111,999);
				if ($last_name): $username = $first_name.$last_name.'_'.$rand; else : $username = $first_name.'_'.$rand; endif;
				$username = strtolower(preg_replace("/&([a-z])[a-z]+;/i", "$1", htmlentities($username)));
				$errors = booked_registration_validation($username,$email);
			endif;
			
			if ($last_name): $nickname = $first_name.' '.$last_name; else : $nickname = $first_name; endif;
			
			if (empty($errors)):
				$userdata = array(
		        	'user_login'    =>  $username,
					'user_email'    =>  $email,
					'user_pass'     =>  $password,
					'first_name'	=>	$first_name,
					'last_name'		=>	$last_name,
					'nickname'		=>	$nickname
		        );
		        $user_id = wp_insert_user( $userdata );
		        
		        $creds = array();
				$creds['user_login'] = $username;
				$creds['user_password'] = $password;
				$creds['remember'] = true;
				$user_signon = wp_signon( $creds, false );
				if ( is_wp_error($user_signon) ){
					$signin_errors = $user_signon->get_error_message();
				}
		        
		        // Send an email to the Admin?
				$email_content = get_option('booked_admin_appointment_email_content');
				$email_subject = get_option('booked_admin_appointment_email_subject');
				if ($email_content && $email_subject):
					$admin_email = get_option( 'admin_email' );
					$tokens = array('%name%','%date%','%time%','%customfields%');
					$replacements = array($first_name,date_i18n($date_format,$timestamp),date_i18n($time_format,$timestamp),$cf_meta_value);
					$email_content = str_replace($tokens,$replacements,$email_content);
					$email_subject = str_replace($tokens,$replacements,$email_subject);
					booked_mailer( $admin_email, $email_subject, $email_content );
				endif;
				
				// Send a registration welcome email to the new user?
				$email_content = get_option('booked_registration_email_content');
				$email_subject = get_option('booked_registration_email_subject');
				if ($email_content && $email_subject):
					$user_name = get_user_meta( $user_id, 'first_name', true );
					$tokens = array('%name%','%username%','%password%');
					$replacements = array($first_name,$username,$password);
					$email_content = str_replace($tokens,$replacements,$email_content);
					$email_subject = str_replace($tokens,$replacements,$email_subject);
					booked_mailer( $email, $email_subject, $email_content );
				endif;
				
		        if ($phone){
			        update_user_meta($user_id,'booked_phone',$phone);
		        }
		        
		        // Create a new appointment post for this new customer
				$new_post = array(
					'post_title' => date_i18n($date_format,$timestamp).' @ '.date_i18n($time_format,$timestamp).' (User: '.$user_id.')',
					'post_content' => '',
					'post_status' => $appointment_default_status,
					'post_date' => date('Y',strtotime($date)).'-'.date('m',strtotime($date)).'-01 00:00:00',
					'post_author' => $user_id,
					'post_type' => 'booked_appointments'
				);
				$post_id = wp_insert_post($new_post);
				
				update_post_meta($post_id, '_appointment_timestamp', $timestamp);
				update_post_meta($post_id, '_appointment_timeslot', $timeslot);
				update_post_meta($post_id, '_appointment_user', $user_id);
				update_post_meta($post_id, '_cf_meta_value', $cf_meta_value);
				
				if (!empty($calendar_id)): wp_set_object_terms($post_id,$calendar_id,'booked_custom_calendars'); endif;
		        
		        echo 'success###'.$date;
				
			else :
			
				echo 'error###Whoops!
'.implode('
',$errors);
			endif;
			
		endif;	
		exit;
	}
	
	if (isset($_GET['action']) && $_GET['action'] == 'cancel_appt' && isset($_GET['appt_id']) && isset($_GET['appt_id']))
	{		
		
		$time_format = get_option('time_format');
		$date_format = get_option('date_format');
		
		$appt_id = $_GET['appt_id'];
		$appt = get_post( $appt_id );
		$appt_author = $appt->post_author;
		if (get_current_user_id() == $appt_author):
			
			// Send an email to the Admin?
			$email_content = get_option('booked_admin_cancellation_email_content');
			$email_subject = get_option('booked_admin_cancellation_email_subject');
			if ($email_content && $email_subject):
				$admin_email = get_option( 'admin_email' );
				$user_name = get_user_meta( $appt_author, 'first_name', true );
				$tokens = array('%name%','%date%','%time%','%customfields%');
				$replacements = array($user_name,date_i18n($date_format,$timestamp),date_i18n($time_format,$timestamp),$cf_meta_value);
				$email_content = str_replace($tokens,$replacements,$email_content);
				$email_subject = str_replace($tokens,$replacements,$email_subject);
				booked_mailer( $admin_email, $email_subject, $email_content );
			endif;
		
			wp_delete_post($appt_id,true);
			
		endif;
		exit;
	}
	
}