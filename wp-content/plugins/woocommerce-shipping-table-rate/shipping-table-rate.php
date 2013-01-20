<?php
/*
Plugin Name: WooCommerce Table Rate Shipping
Plugin URI: http://woocommerce.com
Description: Table rate shipping lets you define rates depending on location, price, weight, or item count.
Version: 1.5.3
Author: WooThemes
Author URI: http://woothemes.com
Requires at least: 3.1
Tested up to: 3.2

	Copyright: © 2009-2011 WooThemes.
	License: GNU General Public License v3.0
	License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

/**
 * Required functions
 **/
if ( ! function_exists( 'is_woocommerce_active' ) ) require_once( 'woo-includes/woo-functions.php' );

/**
 * Plugin updates
 **/
if (is_admin()) {
	$woo_plugin_updater_table_rates = new WooThemes_Plugin_Updater( __FILE__ );
	$woo_plugin_updater_table_rates->api_key = 'b3b1ef0a229aa3ae2b6b0ed430dcaf8e';
	$woo_plugin_updater_table_rates->init();
}

/**
 * Check if WooCommerce is active
 **/
if ( is_woocommerce_active() ) {
	
	/**
	 * Localisation
	 **/
	load_plugin_textdomain('wc_table_rate', false, dirname( plugin_basename( __FILE__ ) ) . '/');
	
	/**
	 * Init the shipping method class
	 **/
	function woocommerce_table_rate_shipping_init() {
	
		/**
	 	* Shipping method class
	 	**/
		class WC_Shipping_Table_Rate extends WC_Shipping_Method {
			
			var $table_rates;		// All table rates
			var $available_rates;	// Available table rates titles and costs
			var $rates;				// Available table rates titles and costs
			
			function __construct() { 
				global $woocommerce;
				
				$this->id 				= 'table_rate';
				$this->method_title 	= __('Table rates', 'wc_table_rate');
		        
				// Load the form fields.
				$this->init_form_fields();
				
				// Load the settings.
				$this->init_settings();
		        
		        // Define user set variables
        		$this->enabled		= $this->settings['enabled'];
        		$this->title 			= $this->settings['title'];
				$this->fee 				= $this->settings['handling_fee'];
				$this->tax_status		= $this->settings['tax_status'];
        	
        		// Other variables
        		$this->available_rates	= array();
				$this->multiple_rates	= true;
				$this->get_shipping_rates();
				
				// Actions
				add_action('woocommerce_update_options_shipping_table_rate', array(&$this, 'process_admin_options'));
				add_action('woocommerce_update_options_shipping_table_rate', array(&$this, 'process_table_rates'));
				
				// Terms
				add_action('product_shipping_class_edit_form_fields', array(&$this, 'term_options'), 10,2);
				add_action('edit_term', array(&$this, 'process_class_table_rates'), 10,3);
				
				// Scripts
				add_action('admin_enqueue_scripts', array(&$this, 'admin_scripts'), 10);
		    }
		    
		    function admin_scripts() {
				$screen = get_current_screen();
				
			    if (in_array( $screen->id, array('edit-product_shipping_class') )) :
			    	wp_enqueue_script( 'woocommerce_admin' );
					wp_enqueue_script( 'chosen' );
					wp_enqueue_script( 'jquery-ui-sortable' );
				endif;
		    }

			/**
		     * Initialise Gateway Settings Form Fields
		     */
		    function init_form_fields() {
		    
		    	$this->form_fields = array(
					'enabled' => array(
									'title' 		=> __( 'Enable/Disable', 'woothemes' ), 
									'type' 			=> 'checkbox', 
									'label' 		=> __( 'Enable Table Rate shipping', 'woothemes' ), 
									'default' 		=> 'yes'
								), 
					'title' => array(
									'title' 		=> __( 'Method Title', 'woothemes' ), 
									'type' 			=> 'text', 
									'description' 	=> __( 'This controls the title which the user sees during checkout.', 'woothemes' ), 
									'default'		=> __( 'Table Rate', 'woothemes' )
								),
					'tax_status' => array(
									'title' 		=> __( 'Tax Status', 'woothemes' ), 
									'type' 			=> 'select', 
									'description' 	=> '', 
									'default' 		=> 'taxable',
									'options'		=> array(
										'taxable' 	=> __('Taxable', 'woothemes'),
										'none' 		=> __('None', 'woothemes')
									)
								),
					'handling_fee' => array(
									'title' 		=> __( 'Handling Fee', 'woothemes' ), 
									'type' 			=> 'text', 
									'description'	=> __('Fee excluding tax. Enter an amount, e.g. 2.50, or a percentage, e.g. 5%. Leave blank to disable.', 'woothemes'),
									'default'		=> ''
								)
					);
		    
		    } // End init_form_fields()

			/**
			 * Admin Panel Options 
			 * - Options for bits like 'title' and availability on a country-by-country basis
			 *
			 * @since 1.0.0
			 */
			public function admin_options() {
				global $woocommerce;
		    	?>
		    	<h3><?php _e('Table Rates', 'wc_table_rate'); ?></h3>
		    	<p><?php _e('Table rates let you calculate shipping costs based on weight, price, or items vs destination.', 'wc_table_rate'); ?></p>
		    	<table class="form-table">
			    	<?php
			    		// Generate the HTML For the settings form.
			    		$this->generate_settings_html();
			    	?>
			    	<tr valign="top">
			            <th scope="row" class="titledesc"><?php _e('Shipping Table Rates', 'wc_table_rate'); ?>:</th>
			            <td class="forminp" id="shipping_rates">
			            	<p style="margin-top:0;" class="description"><?php echo sprintf(__('Define rates below. Class specific table rates can be added from the <a href="%s">Shipping Class</a> section.', 'wc_table_rate'), admin_url('edit-tags.php?post_type=product&taxonomy=product_shipping_class')); ?></p>
							<?php $this->shipping_rows( $this->table_rates ) ?>
			            </td>
			        </tr>
			        <tr valign="top">
			            <th scope="row" class="titledesc"><?php _e('Shipping Class Priorities', 'wc_table_rate'); ?>:</th>
			            <td class="forminp" id="shipping_rates">
			            	<p style="margin-top:0;" class="description"><?php _e('When calculating shipping, the cart contents will be searched for all Shipping Classes. If all product shipping classes are identical, the corresponding class will be used. If there is a mix of classes then the class with the highest priority (defined below) will be used. If no shipping classes are found, or if the matching class has it\'s rates disabled, the default rates (on this page) will be used instead.', 'wc_table_rate'); ?></p>
			            	
			            	<?php
			            		$classes = $woocommerce->shipping->get_shipping_classes();
			            		if (!$classes) :
			            			echo '<p class="description">' . __('No shipping classes exist - you can ignore this option :)', 'wc_table_rate') . '</p>';
			            		else :
			            			$priority = (get_option('woocommerce_table_rate_default_priority')!='') ? get_option('woocommerce_table_rate_default_priority') : 10;
			            			?>
			            			<table class="widefat" style="position:relative;">
			            				<thead>
			            					<tr>
			            						<th><?php _e('Class', 'wc_table_rate'); ?></th>
			            						<th><?php _e('Priority', 'wc_table_rate'); ?></th>
			            						<th><?php _e('Configure', 'wc_table_rate'); ?></th>
			            					</tr>
			            				</thead>
			            				<tbody>
			            					<tr>
			            						<th><?php _e('Default', 'wc_table_rate'); ?></th>
			            						<td><input type="text" size="2" name="woocommerce_table_rate_default_priority" value="<?php echo $priority; ?>" /></td>
			            						<td></td>
			            					</tr>
					            			<?php
					            			$woocommerce_table_rate_priorities = get_option('woocommerce_table_rate_priorities');
						            		foreach($classes as $class) :
												$priority = (isset($woocommerce_table_rate_priorities[$class->slug])) ? $woocommerce_table_rate_priorities[$class->slug] : 10;
												
												echo '<tr><th>'.$class->name.'</th><td><input type="text" value="'.$priority.'" size="2" name="woocommerce_table_rate_priorities['.$class->slug.']" /></td><td><a href="'. admin_url( 'edit-tags.php?post_type=product&action=edit&taxonomy=product_shipping_class&tag_ID='.$class->term_id.'' ) .'" class="button" target="_blank">'. __('Configure', 'wc_table_rate') .'</a></td></tr>';
												
											endforeach;
											?>
										</tbody>
									</table>
									<?php
								endif;
			            	?>
			            </td>
			        </tr>
				</table><!--/.form-table-->
		    	<?php
		    	$this->table_rate_js();
		    } // End admin_options()

			/**
			 * Terms Page Options 
			 * - Shipping rates for classes
			 *
			 * @since 1.3
			 */		    
		    public function term_options( $term ) {
		    	
		    	$rates = array_filter((array) get_woocommerce_term_meta($term->term_id, 'woocommerce_table_rates', true));
		    	$enabled = (get_woocommerce_term_meta( $term->term_id, 'woocommerce_table_shipping_enabled', true )) ? 1 : 0;
				?>
				<tr class="form-field woocommerce">
					<th scope="row" class="titledesc"><?php _e('Shipping Table Rates', 'wc_table_rate'); ?>:</th>
		            <td class="forminp" id="shipping_rates">
	            		<p class="enable_shipping_class_table_rates" style="margin-top:0;">
			            	<legend class="screen-reader-text"><span><?php _e('Enable/Disable', 'wc_table_rate'); ?></span></legend>
							<label for="woocommerce_table_shipping_enabled"><input class="checkbox" style="width: auto;" name="woocommerce_table_shipping_enabled_for_<?php echo $term->term_id; ?>" id="woocommerce_table_shipping_enabled" type="checkbox" value="1" <?php checked($enabled, 1); ?>> <?php _e('Enable Table Rates for this shipping class?', 'wc_table_rate'); ?></label>
						</p>
						<div class="shipping_class_table_rates" style="dislay:none;">
							<?php $this->shipping_rows( $rates ) ?>
						</div>
		            </td>
				</tr>
				<?php
				$this->table_rate_js();
		    }
		    
		    function shipping_rows( $rates ) {
		    	global $woocommerce;
		    	?>
		    	<table class="shippingrows widefat" cellspacing="0" style="position:relative;">
            		<thead>
            			<tr>
            				<th class="check-column"><input type="checkbox"></th>
            				<th class="country"><?php _e('Destination countries/states', 'wc_table_rate'); ?></th>
            				<th><?php _e('Postcode', 'wc_table_rate'); ?>&nbsp;<a class="tips" tip="<?php _e('(optional) Comma separated list of ZIPs/Postcodes. Accepts wildcards, e.g. P* will match a postcode of PE30.', 'wc_table_rate'); ?>">[?]</a></th>
            				<th><?php _e('Exclude Postcode', 'wc_table_rate'); ?>&nbsp;<a class="tips" tip="<?php _e('(optional) Comma separated list of ZIPs/Postcodes to EXCLUDE. Accepts wildcards, e.g. P* will match a postcode of PE30.', 'wc_table_rate'); ?>">[?]</a></th>
            				<th><?php _e('Condition', 'wc_table_rate'); ?>&nbsp;<a class="tips" tip="<?php _e('Condition vs. destination', 'wc_table_rate'); ?>">[?]</a></th>
            				<th><?php _e('Range', 'wc_table_rate'); ?>&nbsp;<a class="tips" tip="<?php _e('Bottom/top range for the selected condition.', 'wc_table_rate'); ?>">[?]</a></th>
            				<th><?php _e('Cost', 'wc_table_rate'); ?>&nbsp;<a class="tips" tip="<?php _e('Cost, excluding tax.', 'wc_table_rate'); ?>">[?]</a></th>
            				<th><?php _e('Label', 'wc_table_rate'); ?>&nbsp;<a class="tips" tip="<?php _e('Label for the shipping method which the user will be presented.', 'wc_table_rate'); ?>">[?]</a></th>
            				<th><?php _e('Priority', 'wc_table_rate'); ?>&nbsp;<a class="tips" tip="<?php _e('Enable this option to offer this rate and no others if matched. Priority is given to rates at the top of the list.', 'wc_table_rate'); ?>">[?]</a></th>
            			</tr>
            		</thead>
            		<tfoot>
            			<tr>
            				<th colspan="2"><a href="#" class="add button"><?php _e('+ Add Shipping Rate', 'wc_table_rate'); ?></a></th>
            				<th colspan="7"><small><?php _e('Note, if the user has multiple matching rates they will get the choice of which to use.', 'wc_table_rate'); ?></small> <a href="#" class="dupe button"><?php _e('Duplicate selected rows', 'wc_table_rate'); ?></a> <a href="#" class="remove button"><?php _e('Delete selected rows', 'wc_table_rate'); ?></a></th>
            			</tr>
            		</tfoot>
            		<tbody class="table_rates">
                	<?php
                	$i = -1; if ($rates) foreach( $rates as $rate ) : $i++;

                		echo '<tr class="table_rate">
                			<td class="check-column"><input type="checkbox" name="select" /></td>
                			<td class="country">
                				<p class="edit"><button class="edit_options button">'.__('Edit', 'wc_table_rate').'</button> <label>'.$this->row_label( $rate['countries'] ).'</label></p>
                				<div class="options" style="display:none">
                					<select name="shipping_countries['.$i.'][]" data-placeholder="'.__('Choose countries&hellip;', 'woothemes').'" class="table_rate_chosen_select select" size="8" multiple="multiple">';
                					
                		$woocommerce->countries->country_multiselect_options( $rate['countries'] );
		               	
		                echo '</select>
		                			<p><button class="select_all button">'.__('All', 'wc_table_rate').'</button><button class="select_none button">'.__('None', 'wc_table_rate').'</button><button class="button select_us_states">'.__('US States', 'wc_table_rate').'</button><button class="button select_europe">'.__('EU States', 'wc_table_rate').'</button></p>
		                		</div>
		               		</td>
		                    <td><input type="text" class="text" value="'.$rate['postcode'].'" name="shipping_postcode['.$i.']" placeholder="*" size="8" /></td>
		                    <td><input type="text" class="text" value="'.$rate['exclude_postcode'].'" name="shipping_exclude_postcode['.$i.']" placeholder="" size="8" /></td>
		                    <td><select class="select" name="shipping_condition['.$i.']" id="woocommerce_table_rate_condition" style="min-width:100px;">
					            <option value="price" '.selected( $rate['condition'], 'price', false ).'>'.__('Price', 'wc_table_rate').'</option>
					            <option value="weight" '.selected( $rate['condition'], 'weight', false ).'>'.__('Weight', 'wc_table_rate').'</option>
					            <option value="items" '.selected( $rate['condition'], 'items', false ).'>'.__('# of Items', 'wc_table_rate').'</option>
					        </select></td>
		                    <td style="white-space:nowrap;"><input type="text" class="text" value="'.$rate['min'].'" name="shipping_min['.$i.']" placeholder="'.__('n/a', 'wc_table_rate').'" size="4" />&mdash;<input type="text" class="text" value="'.$rate['max'].'" name="shipping_max['.$i.']" placeholder="'.__('n/a', 'wc_table_rate').'" size="4" /></td>
		                    <td>
		                    	<input type="text" class="text" value="'.$rate['cost'].'" name="shipping_cost['.$i.']" placeholder="'.__('0.00', 'wc_table_rate').'" size="4" />
		                    </td>
		                    <td><input type="text" class="text" value="'.$rate['label'].'" name="shipping_label['.$i.']" size="8" /></td>
		                    <td><input type="checkbox" class="checkbox" '.checked( $rate['priority'], 1, true ).' name="shipping_priority['.$i.']" /></td>
	                    </tr>';
                	endforeach;
                	?>
                	</tbody>		                    	
                </table>
                <?php
		    }
		    
		    function table_rate_js() {
		    	global $woocommerce;
		    	
		    	ob_start();
		    	
		    	?>
		    	jQuery('.enable_shipping_class_table_rates input').change(function(){
		    		
		    		if (jQuery(this).is(':checked')) {
		    			jQuery('.shipping_class_table_rates').show();
		    		} else {
		    			jQuery('.shipping_class_table_rates').hide();
		    		}
		    		
		    	}).change();
		    	
				jQuery('tr.table_rate .edit_options').live('click', function(){
					jQuery(this).closest('td').find('.options').slideToggle();
					if (jQuery(this).text()=='<?php _e('Edit', 'wc_table_rate'); ?>') {
						jQuery(this).closest('tr').find("select.table_rate_chosen_select").chosen();
						jQuery(this).text('<?php _e('Done', 'wc_table_rate'); ?>');
					} else {
						jQuery(this).text('<?php _e('Edit', 'wc_table_rate'); ?>');
					}
					return false;
				});
				
				jQuery('tr.table_rate .select_all').live('click', function(){
					jQuery(this).closest('td').find('select option').attr("selected","selected");
					jQuery(this).closest('td').find('select.table_rate_chosen_select').trigger("change");
					return false;
				});
				
				jQuery('tr.table_rate .select_none').live('click', function(){
					jQuery(this).closest('td').find('select option').removeAttr("selected");
					jQuery(this).closest('td').find('select.table_rate_chosen_select').trigger("change");
					return false;
				});

				jQuery('tr.table_rate .select_us_states').live('click', function(){
					jQuery(this).closest('td').find('select optgroup[label="United States"] option').attr("selected","selected");
					jQuery(this).closest('td').find('select.table_rate_chosen_select').trigger("change");
					return false;
				});
				
				jQuery('tr.table_rate .options select').live('change', function(){
					// Update label
					jQuery(this).trigger("liszt:updated");
					jQuery(this).closest('td').find('label').text( jQuery(":selected", this).length + '<?php _e(' countries/states selected', 'wc_table_rate') ?>' );
				});
				
				jQuery('tr.table_rate .select_europe').live('click', function(){
					jQuery(this).closest('td').find('option[value="AL"], option[value="AD"], option[value="AM"], option[value="AT"], option[value="BY"], option[value="BE"], option[value="BA"], option[value="BG"], option[value="CH"], option[value="CY"], option[value="CZ"], option[value="DE"], option[value="DK"], option[value="EE"], option[value="ES"], option[value="FO"], option[value="FI"], option[value="FR"], option[value="GB"], option[value="GE"], option[value="GI"], option[value="GR"], option[value="HU"], option[value="HR"], option[value="IE"], option[value="IS"], option[value="IT"], option[value="LT"], option[value="LU"], option[value="LV"], option[value="MC"], option[value="MK"], option[value="MT"], option[value="NO"], option[value="NL"], option[value="PO"], option[value="PT"], option[value="RO"], option[value="RU"], option[value="SE"], option[value="SI"], option[value="SK"], option[value="SM"], option[value="TR"], option[value="UA"], option[value="VA"]').attr("selected","selected");
					jQuery(this).closest('td').find('select.table_rate_chosen_select').trigger("change");
					return false;
				});
			
				jQuery('#shipping_rates a.add').live('click', function(){
					
					var size = jQuery('tbody.table_rates .table_rate').size();
					
					jQuery('<tr class="new_rate table_rate">\
						<td class="check-column"><input type="checkbox" name="select" /></td>\
            			<td class="country">\
            				<p class="edit"><button class="edit_options button"><?php _e('Edit', 'wc_table_rate'); ?></button> <label><?php _e('No countries selected', 'wc_table_rate'); ?></label></p>\
            				<div class="options" style="display:none">\
            					<select name="shipping_countries[' + size + '][]" data-placeholder="<?php _e('Choose countries&hellip;', 'woothemes'); ?>" class="table_rate_chosen_select select" size="10" multiple="multiple">\
	               					<?php $woocommerce->countries->country_multiselect_options('',true); ?>\
	               				</select>\
	                			<p><button class="select_all button"><?php _e('All', 'wc_table_rate'); ?></button><button class="select_none button"><?php _e('None', 'wc_table_rate'); ?></button><button class="button select_us_states"><?php _e('US States', 'wc_table_rate'); ?></button><button class="button select_europe"><?php _e('EU States', 'wc_table_rate'); ?></button></p>\
	                		</div>\
	               		</td>\
	                    <td><input type="text" class="text" name="shipping_postcode[' + size + ']" placeholder="*" size="10" /></td>\
	                    <td><input type="text" class="text" name="shipping_exclude_postcode[' + size + ']" placeholder="" size="10" /></td>\
	                    <td><select class="select" name="shipping_condition[' + size + ']" id="woocommerce_table_rate_condition" style="min-width:100px;">\
				            <option value="price"><?php _e('Price', 'wc_table_rate'); ?></option>\
				            <option value="weight"><?php _e('Weight', 'wc_table_rate'); ?></option>\
				            <option value="items"><?php _e('# of Items', 'wc_table_rate'); ?></option>\
				        </select></td>\
	                    <td style="white-space:nowrap;"><input type="text" class="text" name="shipping_min[' + size + ']" placeholder="<?php _e('n/a', 'wc_table_rate'); ?>" size="4" />&mdash;<input type="text" class="text" name="shipping_max[' + size + ']" placeholder="<?php _e('n/a', 'wc_table_rate'); ?>" size="4" /></td>\
	                    <td><input type="text" class="text" name="shipping_cost[' + size + ']" placeholder="<?php _e('0.00', 'wc_table_rate'); ?>" size="4" /></td>\
	                    <td><input type="text" class="text" name="shipping_label[' + size + ']" size="8" /></td>\
	                    <td><input type="checkbox" class="checkbox" name="shipping_priority[' + size + ']" /></td>\
                    </tr>').appendTo('#shipping_rates table tbody.table_rates');
					
					jQuery(".new_rate select.table_rate_chosen_select").chosen();
					jQuery(".new_rate").removeClass('new_rate');
						
					return false;
				});
				
				// Remove row
				jQuery('#shipping_rates a.remove').live('click', function(){
					var answer = confirm("<?php _e('Delete the selected rates?', 'wc_table_rate'); ?>")
					if (answer) {
						jQuery('#shipping_rates table tbody.table_rates tr td.check-column input:checked').each(function(i, el){
							jQuery(el).closest('tr').remove();
						});
					}
					return false;
				});
				
				// Dupe row
				jQuery('#shipping_rates a.dupe').live('click', function(){
					var answer = confirm("<?php _e('Duplicate the selected rates?', 'wc_table_rate'); ?>")
					if (answer) {
						jQuery('#shipping_rates table tbody.table_rates tr td.check-column input:checked').each(function(i, el){
							var dupe = jQuery(el).closest('tr').clone();
							
							// Append
							jQuery('#shipping_rates table tbody.table_rates').append( dupe );
						});
						
						// Re-index keys
						var loop = 0;
						jQuery('tbody.table_rates .table_rate').each(function( index, row ){
							jQuery('input, select', row).each(function( i, el ){
								
								var t = jQuery(el);
								t.attr('name', t.attr('name').replace(/\[([^[]*)\]/, "[" + loop + "]"));
								
							});
							loop++;
						});
					}
					return false;
				});
				
				// Rate ordering
				jQuery('#shipping_rates tbody.table_rates').sortable({
					items:'tr',
					cursor:'move',
					axis:'y',
					handle: 'td',
					scrollSensitivity:40,
					helper:function(e,ui){
						ui.children().each(function(){
							jQuery(this).width(jQuery(this).width());
						});
						ui.css('left', '0');
						return ui;
					},
					start:function(event,ui){
						ui.item.css('background-color','#f6f6f6');
					},
					stop:function(event,ui){
						ui.item.removeAttr('style');
						shipping_rates_row_indexes();
					}
				});
				
				function shipping_rates_row_indexes() {
					// Re-index keys
					var loop = 0;
					jQuery('#shipping_rates tr.table_rate').each(function( index, row ){
						jQuery('input.text, input.checkbox, select.select', row).each(function( i, el ){
							
							var t = jQuery(el);
							t.attr('name', t.attr('name').replace(/\[([^[]*)\]/, "[" + loop + "]"));
							
						});
						loop++;
					});
				};
				<?php
		    	
		    	$js = ob_get_clean();
		    	
		    	$woocommerce->add_inline_js( $js );
		    }
       
		    function is_available() {
		    	global $woocommerce;
		    	
		    	if ($this->enabled=="no") return false;
		    			    	
		    	// Table rate
		    	$matched_rates = array();
		    	$available_rates = array();
		
				// Get customer location
				$customer_country = $woocommerce->customer->get_shipping_country();
				$customer_state = $woocommerce->customer->get_shipping_state();
				$customer_postcode = $woocommerce->customer->get_shipping_postcode();
				
				$customer_postcode_length = strlen($customer_postcode);

				// Find shipping class for cart
				$shipping_class = '';
				$found_shipping_classes = array();
	    		
	    		// Find shipping classes for products in the cart
	    		if (sizeof($woocommerce->cart->get_cart())>0) : 
	    			foreach ($woocommerce->cart->get_cart() as $item_id => $values) : 
	    				if ( $values['data']->needs_shipping() ) :
	    					$found_shipping_classes[] = $values['data']->get_shipping_class(); 
	    				endif;
	    			endforeach; 
	    		endif;
	
	    		$found_shipping_classes = array_unique($found_shipping_classes);
				
				if (sizeof($found_shipping_classes)==1) :
					$shipping_class = current($found_shipping_classes);
				elseif ($found_shipping_classes>1) :
					
					// Get class with highest priority
					$default_priority = get_option('woocommerce_table_rate_default_priority');
					
					$priority = $default_priority;
					$priorities = get_option('woocommerce_table_rate_priorities');
					
					foreach ($found_shipping_classes as $class) :
						if (isset($priorities[$class]) && $priorities[$class]<$priority) :
							$priority = $priorities[$class];
							$shipping_class = $class;
						endif; 
					endforeach;
					
				endif;
				
				// Count items in the cart with the chosen shipping class
				//   and
				// Get total price of items that need shipping
				//   and
				// Get weight of items that need shipping
				$cart_item_count = 0;
				$cart_item_weight = 0;
				$cart_item_price = 0;
				
				if (sizeof($woocommerce->cart->get_cart())>0) {
	    			foreach ($woocommerce->cart->get_cart() as $item_id => $values) { 
	    				if ( $values['data']->needs_shipping() ) {
	    					
	    					if ( $shipping_class == $values['data']->get_shipping_class() ) {
	    						$cart_item_count += $values['quantity'];
	    					}
	    					
	    					$cart_item_price += $values['data']->get_price() * $values['quantity'];
	    					$cart_item_weight += $values['data']->get_weight() * $values['quantity'];
	    					
	    				}
	    			}
	    		}
				
				// Now get the rates table
				$rates = null;
				
				if ($shipping_class) :
					
					// Get the term
					$term = get_term_by('slug', $shipping_class, 'product_shipping_class');
					
					if ($term) :
					
						// Is it enabled?
						$enabled = (get_woocommerce_term_meta( $term->term_id, 'woocommerce_table_shipping_enabled', true )) ? 1 : 0;
						
						if ($enabled) :
						
							$rates = array_filter((array) get_woocommerce_term_meta($term->term_id, 'woocommerce_table_rates', true));
						
						endif;
					
					endif;
				endif;
				
				if (!$rates) $rates = $this->table_rates;
	
				// Find matching table rates to the customers country
				foreach ($rates as $rate) :
					
					if (!isset($rate['countries']) || !is_array($rate['countries'])) continue;
					
					// Country check - if its not in the array then this rate is not applicable
					if (!$customer_country) continue;
					if (!array_key_exists($customer_country, $rate['countries'])) continue;
										
					// State check
					if (!$customer_state) $customer_state = '*';
					
					if (
						FALSE === array_search( $customer_state, $rate['countries'][$customer_country] ) && 
						FALSE === array_search( '*', $rate['countries'][$customer_country] )
						) continue;				
										
					// Postcodes
					$check_postcode = $customer_postcode;
				
					if (isset( $rate['postcode'] ) && $rate['postcode']) :
					
						// Explode and trim - postcodes can be comma separated
						$postcodes = explode(',', $rate['postcode']);
						$postcodes = array_map('trim', $postcodes);
						$postcodes = array_map('strtoupper', $postcodes);
						
						// Check matches
						$match = false;
						for ($i=0; $i<=$customer_postcode_length; $i++) :
							
							if (in_array($check_postcode, $postcodes)) $match = true;
							
							$check_postcode = substr( $check_postcode, 0, -2 ).'*';
											
						endfor;
						if (!$match) continue;
					endif;
					
					// Excluded Postcodes
					$check_postcode = $customer_postcode;
					
					if (isset( $rate['exclude_postcode'] ) && $rate['exclude_postcode']) :
					
						// Explode and trim - postcodes can be comma separated
						$postcodes = explode(',', $rate['exclude_postcode']);
						$postcodes = array_map('trim', $postcodes);
						$postcodes = array_map('strtoupper', $postcodes);
						
						// Check matches
						$match = false;
						for ($i=0; $i<=$customer_postcode_length; $i++) :
							
							if (in_array($check_postcode, $postcodes)) $match = true;

							$check_postcode = substr( $check_postcode, 0, -2 ).'*';
											
						endfor;
						if ($match) continue; // If we found a match then the postcode is excluded - move on
					endif;
					
					// Its a match...so far
					$matched_rates[] = $rate; 
					
				endforeach;

				// None found?
				if (sizeof($matched_rates)==0) return false;
				
				// Go through matched rates and find out costs
				foreach ($matched_rates as $rate) :
					$matched = false;
					switch ($rate['condition']) :
						case "price" :
								
							if (empty($rate['min']) && empty($rate['max'])) :
								$matched 			= true;
							elseif (!empty($rate['min']) && !empty($rate['max']) && $cart_item_price >= $rate['min'] && $cart_item_price <= $rate['max']) :
								$matched 			= true;
							elseif (empty($rate['max']) && !empty($rate['min']) && $cart_item_price >= $rate['min']) :
								$matched 			= true;
							elseif (empty($rate['min']) && !empty($rate['max']) && $cart_item_price <= $rate['max']) :
								$matched 			= true;
							endif;
								
						break;
						case "weight" :
							
							if (empty($rate['min']) && empty($rate['max'])) :
								$matched 			= true;
							elseif (!empty($rate['min']) && !empty($rate['max']) && $cart_item_weight >= $rate['min'] && $cart_item_weight <= $rate['max']) :
								$matched 			= true;
							elseif (empty($rate['max']) && !empty($rate['min']) && $cart_item_weight >= $rate['min']) :
								$matched 			= true;
							elseif (empty($rate['min']) && !empty($rate['max']) && $cart_item_weight <= $rate['max']) :
								$matched 			= true;
							endif;
							
						break;
						case "items" :
								
							if (empty($rate['min']) && empty($rate['max'])) :
								$matched 			= true;
							elseif (!empty($rate['min']) && !empty($rate['max']) && $cart_item_count >= $rate['min'] && $cart_item_count <= $rate['max']) :
								$matched 			= true;
							elseif (empty($rate['max']) && !empty($rate['min']) && $cart_item_count >= $rate['min']) :
								$matched 			= true;
							elseif (empty($rate['min']) && !empty($rate['max']) && $cart_item_count <= $rate['max']) :
								$matched 			= true;
							endif;
							
						break;
					endswitch;
					
					if ($matched) :
						// single rate?
						if (isset($rate['priority']) && $rate['priority']) :
							$available_rates = array();
							$available_rates[] = $rate; 
							break;
						else :
							$available_rates[] = $rate; 
						endif;
					endif;
									
				endforeach;
				
				// None found
				if (sizeof($available_rates)==0) return false;
				
				// Set available
				$this->available_rates = $available_rates;

				return true;
		    } 
		    
		    function calculate_shipping() {
		    	global $woocommerce;
		    	
				foreach ($this->available_rates as $rate) :
					
					// Default label if not set
					if (!$rate['label']) $rate['label'] = __('Shipping', 'wc_table_rate');
					
					$this->add_rate(array(
						'id' 	=> $rate['id'],
						'label' => $rate['label'],
						'cost' 	=> $this->get_fee( $this->fee, $woocommerce->cart->cart_contents_total ) + $rate['cost']
					));  
					
				endforeach;
				
		    } 
		    		    
		    function get_shipping_rates() {
		    	$this->table_rates = array_filter((array) get_option('woocommerce_table_rates'));
			}
			
			/* Show a label depending on selections */
			function row_label( $selected ) {
				global $woocommerce;
				
				$return = '';
				
				// Get counts/countries
				$counties_array = array();
				$states_count = 0;
				
				if ($selected) foreach ($selected as $country => $value) :
					
					$country = woocommerce_clean($country);

					if (sizeof($value)>1) :
						$states_count+=sizeof($value);
					endif;
					
					if (!in_array($country, $counties_array)) $counties_array[] = $woocommerce->countries->countries[$country];
					
				endforeach;
				
				$states_text = '';
				$countries_text = implode(', ', $counties_array);

				// Show label
				if (sizeof($counties_array)==0) :
				
					$return .= __('No countries selected', 'wc_table_rate');
					
				elseif ( sizeof($counties_array) < 6 ) :
				
					if ($states_count>0) $states_text = sprintf(_n('(1 state)', '(%s states)', $states_count, 'wc_table_rate'), $states_count);

					$return .= sprintf(__('%s', 'wc_table_rate'), $countries_text) . ' ' . $states_text;
					
				else :
					
					if ($states_count>0) $states_text = sprintf(_n('and 1 state', 'and %s states', $states_count, 'wc_table_rate'), $states_count);
					
					$return .= sprintf(_n('1 country', '%1$s countries', sizeof($counties_array), 'wc_table_rate'), sizeof($counties_array)) . ' ' . $states_text;
				
				endif;
				
				return $return;
			}
		    
		    function process_class_table_rates( $term_id, $tt_id, $taxonomy ) {
		    	global $post_type;
		    	$post_type = 'product';
		    	$term = get_term_by( 'id', $term_id, $taxonomy );
		    	$this->process_table_rates( $term );
		    }
		    
		    function process_table_rates( $class = '' ) {
		   		
		   		if ($class) :
		   			// Save enabled/disabled status
		   			$enabled = (isset($_POST['woocommerce_table_shipping_enabled_for_' . $class->term_id])) ? 1 : 0;
		   			update_woocommerce_term_meta($class->term_id, 'woocommerce_table_shipping_enabled', $enabled);
		   		else :
		   			// Save class priorities
		   			if (isset($_POST['woocommerce_table_rate_priorities'])) :
		   				$priorities = array_map('intval', (array) $_POST['woocommerce_table_rate_priorities']);
		   				update_option('woocommerce_table_rate_priorities', $priorities);
		   			endif;
		   			if (isset($_POST['woocommerce_table_rate_default_priority'])) :
		   				update_option('woocommerce_table_rate_default_priority', (int) esc_attr($_POST['woocommerce_table_rate_default_priority']));
		   			endif;
		   		endif;
		   		
		   		if ( empty( $_POST['shipping_countries'] ) ) return;
		   		
				// Save the rates
        		$shipping_countries = array();
				$shipping_postcode = array();
				$shipping_exclude_postcode = array();
				$shipping_condition = array();
				$shipping_min = array();
				$shipping_max = array();
				$shipping_cost = array();
				$shipping_label = array();
				$table_rates = array();
				$shipping_priority = array();
        		
				if (isset($_POST['shipping_countries']))$shipping_countries = $_POST['shipping_countries'];
				if (isset($_POST['shipping_postcode'])) $shipping_postcode 	= array_map('strtoupper', array_map('woocommerce_clean', $_POST['shipping_postcode']));
				if (isset($_POST['shipping_exclude_postcode'])) $shipping_exclude_postcode 	= array_map('strtoupper', array_map('woocommerce_clean', $_POST['shipping_exclude_postcode']));
				if (isset($_POST['shipping_condition']))$shipping_condition = array_map('woocommerce_clean', $_POST['shipping_condition']);
				if (isset($_POST['shipping_min'])) 		$shipping_min 		= array_map('woocommerce_clean', $_POST['shipping_min']);
				if (isset($_POST['shipping_max'])) 		$shipping_max 		= array_map('woocommerce_clean', $_POST['shipping_max']);
				if (isset($_POST['shipping_cost'])) 	$shipping_cost 		= array_map('woocommerce_clean', $_POST['shipping_cost']);
				if (isset($_POST['shipping_label'])) 	$shipping_label 	= array_map('woocommerce_clean', $_POST['shipping_label']);
				if (isset($_POST['shipping_priority'])) 	$shipping_priority 	= array_map('woocommerce_clean', $_POST['shipping_priority']);
				
				// Get max key
				$max_key = max( array_keys( $shipping_label ) );  
				
				for ($i=0; $i<=$max_key; $i++) :
				
					if (isset($shipping_countries[$i]) && isset($shipping_min[$i]) && isset($shipping_max[$i]) && isset($shipping_cost[$i]) && isset($shipping_label[$i])) :
						
						$shipping_cost[$i] = number_format($shipping_cost[$i], 2,  '.', '');
						
						// Handle countries
						$counties_array = array();
						$countries = $shipping_countries[$i];
						
						if ($countries) foreach ($countries as $country) :
							
							$country = woocommerce_clean($country);
							$state = '*';
							
							if (strstr($country, ':')) :
								$cr = explode(':', $country);
								$country = current($cr);
								$state = end($cr);
							endif;
						
							$counties_array[trim($country)][] = trim($state);
							
						endforeach;
						
						// Format min and max
						$condition = $shipping_condition[$i];
						switch ($condition) :
							case 'weight' :
							case 'price' :
								if ($shipping_min[$i]) $shipping_min[$i] = number_format($shipping_min[$i], 2, '.', '');
								if ($shipping_max[$i]) $shipping_max[$i] = number_format($shipping_max[$i], 2, '.', '');
							break;
							default :
								if ($shipping_min[$i]) $shipping_min[$i] = round($shipping_min[$i]);
								if ($shipping_max[$i]) $shipping_max[$i] = round($shipping_max[$i]);
							break;
						endswitch;
						
						// ID
						if ($class) :
							$rate_id = $this->id.':'.$class->slug.':'.$i.'-'.$shipping_label[$i];
						else :
							$rate_id = $this->id.':'.$i.'-'.$shipping_label[$i];
						endif;
						
						// Single rate
						if (isset($shipping_priority[$i])) $priority = 1; else $priority = 0;
						
						// Add to table rates array
						$table_rates[] = array(
							'id'				=> $rate_id,
							'countries' 		=> $counties_array,
							'condition'			=> $shipping_condition[$i],
							'postcode' 			=> $shipping_postcode[$i],
							'exclude_postcode'	=> $shipping_exclude_postcode[$i],
							'min' 				=> $shipping_min[$i],
							'max' 				=> $shipping_max[$i],
							'cost' 				=> $shipping_cost[$i],
							'label' 			=> $shipping_label[$i],
							'priority'			=> $priority
						);  
						
					endif;

				endfor;
				
				if ($class) :
					update_woocommerce_term_meta($class->term_id, 'woocommerce_table_rates', $table_rates);
				else :
					update_option('woocommerce_table_rates', $table_rates);
				endif;
		    }
			
		}
		
		// Init class on tags pages so we can show our options
		add_action( 'load-edit-tags.php' , 'woocommerce_load_table_rates' );	
		
		function woocommerce_load_table_rates() {
			$WC_Shipping_Table_Rate = new WC_Shipping_Table_Rate();
		}
		
	}
	add_action('woocommerce_shipping_init', 'woocommerce_table_rate_shipping_init');
	
	function woocommerce_table_rate_shipping( $methods ) {
		$methods[] = 'WC_Shipping_Table_Rate'; return $methods;
	}
	add_filter('woocommerce_shipping_methods', 'woocommerce_table_rate_shipping' );
}