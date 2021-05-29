(function( $ ) {
	var masthead, siteNavigation;

	masthead       = $( '#masthead' );
	siteNavigation = masthead.find( '.common-menu-wrap .nav>li' );

	// Fix sub-menus for touch devices and better focus for hidden submenu items for accessibility.
	(function() {
		if ( ! siteNavigation.length || ! siteNavigation.children().length ) {
			return;
		}

		// Toggle `focus` class to allow submenu access on tablets.
		function toggleFocusClassTouchScreen() {
			if ( 'none' === $( '.navbar-toggle' ).css( 'display' ) ) {

				if ( ! $( e.target ).closest( '.common-menu-wrap li' ).length ) {
					$( '.common-menu-wrap li' ).removeClass( 'focus' );
				}

				siteNavigation.find( '.menu-item-has-children' ).on( 'touchstart', function( e ) {
					var el = $( this ).parent( 'li' );

					if ( ! el.hasClass( 'focus' ) ) {
						e.preventDefault();
						el.toggleClass( 'focus' );
						el.siblings( '.focus' ).removeClass( 'focus' );
					}
				});
			}
		}

		if ( 'ontouchstart' in window ) {
			toggleFocusClassTouchScreen();
		}

		siteNavigation.find( 'a' ).on( 'focus blur', function() {
			$( this ).parents( '.menu-item' ).toggleClass( 'focus' );
		});
	})();
})( jQuery );



