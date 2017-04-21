<?php
/**
 * WPSeed - SPL Autoloader Class
 *
 * @author   Ryan Bayne
 * @category System
 * @package  WPSeed/System
 * @since    1.0.0
 */
 
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}    

/**
* WP Seed SPL Autoloader Class
*/
class WPSeed_Autoloader {

    /**
     * Path to the includes directory.
     *
     * @var string
     */
    private $include_path = '';

    /**
     * The Constructor.
     */
    public function __construct() {
        if ( function_exists( "__autoload" ) ) {
            spl_autoload_register( "__autoload" );
        }

        spl_autoload_register( array( $this, 'autoload' ) );

        $this->include_path = untrailingslashit( plugin_dir_path( WPSEED_PLUGIN_FILE ) ) . '/includes/';
    }

    /**
     * Take a class name and turn it into a file name.
     *
     * @param  string $class
     * @return string
     */
    private function get_file_name_from_class( $class ) {
        return 'class.' . str_replace( '_', '-', $class ) . '.php';
    }

    /**
     * Include a class file.
     *
     * @param  string $path
     * @return bool successful or not
     */
    private function load_file( $path ) {
        if ( $path && is_readable( $path ) ) {
            include_once( $path );
            return true;
        }
        return false;
    }

    /**
     * Auto-load WPSeed classes on demand to reduce memory consumption.
     *
     * @param string $class
     */
    public function autoload( $class ) {
        $class = strtolower( $class );
        $file  = $this->get_file_name_from_class( $class );
        $path  = '';
   
        if ( strpos( $class, 'wpseed_shortcode_' ) === 0 ) {
            $path = $this->include_path . 'shortcodes/';
        } elseif ( strpos( $class, 'wpseed_meta_box' ) === 0 ) {
            $path = $this->include_path . 'admin/meta-boxes/';
        } elseif ( strpos( $class, 'wpseed_admin' ) === 0 ) {
            $path = $this->include_path . 'admin/';
        } 
    
        if ( empty( $path ) || ( ! $this->load_file( $path . $file ) && strpos( $class, 'wpseed-' ) === 0 ) ) {
            $this->load_file( $this->include_path . $file );
        }
    }
}

new WPSeed_Autoloader();