<?php
/**
 * WPSeed - Depreciated Functions
 *
 * Please add the WordPress core function for triggering and error if a
 * depreciated function is used. 
 * 
 * Use: _deprecated_function( 'wpseed_function_called', '2.1', 'wpseed_replacement_function' );  
 *
 * @author   Ryan Bayne
 * @category Core
 * @package  WPSeed/Core
 * @since    1.0.0
 */
 
if ( ! defined( 'ABSPATH' ) ) {
    exit;
} 
  
/**
 * @deprecated example only
 */
function wpseed_function_called() {
    _deprecated_function( 'wpseed_function_called', '2.1', 'wpseed_replacement_function' );
    //wpseed_replacement_function();
}