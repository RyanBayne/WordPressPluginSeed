<?php
/**
 * Admin View: Settings
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

?>
        
<div class="wrap wpseed">
    <h1>
        <?php _e( 'WPSeed Settings', 'wpseed' ); ?>
    </h1>
    <form method="<?php echo esc_attr( apply_filters( 'wpseed_settings_form_method_tab_' . $current_tab, 'post' ) ); ?>" id="mainform" action="" enctype="multipart/form-data">
        <nav class="nav-tab-wrapper woo-nav-tab-wrapper">
            <?php
                foreach ( $tabs as $name => $label ) {
                    echo '<a href="' . admin_url( 'options-general.php?page=wpseed-settings&tab=' . $name ) . '" class="nav-tab ' . ( $current_tab == $name ? 'nav-tab-active' : '' ) . '">' . $label . '</a>';
                }
                do_action( 'wpseed_settings_tabs' );
            ?>
        </nav>
        <h1 class="screen-reader-text"><?php echo esc_html( $tabs[ $current_tab ] ); ?></h1>
        <?php
            do_action( 'wpseed_sections_' . $current_tab );

            self::show_messages();

            do_action( 'wpseed_settings_' . $current_tab );
            do_action( 'wpseed_settings_tabs_' . $current_tab ); // @deprecated hook
        ?>
        <p class="submit">
            <?php if ( empty( $GLOBALS['hide_save_button'] ) ) : ?>
                <input name="save" class="button-primary wpseed-save-button" type="submit" value="<?php esc_attr_e( 'Save changes', 'wpseed' ); ?>" />
            <?php endif; ?>
            <?php wp_nonce_field( 'wpseed-settings' ); ?>
        </p>
    </form>
</div>
