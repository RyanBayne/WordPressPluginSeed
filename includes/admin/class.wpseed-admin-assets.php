<?php
/**
 * WPSeed - Load Assets 
 *
 * Load admin only js, css, images and fonts. 
 *
 * @author   Ryan Bayne
 * @category Loading
 * @package  WPSeed/Loading
 * @since    1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'WPSeed_Admin_Assets' ) ) :

/**
 * WPSeed_Admin_Assets Class.
 */
class WPSeed_Admin_Assets {

    public function __construct() {
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_styles' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) ); 
    }

    /**
     * Enqueue styles for the admin side.
     */
    public function admin_styles() {
        global $wp_scripts;
        
        // Screen ID Must be set for later arguments
        $screen         = get_current_screen();
        $screen_id      = $screen ? $screen->id : '';
        
        $jquery_version = isset( $wp_scripts->registered['jquery-ui-core']->ver ) ? $wp_scripts->registered['jquery-ui-core']->ver : '1.11.4';

        // Register admin styles
        wp_register_style( 'wpseed_admin_styles', WPSeed()->plugin_url() . '/assets/css/admin.css', array(), WPSEED_VERSION );
        wp_register_style( 'jquery-ui-style', '//code.jquery.com/ui/' . $jquery_version . '/themes/smoothness/jquery-ui.min.css', array(), $jquery_version );

        // Admin styles for WordPress Seed pages only
        /*
        if ( in_array( $screen_id, wpseed_get_screen_ids() ) ) {
            wp_enqueue_style( 'wpseed_admin_styles' );
            wp_enqueue_style( 'jquery-ui-style' );
        }
        */
    }

    /**
     * Enqueue scripts for the admin side.
     */
    public function admin_scripts() {                   
        global $wp_query, $post;

        $screen       = get_current_screen();
        $screen_id    = $screen ? $screen->id : '';
        $package_screen_id = sanitize_title( __( 'WPSeed', 'wpseed' ) );
        $suffix       = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

        // Register scripts
        //wp_register_script( 'wpseed_admin', WPSeed()->plugin_url() . '/assets/js/admin/wpseed_admin' . $suffix . '.js', array( 'jquery', 'jquery-blockui', 'jquery-ui-sortable', 'jquery-ui-widget', 'jquery-ui-core', 'jquery-tiptip' ), WPSEED_VERSION );
        //wp_register_script( 'wpseed_admin_help_faq', WPSeed()->plugin_url() . '/assets/js/admin/wpseed-' . $suffix . '.js' );

        if ( in_array( $screen_id, wpseed_get_screen_ids() ) ) {         
            //wp_enqueue_script( 'wpseed_admin_help_faq' );
        } 
                                   
    }
}

endif;

return new WPSeed_Admin_Assets();
