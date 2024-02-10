<?php
/*
 * Plugin Name: Custom Length Woocommerce Products
 * Plugin URI: https://github.com/Deejpotter/custom-length-woocommerce-listings
 * Description: Create custom length products in WooCommerce and track the leftover stock.
 * Version: 0.0.1
 * Author: Deejpotter
 * Author URI: https://deejpotter.com
 * Text Domain: custom-length-woocommerce-products
*/

if (!defined('ABSPATH')) exit;

// #1 Add New Product Type to Select Dropdown
add_filter('product_type_selector', 'add_custom_product_type');

function add_custom_product_type($types)
{
    // Add a new product type option in the admin product type selector
    $types['custom'] = __('Custom product', 'custom-length-woocommerce-products');
    return $types;
}

// #2 Add New Product Type Class
add_action('init', 'create_custom_product_type');

function create_custom_product_type()
{
    // Define a new class for our custom product type that extends WC_Product_Simple
    class WC_Product_Custom extends WC_Product_Simple
    {
        public function get_type()
        {
            return 'custom';
        }
    }
}

// #3 Load New Product Type Class
add_filter('woocommerce_product_class', 'load_custom_product_class', 10, 2);

function load_custom_product_class($classname, $product_type)
{
    // Load the new product type class when 'custom' product type is set
    if ($product_type === 'custom') {
        $classname = 'WC_Product_Custom';
    }
    return $classname;
}

// #4 Add Custom Length Field to Product Page
add_action('woocommerce_before_add_to_cart_button', 'add_custom_length_field');

function add_custom_length_field()
{
    global $product;
    // Only add this field for products of the 'custom' type
    if ('custom' === $product->get_type()) {
        echo '<div class="custom-length-field">
                <label for="custom_length">' . __('Custom Length (mm)', 'custom-length-woocommerce-products') . '</label>
                <input type="number" id="custom_length" name="custom_length" min="1" step="1">
              </div>';
    }
}

// #5 Save Custom Length Data
add_filter('woocommerce_add_cart_item_data', 'save_custom_length_field', 10, 2);

function save_custom_length_field($cart_item_data, $product_id)
{
    if (isset($_POST['custom_length'])) {
        // Save custom length data into cart item data
        $cart_item_data['custom_length'] = sanitize_text_field($_POST['custom_length']);
        // When adding items to the cart, ensure a unique line item is created for each custom length
        $cart_item_data['unique_key'] = md5(microtime() . rand());
    }
    return $cart_item_data;
}

// #6 Display Custom Length in Cart and Checkout
add_filter('woocommerce_get_item_data', 'display_custom_length_cart', 10, 2);

function display_custom_length_cart($item_data, $cart_item)
{
    if (array_key_exists('custom_length', $cart_item)) {
        // Display the custom length in cart and checkout pages
        $item_data[] = array(
            'name' => __('Custom Length', 'custom-length-woocommerce-products'),
            'value' => $cart_item['custom_length'] . __(' mm', 'custom-length-woocommerce-products')
        );
    }
    return $item_data;
}

// #7 Adjust Stock Management for Custom Length - This is a placeholder function
// You would need to implement actual stock management logic based on your needs.
add_action('woocommerce_reduce_order_stock', 'adjust_stock_for_custom_length');

function adjust_stock_for_custom_length($order_id)
{
    // Adjust stock based on the custom length ordered
    // This function needs to be filled out with your specific stock management logic
}

// #8 Modify Price Based on Custom Length - This is a placeholder function
// You would need to implement actual pricing logic based on your needs.
add_action('woocommerce_before_calculate_totals', 'calculate_price_based_on_length');

function calculate_price_based_on_length($cart)
{
    // Loop through cart items and adjust price if custom length is set
    foreach ($cart->get_cart() as $cart_item_key => $cart_item) {
        if (isset($cart_item['custom_length'])) {
            // Implement price calculation logic based on custom length
            // $cart_item['data']->set_price($new_price);
        }
    }
}

// Note: You'll need to add additional hooks and functions to handle product stock and pricing updates.
// The placeholders provided for stock management and price calculation should be replaced with your specific logic.
