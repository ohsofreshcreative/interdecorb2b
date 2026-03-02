<?php

/**
 * Theme filters.
 */

namespace App;

/**
 * Add "… Continued" to the excerpt.
 *
 * @return string
 */
add_filter('excerpt_more', function () {
    return sprintf(' &hellip; <a href="%s">%s</a>', get_permalink(), __('Continued', 'sage'));
});


add_action('pre_get_posts', function ($q) {
  if (is_admin() || !$q->is_main_query()) {
    return;
  }
  if ($q->is_search()) {
    // Jeżeli przyszło z naszego paska: post_type=produkty
    if (!empty($_GET['post_type']) && $_GET['post_type'] === 'produkty') {
      $q->set('post_type', 'produkty');
      // (opcjonalnie) sortowanie / ilość:
      // $q->set('posts_per_page', 12);
      // $q->set('orderby', 'date');
      // $q->set('order', 'DESC');
    }
  }
});

/*--- AJAX SEARCH ---*/

/**
 * Obsługa wyszukiwania produktów przez AJAX dla WooCommerce.
 */
add_action('wp_ajax_search_products', 'App\handle_ajax_search_products');
add_action('wp_ajax_nopriv_search_products', 'App\handle_ajax_search_products');

function handle_ajax_search_products() {
    $search_query = sanitize_text_field($_REQUEST['s'] ?? '');

    if (empty($search_query)) {
        wp_send_json_error('Empty search query');
        return;
    }

    $args = [
        'post_type' => 'product',
        'posts_per_page' => 5,
        's' => $search_query,
        'post_status' => 'publish',
    ];

    $products_query = new \WP_Query($args);

    $results = [];

    if ($products_query->have_posts()) {
        while ($products_query->have_posts()) {
            $products_query->the_post();
            
            $product = wc_get_product(get_the_ID());
            
            if (!$product) {
                continue;
            }
            
            $image_id = $product->get_image_id();
            $image_url = wp_get_attachment_image_url($image_id, 'thumbnail');

            $results[] = [
                'id' => get_the_ID(),
                'title' => get_the_title(),
                'url' => get_permalink(),
                'image' => $image_url ? $image_url : wc_placeholder_img_src(),
            ];
        }
    }

    wp_reset_postdata();

    wp_send_json_success($results);
}

/**
 * Aktualizacja licznika koszyka przez AJAX
 */
add_filter('woocommerce_add_to_cart_fragments', function($fragments) {
    $count = WC()->cart->get_cart_contents_count();
    
    ob_start();
    if ($count > 0) {
        ?>
        <span class="cart-count absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center">
            <?php echo $count; ?>
        </span>
        <?php
    }
    $fragments['.cart-count'] = ob_get_clean();
    return $fragments;
});

/*--- CHANGE DEFAULT WOOCOMMERCE TABS ---*/

add_filter('woocommerce_product_tabs', function ($tabs) {
  if (!empty($tabs['additional_information']['title'])) {
    $tabs['additional_information']['title'] = __('Specyfikacja produktu', 'twoj-textdomain');
  }
  return $tabs;
}, 98);


add_action('woocommerce_after_add_to_cart_form', function () {
  global $product;

  if (!$product instanceof \WC_Product) {
    return;
  }
  ?>
  <div class="__info flex flex-col border-t border-primary border-dashed pt-8 mt-4">
    <div class="__stock flex items-center">
      <?php
      $status = $product->get_stock_status();

      if ($status === 'instock') {
        echo '<p class="stock in-stock">W magazynie</p>';
      } elseif ($status === 'outofstock') {
        echo '<p class="stock out-of-stock">Brak w magazynie</p>';
      } else {
        echo '<p class="stock available-on-backorder">Na zamówienie</p>';
      }
      ?>
    </div>
    <div class="__shipping flex items-center">Wysyłka w 24–48 h </div>
    <div class="__payment flex items-center">Dostępne płatności na podstawie faktury</div>

  </div>
  <?php
});

/**
 * Zmiana kolejności kolumn na stronie "Moje konto -> Zamówienia".
 */
add_filter( 'woocommerce_account_orders_columns', function ( $columns ) {
    $new_columns = array();
    $new_columns['order-number']  = __( 'Order', 'woocommerce' );
    $new_columns['order-date']    = __( 'Date', 'woocommerce' );
    $new_columns['order-total']   = __( 'Total', 'woocommerce' );
    $new_columns['order-status']  = __( 'Status', 'woocommerce' );
    $new_columns['order-actions'] = __( 'Actions', 'woocommerce' );

    return $new_columns;
});