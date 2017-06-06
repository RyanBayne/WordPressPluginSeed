<?php
/**
 * Plugin Name: Plugin Seed
 * Plugin URI: https://pluginseed.wordpress.com/
 * Github URI: https://github.com/RyanBayne/wpseed
 * Description: Grow a new plugin using a WordPress plugin Seed. 
 * Version: 1.0.0
 * Author: Ryan Bayne
 * Author URI: https://www.linkedin.com/in/ryanrbayne/
 * Requires at least: 4.4
 * Tested up to: 4.7
 * License: GPL3
 * License URI: http://www.gnu.org/licenses/gpl-3.0.txt
 * Domain Path: /i18n/languages/
 * 
 * @package WPSeed
 * @category Core
 * @author Ryan Bayne
 * @license GNU General Public License, Version 3
 * @copyright 2016-2017 Ryan R. Bayne (SqueekyCoder@Gmail.com)
 */
 
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
                 
if ( ! class_exists( 'WordPressPluginSeed' ) ) :

/**
 * Main WPSeed Class.
 *
 * @class WPSeed
 * @version 1.0.0
 */
final class WordPressPluginSeed {
    
    /**
     * WPSeed version.
     *
     * @var string
     */
    public $version = '1.0.0';

    /**
     * Minimum WP version.
     *
     * @var string
     */
    public $min_wp_version = '4.4';
    
    /**
     * The single instance of the class.
     *
     * @var WPSeed
     * @since 2.1
     */
    protected static $_instance = null;

    /**
     * Session instance.
     *
     * @var WPSeed_Session
     */
    public $session = null; 
        
    /**
     * Main WPSeed Instance.
     *
     * Ensures only one instance of WPSeed is loaded or can be loaded.
     *
     * @since 1.0
     * @static
     * @see WordPressSeed()
     * @return WPSeed - Main instance.
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }                    
        return self::$_instance;
    }

    /**
     * Cloning WPSeed is forbidden.
     * @since 1.0
     */
    public function __clone() {
        _doing_it_wrong( __FUNCTION__, __( 'Your not allowed to do that!', 'wpseed' ), '1.0' );
    }

    /**
     * Unserializing instances of this class is forbidden.
     * @since 1.0
     */
    public function __wakeup() {
        _doing_it_wrong( __FUNCTION__, __( 'Your not allowed to do that!', 'wpseed' ), '1.0' );
    }

    /**
     * Auto-load in-accessible properties on demand.
     * @param mixed $key
     * @return mixed
     */
    public function __get( $key ) {
        if ( in_array( $key, array( 'mailer' ) ) ) {
            return $this->$key();
        }
    }   
    
    /**
     * WPSeed Constructor.
     */
    public function __construct() {
        $this->define_constants();
        $this->includes();
        $this->init_hooks();

        do_action( 'wpseed_loaded' );
    }

    /**
     * Hook into actions and filters.
     * @since  1.0
     */
    private function init_hooks() {
        register_activation_hook( __FILE__, array( 'WPSeed_Install', 'install' ) );
        // Do not confuse deactivation of a plugin with deletion of a plugin - two very different requests.
        register_deactivation_hook( __FILE__, array( 'WPSeed_Install', 'deactivate' ) );
        add_action( 'init', array( $this, 'init' ), 0 );
    }

    /**
     * Define WPSeed Constants.
     */
    private function define_constants() {
        
        $upload_dir = wp_upload_dir();
        
        // Main (package) constants.
        if ( ! defined( 'WPSEED_PLUGIN_FILE' ) ) { define( 'WPSEED_PLUGIN_FILE', __FILE__ ); }
        if ( ! defined( 'WPSEED_PLUGIN_BASENAME' ) ) { define( 'WPSEED_PLUGIN_BASENAME', plugin_basename( __FILE__ ) ); }
        if ( ! defined( 'WPSEED_PLUGIN_DIR_PATH' ) ) { define( 'WPSEED_PLUGIN_DIR_PATH', plugin_dir_path( __FILE__ ) ); }
        if ( ! defined( 'WPSEED_VERSION' ) ) { define( 'WPSEED_VERSION', $this->version ); }
        if ( ! defined( 'WPSEED_MIN_WP_VERSION' ) ) { define( 'WPSEED_MIN_WP_VERSION', $this->min_wp_version ); }
        if ( ! defined( 'WPSEED_LOG_DIR' ) ) { define( 'WPSEED_LOG_DIR', $upload_dir['basedir'] . '/wpseed-logs/' ); }
        if ( ! defined( 'WPSEED_SESSION_CACHE_GROUP' ) ) { define( 'WPSEED_SESSION_CACHE_GROUP', 'wpseed_session_id' ); }
        if ( ! defined( 'WPSEED_DEV_MODE' ) ) { define( 'WPSEED_DEV_MODE', false ); }
        if ( ! defined( 'WPSEED_WORDPRESSORG_SLUG' ) ) { define( 'WPSEED_WORDPRESSORG_SLUG', false ); }
        if ( ! defined( 'WPSEED_MARKETPLACE' ) ) { define( 'WPSEED_MARKETPLACE', false ); }
        if ( ! defined( 'WPSEED_MARKETPLACE_ID' ) ) { define( 'WPSEED_MARKETPLACE_ID', false ); }
                                      
        // Support (project) constants.
        if ( ! defined( 'WPSEED_HOME' ) ) { define( 'WPSEED_HOME', 'https://github.com/RyanBayne/wordpresspluginseed' ); }
        if ( ! defined( 'WPSEED_FORUM' ) ) { define( 'WPSEED_FORUM', 'https://wpseed.slack.com/' ); }
        if ( ! defined( 'WPSEED_TWITTER' ) ) { define( 'WPSEED_TWITTER', false ); }
        if ( ! defined( 'WPSEED_DONATE' ) ) { define( 'WPSEED_DONATE', 'https://www.patreon.com/ryanbayne' ); }
        if ( ! defined( 'WPSEED_SKYPE' ) ) { define( 'WPSEED_SKYPE', 'https://join.skype.com/bVtDaGHd9Nnl' ); }
        if ( ! defined( 'WPSEED_GITHUB' ) ) { define( 'WPSEED_GITHUB', 'https://github.com/RyanBayne/wordpresspluginseed' ); }
        if ( ! defined( 'WPSEED_SLACK' ) ) { define( 'WPSEED_SLACK', 'https://wpseed.slack.com/' ); }
        if ( ! defined( 'WPSEED_DOCS' ) ) { define( 'WPSEED_DOCS', 'https://github.com/RyanBayne/wpseed/wiki' ); }
        if ( ! defined( 'WPSEED_FACEBOOK' ) ) { define( 'WPSEED_FACEBOOK', 'https://www.facebook.com/WordPress-Plugin-Seed-704154249757165/' ); }
       
        // Author (social) constants - can act as default when support constants are false.                                                                                                              
        if ( ! defined( 'WPSEED_AUTHOR_HOME' ) ) { define( 'WPSEED_AUTHOR_HOME', 'https://ryanbayne.wordpress.com' ); }
        if ( ! defined( 'WPSEED_AUTHOR_FORUM' ) ) { define( 'WPSEED_AUTHOR_FORUM', false ); }
        if ( ! defined( 'WPSEED_AUTHOR_TWITTER' ) ) { define( 'WPSEED_AUTHOR_TWITTER', 'http://www.twitter.com/Ryan_R_Bayne' ); }
        if ( ! defined( 'WPSEED_AUTHOR_FACEBOOK' ) ) { define( 'WPSEED_AUTHOR_FACEBOOK', 'https://www.facebook.com/ryanrbayne' ); }
        if ( ! defined( 'WPSEED_AUTHOR_DONATE' ) ) { define( 'WPSEED_AUTHOR_DONATE', 'https://www.patreon.com/ryanbayne' ); }
        if ( ! defined( 'WPSEED_AUTHOR_SKYPE' ) ) { define( 'WPSEED_AUTHOR_SKYPE', 'https://join.skype.com/gNuxSa4wnQTV' ); }
        if ( ! defined( 'WPSEED_AUTHOR_GITHUB' ) ) { define( 'WPSEED_AUTHOR_GITHUB', 'https://github.com/RyanBayne' ); }
        if ( ! defined( 'WPSEED_AUTHOR_LINKEDIN' ) ) { define( 'WPSEED_AUTHOR_LINKEDIN', 'https://www.linkedin.com/in/ryanrbayne/' ); }
        if ( ! defined( 'WPSEED_AUTHOR_DISCORD' ) ) { define( 'WPSEED_AUTHOR_DISCORD', 'https://discord.gg/xBNYA7Q' ); }
        if ( ! defined( 'WPSEED_AUTHOR_SLACK' ) ) { define( 'WPSEED_AUTHOR_SLACK', 'https://ryanbayne.slack.com/threads/team/' ); }
    }

    /**
     * Include required core files used in admin and on the frontend.
     */
    public function includes() {
        
        include_once( 'includes/functions.wpseed-core.php' );
        include_once( 'includes/class.wpseed-debug.php' );    
        include_once( 'includes/class.wpseed-autoloader.php' );
        include_once( 'includes/functions.wpseed-validate.php' );        
        include_once( 'includes/class.wpseed-install.php' );
        include_once( 'includes/class.wpseed-ajax.php' );
        
        if ( $this->is_request( 'admin' ) ) {
            include_once( 'includes/admin/class.wpseed-admin.php' );
        }

        if ( $this->is_request( 'frontend' ) ) {
            $this->frontend_includes();
        }
    }

    /**
     * Include required frontend files.
     */
    public function frontend_includes() {
        include_once( 'includes/class.wpseed-frontend-scripts.php' );  
    }

    /**
     * Initialise WordPress Plugin Seed when WordPress Initialises.
     */
    public function init() {                     
        // Before init action.
        do_action( 'before_wpseed_init' );

        // Init action.
        do_action( 'wpseed_init' );
    }

    /**
     * Get the plugin url.
     * @return string
     */
    public function plugin_url() {                
        return untrailingslashit( plugins_url( '/', __FILE__ ) );
    }

    /**
     * Get the plugin path.
     * @return string
     */
    public function plugin_path() {              
        return untrailingslashit( plugin_dir_path( __FILE__ ) );
    }

    /**
     * Get Ajax URL (this is the URL to WordPress core ajax file).
     * @return string
     */
    public function ajax_url() {                
        return admin_url( 'admin-ajax.php', 'relative' );
    }

    /**
     * What type of request is this?
     *
     * Functions and constants are WordPress core. This function will allow
     * you to avoid large operations or output at the wrong time.
     * 
     * @param  string $type admin, ajax, cron or frontend.
     * @return bool
     */
    private function is_request( $type ) {
        switch ( $type ) {
            case 'admin' :
                return is_admin();
            case 'ajax' :
                return defined( 'DOING_AJAX' );
            case 'cron' :
                return defined( 'DOING_CRON' );
            case 'frontend' :
                return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' );
        }
    }    
}

endif;

if( !function_exists( 'WPSeed' ) ) {
    /**
     * Main instance of WordPress Plugin Seed.
     *
     * Returns the main instance of WPSeed to prevent the need to use globals.
     *
     * @since  1.0
     * @return WPSeed
     */
    function WPSeed() {
        return WordPressPluginSeed::instance();
    }

    // Global for backwards compatibility.
    $GLOBALS['wpseed'] = WPSeed();
    
    //$wpseed_debug = new WPSeed_Debug();
    //$wpseed_debug->debugmode();
}