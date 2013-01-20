<?php
/**
 * Plugin Name: WooCommerce Advanced Notifications
 * Plugin URI: http://www.woothemes.com/woocommerce/
 * Description: Add additonal, advanced order and stock notifications to WordPress - ideal for improving store management or for dropshippers.
 * Version: 1.0.0
 * Author: WooThemes / Mike Jolley
 * Author URI: http://mikejolley.com
 * License: GPLv3
 */

/**
 * Required functions
 */
if ( ! function_exists( 'is_woocommerce_active' ) )
	require_once( 'woo-includes/woo-functions.php' );

/**
 * Plugin updates
 */
if ( is_admin() ) {
	$woo_plugin_updater_adv_notifications = new WooThemes_Plugin_Updater( __FILE__ );
	$woo_plugin_updater_adv_notifications->api_key = '277af82927fb12f9e7c89152b3822694';
	$woo_plugin_updater_adv_notifications->init();
}


/**
 * Localisation
 **/
load_plugin_textdomain( 'wc_adv_notifications', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );


/**
 * init_advanced_notifications function.
 *
 * @access public
 * @return void
 */
function init_advanced_notifications() {

	if ( is_woocommerce_active() )
		include_once( 'classes/class-wc-advanced-notifications.php' );

}

add_action( 'plugins_loaded', 'init_advanced_notifications', 0 );


/**
 * Activation
 */
register_activation_hook( __FILE__, 'activate_advanced_notifications' );

function activate_advanced_notifications() {
	global $wpdb;

	$wpdb->hide_errors();

	$collate = '';
    if ( $wpdb->supports_collation() ) {
		if( ! empty($wpdb->charset ) )
			$collate .= "DEFAULT CHARACTER SET $wpdb->charset";

		if( ! empty($wpdb->collate ) )
			$collate .= " COLLATE $wpdb->collate";
    }

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

    /**
     * Table for notifications
     */
    $sql = "
CREATE TABLE {$wpdb->prefix}advanced_notifications (
  notification_id bigint(20) NOT NULL auto_increment,
  recipient_name LONGTEXT NULL,
  recipient_email LONGTEXT NULL,
  recipient_address LONGTEXT NULL,
  recipient_phone varchar(240) NULL,
  recipient_website varchar(240) NULL,
  notification_type varchar(240) NULL,
  notification_plain_text int(1) NOT NULL,
  notification_totals int(1) NOT NULL,
  notification_prices int(1) NOT NULL,
  notification_sent_count bigint(20) NOT NULL default 0,
  PRIMARY KEY  (notification_id)
) $collate;
";
    dbDelta($sql);

    $sql = "
CREATE TABLE {$wpdb->prefix}advanced_notification_triggers (
  notification_id bigint(20) NOT NULL,
  object_id bigint(20) NOT NULL,
  object_type varchar(200) NOT NULL,
  PRIMARY KEY  (notification_id,object_id)
) $collate;
";
    dbDelta($sql);
}