<?php

namespace App;

/**
 * Dodaje nową pozycję "Zamów ponownie" do menu "Moje konto" w WooCommerce.
 */
add_filter( 'woocommerce_account_menu_items', function ( $items ) {
    // Umieszczenie nowej pozycji przed "Wyloguj się"
    $logout = $items['customer-logout'];
    unset( $items['customer-logout'] );
    $items['reorder'] = __( 'Zamów ponownie', 'woocommerce' );
    $items['customer-logout'] = $logout;
    return $items;
}, 25 );

/**
 * Dodaje endpoint dla nowej podstrony.
 */
add_action( 'init', function () {
    add_rewrite_endpoint( 'reorder', EP_PAGES );
} );

/**
 * Wyświetla zawartość podstrony "Zamów ponownie".
 */
add_action( 'woocommerce_account_reorder_endpoint', function () {
    $customer_id = get_current_user_id();
    if ( ! $customer_id ) {
        return;
    }

    $customer_orders = wc_get_orders( [
        'customer_id' => $customer_id,
        'status'      => ['wc-completed', 'wc-processing'],
        'limit'       => -1,
    ] );

    $products_ordered = [];
    if ($customer_orders) {
        foreach ( $customer_orders as $order ) {
            foreach ( $order->get_items() as $item ) {
                $product = $item->get_product();
                if ( $product && $product->exists() && $product->is_purchasable() ) {
                    $products_ordered[ $product->get_id() ] = $product;
                }
            }
        }
    }

    echo \Roots\view('woocommerce.myaccount.reorder-page', [
        'products' => $products_ordered,
    ])->render();
} );


/**
 * Pozostałe funkcje WooCommerce
 */

// Wyłącz liczniki w widgetach/warstwach Woo
add_filter('woocommerce_layered_nav_count', '__return_false');
add_filter('woocommerce_product_categories_widget_args', function ($args) {
  $args['show_count'] = 0;
  return $args;
});

add_action('wp', function () {
  if (is_product()) {
    remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
  }
});