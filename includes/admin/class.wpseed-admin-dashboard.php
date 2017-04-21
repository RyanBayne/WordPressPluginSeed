<?php                 
/**
 * WPSeed - WP Admin Dashboard
 *
 * Custom dashboard widgets and functionality goes here.  
 *
 * @author   Ryan Bayne
 * @category WordPress Dashboard
 * @package  WPSeed/Admin
 * @since    1.0.0
 */
 
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'WPSeed_Admin_Dashboard' ) ) :

/**
 * WPSeed_Admin_Dashboard Class.
 */
class WPSeed_Admin_Dashboard {

    /**
     * Init dashboard widgets.
     */
    public function init() {           
        if ( current_user_can( 'activate_plugins' ) ) {
            wp_add_dashboard_widget( 'wpseed_dashboard_widget_example', __( 'Example Widget', 'wpseed' ), array( $this, 'example_widget' ) );
        }
    }
       
    /**
     * Recent reviews widget.
     */
    public function example_widget() {              
        echo '<p>' . __( 'This is an example widget only. A developer must use it or remove it.', 'wpseed' ) . '</p>';
    }

}

endif;

return new WPSeed_Admin_Dashboard();
