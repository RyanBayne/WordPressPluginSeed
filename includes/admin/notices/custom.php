<?php
/**
 * Admin View: Custom Notices
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

?>
<div id="message" class="updated wpseed-message">
    <a class="wpseed-message-close notice-dismiss" href="<?php echo esc_url( wp_nonce_url( add_query_arg( 'wpseed-hide-notice', $notice ), 'wpseed_hide_notices_nonce', '_wpseed_notice_nonce' ) ); ?>"><?php _e( 'Dismiss', 'wpseed' ); ?></a>
    <?php echo wp_kses_post( wpautop( $notice_html ) ); ?>
</div>
