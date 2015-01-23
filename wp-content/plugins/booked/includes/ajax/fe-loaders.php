<?php

add_action('get_header', 'bp_fe_ajax_loaders', 11);
function bp_fe_ajax_loaders() {
	
	/*
	Load a calendar month
	*/
	if (isset($_POST['load']) && $_POST['load'] == 'calendar_month' && isset($_POST['gotoMonth']))
	{

		$calendar_id = (isset($_POST['calendar_id']) ? $_POST['calendar_id'] : false);
		$timestamp = ($_POST['gotoMonth'] != 'false' ? strtotime($_POST['gotoMonth']) : current_time('timestamp'));
		
		$year = date('Y',$timestamp);
		$month = date('m',$timestamp);
		
		booked_fe_calendar($year,$month,$calendar_id);
		exit;
		
	}
	
	/*
	Load a calendar date
	*/
	if (isset($_POST['load']) && $_POST['load'] == 'calendar_date' && isset($_POST['date']))
	{

		$calendar_id = (isset($_POST['calendar_id']) ? $_POST['calendar_id'] : false);
		booked_fe_calendar_date_content($_POST['date'],$calendar_id);
		exit;
		
	}
	
	
	/*
	Refresh a calendar date square
	*/
	if (isset($_POST['load']) && $_POST['load'] == 'refresh_date_square' && isset($_POST['date']))
	{
		$calendar_id = (isset($_POST['calendar_id']) ? $_POST['calendar_id'] : false);
		booked_fe_calendar_date_square($_POST['date'],$calendar_id);
		exit;
	}
	
	
	/*
	Load the New Appointment form
	*/
	if (isset($_POST['load']) && $_POST['load'] == 'new_appointment_form' && isset($_POST['date']) && isset($_POST['timeslot']))
	{

		$date = $_POST['date'];
		$timeslot = $_POST['timeslot'];
		$timeslot_parts = explode('-',$timeslot);
		
		$date_format = get_option('date_format');
		$time_format = get_option('time_format');
		
		$args = array('orderby' => 'display_name');
		$user_array = get_users($args);
		
		$calendar_id = (isset($_POST['calendar_id']) ? $_POST['calendar_id'] : false);
		
		$appt_date_time = '<p class="name"><b><i class="fa fa-calendar-o"></i>&nbsp;&nbsp;' . date_i18n($date_format, strtotime($date)) . '&nbsp;&nbsp;&nbsp;&nbsp;</b><b><i class="fa fa-clock-o"></i>&nbsp;&nbsp;' . date_i18n($time_format,strtotime($timeslot_parts[0])).' &ndash; '.date_i18n($time_format,strtotime($timeslot_parts[1])) . '</b></p>';
	
		?><p><small><?php _e('Request Appointment','booked'); ?></small></p>
		<form action="" method="post" class="booked-form" id="newAppointmentForm"<?php if ($calendar_id): echo ' data-calendar-id="'.$calendar_id.'"'; endif; ?>>
			
			<input type="hidden" name="date" value="<?php echo date('Y-m-j', strtotime($date)); ?>" />
			<input type="hidden" name="timestamp" value="<?php echo strtotime($date.' '.$timeslot_parts[0]); ?>" />
			<input type="hidden" name="timeslot" value="<?php echo $timeslot; ?>" />
			<input type="hidden" name="customer_type" value="<?php if (is_user_logged_in()): echo 'current'; else : echo 'new'; endif; ?>" />			

			<?php $reached_limit = false;
			
			$reached_daily_limit = false;
			if (is_user_logged_in()):
				
				global $current_user;
				get_currentuserinfo();
				
				$appointment_limit = get_option('booked_appointment_limit');
				$appointment_daily_limit = get_option('booked_appointment_daily_limit');
				$user_appointments = booked_user_appointments($current_user->ID, false);
				$upcoming_user_appointments = count($user_appointments);
				// error_log('current date: '.$date);
				$already_appointed = 0;
				foreach ($user_appointments as $appt) {
					if($date == $appt['date']){
						$already_appointed ++;
					}
				}

				// error_log('User already appointed: '. $already_appointed. ' total: '.$upcoming_user_appointments);

				if($appointment_daily_limit):
					if ($already_appointed >= $appointment_daily_limit):
						$reached_daily_limit = true;
					else :
						$reached_daily_limit = false;
					endif;
				endif;

				if ($appointment_limit):
					$upcoming_user_appointments = booked_user_appointments($current_user->ID,true);
					if ($upcoming_user_appointments >= $appointment_limit):
						$reached_limit = true;
					else :
						$reached_limit = false;
					endif;
				endif;
				


				if (!$reached_limit && !$reached_daily_limit):
				
					?><p><?php echo sprintf( __( 'You are about to request an appointment for %s.','booked' ), get_user_meta( $current_user->ID, 'nickname', true )); ?> <?php _e('Please confirm that you would like to request the following appointment:','booked'); ?></p>
					<?php echo $appt_date_time; ?>
				
					<input type="hidden" name="user_id" value="<?php echo $current_user->ID; ?>" />
				
				<?php elseif ($reached_limit) : ?>

					<p><?php echo sprintf(_n("Sorry, but you've hit the appointment limit. Each user may only book %d appointment at a time.","Sorry, but you've hit the appointment limit. Each user may only book %d appointments at a time.", $appointment_limit, "booked" ), $appointment_limit); ?></p>

				<?php elseif ($reached_daily_limit) : ?>
					<p><?php echo sprintf(_n("Sorry, but you've hit the appointment limit. Each user may only book %d appointment at a day.","Sorry, but you've hit the appointment limit. Each user may only book %d appointments at a day.", $appointment_daily_limit, "booked" ), $appointment_daily_limit); ?></p>					
				<?php endif; ?>
				
			<?php else : ?>
			
				<?php echo $appt_date_time; ?>
				
				<div class="field">
					<input value="<?php _e('First name...','booked'); ?>" title="<?php _e('First name...','booked'); ?>" type="text" class="textfield" name="first_name" />
					<input value="<?php _e('Last name...','booked'); ?>" title="<?php _e('Last name...','booked'); ?>" type="text" class="textfield" name="last_name" />
				</div>
				<div class="field">
					<input value="<?php _e('Email...','booked'); ?>" title="<?php _e('Email...','booked'); ?>" type="email" class="large textfield" name="email" />
				</div>
				<div class="field">
					<input value="<?php _e('Phone number...','booked'); ?>" title="<?php _e('Phone number...','booked'); ?>" type="tel" class="large textfield" name="phone" />
				</div>
				
				<div class="spacer"></div>
			
			<?php endif; ?>
			
			<?php booked_custom_fields(); ?>
			
			<div class="field">
				<?php if (!$reached_limit && !$reached_daily_limit): ?>
					<input type="submit" class="button button-primary" value="<?php _e('Request Appointment','booked'); ?>">
					<button class="cancel button"><?php _e('Cancel','booked'); ?></button>
				<?php else: ?>
					<button class="cancel button"><?php _e('Okay','booked'); ?></button>
				<?php endif; ?>
			</div>
			
		</form>
		
		<script type="text/javascript" src="<?php echo BOOKED_PLUGIN_URL; ?>/js/fe-form_new-appointment.js"></script>
		
		<?php echo '<a href="#" class="close"><i class="fa fa-remove"></i></a>';
		exit;
		
	}

	
	
	
}