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

/**
 * Adds the "Custom Length" product type to the selector dropdown in WooCommerce admin.
 *
 * @param array $types Existing product types.
 * @return array Modified product types array.
 */
function add_custom_product_type($types)
{
    $types['customLength'] = __('Custom Length', 'custom-length-woocommerce-products');
    return $types;
}

// #2 Add New Product Type Class
add_action('init', 'create_custom_product_type');

/**
 * Defines the custom product class which extends WC_Product_Simple.
 */
function create_custom_product_type()
{
    class WC_Product_Custom_Length extends WC_Product_Simple
    {
        /**
         * Returns the product type.
         *
         * @return string  The product type ('customLength')
         */
        public function get_type()
        {
            return 'customLength';
        }
    }
}

// #3 Load New Product Type Class
add_filter('woocommerce_product_class', 'load_custom_product_class', 10, 2);

/**
 * Loads the custom product class when the 'customLength' product type is used.
 *
 * @param string $classname The existing product type class name.
 * @param string $product_type The selected product type.
 * @return string The modified class name ('WC_Product_Custom_Length' if applicable).
 */
function load_custom_product_class($classname, $product_type)
{
    if ($product_type === 'customLength') {
        $classname = 'WC_Product_Custom_Length';
    }
    return $classname;
}

// #4 Add Custom Length Field to Product Page
add_action('woocommerce_before_add_to_cart_button', 'add_custom_length_field');

/**
 * Displays the custom length field on the product page for 'Custom Length' products.
 */
function add_custom_length_field()
{
    global $product;
    if ('customLength' === $product->get_type()) {
        echo '<div class="custom-length-field">
                <label for="custom_length">' . __('Custom Length (mm)', 'custom-length-woocommerce-products') . '</label>
                <input type="number" id="custom_length" name="custom_length" min="1" step="1">
              </div>';
    }
}

// #5 Save Custom Length Data
add_filter('woocommerce_add_cart_item_data', 'save_custom_length_field', 10, 2);

/**
 * Saves custom length data into the cart item data, ensuring unique items for 
 * different lengths.
 *
 * @param array $cart_item_data Cart item data
 * @param int $product_id  ID of the product.
 * @return array Modified cart item data.
 */
function save_custom_length_field($cart_item_data, $product_id)
{
    if (isset($_POST['custom_length'])) {
        $cart_item_data['custom_length'] = sanitize_text_field($_POST['custom_length']);
        $cart_item_data['unique_key'] = md5(microtime() . rand());
    }
    return $cart_item_data;
}

// #6 Display Custom Length in Cart and Checkout
add_filter('woocommerce_get_item_data', 'display_custom_length_cart', 10, 2);

/**
 * Displays the custom length in the cart and checkout pages.
 *
 * @param array $item_data Existing item data.
 * @param array $cart_item Cart item array.
 * @return array Modified item data.
 */
function display_custom_length_cart($item_data, $cart_item)
{
    if (array_key_exists('custom_length', $cart_item)) {
        $item_data[] = array(
            'name' => __('Custom Length', 'custom-length-woocommerce-products'),
            'value' => $cart_item['custom_length'] . __(' mm', 'custom-length-woocommerce-products')
        );
    }
    return $item_data;
}

// #7 Adjust Stock Management for Custom Length 
add_action('woocommerce_reduce_order_stock', 'adjust_stock_for_custom_length');

/**
 * Adjusts the product stock based on the ordered custom length.
 *
 * @param int $order_id ID of the placed order.
 */
function adjust_stock_for_custom_length($order_id)
{
    $order = wc_get_order($order_id);
    foreach ($order->get_items() as $item) {
        if (isset($item['custom_length'])) {
            $product = $item->get_product();

            // 1.  Retrieve Current Stock
            $currentStock = (int) $product->get_meta('_stock');

            // 2.  Calculate Remaining Stock
            $newStock = max($currentStock - $item['custom_length'], 0); // Don't go below 0

            // 3.  Update Stock
            $product->set_stock($newStock);
            $product->save();
        }
    }
}

// #8 Modify Price Based on Custom Length - This is a placeholder function
// You would need to implement actual pricing logic based on your needs.
add_action('woocommerce_before_calculate_totals', 'calculate_price_based_on_length');

/**
 * Calculates and modifies the product price based on the custom length selected.
 *
 * @param WC_Cart $cart The WooCommerce cart object.
 */
function calculate_price_based_on_length($cart)
{
    // TODO: Implement the following:
    //  * Define your price calculation logic (price per unit?  tiered pricing? etc.)
    //  * Apply calculations to modify the cart item's price based on custom length.
}
