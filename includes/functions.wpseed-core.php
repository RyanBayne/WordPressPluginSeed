<?php
/**
 * WPSeed - Core Functions
 *
 * Place a function here when it is doesn't make sense in other files or needs
 * to be obviously available to third-party developers. 
 * 
 * @author   Ryan Bayne
 * @category Core
 * @package  WPSeed/Core
 * @since    1.0.0
 */
 
if ( ! defined( 'ABSPATH' ) ) {
    exit;
} 

// Include core functions (available in both admin and frontend).
include( 'functions.wpseed-formatting.php' );

/**
 * is_ajax - Returns true when the page is loaded via ajax.
 * 
 * The DOING_AJAX constant is set by WordPress.
 * 
 * @return bool
 */
function wpseed_is_ajax() {          
    return defined( 'DOING_AJAX' );
}
    
/**
* Check if the home URL (stored during WordPress installation) is HTTPS. 
* If it is, we don't need to do things such as 'force ssl'.
*
* @return bool
*/
function wpseed_is_https() {      
    return false !== strstr( get_option( 'home' ), 'https:' );
}

/**
* Determine if on the dashboard page. 
* 
* $current_screen is not set early enough for calling in some actions. So use this
* function instead.
*/
function wpseed_is_dashboard() {      
    global $pagenow;
    // method one: check $pagenow value which could be "index.php" and that means the dashboard
    if( isset( $pagenow ) && $pagenow == 'index.php' ) { return true; }
    // method two: should $pagenow not be set, check the server value
    return strstr( $this->PHP->currenturl(), 'wp-admin/index.php' );
}

/**
* Use to check for Ajax or XMLRPC request. Use this function to avoid
* running none urgent tasks during existing operations and demanding requests.
*/
function wpseed_is_background_process() {        
    if ( ( 'wp-login.php' === basename( $_SERVER['SCRIPT_FILENAME'] ) )
            || ( defined( 'XMLRPC_REQUEST' ) && XMLRPC_REQUEST )
            || ( defined( 'DOING_CRON' ) && DOING_CRON )
            || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
                return true;
    }
               
    return false;
}

/**
 * Output any queued javascript code in the footer.
 */
function wpseed_print_js() {
    global $wpseed_queued_js;

    if ( ! empty( $wpseed_queued_js ) ) {
        // Sanitize.
        $wpseed_queued_js = wp_check_invalid_utf8( $wpseed_queued_js );
        $wpseed_queued_js = preg_replace( '/&#(x)?0*(?(1)27|39);?/i', "'", $wpseed_queued_js );
        $wpseed_queued_js = str_replace( "\r", '', $wpseed_queued_js );

        $js = "<!-- WPSeed JavaScript -->\n<script type=\"text/javascript\">\njQuery(function($) { $wpseed_queued_js });\n</script>\n";

        /**
         * wpseed_queued_js filter.
         *
         * @since 2.6.0
         * @param string $js JavaScript code.
         */
        echo apply_filters( 'wpseed_queued_js', $js );

        unset( $wpseed_queued_js );
    }
}

/**
 * Display a WordPress Seed help tip.
 *
 * @since  2.5.0
 *
 * @param  string $tip        Help tip text
 * @param  bool   $allow_html Allow sanitized HTML if true or escape
 * @return string
 */
function wpseed_help_tip( $tip, $allow_html = false ) {
    if ( $allow_html ) {
        $tip = wpseed_sanitize_tooltip( $tip );
    } else {
        $tip = esc_attr( $tip );
    }

    return '<span class="wpseed-help-tip" data-tip="' . $tip . '"></span>';
}

/**
 * Queue some JavaScript code to be output in the footer.
 *
 * @param string $code
 */
function wpseed_enqueue_js( $code ) {
    global $wpseed_queued_js;

    if ( empty( $wpseed_queued_js ) ) {
        $wpseed_queued_js = '';
    }

    $wpseed_queued_js .= "\n" . $code . "\n";
}