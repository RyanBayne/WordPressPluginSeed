<?php
/**
 * WPSeed Admin - Main Admin Class
 *
 * The primary for main add_action() and file includes during an administration side request. There is
 * also a functions.wpseed-admin.php for functions strictly related to admin.  
 * 
 * Do not include files only meant for the frontside.
 * Do not queue scripts or css only meant for frontside. 
 * 
 * @class    WPSeed_Admin
 * @author   Ryan Bayne
 * @category Admin
 * @package  WPSeed/Admin
 * @version  1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * WPSeed_Admin class.
 */
class WPSeed_Admin {

    /**
     * Constructor.
     */
    public function __construct() {         
        add_action( 'init', array( $this, 'includes' ) );
        add_action( 'current_screen', array( $this, 'conditional_includes' ) );
        add_action( 'admin_init', array( $this, 'buffer' ), 1 );
        add_action( 'admin_init', array( $this, 'admin_redirects' ) );
        add_action( 'admin_footer', 'wpseed_print_js', 25 );
        add_filter( 'admin_footer_text', array( $this, 'admin_footer_text' ), 1 );
    }

    /**
     * Output buffering allows admin screens to make redirects later on.
     */
    public function buffer() {
        ob_start();
    }

    /**
     * Include any classes we need within admin.
     */
    public function includes() {
        include_once( dirname( __FILE__ ) . '/functions.wpseed-admin.php' );
        include_once( dirname( __FILE__ ) . '/class.wpseed-admin-menus.php' );
        include_once( dirname( __FILE__ ) . '/class.wpseed-admin-notices.php' );
        include_once( dirname( __FILE__ ) . '/class.wpseed-admin-assets.php' );
        include_once( dirname( __FILE__ ) . '/class.wpseed-admin-pointers.php' );
        include_once( dirname( __FILE__ ) . '/class.wpseed-admin-help.php' );
        include_once( dirname( __FILE__ ) . '/class.wpseed-admin-pointers.php' );
        
        // Help Tabs
        if ( apply_filters( 'wpseed_enable_admin_help_tab', true ) ) {
            include_once( dirname( __FILE__ ) . '/class.wpseed-admin-help.php' );
        }
                
        // Setup/welcome
        if ( ! empty( $_GET['page'] ) ) {
            switch ( $_GET['page'] ) {
                case 'wpseed-setup' :
                    include_once( dirname( __FILE__ ) . '/class.wpseed-admin-setup-wizard.php' );
                break;
            }
        }
    }

    /**
     * Include admin files conditionally based on specific page.
     */
    public function conditional_includes() {

        if ( ! $screen = get_current_screen() ) {
            return;
        }

        switch ( $screen->id ) {
            case 'dashboard' :
                include( 'class.wpseed-admin-dashboard.php' );
            break;
            case 'wpseed' :
            break;
            case 'users' :
            break;
            case 'user' :
            break;
            case 'profile' :
            break;
            case 'user-edit' :
            break;
            case 'wpseed-settings' :
            break;
        }
    }

    /**
     * Handle redirects to setup/welcome page after install and updates.
     *
     * For setup wizard, transient must be present, the user must have access rights, and we must ignore the network/bulk plugin updaters.
     */
    public function admin_redirects() {

        // Nonced plugin install redirects (whitelisted)
        if ( ! empty( $_GET['wpseed-install-plugin-redirect'] ) ) {
            $plugin_slug = wpseed_clean( $_GET['wpseed-install-plugin-redirect'] );

            if ( current_user_can( 'install_plugins' ) && in_array( $plugin_slug, array( 'wpseed-gateway-stripe' ) ) ) {
                $nonce = wp_create_nonce( 'install-plugin_' . $plugin_slug );
                $url   = self_admin_url( 'update.php?action=install-plugin&plugin=' . $plugin_slug . '&_wpnonce=' . $nonce );
            } else {
                $url = admin_url( 'plugin-install.php?tab=search&type=term&s=' . $plugin_slug );
            }

            wp_safe_redirect( $url );
            exit;
        }

        // Setup wizard redirect
        if ( get_transient( '_wpseed_activation_redirect' ) ) {
            delete_transient( '_wpseed_activation_redirect' );

            if ( ( ! empty( $_GET['page'] ) && in_array( $_GET['page'], array( 'wpseed-setup' ) ) ) || is_network_admin() || isset( $_GET['activate-multi'] ) || ! current_user_can( 'manage_wpseed' ) || apply_filters( 'wpseed_prevent_automatic_wizard_redirect', false ) ) {
                return;
            }

            // If the user needs to install, send them to the setup wizard
            if ( WPSeed_Admin_Notices::has_notice( 'install' ) ) {
                wp_safe_redirect( admin_url( 'index.php?page=wpseed-setup' ) );
                exit;
            }
        }
    }

    /**
     * Change the admin footer text on WordPress Seed admin pages.
     */
    public function admin_footer_text( $footer_text ) {
        if ( ! current_user_can( 'manage_wpseed' ) ) {
            return;
        }
        $current_screen = get_current_screen();
        $wpseed_pages   = wpseed_get_screen_ids();

        // Check to make sure we're on a WPSeed admin page
        if ( isset( $current_screen->id ) && apply_filters( 'wpseed_display_admin_footer_text', in_array( $current_screen->id, $wpseed_pages ) ) ) {
            $footer_text = __( 'Thank you for planting a WordPress Seed. I recommend removing this footer message. This text is an example only.', 'wpseed' );
        }

        return $footer_text;
    }
}

return new WPSeed_Admin();