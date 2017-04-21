<?php
/**
 * Admin View: Notice - Install with wizard start button.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

?>
<div id="message" class="updated wpseed-message wpseed-connect">
    <p><?php _e( '<strong>Welcome to WordPress Seed</strong> &#8211; You&lsquo;re almost ready to begin using the plugin.', 'wpseed' ); ?></p>
    <p class="submit"><a href="<?php echo esc_url( admin_url( 'admin.php?page=wpseed-setup' ) ); ?>" class="button-primary"><?php _e( 'Run the Setup Wizard', 'wpseed' ); ?></a> <a class="button-secondary skip" href="<?php echo esc_url( wp_nonce_url( add_query_arg( 'wpseed-hide-notice', 'install' ), 'wpseed_hide_notices_nonce', '_wpseed_notice_nonce' ) ); ?>"><?php _e( 'Skip Setup', 'wpseed' ); ?></a></p>
</div>
