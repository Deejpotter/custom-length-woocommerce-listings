<?php

namespace CLWL {

    // Specify that we're using the L10n, Admin_Controller and Public_Controller classes.
    use CLWL\Includes\Classes\L10n;
    use CLWL\Includes\Controllers\Admin_Controller;
    use CLWL\Includes\Controllers\Public_Controller;

    // Prevent direct access to the file.
    if (!defined('ABSPATH')) {
        die;
    }

    /**
     * Main plugin class for Custom Length WooCommerce Listings.
     * Handles initialization and configuration of the plugin's core functionalities.
     */
    class Main
    {
        // The plugin's settings, we don't have any yet.
        private $settings = [];

        public function __construct()
        {

        }

        public function initialize($version,$base_file) {

            $basename = plugin_basename( $base_file );

            $this->settings = [

                // basic
                'name'				=> 'Advanced Product Fields for WooCommerce',
                'version'			=> $version,

                // urls
                'basename'			=> $basename,
                'path'				=> plugin_dir_path( $base_file ),
                'url'				=> plugin_dir_url( $base_file ),
                'slug'				=> dirname($basename),

                // options
                'capability'        => 'manage_options',

                // cpts
                'cpts'              => ['wapf_product']
            ];

            // Includes
            include_once( trailingslashit($this->settings['path']) . 'includes/api/api-helpers.php');

            // Language stuff
            new l10n();

            // Actions
            add_action('init',	[$this, 'register_post_types']);

            // Kick off admin page.
            if(is_admin())
                new Admin_Controller();

            new Public_Controller();

        }

        public function has_setting( $name ) {
            return isset($this->settings[ $name ]);
        }

        public function get_setting( $name ) {
            return isset($this->settings[ $name ]) ? $this->settings[ $name ] : null;
        }

        public function register_post_types() {

            $cap = wapf_get_setting('capability');

            register_post_type('wapf_product', [
                'labels'			=> [
                    'name'					=> __( 'Product Field Groups', 'advanced-product-fields-for-woocommerce' ),
                    'singular_name'			=> __( 'Field Group', 'advanced-product-fields-for-woocommerce' ),
                    'add_new'				=> __( 'Add New' , 'advanced-product-fields-for-woocommerce' ),
                    'add_new_item'			=> __('Add New Product Field Group' , 'advanced-product-fields-for-woocommerce'),
                    'edit_item'				=> __('Edit Product Field Group' , 'advanced-product-fields-for-woocommerce'),
                    'new_item'				=> __( 'New Field Group' , 'advanced-product-fields-for-woocommerce' ),
                    'view_item'				=> __( 'View Field Group', 'advanced-product-fields-for-woocommerce' ),
                    'search_items'			=> __( 'Search Field Groups', 'advanced-product-fields-for-woocommerce' ),
                    'not_found'				=> __( 'No Field Groups found', 'advanced-product-fields-for-woocommerce' ),
                    'not_found_in_trash'	=> __( 'No Field Groups found in Trash', 'advanced-product-fields-for-woocommerce' ),
                ],
                'public'			=> false,
                'show_ui'			=> true,
                '_builtin'			=> false,
                'capability_type'	=> 'post',
                'capabilities'		=> [
                    'edit_post'			=> $cap,
                    'delete_post'		=> $cap,
                    'edit_posts'		=> $cap,
                    'delete_posts'		=> $cap,
                ],
                'hierarchical'		=> true,
                'rewrite'			=> false,
                'query_var'			=> false,
                'supports' 			=> ['title'],
                'show_in_menu'		=> false,
                'show_in_rest'      => false
            ]);

        }

    }
}