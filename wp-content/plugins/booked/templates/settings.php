<div class="booked-settings-wrap wrap">
	
	<?php
		
	if(!empty($_GET['settings-updated'])) {
		booked_update_color_theme();
	}
	
	settings_errors();
	
	?>
	
	<div class="topSavingState savingState"><i class="fa fa-refresh fa-spin"></i>&nbsp;&nbsp;<?php _e('Updating, please wait...','booked'); ?></div>
	
	<div class="booked-settings-title"><?php _e('Appointment Settings','booked'); ?></div>
	
	<div id="booked-admin-panel-container">
	
		<div id="data-ajax-url"><?php echo get_admin_url(); ?></div>
	
		<ul class="booked-admin-tabs bookedClearFix">
			<li class="active"><a href="#general"><i class="fa fa-gear"></i>&nbsp;&nbsp;<?php _e('General Settings','booked'); ?></a></li>
			<li><a href="#user-emails"><i class="fa fa-envelope"></i>&nbsp;&nbsp;<?php _e('User Emails','booked'); ?></a></li>
			<li><a href="#admin-emails"><i class="fa fa-envelope-o"></i>&nbsp;&nbsp;<?php _e('Admin Emails','booked'); ?></a></li>
			<li><a href="#custom-fields"><i class="fa fa-pencil"></i>&nbsp;&nbsp;<?php _e('Custom Fields','booked'); ?></a></li>
			<li><a href="#defaults"><span class="savingState"><i class="fa fa-refresh fa-spin"></i>&nbsp;&nbsp;</span><i class="fa fa-clock-o"></i>&nbsp;&nbsp;<?php _e('Default Time Slots','booked'); ?></a></li>
			<li><a href="#shortcodes"><i class="fa fa-code"></i>&nbsp;&nbsp;<?php _e('Shortcodes','booked'); ?></a></li>
			<!-- <li><a href="#custom-timeslots"><span class="savingState"><i class="fa fa-refresh fa-spin"></i>&nbsp;&nbsp;</span><i class="fa fa-clock-o"></i>&nbsp;&nbsp;<?php _e('Custom Time Slots','booked'); ?></a></li> -->
		</ul>
	
		<div class="form-wrapper">
			
			<form action="options.php" method="post">
				
				<?php settings_fields('booked_plugin-group'); ?>
			
				<div id="booked-general" class="tab-content">
					
					<?php
	
					$upload_dir = wp_upload_dir();
					$main_upload_dir = $upload_dir['basedir'];
					$booked_upload_dir = $upload_dir['basedir'] . DIRECTORY_SEPARATOR . 'booked';
					
					$main_upload_dir_writeable = is_writable($main_upload_dir);
					$booked_dir_writeable = is_writable($booked_upload_dir);
					
					$color_theme_file = $booked_upload_dir . DIRECTORY_SEPARATOR . 'color-theme.css';
					$color_theme_file_writeable = is_writable($color_theme_file);
					
					if (!$main_upload_dir_writeable || !$booked_dir_writeable || !$color_theme_file_writeable):
						
						echo '<div style="background:#f2f2f2; padding:20px 23px 9px; margin:0 0 40px;">';
					
							if (!$main_upload_dir_writeable):
								echo '<div id="setting-error-settings_updated" class="error settings-error below-h2">';
									echo '<p><strong>Warning:</strong> You need make the uploads folder writeable:<code>'.$main_upload_dir.'</code></p>';
								echo '</div>';
							endif;
							
							if (!$booked_dir_writeable):
								echo '<div id="setting-error-settings_updated" class="error settings-error below-h2">';
									echo '<p><strong>Warning:</strong> You need make the /booked/ folder writeable:<code>'.$booked_upload_dir.'</code></p>';
								echo '</div>';
							endif;
							
							if (!$color_theme_file_writeable):
								echo '<div id="setting-error-settings_updated" class="error settings-error below-h2">';
									echo '<p><strong>Warning:</strong> The <em>color theme</em> might not save correctly unless you make the following file writeable:<code>'.$color_theme_file.'</code></p>';
								echo '</div>';
							endif;
						
						echo '</div>';
						
					endif;
					
					?>
					
					<div class="section-row">
						<div class="section-head">
							<?php $section_title = __('Profile Page', 'booked'); ?>
							<h3><?php echo esc_attr($section_title); ?></h2>
							<p><?php _e('Create a page that includes the <strong>[booked-login]</strong> shortcode for your profile template then choose it from this dropdown.','booked'); ?><br />
							<?php _e('Or instead of this page, you can use the <strong>[booked-profile]</strong> shortcode to display the Profile content anywhere.','booked'); ?></p>

							<?php $option_name = 'booked_profile_page';
		
							$pages = get_posts(array(
								'post_type' => 'page',
								'orderby'	=> 'name',
								'order'		=> 'asc',
								'posts_per_page' => -1
							));
		
							$selected_value = get_option($option_name); ?>
							<div class="select-box">
								<select name="<?php echo $option_name; ?>">
									<option value=""><?php _e('Choose a page to use for profile page...','booked'); ?></option>
									<?php if(!empty($pages)) :
										foreach($pages as $p) :
											$entry_id = $p->ID;
											$entry_title = get_the_title($entry_id); ?>
											<option value="<?php echo $entry_id; ?>"<?php echo ($selected_value == $entry_id ? ' selected="selected"' : ''); ?>><?php echo $entry_title; ?></option>
										<?php endforeach;
		
									endif; ?>
								</select>
							</div><!-- /.select-box -->
						</div><!-- /.section-body -->
					</div><!-- /.section-row -->
					
					<div class="section-row">
						<div class="section-head">
							<?php $section_title = __('Time Slot Intervals', 'booked'); ?>
							<h3><?php echo esc_attr($section_title); ?></h2>
							<p><?php _e('Choose the intervals you need for your appointment time slots. This will only affect the way default time slots are entered.','booked'); ?></p>

							<?php $option_name = 'booked_timeslot_intervals';
							$selected_value = get_option($option_name);
							
							$interval_options = array(
								'120' 				=> __('Every 2 hours','booked'),
								'60' 				=> __('Every 1 hour','booked'),
								'30' 				=> __('Every 30 minutes','booked'),
								'15' 				=> __('Every 15 minutes','booked'),
								'10' 				=> __('Every 10 minutes','booked'),
								'5' 				=> __('Every 5 minutes','booked')
							); ?>
							
							<div class="select-box">
								<select name="<?php echo $option_name; ?>">
									<?php foreach($interval_options as $current_value => $option_title):
										echo '<option value="'.$current_value.'"' . ($selected_value == $current_value ? ' selected' : ''). '>' . $option_title . '</option>';
									endforeach; ?>
								</select>
							</div><!-- /.select-box -->
						</div><!-- /.section-body -->
					</div><!-- /.section-row -->
					
					<div class="section-row">
						<div class="section-head">
							<?php $section_title = __('Appointment Buffer', 'booked'); ?>
							<h3><?php echo esc_attr($section_title); ?></h2>
							<p><?php _e('To prevent appointments from getting booked too close to the current date and/or time, you can set an appointment buffer. Available appointments time slots will be pushed up to a new date and time depending on which buffer amount you choose below.','booked'); ?></p>

							<?php $option_name = 'booked_appointment_buffer';
							$selected_value = get_option($option_name);
							
							$interval_options = array(
								'0' 				=> __('No buffer','booked'),
								'1' 				=> __('1 hour','booked'),
								'2' 				=> __('2 hours','booked'),
								'3' 				=> __('3 hours','booked'),
								'4' 				=> __('4 hours','booked'),
								'5' 				=> __('5 hours','booked'),
								'6' 				=> __('6 hours','booked'),
								'12' 				=> __('12 hours','booked'),
								'24' 				=> __('24 hours','booked'),
								'48' 				=> __('2 days','booked'),
								'72' 				=> __('3 days','booked'),
								'96' 				=> __('5 days','booked'),
								'144' 				=> __('6 days','booked'),
								'168' 				=> __('1 week','booked'),
								'336' 				=> __('2 weeks','booked'),
								'672' 				=> __('4 weeks','booked'),
							); ?>
							
							<div class="select-box">
								<select name="<?php echo $option_name; ?>">
									<?php foreach($interval_options as $current_value => $option_title):
										echo '<option value="'.$current_value.'"' . ($selected_value == $current_value ? ' selected' : ''). '>' . $option_title . '</option>';
									endforeach; ?>
								</select>
							</div><!-- /.select-box -->
						</div><!-- /.section-body -->
					</div><!-- /.section-row -->
					
					<div class="section-row">
						<div class="section-head">
							<?php $section_title = __('Cancellation Buffer', 'booked'); ?>
							<h3><?php echo esc_attr($section_title); ?></h2>
							<p><?php _e('To prevent appointments from getting cancelled too close to the appointment time, you can set a cancellation buffer.','booked'); ?></p>

							<?php $option_name = 'booked_cancellation_buffer';
							$selected_value = get_option($option_name);
							
							$interval_options = array(
								'0' 				=> __('No buffer','booked'),
								'0.25' 				=> __('15 minutes','booked'),
								'0.50' 				=> __('30 minutes','booked'),
								'0.75' 				=> __('45 minutes','booked'),
								'1' 				=> __('1 hour','booked'),
								'2' 				=> __('2 hours','booked'),
								'3' 				=> __('3 hours','booked'),
								'4' 				=> __('4 hours','booked'),
								'5' 				=> __('5 hours','booked'),
								'6' 				=> __('6 hours','booked'),
								'12' 				=> __('12 hours','booked'),
								'24' 				=> __('24 hours','booked'),
								'48' 				=> __('2 days','booked'),
								'72' 				=> __('3 days','booked'),
								'96' 				=> __('5 days','booked'),
								'144' 				=> __('6 days','booked'),
								'168' 				=> __('1 week','booked'),
								'336' 				=> __('2 weeks','booked'),
								'672' 				=> __('4 weeks','booked'),
							); ?>
							
							<div class="select-box">
								<select name="<?php echo $option_name; ?>">
									<?php foreach($interval_options as $current_value => $option_title):
										echo '<option value="'.$current_value.'"' . ($selected_value == $current_value ? ' selected' : ''). '>' . $option_title . '</option>';
									endforeach; ?>
								</select>
							</div><!-- /.select-box -->
						</div><!-- /.section-body -->
					</div><!-- /.section-row -->
					
					<div class="section-row">
						<div class="section-head">
							<?php $section_title = __('Appointment Limit', 'booked'); ?>
							<h3><?php echo esc_attr($section_title); ?></h2>
							<p><?php _e('To prevent users from booking too many appointments, you can set an appointment limit.','booked'); ?></p>

							<?php $option_name = 'booked_appointment_limit';
							$selected_value = get_option($option_name);
							
							$interval_options = array(
								'0' 				=> __('No limit','booked'),
								'1' 				=> __('1 appointment','booked'),
								'2' 				=> __('2 appointments','booked'),
								'3' 				=> __('3 appointments','booked'),
								'4' 				=> __('4 appointments','booked'),
								'5' 				=> __('5 appointments','booked'),
								'6' 				=> __('6 appointments','booked'),
								'7' 				=> __('7 appointments','booked'),
								'8' 				=> __('8 appointments','booked'),
								'9' 				=> __('9 appointments','booked'),
								'10' 				=> __('10 appointments','booked'),
								'15' 				=> __('15 appointments','booked'),
								'20' 				=> __('20 appointments','booked'),
								'25' 				=> __('25 appointments','booked'),
								'50' 				=> __('50 appointments','booked'),
							); ?>
							
							<div class="select-box">
								<select name="<?php echo $option_name; ?>">
									<?php foreach($interval_options as $current_value => $option_title):
										echo '<option value="'.$current_value.'"' . ($selected_value == $current_value ? ' selected' : ''). '>' . $option_title . '</option>';
									endforeach; ?>
								</select>
							</div><!-- /.select-box -->
						</div><!-- /.section-body -->
					</div><!-- /.section-row -->
					
					<div class="section-row">
						<div class="section-head">
							<?php $section_title = __('New Appointment Default', 'booked'); ?>
							<h3><?php echo esc_attr($section_title); ?></h2>
							<p><?php _e('Would you like your appointment requests to go into a pending list or should they be approved immediately?','booked'); ?></p>

							<?php $option_name = 'booked_new_appointment_default';
							$selected_value = get_option($option_name);
							
							$interval_options = array(
								'draft' 	=> __('Set as Pending','booked'),
								'published' => __('Approve Immediately','booked')
							); ?>
							
							<div class="select-box">
								<select name="<?php echo $option_name; ?>">
									<?php foreach($interval_options as $current_value => $option_title):
										echo '<option value="'.$current_value.'"' . ($selected_value == $current_value ? ' selected' : ''). '>' . $option_title . '</option>';
									endforeach; ?>
								</select>
							</div><!-- /.select-box -->
						</div><!-- /.section-body -->
					</div><!-- /.section-row -->
					
					<div class="section-row">
						<div class="section-head">
							<?php $section_title = __('Front-End Color Settings', 'booked'); ?>
							<h3><?php echo esc_attr($section_title); ?></h3><?php // TODO - WIP ?>
						</div><!-- /.section-head -->
						<div class="section-body">
						
							<?php
							$color_options = array(
								array(
									'name' => 'booked_light_color',
									'title' => 'Light Color',
									'val' => get_option('booked_light_color','#44535B'),
									'default' => '#44535B'
								),
								array(
									'name' => 'booked_dark_color',
									'title' => 'Dark Color',
									'val' => get_option('booked_dark_color','#2D3A40'),
									'default' => '#2D3A40'

								),
								array(
									'name' => 'booked_button_color',
									'title' => 'Primary Button Color',
									'val' => get_option('booked_button_color','#56C477'),
									'default' => '#56C477'

								),
							);
							
							foreach($color_options as $color_option):
							
								echo '<label class="booked-color-label" for="'.$color_option['name'].'">'.$color_option['title'].'</label>';
								echo '<input data-default-color="'.$color_option['default'].'" type="text" name="'.$color_option['name'].'" value="'.$color_option['val'].'" id="'.$color_option['name'].'" class="booked-color-field" />';
								
							endforeach;
							?>
		
						</div><!-- /.section-body -->
					</div>
					
					<div class="section-row submit-section" style="padding:0;">
						<?php @submit_button(); ?>
					</div><!-- /.section-row -->
					
				</div>
			
				<div id="booked-user-emails" class="tab-content">
					
					<div class="section-row">
						<div class="section-head">
							<p><strong style="font-size:17px; line-height:1.7;"><?php _e('If you do not want to send email notifications for any or all of the following actions, you can just delete the text and an email will not be sent.','booked'); ?></strong></p>
						</div>
					</div>
					
					<div class="section-row">
						<div class="section-head"><?php
							
							$option_name = 'booked_email_logo';
							$booked_email_logo = get_option($option_name);
							$section_title = __('Email Content - Logo Image', 'booked'); ?>
							
							<h3><?php echo esc_attr($section_title); ?></h3>
							<p><?php _e('Choose an image for your custom emails. Keep it 600px or less for best results.','booked'); ?></p>
							
							<input id="<?php echo $option_name; ?>" name="<?php echo $option_name; ?>" type="hidden" />
							<input id="booked_email_logo_button" class="button" name="booked_email_logo_button" type="button" value="Upload Logo" />
						
							<img src="<?php echo $booked_email_logo; ?>" id="booked_email_logo-img">
						</div>
					</div>
					
					<div class="section-row">
						<div class="section-head">
							<?php $option_name = 'booked_registration_email_content';
								
$default_content = 'Hey %name%!

Thanks for registering at '.get_bloginfo('name').'. You can now login to manage your account and appointments using the following credentials:

Username: %username%
Password: %password%

Sincerely,
Your friends at '.get_bloginfo('name');

							$email_content_registration = get_option($option_name,$default_content);
							$section_title = __('Email Content - Registration', 'booked'); ?>
							
							<h3><?php echo esc_attr($section_title); ?></h3>
							<p><?php _e('The email content that is sent to the user upon registration (using the Booked registration form). Some tokens you can use:','booked'); ?></p>
							<ul class="cp-list">
								<li><strong>%name%</strong> &mdash; <?php _e("To display the person's name.","booked"); ?></li>
								<li><strong>%username%</strong> &mdash; <?php _e("To display the username for login.","booked"); ?></li>
								<li><strong>%password%</strong> &mdash; <?php _e("To display the password for login.","booked"); ?></li>
							</ul><br>
							
							<?php
							
							$subject_var = 'booked_registration_email_subject';
							$subject_default = 'Thank you for registering!';
							$current_subject_value = get_option($subject_var,$subject_default); ?>
							
							<input name="<?php echo $subject_var; ?>" value="<?php echo $current_subject_value; ?>" type="text" class="field">
							<textarea name="<?php echo $option_name; ?>" class="field large"><?php echo $email_content_registration; ?></textarea>
						</div>
					</div><!-- /.section-row -->
					
					<div class="section-row" data-controller="cp_fes_controller" data-controlled_by="fes_enabled">
						<div class="section-head">
							<?php $option_name = 'booked_appt_confirmation_email_content';
								
$default_content = 'Hey %name%!

This is just an email to confirm your appointment. For reference, here\'s the appointment information:

Date: %date%
Time: %time%

Sincerely,
Your friends at '.get_bloginfo('name');

							$email_content_approval = get_option($option_name,$default_content);
							$section_title = __('Email Content - Appointment Confirmation', 'booked'); ?>
							
							<h3><?php echo esc_attr($section_title); ?></h3>
							<p><?php _e('The email content that is sent to the user upon appointment creation. Some tokens you can use:','booked'); ?></p>
							<ul class="cp-list">
								<li><strong>%name%</strong> &mdash; <?php _e("To display the person's name.","booked"); ?></li>
								<li><strong>%date%</strong> &mdash; <?php _e("To display the appointment date.","booked"); ?></li>
								<li><strong>%time%</strong> &mdash; <?php _e("To display the appointment time.","booked"); ?></li>
								<li><strong>%customfields%</strong> &mdash; <?php _e("To display all custom field values associated with this appointment.","booked"); ?></li>
							</ul><br>
							
							<?php
							
							$subject_var = 'booked_appt_confirmation_email_subject';
							$subject_default = 'Your appointment confirmation from '.get_bloginfo('name').'.';
							$current_subject_value = get_option($subject_var,$subject_default); ?>
							
							<input name="<?php echo $subject_var; ?>" value="<?php echo $current_subject_value; ?>" type="text" class="field">
							<textarea name="<?php echo $option_name; ?>" class="field large"><?php echo $email_content_approval; ?></textarea>
						</div>
					</div><!-- /.section-row -->
					
					<div class="section-row" data-controller="cp_fes_controller" data-controlled_by="fes_enabled">
						<div class="section-head">
							<?php $option_name = 'booked_approval_email_content';
								
$default_content = 'Hey %name%!

The appointment you requested at '.get_bloginfo('name').' has been approved! Here\'s your appointment information:

Date: %date%
Time: %time%

Sincerely,
Your friends at '.get_bloginfo('name');

							$email_content_approval = get_option($option_name,$default_content);
							$section_title = __('Email Content - Appointment Approval', 'booked'); ?>
							
							<h3><?php echo esc_attr($section_title); ?></h3>
							<p><?php _e('The email content that is sent to the user upon appointment approval. Some tokens you can use:','booked'); ?></p>
							<ul class="cp-list">
								<li><strong>%name%</strong> &mdash; <?php _e("To display the person's name.","booked"); ?></li>
								<li><strong>%date%</strong> &mdash; <?php _e("To display the appointment date.","booked"); ?></li>
								<li><strong>%time%</strong> &mdash; <?php _e("To display the appointment time.","booked"); ?></li>
								<li><strong>%customfields%</strong> &mdash; <?php _e("To display all custom field values associated with this appointment.","booked"); ?></li>
							</ul><br>
							
							<?php
							
							$subject_var = 'booked_approval_email_subject';
							$subject_default = 'Your appointment has been approved!';
							$current_subject_value = get_option($subject_var,$subject_default); ?>
							
							<input name="<?php echo $subject_var; ?>" value="<?php echo $current_subject_value; ?>" type="text" class="field">
							<textarea name="<?php echo $option_name; ?>" class="field large"><?php echo $email_content_approval; ?></textarea>
						</div>
					</div><!-- /.section-row -->
					
					<div class="section-row" data-controller="cp_fes_controller" data-controlled_by="fes_enabled">
						<div class="section-head">
							<?php $option_name = 'booked_cancellation_email_content';
								
$default_content = 'Hey %name%!

The appointment you requested at '.get_bloginfo('name').' has been cancelled. For reference, here\'s the appointment information:

Date: %date%
Time: %time%

Sincerely,
Your friends at '.get_bloginfo('name');

							$email_content_approval = get_option($option_name,$default_content);
							$section_title = __('Email Content - Appointment Cancellation', 'booked'); ?>
							
							<h3><?php echo esc_attr($section_title); ?></h3>
							<p><?php _e('The email content that is sent to the user upon appointment cancellation. Some tokens you can use:','booked'); ?></p>
							<ul class="cp-list">
								<li><strong>%name%</strong> &mdash; <?php _e("To display the person's name.","booked"); ?></li>
								<li><strong>%date%</strong> &mdash; <?php _e("To display the appointment date.","booked"); ?></li>
								<li><strong>%time%</strong> &mdash; <?php _e("To display the appointment time.","booked"); ?></li>
								<li><strong>%customfields%</strong> &mdash; <?php _e("To display all custom field values associated with this appointment.","booked"); ?></li>
							</ul><br>
							
							<?php
							
							$subject_var = 'booked_cancellation_email_subject';
							$subject_default = 'Your appointment has been cancelled.';
							$current_subject_value = get_option($subject_var,$subject_default); ?>
							
							<input name="<?php echo $subject_var; ?>" value="<?php echo $current_subject_value; ?>" type="text" class="field">
							<textarea name="<?php echo $option_name; ?>" class="field large"><?php echo $email_content_approval; ?></textarea>
						</div>
					</div><!-- /.section-row -->
					
					<div class="section-row submit-section" style="padding:0;">
						<?php @submit_button(); ?>
					</div><!-- /.section-row -->
				
				</div><!-- /templates -->
				
				<div id="booked-admin-emails" class="tab-content">
					
					<div class="section-row">
						<div class="section-head">
							<p><strong style="font-size:17px; line-height:1.7;"><?php _e('If you do not want to send email notifications for any or all of the following actions, you can just delete the text and an email will not be sent.','booked'); ?></strong></p>
						</div>
					</div>
						
					<div class="section-row">
						<div class="section-head">
							<?php $option_name = 'booked_admin_appointment_email_content';
								
$default_content = 'You have a new appointment request! Here\'s the appointment information:

Customer: %name%
Date: %date%
Time: %time%

Log into your website here: '.get_admin_url().' to approve this appointment.

(Sent via the '.get_bloginfo('name').' website)';

							$email_content_registration = get_option($option_name,$default_content);
							$section_title = __('New Appointment Request', 'booked'); ?>
							
							<h3><?php echo esc_attr($section_title); ?></h3>
							<p><?php _e('The email content that is sent (to the selected admin users above) upon appointment request. Some tokens you can use:','booked'); ?></p>
							<ul class="cp-list">
								<li><strong>%name%</strong> &mdash; <?php _e("To display the person's name.","booked"); ?></li>
								<li><strong>%date%</strong> &mdash; <?php _e("To display the appointment date.","booked"); ?></li>
								<li><strong>%time%</strong> &mdash; <?php _e("To display the appointment time.","booked"); ?></li>
								<li><strong>%customfields%</strong> &mdash; <?php _e("To display all custom field values associated with this appointment.","booked"); ?></li>
							</ul><br>
							
							<?php
							
							$subject_var = 'booked_admin_appointment_email_subject';
							$subject_default = 'You have a new appointment request!';
							$current_subject_value = get_option($subject_var,$subject_default); ?>
							
							<input name="<?php echo $subject_var; ?>" value="<?php echo $current_subject_value; ?>" type="text" class="field">
							<textarea name="<?php echo $option_name; ?>" class="field large"><?php echo $email_content_registration; ?></textarea>
						</div>
					</div><!-- /.section-row -->
						
					<div class="section-row">
						<div class="section-head">
							<?php $option_name = 'booked_admin_cancellation_email_content';
								
$default_content = 'One of your customers has cancelled their appointment. Here\'s the appointment information:

Customer: %name%
Date: %date%
Time: %time%

(Sent via the '.get_bloginfo('name').' website)';

							$email_content_registration = get_option($option_name,$default_content);
							$section_title = __('Appointment Cancellation', 'booked'); ?>
							
							<h3><?php echo esc_attr($section_title); ?></h3>
							<p><?php _e('The email content that is sent (to the selected admin users above) upon cancellation. Some tokens you can use:','booked'); ?></p>
							<ul class="cp-list">
								<li><strong>%name%</strong> &mdash; <?php _e("To display the person's name.","booked"); ?></li>
								<li><strong>%date%</strong> &mdash; <?php _e("To display the username for login.","booked"); ?></li>
								<li><strong>%time%</strong> &mdash; <?php _e("To display the password for login.","booked"); ?></li>
								<li><strong>%customfields%</strong> &mdash; <?php _e("To display all custom field values associated with this appointment.","booked"); ?></li>
							</ul><br>
							
							<?php
							
							$subject_var = 'booked_admin_cancellation_email_subject';
							$subject_default = 'An appointment has been cancelled.';
							$current_subject_value = get_option($subject_var,$subject_default); ?>
							
							<input name="<?php echo $subject_var; ?>" value="<?php echo $current_subject_value; ?>" type="text" class="field">
							<textarea name="<?php echo $option_name; ?>" class="field large"><?php echo $email_content_registration; ?></textarea>
						</div>
					</div><!-- /.section-row -->
					
					<div class="section-row submit-section" style="padding:0;">
						<?php @submit_button(); ?>
					</div><!-- /.section-row -->
				
				</div><!-- /templates -->
				
			</form>
			
			<div id="booked-custom-fields" class="tab-content">
			
				<div class="section-row">
					<div class="section-head">
						
						<div class="booked-cf-block">
							
							<form id="booked-cf-sortables-form">
								<ul id="booked-cf-sortables"><?php
									
									$custom_fields = json_decode(stripslashes(get_option('booked_custom_fields')),true);
									
									if (!empty($custom_fields)):
									
										$look_for_subs = false;
										
										foreach($custom_fields as $field):
										
											if ($look_for_subs):
											
												$field_type = explode('---',$field['name']);
												$field_type = $field_type[0];
													
												if ($field_type == 'single-checkbox'):
												
													?><li class="ui-state-default"><i class="sub-handle fa fa-bars"></i>
														<input type="text" name="<?php echo $field['name']; ?>" value="<?php echo htmlentities($field['value']); ?>" placeholder="Enter a label for this checkbox..." />
														<span class="cf-delete"><i class="fa fa-close"></i></span>
													</li><?php
												
												elseif ($field_type == 'single-radio-button'):
												
													?><li class="ui-state-default"><i class="sub-handle fa fa-bars"></i>
														<input type="text" name="<?php echo $field['name']; ?>" value="<?php echo htmlentities($field['value']); ?>" placeholder="Enter a label for this radio button..." />
														<span class="cf-delete"><i class="fa fa-close"></i></span>
													</li><?php
														
												elseif ($field_type == 'single-drop-down'):
												
													?><li class="ui-state-default"><i class="sub-handle fa fa-bars"></i>
														<input type="text" name="<?php echo $field['name']; ?>" value="<?php echo htmlentities($field['value']); ?>" placeholder="Enter a label for this option..." />
														<span class="cf-delete"><i class="fa fa-close"></i></span>
													</li><?php
													
												else :
													
													if ($look_for_subs == 'checkboxes'):
													
														?></ul>
														<button class="cfButton button" data-type="checkbox"><i class="fa fa-plus"></i>&nbsp;&nbsp;<?php _e('Checkbox','booked'); ?></button>
														<span class="cf-delete"><i class="fa fa-close"></i></span>
													</li><?php
														
													elseif ($look_for_subs == 'radio-buttons'):
													
														?></ul>
														<button class="cfButton button" data-type="radio-button"><i class="fa fa-plus"></i>&nbsp;&nbsp;<?php _e('Radio Button','booked'); ?></button>
														<span class="cf-delete"><i class="fa fa-close"></i></span>
													</li><?php
														
													elseif ($look_for_subs == 'dropdowns'):
													
														?></ul>
														<button class="cfButton button" data-type="option"><i class="fa fa-plus"></i>&nbsp;&nbsp;<?php _e('Option','booked'); ?></button>
														<span class="cf-delete"><i class="fa fa-close"></i></span>
													</li><?php
														
													endif;
													
													$look_for_subs = false;
												
												endif;
											
											endif;
											
											$field_parts = explode('---',$field['name']);
											$field_type = $field_parts[0];
											$end_of_string = explode('___',$field_parts[1]);
											$numbers_only = $end_of_string[0];
											$is_required = (isset($end_of_string[1]) ? true : false);
											
											switch($field_type):
											
												case 'single-line-text-label' :
												
													?><li class="ui-state-default"><i class="main-handle fa fa-bars"></i>
														<small><?php _e('Single Line Text','booked'); ?></small>
														<p><input class="cf-required-checkbox"<?php if ($is_required): echo ' checked="checked"'; endif; ?> type="checkbox" name="required---<?php echo $numbers_only; ?>" id="required---<?php echo $numbers_only; ?>"> <label for="required---<?php echo $numbers_only; ?>"><?php _e('Required Field','booked'); ?></label></p>
														<input type="text" name="<?php echo $field['name']; ?>" value="<?php echo htmlentities($field['value']); ?>" placeholder="Enter a label for this field..." />
														<span class="cf-delete"><i class="fa fa-close"></i></span>
													</li><?php
												
												break;
												
												case 'paragraph-text-label' :
												
													?><li class="ui-state-default"><i class="main-handle fa fa-bars"></i>
														<small><?php _e('Paragraph Text','booked'); ?></small>
														<p><input class="cf-required-checkbox"<?php if ($is_required): echo ' checked="checked"'; endif; ?> type="checkbox" name="required---<?php echo $numbers_only; ?>" id="required---<?php echo $numbers_only; ?>"> <label for="required---<?php echo $numbers_only; ?>"><?php _e('Required Field','booked'); ?></label></p>
														<input type="text" name="<?php echo $field['name']; ?>" value="<?php echo htmlentities($field['value']); ?>" placeholder="Enter a label for this field..." />
														<span class="cf-delete"><i class="fa fa-close"></i></span>
													</li><?php
												
												break;
												
												case 'checkboxes-label' :
												
													?><li class="ui-state-default"><i class="main-handle fa fa-bars"></i>
														<small><?php _e('Checkboxes','booked'); ?></small>
														<p><input class="cf-required-checkbox"<?php if ($is_required): echo ' checked="checked"'; endif; ?> type="checkbox" name="required---<?php echo $numbers_only; ?>" id="required---<?php echo $numbers_only; ?>"> <label for="required---<?php echo $numbers_only; ?>"><?php _e('Required Field','booked'); ?></label></p>
														<input type="text" name="<?php echo $field['name']; ?>" value="<?php echo htmlentities($field['value']); ?>" placeholder="Enter a label for this checkbox group..." />
														<ul id="booked-cf-checkboxes"><?php
														
													$look_for_subs = 'checkboxes';
												
												break;
												
												case 'radio-buttons-label' :
												
													?><li class="ui-state-default"><i class="main-handle fa fa-bars"></i>
														<small><?php _e('Radio Buttons','booked'); ?></small>
														<p><input class="cf-required-checkbox"<?php if ($is_required): echo ' checked="checked"'; endif; ?> type="checkbox" name="required---<?php echo $numbers_only; ?>" id="required---<?php echo $numbers_only; ?>"> <label for="required---<?php echo $numbers_only; ?>"><?php _e('Required Field','booked'); ?></label></p>
														<input type="text" name="<?php echo $field['name']; ?>" value="<?php echo htmlentities($field['value']); ?>" placeholder="Enter a label for this radio button group..." />
														<ul id="booked-cf-radio-buttons"><?php
														
													$look_for_subs = 'radio-buttons';
												
												break;
												
												case 'drop-down-label' :
												
													?><li class="ui-state-default"><i class="main-handle fa fa-bars"></i>
														<small><?php _e('Drop Down','booked'); ?></small>
														<p><input class="cf-required-checkbox"<?php if ($is_required): echo ' checked="checked"'; endif; ?> type="checkbox" name="required---<?php echo $numbers_only; ?>" id="required---<?php echo $numbers_only; ?>"> <label for="required---<?php echo $numbers_only; ?>"><?php _e('Required Field','booked'); ?></label></p>
														<input type="text" name="<?php echo $field['name']; ?>" value="<?php echo htmlentities($field['value']); ?>" placeholder="Enter a label for this drop-down group..." />
														<ul id="booked-cf-drop-down"><?php
														
													$look_for_subs = 'dropdowns';
												
												break;
											
											endswitch;
										
										endforeach;
										
										if ($look_for_subs):
													
											if ($look_for_subs == 'checkboxes'):
													
												?></ul>
												<button class="cfButton button" data-type="single-checkbox"><i class="fa fa-plus"></i>&nbsp;&nbsp;<?php _e('Checkbox','booked'); ?></button>
												<span class="cf-delete"><i class="fa fa-close"></i></span>
											</li><?php
												
											elseif ($look_for_subs == 'radio-buttons'):
											
												?></ul>
												<button class="cfButton button" data-type="single-radio-button"><i class="fa fa-plus"></i>&nbsp;&nbsp;<?php _e('Radio Button','booked'); ?></button>
												<span class="cf-delete"><i class="fa fa-close"></i></span>
											</li><?php
												
											elseif ($look_for_subs == 'dropdowns'):
											
												?></ul>
												<button class="cfButton button" data-type="single-drop-down"><i class="fa fa-plus"></i>&nbsp;&nbsp;<?php _e('Option','booked'); ?></button>
												<span class="cf-delete"><i class="fa fa-close"></i></span>
											</li><?php
												
											endif;
										
										endif;
										
									endif;
								?></ul>
							</form>
							
							<button class="cfButton button" data-type="single-line-text-label"><i class="fa fa-plus"></i>&nbsp;&nbsp;<?php _e('Single Line Text','booked'); ?></button>&nbsp;
							<button class="cfButton button" data-type="paragraph-text-label"><i class="fa fa-plus"></i>&nbsp;&nbsp;<?php _e('Paragraph Text','booked'); ?></button>&nbsp;
							<button class="cfButton button" data-type="checkboxes-label"><i class="fa fa-plus"></i>&nbsp;&nbsp;<?php _e('Checkboxes','booked'); ?></button>&nbsp;
							<button class="cfButton button" data-type="radio-buttons-label"><i class="fa fa-plus"></i>&nbsp;&nbsp;<?php _e('Radio Buttons','booked'); ?></button>&nbsp;
							<button class="cfButton button" data-type="drop-down-label"><i class="fa fa-plus"></i>&nbsp;&nbsp;<?php _e('Drop Down','booked'); ?></button>
							
						</div>
						
						<ul id="booked-cf-sortable-templates">
								
							<li id="bookedCFTemplate-single-line-text-label" class="ui-state-default"><i class="main-handle fa fa-bars"></i>
								<small><?php _e('Single Line Text','booked'); ?></small>
								<p><input class="cf-required-checkbox" type="checkbox" name="required" id="required"> <label for="required"><?php _e('Required Field','booked'); ?></label></p>
								<input type="text" name="single-line-text-label" value="" placeholder="Enter a label for this field..." />
								<span class="cf-delete"><i class="fa fa-close"></i></span>
							</li>
							<li id="bookedCFTemplate-paragraph-text-label" class="ui-state-default"><i class="main-handle fa fa-bars"></i>
								<small><?php _e('Paragraph Text','booked'); ?></small>
								<p><input class="cf-required-checkbox" type="checkbox" name="required" id="required"> <label for="required"><?php _e('Required Field','booked'); ?></label></p>
								<input type="text" name="paragraph-text-label" value="" placeholder="Enter a label for this field..." />
								<span class="cf-delete"><i class="fa fa-close"></i></span>
							</li>
							<li id="bookedCFTemplate-checkboxes-label" class="ui-state-default"><i class="main-handle fa fa-bars"></i>
								<small><?php _e('Checkboxes','booked'); ?></small>
								<p><input class="cf-required-checkbox" type="checkbox" name="required" id="required"> <label for="required"><?php _e('Required Field','booked'); ?></label></p>
								<input type="text" name="checkboxes-label" value="" placeholder="Enter a label for this checkbox group..." />
								<ul id="booked-cf-checkboxes"></ul>
								<button class="cfButton button" data-type="single-checkbox"><i class="fa fa-plus"></i>&nbsp;&nbsp;<?php _e('Checkbox','booked'); ?></button>
								<span class="cf-delete"><i class="fa fa-close"></i></span>
							</li>
							<li id="bookedCFTemplate-radio-buttons-label" class="ui-state-default"><i class="main-handle fa fa-bars"></i>
								<small><?php _e('Radio Buttons','booked'); ?></small>
								<p><input class="cf-required-checkbox" type="checkbox" name="required" id="required"> <label for="required"><?php _e('Required Field','booked'); ?></label></p>
								<input type="text" name="radio-buttons-label" value="" placeholder="Enter a label for this radio button group..." />
								<ul id="booked-cf-radio-buttons"></ul>
								<button class="cfButton button" data-type="single-radio-button"><i class="fa fa-plus"></i>&nbsp;&nbsp;<?php _e('Radio Button','booked'); ?></button>
								<span class="cf-delete"><i class="fa fa-close"></i></span>
							</li>
							<li id="bookedCFTemplate-drop-down-label" class="ui-state-default"><i class="main-handle fa fa-bars"></i>
								<small><?php _e('Drop Down','booked'); ?></small>
								<p><input class="cf-required-checkbox" type="checkbox" name="required" id="required"> <label for="required"><?php _e('Required Field','booked'); ?></label></p>
								<input type="text" name="drop-down-label" value="" placeholder="Enter a label for this drop-down group..." />
								<ul id="booked-cf-drop-down"></ul>
								<button class="cfButton button" data-type="single-drop-down"><i class="fa fa-plus"></i>&nbsp;&nbsp;<?php _e('Option','booked'); ?></button>
								<span class="cf-delete"><i class="fa fa-close"></i></span>
							</li>
							
							<li id="bookedCFTemplate-single-checkbox" class="ui-state-default "><i class="sub-handle fa fa-bars"></i>
								<input type="text" name="single-checkbox" value="" placeholder="Enter a label for this checkbox..." />
								<span class="cf-delete"><i class="fa fa-close"></i></span>
							</li>
							<li id="bookedCFTemplate-single-radio-button" class="ui-state-default "><i class="sub-handle fa fa-bars"></i>
								<input type="text" name="single-radio-button" value="" placeholder="Enter a label for this radio button..." />
								<span class="cf-delete"><i class="fa fa-close"></i></span>
							</li>
							<li id="bookedCFTemplate-single-drop-down" class="ui-state-default "><i class="sub-handle fa fa-bars"></i>
								<input type="text" name="single-drop-down" value="" placeholder="Enter a label for this option..." />
								<span class="cf-delete"><i class="fa fa-close"></i></span>
							</li>
							
						</ul>
						
					</div>
				</div>
				
				<input id="booked_custom_fields" name="booked_custom_fields" value="<?php echo $custom_fields; ?>" type="hidden" class="field" style="width:100%;">
				
				<div class="section-row submit-section bookedClearFix" style="padding:0;">
					<input id="booked-cf-saveButton" type="button" class="button button-primary" value="<?php _e('Save Custom Fields','booked'); ?>">
					<div class="cf-updater savingState"><i class="fa fa-refresh fa-spin"></i>&nbsp;&nbsp;Saving...</div>
				</div><!-- /.section-row -->
					
			</div><!-- /templates -->
							
			<div id="booked-defaults" class="tab-content">
				
				<?php
			
				$calendars = get_terms('booked_custom_calendars','orderby=slug&hide_empty=0');
											
				if (!empty($calendars)):
					
					?><div id="booked-timeslotsSwitcher"><p>
						<i class="fa fa-calendar"></i><?php
					
						echo '<select name="bookedTimeslotsDisplayed">';
						echo '<option value="">'.__('Default Time Slots','booked').'</option>';
					
						foreach($calendars as $calendar):
							
							?><option value="<?php echo $calendar->term_id; ?>"><?php echo $calendar->name; ?></option><?php
						
						endforeach;
						
						echo '</select>';
						
					?></p></div><?php
					
				endif;
				
				?>
				
				<div id="bookedTimeslotsWrap">
					<?php booked_render_timeslots(); ?>
				</div>
				
				<?php $timeslot_intervals = get_option('booked_timeslot_intervals',60); ?>
				
				<div id="timepickerTemplate">
					<div class="timeslotTabs bookedClearFix">
						<a class="addTimeslotTab active" href="#Single"><?php _e('Single','booked'); ?></a>
						<a class="addTimeslotTab" href="#Bulk"><?php _e('Bulk','booked'); ?></a>
					</div>
					<div class="tsTabContent tsSingle">
						<?php booked_render_time_select('startTime',$timeslot_intervals,__('Start time ...','booked')); ?>
						<?php booked_render_time_select('endTime',$timeslot_intervals,__('End time ...','booked')); ?>
						<?php booked_render_count_select('count','How many?'); ?>
					</div>
					<div class="tsTabContent tsBulk">
						<?php booked_render_time_select('startTime',$timeslot_intervals,__('Start time ...','booked')); ?>
						<?php booked_render_time_select('endTime',$timeslot_intervals,__('End time ...','booked')); ?>
						<?php booked_render_time_between_select('time_between',__('Time between ...','booked')); ?>
						<?php booked_render_interval_select('interval',__('Appt Length ...','booked')); ?>
						<?php booked_render_count_select('count',__('# of Each ...','booked')); ?>
					</div>
				</div>
				
			</div><!-- /templates -->
			
			<div id="booked-shortcodes" class="tab-content">
				
				<div class="section-row" style="margin-bottom:-50px;">
					<div class="section-head">
						
						<h3><?php echo __('Display the Default Calendar', 'booked'); ?></h3>
						<p>You can use this shortcode to display the front-end booking calendar.</p>
						<p><input value="[booked-calendar]" type="text" disabled="disabled" class="field"></p>
						
					</div>
						
					<?php
						
					$calendars = get_terms('booked_custom_calendars','orderby=slug&hide_empty=0');
							
					if (!empty($calendars)):
					
						?><div class="section-head">
							<h3><?php echo __('Display a Custom Calendar', 'booked'); ?></h3>
							<p style="margin:0 0 10px;">&nbsp;</p><?php
					
							foreach($calendars as $calendar):
				
								?><p style="margin:0 0 10px;"><strong style="font-size:14px;"><?php echo $calendar->name; ?></strong></p>
								<input value="[booked-calendar calendar=<?php echo $calendar->term_id; ?>]" type="text" disabled="disabled" class="field"><?php
			
							endforeach;
							
						?></div><?php
					
					endif;
					
					?>
					
					<div class="section-head">
						
						<h3><?php echo __('Display the Login / Register Form', 'booked'); ?></h3>
						<p>If the Registration tab doesn't show up, be sure to allow registrations from the Settings > General page.</p>
						<p><input value="[booked-login]" type="text" disabled="disabled" class="field"></p>
						
					</div>
					
					<div class="section-head">
						
						<h3><?php echo __('Display Appointments List', 'booked'); ?></h3>
						<p>You can use this shortcode to display the currently logged in user's upcoming appointments.</p>
						<p><input value="[booked-appointments]" type="text" disabled="disabled" class="field"></p>
						
					</div>
				
				</div>
				
			</div>
			
		</div>
		
	</div>
</div>