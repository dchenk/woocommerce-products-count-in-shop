<?php
/*
Plugin Name: WooCommerce Products Count in Shop
Plugin URI: https://github.com/dchenk/woocommerce-products-count-in-shop
Description: Customize the number of products shown on the WooCommerce shop page.
Version: 1.0.0
Author: widerwebs
License: MIT
*/

define('WW_WOOCOMM_PRODUCTS_COUNT_FIELD', 'ww_woocommerce_products_count_in_shop');

function ww_woocommerce_products_count_sanitize($val) {
    return filter_var($val, FILTER_VALIDATE_INT, [
        'options' => ['default' => 12, 'min_range' => 1]
    ]);
}

function ww_woocommerce_products_count_in_shop($prods) {
    return ww_woocommerce_products_count_sanitize(get_option(WW_WOOCOMM_PRODUCTS_COUNT_FIELD));
}

add_filter('loop_shop_per_page', 'ww_woocommerce_products_count_in_shop', 20);

function ww_woocommerce_products_count_in_shop_init() {
    register_setting('ww_woocommerce_products_count', WW_WOOCOMM_PRODUCTS_COUNT_FIELD, [
        'type' => 'number',
        'description' => 'The number of products shown on the WooCommerce shop page',
        'sanitize_callback' => 'ww_woocommerce_products_count_sanitize',
        'default' => null,
    ]);

    add_settings_section(
        'ww_woocommerce_products_count_in_shop_section',
        '',
        '__return_true', /*'ww_woocommerce_products_count_in_shop_output',*/
        'ww_woocommerce_products_count'
    );

    add_settings_field(
        'ww_woocommerce_products_count_in_shop',
        'Number of products to show on shop page',
        'ww_woocommerce_products_count_in_shop_render',
        'ww_woocommerce_products_count',
        'ww_woocommerce_products_count_in_shop_section'
    );
}

add_action('admin_init', 'ww_woocommerce_products_count_in_shop_init');

function ww_woocommerce_products_count_in_shop_render() {
    echo '<input type="number" id="'.WW_WOOCOMM_PRODUCTS_COUNT_FIELD.'" name="'.WW_WOOCOMM_PRODUCTS_COUNT_FIELD.'" value="' . get_option(WW_WOOCOMM_PRODUCTS_COUNT_FIELD, '12') . '">';
}

function ww_woocommerce_products_count_in_shop_admin_page() {
    add_options_page(
        'WooCommerce count of products on shop page',
        'Shop Products Count',
        'manage_options',
        'ww_woocommerce_products_count',
        'ww_woocommerce_products_count_in_shop_options_page'
    );
}

add_action('admin_menu', 'ww_woocommerce_products_count_in_shop_admin_page');

function ww_woocommerce_products_count_in_shop_options_page() {
    ?>
    <div class="wrap">
        <h2>WooCommerce count of products on shop page</h2>
        <form action="options.php" method="post">
            <?php settings_fields('ww_woocommerce_products_count'); ?>
            <?php do_settings_sections('ww_woocommerce_products_count'); ?>
            <input name="Submit" type="submit" value="Save Changes" class="button button-primary">
        </form>
    </div><?php
}