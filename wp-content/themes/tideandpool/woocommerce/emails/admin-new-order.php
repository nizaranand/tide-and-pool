<?php
/**
 * Admin new order email
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates/Emails
 * @version     1.6.4
 */

if (!defined('ABSPATH')) exit; ?>

<?php do_action('woocommerce_email_header', $email_heading); ?>

<div style="margin:0 30px;color#666;">
	<?php echo __('You have received an order from', 'woocommerce') . ' ' . $order->billing_first_name . ' ' . $order->billing_last_name . __(". Their order is as follows:", 'woocommerce'); ?></p>
</div>

<?php do_action('woocommerce_email_before_order_table', $order, true); ?>


<table cellspacing="0" cellpadding="0" border="0" width="653" style="color:#666;padding:30px;">
	<tbody>
		<tr>
			<td><strong>ORDER DATE:</strong></td>
			<td align="right"><?php echo __('<strong>ORDER #:</strong>', 'woocommerce') . ' ' . $order->get_order_number(); ?></td>
		</tr>
		<tr>
			<td colspan="2">Date</td>
		</tr>
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<td><strong>BILL TO:</strong></td>
			<td align="right"><strong>SHIP TO:</strong></td>
		</tr>
		<tr>
			<td valign="top" width="50%"><?php echo $order->get_formatted_billing_address(); ?></td>
			<td align="right" valign="top" width="50%"><?php echo $order->get_formatted_shipping_address(); ?></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td colspan="2" align="right"><strong>UPS SHIPPING METHOD</strong><br>[Shipping method here]</td>
		</tr>
		<tr>
        	<td style="height:50px;">&nbsp;</td>
        </tr>
		<tr>
			<td colspan="2">
				<table width="653" border="0" cellpadding="5" cellspacing="0" style="color:#666;">
					<tr>
						<th scope="col" align="left" style="height:36px;line-height:36px;border-top:1px solid #eee;border-bottom:1px solid #eee;"><?php _e('ITEM #', 'woocommerce'); ?></th>
						<th scope="col" align="left" style="height:36px;line-height:36px;border-top:1px solid #eee;border-bottom:1px solid #eee;"><?php _e('ITEM NAME', 'woocommerce'); ?></th>
						<th scope="col" align="left" style="height:36px;line-height:36px;border-top:1px solid #eee;border-bottom:1px solid #eee;"><?php _e('QUANTITY', 'woocommerce'); ?></th>
						<th scope="col" align="left" style="height:36px;line-height:36px;border-top:1px solid #eee;border-bottom:1px solid #eee;"><?php _e('PRICE', 'woocommerce'); ?></th>
						<th scope="col" align="right" style="height:36px;line-height:36px;border-top:1px solid #eee;border-bottom:1px solid #eee;"><?php _e('TOTAL', 'woocommerce'); ?></th>
					</tr>
					
					<?php echo $order->email_order_items_table( (get_option('woocommerce_downloads_grant_access_after_payment')=='yes' && $order->status=='processing') ? true : false, true, ($order->status=='processing') ? true : false ); ?>
					<!--
<tr>
						<td>ITEM #</td>
						<td>ITEM NAME</td>
						<td>QUANITY</td>
						<td>PRICE</td>
						<td>TOTAL</td>
					</tr>
					<tr>
						<td>[item no]</td>
						<td>[item name]</td>
						<td>[quanity]</td>
						<td>[price]</td>
						<td>[total]</td>
					</tr>
					<tr>
						<td colspan="3">&nbsp;</td>
						<td colspan="1">SUB-TOTAL:</td>
						<td colspan="1">[price here]</td>
					</tr>
-->	
					<tr>
						<td>&nbsp;</td>
					</tr>
					<tfoot>
						<?php
			if ( $totals = $order->get_order_item_totals() ) {
				$i = 0;
				foreach ( $totals as $total ) {
					$i++;
					?>
					<tr>
						<th scope="row" colspan="4" align="right"><?php echo $total['label']; ?></th>
						<td  align="right"><?php echo $total['value']; ?></td>
					</tr><?php
				}
			}
		?>
					</tfoot>
				</table>
			</td>
		</tr>
	</tbody>
	<!--
<thead>
		<tr>
			<th scope="col" style="text-align:left; border: 1px solid #eee;"><?php _e('Product', 'woocommerce'); ?></th>
			<th scope="col" style="text-align:left; border: 1px solid #eee;"><?php _e('Quantity', 'woocommerce'); ?></th>
			<th scope="col" style="text-align:left; border: 1px solid #eee;"><?php _e('Price', 'woocommerce'); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php echo $order->email_order_items_table( (get_option('woocommerce_downloads_grant_access_after_payment')=='yes' && $order->status=='processing') ? true : false, true, ($order->status=='processing') ? true : false ); ?>
	</tbody>
	<tfoot>
		<?php
			if ( $totals = $order->get_order_item_totals() ) {
				$i = 0;
				foreach ( $totals as $total ) {
					$i++;
					?><tr>
						<th scope="row" colspan="2" style="text-align:left; border: 1px solid #eee; <?php if ( $i == 1 ) echo 'border-top-width: 4px;'; ?>"><?php echo $total['label']; ?></th>
						<td style="text-align:left; border: 1px solid #eee; <?php if ( $i == 1 ) echo 'border-top-width: 4px;'; ?>"><?php echo $total['value']; ?></td>
					</tr><?php
				}
			}
		?>
	</tfoot>
-->
</table>

<?php do_action('woocommerce_email_after_order_table', $order, true); ?>

<!--
<h2><?php _e('Customer details', 'woocommerce'); ?></h2>

<?php if ($order->billing_email) : ?>
	<p><strong><?php _e('Email:', 'woocommerce'); ?></strong> <?php echo $order->billing_email; ?></p>
<?php endif; ?>
<?php if ($order->billing_phone) : ?>
	<p><strong><?php _e('Tel:', 'woocommerce'); ?></strong> <?php echo $order->billing_phone; ?></p>

<?php endif; ?>-->

<?php woocommerce_get_template('emails/email-addresses.php', array( 'order' => $order )); ?>

<?php do_action('woocommerce_email_footer'); ?>