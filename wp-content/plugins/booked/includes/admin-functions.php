<?php
	
function booked_render_timeslots($calendar_id = false){
	
	if ($calendar_id):
		$booked_defaults = get_option('booked_defaults_'.$calendar_id);
	else :
		$booked_defaults = get_option('booked_defaults');
	endif;
	
	$time_format = get_option('time_format');
	$first_day_of_week = (get_site_option('start_of_week') == 0 ? 7 : 1);
	
	$day_loop = array('Sun','Mon','Tue','Wed','Thu','Fri','Sat');
	if ($first_day_of_week == 1): $sunday_item = array_shift($day_loop); $day_loop[] = $sunday_item; endif;
	
	?><table class="booked-timeslots"<?php echo ($calendar_id ? ' data-calendar-id="'.$calendar_id.'"' : ''); ?>>
		<thead>
			<tr>
				<?php foreach($day_loop as $day):
					echo '<th>'.$day.'</th>';
				endforeach; ?>
			</tr>
		</thead>
		<tbody>
			<tr>
				<?php foreach($day_loop as $day): ?>
					<td data-day="<?php echo $day; ?>" class="addTimeslot"><span class="cancel button"><?php _e('Cancel','booked'); ?></span><a href="#" class="button"><?php _e('Add ...','booked'); ?></a><a href="#" class="button-mobile"><i class="fa fa-plus"></i></a></td>
				<?php endforeach; ?>
			</tr>
			<tr>
				<?php foreach($day_loop as $day):
				
					echo '<td class="dayTimeslots" data-day="'.$day.'">';
						if (!empty($booked_defaults[$day])):
							ksort($booked_defaults[$day]);
							foreach($booked_defaults[$day] as $time => $count):
								booked_render_timeslot_info($time_format,$time,$count);
							endforeach;
						else :
							echo '<p><small>'.__('No time slots.','booked').'</small></p>';
						endif;
					echo '</td>';
				
				endforeach; ?>
			</tr>
		</tbody>
	</table><?php
	
}

function booked_render_timeslot_info($time_format,$time,$count){
	echo '<span class="timeslot" data-timeslot="'.$time.'">';
		$time = explode('-',$time);
		echo '<span class="start">'.date($time_format,strtotime('2014-01-01 '.$time[0])).'</span> &ndash; ';
		echo '<span class="end">'.date($time_format,strtotime('2014-01-01 '.$time[1])).'</span>';
		echo '<span class="slotsBlock">';
			echo '<span class="changeCount minus" data-count="-1"><i class="fa fa-minus-circle"></i></span>';
			echo '<span class="count"><em>'.$count.'</em> '._n('slot','slots',$count,'booked').'</span>';
			echo '<span class="changeCount add" data-count="1"><i class="fa fa-plus-circle"></i></span>';
		echo '</span>';
		echo '<span class="delete"><i class="fa fa-remove"></i></span>';
	echo '</span>';
}

function booked_render_time_select($select_name,$interval,$placeholder){
	$time = 0;
	$time_format = get_option('time_format');
	
	echo '<select name="'.$select_name.'">';
	echo '<option value="">'.$placeholder.'</option>';
		do {
			$time_display = booked_convertTime($time);
			echo '<option value="'.date('Hi',strtotime('2014-01-01 '.$time_display)).'">'.date($time_format,strtotime('2014-01-01 '.$time_display)).'</option>';
			$time = $time + $interval;
		} while ($time < 1440);
		echo '<option value="2400">'.date($time_format,strtotime('2014-01-01 24:00')).'</option>';
	echo '</select>';
}

function booked_render_interval_select($select_name,$placeholder){
	echo '<select name="'.$select_name.'">'; ?>
	<option value="60" selected><?php _e('Every 1 hour','booked'); ?></option>
	<option value="90"><?php _e('Every 1 hour, 30 minutes','booked'); ?></option>
	<option value="120"><?php _e('Every 2 hours','booked'); ?></option>
	<option value="45"><?php _e('Every 45 minutes','booked'); ?></option>
	<option value="30"><?php _e('Every 30 minutes','booked'); ?></option>
	<option value="20"><?php _e('Every 20 minutes','booked'); ?></option>
	<option value="15"><?php _e('Every 15 minutes','booked'); ?></option>
	<option value="10"><?php _e('Every 10 minutes','booked'); ?></option>
	<option value="5"><?php _e('Every 5 minutes','booked'); ?></option>
	<?php echo '</select>';
}

function booked_render_time_between_select($select_name,$placeholder){
	echo '<select name="'.$select_name.'">'; ?>
	<option value="0" selected><?php echo $placeholder; ?></option>
	<option value="5"><?php _e('5 minutes','booked'); ?></option>
	<option value="10"><?php _e('10 minutes','booked'); ?></option>
	<option value="15"><?php _e('15 minutes','booked'); ?></option>
	<option value="20"><?php _e('20 minutes','booked'); ?></option>
	<option value="30"><?php _e('30 minutes','booked'); ?></option>
	<option value="45"><?php _e('45 minutes','booked'); ?></option>
	<option value="60"><?php _e('1 hour','booked'); ?></option>
	<?php echo '</select>';
}

function booked_render_count_select($select_name,$placeholder){
	echo '<select name="'.$select_name.'">'; ?>
	<option value="1" selected><?php _e('1 time slot','booked'); ?></option>
	<option value="2"><?php _e('2 time slots','booked'); ?></option>
	<option value="3"><?php _e('3 time slots','booked'); ?></option>
	<option value="4"><?php _e('4 time slots','booked'); ?></option>
	<option value="5"><?php _e('5 time slots','booked'); ?></option>
	<option value="6"><?php _e('6 time slots','booked'); ?></option>
	<option value="7"><?php _e('7 time slots','booked'); ?></option>
	<option value="8"><?php _e('8 time slots','booked'); ?></option>
	<option value="9"><?php _e('9 time slots','booked'); ?></option>
	<option value="10"><?php _e('10 time slots','booked'); ?></option>
	<option value="11"><?php _e('11 time slots','booked'); ?></option>
	<option value="12"><?php _e('12 time slots','booked'); ?></option>
	<option value="13"><?php _e('13 time slots','booked'); ?></option>
	<option value="14"><?php _e('14 time slots','booked'); ?></option>
	<option value="15"><?php _e('15 time slots','booked'); ?></option>
	<option value="16"><?php _e('16 time slots','booked'); ?></option>
	<option value="17"><?php _e('17 time slots','booked'); ?></option>
	<option value="18"><?php _e('18 time slots','booked'); ?></option>
	<option value="19"><?php _e('19 time slots','booked'); ?></option>
	<option value="20"><?php _e('20 time slots','booked'); ?></option>
	<?php echo '</select>';
}

function booked_admin_set_timezone(){
	$timezone_seconds = (int)get_site_option('gmt_offset') * 3600;
	$timezone_name = timezone_name_from_abbr(null, $timezone_seconds, true);
	date_default_timezone_set($timezone_name);
}

function booked_admin_calendar($year = false,$month = false,$calendar_id = false){
	
	$local_time = current_time('timestamp');
	
	$year = ($year ? $year : date('Y',$local_time));
	$month = ($month ? $month : date('m',$local_time));
	$today = date('j',$local_time); // Defaults to current day
	$last_day = date('t',strtotime($year.'-'.$month));
	
	$monthShown = date($year.'-'.$month.'-01');
	$currentMonth = date('Y-m-01',$local_time);
	
	$first_day_of_week = (get_site_option('start_of_week') == 0 ? 7 : 1); 	// 1 = Monday, 7 = Sunday, Get from WordPress Settings
														
	$start_timestamp = strtotime('-1 second', strtotime($year.'-'.$month.'-01 00:00:00'));
	$end_timestamp = strtotime('+1 second', strtotime($year.'-'.$month.'-'.$last_day.' 23:59:59'));
	
	$args = array(
		'post_type' => 'booked_appointments',
		'posts_per_page' => -1,
		'post_status' => 'any',
		'meta_query' => array(
			array(
				'key'     => '_appointment_timestamp',
				'value'   => array( $start_timestamp, $end_timestamp ),
				'compare' => 'BETWEEN',
			)
		)
	);
	
	if ($calendar_id):
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'booked_custom_calendars',
				'field'    => 'id',
				'terms'    => $calendar_id,
			)
		);
	endif;
	
	$bookedAppointments = new WP_Query($args);
	if($bookedAppointments->have_posts()):
		while ($bookedAppointments->have_posts()):
			$bookedAppointments->the_post();
			global $post;
			$timestamp = get_post_meta($post->ID, '_appointment_timestamp',true);
			$day = date('j',$timestamp);
			$appointments_array[$day][$post->ID]['timestamp'] = $timestamp;
			$appointments_array[$day][$post->ID]['status'] = $post->post_status;
		endwhile;
	endif;
	echo 'asdfadf'.implode($appointments_array);
	// Appointments Array
	// [DAY] => [POST_ID] => [TIMESTAMP/STATUS]
	
	if ($calendar_id):
		$calendar_name = get_term_by('id',$calendar_id,'booked_custom_calendars');
		$calendar_name = $calendar_name->name;
	else :
		$calendar_name = false;
	endif;
	
	?><table class="booked-calendar"<?php echo ($calendar_id ? ' data-calendar-id="'.$calendar_id.'"' : ''); ?> data-monthShown="<?php echo $monthShown; ?>">
		<thead>
			<tr>
				<th colspan="7">
					<a href="#" data-goto="<?php echo date('Y-m-01', strtotime("-1 month", strtotime($year.'-'.$month.'-01'))); ?>" class="page-left"><i class="fa fa-arrow-left"></i></a>
					<span class="calendarSavingState">
						<i class="fa fa-refresh fa-spin"></i>
					</span>
					<span class="monthName">
						<?php if ($monthShown != $currentMonth): ?>
							<a href="#" class="backToMonth" data-goto="<?php echo $currentMonth; ?>"><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;&nbsp;<?php _e('Back to','booked'); ?> <?php echo date_i18n('F',strtotime($currentMonth)); ?></a>
						<?php endif; ?>
						<?php echo date_i18n("F Y", strtotime($year.'-'.$month.'-01')); ?>
						<?php if ($calendar_name): ?>
							<span class="calendarName"><i class="fa fa-calendar"></i>&nbsp;&nbsp;&nbsp;<?php echo $calendar_name; ?></span>
						<?php endif; ?>
					</span>
					<a href="#" data-goto="<?php echo date('Y-m-01', strtotime("+1 month", strtotime($year.'-'.$month.'-01'))); ?>" class="page-right"><i class="fa fa-arrow-right"></i></a>
				</th>
			</tr>
			<tr class="days">
				<?php if ($first_day_of_week == 7): echo '<th>'.__('Sun','booked').'</th>'; endif; ?>
				<th><?php _e('Mon','booked'); ?></th>
				<th><?php _e('Tue','booked'); ?></th>
				<th><?php _e('Wed','booked'); ?></th>
				<th><?php _e('Thu','booked'); ?></th>
				<th><?php _e('Fri','booked'); ?></th>
				<th><?php _e('Sat','booked'); ?></th>
				<?php if ($first_day_of_week == 1): echo '<th>'.__('Sun','booked').'</th>'; endif; ?>
			</tr>
		</thead>
		<tbody><?php
			
			$today_date = date('Y',$local_time).'-'.date('m',$local_time).'-'.date('j',$local_time);

			// $days = cal_days_in_month(CAL_GREGORIAN,$month,$year); 		// Days in current month
			$days = date('t', mktime(0, 0, 0, $month, 1, $year)); 
			$lastmonth = date("t", mktime(0,0,0,$month-1,1,$year)); 	// Days in previous month
			
			$start = date("N", mktime(0,0,0,$month,1,$year)); 			// Starting day of current month
			if ($first_day_of_week == 7): $start = $start + 1; endif;
			if ($start > 7): $start = 1; endif;
			$finish = $days; 											// Finishing day of current month
			$laststart = $start - 1; 									// Days of previous month in calander
			
			$counter = 1;
			$nextMonthCounter = 1;
			
			if ($calendar_id):
				$booked_defaults = get_option('booked_defaults_'.$calendar_id);
				if (!$booked_defaults):
					$booked_defaults = get_option('booked_defaults');
				endif;
			else :
				$booked_defaults = get_option('booked_defaults');
			endif;
			
			if($start > 5){ $rows = 6; } else { $rows = 5; }
		
			for($i = 1; $i <= $rows; $i++){
				echo '<tr class="week">';
				for($x = 1; $x <= 7; $x++){
				
					$classes = array();		
					
					$appointments_count = 0;	
					
					if(($counter - $start) < 0){
					
						$date = (($lastmonth - $laststart) + $counter);
						$classes[] = 'blur';
					
					} else if(($counter - $start) >= $days){
					
						$date = ($nextMonthCounter);
						$nextMonthCounter++;
						$classes[] = 'blur';
							
					} else {
					
						$date = ($counter - $start + 1);
						if($today == $counter - $start + 1){
							if ($today_date == $year.'-'.$month.'-'.$date):
								$classes[] = 'today';
							endif;
						}
						
						$day_name = date('D',strtotime($year.'-'.$month.'-'.$date));
						$full_count = (isset($booked_defaults[$day_name]) && !empty($booked_defaults[$day_name]) ? $booked_defaults[$day_name] : false);
						$total_full_count = 0;
						if ($full_count):
							foreach($full_count as $full_counter){
								$total_full_count = $total_full_count + $full_counter;
							}
						endif;
						
						if (isset($appointments_array[$date]) && !empty($appointments_array[$date])):
							$appointments_count = count($appointments_array[$date]);
							if ($appointments_count > 0 && $appointments_count < $total_full_count): $classes[] = 'partial';
							elseif ($appointments_count >= $total_full_count): $classes[] = 'booked'; endif;
						endif;
						
					}
					
					echo '<td data-date="'.$year.'-'.$month.'-'.$date.'" class="'.implode(' ',$classes).'">';
						echo '<span class="date"><span class="number">'. $date . '</span></span>';
						if ($appointments_count > 0): echo '<span class="count">'.$appointments_count.' '._n('appointment','appointments',$appointments_count,'booked').'</span>'; endif;
					echo '</td>';
				
					$counter++;
					$class = '';
				}
				echo '</tr>';
			} ?>
		</tbody>
	</table><?php
	
}

function booked_admin_calendar_date_content($date,$calendar_id = false){
	
	$calendars = get_terms('booked_custom_calendars','orderby=slug&hide_empty=0');
	if (!empty($calendars)):
		$tabbed = true;
	else :
		$tabbed = false;
	endif;

	echo '<div class="booked-appt-list">';
	
		$time_format = get_option('time_format');
		$date_format = get_option('date_format');
	
		/*
		Grab all of the appointments for this day
		*/
		
		if ($tabbed):
		
			?><ul id="bookedAppointmentTabs" class="bookedClearFix">
				<li<?php if (!$calendar_id): ?> class="active"<?php endif; ?>><a href="#calendar-default"><?php _e('Default Calendar','booked'); ?></a></li><?php
				foreach($calendars as $calendar):
					?><li<?php if ($calendar_id == $calendar->term_id): ?> class="active"<?php endif; ?>><a href="#calendar-<?php echo $calendar->term_id; ?>"><?php echo $calendar->name; ?></a></li><?php
				endforeach;
			?></ul><?php

			$tab_title = __('Appointments for','booked');
			?><div id="bookedCalendarAppointmentsTab-default" class="bookedAppointmentTab<?php if (!$calendar_id): ?> active<?php endif; ?>"><?php
				booked_admin_calendar_date_loop($date,$time_format,$date_format,false,$tab_title,$tabbed,$calendars);
			?></div><?php
			foreach($calendars as $calendar):
				?><div id="bookedCalendarAppointmentsTab-<?php echo $calendar->term_id; ?>" class="bookedAppointmentTab<?php if ($calendar_id == $calendar->term_id): ?> active<?php endif; ?>"><?php
					$display_calendar_id = $calendar->term_id;
					$tab_title = $calendar->name.' '.__('for','booked');
					booked_admin_calendar_date_loop($date,$time_format,$date_format,$display_calendar_id,$tab_title,$tabbed,$calendars);
				?></div><?php
			endforeach;
			
		else :
		
			$tab_title = __('Appointments for','booked');
			booked_admin_calendar_date_loop($date,$time_format,$date_format,$calendar_id,$tab_title,false,$calendars);
		
		endif;
	
	echo '</div>';
	
}

function booked_admin_calendar_date_loop($date,$time_format,$date_format,$calendar_id = false,$tab_title,$tabbed = false,$calendars = false){
	
	$year = date('Y',strtotime($date));
	$month = date('m',strtotime($date));
	$day = date('d',strtotime($date));

	$start_timestamp = strtotime('-1 second',strtotime($year.'-'.$month.'-'.$day.' 00:00:00'));
	$end_timestamp = strtotime('+1 second',strtotime($year.'-'.$month.'-'.$day.' 23:59:59'));
	
	$date_display = date_i18n($date_format,strtotime($date));
	$day_name = date('D',strtotime($date));	
		
	$args = array(
		'post_type' => 'booked_appointments',
		'posts_per_page' => -1,
		'post_status' => 'any',
		'meta_query' => array(
			array(
				'key'     => '_appointment_timestamp',
				'value'   => array( $start_timestamp, $end_timestamp ),
				'compare' => 'BETWEEN'
			)
		)
	);
	
	if ($calendar_id):
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'booked_custom_calendars',
				'field'    => 'id',
				'terms'    => $calendar_id,
			)
		);
	elseif (!$calendar_id && $tabbed && !empty($calendars)):
		
		foreach($calendars as $calendar_term){
            $not_in_calendar[] = $calendar_term->term_id; 
        }
	
		$args['tax_query'] = array(
			array(
				'taxonomy' 			=> 'booked_custom_calendars',
				'field'    			=> 'id',
				'terms'            	=> $not_in_calendar,
				'include_children' 	=> false,
				'operator'         	=> 'NOT IN'
			)
		);
		
	endif;
	
	$appointments_array = array();
	
	$bookedAppointments = new WP_Query($args);
	if($bookedAppointments->have_posts()):
		while ($bookedAppointments->have_posts()):
			$bookedAppointments->the_post();
			global $post;
			$timestamp = get_post_meta($post->ID, '_appointment_timestamp',true);
			$timeslot = get_post_meta($post->ID, '_appointment_timeslot',true);
			$user_id = get_post_meta($post->ID, '_appointment_user',true);
			$day = date('d',$timestamp);
			$appointments_array[$post->ID]['post_id'] = $post->ID;
			$appointments_array[$post->ID]['timestamp'] = $timestamp;
			$appointments_array[$post->ID]['timeslot'] = $timeslot;
			$appointments_array[$post->ID]['status'] = $post->post_status;
			$appointments_array[$post->ID]['user'] = $user_id;
		endwhile;
	endif;
	
	/*
	Start the list
	*/
	
	echo '<h2>'.$tab_title.' '.$date_display.'</h2>';
	
	/*
	Get today's default timeslots
	*/
	
	if ($calendar_id):
		$booked_defaults = get_option('booked_defaults_'.$calendar_id);
		if (!$booked_defaults):
			$booked_defaults = get_option('booked_defaults');
		endif;
	else :
		$booked_defaults = get_option('booked_defaults');
	endif;
	
	$todays_defaults = (isset($booked_defaults[$day_name]) && !empty($booked_defaults[$day_name]) ? $booked_defaults[$day_name] : false);
	
	/*
	There are timeslots available, let's loop through them
	*/
	
	if ($todays_defaults){
	
		ksort($todays_defaults);
		
		foreach($todays_defaults as $timeslot => $count):
		
			$appts_in_this_timeslot = array();
			
			/*
			Are there any appointments in this particular timeslot?
			If so, let's create an array of them.
			*/
			
			foreach($appointments_array as $post_id => $appointment):
				if ($appointment['timeslot'] == $timeslot):
					$appts_in_this_timeslot[] = $post_id;
				endif;
			endforeach;
			
			/*
			Calculate the number of spots available based on total minus the appointments booked
			*/
			
			$spots_available = $count - count($appts_in_this_timeslot);
			$spots_available = ($spots_available < 0 ? $spots_available = 0 : $spots_available = $spots_available);
			
			/*
			Display the timeslot
			*/
			
			$timeslot_parts = explode('-',$timeslot);
			
			$current_timestamp = current_time('timestamp');
			$this_timeslot_timestamp = strtotime($year.'-'.$month.'-'.$day.' '.$timeslot_parts[0]);
			
			if ($current_timestamp < $this_timeslot_timestamp){
				$available = true;
			} else {
				$available = false;
			}
			
			echo '<div class="timeslot bookedClearFix">';
				echo '<span class="timeslot-time"><i class="fa fa-clock-o"></i>&nbsp;&nbsp;'.date_i18n($time_format,strtotime($timeslot_parts[0])).' &ndash; '.date_i18n($time_format,strtotime($timeslot_parts[1])).'</span>';
				echo '<span class="timeslot-count">';
					
					echo '<span class="spots-available'.($spots_available == 0 ? ' empty' : '').'">'.$spots_available.' '._n('time slot','time slots',$spots_available,'booked').' '.__('available','booked').'</span>';
					
					/*
					Display the appointments set in this timeslot
					*/
					
					if (!empty($appts_in_this_timeslot)):
					
						$booked_appts = count($appts_in_this_timeslot);
					
						echo '<strong>'.$booked_appts.' '._n('Appointment','Appointments',$booked_appts,'booked').':</strong>';
					
						foreach($appts_in_this_timeslot as $appt_id):
						
							$user_info = get_userdata($appointments_array[$appt_id]['user']);
							$status = ($appointments_array[$appt_id]['status'] == 'draft' ? 'pending' : 'approved');
							echo '<span class="appt-block" data-appt-id="'.$appt_id.'">';
								if (isset($user_info->ID)):
									echo '<a href="#" class="user" data-user-id="'.$appointments_array[$appt_id]['user'].'">'.$user_info->user_firstname.' '.$user_info->user_lastname.'</a>'.($status == 'pending' ? '<span class="pending-text"> ('.__('pending','booked').')</span>' : '');
								else :
									_e('(this user no longer exists)','booked');
								endif;
								echo '<a href="#" class="delete"'.($calendar_id ? ' data-calendar-id="'.$calendar_id.'"' : '').'><i class="fa fa-remove"></i></a>'.($status == 'pending' ? '<button data-appt-id="'.$appt_id.'" class="approve button button-primary">'.__('Approve','booked').'</button>' : '');
							echo '</span>';
							unset($appointments_array[$appt_id]);
							
						endforeach;
						
					endif;
					
				echo '</span>';
				echo '<span class="timeslot-people"><button'.(!$available ? ' disabled' : '').' data-timeslot="'.$timeslot.'" data-date="'.$date.'"'.($calendar_id ? ' data-calendar-id="'.$calendar_id.'"' : '').' class="new-appt button"'.(!$spots_available ? ' disabled' : '').'>'.__('New Appointment','booked').' ...</button></span>';
			echo '</div>';
			
		endforeach;
		
		/*
		Are there any additional appointments for this day that are not in the default timeslots?
		*/
		
		if (!empty($appointments_array)):
			
			echo '<span class="additional-timeslots">';
			echo '<br><p>'.__('There are additional appointments booked from previously available time slots:','booked').'</p>';
			foreach($appointments_array as $appointment):
			
				$user_info = get_userdata($appointment['user']);
				$status = ($appointment['status'] == 'draft' ? 'pending' : 'approved');
				$timeslot = explode('-',$appointment['timeslot']);
				echo '<div class="timeslot bookedClearFix" data-appt-id="'.$appointment['post_id'].'">';
					echo '<span class="timeslot-time">'.date_i18n($time_format,strtotime($timeslot[0])).' &ndash; '.date_i18n($time_format,strtotime($timeslot[1])).'</span>';
					echo '<span class="timeslot-count count-wide">';
						echo '<a href="#" class="user" data-user-id="'.$appointments_array[$appointment['post_id']]['user'].'">'.$user_info->user_firstname.' '.$user_info->user_lastname.'</a>'.($status == 'pending' ? '<span class="pending-text"> ('.__('pending','booked').')</span>' : '');
						echo '<a href="#" class="delete"'.($calendar_id ? ' data-calendar-id="'.$calendar_id.'"' : '').'><i class="fa fa-remove"></i></a>'.($status == 'pending' ? '<button data-appt-id="'.$appointment['post_id'].'" class="approve button button-primary">'.__('Approve','booked').'</button>' : '');
					echo '</span>';
				echo '</div>';
				
			endforeach;
			echo '</span>';
			
		endif;
		
	/*
	There are no default timeslots, however there are appointments booked.
	*/
		
	} else if (!$todays_defaults && !empty($appointments_array)) {
	
		echo '<span class="additional-timeslots">';
		echo '<p>'.__('There are no appointment slots available for this day, however there are appointments booked from previously available time slots:','booked').'</p>';
		foreach($appointments_array as $appointment):
		
			$user_info = get_userdata($appointment['user']);
			$status = ($appointment['status'] == 'draft' ? 'pending' : 'approved');
			$timeslot = explode('-',$appointment['timeslot']);
			echo '<div class="timeslot bookedClearFix" data-appt-id="'.$appointment['post_id'].'">';
				echo '<span class="timeslot-time">'.date_i18n($time_format,strtotime($timeslot[0])).' &ndash; '.date_i18n($time_format,strtotime($timeslot[1])).'</span>';
				echo '<span class="timeslot-count count-wide">';
					echo '<a href="#" class="user" data-user-id="'.$appointments_array[$appointment['post_id']]['user'].'">'.$user_info->user_firstname.' '.$user_info->user_lastname.'</a>'.($status == 'pending' ? '<span class="pending-text"> ('.__('pending','booked').')</span>' : '');
					echo '<a href="#" class="delete"'.($calendar_id ? ' data-calendar-id="'.$calendar_id.'"' : '').'><i class="fa fa-remove"></i></a>'.($status == 'pending' ? '<button data-appt-id="'.$appointment['post_id'].'" class="approve button button-primary">'.__('Approve','booked').'</button>' : '');
				echo '</span>';
			echo '</div>';
			
		endforeach;
		echo '</span>';
		
	/*
	There are no default timeslots and no appointments booked for this particular day.
	*/
	
	} else {
		echo '<p>'.__('There are no appointment time slots available for this day.','booked').' <a href="'.get_admin_url(null,'admin.php?page=booked-settings#defaults').'">'.__('Would you like to add some?','booked').'</a></p>';
	}
}

function booked_admin_calendar_date_square($date,$calendar_id = false){

	//booked_admin_set_timezone();
	
	$local_time = current_time('timestamp');
	
	$year = date('Y',strtotime($date));
	$month = date('m',strtotime($date));
	$this_day = date('j',strtotime($date)); // Defaults to current day
	$last_day = date('t',strtotime($year.'-'.$month));
	
	$monthShown = date($year.'-'.$month.'-01');
	$currentMonth = date('Y-m-01',$local_time);
	
	$first_day_of_week = (get_site_option('start_of_week') == 0 ? 7 : 1); 	// 1 = Monday, 7 = Sunday, Get from WordPress Settings
														
	$start_timestamp = strtotime('-1 second', strtotime($year.'-'.$month.'-01 00:00:00'));
	$end_timestamp = strtotime('+1 second', strtotime($year.'-'.$month.'-'.$last_day.' 23:59:59'));
	
	if ($calendar_id):
		$booked_defaults = get_option('booked_defaults_'.$calendar_id);
		if (!$booked_defaults):
			$booked_defaults = get_option('booked_defaults');
		endif;
	else :
		$booked_defaults = get_option('booked_defaults');
	endif;
	
	$args = array(
		'post_type' => 'booked_appointments',
		'posts_per_page' => -1,
		'meta_query' => array(
			array(
				'key'     => '_appointment_timestamp',
				'value'   => array( $start_timestamp, $end_timestamp ),
				'compare' => 'BETWEEN',
			)
		)
	);
	
	if ($calendar_id):
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'booked_custom_calendars',
				'field'    => 'id',
				'terms'    => $calendar_id,
			)
		);
	endif;
	
	$bookedAppointments = new WP_Query($args);
	if($bookedAppointments->have_posts()):
		while ($bookedAppointments->have_posts()):
			$bookedAppointments->the_post();
			global $post;
			$timestamp = get_post_meta($post->ID, '_appointment_timestamp',true);
			$day = date('j',$timestamp);
			$appointments_array[$day][$post->ID]['timestamp'] = $timestamp;
			$appointments_array[$day][$post->ID]['status'] = $post->post_status;
		endwhile;
	endif;
	
	$classes[] = 'active';
	
	$today_date = date('Y').'-'.date('m').'-'.date('j');
	if ($today_date == $_POST['date']):
		$classes[] = 'today';
	endif;
	
	$day_name = date('D',strtotime($date));
	$full_count = (isset($booked_defaults[$day_name]) && !empty($booked_defaults[$day_name]) ? $booked_defaults[$day_name] : false);
	$total_full_count = 0;
	if ($full_count):
		foreach($full_count as $full_counter){
			$total_full_count = $total_full_count + $full_counter;
		}
	endif;
	
	if (isset($appointments_array[$this_day]) && !empty($appointments_array[$this_day])):
		$appointments_count = count($appointments_array[$this_day]);
		if ($appointments_count > 0 && $appointments_count < $total_full_count): $classes[] = 'partial';
		elseif ($appointments_count >= $total_full_count): $classes[] = 'booked'; endif;
	endif;
	
	echo '<td data-date="'.$date.'" class="'.implode(' ',$classes).'">';
	echo '<span class="date"><span class="number">'. $this_day . '</span></span>';
	if (isset($appointments_count) && $appointments_count > 0): echo '<span class="count">'.$appointments_count.' '._n('appointment','appointments',$appointments_count,'booked').'</span>'; endif;
	echo '</td>';

}