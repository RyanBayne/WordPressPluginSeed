<?php
/**
 * WPSeed Example Settings
 *
 * @author      Ryan Bayne
 * @category    Admin
 * @package     WPSeed/Admin
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if ( ! class_exists( 'WPSeed_Settings_General' ) ) :

/**
 * WPSeed_Settings_Example.
 */
class WPSeed_Settings_Example extends WPSeed_Settings_Page {

    /**
     * Constructor.
     */
    public function __construct() {

        $this->id    = 'example';
        $this->label = __( 'Example Inputs', 'wpseed' );

        add_filter( 'wpseed_settings_tabs_array', array( $this, 'add_settings_page' ), 20 );
        add_action( 'wpseed_settings_' . $this->id, array( $this, 'output' ) );
        add_action( 'wpseed_settings_save_' . $this->id, array( $this, 'save' ) );
    }

    /**
     * Get settings array.
     *
     * @return array
     */
    public function get_settings() {

        $settings = apply_filters( 'wpseed_example_settings', array(

            array( 'title' => __( 'Options Header Example One', 'wpseed' ), 'type' => 'title', 'desc' => '', 'id' => 'section_one' ),

            array(
                'title'    => __( 'Example Select', 'wpseed' ),
                'id'       => 'wpseed_example_select_basic',
                'desc_tip' =>  __( 'Example of a basic select menu.', 'wpseed' ),
                'default'  => 'itemone',
                'type'     => 'select',
                'class'    => 'wpseed-enhanced-select',
                'options'  => array(
                    ''          => __( 'Please select an item...', 'wpseed' ),
                    'itemone'   => __( 'Item One', 'wpseed' ),
                    'itemtwo'   => __( 'Item Two', 'wpseed' ),
                    'itemthree' => __( 'Item Three', 'wpseed' ),
                ),
            ),

            array(
                'title'   => __( 'Example Checkbox', 'wpseed' ),
                'desc'    => __( 'Example of a basic checkbox.', 'wpseed' ),
                'id'      => 'wpseed_example_checkbox_basic',
                'default' => 'no',
                'type'    => 'checkbox'
            ),

            array(
                'title'    => __( 'Store Notice Text', 'wpseed' ),
                'desc'     => '',
                'id'       => 'wpseed_example_textarea_basic',
                'default'  => __( 'An example of a basic textarea.', 'wpseed' ),
                'type'     => 'textarea',
                'css'     => 'width:350px; height: 65px;',
                'autoload' => false
            ),

            array( 'type' => 'sectionend', 'id' => 'section_one'),

            array( 'title' => __( 'Options Header Example Two', 'wpseed' ), 'type' => 'title', 'desc' => __( 'An example description which is attached to the header by way of array.', 'wpseed' ), 'id' => 'some_example_options' ),

            array(
                'title'    => __( 'Example Text Input', 'wpseed' ),
                'desc'     => __( 'This is an example of a basic text input..', 'wpseed' ),
                'id'       => 'wpseed_example_text_basic',
                'css'      => 'width:50px;',
                'default'  => '.',
                'type'     => 'text',
                'desc_tip' =>  true,
            ),

            array(
                'title'    => __( 'Example Number Input', 'wpseed' ),
                'desc'     => __( 'This is an example number input.', 'wpseed' ),
                'id'       => 'wpseed_example_number_basic',
                'css'      => 'width:50px;',
                'default'  => '2',
                'desc_tip' =>  true,
                'type'     => 'number',
                'custom_attributes' => array(
                    'min'  => 0,
                    'step' => 1
                )
            ),

            array( 'type' => 'sectionend', 'id' => 'some_example_options' )

        ) );

        return apply_filters( 'wpseed_get_settings_' . $this->id, $settings );
    }

    /**
     * Save settings.
     */
    public function save() {
        $settings = $this->get_settings();

        WPSeed_Admin_Settings::save_fields( $settings );
    }

}

endif;

return new WPSeed_Settings_Example();
