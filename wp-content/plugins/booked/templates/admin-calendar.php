<section id="booked-plugin-page">
	<div id="data-ajax-url"><?php echo get_admin_url(); ?></div>
	
	<?php
			
	$calendars = get_terms('booked_custom_calendars','orderby=slug&hide_empty=0');
								
	if (!empty($calendars)):
		
		?><div id="booked-calendarSwitcher"><p>
			<i class="fa fa-calendar"></i><?php
		
			echo '<select name="bookedCalendarDisplayed">';
			echo '<option value="">'.__('All Appointments','booked').'</option>';
		
			foreach($calendars as $calendar):
				
				?><option value="<?php echo $calendar->term_id; ?>"><?php echo $calendar->name; ?></option><?php
			
			endforeach;
			
			echo '</select>';
			
		?></p></div><?php
		
	else :
	
		?><div class="noCalendarsSpacer"></div><?php
	
	endif;
	
	?>
		
	<div class="booked-admin-calendar-wrap">
		<?php booked_admin_calendar(); ?>
	</div>
</section>