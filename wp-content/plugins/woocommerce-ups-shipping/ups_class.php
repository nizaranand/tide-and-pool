<?php
/**
WooCommerce UPS Shipping classes
Copyright (c) 2012 - IgniteWoo.com  -- ALL RIGHTS RESERVED

****** This is NOT GPL Code ******

Single Site license. No re-distribution rights. 

*/ 
if ( class_exists( 'woocommerce_shipping_method' ) ) {
	class ups_rate_base extends woocommerce_shipping_method {}
} else if ( class_exists( 'WC_Shipping_Method' ) ) {
	class ups_rate_base extends WC_Shipping_Method {}
}

class ups_rate extends ups_rate_base {
	
	function __construct() { 

		$this->id = 'ups_rate';

		$this->method_title = __( 'UPS', 'woocommerce' );

		// Load form fields
		$this->init_form_fields();

		// Load settings
		$this->init_settings();

		// User config vars
		$this->enabled		= $this->settings['enabled'];
		$this->title 		= $this->settings['title'];
		$this->availability 	= $this->settings['availability'];
		$this->countries 	= $this->settings['countries'];
		$this->type 		= $this->settings['type'];
		$this->tax_status	= $this->settings['tax_status'];
		$this->fee 		= $this->settings['fee']; 
		$this->license 		= $this->settings['license']; 
		$this->userid 		= $this->settings['userid']; 
		$this->password 	= $this->settings['password']; 
		$this->shippernumber	= $this->settings['shippernumber']; 
		$this->origincountry	= $this->settings['origincountry']; 
		$this->negotiated_rates	= $this->settings['negotiated_rates']; 
		$this->originstate	= $this->settings['originstate']; 
		$this->originzip	= $this->settings['originzip']; 
		$this->dimension_unit 	= $this->settings['dimension_unit']; 
		$this->weight_unit	= $this->settings['weight_unit']; 
		$this->pickup_type	= $this->settings['pickup_type']; 
		$this->include_value	= $this->settings['include_value'];
		$this->packaging_type	= $this->settings['packaging_type']; 
		$this->dev_server	= $this->settings['dev_server']; 
		$this->destination_type = $this->settings['destination_type'];

		$this->use_cache	= 'no'; //$this->settings['use_cache']; 


		if ( '' == $this->settings['origincountry'] ) 
			$this->origincountry = get_option( 'woocommerce_ups_rate_country', false );

		if ( '' == $this->settings['originstate'] ) 
			$this->originstate = get_option( 'woocommerce_ups_rate_state', false );

		if ( '' == $this->settings['originzip'] ) 
			$this->originzip = get_option( 'woocommerce_ups_rate_originzip', false );


		// admin settings 
		// As of WooCom 1.5.4 these hooks are use
		add_action('woocommerce_update_options_shipping_ups_rate', array(&$this, 'process_admin_options'));
		add_action('woocommerce_update_options_shipping_ups_rate', array(&$this, 'process_ups_rates'));

		// Earlier versions of WooCom rely on these hooks instead - keep for backward compatibility
		add_action('woocommerce_update_options_shipping_methods', array(&$this, 'process_admin_options'));
		add_action('woocommerce_update_options_shipping_methods', array(&$this, 'process_ups_rates'));

		// order page meta box
		add_action( 'add_meta_boxes', array( &$this, 'add_order_meta_box' ), -1 );

		// remove shipping method if no zip code is set
		add_action( 'plugins_loaded', array( &$this, 'remove_shipping_method' ), 99 );

		// caching control
		//add_action( 'init', array( &$this, 'update_cart_action' ), -9999 );
		add_action( 'init', array( &$this, 'cart_calculate_shipping' ), -9999 );
		add_action( 'init', array( &$this, 'add_to_cart_action' ), -9999 );
		add_action( 'wp', array( &$this, 'clear_cart_after_payment' ), -9999 );
		add_action( 'woocommerce_before_checkout_process', array( &$this, 'is_cart_expired' ), 1 );
		add_action( 'wp_ajax_woocommerce_update_order_review', array( &$this, 'is_cart_expired' ), -9999 );
		add_action( 'wp_ajax_nopriv_woocommerce_update_order_review', array( &$this, 'is_cart_expired'), -9999 );
		add_action( 'wp', array( &$this, 'clear_cart_on_return' ), -9999 );
		add_action( 'wp_logout', array( &$this, 'clear_cart_on_logout' ), -9999 );
		add_action( 'woocommerce_checkout_update_order_review', array( &$this, 'check_dest_zip'), 1, 1 );

		// checkout page - zip change script
		add_action( 'wp_head', array( &$this, 'insert_script' ) );

		// check for jurassic era servers
		add_action( 'init', array( &$this, 'software_tests' ), -1 );

		//add_action( 'woocommerce_after_order_notes', array( &$this, 'after_order_notes' ) );
	} 


	// insert script that removes previous shipping options while new rates are calculated
	function insert_script() { 
		global $woocommerce;
	?>
		<script>
		jQuery( document ).ready( function() { 
			jQuery('#billing_postcode').change( function() {
				jQuery('#shipping_method').replaceWith('<?php _e( '<img src="'.$woocommerce->plugin_url(). '/assets/images/ajax-loader.gif" align="absmiddle"> Calculating rates...', 'woocommerce' )?>');
			});
			jQuery('#shipping_postcode').change( function() {
				jQuery('#shipping_method').replaceWith('<?php _e( '<img src="'.$woocommerce->plugin_url(). '/assets/images/ajax-loader.gif" align="absmiddle"> Calculating rates...', 'woocommerce' )?>');
			});
		});
		</script>
	<?php
	}


	// Make sure the site is not running on Jurassic era software
	function software_tests() { 
		global $woocommerce;

		if ( is_admin() && !function_exists( 'curl_init' ) ) { 

			add_action( 'admin_notices',  array( &$this, 'curl_nag' ) );

		}

		if ( is_admin() && !class_exists( 'SimpleXMLElement' ) ) { 

			add_action( 'admin_notices',  array( &$this, 'simplexml_nag' ) );

		}

		if ( version_compare( $woocommerce->version, '1.4.0') <= 0) {

			add_action( 'admin_notices', array( &$this, 'woocomver_nag' ) );

		}

	}


	function curl_nag() { 

		echo '<div style="background-color:#cf0000;color:#fff;font-weight:bold;font-size:16px;margin: -1px 15px 0 5px;padding:5px 10px">';
		_e( 'Your server does not support CURL so the UPS Shipping Module will not work for you.<br/>Ask your hosting company to install CURL for PHP', 'woocommerce' );
		echo '</div>';

	}


	function simplexml_nag() { 

		echo '<div style="background-color:#cf0000;color:#fff;font-weight:bold;font-size:16px;margin: -1px 15px 0 5px;padding:5px 10px">';
		_e( 'Your server does not support SimpleXMLElement so the UPS Shipping Module will not work for you.<br/>Ask your hosting company to enable it for PHP.', 'woocommerce' );
		echo '</div>';

	}

	function woocomver_nag() { 
		global $woocommerce;

		echo '<div style="background-color:#cf0000;color:#fff;font-weight:bold;font-size:16px;margin: -1px 15px 0 5px;padding:5px 10px">';
		_e( 'The UPS Shipping module requires WooCommerce 1.4.0 or newer to work correctly. You\'re using version', 'woocommerce' );
		echo ' ' . $woocommerce->version; 
		echo '</div>';

	}


	function is_available() {
		global $woocommerce;
		
		if ( 'no' == $this->enabled ) return false;
			
		if ( 'specific' == $this->availability ) {
			
			if ( is_array( $this->countries ) ) 
				if ( !in_array( $woocommerce->customer->get_shipping_country(), $this->countries ) ) 
					return false;
			
		} 

		return apply_filters( 'woocommerce_shipping_' . $this->id . '_is_available', true );

	} 


	function remove_shipping_method() { 
		global $woocommerce;

		if ( is_admin() ) return;

		if ( ( '' == $_SESSION['customer']['postcode'] && '' == $_SESSION['customer']['shipping_postcode'] ) || '' == $_SESSION['customer']['country'] ) {

			$unset = false;

			for ( $i = 0; $i < count( $woocommerce->shipping->shipping_methods ); $i++ ) { 

				if ( $woocommerce->shipping->shipping_methods[ $i ]->id = 'ups_rate' ) 
					$unset = $i;

			}

			if ( $unset !== false ) 
				unset( $woocommerce->shipping->shipping_methods[ $unset ] );

		}

	}


	// Handle cache updates after any sort of cart update
	function add_to_cart_action() { 
		global $woocommerce; 

		if ( !session_id() || !isset( $_SESSION ) ) return;

		if ( 'no' == $this->use_cache || !$this->use_cache ) { 

			$_SESSION['ups_cache']  = '';
			$_SESSION['ups_rate'] = '';
			$_SESSION['ups_cache_zip'] = '';
			return;

		}

		$go = false; 

		if ( !empty( $_GET['add-to-cart'] ) && $woocommerce->verify_nonce( 'add_to_cart', '_GET') ) 
			$go = true;
		elseif ( isset( $_GET['remove_item'] ) && $_GET['remove_item'] && $woocommerce->verify_nonce('cart', '_GET') )
			$go = true;
		elseif ( isset($_POST['update_cart']) && $_POST['update_cart'] && $woocommerce->verify_nonce('cart') ) 
			$go = true;

		if ( !$go ) return;


		$cart = $woocommerce->cart->get_cart();
		$cached_cart = $_SESSION['ups_cart'];

		// delete cache if anything changes in the cart
		if ( $cart !== $cached_cart ) {

			$_SESSION['ups_cache']  = '';
			$_SESSION['ups_rate'] = '';
			$_SESSION['ups_cache_zip'] = '';

		}

	}


	// is the cart page shipping calculator in use - if so did the zip change? 
	function cart_calculate_shipping() { 

		if ( empty( $_POST ) && ( '1' != $_POST['calc_shipping'] ) )
			return;

		// cart page shipping calculator in use - did the zip change? 
		if ( $_POST['calc_shipping_postcode'] !== $_SESSION['ups_cache_zip'] ) { 

			$_SESSION['ups_cache']  = '';
			$_SESSION['ups_rate'] = '';
			$_SESSION['ups_cache_zip'] = '';

		}


	}


	function check_dest_zip( $args = '' ) { 
		global $woocommerce;

		if ( 'no' == $this->use_cache || !$this->use_cache ) return;

                if ( !isset( $_POST['postcode'] ) && !isset( $_POST['s_postcode'] ) )
			return;

		// new shipping postcode entered? if so does it match what we cached? if not, clear cache
		if ( isset( $_POST['s_postcode'] ) && $_POST['s_postcode'] !== $_SESSION['ups_cache_zip'] ) {

			$woocommerce->customer->set_shipping_postcode( $_POST['s_postcode'] );

			$_SESSION['ups_cache']  = '';
			$_SESSION['ups_rate'] = '';
			$_SESSION['ups_cache_zip'] = '';

			$this->calculate_shipping();

			return;

		}

		// ship to billing set? 
		// new billing postcode entered? if so does it match what we cached? if not, clear cache
		if ( isset( $_POST['shiptobilling'] ) && isset( $_POST['postcode'] ) && $_POST['postcode'] !== $_SESSION['ups_cache_zip'] ) {

			$woocommerce->customer->set_postcode( $_POST['postcode'] );

			$_SESSION['ups_cache']  = '';
			$_SESSION['ups_rate'] = '';
			$_SESSION['ups_cache_zip'] = '';

			$this->calculate_shipping();

			return;

		}

	}


	// if cart is expired make certain cache is cleared
	function is_cart_expired() { 
		global $woocommerce;

		if ( 'no' == $this->use_cache || !$this->use_cache ) return;

		if ( 0 != sizeof( $woocommerce->cart->get_cart() ) )
			return;

		$_SESSION['ups_cache']  = '';
		$_SESSION['ups_rate'] = '';
		$_SESSION['ups_cache_zip'] = '';

	}


	// Empty cache after checkout- modeled on WooCommerce core code
	function clear_cart_on_return() { 

		if ( 'no' == $this->use_cache || !$this->use_cache ) return;

		if ( !is_page( get_option( 'woocommerce_thanks_page_id' ) ) )
			return;
		
		if ( isset( $_GET['order'] ) ) 
			$order_id = $_GET['order']; 
		else 
			$order_id = 0;

		if ( isset( $_GET['key'] ) ) 
			$order_key = $_GET['key']; 
		else 
			$order_key = '';

		if ( $order_id > 0 ) {

			$order = &new woocommerce_order( $order_id );

			if ( $order->order_key == $order_key ) {

				$_SESSION['ups_cache'] = '';
				$_SESSION['ups_cart'] = '';
				$_SESSION['ups_cache_zip'] = '';

			}

		}

	}



	// clear cache after payment accepted -- modeled on WooCommerce core code
	function clear_cart_after_payment( $url = false ) {
		global $woocommerce;

		if ( !session_id() ) return;

		if ( 'no' == $this->use_cache || !$this->use_cache ) return;
		
		if ( isset( $_SESSION['order_awaiting_payment']) && $_SESSION['order_awaiting_payment'] > 0 ) { 
			
			$order = &new woocommerce_order( $_SESSION['order_awaiting_payment'] );
			
			if ( $order->id > 0 && 'pending' !== $order->status ) {

					$_SESSION['ups_cache'] = '';
					$_SESSION['ups_cart'] = '';
					$_SESSION['ups_cache_zip'] = '';

			}
			
		}
        
	}


	// clear cache on logout -- modeled on WooCommerce core code
	function clear_cart_on_logout() {

		if ( 'no' == $this->use_cache || !$this->use_cache ) return;
		
		if ( 'yes' == get_option('woocommerce_clear_cart_on_logout') ) { 
			
			$_SESSION['ups_cache'] = '';
			$_SESSION['ups_cart'] = '';
			
		}
	}


	// Init form fields - modeled on WooCommerce core code
	function init_form_fields() {
		global $woocommerce;

		$this->form_fields = array(
				'enabled' => array(
							'title' 		=> __( 'Enable/Disable', 'woocommerce' ), 
							'type' 			=> 'checkbox', 
							'label' 		=> __( 'Enable UPS Rate shipping', 'woocommerce' ), 
							'default' 		=> 'yes'
							), 
				'title' => array(
							'title' 		=> __( 'Method Title', 'woocommerce' ), 
							'type' 			=> 'text', 
							'description' 		=> __( 'This controls the title which the user sees during checkout.', 'woocommerce' ), 
							'default'		=> __( 'UPS', 'woocommerce' )
							),
				'availability' => array(
							    'title' 		=> __( 'Method availability', 'woocommerce' ), 
							    'type' 		=> 'select', 
							    'default' 		=> 'all',
							    'class'		=> 'availability',
							    'options'		=> array(
								    'all' 	=> __('All allowed countries', 'woocommerce'),
								    'specific' 	=> __('Specific Countries', 'woocommerce')
							    )
							),
				'countries' => array(
							'title' 		=> __( 'Specific Countries', 'woocommerce' ), 
							'type' 			=> 'multiselect', 
							'class'			=> 'chosen_select',
							'css'			=> 'width: 450px;',
							'default' 		=> '',
							'options'		=> $woocommerce->countries->countries
							),

				'tax_status' => array(
							    'title' 		=> __( 'Tax Status', 'woocommerce' ), 
							    'type' 		=> 'select', 
							    'description' 	=> '', 
							    'default' 		=> 'taxable',
							    'options'		=> array(
								    'taxable' 	=> __('Taxable', 'woocommerce'),
								    'none' 	=> __('None', 'woocommerce')
							    )
							),

				'fee' => array(
							'title' 		=> __( 'Default Handling Fee', 'woocommerce' ), 
							'type' 			=> 'text', 
							'description'		=> __('Handlng fee (this will be added to the shipping fee).', 'woocommerce'),
							'default'		=> ''
							),

				'license' => array(
							'title' 		=> __( 'UPS Access Key', 'woocommerce' ), 
							'type' 			=> 'text', 
							'description'		=> '',
							'default'		=> ''
							),
				'userid' => array(
							'title' 		=> __( 'UPS User ID', 'woocommerce' ), 
							'type' 			=> 'text', 
							'description'		=> '',
							'default'		=> ''
							),
				'password' => array(
							'title' 		=> __( 'UPS Password', 'woocommerce' ), 
							'type' 			=> 'password', 
							'description'		=> '',
							'default'		=> ''
							),
				'shippernumber' => array(
							'title' 		=> __( 'UPS Account Number', 'woocommerce' ), 
							'type' 			=> 'text', 
							'description'		=> __( 'Optional. However you MUST enter this value if you want to use rates that you have negotiated with UPS', 'woocommerce' ),
							'default'		=> ''
							),

				'negotiated_rates' => array(
							'title' 		=> __( 'Use Negotiated Rates', 'woocommerce' ), 
							'type' 			=> 'checkbox', 
							'description'		=> __( 'DO NOT enable unless you have already negotiated preferred rates with UPS!', 'woocommerce' ),
							),

				'dimension_unit' => array(
							    'title' 		=> __( 'Dimensions Unit', 'woocommerce' ), 
							    'type' 		=> 'select', 
							    'default' 		=> 'IN',
							    'options'		=> array(
								    'IN' 	=> __('Inches', 'woocommerce'),
								    'CM' 	=> __('Centimeters', 'woocommerce')
							    )
							),

				'weight_unit' => array(
							    'title' 		=> __( 'Weight Unit', 'woocommerce' ), 
							    'type' 		=> 'select', 
							    'default' 		=> 'LBS',
							    'options'		=> array(
								    'LBS' 	=> __('Pounds', 'woocommerce'),
								    'KGS' 	=> __('Kilograms', 'woocommerce')
							    )
							),

				'pickup_type' => array(
							    'title' 		=> __( 'Pickup Type', 'woocommerce' ), 
							    'type' 		=> 'select', 
							    'description'	=> 'Your typical pickup method.',
							    'default' 		=> '01',
							    'options'		=> array(
								    '01' 	=> __('Daily Pickup', 'woocommerce'),
								    '03' 	=> __('Drop off at Customer Counter', 'woocommerce'),
								    '06'	=> __('One-time Pickup', 'woocommerce')
							    )
							),

				'packaging_type' => array(
							    'title' 		=> __( 'Packaging Type', 'woocommerce' ), 
							    'type' 		=> 'select', 
							    'description'	=> 'Your typical packaging',
							    'default' 		=> '02',
							    'options'		=> array(
								    '02' 	=> __('UPS Packaging', 'woocommerce'),
								    '00' 	=> __('Your Packaging', 'woocommerce'),
							    )
							),

				'include_value' => array(
							'title' 		=> __( 'Include Declared Value', 'woocommerce' ), 
							'type' 			=> 'checkbox', 
							'description'		=> __( 'When enabled, the total value of the order is sent to UPS as the "Declared Value" of the shipment. Note that when declaring a package value shipping cost estimates will increase.', 'woocommerce' ),
							),

				'destination_type' => array(
							'title' 		=> __( 'Typical Destination Type', 'woocommerce' ), 
							'type' 			=> 'select', 
							'description'		=> ' Rates for residential shipments are usually higher than commercial destinations. Set this to reflect the destintation type for the majority of your shipments to improve the accuracy of shipping cost estimates.',
							'default' 		=> 'res',
							'options'		=> array(
									'res' 	=> __('Residential', 'woocommerce'),
									'com' 	=> __('Commercial', 'woocommerce'),
								)
							),

				'dev_server' => array(
							    'title' 		=> __( 'Use UPS test server', 'woocommerce' ), 
							    'type' 		=> 'checkbox', 
							    'description'	=> 'Enable this for testing. DO NOT FORGET to disable this when your store goes live!',
							),

				/*
				'use_cache' => array(
							    'title' 		=> __( 'Use caching', 'woocommerce' ), 
							    'type' 		=> 'checkbox', 
							    'description'	=> 'Enable this to cache shipping cost lookups for customer carts. Improves cart performance.',
							),
				*/
		);

	}

	/*
	function after_order_notes( $checkout = null ) { 
		?>

		<p id="order_comments_field" class="form-row notes">
			<input type="checkbox" value="1" name="shiptoresidential" checked="checked" class="input-checkbox" id="shiptoresidential">
			<label class="" for="shiptoresidential" style="display:inline"> Shipping address is residential</label>

		</p>

		<?php 
	}
	*/

/**
TODO: Add UPS label printing via Shipping API
TODO: Add UPS tracking interface
*/


	// Talk to UPS
	function get_rates( $dest_zip, $dest_state = '', $dest_country, $length, $width, $height, $weight ) {
		global $woocommerce;

		$data ="<?xml version=\"1.0\"?>  
			<AccessRequest xml:lang=\"en-US\">  
			    <AccessLicenseNumber>{$this->license}</AccessLicenseNumber>  
			    <UserId>{$this->userid}</UserId>  
			    <Password>{$this->password}</Password>  
			</AccessRequest>  
			<RatingServiceSelectionRequest xml:lang=\"en-US\">  
			    <Request>  
				<TransactionReference>  
				    <CustomerContext>Bare Bones Rate Request</CustomerContext>  
				    <XpciVersion>1.0001</XpciVersion>  
				</TransactionReference>  
				<RequestAction>Rate</RequestAction>  
				<RequestOption>Shop</RequestOption>  
			    </Request>  
			<PickupType>  
			    <Code>{$this->pickup_type}</Code>  
			</PickupType>  
			<Shipment>  
			    <Shipper>  
				<Address>  
				    <PostalCode>{$this->originzip}</PostalCode>  
				    <CountryCode>{$this->origincountry}</CountryCode>  
				</Address>  
				<ShipperNumber>{$this->shippernumber}</ShipperNumber>  
			    </Shipper>  
			    <ShipTo>  
				<Address>  
				    <StateProvinceCode>$dest_state</StateProvinceCode>
				    <PostalCode>$dest_zip</PostalCode>  
				    <CountryCode>$dest_country</CountryCode>";

			if ( 'res' == $this->destination_type ) { 
$data .= "
				    <ResidentialAddressIndicator>1</ResidentialAddressIndicator>
";
			} 

$data .= "				</Address>  
			    </ShipTo>  
			    <ShipFrom>  
				<Address>  
				    <StateProvinceCode>{$this->originstate}</StateProvinceCode>
				    <PostalCode>{$this->originzip}</PostalCode>  
				    <CountryCode>{$this->origincountry}</CountryCode>  
				</Address>  
			    </ShipFrom>  
			    <Package>  
				<PackagingType>  
				    <Code>{$this->packaging_type}</Code>  
				</PackagingType>  
				<Dimensions>  
				    <UnitOfMeasurement>  
					<Code>{$this->dimension_unit}</Code>  
				    </UnitOfMeasurement>  
				    <Length>$length</Length>  
				    <Width>$width</Width>  
				    <Height>$height</Height>  
				</Dimensions>  
				<PackageWeight>  
				    <UnitOfMeasurement>  
					<Code>{$this->weight_unit}</Code>  
				    </UnitOfMeasurement>  
				    <Weight>$weight</Weight>  
				</PackageWeight>";

			if ( 'yes' == $this->include_value ) { 
				
$data .= "
				<PackageServiceOptions>
				    <InsuredValue>
					<CurrencyCode>" . get_option('woocommerce_currency') . "</CurrencyCode>
					<MonetaryValue>" . $woocommerce->cart->cart_contents_total . "</MonetaryValue>
				    </InsuredValue>
				</PackageServiceOptions> 
";
			}

$data .= "			    </Package>";


		if ( 'yes' == $this->negotiated_rates )
		$data .= "			    <RateInformation>
				<NegotiatedRatesIndicator/>
			    </RateInformation>";

		$data .= "
			</Shipment>  
			</RatingServiceSelectionRequest>";  

		    if ( $this->dev_server ) 
			$url = 'https://wwwcie.ups.com/ups.app/xml/Rate';
		    else 
			// $url = 'https://www.ups.com/ups.app/xml/Rate';
			$url = 'https://onlinetools.ups.com/ups.app/xml/Rate';
			
		    $ch = curl_init( $url ); 

		    curl_setopt( $ch, CURLOPT_HEADER, 1 );
		    curl_setopt( $ch, CURLOPT_POST,1 );
		    curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 10 );
		    curl_setopt( $ch, CURLOPT_TIMEOUT, 60 );
		    curl_setopt( $ch, CURLOPT_RETURNTRANSFER,1 ); 
		    curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 0 );
		    curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 0 );
		    curl_setopt( $ch, CURLOPT_POSTFIELDS, $data );

		    $result = curl_exec ($ch);  

		    $data = strstr( $result, '<?' );  

		    curl_close($ch);  

		    libxml_use_internal_errors(true);

		    $cost_data = new SimpleXMLElement( $data );

		    if ( !$cost_data ) 
			return false;

		    $rates = array();

		    if ( isset( $cost_data->RatedShipment ) ) 
		    foreach ( $cost_data->RatedShipment as $rs ) { 

			    $nrate = '';

			    if ( isset( $rs->NegotiatedRates->NetSummaryCharges->GrandTotal->MonetaryValue ) )
				    $nrate = (string)$rs->NegotiatedRates->NetSummaryCharges->GrandTotal->MonetaryValue;

			    if ( '' != $nrate ) 
				    $rates [ (string)$rs->Service->Code ] = $nrate; 
			    else 
				    $rates[ (string)$rs->Service->Code ] = (string)$rs->TotalCharges->MonetaryValue; 

		    }

		    return $rates; 

	} 


	// Main calc routine - calculates estimated package size and weight, gets rates for each enabled shipping type, caches results
	function calculate_shipping() {
		global $woocommerce;

		if ( sizeof( $woocommerce->cart->get_cart() ) <= 0 ) {
			$this->id = '';
			$this->title = '';
			$this->shipping_total = '';
			$this->shipping_tax = '';
			return false; 
		}


		if ( ( '' == $_SESSION['customer']['postcode'] && '' == $_SESSION['customer']['shipping_postcode'] ) || '' == $_SESSION['customer']['country'] ) {
			$this->id = '';
			$this->title = '';
			$this->shipping_total = '';
			$this->shipping_tax = '';
			return false; 
		}

		if ( 'yes' == $this->use_cache && isset( $_SESSION['ups_cache'] ) && '' != $_SESSION['ups_cache'] ) { 

			$this->rates = array();

			$this->multiple_rates = true;

			$this->ups_rates = array();

			$this->ups_rates = unserialize($_SESSION['ups_cache']);

			if ( is_array( $this->ups_rates ) )
			foreach( $this->ups_rates as $key => $r ) { 

				$next_rate = new stdClass(); 

				$next_rate->id = 'UPS ' . $r->id;

				//$next_rate->title = $name;
				$next_rate->label = $r->label;

				// $next_rate->shipping_total = floatval( $the_rate[0] ) ; 
				$next_rate->cost = floatval( $r->cost ) ; 

				// handling fee, if any
				if ( $this->fee )
					$next_rate->cost += $this->fee;

				$this->add_rate( $next_rate );
			}

			return; 

		}

		$dest_zip = $_SESSION['customer']['shipping_postcode'];
		$dest_state = $_SESSION['customer']['shipping_state'];


		if ( !$dest_zip ) 
			$dest_zip = $_SESSION['customer']['postcode'];


		$dest_country = $_SESSION['customer']['country'];


		$this->shipping_total = 0;
		$this->shipping_tax = 0;
		
		$items = array();

		foreach ( $woocommerce->cart->get_cart() as $item_id => $values) {

			if ( isset( $values['data']->virtual ) && 'no' != $values['data']->virtual )
			    continue;


			if ( '' == trim( $values['data']->weight ) && '' == trim( $values['data']->length ) & '' == trim( $values['data']->height ) && '' == trim( $values['data']->width ) )
			    continue;


			if ( '' == trim( $values['data']->length ) )
				$length = 1;
			else
				$length = floatval( $values['data']->length );


			if ( '' == trim( $values['data']->width ) )
				$width = 1;
			else
				$width = floatval( $values['data']->width );


			if ( '' == trim( $values['data']->height ) )
				$height = 1;
			else
				$height = floatval( $values['data']->height );


			if ( '' == trim( $values['data']->weight ) )
				$weight = 1;
			else
				$weight = floatval( $values['data']->weight );


			if ( '' == trim( $values['quantity'] ) )
				$quantity = 1;
			else
				$quantity = intval( $values['quantity'] );


			$items[] = array( 'l' => $length, 'w' => $width, 'h' => $height, 'we' => $weight, 'q' => $quantity );

		}


		if ( count( $items ) <= 0 ) {
			$this->id = '';
			$this->title = '';
			$this->shipping_total = '';
			$this->shipping_tax = '';
			return false; 
		}

		// guesstimate the box size required if all items are stacked on top of each other vertically
		$total_height = 0;
		$total_width = 0;
		$total_length = 0;
		$total_weight = 0;

		// get total height
		foreach ( $items as $i ) { 
			$total_height += $i['h'];
		}

		foreach ( $items as $i ) { 
			for( $x = 1; $x <= $i['q']; $x++ )
				$total_weight += $i['we'];
		}


		// width and length
		foreach ( $items as $i ) { 

			if ( $i['w'] > $total_width ) 
				$total_width = $i['w'];

			if ( $i['l'] > $total_length ) 
				$total_length = $i['l'];
		}


		if ( $total_height <= 0 || $total_width <= 0 || $total_length <= 0 || $total_weight <= 0 ) {
			$this->id = '';
			$this->title = '';
			$this->shipping_total = '';
			$this->shipping_tax = '';
			return false; 
		}
 

		// echo "h: $total_height w: $total_width l: $total_length we: $total_weight";

		$services = get_option( 'woocommerce_ups_rate_types', false );

		if ( !$services ) return;

		//$_tax = &new woocommerce_tax();

		$this->rates = array();

		$this->ups_rates = array();

		$the_rates = $this->get_rates( $dest_zip, $dest_state, $dest_country, $total_length, $total_width, $total_height, $total_weight );

		if ( false !== $the_rates && is_array( $the_rates ) && count( $the_rates ) > 0 )
		foreach( $the_rates as $code => $rate ) {

			if ( '' == $code || floatval( $rate ) <= 0 )
				continue; 

			foreach( $services as $name => $service_code ) { 

				if ( $code != $service_code )
					continue;

				$next_rate = new stdClass(); 

				$next_rate->id = 'UPS ' . $name;

				//$next_rate->title = $name;
				$next_rate->label = $name;

				// $next_rate->shipping_total = floatval( $the_rate[0] ) ; 
				$next_rate->cost = floatval( $rate ) ; 


				// handling fee, if any
				// handling fee, if any
				if ( $this->fee )
					$next_rate->cost += $this->fee;

				$this->ups_rates[] = $next_rate;

				$this->add_rate( $next_rate );

			}

		}

		$this->multiple_rates = true;

		// cache control
		if ( 'yes' == $this->use_cache ) { 

			unset( $_SESSION['ups_cache'] );
			unset( $_SESSION['ups_cache_zip'] );
			unset( $_SESSION['ups_cart'] );

			$_SESSION['ups_cache'] = serialize($this->ups_rates);
			$_SESSION['ups_cache_zip'] = $dest_zip;
			$_SESSION['ups_cart'] = $woocommerce->cart->get_cart();

		}

	} 


	// Admin panel
	function admin_options() {
		global $woocommerce;
	?>

		<div style="float:right; position:relative; top: 10px; width:250px; border: 3px solid #00cc00; padding: 12px 0; font-weight:bold; font-style:italic; margin-top: 15px; text-align:center; border-radius:7px;-webkit-border-radius:7px">
			<a title='<?php _e( ' More Extensions + Custom WooCommerce Site Development ', 'woocommerce' )?>' href="http://ignitewoo.com" target="_blank" style="color:#0000cc; text-decoration:none">
			    <img style="height:50px" src="data:image/png;base64,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%3D%3D">
			</a>
			<br>
			<?php _e( 'Get more custom plugins <br/> + Custom development', 'woocommerce' ); ?>
			<br><br>
			<a title=" More Extensions + Custom WooCommerce Site Development " href="http://ignitewoo.com" target="_blank" style="color:#0000cc; text-decoration:none">Contact us!<br>IgniteWoo.com now!</a>
		</div>

		<h3><?php _e('UPS Shipping', 'woocommerce'); ?></h3>
		<p><?php _e('Configure your API access credentials and other details.', 'woocommerce'); ?></p>

		<p style="color:#cf0000;font-weight:bold">
		    <?php _e('You must do the following or UPS will not be able to quote shipping rates:', 'woocommerce'); ?>
		</p>
		    <?php echo '<ul style="color:#cf0000;font-weight:bold; margin-left: 30px">'; ?>
			<li style="list-style-type:disc"><?php _e('Configure weights and dimensions for your products', 'woocommerce');?></li>
			<li style="list-style-type:disc"><?php _e('Configure your catalog settings to use weights and dimensions of pounds/inches or kilograms/centimeters', 'woocommerce');?></li>
		    <?php echo '</ul>'; ?>
		<p style="color:#cf0000;font-weight:bold">
		    <?php _e('Please do not contact us for support until you have verified correct settings.', 'woocommerce'); ?>
		</p>

		<table class="form-table" >

			<?php $this->generate_settings_html(); ?>

		<?php

			$countries = $woocommerce->countries->countries;

			$country = $this->origincountry;

			$state = $this->originstate;

			if ( '' == $state )
				$state = '*';


                ?>
			<tr valign="top">
				<th scope="rpw" class="titledesc">Shipping Origin Country / State</th>
				<td class="forminp">
					<select class="chosen_select" name="woocommerce_ups_rate_country_state" data-placeholder="<?php _e('Choose a country&raquo;', 'woocommerce'); ?>" title="Country">
						    <?php echo $woocommerce->countries->country_dropdown_options( $country, $state ); ?>
					</select>
				</td>
			</tr>

			<tr valign="top">
				<th class="titledesc" scope="row">Package Origin Zip Code</th>
				<td class="forminp">
					<fieldset><legend class="screen-reader-text"><span>Shipping Origin Zip Code</span></legend>
						<label for="woocommerce_ups_rate_originzip"><input type="text" value="<?php echo $this->originzip ?>" style="" id="woocommerce_ups_rate_originzip" name="woocommerce_ups_rate_originzip" class="input-text wide-input "><span class="description">Zip Code where you packages are labeled as shipping from</span></label>
					</fieldset>
				</td>
			</tr>

			<?php $rate_types = get_option( 'woocommerce_ups_rate_types', false ); ?>

			<tr valign="top">
			    <th scope="row" class="titledesc"><?php _e('Rates Types to Offer', 'woocommerce'); ?>:</th>
			    <td class="forminp" id="flat_rates">

				    <table>
					<tbody>
					    <thead>
						<tr><th>Domestic</th><th>International</th><th>Poland</th></tr>
					    </thead>

					    <tr><td valign="top">
					    <input type="checkbox" name="rate_types[Next Day Air Early AM]" value="14"  <?php if ( $rate_types['Next Day Air Early AM'] ) echo 'checked="checked"'; ?>> Next Day Air Early AM<br/>
					    <input type="checkbox" name="rate_types[Next Day Air]" value="01" <?php if ( $rate_types['Next Day Air'] ) echo 'checked="checked"'; ?>> Next Day Air<br/>
					    <input type="checkbox" name="rate_types[Next Day Air Saver]" value="13" <?php if ( $rate_types['Next Day Air Saver'] ) echo 'checked="checked"'; ?>> Next Day Air Saver<br/>
					    <input type="checkbox" name="rate_types[2nd Day Air AM]" value="59" <?php if ( $rate_types['2nd Day Air AM'] ) echo 'checked="checked"'; ?>> 2nd Day Air AM<br/>
					    <input type="checkbox" name="rate_types[2nd Day Air]" value="02" <?php if ( $rate_types['2nd Day Air'] ) echo 'checked="checked"'; ?>> 2nd Day Air<br/>
					    <input type="checkbox" name="rate_types[3 Day Select]" value="12" <?php if ( $rate_types['3 Day Select'] ) echo 'checked="checked"'; ?>> 3 Day Select<br/>
					    <input type="checkbox" name="rate_types[Ground]" value="03" <?php if ( $rate_types['Ground'] ) echo 'checked="checked"'; ?>> Ground<br/>
					    </td>

					    <td valign="top">
					    <input type="checkbox" name="rate_types[Standard]" value="11" <?php if ( $rate_types['Standard'] ) echo 'checked="checked"'; ?>> Standard<br/>
					    <input type="checkbox" name="rate_types[Worldwide Express]" value="07" <?php if ( $rate_types['Worldwide Express'] ) echo 'checked="checked"'; ?>> Worldwide Express<br/>
					    <input type="checkbox" name="rate_types[Worldwide Express Plus]" value="54" <?php if ( $rate_types['Worldwide Express Plus'] ) echo 'checked="checked"'; ?>> Worldwide Express Plus<br/>
					    <input type="checkbox" name="rate_types[Worldwide Expedited]" value="08" <?php if ( $rate_types['Worldwide Expedited'] ) echo 'checked="checked"'; ?>> Worldwide Expedited<br/>
					    <input type="checkbox" name="rate_types[Saver]" value="65" <?php if ( $rate_types['Saver'] ) echo 'checked="checked"'; ?>> Saver<br/>
					    </td>

					    <td valign="top">
					    <input type="checkbox" name="rate_types[UPS Today Standard]" value="82" <?php if ( $rate_types['UPS Today Standard'] ) echo 'checked="checked"'; ?>> UPS Today Standard<br/>
					    <input type="checkbox" name="rate_types[UPS Today Dedicated Courier]" value="83" <?php if ( $rate_types['UPS Today Dedicated Courier'] ) echo 'checked="checked"'; ?>> UPS Today Dedicated Courier<br/>
					    <input type="checkbox" name="rate_types[UPS Today Intercity]" value="84" <?php if ( $rate_types['UPS Today Intercity'] ) echo 'checked="checked"'; ?>> UPS Today Intercity<br/>
					    <input type="checkbox" name="rate_types[UPS Today Express]" value="85" <?php if ( $rate_types['UPS Today Express'] ) echo 'checked="checked"'; ?>> UPS Today Express<br/>
					    <input type="checkbox" name="rate_types[UPS Today ExpressSaver]" value="86" <?php if ( $rate_types['UPS Today ExpressSaver'] ) echo 'checked="checked"'; ?>> UPS Today ExpressSaver<br/>
					    </td></tr>

					</tbody>
				    </table>
			    </td>

			</tr>

		</table>

	<?php
	} 


	// process rate type settings
	function process_ups_rates() {

		if ( isset( $_POST['rate_types'] ) )
			$ups_rate_types = $_POST['rate_types'];
		    
		update_option( 'woocommerce_ups_rate_types', $ups_rate_types );

		if ( isset( $_POST['woocommerce_ups_rate_country_state'] ) )
			$data = explode( ':', $_POST['woocommerce_ups_rate_country_state'] );

		update_option( 'woocommerce_ups_rate_country', $data[0] );

		if ( empty( $data[1] ) )
			$data[1] = '';

		update_option( 'woocommerce_ups_rate_state', $data[1] );

		if ( isset( $_POST['woocommerce_ups_rate_originzip'] ) )
			update_option( 'woocommerce_ups_rate_originzip', $_POST['woocommerce_ups_rate_originzip'] );

	}


	function add_order_meta_box() { 
		add_meta_box( 'woocommerce-ups-tracking', __('UPS Tracking ID', 'woocommerce'), array( 'ups_rate', 'ups_tracking_number_meta_box' ), 'shop_order', 'side', 'default');

	}

	// add UPS tracking number metabox so admins can enter the number and have it automatically sent to the customer
	function ups_tracking_number_meta_box() {
		global $woocommerce, $post;

		?>

		<div class="add_note">
			<h4><?php _e( 'Add UPS Tracking Number(s)', 'woocommerce'); ?></h4>
			<p><?php _e( 'Enter the UPS tracking numbers (one per line) and click the Update button to save. The customer will be notified.', 'woocommerce'); ?></p>
			<p><textarea name="ups_tracking" id="ups_tracking_id"><?php echo get_post_meta( $_GET['post'], 'ups_tracking_number', true ); ?></textarea><br/>
			<a href="#" class="add_tracking button"><?php _e('Update', 'woocommerce'); ?></a>
		</div>
		<script type="text/javascript">
			
			jQuery( 'a.add_tracking' ).click( function(){
				
				if ( !jQuery( 'textarea#ups_tracking_id' ).val() ) return;
				
				var data = {
					action: 'woocommerce_add_ups_tracking_id',
					post_id:'<?php echo $post->ID; ?>',
					the_ids: jQuery('textarea#ups_tracking_id').val(),
					security: '<?php echo wp_create_nonce( "add-ups-tracking_number" ); ?>'
				};

				jQuery.post( '<?php echo admin_url('admin-ajax.php'); ?>', data, function( response ) {} );
				
				return false;
				
			});
			
		</script>
		<?php
	}

}


// Ajax action callback for tracking number meta box input
add_action('wp_ajax_woocommerce_add_ups_tracking_id', 'woocommerce_add_ups_tracking_id' );

function woocommerce_add_ups_tracking_id() { 

	check_ajax_referer( 'add-ups-tracking_number', 'security' );

	// save tracking number to order meta data
	update_post_meta( $_POST['post_id'], 'ups_tracking_number', $_POST['the_ids'] );

	$the_ids = explode( "\n", $_POST['the_ids'] );

	// send a note to the customer

        $order = &new woocommerce_order( $_POST['post_id'] );

	if ( !$order ) return;
            
        $email_heading = __( 'A note has been added to your order', 'woocommerce' );
            
        $subject = sprintf(__( '[%s] A note has been added to your order', 'woocommerce' ), get_bloginfo( 'name' ) );

	ob_start();

	?>

	<p><?php _e( "Hello, your order has a UPS tracking number:", 'woocommerce' ); ?></p>

	<p><?php _e( 'Order #', 'woocommerce' ) . ' ' . $_POST['post_id']; ?></p>

	<?php foreach( $the_ids as $id ) { ?>

		<p>UPS Tracking ID: <a href="https://wwwapps.ups.com/WebTracking/processInputRequest?AgreeToTermsAndConditions=yes&loc=en_US&tracknum=<?php echo $id ?>"><?php echo $id ?></a></p>

	<?php } ?>

	<p><?php _e( 'Thanks for your business!', 'woocommerce' ) ?></p>

	<?php

        $message = ob_get_clean();

        woocommerce_mail( $order->billing_email, $subject, $message );

	die;

}


// TEMPLATE TAG FOR DISPLAYING TRACKING NUMBERS: 
function insert_ups_track_ids( $order_id, $before = '<div>', $after = '</div>' ) { 

	$the_ids = get_post_meta( $order_id, 'ups_tracking_number', true );

	if ( !$the_ids ) return;

	$the_ids = explode( "\n", $the_ids );

	foreach( $the_ids as $id )
		echo $before . 'UPS Tracking ID: <a target="_blank" href="https://wwwapps.ups.com/WebTracking/processInputRequest?AgreeToTermsAndConditions=yes&loc=en_US&tracknum=' . $id .'">'. $id . '</a>' . $after;

}


/*******************************************************************************************

class ups_tracker { 

	    var $AccessLicenseNumber;  
	    var $UserId;  
	    var $Password;
	    var $credentials;

	    function __construct($access,$user,$pass) {
		$this->AccessLicenseNumber = $access;
		$this->UserID = $user;
		$this->Password = $pass;	
		$this->credentials = 1;
	    }

	    function getTrack($trackingNumber) {
		if ($this->credentials != 1) {
			print 'Please set your credentials';
			die();
		}
		
		$data ="<?xml version=\"1.0\"?>
		<AccessRequest xml:lang='en-US'>
		    <AccessLicenseNumber>$this->AccessLicenseNumber</AccessLicenseNumber>
		    <UserId>$this->UserID</UserId>
		    <Password>$this->Password</Password>
		</AccessRequest>
		<?xml version=\"1.0\"?>
		<TrackRequest>
		    <Request>
			<TransactionReference>
			    <CustomerContext>
				<InternalKey>blah</InternalKey>
			    </CustomerContext>t_
			    <XpciVersion>1.0</XpciVersion>
			</TransactionReference>
			<RequestAction>Track</RequestAction>
		    </Request>
		    <TrackingNumber>$trackingNumber</TrackingNumber>
		</TrackRequest>";
		$ch = curl_init('https://www.ups.com/ups.app/xml/Track');
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch,CURLOPT_POST,1);
		curl_setopt($ch,CURLOPT_TIMEOUT, 60);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
		$result=curl_exec ($ch);
		// echo '<!-- '. $result. ' -->';
		$data = strstr($result, '<?');
		$xml_parser = xml_parser_create();
		xml_parse_into_struct($xml_parser, $data, $vals, $index);
		xml_parser_free($xml_parser);
		$params = array();
		$level = array();
		foreach ($vals as $xml_elem) {
		if ($xml_elem['type'] == 'open') {
		    if (array_key_exists('attributes',$xml_elem)) {
			list($level[$xml_elem['level']],$extra) = array_values($xml_elem['attributes']);
		    } else {
			$level[$xml_elem['level']] = $xml_elem['tag'];
		    }
		}
		if ($xml_elem['type'] == 'complete') {
		    $start_level = 1;
		    $php_stmt = '$params';
		    while($start_level < $xml_elem['level']) {
			$php_stmt .= '[$level['.$start_level.']]';
			$start_level++;
		    }
		    $php_stmt .= '[$xml_elem[\'tag\']] = $xml_elem[\'value\'];';
		    eval($php_stmt);
		}
		}
		curl_close($ch);
		return $params;
	    }

}


class woocommerce_ups_shipping_label { 


	function __construct() { 
	}


	// ================= Shipment Accept Request ===============
	function accept_request() { 

		$digest = $this->get_digest();

		if ( !$digest || '' == $digest ) 
			return false;

		$xml_request="<?xml version=”1.0″ encoding=”ISO-8859-1″?>
			<AccessRequest>
				<AccessLicenseNumber>ACCESS LICENCE NUMBER</AccessLicenseNumber>
				<UserId>UPS USERNAME</UserId>
				<Password>UPS PASSWORD</Password>
			</AccessRequest>
			<ShipmentAcceptRequest>
				<Request>
					<TransactionReference>
						<CustomerContext>Customer Comment</CustomerContext>
					</TransactionReference>
					<RequestAction>ShipAccept</RequestAction>
					<RequestOption>1</RequestOption>
				</Request>
				<ShipmentDigest>{$digest}</ShipmentDigest>
			</ShipmentAcceptRequest>";

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, “https://wwwcie.ups.com/ups.app/xml/ShipAccept”);

		// uncomment the next line if you get curl error 60: error setting certificate verify locations
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_request);
		curl_setopt($ch, CURLOPT_TIMEOUT, 3600);

		$xml = curl_exec ($ch); // SHIP ACCEPT RESPONSE

		if ( !$xml || '' == $xml ) 
			return false;

		preg_match_all( "/\<ShipmentAcceptResponse\>(.*?)\<\/ShipmentAcceptResponse\>/s", $xml, $bookblocks );

		foreach( $bookblocks[1] as $block ) {

			preg_match_all( "/\<GraphicImage\>(.*?)\<\/GraphicImage\>/", $block, $label ); // GET LABEL

			preg_match_all( "/\<TrackingNumber\>(.*?)\<\/TrackingNumber\>/", $block, $tracking ); // GET TRACKING NUMBER
			//echo( $author[1][0].”\n” );
		}

		echo '<img src="data:image/gif;base64,'. $label[1][0] . '"/>';

	}


	// ==================== GET DIGEST ==========================
	function get_digest() { 

		$xml_request='<?xml version=”1.0″?>
		<AccessRequest xml:lang=”en-US”>
			<AccessLicenseNumber>ACCESS LICENCE NUMBER</AccessLicenseNumber>
			<UserId>UPS USERNAME</UserId>
			<Password>UPS PASSWORD</Password>
		</AccessRequest>
		<ShipmentConfirmRequest xml:lang=”en-US”>
			<Request>
				<TransactionReference>
					<CustomerContext>Customer Comment</CustomerContext>
					<XpciVersion/>
				</TransactionReference>
				<RequestAction>ShipConfirm</RequestAction>
				<RequestOption>validate</RequestOption>
			</Request>
			<LabelSpecification>
				<LabelPrintMethod>
					<Code>GIF</Code>
					<Description>gif file</Description>
				</LabelPrintMethod>
				<HTTPUserAgent>Mozilla/4.5</HTTPUserAgent>
				<LabelImageFormat>
					<Code>GIF</Code>
					<Description>gif</Description>
				</LabelImageFormat>
			</LabelSpecification>
			<Shipment>
				<RateInformation>
					<NegotiatedRatesIndicator/>
				</RateInformation>
				<Description/>
				<Shipper>
					<Name>TEST</Name>
					<PhoneNumber>111-111-1111</PhoneNumber>
					<ShipperNumber>SHIPPER NUMBER</ShipperNumber>
					<TaxIdentificationNumber>1234567890</TaxIdentificationNumber>
					<Address>
						<AddressLine1>AIRWAY ROAD SUITE 7</AddressLine1>
						<City>SAN DIEGO</City>
						<StateProvinceCode>CA</StateProvinceCode>
						<PostalCode>92154</PostalCode>
						<PostcodeExtendedLow></PostcodeExtendedLow>
						<CountryCode>US</CountryCode>
					</Address>
				</Shipper>
				<ShipTo>
					<CompanyName>Yats</CompanyName>
					<AttentionName>Yats</AttentionName>
					<PhoneNumber>123.456.7890</PhoneNumber>
					<Address>
						<AddressLine1>AIRWAY ROAD SUITE 7</AddressLine1>
						<City>SAN DIEGO</City>
						<StateProvinceCode>CA</StateProvinceCode>
						<PostalCode>92154</PostalCode>
						<CountryCode>US</CountryCode>
					</Address>
				</ShipTo>
				<ShipFrom>
					<CompanyName>Ship From Company Name</CompanyName>
					<AttentionName>Ship From Attn Name</AttentionName>
					<PhoneNumber>1234567890</PhoneNumber>
					<TaxIdentificationNumber>1234567877</TaxIdentificationNumber>
					<Address>
						<AddressLine1>AIRWAY ROAD SUITE 7</AddressLine1>
						<City>SAN DIEGO</City>
						<StateProvinceCode>CA</StateProvinceCode>
						<PostalCode>92154</PostalCode>
						<CountryCode>US</CountryCode>
					</Address>
				</ShipFrom>
				<PaymentInformation>
					<Prepaid>
					<BillShipper>
						<AccountNumber>SHIPPER NUMBER</AccountNumber>
					</BillShipper>
					</Prepaid>
				</PaymentInformation>
				<Service>
					<Code>02</Code>
					<Description>2nd Day Air</Description>
				</Service>
				<Package>
					<PackagingType>
						<Code>02</Code>
						<Description>Customer Supplied</Description>
					</PackagingType>
					<Description>Package Description</Description>
					<ReferenceNumber>
						<Code>00</Code>
						<Value>Package</Value>
					</ReferenceNumber>
					<PackageWeight>
						<UnitOfMeasurement/>
						<Weight>60.0</Weight>
					</PackageWeight>
					<LargePackageIndicator/>
					<AdditionalHandling>0</AdditionalHandling>
				</Package>
			</Shipment>
		</ShipmentConfirmRequest>';

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, “https://wwwcie.ups.com/ups.app/xml/ShipConfirm”);

		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_request);
		curl_setopt($ch, CURLOPT_TIMEOUT, 3600);

		$xml = curl_exec ($ch); // SHIP CONFORMATION RESPONSE

		if ( !$xml || '' == $xml )
			return false;

		preg_match_all( "/\<ShipmentConfirmResponse\>(.*?)\<\/ShipmentConfirmResponse\>/s", $xml, $bookblocks );

		foreach( $bookblocks[1] as $block ) {
			preg_match_all( "/\<ShipmentDigest\>(.*?)\<\/ShipmentDigest\>/", $block, $label ); // SHIPPING DIGEST
			return $label[1][0]; 
		}

		return false;

	}

}

*****************************************************************************************/

?>