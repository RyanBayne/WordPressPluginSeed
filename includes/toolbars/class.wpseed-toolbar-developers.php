<?php
/**
 * WPSeed - Developer Toolbar
 *
 * The developer toolbar requires the "seniordeveloper" custom capability. The
 * toolbar allows actions not all key holders should be giving access to. The
 * menu is intended for developers to already have access to a range of
 *
 * @author   Ryan Bayne
 * @category Admin
 * @package  WPSeed/Toolbars
 * @since    1.0.0
 */
 
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}  

if( !class_exists( 'WPSeed_Admin_Toolbar_Developers' ) ) :

class WPSeed_Admin_Toolbar_Developers {
    public function __construct() {
        if( !current_user_can( 'seniordeveloper' ) ) return false;
        $this->init(); 
    }    
    
    private function init() {
        global $wp_admin_bar, $wpseed_settings;  
        
        // Top Level/Level One
        $args = array(
            'id'     => 'wpseed-toolbarmenu-developers',
            'title'  => __( 'WP Seed Developers', 'text_domain' ),          
        );
        $wp_admin_bar->add_menu( $args );
        
            // Group - Debug Tools
            $args = array(
                'id'     => 'wpseed-toolbarmenu-debugtools',
                'parent' => 'wpseed-toolbarmenu-developers',
                'title'  => __( 'Debug Tools', 'text_domain' ), 
                'meta'   => array( 'class' => 'first-toolbar-group' )         
            );        
            $wp_admin_bar->add_menu( $args );

                // error display switch        
                $href = wp_nonce_url( admin_url() . 'admin.php?page=' . $_GET['page'] . '&wpseedaction=' . 'debugmodeswitch'  . '', 'debugmodeswitch' );
                if( !isset( $wpseed_settings['displayerrors'] ) || $wpseed_settings['displayerrors'] !== true ) 
                {
                    $error_display_title = __( 'Hide Errors', 'wpseed' );
                } 
                else 
                {
                    $error_display_title = __( 'Display Errors', 'wpseed' );
                }
                $args = array(
                    'id'     => 'wpseed-toolbarmenu-errordisplay',
                    'parent' => 'wpseed-toolbarmenu-debugtools',
                    'title'  => $error_display_title,
                    'href'   => $href,            
                );
                $wp_admin_bar->add_menu( $args );    
    }
    
}   

endif;

return new WPSeed_Admin_Toolbar_Developers();
