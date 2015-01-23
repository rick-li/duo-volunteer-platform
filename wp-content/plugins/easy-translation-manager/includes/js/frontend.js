jQuery( function( $ ) {
	if(jQuery('.widget_shopping_cart').length > 0){
		jQuery.post(wc_cart_fragments_params.ajax_url,{action: 'woocommerce_get_refreshed_fragments' },function( data ) {
			if ( data && data.fragments ) {
	
				$.each( data.fragments, function( key, value ) {
					$( key ).replaceWith( value );
				});
	
				if ( $supports_html5_storage ) {
					sessionStorage.setItem( wc_cart_fragments_params.fragment_name, JSON.stringify( data.fragments ) );
					sessionStorage.setItem( 'wc_cart_hash', data.cart_hash );
				}
				$( 'body' ).trigger( 'wc_fragments_refreshed' );
			}
		},'json');
	}
});