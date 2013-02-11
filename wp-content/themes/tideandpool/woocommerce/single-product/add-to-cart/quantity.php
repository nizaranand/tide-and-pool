<?php
/**
 * Single product quantity inputs
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */
?>
<!--
<div class="select-wrapper">
	<select>
		<option class="input-text qty text" value="1" name="quantity">Qty: 1</option>
		<option class="input-text qty text" value="2" name="quantity">Qty: 2</option>
		<option class="input-text qty text" value="3" name="quantity">Qty: 3</option>
		<option class="input-text qty text" value="4" name="quantity">Qty: 4</option>
		<option class="input-text qty text" value="5" name="quantity">Qty: 5</option>
		<option class="input-text qty text" value="6" name="quantity">Qty: 6</option>
	</select>
</div>
-->
<div class="quanity-input"><input name="<?php echo $input_name; ?>" data-min="1" data-max="5" placeholder="<?php echo $input_value; ?>" value="1" size="4" title="Qty" class="input-text qty text" maxlength="12" /></div>