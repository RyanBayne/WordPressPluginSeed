/*global wpseed_setup_params */
jQuery( function( $ ) {

    $( '.button-next' ).on( 'click', function() {
        $('.wpseed-setup-content').block({
            message: null,
            overlayCSS: {
                background: '#fff',
                opacity: 0.6
            }
        });
        return true;
    } );

    $( '.wpseed-wizard-plugin-extensions' ).on( 'change', '.wpseed-wizard-extension-enable input', function() {
        if ( $( this ).is( ':checked' ) ) {
            $( this ).closest( 'li' ).addClass( 'checked' );
        } else {
            $( this ).closest( 'li' ).removeClass( 'checked' );
        }
    } );

    $( '.wpseed-wizard-plugin-extensions' ).on( 'click', 'li.wpseed-wizard-extension', function() {
        var $enabled = $( this ).find( '.wpseed-wizard-extension-enable input' );

        $enabled.prop( 'checked', ! $enabled.prop( 'checked' ) ).change();
    } );

    $( '.wpseed-wizard-plugin-extensions' ).on( 'click', 'li.wpseed-wizard-extension table, li.wpseed-wizard-extension a', function( e ) {
        e.stopPropagation();
    } );
} );
