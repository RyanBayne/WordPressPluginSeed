<?php             
/**
 * WPSeed - WordPress.org API
 *
 * Interacts with WordPress.org and fetches plugins data. 
 *
 * @author   Ryan Bayne
 * @category External
 * @package  WPSeed/WordPressAPI
 * @since    1.0.0
 */
 
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class WPSeed_Wordpressorgapi {  

    /**
    * Query plugin data on WordPress.org
    */
    public function query_plugins( $url = 'http://api.wordpress.org/plugins/info/1.0/', $args = array() ) {
        return wp_remote_post(
            $url,
            array(
                'body' => array(
                    'action' => 'query_plugins',
                    'request' => serialize((object)$args)
                )
            )
        );    
    }

    /**
    * Query plugin data on WordPress.org. 
    */
    public function query_themes( $url = 'http://api.wordpress.org/plugins/info/1.0/', $args = array()) {
        return wp_remote_post(
            $url,
            array(
                'body' => array(
                    'action' => 'query_themes',
                    'request' => serialize((object)$args)
                )
            )
        );    
    }
       
    /**
    * Plugin properties as stored on WordPress.org
    * 
    * @version 1.2
    */
    public function plugin_properties() {              
        return array(
            'slug'              => array( 'description' => __( 'The slug of the plug-in to return the data for.', 'wpseed' ) ), 
            'author'            => array( 'description' => __( '(When the action is query_plugins). The author\'s WordPress username, to retrieve plugins by a particular author.', 'wpseed' ) ),  
            'version'           => array( 'description' => __( 'Latest plugin version.', 'wpseed' ) ),
            'author'            => array( 'description' => __( 'Author name and link to profile.', 'wpseed' ) ), 
            'requires'          => array( 'description' => __( 'The minimum WordPress version required.', 'wpseed' ) ), 
            'tested'            => array( 'description' => __( 'The latest WordPress version tested.', 'wpseed' ) ), 
            'compatibility'     => array( 'description' => __( "An array which contains an array for each version of your plug-in. This array stores the number of votes, the number of 'works' votes and this number as a percentage.", 'wpseed' ) ), 
            'downloaded'        => array( 'description' => __( 'The number of times the plugin has been downloaded.', 'wpseed' ) ), 
            'rating'            => array( 'description' => __( 'The plugins rating as percentage.', 'wpseed' ) ), 
            'num_ratings'       => array( 'description' => __( 'Number of times the plugin has been rated.', 'wpseed' ) ),
            'sections'          => array( 'description' => __( "An array with the HTML for each section on the WordPress plug-in page as values, keys can include 'description', 'installation', 'screenshots', 'changelog' and 'faq'.", 'wpseed' ) ),  
            'description'       => array( 'description' => __( 'Plugins full description, default false.', 'wpseed' ) ),
            'short_description' => array( 'description' => __( 'Plugins short description, default false.', 'wpseed' ) ), 
            'name'              => array( 'description' => __( 'Name of the plugin.', 'wpseed' ) ),
            'author_profile'    => array( 'description' => __( 'Unsure, please update. Does it return URL to authors profile or an array of the authors details?', 'wpseed' ) ), 
            'tags'              => array( 'description' => __( 'Unsure.', 'wpseed' ) ),
            'homepage'          => array( 'description' => __( 'Unsure.', 'wpseed' ) ), 
            'contributors'      => array( 'description' => __( 'Array of contributors.', 'wpseed' ) ), 
            'added'             => array( 'description' => __( 'When the plugin was added to the repository.', 'wpseed' ) ),
            'last_updated'      => array( 'description' => __( 'Unsure, please update. It may be the author stated update or the last time the repository for this plugin was updated.', 'wpseed' ) ),
        );
    }

    /**
    * Theme properties as stored on WordPress.org
    * 
    * @version 1.2
    */
    public function theme_properties() {            
        return array(
            'slug'              => array( 'description' => __( 'The slug of the theme to return the data for.', 'wpseed' ) ), 
            'browse'            => array( 'description' => __( 'Takes the values featured, new or updated.', 'wpseed' ) ), 
            'author'            => array( 'description' => __( 'The author\'s username, to retrieve themes by a particular author.', 'wpseed' ) ), 
            'tag'               => array( 'description' => __( 'An array of tags with which to retrieve themes for.', 'wpseed' ) ),  
            'search'            => array( 'description' => __( 'A search term, with which to search the repository.', 'wpseed' ) ), 
            'fields'            => array( 'description' => __( 'An array with a true or false value for each key (field). The fields that are included make up the properties of the returned object above.', 'wpseed' ) ),  
            'version'           => array( 'description' => __( 'Themes latest version.', 'wpseed' ) ), 
            'author'            => array( 'description' => __( 'Author of the theme.', 'wpseed' ) ),
            'preview_url'       => array( 'description' => __( 'URL to wp-themes.com hosted preview.', 'wpseed' ) ), 
            'screenshot_url'    => array( 'description' => __( 'URL to screenshot image.', 'wpseed' ) ), 
            'screenshot_count'  => array( 'description' => __( 'Number of screenshots the theme has.', 'wpseed' ) ), 
            'screenshots'       => array( 'description' => __( 'Array of screenshot URLs', 'wpseed' ) ), 
            'rating'            => array( 'description' => __( 'Themes rating as a percentage.', 'wpseed' ) ),
            'num_ratings'       => array( 'description' => __( 'Number of times the theme has been rated.', 'wpseed' ) ), 
            'downloaded'        => array( 'description' => __( 'Number of times the theme has been downloaded.', 'wpseed' ) ), 
            'sections'          => array( 'description' => __( 'Array of the data from each section on the plugins page.', 'wpseed' ) ),
            'description'       => array( 'description' => __( 'Description of the theme.', 'wpseed' ) ),
            'download_link'     => array( 'description' => __( 'Unsure, please update. Is it a HTML link or URL?', 'wpseed' ) ),
            'name'              => array( 'description' => __( 'Name of the theme.', 'wpseed' ) ),
            'slug'              => array( 'description' => __( 'The themes slug, may not match themes full name.', 'wpseed' ) ),
            'tags'              => array( 'description' => __( 'Theme tags as found in readme.txt', 'wpseed' ) ),
            'homepage'          => array( 'description' => __( 'Themes home page.', 'wpseed' ) ),
            'contributors'      => array( 'description' => __( 'Array of contributors.', 'wpseed' ) ),
            'last_updated'      => array( 'description' => __( 'Unsure, please update. Is it the authors stated last update month and year or is it a repository time.', 'wpseed' ) ),
        );
    }
}