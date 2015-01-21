jQuery(function() {
	
	/* Accordion */
	jQuery('.simnor-shortcode-toggle-active').each(function() {
		jQuery(this).find('.simnor-shortcode-toggle-content').show();
	});
	jQuery('.simnor-shortcode-toggle .simnor-shortcode-toggle-heading').click(function() {
		var toggle = jQuery(this).parent('.simnor-shortcode-toggle');
		if(jQuery(this).parent('.simnor-shortcode-toggle').parent('div').hasClass('simnor-shortcode-accordion')) {
			toggle.parent('div').find('.simnor-shortcode-toggle').find('.simnor-shortcode-toggle-content:visible').slideUp();
			toggle.parent('div').find('.simnor-shortcode-toggle-active').removeClass('simnor-shortcode-toggle-active');
			toggle.toggleClass('simnor-shortcode-toggle-active');
			toggle.find('.simnor-shortcode-toggle-content').slideToggle(500);
		} else {
			toggle.toggleClass('simnor-shortcode-toggle-active');
			toggle.find('.simnor-shortcode-toggle-content').slideToggle(500);
		}
	});
	
	
	/* Tabs */
	jQuery('.simnor-shortcode-tabs').each(function() {
		
		jQuery(this).prepend('<div class="simnor-shortcode-tab-buttons"></div>');
		jQuery(this).find('.simnor-shortcode-tabpane').each(function() {
			
			jQuery(this).parent('.simnor-shortcode-tabs').find('.simnor-shortcode-tab-buttons').append('<a href="#">'+jQuery(this).find('.simnor-shortcode-tab-label').text()+'</a>');
			jQuery(this).find('.simnor-shortcode-tab-label').remove();
			
		});
		
		jQuery(this).find('.simnor-shortcode-tab-buttons').find('a:first').addClass('active');
		jQuery(this).find('.simnor-shortcode-tabpane').hide();
		jQuery(this).find('.simnor-shortcode-tabpane:first').show();
		
	});
	
	var tab_to_show = 0;
	jQuery(document).on('click', '.simnor-shortcode-tab-buttons a', function() {
		tab_to_show = jQuery(this).parent('.simnor-shortcode-tab-buttons').find('a').index(jQuery(this));
		jQuery(this).parent('.simnor-shortcode-tab-buttons').parent('.simnor-shortcode-tabs').find('.simnor-shortcode-tabpane').hide();
		jQuery(this).parent('.simnor-shortcode-tab-buttons').parent('.simnor-shortcode-tabs').find('.simnor-shortcode-tabpane').eq(tab_to_show).show();
		jQuery(this).parent('.simnor-shortcode-tab-buttons').find('a').removeClass('active');
		jQuery(this).parent('.simnor-shortcode-tab-buttons').find('a').eq(tab_to_show).addClass('active');
		return false;
	});
	
});