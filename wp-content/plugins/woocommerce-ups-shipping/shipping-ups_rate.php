<?php
/*
Plugin Name: WooCommerce UPS Shipping Pro
Plugin URI:  http://ignitewoo.com
Description: Provides UPS shipping rates. Capable of calculating a maximum box size and weight for all items in a cart to shipped in one package. (Commercial Software)
Version: 3.0.5
Author: <strong>IgniteWoo.com - Custom Add-ons for WooCommerce!</strong>
Author URI: http://ignitewoo.com

Copyright (c) 2012 - IgniteWoo.com -- ALL RIGHTS RESERVED

LICENSE: 
This is a single site license product. 
You must purchase a copy of this software from IgniteWoo.com in order to receive plugin updates/bug fixes and support. 

NOTE: 

Designers can use the following template tag to insert tracking numbers in their My Account template ( shortcode-my_account.php )


<?php insert_ups_track_ids( $order->id, $before = '<div>', $after = '</div>' ); ?>

Each tracking number is output in a HREF tag that links directly to the UPS tracking information page.
The link itself is wrapped in whatever you set for $before and $after. Defaults are shown above.


CHANGELOG: 

SEE THE README.txt FILE

*/ 
/**
* Required functions
*/
if ( ! function_exists( 'ignitewoo_queue_update' ) )
	require_once( dirname( __FILE__ ) . '/ignitewoo_updater/ignitewoo_update_api.php' );

$this_plugin_base = plugin_basename( __FILE__ );

add_action( "after_plugin_row_" . $this_plugin_base, 'ignite_plugin_update_row', 1, 2 );


/**
* Plugin updates
*/
ignitewoo_queue_update( plugin_basename( __FILE__ ), 'f43ad72336b82ad90f982f157b79b726', '49' );



add_action( 'woocommerce_shipping_init', 'init_ups_shipping');

function init_ups_shipping() { 
	require_once( dirname(__FILE__) . '/ups_class.php' );
	add_filter('woocommerce_shipping_methods', 'add_ups_rate_method', 10 );
	// order page meta box
	add_action( 'add_meta_boxes', array( 'ups_rate', 'add_order_meta_box' ), -1 );
}

function add_ups_rate_method( $methods ) {
	$methods[] = 'ups_rate'; 
	return $methods;
}