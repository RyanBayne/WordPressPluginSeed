<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if ( ! class_exists( 'WPSeed_ListTable_Stuff' ) ) {
    require_once( 'class.wpseed-listtable-demo.php' );
}

/**
 * WPSeed_MainView_Team_Items.
 * 
 * This is one of multiple classes that extends a parent class which builds
 * the table. This approach essentially splits a table into common views just as if
 * a search criteria was entered.  
 *
 * @author      Ryan Bayne
 * @category    Admin
 * @package     WPSeed/Admin/MainViews
 * @version     1.0.0
 */
class WPSeed_MainView_Team_Items extends WPSeed_ListTable_Demo {

    /**
     * No items found text.
     */
    public function no_items() {
        _e( 'No applicable items found.', 'wpseed' );
    }

    /**
     * Filter the main data result and only return the items that apply
     * to this report.
     *
     * @param int $current_page
     * @param int $per_page
     */
    public function get_items( $current_page, $per_page ) {
        global $wpdb;
        
        // Filter $this->items to create a dataset suitable for this view.
        unset($this->items[1],$this->items[2],$this->items[3]);          
    }
}