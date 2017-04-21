<?php
/**
 * WPSeed - Admin Only Functions
 *
 * This file will only be included during an admin request. Use a file
 * like functions.wpseed-core.php if your function is meant for the frontend.   
 *
 * @author   Ryan Bayne
 * @category Admin
 * @package  WPSeed/Admin
 * @since    1.0.0
 */
 
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Generate the complete nonce string, from the nonce base, the action 
 * and an item, e.g. wpseed_delete_table_3.
 *
 * @since 1.0.0
 *
 * @param string      $action Action for which the nonce is needed.
 * @param string|bool $item   Optional. Item for which the action will be performed, like "table".
 * @return string The resulting nonce string.
 */
function wpseed_nonce_prepend( $action, $item = false ) {
    $nonce = "wpseed_{$action}";
    if ( $item ) {
        $nonce .= "_{$item}";
    }
    return $nonce;
}

/**
 * Get all WordPress Seed screen ids.
 *
 * @return array
 */
function wpseed_get_screen_ids() {

    $screen_ids   = array(
        'plugins_page_wpseed',
        'settings_page_wpseed-settings',
    );

    return apply_filters( 'wpseed_screen_ids', $screen_ids );
}