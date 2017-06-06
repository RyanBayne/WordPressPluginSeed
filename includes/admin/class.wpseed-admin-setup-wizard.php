<?php
/**
 * Setup Wizard which completes installation of plugin. 
 *
 * @author      Ryan Bayne
 * @category    Admin
 * @package     WPSeed/Admin
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if( !class_exists( 'WPSeed_Admin_Setup_Wizard' ) ) :

/**
 * WPSeed_Admin_Setup_Wizard Class 
 * 
 * Class originally created by ** Automattic ** and is the best approach to plugin
 * installation found if an author wants to treat the user and their site with
 * respect.
 *
 * @author      Ryan Bayne
 * @category    Admin
 * @package     WPSeed/Admin
 * @version     1.0.0
*/
class WPSeed_Admin_Setup_Wizard {

    /** @var string Current Step */
    private $step   = '';

    /** @var array Steps for the setup wizard */
    private $steps  = array();

    /** @var boolean Is the wizard optional or required? */
    private $optional = false;

    /**
     * Hook in tabs.
     */
    public function __construct() {
        if ( apply_filters( 'wpseed_enable_setup_wizard', true ) && current_user_can( 'manage_wpseed' ) ) {
            add_action( 'admin_menu', array( $this, 'admin_menus' ) );
            add_action( 'admin_init', array( $this, 'setup_wizard' ) );
        }
    }

    /**
     * Add admin menus/screens.
     */
    public function admin_menus() {
        add_dashboard_page( '', '', 'manage_options', 'wpseed-setup', '' );
    }

    /**
     * Show the setup wizard.
     */
    public function setup_wizard() {
        if ( empty( $_GET['page'] ) || 'wpseed-setup' !== $_GET['page'] ) {
            return;
        }
        $this->steps = array(
            'introduction' => array(
                'name'    =>  __( 'Introduction', 'wpseed' ),
                'view'    => array( $this, 'wpseed_setup_introduction' ),
                'handler' => ''
            ),
            'administrators' => array(
                'name'    =>  __( 'Access', 'wpseed' ),
                'view'    => array( $this, 'wpseed_setup_administrators' ),
                'handler' => array( $this, 'wpseed_setup_administrators_save' )
            ),
            'folders' => array(
                'name'    =>  __( 'Files', 'wpseed' ),
                'view'    => array( $this, 'wpseed_setup_folders' ),
                'handler' => array( $this, 'wpseed_setup_folders_save' )
            ),
            'database' => array(
                'name'    =>  __( 'Database', 'wpseed' ),
                'view'    => array( $this, 'wpseed_setup_database' ),
                'handler' => array( $this, 'wpseed_setup_database_save' ),
            ), 
            'extensions' => array(
                'name'    =>  __( 'Extensions', 'wpseed' ),
                'view'    => array( $this, 'wpseed_setup_extensions' ),
                'handler' => array( $this, 'wpseed_setup_extensions_save' ),
            ),                       
            'improvement' => array(
                'name'    =>  __( 'Feedback', 'wpseed' ),
                'view'    => array( $this, 'wpseed_setup_improvement' ),
                'handler' => array( $this, 'wpseed_setup_improvement_save' ),
            ),
            'next_steps' => array(
                'name'    =>  __( 'Ready!', 'wpseed' ),
                'view'    => array( $this, 'wpseed_setup_ready' ),
                'handler' => ''
            )
        );
        $this->step = isset( $_GET['step'] ) ? sanitize_key( $_GET['step'] ) : current( array_keys( $this->steps ) );
        $suffix     = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

        // Register scripts for the pretty extension presentation and selection.
        wp_register_script( 'jquery-blockui', WPSeed()->plugin_url() . '/assets/js/jquery-blockui/jquery.blockUI' . $suffix . '.js', array( 'jquery' ), '2.70', true );
        wp_register_script( 'select2', WPSeed()->plugin_url() . '/assets/js/select2/select2' . $suffix . '.js', array( 'jquery' ), '3.5.2' );
        wp_register_script( 'wpseed-enhanced-select', WPSeed()->plugin_url() . '/assets/js/admin/wpseed-enhanced-select' . $suffix . '.js', array( 'jquery', 'select2' ), WPSEED_VERSION );
        
        // Queue CSS for the entire setup process.
        wp_enqueue_style( 'wpseed_admin_styles', WPSeed()->plugin_url() . '/assets/css/admin.css', array(), WPSEED_VERSION );
        wp_enqueue_style( 'wpseed-setup', WPSeed()->plugin_url() . '/assets/css/wpseed-setup.css', array( 'dashicons', 'install' ), WPSEED_VERSION );
        wp_register_script( 'wpseed-setup', WPSeed()->plugin_url() . '/assets/js/admin/wpseed-setup.min.js', array( 'jquery', 'wpseed-enhanced-select', 'jquery-blockui' ), WPSEED_VERSION );

        if ( ! empty( $_POST['save_step'] ) && isset( $this->steps[ $this->step ]['handler'] ) ) {
            call_user_func( $this->steps[ $this->step ]['handler'] );
        }
    
        ob_start();
        $this->setup_wizard_header();
        $this->setup_wizard_steps();
        $this->setup_wizard_content();
        $this->setup_wizard_footer();
        exit;
    }

    public function get_next_step_link() {
        $keys = array_keys( $this->steps );
        return add_query_arg( 'step', $keys[ array_search( $this->step, array_keys( $this->steps ) ) + 1 ] );
    }

    /**
     * Setup Wizard Header.
     */
    public function setup_wizard_header() {        
        ?>
        <!DOCTYPE html>
        <html <?php language_attributes(); ?>>
        <head>
            <meta name="viewport" content="width=device-width" />
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title><?php _e( 'WordPress Seed &rsaquo; Setup Wizard', 'wpseed' ); ?></title>
            <?php wp_print_scripts( 'wpseed-setup' ); ?>
            <?php do_action( 'admin_print_styles' ); ?>
            <?php do_action( 'admin_head' ); ?>
        </head>
        <body class="wpseed-setup wp-core-ui">
            <h1 id="wpseed-logo"><a href="<?php echo WPSEED_HOME;?>"><img src="<?php echo WPSeed()->plugin_url(); ?>/assets/images/wpseed_logo.png" alt="WPSeed" /></a></h1>
        <?php
    }

    /**
     * Setup Wizard Footer.
     */
    public function setup_wizard_footer() {
        ?>
            <?php if ( 'next_steps' === $this->step ) : ?>
                <a class="wpseed-return-to-dashboard" href="<?php echo esc_url( admin_url() ); ?>"><?php _e( 'Return to the WordPress Dashboard', 'wpseed' ); ?></a>
            <?php endif; ?>
            </body>
        </html>
        <?php
    }

    /**
     * Output the steps.
     */
    public function setup_wizard_steps() {      
        $ouput_steps = $this->steps;
        array_shift( $ouput_steps );
        ?>
        <ol class="wpseed-setup-steps">
            <?php foreach ( $ouput_steps as $step_key => $step ) : ?>
                <li class="<?php
                    if ( $step_key === $this->step ) {
                        echo 'active';
                    } elseif ( array_search( $this->step, array_keys( $this->steps ) ) > array_search( $step_key, array_keys( $this->steps ) ) ) {
                        echo 'done';
                    }
                ?>"><?php echo esc_html( $step['name'] ); ?></li>
            <?php endforeach; ?>
        </ol>
        <?php
    }

    /**
     * Output the content for the current step.
     */
    public function setup_wizard_content() {           
        echo '<div class="wpseed-setup-content">'; 
        
        if( !isset( $this->steps[ $this->step ]['view'] ) ) {
            ?><h1><?php _e( 'Invalid Step!', 'wpseed' ); ?></h1><p><?php _e( 'You have attempted to visit a setup step that does not exist. I would like to know how this happened so that I can improve the plugin. Please tell me what you did before this message appeared. If you were just messing around, then stop it you naughty hacker!', 'wpseed' ); ?></p><?php 
        } elseif( !method_exists( $this, $this->steps[ $this->step ]['view'][1] ) ) {
            ?><h1><?php _e( 'Something Has Gone Very Wrong!', 'wpseed' ); ?></h1><p><?php _e( 'You have attempted to visit a step in the setup process that may not be ready yet! This should not have happened. Please report it to me.', 'wpseed' ); ?></p><?php             
        } else {
            call_user_func( $this->steps[ $this->step ]['view'] );
        }
        
        echo '</div>';
    }

    /**
     * Introduction step.
     */
    public function wpseed_setup_introduction() { ?>
        <h1><?php _e( 'Setup WordPress Seed', 'wpseed' ); ?></h1>
        
        <?php if( $this->optional ) { ?>
        
        <p><?php _e( 'Thank you for choosing WordPress Seed to improve your website! The setup wizard will help you configure the basic settings. <strong>It’s completely optional and shouldn’t take longer than five minutes.</strong>', 'wpseed' ); ?></p>
        <p><?php _e( 'No time right now? If you don’t want to go through the wizard, you can skip and return to the WordPress dashboard. You will be able to use the plugin but you might miss some features!', 'wpseed' ); ?></p>
        <p class="wpseed-setup-actions step">
            <a href="<?php echo esc_url( $this->get_next_step_link() ); ?>" class="button-primary button button-large button-next"><?php _e( 'Let\'s Go!', 'wpseed' ); ?></a>
            <a href="<?php echo esc_url( admin_url() ); ?>" class="button button-large"><?php _e( 'Not right now', 'wpseed' ); ?></a>
        </p>
        
        <?php } else { ?> 
            
        <p><?php _e( 'Thank you for choosing WordPress Seed to improve your website! The setup wizard will help you configure the basic settings.', 'wpseed' ); ?></p>
        <p><?php _e( 'No time right now? If you don’t want to go through the wizard, you can return to the WordPress dashboard but will be unable to use the plugin. Come back when you are ready to continue by clicking the Run the Setup Wizard button!', 'wpseed' ); ?></p>
        <p class="wpseed-setup-actions step">
            <a href="<?php echo esc_url( $this->get_next_step_link() ); ?>" class="button-primary button button-large button-next"><?php _e( 'Let\'s Go!', 'wpseed' ); ?></a>
            <a href="<?php echo esc_url( admin_url() ); ?>" class="button button-large"><?php _e( 'Not right now', 'wpseed' ); ?></a>
        </p>
                    
        <?php }
    }

    /**
     * Access setup allowing user to select which administrators can access the plugin
     * during installation.
     */
    public function wpseed_setup_administrators() { 
        $args = array(
            'blog_id'      => $GLOBALS['blog_id'],
            'role'         => 'administrator',
            'exclude'      => array(1),
            'orderby'      => 'login',
            'fields'       => array( 'ID', 'user_nicename', 'display_name', )
        ); 
        $users = get_users( $args ); ?>
        
        <h1><?php _e( 'Choose Administrator Access', 'wpseed' ); ?></h1>
        
        <form method="post">

            <?php 
            if( !$users ) { 
                echo '<p>' . __( 'Your the only administrator, no actions are needed here. If you had other administrator accounts in your WordPress database, they would be listed here.', 'wpseed' ) . '</p>'; 
            }else{
                echo '<p>' . __( 'You have the opportunity of limiting access to the plugin while it is being configured. Public features and services will be hidden from both visitors and staff until you are ready to fully launch the plugin.', 'wpseed' ) . '</p>'; 
            ?>
            
            <table class="wpseed-setup-extensions" cellspacing="0">
                <thead>
                    <tr>
                        <th class="extension-name"><?php _e( 'User ID', 'wpseed' ); ?></th>
                        <th class="extension-description"><?php _e( 'Username', 'wpseed' ); ?></th>
                        <th class="extension-description"><?php _e( 'Display Name', 'wpseed' ); ?></th>
                        <th class="extension-description"></th>
                    </tr>
                </thead>
                <tbody>
                
                    <?php foreach( $users as $key => $user_object ) { ?>
                    <tr>
                        <td class="access-name"><?php echo $user_object->ID ?></td>
                        <td><?php echo $user_object->user_nicename; ?></td>
                        <td><?php echo $user_object->display_name; ?></td>
                        <td><label for="currency_pos">
                                <select id="currency_pos" name="currency_pos" class="wpseed-enhanced-select">
                                    <option value="left" <?php selected( null, 'now' ); ?>><?php echo __( 'Allow Now', 'wpseed' ); ?></option>
                                    <option value="right" <?php selected( null, 'never' ); ?>><?php echo __( 'Never Allow', 'wpseed' ); ?></option>
                                    <option value="left_space" <?php selected( null, 'launch' ); ?>><?php echo __( 'On Launch', 'wpseed' ); ?></option>
                                </select>
                            </label>                        
                        </td>
                    </tr>
                    <?php } ?>

                </tbody>
            </table>

            <p><?php _e( 'Once the wizard is complete, you will be offered a button to fully launch the plugin services. Only the administrators with On Launch selected will see the plugins settings pages.', 'wpseed' ); ?></p>

            <?php } ?>
            
            <p class="wpseed-setup-actions step">
                <input type="submit" class="button-primary button button-large button-next" value="<?php esc_attr_e( 'Continue', 'wpseed' ); ?>" name="save_step" />
                <a href="<?php echo esc_url( $this->get_next_step_link() ); ?>" class="button button-large button-next"><?php _e( 'Skip this step', 'wpseed' ); ?></a>
                <?php wp_nonce_field( 'wpseed-setup' ); ?>
            </p>
        </form>
        <?php
    }

    /**
     * Save Page Settings.
     */
    public function wpseed_setup_administrators_save() {          
        check_admin_referer( 'wpseed-setup' );

        wp_redirect( esc_url_raw( $this->get_next_step_link() ) );
        exit;
    }

    /**
     * Folders and files step.
     */
    public function wpseed_setup_folders() { ?>
        <h1><?php _e( 'Create Folders &amp; Files', 'wpseed' ); ?></h1>
        <form method="post">
            <table class="wpseed-setup-extensions" cellspacing="0">
                <thead>
                    <tr>
                        <th class="extension-name"><?php _e( 'Name', 'wpseed' ); ?></th>
                        <th class="extension-description"><?php _e( 'Type', 'wpseed' ); ?></th>
                        <th class="extension-description"><?php _e( 'Path', 'wpseed' ); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="access-name"><?php _e( 'Extensions', 'wpseed' ); ?></td>
                        <td><?php _e( 'Folder', 'wpseed' ); ?></td>
                        <td>wp-content/wpseed-extensions</td>
                    </tr>
                </tbody>
            </table>
            
            <p><?php _e( 'This step exists to explain the folders and files that will appear within your installation of WP. Please try to avoid removing the folders and files you see in the list above. They will be installed when you click Continue or you can skip this step if you are an advanced user.', 'wpseed' ); ?></p>
            
            <p class="wpseed-setup-actions step">
                <input type="submit" class="button-primary button button-large button-next" value="<?php esc_attr_e( 'Continue', 'wpseed' ); ?>" name="save_step" />
                <a href="<?php echo esc_url( $this->get_next_step_link() ); ?>" class="button button-large button-next"><?php _e( 'Skip this step', 'wpseed' ); ?></a>
                <?php wp_nonce_field( 'wpseed-setup' ); ?>
            </p>
        </form>
        <?php
    }

    /**
     * Create folders and files.
     */
    public function wpseed_setup_folders_save() {       
        check_admin_referer( 'wpseed-setup' );
        wp_redirect( esc_url_raw( $this->get_next_step_link() ) );
        exit;
    }

    /**
     * Database changes overview step.
     */
    public function wpseed_setup_database() {        
        ?>
        <h1><?php _e( 'Database Changes', 'wpseed' ); ?></h1>
        <form method="post">
            
            <p><?php _e( 'WordPress Seed needs to insert these options into your database and they are important for the plugin to run.', 'wpseed' ); ?></p>
            <table class="wpseed-setup-extensions" cellspacing="0">
                <thead>
                    <tr>
                        <th class="extension-name"><?php _e( 'Option Name', 'wpseed' ); ?></th>
                        <th class="extension-description"><?php _e( 'Description', 'wpseed' ); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="access-name"><?php _e( 'Extensions', 'wpseed' ); ?></td>
                        <td><?php _e( 'Folder', 'wpseed' ); ?></td>
                        <td>wp-content/wpseed-extensions</td>
                    </tr>
                </tbody>
            </table>            
            <p><?php _e( 'The plugin will not create or alter any database tables for this installation.', 'wpseed' ); ?></p>
            <table class="wpseed-setup-extensions" cellspacing="0">
                <thead>
                    <tr>
                        <th class="extension-name"><?php _e( 'Table Name', 'wpseed' ); ?></th>
                        <th class="extension-description"><?php _e( 'Description', 'wpseed' ); ?></th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
            <p class="wpseed-setup-actions step">
                <input type="submit" class="button-primary button button-large button-next" value="<?php esc_attr_e( 'Continue', 'wpseed' ); ?>" name="save_step" />
                <a href="<?php echo esc_url( $this->get_next_step_link() ); ?>" class="button button-large button-next"><?php _e( 'Skip this step', 'wpseed' ); ?></a>
                <?php wp_nonce_field( 'wpseed-setup' ); ?>
            </p>
        </form>
        <?php
    }

    /**
     * Save shipping and tax options.
     */
    public function wpseed_setup_database_save() {           
        check_admin_referer( 'wpseed-setup' );
        wp_redirect( esc_url_raw( $this->get_next_step_link() ) );
        exit;
    }

    /**
     * Array of official and endorsed extensions.
     * 
     * @return array
     */
    protected function get_wizard_extensions() {       
        $gateways = array(
            'csv2post' => array(
                'name'        => __( 'CSV 2 POST', 'wpseed' ),
                'description' => __( 'Import data for the purpose of mass publishing posts. Another plugin by Ryan Bayne.', 'wpseed' ),
                'repo-slug'   => 'csv-2-post',
                'source'        => 'remote'
            ),  /*
            'stripe' => array(
                'name'        => __( 'Channel Solution for Twitch', 'wpseed' ),
                'description' => __( 'A modern and robust wa.', 'wpseed' ),
                'repo-slug'   => 'channel-solution-for-twitch',
                'source'        => 'remote',
            ),            
            'paypal' => array(
                'name'        => __( 'PayPal Standard', 'wpseed' ),
                'description' => __( 'Accept payments via PayPal using account balance or credit card.', 'wpseed' ),
                'settings'    => array(
                    'email' => array(
                        'label'       => __( 'PayPal email address', 'wpseed' ),
                        'type'        => 'email',
                        'value'       => get_option( 'admin_email' ),
                        'placeholder' => __( 'PayPal email address', 'wpseed' ),
                    ),
                ),
                'source'        => 'local'
            ),
            'cheque' => array(
                'name'        => _x( 'Check Payments', 'Check payment method', 'wpseed' ),
                'description' => __( 'A simple offline gateway that lets you accept a check as method of payment.', 'wpseed' ),
                'source'        => 'local'
            ),
            'bacs' => array(
                'name'        => __( 'Bank Transfer (BACS) Payments', 'wpseed' ),
                'description' => __( 'A simple offline gateway that lets you accept BACS payment.', 'wpseed' ),
                'source'        => 'local'
            ) */
        );

        return $gateways;
    }

    /**
     * Extensions selection step.
     * 
     * Both WordPress.org plugins and packaged plugins are offered.
     */
    public function wpseed_setup_extensions() {
        $gateways = $this->get_wizard_extensions();?>
        
        <h1><?php _e( 'Extensions', 'wpseed' ); ?></h1>   
        <p><?php _e( 'Normal WordPress plugins safely downloaded from wordpress.org website.', 'wpseed' ); ?></p>
         
        <form method="post" class="wpseed-wizard-plugin-extensions-form">
            
            <ul class="wpseed-wizard-plugin-extensions">
                <?php foreach ( $gateways as $gateway_id => $gateway ) : ?>
                    <li class="wpseed-wizard-extension wpseed-wizard-extension-<?php echo esc_attr( $gateway_id ); ?>">
                        <div class="wpseed-wizard-extension-enable">
                            <input type="checkbox" name="wpseed-wizard-extension-<?php echo esc_attr( $gateway_id ); ?>-enabled" class="input-checkbox" value="yes" />
                            <label>
                                <?php echo esc_html( $gateway['name'] ); ?>
                            </label>
                        </div>
                        <div class="wpseed-wizard-extension-description">
                            <?php echo wp_kses_post( wpautop( $gateway['description'] ) ); ?>
                        </div>
                        <?php if ( ! empty( $gateway['settings'] ) ) : ?>
                            <table class="form-table wpseed-wizard-extension-settings">
                                <?php foreach ( $gateway['settings'] as $setting_id => $setting ) : ?>
                                    <tr>
                                        <th scope="row"><label for="<?php echo esc_attr( $gateway_id ); ?>_<?php echo esc_attr( $setting_id ); ?>"><?php echo esc_html( $setting['label'] ); ?>:</label></th>
                                        <td>
                                            <input
                                                type="<?php echo esc_attr( $setting['type'] ); ?>"
                                                id="<?php echo esc_attr( $gateway_id ); ?>_<?php echo esc_attr( $setting_id ); ?>"
                                                name="<?php echo esc_attr( $gateway_id ); ?>_<?php echo esc_attr( $setting_id ); ?>"
                                                class="input-text"
                                                value="<?php echo esc_attr( $setting['value'] ); ?>"
                                                placeholder="<?php echo esc_attr( $setting['placeholder'] ); ?>"
                                                />
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </table>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
           
            <p class="wpseed-setup-actions step">
                <input type="submit" class="button-primary button button-large button-next" value="<?php esc_attr_e( 'Continue', 'wpseed' ); ?>" name="save_step" />
                <a href="<?php echo esc_url( $this->get_next_step_link() ); ?>" class="button button-large button-next"><?php _e( 'Skip this step', 'wpseed' ); ?></a>
                <?php wp_nonce_field( 'wpseed-setup' ); ?>
            </p>
        </form>
        <?php
    }

    /**
     * Extensions installation and activation.
     * 
     * Both mini-extensions (single files stored in wp-content) and plugin-extensions
     * (plugins downloaded from wordpress.org) are handled by this step.
     */
    public function wpseed_setup_extensions_save() {                  
        check_admin_referer( 'wpseed-setup' );

        $gateways = $this->get_wizard_extensions();

        foreach ( $gateways as $gateway_id => $gateway ) {
            // If repo-slug is defined, download and install plugin from .org.
            if ( ! empty( $gateway['repo-slug'] ) && ! empty( $_POST[ 'wpseed-wizard-extension-' . $gateway_id . '-enabled' ] ) ) {
                wp_schedule_single_event( time() + 10, 'wpseed_plugin_background_installer', array( $gateway_id, $gateway ) );
            }

            $settings_key        = 'wpseed_' . $gateway_id . '_settings';
            $settings            = array_filter( (array) get_option( $settings_key, array() ) );
            $settings['enabled'] = ! empty( $_POST[ 'wpseed-wizard-extension-' . $gateway_id . '-enabled' ] ) ? 'yes' : 'no';

            if ( ! empty( $gateway['settings'] ) ) {
                foreach ( $gateway['settings'] as $setting_id => $setting ) {
                    $settings[ $setting_id ] = wpseed_clean( $_POST[ $gateway_id . '_' . $setting_id ] );
                }
            }

            update_option( $settings_key, $settings );
        }

        wp_redirect( esc_url_raw( $this->get_next_step_link() ) );
        exit;
    }

    /**
     * Improvement program and feedback.
     */
    public function wpseed_setup_improvement() { ?>
        <h1><?php _e( 'Improvement Program &amp; Feedback', 'wpseed' ); ?></h1>
        <p><?php _e( 'Taking the time to provide constructive feedback and allowing the plugin to send none-sensitive data to me can be as valuable as a donation.', 'wpseed' ); ?></p>
        
        <form method="post">
            <table class="form-table">
                <tr>
                    <th scope="row"><label for="wpseed_allow_information_sending"><?php _e( 'Allow none-sensitive information to be sent to Ryan Bayne?', 'wpseed' ); ?></label></th>
                    <td>
                        <input type="checkbox" id="wpseed_allow_information_sending" <?php checked( get_option( 'wpseed_ship_to_countries', '' ) !== 'disabled', true ); ?> name="wpseed_allow_information_sending" class="input-checkbox" value="1" />
                        <label for="wpseed_allow_information_sending"><?php _e( 'Yes, send configuration and logs only.', 'wpseed' ); ?></label>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="wpseed_allow_future_prompt"><?php _e( 'Allow the plugin to prompt you for feedback in the future?', 'wpseed' ); ?></label></th>
                    <td>
                        <input type="checkbox" <?php checked( get_option( 'wpseed_calc_taxes', 'no' ), 'yes' ); ?> id="wpseed_allow_future_prompt" name="wpseed_allow_future_prompt" class="input-checkbox" value="1" />
                        <label for="wpseed_allow_future_prompt"><?php _e( 'Yes, prompt me in a couple of months.', 'wpseed' ); ?></label>
                    </td>
                </tr>
            </table>
            <p class="wpseed-setup-actions step">
                <input type="submit" class="button-primary button button-large button-next" value="<?php esc_attr_e( 'Continue', 'wpseed' ); ?>" name="save_step" />
                <a href="<?php echo esc_url( $this->get_next_step_link() ); ?>" class="button button-large button-next"><?php _e( 'Skip this step', 'wpseed' ); ?></a>
                <?php wp_nonce_field( 'wpseed-setup' ); ?>
            </p>
        </form>
        <?php
    }

    /**
     * Save improvement program and feedback.
     */
    public function wpseed_setup_improvement_save() { 
        check_admin_referer( 'wpseed-setup' );
        wp_redirect( esc_url_raw( $this->get_next_step_link() ) );
        exit;
    }
    
    public function wpseed_setup_ready_actions() {
        // Stop showing notice inviting user to start the setup wizard. 
        WPSeed_Admin_Notices::remove_notice( 'install' );      
    }    
    
    /**
     * Final step.
     */
    public function wpseed_setup_ready() {
        $this->wpseed_setup_ready_actions();?>
        <h1><?php _e( 'WordPress Seed is Ready!', 'wpseed' ); ?></h1>

        <div class="wpseed-setup-next-steps">
            <div class="wpseed-setup-next-steps-first">
                <h2><?php _e( 'Next Steps', 'wpseed' ); ?></h2>
                <ul>
                    <li class="setup-thing"><a class="button button-primary button-large" href="<?php echo esc_url( admin_url( 'options-general.php?page=wpseed-settings' ) ); ?>"><?php _e( 'Go to Settings', 'wpseed' ); ?></a></li>
                </ul>                                                                                                 
            </div>
            <div class="wpseed-setup-next-steps-last">
            
                <h2><?php _e( 'Contact Ryan', 'wpseed' ); ?></h2>
                
                <a href="https://ryanbayne.slack.com/threads/team/squeekycoder/"><?php _e( 'Slack', 'wpseed' ); ?></a>
                <a href="https://join.skype.com/pJAjfxcbfHPN"><?php _e( 'Skype', 'wpseed' ); ?></a>
                <a href="https://discord.gg/PcqNqNh"><?php _e( 'Discord', 'wpseed' ); ?></a>
                <a href="https://twitter.com/Ryan_R_Bayne"><?php _e( 'Twitter', 'wpseed' ); ?></a>
                <a href="https://plus.google.com/u/0/collection/oA85PE"><?php _e( 'Google+', 'wpseed' ); ?></a>
  
            </div>
        </div>
        <?php
    }
}

endif;

new WPSeed_Admin_Setup_Wizard();