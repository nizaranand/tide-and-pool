<?php

add_action('wp_ajax_nopriv_get_updated_price', 'woocommerce_gravityforms_get_updated_price');
add_action('wp_ajax_get_updated_price', 'woocommerce_gravityforms_get_updated_price');

function woocommerce_gravityforms_get_updated_price() {
    global $woocommerce;
    header('Cache-Control: no-cache, must-revalidate');
    header('Content-type: application/json');

    $variation_id = isset($_POST['variation_id']) ? $_POST['variation_id'] : '';
    $product_id = isset($_POST['product_id']) ? $_POST['product_id'] : 0;
    $gform_total = isset($_POST['gform_total']) ? $_POST['gform_total'] : 0;
    
    $product_data = null;
    if (function_exists('get_product')) {
        $product_data = get_product( $variation_id > 0 ? $variation_id : $product_id);
    } else {
        if ($variation_id > 0) :
            $product_data = &new WC_Product_Variation($variation_id);
        else :
            $product_data = &new WC_Product($product_id);
        endif;
    }

    $result = array(
        'formattedBasePrice' => apply_filters('woocommerce_gform_base_price', woocommerce_price($product_data->get_price()), $product_data),
        'formattedTotalPrice' => apply_filters('woocommerce_gform_total_price', woocommerce_price($product_data->get_price() + $gform_total), $product_data),
        'formattedVariationTotal' => apply_filters('woocommerce_gform_variation_total_price', woocommerce_price($gform_total), $product_data)
    );

    echo json_encode($result);
    die();
}

?>