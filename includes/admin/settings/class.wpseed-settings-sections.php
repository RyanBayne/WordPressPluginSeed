<?php
/**
 * WPSeed Sections Settings
 *
 * @author   Ryan Bayne
 * @category Admin
 * @package  WPSeed/Admin
 * @version  1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if ( ! class_exists( 'WPSeed_Settings_Sections' ) ) :

/**
 * WPSeed_Settings_Sections.
 */
class WPSeed_Settings_Sections extends WPSeed_Settings_Page {

    /**
     * Constructor.
     */
    public function __construct() {

        $this->id    = 'sections';
        $this->label = __( 'Sections Example', 'wpseed' );

        add_filter( 'wpseed_settings_tabs_array', array( $this, 'add_settings_page' ), 20 );
        add_action( 'wpseed_settings_' . $this->id, array( $this, 'output' ) );
        add_action( 'wpseed_settings_save_' . $this->id, array( $this, 'save' ) );
        add_action( 'wpseed_sections_' . $this->id, array( $this, 'output_sections' ) );
    }

    /**
     * Get sections.
     *
     * @return array
     */
    public function get_sections() {

        $sections = array(
            ''              => __( 'Section A', 'wpseed' ),
            'sectionb'       => __( 'Section B', 'wpseed' ),
        );

        return apply_filters( 'wpseed_get_sections_' . $this->id, $sections );
    }

    /**
     * Output the settings.
     */
    public function output() {
        global $current_section;

        $settings = $this->get_settings( $current_section );

        WPSeed_Admin_Settings::output_fields( $settings );
    }

    /**
     * Save settings.
     */
    public function save() {
        global $current_section;

        $settings = $this->get_settings( $current_section );
        WPSeed_Admin_Settings::save_fields( $settings );
    }

    /**
     * Get settings array.
     *
     * @return array
     */
    public function get_settings( $current_section = '' ) {
        if ( 'sectionb' == $current_section ) {

            $settings = apply_filters( 'wpseed_sectionb_settings', array(
            
                array(
                    'title' => __( 'Title and Introduction Example', 'wpseed' ),
                    'type'     => 'title',
                    'desc'     => __( 'This is the example of an introduction which is part of the titles data.', 'wpseed' ),
                    'id'     => 'image_options'
                ),

                array(
                    'title'         => __( 'Example Checkbox', 'wpseed' ),
                    'desc'          => __( 'Example input descripton.', 'wpseed' ),
                    'id'            => 'wpseed_enable_examplecheckbox2',
                    'default'       => 'yes',
                    'desc_tip'      => __( 'This is an example of a tip.', 'wpseed' ),
                    'type'          => 'checkbox'
                ),

                array(
                    'type'     => 'sectionend',
                    'id'     => 'image_options'
                )

            ));
        } else {
            $settings = apply_filters( 'wpseed_checkboxesexamples_general_settings', array(
 
                array(
                    'title' => __( 'Checkboxes Examples', 'wpseed' ),
                    'type'     => 'title',
                    'desc'     => '',
                    'id'     => 'checkboxes_example_options',
                ),

                array(
                    'title'           => __( 'Checkbox Group', 'wpseed' ),
                    'desc'            => __( 'Checkbox group with start and end parameters in use.', 'wpseed' ),
                    'id'              => 'wpseed_checkbox_example_start',
                    'default'         => 'yes',
                    'type'            => 'checkbox',
                    'checkboxgroup'   => 'start',
                    'show_if_checked' => 'option',
                ),

                array(
                    'desc'            => __( 'Middle Checkbox', 'wpseed' ),
                    'id'              => 'wpseed_checkbox_example_middle',
                    'default'         => 'yes',
                    'type'            => 'checkbox',
                    'checkboxgroup'   => '',
                    'show_if_checked' => 'yes',
                    'autoload'        => false,
                ),

                array(
                    'desc'            => __( 'End Checkbox"', 'wpseed' ),
                    'id'              => 'wpseed_checkbox_example_end',
                    'default'         => 'no',
                    'type'            => 'checkbox',
                    'checkboxgroup'   => 'end',
                    'show_if_checked' => 'yes',
                    'autoload'        => false,
                ),

                array(
                    'type'     => 'sectionend',
                    'id'     => 'checkboxes_example_options'
                ),

            ));
        }

        return apply_filters( 'wpseed_get_settings_' . $this->id, $settings, $current_section );
    }
}

endif;

return new WPSeed_Settings_Sections();
