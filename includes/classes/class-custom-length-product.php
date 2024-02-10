<?php

add_action('init', 'add_custom_length_product_type');
add_action('woocommerce_product_data_tabs', 'add_custom_lengths_tab');
add_action('woocommerce_product_data_panels', 'add_custom_length_field');
add_action('woocommerce_process_product_meta', 'save_custom_length_data');
add_action('woocommerce_single_product_summary', 'display_custom_length');
add_filter('woocommerce_product_settings', 'add_custom_length_settings');
add_filter('woocommerce_product_class', 'add_custom_product_class', 10, 2);

/**
* Register the custom length product type.
*/
function register_custom_product_type()
{
add_filter('product_type_selector', 'add_custom_length_product_type');
}

/**
* Add the custom length product type to the list of available product types.
* @param array $types The list of available product types.
* @return array The modified list of available product types.
*/
function add_custom_length_product_type($types)
{
$types['custom_length'] = __('Custom Length');
return $types;
}

/**
* Add the tab for custom length options to the product data metabox.
*/
function add_custom_lengths_tab()
{
echo '<li class="custom_length_options"><a href="#custom_length_options">' . __('Custom Lengths') . '</a></li>';
}

/**
* Add the custom length fields to the product data metabox.
*/
function add_custom_length_field()
{
woocommerce_wp_text_input(array(
'id' => '_custom_length',
'class' => 'custom_length',
'label' => __('Custom Length'),
'desc_tip' => true,
'description' => __('Enter the custom length for this product'),
'wrapper_class' => 'form-row form-row-full'
));
}

/**
* Save the custom length data when the product is saved.
* @param int $product_id The ID of the product being saved.
*/
function save_custom_length_data($product_id)
{
$custom_length = isset($_POST['_custom_length']) ? sanitize_text_field($_POST['_custom_length']) : '';
update_post_meta($product_id, '_custom_length', $custom_length);
}

/**
* Display the custom length on the single product page.
*/
function display_custom_length()
{
$custom_length = get_post_meta(get_the_ID(), '_custom_length', true);
if (!empty($custom_length)) {
echo '<div id="product_length">' . __('Custom Length:') . ' ' . $custom_length . '</div>';
}
}

/**
* Add the custom length options to the WooCommerce settings.
* @param array $settings The current WooCommerce settings.
* @return array The updated WooCommerce settings.
*/
function add_custom_length_settings($settings)
{
// Your settings code here
return $settings;
}

/**
* Add the custom product type class.
* @param string $classname The current product class.
* @param string $product_type The current product type.
* @return string The updated product class.
*/
function add_custom_product_class($classname, $product_type)
{
if ($product_type === 'custom_length') {
$classname = 'WC_Product_Custom_Length';
}
return $classname;
}

/**
* The custom product type class.
*/
class WC_Product_Custom_Length extends WC_Product
{
public function get_type()
{
return 'custom_length';
}
}

add_action('before_woocommerce_init', function () {
if (class_exists(\Automattic\WooCommerce\Utilities\FeaturesUtil::class)) {
\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('custom_order_tables', __FILE__, true);
}
});
