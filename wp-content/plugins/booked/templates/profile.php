<?php

// This template only shows up if you are logged in or if you have a username after the /profile/ in the url.

global $current_user,$custom_query,$custom_recipe_title,$custom_type,$error,$post;

get_currentuserinfo();
$profile_username = $current_user->user_login;
$my_id = $current_user->ID;
$my_profile = true;

//booked_set_timezone();

$user_data = get_user_by( 'id', $current_user->ID );

?><div id="booked-profile-page"<?php if ($my_profile): ?> class="me"<?php endif; ?>><?php

if (empty($user_data)) {

	echo '<h2>' . __('No profile here!','booked') . '</h2>';
	echo '<p>' . __('Sorry, this user profile does not exist.','booked') . '</p>';

} else { ?>

	<div class="booked-profile-header bookedClearFix">

		<div class="booked-avatar">
			<?php echo booked_avatar($user_data->ID,150); ?>
		</div>
		
		<?php
			
			$user_meta = get_user_meta($user_data->ID);
			$user_url = $user_data->data->user_url;
			$user_desc = $user_meta['description'][0];
			$h3_class = '';
			
		?>
		
		<div class="booked-info">
			<div class="booked-user">
				<h3 class="<?php echo $h3_class; ?>"><?php echo get_user_meta( $user_data->ID, 'nickname', true ); ?></h3>
				<?php if ($user_url){ echo '<p><a href="'.$user_url.'" target="_blank">'.$user_url.'</a></p>'; } ?>
				<?php if ($user_desc){ echo wpautop($user_desc); } ?>
				<?php if ($my_profile): ?>
					<a class="booked-logout-button" href="<?php echo wp_logout_url(get_permalink($post->ID)); ?>" title="<?php _e('Logout','booked'); ?>"><?php _e('Logout','booked'); ?></a>
				<?php endif; ?>
			</div>
		</div>

	</div>
	
	
	
	<?php
	
	/*
	Grab all of the appointments for this user
	*/
	
	$time_format = get_option('time_format');
	$date_format = get_option('date_format');
	$appointments_array = booked_user_appointments($user_data->ID,false,$time_format,$date_format);
	$total_appts = count($appointments_array);
	$appointment_default_status = get_option('booked_new_appointment_default','draft');
		
	?>
	
	

	<?php if ( is_user_logged_in() && $my_profile ) : ?>
		<div id="profile-appointments" class="booked-tab-content bookedClearFix">
			
			<div id="data-ajax-url"><?php echo get_the_permalink(); ?></div>
		
			<?php if (isset($_GET['appt_requested']) && isset($_GET['new_account'])){
				
				echo '<p class="booked-form-notice">'.__('Your appointment has been requested! We have also set up an account for you. Your login information has been sent via email. When logged in, you can view your upcoming appointments below. Be sure to change your password to something more memorable by using the Edit Profile section below.','booked').'</p>';
				
			} else if (isset($_GET['appt_requested'])){
				
				if ($appointment_default_status == 'draft'):
					echo '<p class="booked-form-notice">'.__('Your appointment has been requested! It will be updated below if approved.','booked').'</p>';
				else :
					echo '<p class="booked-form-notice">'.__('Your appointment has been added to our calendar!','booked').'</p>';
				endif;
				
			} ?>
			
			<?php
				
			echo '<div class="booked-profile-appt-list">';
			
				echo '<h4><i class="fa fa-calendar"></i>&nbsp;&nbsp;<span class="count">' . number_format($total_appts) . '</span> ' . _n('Upcoming Appointment','Upcoming Appointments',$total_appts,'booked') . '</h4>';
				
				foreach($appointments_array as $appt):
						
					$today = date_i18n($date_format);
					$date_display = date_i18n($date_format,$appt['timestamp']);
					if ($date_display == $today){
						$date_display = __('Today','booked');
						$day_name = '';
					} else {
						$day_name = date_i18n('l',$appt['timestamp']).', ';
					}
					
					$cf_meta_value = get_post_meta($appt['post_id'], '_cf_meta_value',true);
					
					$timeslots = explode('-',$appt['timeslot']);
					$time_start = date($time_format,strtotime($timeslots[0]));
					$time_end = date($time_format,strtotime($timeslots[1]));
					
					$appt_date_time = strtotime($date_display.' '.$time_start);
					$current_timestamp = current_time('timestamp');
					
					$google_date_startend = date('Ymd',$appt['timestamp']);
					$google_time_start = date('Hi',strtotime($timeslots[0]));
					$google_time_end = date('Hi',strtotime($timeslots[1]));
					
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
					echo '<span class="appt-block bookedClearFix '.$status.'" data-appt-id="'.$appt['post_id'].'">';
						if ($appointment_default_status == 'draft'):
							echo '<span class="status-block">'.($status == 'pending' ? '<i class="fa fa-circle-o"></i>' : '<i class="fa fa-check-circle"></i>').'&nbsp;&nbsp;'.$status.'</span>';
						endif;
						echo '<strong>'.$day_name.$date_display.'</strong><br><i class="fa fa-clock-o"></i>&nbsp;&nbsp;'.__('from','booked').' '.$time_start.' '.__('to','booked').' '.$time_end;
						echo ($cf_meta_value ? '<br><i class="fa fa-info-circle"></i>&nbsp;&nbsp;<a href="#" class="booked-show-cf">Additional information</a><div class="cf-meta-values-hidden">'.$cf_meta_value.'</div>' : '');
						echo '<div class="booked-cal-buttons">';
							echo '<a href="https://www.google.com/calendar/render?action=TEMPLATE&text='.urlencode(sprintf(__('Appointment with %s','booked'),get_bloginfo('name'))).'&dates='.$google_date_startend.'T'.$google_time_start.'00/'.$google_date_startend.'T'.$google_time_end.'00&details=&location=&sf=true&output=xml"target="_blank" rel="nofollow" class="google-cal-button"><i class="fa fa-plus"></i>&nbsp;&nbsp;'.__('Google Calendar','booked').'</a>';
							if ( $appt_date_time >= $date_to_compare ) { echo '<a href="#" data-appt-id="'.$appt['post_id'].'" class="cancel">'.__('Cancel','booked').'</a>'; }
						echo '</div>';
					echo '</span>';
					
				endforeach;
			
			echo '</div>';
			
			?>
		
			<div id="profile-edit">
				
				<?php echo '<h4><i class="fa fa-pencil-square-o"></i>&nbsp;&nbsp;'.__('Edit Profile','booked').'</h4>'; ?>	
					
		        <form method="post" enctype="multipart/form-data" id="booked-page-form" action="<?php the_permalink(); ?>">
		        	
		        	<div class="bookedClearFix">
			            <p class="form-avatar">	
			                <label for="avatar"><?php _e('Update Avatar', 'booked'); ?></label><br>
			                <span class="booked-upload-wrap"><span><?php _e('Choose image ...','booked'); ?></span><input class="field" name="avatar" type="file" id="avatar" value="" /></span>
			                <?php wp_nonce_field( 'avatar_upload', 'avatar_nonce' ); ?>
			                <span class="hint-p"><?php _e('Recommended size: 100px by 100px or larger', 'booked'); ?></span>
			            </p><!-- .form-nickname -->
		        	</div>
		        	
		            <div class="bookedClearFix">
			            <p class="form-nickname">
			                <label for="nickname"><?php _e('Display Name', 'booked'); ?></label>
			                <input class="text-input" name="nickname" type="text" id="nickname" value="<?php the_author_meta( 'nickname', $current_user->ID ); ?>" />
			            </p><!-- .form-nickname -->
			            <p class="form-email">
			                <label for="email"><?php _e('E-mail *', 'booked'); ?></label>
			                <input class="text-input" name="email" type="text" id="email" value="<?php the_author_meta( 'user_email', $current_user->ID ); ?>" />
			            </p><!-- .form-email -->
			            <p class="form-url">
			                <label for="url"><?php _e('Website', 'booked'); ?></label>
			                <input class="text-input" name="url" type="text" id="url" value="<?php the_author_meta( 'user_url', $current_user->ID ); ?>" />
			            </p><!-- .form-url -->
		            </div>
		            <div class="bookedClearFix">
			            <p class="form-password">
			                <label for="pass1"><?php _e('Change Password', 'booked'); ?></label>
			                <input class="text-input" name="pass1" type="password" id="pass1" />
			            </p><!-- .form-password -->
			            <p class="form-password">
			                <label for="pass2"><?php _e('Repeat Password', 'booked'); ?></label>
			                <input class="text-input" name="pass2" type="password" id="pass2" />
			            </p><!-- .form-password -->
		            </div>
		            <p class="form-textarea">
		                <label for="description"><?php _e('Short Bio', 'booked') ?></label>
		                <textarea name="description" id="description" rows="3" cols="50"><?php the_author_meta( 'description', $current_user->ID ); ?></textarea>
		            </p><!-- .form-textarea -->
		
		            <?php 
		                //action hook for plugin and extra fields
		                do_action('edit_user_profile',$current_user); 
		            ?>
		            <p class="form-submit">
		                <input name="updateuser" type="submit" id="updateuser" class="submit button button-primary" value="<?php _e('Update', 'booked'); ?>" />
		                <?php wp_nonce_field( 'update-user' ) ?>
		                <input name="action" type="hidden" id="action" value="update-user" />
		            </p><!-- .form-submit -->
		        </form><!-- #adduser -->
			</div>
		
		</div>
	<?php endif; ?>	
	

<?php } ?>
	
</div>