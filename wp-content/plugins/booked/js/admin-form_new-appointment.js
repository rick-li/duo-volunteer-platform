jQuery(document).ready(function($) {
		
	$('form.booked-form').on('click','input[type=submit]',function(e){
		e.preventDefault();
		
		var customerType 		= $('#newAppointmentForm input[name=customer_type]:checked').val(),
			customerID			= $('#newAppointmentForm select[name=user_id]').val(),
			firstName			= $('#newAppointmentForm input[name=first_name]').val(),
			firstNameDefault	= $('#newAppointmentForm input[name=first_name]').attr('title'),
			lastName			= $('#newAppointmentForm input[name=last_name]').val(),
			email				= $('#newAppointmentForm input[name=email]').val(),
			emailDefault		= $('#newAppointmentForm input[name=email]').attr('title'),
			phone				= $('#newAppointmentForm input[name=phone]').val(),
			mobile				= $('#newAppointmentForm input[name=mobile]').val(),
			calendar_id			= $('#newAppointmentForm').attr('data-calendar-id'),
			showRequiredError	= false,
			ajaxRequests 		= [];
			
		$(this).parents('form.booked-form').find('input,textarea,select').each(function(i,field){

			var required = $(this).attr('required');
			
			if (required && $(field).attr('type') == 'hidden'){
				var fieldParts = $(field).attr('name');
				fieldParts = fieldParts.split('---');
				fieldName = fieldParts[0];
				fieldNumber = fieldParts[1].split('___');
				fieldNumber = fieldNumber[0];
				
				if (fieldName == 'radio-buttons-label'){
					var radioValue = false;
					$('input:radio[name="single-radio-button---'+fieldNumber+'[]"]:checked').each(function(){
						if ($(this).val()){
							radioValue = $(this).val();
						}
					});
					if (!radioValue){
						showRequiredError = true;
					}
				} else if (fieldName == 'checkboxes-label'){
					var checkboxValue = false;
					$('input:checkbox[name="single-checkbox---'+fieldNumber+'[]"]:checked').each(function(){
						if ($(this).val()){
							checkboxValue = $(this).val();
						}
					});
					if (!checkboxValue){
						showRequiredError = true;
					}
				}
				
			} else if (required && $(field).attr('type') != 'hidden' && $(field).val() == ''){
	            showRequiredError = true;
	        }
	        
	    });
	    
	    if (showRequiredError){
		    alert(i18n_fill_out_required_fields);
		    return false;
	    }
		
		if (customerType == 'current' && customerID){
		
			$('form.booked-form input').each(function(){
				thisDefault = $(this).attr('title');
				thisVal = $(this).val();
				if (thisDefault == thisVal){ $(this).val(''); }
			});
			
			$(this).val('Please wait...').attr('disabled',true);
			$(this).parents('form').find('button.cancel').attr('disabled',true);
			
			var formData 		= $('form.booked-form').serialize(),
				booked_ajaxURL	= $('#data-ajax-url').html(),
				$activeTD		= $('td.active');
			
			ajaxRequests.push = $.ajaxQueue({
				'url' : booked_ajaxURL,
				'data': formData + '&action=add_appt&calendar_id='+calendar_id,
				success: function(date) {
					$('tr.entryBlock').find('td').load(booked_ajaxURL, {'load':'calendar_date','date':date,'calendar_id':calendar_id},function(){
						$('tr.entryBlock').find('.booked-appt-list').show();
						$('tr.entryBlock').find('.booked-appt-list').addClass('shown');
						$('.bookedAppointmentTab.active').fadeIn(300);
					});
					$activeTD.load(booked_ajaxURL, {'load':'refresh_date_square','date':date,'calendar_id':calendar_id},function(){
						var self = $(this);
						self.replaceWith(self.children());
						adjust_calendar_boxes();
					});
					close_booked_modal();
				}
			});
			
			return false;
			
		} else if (customerType == 'current' && !customerID){
			
			alert(i18n_choose_customer);
			
		}
		
		if (customerType == 'new' && firstName != firstNameDefault && email != emailDefault){
		
			$('form.booked-form input').each(function(){
				thisDefault = $(this).attr('title');
				thisVal = $(this).val();
				if (thisDefault == thisVal){ $(this).val(''); }
			});
			
			$thisButton = $(this);
			
			$thisButton.val('Please wait...').attr('disabled',true);
			$thisButton.parents('form').find('button.cancel').attr('disabled',true);
			
			var formData 		= $('form.booked-form').serialize(),
				booked_ajaxURL	= $('#data-ajax-url').html(),
				$activeTD		= $('td.active');
			
			ajaxRequests.push = $.ajaxQueue({
				'url' : booked_ajaxURL,
				'data': formData + '&action=add_appt&calendar_id='+calendar_id,
				success: function(data) {
				
					data = data.split('###');
				
					if (data[0] != 'success'){
						
						console.log(data[0]);
						
						// There was an error!
						$thisButton.val('Create Appointment').attr('disabled',false);
						$thisButton.parents('form').find('button.cancel').attr('disabled',false);
						
						$('form.booked-form input').each(function(){
							thisDefault = $(this).attr('title');
							thisVal = $(this).val();
							if (!thisVal){ $(this).val(thisDefault); }
						});
						
						alert(data[1]);
					
					} else {
					
						$('tr.entryBlock').find('td').load(booked_ajaxURL, {'load':'calendar_date','date':data[1],'calendar_id':calendar_id},function(){
							$('tr.entryBlock').find('.booked-appt-list').show();
							$('tr.entryBlock').find('.booked-appt-list').addClass('shown');
							$('.bookedAppointmentTab.active').fadeIn(300);
						});
						$activeTD.load(booked_ajaxURL, {'load':'refresh_date_square','date':data[1],'calendar_id':calendar_id},function(){
							var self = $(this);
							self.replaceWith(self.children());
							adjust_calendar_boxes();
						});
						close_booked_modal();
						
					}
				}
			});
			
			return false;
			
		} else if (customerType == 'new' && firstName == firstNameDefault || customerType == 'new' && email == emailDefault){
			
			alert(i18n_appt_required_fields);
			
		}
		
	});
			
});