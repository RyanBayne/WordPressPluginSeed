<?php
/**
 * WPSeed - Primary Sidebar Widgets File
 *
 * @author   Ryan Bayne
 * @category Widgets
 * @package  WPSeed/Widgets
 * @since    1.0.0
 */
 
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Include widget classes.
//include_once( 'abstracts/abstract-wpseed-widget.php' );

/**
 * Register Widgets.
 */
function wpseed_register_widgets() {
    //register_widget( 'WPSeed_Widget_Example' );
}
add_action( 'widgets_init', 'wpseed_register_widgets' );