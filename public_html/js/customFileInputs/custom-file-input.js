/*
	By Osvaldas Valutis, www.osvaldas.info
	Available for use under the MIT License

	Customizing for input's file without multiple select
	By a8an7a, https://github.com/a8an7a 
*/

'use strict';

;( function( $, window, document, undefined )
{
	$( '.inputFile' ).each( function()
	{
		var $input	 = $( this ),
			$label	 = $input.prev( 'label' ),
			labelVal = $label.html();

		$input.on( 'change', function( e )
		{
			var fileName = '';
			
			fileName = e.target.value.split( '\\' ).pop();

			if( fileName )
				$label.find( '.inputFile-info' ).html( fileName );
			else
				$label.html( labelVal );
		});

		// Firefox bug fix
		$input
		.on( 'focus', function(){ $input.addClass( 'has-focus' ); })
		.on( 'blur', function(){ $input.removeClass( 'has-focus' ); });
	});
})( jQuery, window, document );