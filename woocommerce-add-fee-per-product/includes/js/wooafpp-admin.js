jQuery(document).ready(function($) {

	//Click on add new option
	$( document ).on( 'click', '.wooafpp_repeater_addnew', function() {

		//Get repeater wrap
		var repeater_wrap = $(this).siblings( '.wooafpp_repeater_wrap' );
		var clone_item = repeater_wrap.find('.wooafpp_repeater_item:last-child').clone();

		//Clone old to repeat new one
		clone_item.appendTo( repeater_wrap );
		var recent_item = repeater_wrap.find('.wooafpp_repeater_item:last-child');
		var next_index = parseInt( recent_item.data('index') ) + 1;
		recent_item.attr( 'data-index', parseInt( recent_item.data('index') ) + 1 );
		recent_item.find('input, select').each(function() {
			var new_name = $(this).data('sample').replace( '{key}', next_index );
			$(this).attr( 'name', new_name );
			$(this).val('');
		});
	});

	//Click on remove option
	$( document ).on( 'click', '.wooafpp_repeater_remove', function() {
		if( $(this).parents( '.wooafpp_repeater_wrap' ).find( '.wooafpp_repeater_item' ).length > 1 ) {
			var remove = confirm( WOOAFPP_Data.confirm_delete_msg );
			if( remove == true ) {
				$(this).parent().remove();
			}
		}
	});
});