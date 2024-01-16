<?php
/*
 * Plugin Name: Custom Length Woocommerce Listings
 * Plugin URI: https://github.com/Deejpotter/custom-length-woocommerce-listings
 * Description: Customize WooCommerce product pages with powerful and intuitive fields ( = product add-ons).
 * Version: 0.0.1
 * Author: Maker Store
 * Author URI: https://www.makerstore.com.au
 * Text Domain: custom-length-woocommerce-listings
*/

// Prevent direct file access. ABSPATH is defined by WordPress, so if it's not, stop executing the file.
if (!defined('ABSPATH')) exit;


/**
 * Autoload function for the CLWL classes.
 * This function automatically loads the required class files when they are needed.
 * 
 * @param string $class_name The fully-qualified name of the class to load.
 */
function clwl_autoload_classes($class_name)
{
    // If the class does not belong to our plugin, return early to avoid unnecessary processing.
    if (!is_int(strpos($class_name, 'CLWL')))
        return;
    // Convert the class name to a file path by removing the namespace and replacing backslashes with slashes.
    $class_name = str_replace('CLWL\\', '', $class_name);
    $class_name = str_replace('\\', '/', strtolower($class_name)) . '.php';
    // Extract the file name from the class name.
    $pos = strrpos($class_name, '/');
    $file_name = is_int($pos) ? substr($class_name, $pos + 1) : $class_name;
    // Extract the path from the class name.
    $path = str_replace(
        $file_name,
        '',
        $class_name
    );
    // Adjust the file name to match the WordPress file naming conventions.
    // This involves prefixing 'class-' to the file name and replacing underscores with hyphens.
    $new_file_name = 'class-'.str_replace('_','-',$file_name);
    // Construct the full file path using the WordPress function plugin_dir_path to ensure compatibility.
    $file_path = plugin_dir_path(__FILE__) . str_replace('\\', DIRECTORY_SEPARATOR, $path . strtolower($new_file_name));
    // Check if the file exists before requiring it to avoid errors.
    if (file_exists($file_path))
    require_once($file_path);
}

// Register the autoloader function to enable automatic class loading.
spl_autoload_register('clwl_autoload_classes');


/**
 * Initialize the WAPF class and return it.
 * This function creates a singleton instance of the main plugin class.
 * @return \CLWL\WAPF The initialized WAPF object.
 */
function clwl_initialize_plugin() {
// Define the version of the plugin.
$version = '1.6.6';
// Use a global variable to store the main plugin object to ensure it's only initialized once.
global $wapf;
// Initialize the main plugin class if it hasn't been already.
if( !isset($wapf) ) {
$wapf = new \CLWL\WAPF();
$wapf->initialize($version, __FILE__);
}
// Return the initialized plugin object.
return $wapf;
}

// Trigger the initialization of the plugin.
clwl_initialize_plugin();


// Declare compatibility with WooCommerce High-Performance Order Storage (HPOS).
// This ensures that the plugin functions correctly with custom order tables, a feature provided by HPOS.
add_action( 'before_woocommerce_init', function() {
if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
}});