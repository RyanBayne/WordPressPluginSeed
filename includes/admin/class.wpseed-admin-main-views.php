<?php
/**
 * WPSeed Admin Table Views
 *
 * @author      WPSeed
 * @category    Admin
 * @package     WPSeed/Admin
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
               
if ( ! class_exists( 'WPSeed_Admin_Main_Views' ) ) :
            
/**
 * WPSeed_Admin_Main_Views Class.
 */
class WPSeed_Admin_Main_Views {

    /**
     * Handles output of the main tables page in admin.
     */
    public static function output() {       
        $tabs              = self::get_tabs();
        $first_tab         = array_keys( $tabs );
        $current_tab       = ! empty( $_GET['tab'] ) ? sanitize_title( $_GET['tab'] ) : $first_tab[0];
        $current_tablelist = isset( $_GET['seedview'] ) ? sanitize_title( $_GET['seedview'] ) : current( array_keys( $tabs[ $current_tab ]['maintabviews'] ) );

        require_once( 'views/html-admin-page.php' );
    }

    /**
     * Returns the definitions for the tables to show in admin.
     *
     * @return array
     */
    public static function get_tabs() {
        $tabviews = array();
        
        // Basic List Tables
        $tabviews['basic_list_tables'] = array(
            'title'  => __( 'Basic List Tables', 'wpseed' ),
            'maintabviews' => array(
                "default_items" => array(
                    'title'       => __( 'Default Items', 'wpseed' ),
                    'description' => '',
                    'hide_title'  => true,
                    'callback'    => array( __CLASS__, 'get_maintabview' )
                ),                    
                "team_items" => array(
                    'title'       => __( 'Teams', 'wpseed' ),
                    'description' => '',
                    'hide_title'  => true,
                    'callback'    => array( __CLASS__, 'get_maintabview' )
                ),
                "animal_items" => array(
                    'title'       => __( 'Animals', 'wpseed' ),
                    'description' => '',
                    'hide_title'  => true,
                    'callback'    => array( __CLASS__, 'get_maintabview' )
                ),
                "food_items" => array(
                    'title'       => __( 'Food', 'wpseed' ),
                    'description' => '',
                    'hide_title'  => true,
                    'callback'    => array( __CLASS__, 'get_maintabview' )
                ),
            )
        );

        // Advanced List Tables
        $tabviews['advanced_list_tables'] = array(
            'title'  => __( 'Advanced List Tables', 'wpseed' ),
            'maintabviews' => array(
                "default_advanced" => array(
                    'title'       => __( 'Default Items', 'wpseed' ),
                    'description' => '',
                    'hide_title'  => true,
                    'callback'    => array( __CLASS__, 'get_maintabview' )
                ),                    
                "team_advanced" => array(
                    'title'       => __( 'Teams', 'wpseed' ),
                    'description' => '',
                    'hide_title'  => true,
                    'callback'    => array( __CLASS__, 'get_maintabview' )
                ),
                "animal_advanced" => array(
                    'title'       => __( 'Animals', 'wpseed' ),
                    'description' => '',
                    'hide_title'  => true,
                    'callback'    => array( __CLASS__, 'get_maintabview' )
                ),
                "food_advanced" => array(
                    'title'       => __( 'Food', 'wpseed' ),
                    'description' => '',
                    'hide_title'  => true,
                    'callback'    => array( __CLASS__, 'get_maintabview' )
                ),
            )
        );
      
        $tabviews = apply_filters( 'wpseed_admin_mainviews', $tabviews );

        return $tabviews;
    }

    /**
     * Get a specific table view from 'mainviews' subfolder.
     */
    public static function get_maintabview( $name_presan ) {     
        $name  = sanitize_title( str_replace( '_', '-', $name_presan ) );
        $class = 'WPSeed_MainView_' . str_replace( '-', '_', $name );
        
        require_once( apply_filters( 'wpseed_admin_mainviews_path', 'mainviews/class.wpseed-' . $name . '.php', $name, $class ) );

        if ( ! class_exists( $class ) )
            return;

        $maintabs = new $class();      
        $maintabs->output_result();
    }
}

endif;
