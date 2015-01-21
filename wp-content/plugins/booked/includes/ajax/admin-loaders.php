<?php

add_action('admin_init', 'bp_admin_ajax_loaders', 11);
function bp_admin_ajax_loaders() {

	/*
	Load the timeslots for a single day
	*/
	if (isset($_POST['load']) && $_POST['load'] == 'load_timeslots' && isset($_POST['day']))
	{
		
		$calendar_id = (isset($_POST['calendar_id']) ? $_POST['calendar_id'] : false);
	
		// Get the saved Default Timeslots
		if ($calendar_id):
			$booked_defaults = get_option('booked_defaults_'.$calendar_id);
		else :
			$booked_defaults = get_option('booked_defaults');
		endif;
		
		$day = $_POST['day'];
		$time_format = get_option('time_format');
		
		if (!empty($booked_defaults[$day])):
			ksort($booked_defaults[$day]);
			foreach($booked_defaults[$day] as $time => $count):
				booked_render_timeslot_info($time_format,$time,$count);
			endforeach;
		else :
			echo '<p><small>'.__('No time slots.','booked').'</small></p>';
		endif;
		exit;
		
	}
	
	/*
	Load the timeslots for the whole week
	*/
	if (isset($_POST['load']) && $_POST['load'] == 'load_full_timeslots')
	{
		
		$calendar_id = (isset($_POST['calendar_id']) ? $_POST['calendar_id'] : false);
		booked_render_timeslots($calendar_id);
		exit;
		
	}
	
	/*
	Load a calendar month
	*/
	if (isset($_POST['load']) && $_POST['load'] == 'calendar_month' && isset($_POST['gotoMonth']))
	{

		$calendar_id = (isset($_POST['calendar_id']) ? $_POST['calendar_id'] : false);
		$timestamp = ($_POST['gotoMonth'] != 'false' ? strtotime($_POST['gotoMonth']) : current_time('timestamp'));
		
		$year = date('Y',$timestamp);
		$month = date('m',$timestamp);
		
		booked_admin_calendar($year,$month,$calendar_id);
		exit;
		
	}
	
	/*
	Load a calendar date
	*/
	if (isset($_POST['load']) && $_POST['load'] == 'calendar_date' && isset($_POST['date']))
	{

		$calendar_id = (isset($_POST['calendar_id']) ? $_POST['calendar_id'] : false);
		booked_admin_calendar_date_content($_POST['date'],$calendar_id);
		exit;
		
	}
	
	
	/*
	Refresh a calendar date square
	*/
	if (isset($_POST['load']) && $_POST['load'] == 'refresh_date_square' && isset($_POST['date']))
	{
		$calendar_id = (isset($_POST['calendar_id']) ? $_POST['calendar_id'] : false);
		booked_admin_calendar_date_square($_POST['date'],$calendar_id);
		exit;
	}
	
	
	/*
	Load the user info modal
	*/
	if (isset($_POST['load']) && $_POST['load'] == 'user_info_modal' && isset($_POST['user_id']))
	{


		// Customer Information
		$user_info = get_userdata($_POST['user_id']);
		$display_name = $user_info->display_name;
		$email = $user_info->user_email;
		$phone = get_user_meta($_POST['user_id'], 'booked_phone', true);
	
		echo '<p><small>'.__('Contact Information','booked').'</small></p>';
		echo '<p><strong class="booked-left-title">'.__('Name','booked').':</strong> '.$user_info->display_name.'</p>';
		if ($email) : echo '<p><strong class="booked-left-title">'.__('Email','booked').':</strong> <a href="mailto:'.$email.'">'.$email.'</a></p>'; endif;
		if ($phone) : echo '<p><strong class="booked-left-title">'.__('Phone','booked').':</strong> <a href="tel:'.preg_replace('/[^0-9+]/', '', $phone).'">'.$phone.'</a></p>'; endif;
		
		// Appointment Information
		if (isset($_POST['appt_id'])):
		
			$time_format = get_option('time_format');
			$date_format = get_option('date_format');
			$appt_id = $_POST['appt_id'];

			$timestamp = get_post_meta($appt_id, '_appointment_timestamp',true);
			$timeslot = get_post_meta($appt_id, '_appointment_timeslot',true);
			$cf_meta_value = get_post_meta($appt_id, '_cf_meta_value',true);
			
			$date_display = date_i18n($date_format,$timestamp);
			$day_name = date_i18n('l',$timestamp);
			
			$timeslots = explode('-',$timeslot);
			$time_start = date($time_format,strtotime($timeslots[0]));
			$time_end = date($time_format,strtotime($timeslots[1]));
			
			echo '<br><p><small>'.__('Appointment Information','booked').'</small></p>';
			echo '<p><strong class="booked-left-title">'.__('Date','booked').':</strong> '.$day_name.', '.$date_display.'</p>';
			echo '<p><strong class="booked-left-title">'.__('Time','booked').':</strong> '.$time_start.' '.__('to','booked').' '.$time_end.'</p>';
			echo ($cf_meta_value ? '<div class="cf-meta-values">'.$cf_meta_value.'</div>' : '');
			
		endif;
		
		// Close button
		echo '<a href="#" class="close"><i class="fa fa-remove"></i></a>';
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
	
		?><p><small><?php _e('New Appointment','booked'); ?></small></p>
		<p class="name"><b><i class="fa fa-calendar-o"></i>&nbsp;&nbsp;<?php echo date_i18n($date_format, strtotime($date)); ?>&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-clock-o"></i>&nbsp;&nbsp;<?php echo date_i18n($time_format,strtotime($timeslot_parts[0])).' &ndash; '.date_i18n($time_format,strtotime($timeslot_parts[1])); ?></b></p>
		<form action="" method="post" class="booked-form" id="newAppointmentForm"<?php if ($calendar_id): echo ' data-calendar-id="'.$calendar_id.'"'; endif; ?>>
			
			<input type="hidden" name="date" value="<?php echo date('Y-m-j', strtotime($date)); ?>" />
			<input type="hidden" name="timestamp" value="<?php echo strtotime($date.' '.$timeslot_parts[0]); ?>" />
			<input type="hidden" name="timeslot" value="<?php echo $timeslot; ?>" />
			
			<div class="field">
				<input data-condition="customer_type" type="radio" name="customer_type" id="customer_current" value="current" checked> <label for="customer_current"><?php _e('Current Customer','booked'); ?></label>
			</div>
			<div class="field">
				<input data-condition="customer_type" type="radio" name="customer_type" id="customer_new" value="new"> <label for="customer_new"><?php _e('New Customer','booked'); ?></label>
			</div>
			
			<hr>
			
			<div class="condition-block customer_type default" id="condition-current">
				<div class="field">
					<select data-placeholder="<?php _e('Select a customer ...','booked'); ?>" id="userList" name="user_id">
						<option></option>
						<?php foreach($user_array as $user): ?>
							<option value="<?php echo $user->ID; ?>"><?php echo $user->display_name; ?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
			
			<div class="condition-block customer_type" id="condition-new">
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
			</div>
			
			<?php booked_custom_fields(); ?>
			
			<hr>
			
			<div class="field">
				<input type="submit" class="button button-primary" value="<?php _e('Create Appointment','booked'); ?>">
				<button class="cancel button"><?php _e('Cancel','booked'); ?></button>
			</div>
			
		</form>
		
		<script type="text/javascript" src="<?php echo BOOKED_PLUGIN_URL; ?>/js/admin-form_new-appointment.js"></script>
		
		<?php echo '<a href="#" class="close"><i class="fa fa-remove"></i></a>';
		exit;
		
	}

	
	
	
}