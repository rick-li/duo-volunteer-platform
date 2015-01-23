 var etm_manual_pos_save = '';
 var etm_save_radius = 0;
 var etm_site_hidden = false;
 
;( function( window ) {
	
	'use strict';

	/**
	 * based on from https://github.com/inuyaksa/jquery.nicescroll/blob/master/jquery.nicescroll.js
	 */
	function hasParent( e, p ) {
		if (!e) return false;
		var el = e.target||e.srcElement||e||false;
		while (el && el != p) {
			el = el.parentNode||false;
		}
		return (el!==false);
	};
	
	/**
	 * extend obj function
	 */
	function extend( a, b ) {
		for( var key in b ) { 
			if( b.hasOwnProperty( key ) ) {
				a[key] = b[key];
			}
		}
		return a;
	}

	/**
	 * SelectFx function
	 */
	function SelectFx( el, options ) {	
		this.el = el;
		this.options = extend( {}, this.options );
		extend( this.options, options );
		this._init();
	}

	/**
	 * SelectFx options
	 */
	SelectFx.prototype.options = {
		// if true all the links will open in a new tab.
		// if we want to be redirected when we click an option, we need to define a data-link attr on the option of the native select element
		newTab : true,
		// when opening the select element, the default placeholder (if any) is shown
		stickyPlaceholder : true,
		// callback when changing the value
		onChange : function( val ) { return false; },
		onClick : function(val) { return false; },
		onClickBack : function(val) { return false; }
	}

	/**
	 * init function
	 * initialize and cache some vars
	 */
	SelectFx.prototype._init = function() {
		// check if we are using a placeholder for the native select box
		// we assume the placeholder is disabled and selected by default
		var selectedOpt = this.el.querySelector( 'option[selected]' );
		this.hasDefaultPlaceholder = selectedOpt && selectedOpt.disabled;

		// get selected option (either the first option with attr selected or just the first option)
		this.selectedOpt = selectedOpt || this.el.querySelector( 'option' );

		// create structure
		this._createSelectEl();

		// all options
		this.selOpts = [].slice.call( this.selEl.querySelectorAll( 'li[data-option]' ) );
		
		// total options
		this.selOptsCount = this.selOpts.length;
		
		// current index
		this.current = this.selOpts.indexOf( this.selEl.querySelector( 'li.cs-selected' ) ) || -1;
		
		// placeholder elem
		this.selPlaceholder = this.selEl.querySelector( 'span.cs-placeholder' );

		// init events
		this._initEvents();
	}

	/**
	 * creates the structure for the select element
	 */
	SelectFx.prototype._createSelectEl = function() {
		var self = this, options = '', createOptionHTML = function(el) {
			var optclass = '', classes = '', link = '';

			if( el.selectedOpt && !this.foundSelected && !this.hasDefaultPlaceholder ) {
				classes += 'cs-selected ';
				this.foundSelected = true;
			}
			// extra classes
			if( el.getAttribute( 'data-class' ) ) {
				classes += el.getAttribute( 'data-class' );
			}
			// link options
			if( el.getAttribute( 'data-link' ) ) {
				link = 'data-link=' + el.getAttribute( 'data-link' );
			}

			if( classes !== '' ) {
				optclass = 'class="' + classes + '" ';
			}

			return '<li ' + optclass + link + ' data-option data-value="' + el.value + '"><span>' + el.textContent + '</span></li>';
		};

		[].slice.call( this.el.children ).forEach( function(el) {
			if( el.disabled ) { return; }

			var tag = el.tagName.toLowerCase();

			if( tag === 'option' ) {
				options += createOptionHTML(el);
			}
			else if( tag === 'optgroup' ) {
				options += '<li class="cs-optgroup"><span>' + el.label + '</span><ul>';
				[].slice.call( el.children ).forEach( function(opt) {
					options += createOptionHTML(opt);
				} )
				options += '</ul></li>';
			}
		} );

		var opts_el = '<div class="cs-options"><ul>' + options + '</ul></div>';
		this.selEl = document.createElement( 'div' );
		this.selEl.className = this.el.className;
		this.selEl.tabIndex = this.el.tabIndex;
		this.selEl.innerHTML = '<span class="cs-placeholder">' + this.selectedOpt.textContent + '</span>' + opts_el;
		this.el.parentNode.appendChild( this.selEl );
		this.selEl.appendChild( this.el );
	}

	/**
	 * initialize the events
	 */
	SelectFx.prototype._initEvents = function() {
		var self = this;

		// open/close select
		this.selPlaceholder.addEventListener( 'click', function() {
			self._toggleSelect();
		} );

		// clicking the options
		this.selOpts.forEach( function(opt, idx) {
			opt.addEventListener( 'click', function() {
				self.current = idx;
				self._changeOption();
				// close select elem
				self._toggleSelect();
			} );
		} );

		// close the select element if the target it´s not the select element or one of its descendants..
		document.addEventListener( 'click', function(ev) {
			var target = ev.target;
			if( self._isOpen() && target !== self.selEl && !hasParent( target, self.selEl ) ) {
				self._toggleSelect();
			}
			
			if( self._isOpen() && jQuery(self.selEl).hasClass( "cs-skin-boxes" ) && ((jQuery(self.selEl).hasClass( "cs-active" ) && jQuery(target).is( "ul" )) || (jQuery(target).hasClass('cs-options') && jQuery(target).is( "div" )))){
				self._toggleSelect();
			}
		} );

		// keyboard navigation events
		this.selEl.addEventListener( 'keydown', function( ev ) {
			var keyCode = ev.keyCode || ev.which;

			switch (keyCode) {
				// up key
				case 38:
					ev.preventDefault();
					self._navigateOpts('prev');
					break;
				// down key
				case 40:
					ev.preventDefault();
					self._navigateOpts('next');
					break;
				// space key
				case 32:
					ev.preventDefault();
					if( self._isOpen() && typeof self.preSelCurrent != 'undefined' && self.preSelCurrent !== -1 ) {
						self._changeOption();
					}
					self._toggleSelect();
					break;
				// enter key
				case 13:
					ev.preventDefault();
					if( self._isOpen() && typeof self.preSelCurrent != 'undefined' && self.preSelCurrent !== -1 ) {
						self._changeOption();
						self._toggleSelect();
					}
					break;
				// esc key
				case 27:
					ev.preventDefault();
					if( self._isOpen() ) {
						self._toggleSelect();
					}
					break;
			}
		} );
	}

	/**
	 * navigate with up/dpwn keys
	 */
	SelectFx.prototype._navigateOpts = function(dir) {
		if( !this._isOpen() ) {
			this._toggleSelect();
		}

		var tmpcurrent = typeof this.preSelCurrent != 'undefined' && this.preSelCurrent !== -1 ? this.preSelCurrent : this.current;
		
		if( dir === 'prev' && tmpcurrent > 0 || dir === 'next' && tmpcurrent < this.selOptsCount - 1 ) {
			// save pre selected current - if we click on option, or press enter, or press space this is going to be the index of the current option
			this.preSelCurrent = dir === 'next' ? tmpcurrent + 1 : tmpcurrent - 1;
			// remove focus class if any..
			this._removeFocus();
			// add class focus - track which option we are navigating
			classie.add( this.selOpts[this.preSelCurrent], 'cs-focus' );
		}
	}

	/**
	 * open/close select
	 * when opened show the default placeholder if any
	 */
	SelectFx.prototype._toggleSelect = function() {
		// remove focus class if any..
		this._removeFocus();
		if( this._isOpen() ) {
			if( this.current !== -1 ) {
				// update placeholder text
				this.selPlaceholder.textContent = this.selOpts[ this.current ].textContent;
			}
			etm_manual_pos_save = this.selEl;
			setTimeout(function() {jQuery(etm_manual_pos_save).css('z-index',100);jQuery(etm_manual_pos_save).find('span.cs-placeholder').css('z-index','');},500);
			this.options.onClickBack(etm_manual_pos_save);
			classie.remove( this.selEl, 'cs-active' );	
		}
		else {
			
		
			if( this.hasDefaultPlaceholder && this.options.stickyPlaceholder ) {
				// everytime we open we wanna see the default placeholder text
				this.selPlaceholder.textContent = this.selectedOpt.textContent;
			}
			classie.add( this.selEl, 'cs-active' );
			

			if(jQuery(this.selEl).hasClass('cs-skin-circular')){
				jQuery(this.selEl).find('span.cs-placeholder').css('z-index',99999999);
			}

			jQuery(this.selEl).css('z-index',9999999);
			this.options.onClick(this.selEl);
		}
		
		
	}

	/**
	 * change option - the new value is set
	 */
	SelectFx.prototype._changeOption = function() {
		// if pre selected current (if we navigate with the keyboard)...
		if( typeof this.preSelCurrent != 'undefined' && this.preSelCurrent !== -1 ) {
			this.current = this.preSelCurrent;
			this.preSelCurrent = -1;
		}

		// current option
		var opt = this.selOpts[ this.current ];

		// update current selected value
		this.selPlaceholder.textContent = opt.textContent;
		
		// change native select element´s value
		this.el.value = opt.getAttribute( 'data-value' );

		// remove class cs-selected from old selected option and add it to current selected option
		var oldOpt = this.selEl.querySelector( 'li.cs-selected' );
		if( oldOpt ) {
			classie.remove( oldOpt, 'cs-selected' );
		}
		//classie.add( opt, 'cs-selected' );

		// if there´s a link defined
		if( opt.getAttribute( 'data-link' ) ) {
			// open in new tab?
			if( this.options.newTab ) {
				setTimeout(function() {window.open( opt.getAttribute( 'data-link' ), '_blank' )},500);
			}
			else {
				setTimeout(function() {window.location = opt.getAttribute( 'data-link' )},500);
			}
		}

		// callback
		this.options.onChange( this.el.value );
		
	}

	/**
	 * returns true if select element is opened
	 */
	SelectFx.prototype._isOpen = function(opt) {
		return classie.has( this.selEl, 'cs-active' );
	}

	/**
	 * removes the focus class from the option
	 */
	SelectFx.prototype._removeFocus = function(opt) {
		var focusEl = this.selEl.querySelector( 'li.cs-focus' )
		if( focusEl ) {
			classie.remove( focusEl, 'cs-focus' );
		}
	}

	/**
	 * add to global namespace
	 */
	window.SelectFx = SelectFx;

} )( window );

jQuery(document).ready(function($){
	[].slice.call( document.querySelectorAll( 'select.cs-select' ) ).forEach( function(el) {	new SelectFx(el, {
		stickyPlaceholder: false,
		newTab:false,
		onClick : function(tmp_object){
			if (jQuery('.etm_menu .cs-skin-boxes.cs-active').length > 0) { 
				if(jQuery('.site').css('overflow') == 'hidden'){
					jQuery('.site').css('overflow','visible');
					etm_site_hidden = true;
				}
				etm_menu_resizer();
			}
			if (jQuery('.etm_menu .cs-skin-slide.cs-active').length > 0) {					
				jQuery('.etm_menu .cs-skin-slide.cs-active .cs-options li.removethis').remove();
			}
			
			if (jQuery('.etm_menu .cs-skin-circular.cs-active').length > 0) { 
				etm_menu_cirkle_fn();
				etm_save_radius = 0;
			}	
		},
		onClickBack: function(tmp_object){
			if(jQuery(tmp_object).hasClass('cs-skin-circular')){
				jQuery(tmp_object).find('.cs-options li').css('-webkit-transform','scale3d(0,0,0) rotate(0deg) translate3d(0, 0, 0)');
				jQuery(tmp_object).find('.cs-options li').css('transform','scale3d(0,0,0) rotate(0deg) translate3d(0, 0, 0)');
			}
			
			if(etm_site_hidden){
				jQuery('.site').css('overflow','hidden');
				etm_site_hidden = false;
			}
		},
		onChange: function(val){
			if (jQuery('.etm_menu .cs-skin-slide.cs-active ').length > 0) { 
				jQuery('.etm_menu .cs-skin-slide.cs-active  .cs-placeholder').css('background-image', 'url(' + val + ')');
			}
			
			if (jQuery('.etm_menu .cs-skin-rotate.cs-active ').length > 0) { 
				jQuery('.etm_menu .cs-skin-rotate.cs-active  .cs-placeholder').css('background-image', 'url(' + val + ')');
			}
			
			if (jQuery('.etm_menu .cs-skin-elastic.cs-active ').length > 0) { 
				jQuery('.etm_menu .cs-skin-elastic.cs-active  .cs-placeholder').css('background-image', 'url(' + val + ')');
			}
			
			if (jQuery('.etm_menu .cs-skin-circular.cs-active ').length > 0) { 
				jQuery('.etm_menu .cs-skin-circular.cs-active  .cs-placeholder').css('background-image', 'url(' + val + ')');
			}		
			
			if (jQuery('.etm_menu .cs-skin-boxes.cs-active ').length > 0) { 
				jQuery('.etm_menu .cs-skin-boxes.cs-active  .cs-placeholder').css('background-image', 'url(' + val + ')');
			}			
		}});
	});
	
	jQuery( window ).resize(etm_resize);
	jQuery( window ).scroll(etm_menu_cirkle_fn);
	
	jQuery('.etm_menu.menu_display1 .cs-skin-boxes .cs-placeholder').html('&nbsp;');
	
	if (jQuery('.etm_menu .cs-skin-slide').length > 0) { 
		jQuery('.etm_menu .cs-skin-slide').each(function(){	
			jQuery(this).css('width',jQuery(this).width());
		})				
	}	
	
});

function etm_resize(){
	if (jQuery('.etm_menu .cs-skin-boxes.cs-active').length > 0) { 
		etm_menu_resizer();
	}	
	if (jQuery('.etm_menu .cs-skin-circular.cs-active').length > 0) { 
		etm_menu_cirkle_fn();
	}
}



function etm_menu_cirkle_fn(){
	if(jQuery('.etm_menu .cs-skin-circular.cs-active').length > 0){
	
		jQuery('.etm_menu .cs-skin-circular.cs-active .cs-options li.removethis').remove();
		screen_h = jQuery(window).height();
		screen_w = jQuery(window).width();
		scroll_t = jQuery(window).scrollTop();
		scroll_l = jQuery(window).scrollLeft();
		start_px = end_px = 0;
		offset_object = new Object();
		offset_object.left = 0;
		offset_object.top = 0;
		offset_object.right = 0;
		offset_object.bottom = 0;
		postion = jQuery('.etm_menu .cs-skin-circular.cs-active').parent().offset();
		postion.left += jQuery('.etm_menu .cs-select.cs-active.cs-skin-circular').width()/2;
		postion.top += jQuery('.etm_menu .cs-select.cs-active.cs-skin-circular').height()/2;
		pos = new Array(0,0,0,0);
		
		count_li = jQuery('.etm_menu .cs-skin-circular.cs-active .cs-options li').length;
		count_li_extra = count_li;
		current_object_overflow = jQuery('.etm_menu .cs-skin-circular.cs-active');
		
		for(var j=0;j<20;j++){
			current_object_overflow = jQuery(current_object_overflow).parent();

			if(jQuery(current_object_overflow).css('overflow') != 'undefined' && jQuery(current_object_overflow).css('overflow') != '' && jQuery(current_object_overflow).css('overflow') != undefined && jQuery(current_object_overflow).css('overflow') != 'visible'){
				offset_object = jQuery(current_object_overflow).offset();
				offset_object.right = offset_object.left + jQuery(current_object_overflow).width();	
				offset_object.bottom = offset_object.top + jQuery(current_object_overflow).height();
				break;
			}
			if(jQuery(current_object_overflow).is('body')){
				break;
			}
		}	
		

		radius_line = parseInt(160+30);
		
		if(offset_object.top > 0 && 0 < (offset_object.top-scroll_t)){
			pos[0] = postion.top-offset_object.top;
		} else {
			pos[0] = postion.top-scroll_t;
		}
		
		if(offset_object.right > 0){
			pos[1] = offset_object.right-(postion.left)
		} else {
			pos[1] = screen_w-(postion.left);
		}	
		
		if(offset_object.bottom > 0 &&  0 < ((scroll_t+screen_h)-offset_object.bottom)){
			pos[2] = offset_object.bottom-(postion.top);
		} else {
			pos[2] = screen_h-(postion.top-scroll_t);
		}		

		if(offset_object.left > 0 && 0 < (offset_object.left-scroll_l)){
			pos[3] = postion.left-offset_object.left;
		} else {
			pos[3] = postion.left-scroll_l;
		}
		
		if(pos[0]< 0){pos[0] = 10;} else {pos[0] = parseInt(pos[0]);}
		if(pos[1]< 0){pos[1] = 10;} else {pos[1] = parseInt(pos[1]);}		
		if(pos[2]< 0){pos[2] = 10;} else {pos[2] = parseInt(pos[2]);}		
		if(pos[3]< 0){pos[3] = 10;} else {pos[3] = parseInt(pos[3]);}		
		
		if(pos[0] >= radius_line && pos[1] >= radius_line && pos[2] >= radius_line && pos[3] >= radius_line){
			start_px = 0;
			end_px = 360;
		} 
		
		if(start_px == 0 && end_px == 0){
			for(var b=0;b<4;b++){
				tmp_var1 = b;
				tmp_var2 = 1+b;
				tmp_var3 = 2+b;
				tmp_var4 = 3+b;
				
				if(tmp_var1 > 3){tmp_var1 -= 4}
				if(tmp_var2 > 3){tmp_var2 -= 4}
				if(tmp_var3 > 3){tmp_var3 -= 4}
				if(tmp_var4 > 3){tmp_var4 -= 4}
				if(pos[tmp_var1] >= radius_line && pos[tmp_var2] >= radius_line && pos[tmp_var3] < radius_line && pos[tmp_var4] >= radius_line){	
					v = Math.asin((pos[tmp_var3])/radius_line)* (radius_line/Math.PI);
					
					start_px = -90 + (b*90);
					end_px = 90 + (b*90);
	
					if(v > 0){
						start_px -= v;
						end_px += v;
					}
					count_li_extra += 1;
					count_li += 2;
					break;
				}
				
				if(pos[tmp_var1] >= radius_line && pos[tmp_var2] >= radius_line && pos[tmp_var3] < radius_line && pos[tmp_var4] < radius_line){
					sv = Math.asin((pos[tmp_var4])/radius_line)* (160/Math.PI);
					ev = Math.asin((pos[tmp_var3])/radius_line)* (160/Math.PI);
					
					start_px = 0 + (b*90);
					end_px = 90 + (b*90);
					if(sv > 0){
						start_px -= sv;
					}
					if(ev > 0){
						end_px += ev;
					}
				
					count_li_extra += 1;
					count_li += 2;
					break
				}
				
			}
		}
		
		if(start_px == 0 && end_px == 0){
			return '';
		}

		surface=(((2*160*Math.PI)*((end_px-start_px))/360)/(count_li));
		if(surface > 100){
			surface = 100;
		}
	
		start_px -= 90;
		end_px -= 90;
		different = (end_px-start_px)/(count_li_extra);
		
		count = 1;
		jQuery('.etm_menu .cs-skin-circular.cs-active .cs-options li').each(function (){
			
			start_x = Math.cos(((start_px+(different*(count))) * .017453292519943295))*160;
			start_y = Math.sin(((start_px+(different*(count))) * .017453292519943295))*160;
			
			if(start_x > 0){
				start_x = parseInt(start_x+0.5);
			} else {
				start_x = parseInt(start_x-0.5);
			}
			if(start_y > 0){
				start_y = parseInt(start_y+0.5);
			} else {
				start_y = parseInt(start_y-0.5);
			}

			jQuery(this).css('-webkit-transform','scale3d(1,1,1) rotate(0deg) translate3d('+start_x+'px, '+start_y+'px, 0)');
			jQuery(this).css('transform','scale3d(1,1,1) rotate(0deg) translate3d('+start_x+'px, '+start_y+'px, 0)');
			jQuery(this).css('width',surface+'px');
			jQuery(this).css('height',surface+'px');
			jQuery(this).css('margin-left',((jQuery('.etm_menu .cs-skin-circular.cs-active .cs-options').width()/2)-(surface/2))+'px');
			jQuery(this).css('margin-top',((jQuery('.etm_menu .cs-skin-circular.cs-active .cs-options').height()/2)-(surface/2))+'px');
	
			count++;
		});	
	}
}

function etm_menu_resizer(){
	if(jQuery('.etm_menu .cs-skin-boxes.cs-active').length > 0){
	
		jQuery('.etm_menu .cs-skin-boxes.cs-active .cs-options li.removethis').remove();
		count_li = jQuery('.etm_menu .cs-skin-boxes.cs-active .cs-options li').length;
		
		screen_w = jQuery(window).width();
		screen_h = jQuery(window).height();
		object_w = 0;
		object_h = 0;
		object_margin = 5;
		
		flag_difference_w = 1.48936170212766;
		flag_difference_h = 0.67142857142857;
		count = 0;


		size_w = Math.sqrt(((screen_w * screen_h)) / (flag_difference_h*count_li));	
		size_h = Math.sqrt(((screen_w * screen_h)) / (flag_difference_w*count_li));
	
		for(var i=1; i<100;i++){
			size_w = parseInt(size_w -10);
			size_h = parseInt(size_w*flag_difference_h);
			
			get_cols = Math.floor(((screen_w)/(size_w+(object_margin*2))));
			get_rows = Math.ceil(count_li/get_cols);
			if(screen_h>((size_h+(object_margin*2))*get_rows)){
				size_w = parseInt(size_w -10);
				size_h = parseInt(size_w*flag_difference_h);
				
				get_cols = Math.floor(((screen_w)/(size_w+(object_margin*2))));
				get_rows = Math.ceil(count_li/get_cols);
				break;
			}
		}
		extra_w_2 = (screen_w- ((size_w+(object_margin*2))*get_cols))/2;
		extra_h_2 = (screen_h- ((size_h+(object_margin*2))*get_rows))/2;
		extramove = ((get_rows*get_cols)-count_li);
		
		if(extramove > 0){
			jQuery('.etm_menu .cs-skin-boxes.cs-active .cs-options ul li').css('margin-left',object_margin+'px');
				if(get_rows == 1){
					jQuery('.etm_menu .cs-skin-boxes.cs-active .cs-options ul li:visible:nth-child(1)').css('margin-left',(((extramove*(size_w+(object_margin*2)))/2)+10)+'px');
				} else {
					jQuery('.etm_menu .cs-skin-boxes.cs-active .cs-options ul li:visible:nth-child('+((count_li)-(get_cols-extramove-1))+')').css('margin-left',(((extramove*(size_w+(object_margin*2)))/2)+10)+'px');
				}
			
		} else {
			jQuery('.etm_menu .cs-skin-boxes.cs-active .cs-options ul li').css('margin-left',object_margin+'px');
		}


		jQuery('.etm_menu .cs-skin-boxes.cs-active .cs-options ul').css('left',(extra_w_2)+'px');
		jQuery('.etm_menu .cs-skin-boxes.cs-active .cs-options ul').css('top',(extra_h_2)+'px');	
		
		jQuery('.etm_menu .cs-skin-boxes.cs-active .cs-options li').each(function (){
			jQuery(this).css('width',size_w+'px');
			jQuery(this).css('height',size_h+'px');
		});
	}
}


