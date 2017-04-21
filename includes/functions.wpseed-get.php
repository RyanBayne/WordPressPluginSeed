<?php
/**
 * WPSeed - Small Get Functions
 *
 * Make the plugins API easier with get functions. Try to avoid functions
 * that need to include files or create objects. The goal is for this file
 * to offer functions that don't come with drawbacks on performance. 
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
* Get visitors IP address.
* 
* @version 1.1
*/
function wpseed_get_ip_address() {         
    $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = $_SERVER['REMOTE_ADDR'];
    $ip = null;
    
    if(filter_var($client, FILTER_VALIDATE_IP))
    {
        $ip = $client;
    }
    elseif(filter_var($forward, FILTER_VALIDATE_IP))
    {
        $ip = $forward;
    }
    else
    {
        $ip = $remote;
    }

    return $ip;
}

/**
* Count total number of "administrators". 
*/
function wpseed_get_total_administrators( $partial_admin = false, $return_users = false ) {   
    $user_query = new WP_User_Query( array( 'role' => 'administrator' ) );
    return $user_query->total_users;      
} 