;(function($, window, document, undefined) {
	
	var $win = $(window);

	$.fn.spin.presets.booked = {
	 	lines: 9, // The number of lines to draw
		length: 7, // The length of each line
		width: 5, // The line thickness
		radius: 11, // The radius of the inner circle
		corners: 1, // Corner roundness (0..1)
		rotate: 0, // The rotation offset
		direction: 1, // 1: clockwise, -1: counterclockwise
		color: '#555', // #rgb or #rrggbb or array of colors
		speed: 1, // Rounds per second
		trail: 60, // Afterglow percentage
		shadow: false, // Whether to render a shadow
		hwaccel: false, // Whether to use hardware acceleration
		className: 'booked-spinner', // The CSS class to assign to the spinner
		zIndex: 2e9, // The z-index (defaults to 2000000000)
		top: '50%', // Top position relative to parent
		left: '50%' // Left position relative to parent
	}

	$win.on('load', function() {

		var ajaxRequests = [];
		
		// Calendar Next/Prev Click
		$('.booked-calendar-wrap').on('click', '.page-right, .page-left, .monthName a', function(e) {
			
			e.preventDefault();
				
			var $button 			= $(this),
				gotoMonth			= $button.attr('data-goto'),
				booked_ajaxURL		= $('#data-ajax-url').html(),
				thisCalendarWrap 	= $button.parents('.booked-calendar-wrap')
				calendar_id			= $button.parents('table.booked-calendar').attr('data-calendar-id');
			
			savingState(true,thisCalendarWrap);
			thisCalendarWrap.load(booked_ajaxURL, {'load':'calendar_month','gotoMonth':gotoMonth,'calendar_id':calendar_id}, function(){
				adjust_calendar_boxes();
			});
			
			return false;
			
		});
		
		// Calendar Date Click
		$('.booked-calendar-wrap').on('click', 'tr.week td', function(e) {
			
			e.preventDefault();
			
			var $thisDate 			= $(this),
				$thisRow			= $thisDate.parent(),
				date				= $thisDate.attr('data-date'),
				booked_ajaxURL		= $('#data-ajax-url').html(),
				calendar_id			= $thisDate.parents('table.booked-calendar').attr('data-calendar-id');
			
			if ($thisDate.hasClass('blur') || $thisDate.hasClass('booked') || $thisDate.hasClass('prev-date')){
			
				// Do nothing.
			
			} else if ($thisDate.hasClass('active')){
				
				$thisDate.removeClass('active');
				$('tr.entryBlock').remove();
				
			} else {
			
				$('tr.week td').removeClass('active');
				$thisDate.addClass('active');
				
				$('tr.entryBlock').remove();
				$thisRow.after('<tr class="entryBlock loading"><td colspan="7"></td></tr>');
				$('tr.entryBlock').find('td').spin('booked');
				
				$('tr.entryBlock').find('td').load(booked_ajaxURL, {'load':'calendar_date','date':date,'calendar_id':calendar_id},function(){
					$('tr.entryBlock').removeClass('loading');
					$('tr.entryBlock').find('.booked-appt-list').fadeIn(300);
					$('tr.entryBlock').find('.booked-appt-list').addClass('shown');
					adjust_calendar_boxes();
				});
			
			}
			
			adjust_calendar_boxes();
			return false;
			
		});
				
		// New Appointment Click
		$('.booked-calendar-wrap').on('click', 'tr.entryBlock button.new-appt', function(e) {
		
			e.preventDefault();
			
			var $button 		= $(this),
				timeslot		= $button.attr('data-timeslot'),
				date			= $button.attr('data-date'),
				$thisTimeslot	= $button.parents('.timeslot'),
				booked_ajaxURL  = $('#data-ajax-url').html(),
				calendar_id		= $button.parents('table.booked-calendar').attr('data-calendar-id');
			
			create_booked_modal();
			$('.bm-window').load(booked_ajaxURL, {'load':'new_appointment_form','date':date,'timeslot':timeslot,'calendar_id':calendar_id});
			
			return false;
			
		});
		
		// Profile Tabs
		var profileTabs = $('.booked-tabs');
		
		if (!profileTabs.find('li.active').length){
			profileTabs.find('li:first-child').addClass("active");
		}
		
		if (profileTabs.length){
			$('.booked-tab-content').hide();
			var activeTab = profileTabs.find('.active > a').attr('href');
			activeTab = activeTab.split('#');
			activeTab = activeTab[1];
			$('#profile-'+activeTab).show();
			
			profileTabs.find('li > a').on('click', function(e) {
			
				e.preventDefault();
				$('.booked-tab-content').hide();
				profileTabs.find('li').removeClass('active');
				
				$(this).parent().addClass('active');
				var activeTab = $(this).attr('href');
				activeTab = activeTab.split('#');
				activeTab = activeTab[1];
				
				$('#profile-'+activeTab).show();
				return false;
				
			});
		}
		
		// Show Additional Information
		$('.booked-profile-appt-list').on('click', '.booked-show-cf', function(e) {
			
			e.preventDefault();
			var hiddenBlock = $(this).parent().find('.cf-meta-values-hidden');
			hiddenBlock.toggle(0);
			return false;
			
		});
		
		// Check Login/Registration/Forgot Password forms before Submitting
		if ($('#loginform').length){
			$('#loginform input[type="submit"]').on('click',function(e) {
				if ($('#loginform input[name="log"]').val() && $('#loginform input[name="pwd"]').val()){
					$('#loginform .booked-custom-error').hide();
				} else {
					e.preventDefault();
					$('#loginform').parents('.booked-form-wrap').find('.booked-custom-error').fadeOut(200).fadeIn(200);
				}
			});
		}
		
		if ($('#profile-forgot').length){
			$('#profile-forgot input[type="submit"]').on('click',function(e) {
				if ($('#profile-forgot input[name="user_login"]').val()){
					$('#profile-forgot .booked-custom-error').hide();
				} else {
					e.preventDefault();
					$('#profile-forgot').find('.booked-custom-error').fadeOut(200).fadeIn(200);
				}
			});
		}
		
		// Custom Upload Field
		if ($('.booked-upload-wrap').length){
			
			$('.booked-upload-wrap input[type=file]').on('change',function(){
				
				var fileName = $(this).val();
				$(this).parent().find('span').html(fileName);
				$(this).parent().addClass('hasFile');
				
			});
			
		}
		
		// Delete Appointment from Pending List
		$('.booked-profile-appt-list').on('click', '.appt-block .cancel', function(e) {
		
			e.preventDefault();
			
			var $button 		= $(this),
				$thisParent		= $button.parents('.appt-block'),
				appt_id			= $thisParent.attr('data-appt-id'),
				booked_ajaxURL  = $('#data-ajax-url').html();
				
			confirm_delete = confirm(i18n_confirm_appt_delete);
			if (confirm_delete == true){
				
				var currentApptCount = parseInt($('.booked-profile-appt-list').find('h4').find('span.count').html());
				currentApptCount = parseInt(currentApptCount - 1);
				if (currentApptCount < 1){
					$('.booked-profile-appt-list').find('h4').find('span.count').html('0');
					$('.no-appts-message').slideDown('fast');
				} else {
					$('.booked-profile-appt-list').find('h4').find('span.count').html(currentApptCount);
				}
	  		
	  			$thisParent.slideUp('fast',function(){
					$(this).remove();
				});
	  							
				ajaxRequests.push = $.ajaxQueue({
					'url' : booked_ajaxURL,
					'data': {
						'action'     	: 'cancel_appt',
						'appt_id'     	: appt_id
					},
					success: function(data) {
						console.log(data);
					}
				});
				
			}
			
			return false;
			
		});
		
		$('body').on('click','.bm-overlay, .bm-window .close, form.booked-form .cancel',function(e){
			e.preventDefault();
			close_booked_modal();
			return false;
		});
		
		$('body')
		.on('focusin', 'form.booked-form input', function() {
			if(this.title==this.value) {
				$(this).addClass('hasContent');
				this.value = '';
			}
		}).on('focusout', 'form.booked-form input', function(){
			if(this.value==='') {
				$(this).removeClass('hasContent');
				this.value = this.title;
			}
		});
		
		// Adjust the calendar sizing when resizing the window
		$win.resize(function(){
			adjust_calendar_boxes();
		});
		
		// Adjust the calendar sizing on load
		adjust_calendar_boxes();
			
	});
	
	
	
	// Create Booked Modal
	function create_booked_modal(){
		//$('body').css({'overflow':'hidden'});
		$('<div class="booked-modal"><div class="bm-overlay"></div><div class="bm-window"><div style="height:100px"></div></div></div>').appendTo('body');
		$('.booked-modal .bm-window').spin('booked');
	}
	
	// Saving state updater
	function savingState(show,limit_to){
		
		show = typeof show !== 'undefined' ? show : true;
		limit_to = typeof limit_to !== 'undefined' ? limit_to : false;
		
		if (limit_to){
			
			var $savingStateDIV = limit_to.find('li.active .savingState, .topSavingState.savingState, .calendarSavingState');
			var $stuffToHide = limit_to.find('.monthName');
			var $stuffToTransparent = limit_to.find('table.booked-calendar tbody');
			
		} else {
			
			var $savingStateDIV = $('li.active .savingState, .topSavingState.savingState, .calendarSavingState');
			var $stuffToHide = $('.monthName');
			var $stuffToTransparent = $('table.booked-calendar tbody');
			
		}
		
		if (show){
			$savingStateDIV.fadeIn(200);	
			$stuffToHide.hide();
			$stuffToTransparent.animate({'opacity':0.2},100);
		} else {
			$savingStateDIV.hide();
			$stuffToHide.show();
			$stuffToTransparent.animate({'opacity':1},0);
		}
		
	}
	
	$(document).ajaxStop(function() {
		savingState(false);
	});

})(jQuery, window, document);

function close_booked_modal(){
	jQuery('.booked-modal').fadeOut(200);
	jQuery('.booked-modal').addClass('bm-closing');
	//jQuery('body').css({'overflow':'auto'});
	setTimeout(function(){
		jQuery('.booked-modal').remove();
	},300);
}

// Function to adjust calendar sizing
function adjust_calendar_boxes(){
	var boxesWidth = jQuery('.booked-calendar tbody tr.week td').width();
	var calendarHeight = jQuery('.booked-calendar').height();
	boxesHeight = boxesWidth * 0.8;
	jQuery('.booked-calendar tbody tr.week td').height(boxesHeight);
	jQuery('.booked-calendar tbody tr.week td .date').css('line-height',boxesHeight+'px');
	
	var calendarHeight = jQuery('.booked-calendar').height();
	jQuery('.booked-calendar-wrap').height(calendarHeight);
}

// Ajax Queue Function
(function($) {
 
	// jQuery on an empty object, we are going to use this as our Queue
	var ajaxQueue = $({});
	 
	$.ajaxQueue = function( ajaxOpts ) {
	    var jqXHR,
	        dfd = $.Deferred(),
	        promise = dfd.promise();
	 
	    // queue our ajax request
	    ajaxQueue.queue( doRequest );
	 
	    // add the abort method
	    promise.abort = function( statusText ) {
	 
	        // proxy abort to the jqXHR if it is active
	        if ( jqXHR ) {
	            return jqXHR.abort( statusText );
	        }
	 
	        // if there wasn't already a jqXHR we need to remove from queue
	        var queue = ajaxQueue.queue(),
	            index = $.inArray( doRequest, queue );
	 
	        if ( index > -1 ) {
	            queue.splice( index, 1 );
	        }
	 
	        // and then reject the deferred
	        dfd.rejectWith( ajaxOpts.context || ajaxOpts, [ promise, statusText, "" ] );
	        return promise;
	    };
	 
	    // run the actual query
	    function doRequest( next ) {
	        jqXHR = $.ajax( ajaxOpts )
	            .done( dfd.resolve )
	            .fail( dfd.reject )
	            .then( next, next );
	    }
	 
	    return promise;
	};



		console.log('=====dfahdfkahfkasdhdkfdas')
		// Calendar Switcher
		var $ = jQuery;


		$(document).ready(function() {
				console.log('=====dfahdfkahfkasdhdkfdas', $('#booked-calendarSwitcher'))
		$('#booked-calendarSwitcher').on('change','select[name="bookedCalendarDisplayed"]',function(e){
			// debugger;
			console.log('Calendar siwtcher click====');
			var calendar_id = $(this).val(),
				booked_ajaxURL = $('#data-ajax-url').html(),
				currentMonth = $('table.booked-calendar').attr('data-monthShown');
			
			savingState(true);
			$('.booked-admin-calendar-wrap').load(booked_ajaxURL, {'load':'calendar_month','gotoMonth':currentMonth,'calendar_id':calendar_id}, function(){
				adjust_calendar_boxes();
			});
			
		});
		
		// Calendar Next/Prev Click
		$('.booked-admin-calendar-wrap').on('click', '.page-right, .page-left, .monthName a', function(e) {
			
			e.preventDefault();
			
			var $button 		= $(this),
				gotoMonth		= $button.attr('data-goto'),
				booked_ajaxURL	= $('#data-ajax-url').html(),
				calendar_id		= $('table.booked-calendar').attr('data-calendar-id');
			
			savingState(true);
			$('.booked-admin-calendar-wrap').load(booked_ajaxURL, {'load':'calendar_month','gotoMonth':gotoMonth,'calendar_id':calendar_id}, function(){
				adjust_calendar_boxes();
			});
			
			return false;
			
		});
		
		// Calendar Date Click
		$('.booked-admin-calendar-wrap').on('click', 'tr.week td', function(e) {
			
			e.preventDefault();
			
			var $thisDate 		= $(this),
				$thisRow		= $thisDate.parent(),
				date			= $thisDate.attr('data-date'),
				booked_ajaxURL	= $('#data-ajax-url').html(),
				calendar_id		= $('table.booked-calendar').attr('data-calendar-id');
			
			if ($thisDate.hasClass('blur')){
			
				// Do nothing.
			
			} else if ($thisDate.hasClass('active')){
				
				$thisDate.removeClass('active');
				$('tr.entryBlock').remove();
				
			} else {
			
				$('tr.week td').removeClass('active');
				$thisDate.addClass('active');
				
				$('tr.entryBlock').remove();
				$thisRow.after('<tr class="entryBlock loading"><td colspan="7"></td></tr>');
				$('tr.entryBlock').find('td').spin('booked');
				
				$('tr.entryBlock').find('td').load(booked_ajaxURL, {'load':'calendar_date','date':date,'calendar_id':calendar_id},function(){
					$('tr.entryBlock').removeClass('loading');
					$('tr.entryBlock').find('.booked-appt-list').fadeIn(300);
					$('tr.entryBlock').find('.booked-appt-list').addClass('shown');
					$('.bookedAppointmentTab.active').fadeIn(300);
				});
			
			}
			
			return false;
			
		});
		
		// Delete Appointment Click from Calendar
		$('.booked-admin-calendar-wrap').on('click', 'tr.entryBlock .delete', function(e) {
		
			e.preventDefault();
			
			var $button 		= $(this),
				$thisParent		= $button.parents('.timeslot'),
				$thisTimeslot	= $button.parents('.timeslot'),
				$activeTD		= $('td.active'),
				$addlParent		= $thisTimeslot.parents('.additional-timeslots'),
				appt_id			= $thisTimeslot.attr('data-appt-id'),
				booked_ajaxURL	= $('#data-ajax-url').html(),
				date			= $activeTD.attr('data-date'),
				calendar_id		= $button.attr('data-calendar-id');
				
				
			if (!appt_id){
				appt_id			= $button.parents('.appt-block').attr('data-appt-id');
				$thisParent		= $button.parents('.appt-block');
			}
			
			confirm_appt_delete = confirm(i18n_confirm_appt_delete);  		
	  		if (confirm_appt_delete == true){
	  	
		    	$thisParent.slideUp('fast',function(){
					$(this).remove();
					if ($addlParent.length){
		  				if (!$addlParent.find('.timeslot').length){
			  				$addlParent.remove();
		  				}
		  			} else {
			  			if (!$thisTimeslot.find('.appt-block').length){
				  			$thisTimeslot.find('strong').remove();
			  			}
		  			}
				});
				
				$thisTimeslot.addClass('faded');
				
				ajaxRequests.push = $.ajaxQueue({
					'url' : booked_ajaxURL,
					'data': {
						'action'     	: 'delete_appt',
						'appt_id'     	: appt_id
					},
					success: function(data) {
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
					}
				});
			
			}
			
			return false;
			
		});
		
		// Approve Appointment in Calendar
		$('.booked-admin-calendar-wrap').on('click', 'tr.entryBlock .approve', function(e) {
		
			e.preventDefault();
			
			var $button 		= $(this),
				$thisParent		= $button.parents('.timeslot'),
				appt_id			= $thisParent.attr('data-appt-id'),
				booked_ajaxURL	= $('#data-ajax-url').html();
				
			if (!appt_id){
				$thisParent		= $button.parents('.appt-block');
				appt_id			= $button.attr('data-appt-id');
			}
			
			confirm_appt_approve = confirm(i18n_confirm_appt_approve);
			if (confirm_appt_approve == true){
	  	
		    	$button.remove();
		    	$thisParent.find('.pending-text').remove();
				
				ajaxRequests.push = $.ajaxQueue({
					'url' : booked_ajaxURL,
					'data': {
						'action'     	: 'approve_appt',
						'appt_id'     	: appt_id
					},
					success: function(data) {
						// Do nothing
					}
				});
			
			}
			
			return false;
			
		});
		
		// User Info Click
		$('.booked-admin-calendar-wrap').on('click', 'tr.entryBlock .user', function(e) {
		
			e.preventDefault();
			
			var $thisLink 		= $(this),
				user_id			= $thisLink.attr('data-user-id'),
				appt_id			= $thisLink.parent().attr('data-appt-id'),
				booked_ajaxURL	= $('#data-ajax-url').html();
			
			create_booked_modal();
			$('.bm-window').load(booked_ajaxURL, {'load':'user_info_modal','user_id':user_id,'appt_id':appt_id},function(){
				// Loaded!
			});
			
			return false;
			
		});
		
		// User Info Click
		$('.booked-pending-appt-list').on('click', '.user', function(e) {
		
			e.preventDefault();
			
			var $thisLink 		= $(this),
				user_id			= $thisLink.attr('data-user-id'),
				appt_id			= $thisLink.parent().attr('data-appt-id'),
				booked_ajaxURL	= $('#data-ajax-url').html();
			
			create_booked_modal();
			$('.bm-window').load(booked_ajaxURL, {'load':'user_info_modal','user_id':user_id,'appt_id':appt_id},function(){
				// Loaded!
			});
			
			return false;
			
		});
		
		$('.booked-admin-calendar-wrap').on('click', '#bookedAppointmentTabs li a', function(e) {
			
			e.preventDefault();
			
			var $thisTab = $(this);
			var tabName	= $thisTab.attr('href').split('#calendar-');
			tabName = tabName[1];
			
			$('#bookedAppointmentTabs li').removeClass('active');
			$('.bookedAppointmentTab').hide();
			$('.bookedAppointmentTab').removeClass('active');
			
			$thisTab.parent().addClass('active');
			
			$('#bookedCalendarAppointmentsTab-'+tabName).fadeIn(100);
			$('#bookedCalendarAppointmentsTab-'+tabName).addClass('active');
	
			return false;
			
		});
		
		// New Appointment Click
		$('.booked-admin-calendar-wrap').on('click', 'tr.entryBlock button.new-appt', function(e) {
		
			e.preventDefault();
			
			var $button 		= $(this),
				timeslot		= $button.attr('data-timeslot'),
				date			= $button.attr('data-date'),
				$thisTimeslot	= $button.parents('.timeslot'),
				booked_ajaxURL	= $('#data-ajax-url').html(),
				calendar_id		= $button.attr('data-calendar-id');
			
			create_booked_modal();
			$('.bm-window').load(booked_ajaxURL, {'load':'new_appointment_form','date':date,'timeslot':timeslot,'calendar_id':calendar_id},function(){
				$('select#userList').chosen();
			});
			
			return false;
			
		});
		
		// Delete Appointment from Pending List
		$('.booked-pending-appt-list').on('click', '.pending-appt .delete', function(e) {
		
			e.preventDefault();
			
			var $button 		= $(this),
				$thisParent		= $button.parents('.pending-appt'),
				appt_id			= $thisParent.attr('data-appt-id'),
				booked_ajaxURL	= $('#data-ajax-url').html();
			
			confirm_appt_delete = confirm(i18n_confirm_appt_delete);
			if (confirm_appt_delete == true){
	  		
	  			var currentPendingCount = parseInt($('li.toplevel_page_booked-appointments').find('li.current').find('span.update-count').html());
				currentPendingCount = parseInt(currentPendingCount - 1);
				if (currentPendingCount < 1){
					$('li.toplevel_page_booked-appointments').find('li.current').find('span.update-plugins').remove();
					$('.no-pending-message').slideDown('fast');
				} else {
					$('li.toplevel_page_booked-appointments').find('li.current').find('span.update-count').html(currentPendingCount);
				}
	  		
	  			$thisParent.slideUp('fast',function(){
					$(this).remove();
				});
				
	  			savingState(true);
	  							
				ajaxRequests.push = $.ajaxQueue({
					'url' : booked_ajaxURL,
					'data': {
						'action'     	: 'delete_appt',
						'appt_id'     	: appt_id
					},
					success: function(data) {
						savingState(false);
					}
				});
			
			}
			
			return false;
			
		});
		
		// Approve Appointment from Pending List
		$('.booked-pending-appt-list').on('click', '.pending-appt .approve', function(e) {
		
			e.preventDefault();
			
			var $button 		= $(this),
				$thisParent		= $button.parents('.pending-appt'),
				appt_id			= $thisParent.attr('data-appt-id'),
				booked_ajaxURL			= $('#data-ajax-url').html();
			
			confirm_appt_approve = confirm(i18n_confirm_appt_approve);
			if (confirm_appt_approve == true){
				
				var currentPendingCount = parseInt($('li.toplevel_page_booked-appointments').find('li.current').find('span.update-count').html());
				currentPendingCount = parseInt(currentPendingCount - 1);
				if (currentPendingCount < 1){
					$('li.toplevel_page_booked-appointments').find('li.current').find('span.update-plugins').remove();
					$('.no-pending-message').slideDown('fast');
				} else {
					$('li.toplevel_page_booked-appointments').find('li.current').find('span.update-count').html(currentPendingCount);
				}
				
				$thisParent.slideUp('fast',function(){
					$(this).remove();
				});
				
	  			savingState(true);
	  	
		  		ajaxRequests.push = $.ajaxQueue({
					'url' : booked_ajaxURL,
					'data': {
						'action'     	: 'approve_appt',
						'appt_id'     	: appt_id
					},
					success: function(data) {
						savingState(false);
					}
				});
			
			}
			
			return false;
			
		});
		});

		
	
	 
})(jQuery);