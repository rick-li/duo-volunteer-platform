<?php
	
/* CALENDAR SHORTCODE */


if (!shortcode_exists('booked-calendar')) {
	add_shortcode('booked-calendar', 'booked_calendar_shortcode');
}

function booked_calendar_shortcode($atts, $content = null) {

	$atts = shortcode_atts(
		array(
			'size' => 'large',
			'calendar' => false
		), $atts );
	
	ob_start();
	
	echo '<div id="data-ajax-url">'.get_the_permalink().'</div>';
	echo '<div class="booked-calendar-wrap '.$atts['size'].'">';
		booked_fe_calendar(null,null,$atts['calendar']);
	echo '</div>';
	
	return ob_get_clean();

}



/* APPOINTMENTS SHORTCODE */

if (!shortcode_exists('booked-appointments')) {
	add_shortcode('booked-appointments', 'booked_appointments_shortcode');
}

function booked_appointments_shortcode($atts, $content = null) {
	
	ob_start();
	
	if (is_user_logged_in()):
	
		global $current_user;
		get_currentuserinfo();
		$my_id = $current_user->ID;
		
		$time_format = get_option('time_format');
		$date_format = get_option('date_format');
		$appointments_array = booked_user_appointments($my_id,false,$time_format,$date_format);
		$total_appts = count($appointments_array);
		
		// echo etm_tools_retrive_languages_data(etm_tools_retrive_aktiv_languages('',false),true);
		// echo 'selected lang is '.$_SESSION['etm_lang'];
		$selectedLang = $_SESSION['etm_lang'];
		echo '<div id="booked-profile-page" class="booked-shortcode"><div class="booked-profile-appt-list">';
				
			echo '<h4><i class="fa fa-calendar"></i>&nbsp;&nbsp;<span class="count">' . number_format($total_appts) . '</span> ' . _n('Upcoming Appointment',__('Upcoming Appointments', 'booked'),$total_appts,'booked') . '</h4>';
		
			
			foreach($appointments_array as $appt):
				// echo join(' === ', $appt);
				$today = date_i18n($date_format);
				$date_display = date_i18n($date_format,$appt['timestamp']);
				if ($date_display == $today){
					$date_display = __('Today','booked');
					$day_name = '';
				} else {
					$day_name = date_i18n('l',$appt['timestamp']).', ';
				}
				
				$timeslots = explode('-',$appt['timeslot']);
				$time_start = date($time_format,strtotime($timeslots[0]));
				$time_end = date($time_format,strtotime($timeslots[1]));
				
				$appt_date_time = strtotime($date_display.' '.$time_start);
				$current_timestamp = strtotime(date('Y-m-d H:i:s'));
				
				$google_date_startend = date('Ymd',$appt['timestamp']);
				$google_time_start = date('Hi',strtotime($timeslots[0]));
				$google_time_end = date('Hi',strtotime($timeslots[1]));
				
				$timezone_seconds = (int)get_site_option('gmt_offset') * 3600;
				$timezone_name = timezone_name_from_abbr(null, $timezone_seconds, true);
				
				$cancellation_buffer = get_option('booked_cancellation_buffer',0);
	
				if ($cancellation_buffer):
					if ($cancellation_buffer < 1){
						$time_type = 'minutes';
						$time_count = $cancellation_buffer * 60;
					} else {
						$time_type = 'hours';
						$time_count = $cancellation_buffer;
					}
					$buffered_timestamp = strtotime('+'.$time_count.' '.$time_type,$current_timestamp);
					$date_to_compare = $buffered_timestamp;
				else:
					$date_to_compare = strtotime(date('Y-m-d H:i:s'));
				endif;
				
				$status = ($appt['status'] == 'draft' ? __('pending','booked') : __('approved','booked'));
				
				$getval = get_option('ect_tran_terms_'.$selectedLang);
				$localedCalendarName = $getval[$appt['calendar_id']]->name;	
				if(!$localedCalendarName){
					$localedCalendarName = $appt['calendar_name'];
				}
				echo '<span class="appt-block bookedClearFix '.$status.'" data-appt-id="'.$appt['post_id'].'">';
					echo '<span class="status-block">'.($status == 'pending' ? '<i class="fa fa-circle-o"></i>' : '<i class="fa fa-check-circle"></i>').'&nbsp;&nbsp;'.$status.'</span>';
					echo '<div>'.$localedCalendarName.'</div>';
					echo '<strong>'.$day_name.$date_display.'</strong><br>'.__('from','booked').' '.$time_start.' '.__('to','booked').' '.$time_end;
					echo '<div class="booked-cal-buttons">';
						echo '<a href="https://www.google.com/calendar/render?action=TEMPLATE&text='.urlencode(sprintf(__('Appointment with %s','booked'),get_bloginfo('name'))).'&dates='.$google_date_startend.'T'.$google_time_start.'00/'.$google_date_startend.'T'.$google_time_end.'00&details=&location=&sf=true&output=xml"target="_blank" rel="nofollow" class="google-cal-button"><i class="fa fa-plus"></i>&nbsp;&nbsp;'.__('Google Calendar','booked').'</a>';
						if ( $appt_date_time >= $date_to_compare ) { echo '<a href="#" data-appt-id="'.$appt['post_id'].'" class="cancel">'.__('Cancel','booked').'</a>'; }
					echo '</div>';
				echo '</span>';
				
			endforeach;
		
		echo '</div></div>';
		
	else :
	
		return '<p>'.__('Please log in to view your upcoming appointments.','booked').'</p>';
	
	endif;
	
	return ob_get_clean();

}



/* LOGIN SHORTCODE */

if (!shortcode_exists('booked-login')) {
	add_shortcode( 'booked-login', 'booked_login_form' );
}

function booked_complete_registration() {
    global $reg_errors, $username, $first_name, $last_name, $password, $email;
    
    if ( 1 > count( $reg_errors->get_error_messages() ) ) {
	    
        $userdata = array(
        	'user_login'    =>   $username,
			'user_email'    =>   $email,
			'user_pass'     =>   $password,
			'first_name'	=>	 $first_name,
			'last_name'		=>	 $last_name
        );
        $user_id = wp_insert_user( $userdata );
        
        // Send a registration welcome email to the new user?
		$email_content = get_option('booked_registration_email_content');
		$email_subject = get_option('booked_registration_email_subject');
		if ($email_content && $email_subject):
			$tokens = array('%name%','%username%','%password%');
			$replacements = array($first_name,$username,$password);
			$email_content = str_replace($tokens,$replacements,$email_content);
			$email_subject = str_replace($tokens,$replacements,$email_subject);
			booked_mailer( $email, $email_subject, $email_content );
		endif;
		
        return '<p class="booked-form-notice"><strong>'.__('Success!','booked').'</strong><br />'.__('Registration complete, please check your email for login information.','booked').'</p>';

    } else {
	    return false;
    }
}

function booked_registration_form($first_name, $last_name, $email){
	
	?><form action="<?php echo get_the_permalink(); ?>" method="post" class="wp-user-form">
	
		<p class="first_name">
			<label for="first_name"><?php _e('First Name','booked'); ?></label>
			<input type="text" name="first_name" value="<?php echo ( isset( $_POST['first_name'] ) ? $first_name : null ); ?>" id="first_name" tabindex="101" />
		</p>
		<p class="last_name">
			<label for="last_name"><?php _e('Last Name','booked'); ?></label>
			<input type="text" name="last_name" value="<?php echo ( isset( $_POST['last_name'] ) ? $last_name : null ); ?>" id="last_name" tabindex="102" />
		</p>
		<p class="email">
			<label for="email"><?php _e('Your Email','booked'); ?></label>
			<input type="text" name="email" value="<?php echo ( isset( $_POST['email'] ) ? $email : null ); ?>" id="email" tabindex="103" />
		</p>
		
		<input type="submit" name="submit" value="<?php _e('Register','booked'); ?>" class="user-submit button-primary" tabindex="105" />
		
	</form><?php
						
}

function booked_login_form( $atts, $content = null ) {

	global $post;

	if (!is_user_logged_in()) {
	
		ob_start();
	
		?><div id="booked-profile-page">
		
			<div id="booked-page-form">
		
				<ul class="booked-tabs login bookedClearFix">
					<li<?php if ( !isset($_POST['submit'] ) ) { ?> class="active"<?php } ?>><a href="#login"><i class="fa fa-user"></i><?php _e('Login','booked'); ?></a></li>
					<?php if (get_option('users_can_register')): ?><li<?php if ( isset($_POST['submit'] ) ) { ?> class="active"<?php } ?>><a href="#register"><i class="fa fa-edit"></i><?php _e('Register','booked'); ?></a></li><?php endif; ?>
					<li><a href="#forgot"><i class="fa fa-question"></i><?php _e('Forgot your password?','booked'); ?></a></li>
				</ul>
			
				<div id="profile-login" class="booked-tab-content">
		
					<?php if (isset($reset) && $reset == true) { ?>
		
						<p class="booked-form-notice">
						<strong><?php _e('Success!','booked'); ?></strong><br />
						<?php _e('Check your email to reset your password.','booked'); ?>
						</p>
		
					<?php } ?>
		
					<div class="booked-form-wrap bookedClearFix">
						<div class="booked-custom-error"><?php _e('Both fields are required to log in.','booked'); ?></div>
						<?php if (isset($_GET['loginfailed'])): ?><div class="booked-custom-error not-hidden"><?php _e('Sorry, those login credentials are incorrect.'); ?></div><?php endif; ?>
						<?php echo wp_login_form( array( 'echo' => false, 'redirect' => get_the_permalink($post->ID) ) ); ?>
					</div>
				</div>
				
				<?php if (get_option('users_can_register')): ?>
				
				<div id="profile-register" class="booked-tab-content">
					<div class="booked-form-wrap bookedClearFix">
					
						<?php if ( isset($_POST['submit'] ) ) {
						
					        // sanitize user form input
					        global $username, $first_name, $last_name, $password, $email;
					        
					        $first_name =   sanitize_user( $_POST['first_name'] );
					        $last_name 	=   sanitize_user( $_POST['last_name'] );
					        $password 	= 	wp_generate_password();
					        $email      =   sanitize_email( $_POST['email'] );
					        
					        if ($last_name): $username = $first_name.$last_name; else : $username = $first_name; endif;
							$username = preg_replace("/&([a-z])[a-z]+;/i", "$1", htmlentities($username));
							$errors = booked_registration_validation($username,$email);
						    
						    if (!empty($errors)):
								$rand = rand(111,999);
								if ($last_name): $username = $first_name.$last_name.'_'.$rand; else : $username = $first_name.'_'.$rand; endif;
								$username = preg_replace("/&([a-z])[a-z]+;/i", "$1", htmlentities($username));
								$errors = booked_registration_validation($username,$email);
							endif;
	
							if (empty($errors)):
					        	$registration_complete = booked_complete_registration();
					        else :
					        	$registration_complete = 'error';
					        endif;
					        
					    } else {
					    
						    $registration_complete = false;
						    
					    }
					    
					    if ($registration_complete && $registration_complete != 'error'){
					    
						    echo $registration_complete;
						    
					    } else {
					    
					    	if ($registration_complete == 'error'){
						    	?><div class="booked-custom-error" style="display:block"><?php echo implode('<br>', $errors); ?></div><?php
					    	}
					    
						    $first_name = (isset($_POST['first_name']) ? $_POST['first_name'] : '');
						    $last_name = (isset($_POST['last_name']) ? $_POST['last_name'] : '');
							$email = (isset($_POST['email']) ? $_POST['email'] : '');
							
							booked_registration_form($first_name,$last_name,$email);
							
					    }
						?>
					
					</div>
				</div>
				
				<?php endif; ?>
				
				<div id="profile-forgot" class="booked-tab-content">
					<div class="booked-form-wrap bookedClearFix">
						<div class="booked-custom-error"><?php _e('A username or email address is required to reset your password.','booked'); ?></div>
						<form method="post" action="<?php echo site_url('wp-login.php?action=lostpassword', 'login_post') ?>" class="wp-user-form">
							<p class="username">
								<label for="user_login" class="hide"><?php _e('Username or Email'); ?></label>
								<input type="text" name="user_login" value="" size="20" id="user_login" tabindex="1001" />
							</p>
								
							<?php do_action('login_form', 'resetpass'); ?>
							<input type="submit" name="user-submit" value="<?php _e('Reset my password'); ?>" class="user-submit button-primary" tabindex="1002" />
							<input type="hidden" name="redirect_to" value="<?php the_permalink(); ?>?reset=true" />
							<input type="hidden" name="user-cookie" value="1" />
								
						</form>
					</div>
				</div>
			</div><!-- END #booked-page-form -->
			
		</div><?php
		
		$content = ob_get_clean();
	}
	
	return $content;
	
}