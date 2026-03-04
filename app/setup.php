<?php

/**
 * Theme setup.
 */

namespace App;

use Illuminate\Support\Facades\Vite;

/**
 * Inject styles into the block editor.
 */
add_filter('block_editor_settings_all', function ($settings) {
    $style = Vite::asset('resources/css/editor.css');

    $settings['styles'][] = [
        'css' => "@import url('{$style}')",
    ];

    return $settings;
});

/**
 * Inject scripts into the block editor.
 */
add_filter('admin_head', function () {
    if (! get_current_screen()?->is_block_editor()) {
        return;
    }

    $dependencies = json_decode(Vite::content('editor.deps.json'));

    foreach ($dependencies as $dependency) {
        if (! wp_script_is($dependency)) {
            wp_enqueue_script($dependency);
        }
    }

    echo Vite::withEntryPoints([
        'resources/js/editor.js',
    ])->toHtml();
});

/**
 * Use the generated theme.json file.
 */
add_filter('theme_file_path', function ($path, $file) {
    return $file === 'theme.json'
        ? public_path('build/assets/theme.json')
        : $path;
}, 10, 2);

/**
 * Register the initial theme setup.
 *
 * @return void
 */
add_action('after_setup_theme', function () {
    /**
     * Enable features from Soil when plugin is activated
     * @link https://roots.io/plugins/soil/
     */
    add_theme_support('soil', [
        'clean-up',
        'nav-walker',
        'nice-search',
        'relative-urls',
    ]);

    /**
     * Enable features from WooCommerce
     */
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
    remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
    remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);

    /**
     * Disable full-site editing support.
     * @link https://wptavern.com/gutenberg-10-5-embeds-pdfs-adds-verse-block-color-options-and-introduces-new-patterns
     */
    remove_theme_support('block-templates');

    /**
     * Register the navigation menus.
     * @link https://developer.wordpress.org/reference/functions/register_nav_menus/
     */
    register_nav_menus([
        'primary_navigation' => __('Primary Navigation', 'sage'),
    ]);

    /**
     * Disable the default block patterns.
     * @link https://developer.wordpress.org/block-editor/developers/themes/theme-support/#disabling-the-default-block-patterns
     */
    remove_theme_support('core-block-patterns');

    /**
     * Enable plugins to manage the document title.
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#title-tag
     */
    add_theme_support('title-tag');

    /**
     * Enable post thumbnail support.
     * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
     */
    add_theme_support('post-thumbnails');

    /**
     * Enable responsive embed support.
     * @link https://developer.wordpress.org/block-editor/how-to-guides/themes/theme-support/#responsive-embedded-content
     */
    add_theme_support('responsive-embeds');

    /**
     * Enable HTML5 markup support.
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#html5
     */
    add_theme_support('html5', [
        'caption',
        'comment-form',
        'comment-list',
        'gallery',
        'search-form',
        'script',
        'style',
    ]);

    /**
     * Enable selective refresh for widgets in customizer.
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#customize-selective-refresh-widgets
     */
    add_theme_support('customize-selective-refresh-widgets');

    /**
     * WooCommerce Shop Page Customizations
     */
    add_action( 'woocommerce_before_shop_loop', function() {
        if ( is_shop() || is_product_category() || is_product_tag() ) {
            render_product_list_headers();
        }
    }, 5);

    remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
    remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );

}, 20);

/**
 * Funkcja renderująca nagłówki listy produktów.
 * Została wydzielona, aby można było jej używać w handlerze AJAX.
 */
function render_product_list_headers() {
    echo '<ul class="hidden lg:flex items-center py-2 border-b border-gray-200 font-bold text-sm">
            <li class="flex-grow">Produkt</li>
            <li class="w-32 text-center flex-shrink-0">Cena</li>
            <li class="w-32 text-center flex-shrink-0">Dostępność</li>
            <li class="w-48 text-right flex-shrink-0"></li>
          </ul>';
}

/*--- WOOCOMMERCE PHP FILES ---*/
array_map(function ($file) {
  require_once $file;
}, array_merge(
  glob(get_theme_file_path('app/Woo/*.php')) ?: [],
  glob(get_theme_file_path('app/Woo/*/*.php')) ?: []
));



// Dodaj tę linię, aby wczytać Twój plik
require_once __DIR__ . '/woocommerce.php';


/*--- WOOCOMMERCE SIDEBAR ---*/
add_action('widgets_init', function () {
    register_sidebar([
        'name'          => __('Sklep - Filtry', 'sage'),
        'id'            => 'sidebar-shop',
        'before_widget' => '<section class="widget %1$s %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h6 class="widget-title font-bold mb-4">',
        'after_title'   => '</h6>',
    ]);
});

/**
 * Register the theme sidebars.
 */
add_action('widgets_init', function () {
    $defaultConfig = [
        'before_widget' => '<section class="footer_widget widget %1$s %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h6 class="widget-title text-h6 text-white mb-4 flex">',
        'after_title' => '</h6>',
    ];

    register_sidebar([
        'name' => __('Primary', 'sage'),
        'id' => 'sidebar-primary',
    ] + $defaultConfig);

    register_sidebar([
        'name' => __('Footer 1', 'sage'),
        'id'   => 'sidebar-footer-1',
    ] + $defaultConfig);

    register_sidebar([
        'name' => __('Footer 2', 'sage'),
        'id'   => 'sidebar-footer-2',
    ] + $defaultConfig);

    register_sidebar([
        'name' => __('Footer 3', 'sage'),
        'id'   => 'sidebar-footer-3',
    ] + $defaultConfig);

    register_sidebar([
        'name' => __('Footer 4', 'sage'),
        'id'   => 'sidebar-footer-4',
    ] + $defaultConfig);
});

/*--- CATEGORY IMAGE ---*/
add_action('acf/init', function () {
    if (function_exists('acf_add_local_field_group')) {
        acf_add_local_field_group([
            'key' => 'group_category_settings',
            'title' => 'Ustawienia Kategorii',
            'fields' => [
                [
                    'key' => 'field_category_header',
                    'label' => 'Nagłówek',
                    'name' => 'category_header',
                    'type' => 'text',
                    'instructions' => 'Opcjonalny nagłówek, który może zastąpić domyślną nazwę kategorii.',
                ],
                [
                    'key' => 'field_category_description',
                    'label' => 'Opis',
                    'name' => 'category_description',
                    'type' => 'textarea',
                    'instructions' => 'Krótki opis wyświetlany na stronie kategorii.',
                ],
                [
                    'key' => 'field_category_image',
                    'label' => 'Zdjęcie Kategorii',
                    'name' => 'category_image',
                    'type' => 'image',
                    'instructions' => 'Dodaj obrazek, który będzie wyświetlany jako tło lub nagłówek dla tej kategorii.',
                    'return_format' => 'array',
                    'preview_size' => 'medium',
                    'library' => 'all',
                ],
            ],
            'location' => [
                [
                    [
                        'param' => 'taxonomy',
                        'operator' => '==',
                        'value' => 'category',
                    ],
                ],
            ],
            'menu_order' => 0,
            'position' => 'normal',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'active' => true,
        ]);
    }
});

/**
 * Remove archive title prefix (e.g., "Category:", "Tag:").
 */
add_filter('get_the_archive_title', function ($title) {
    if (is_category()) {
        $title = single_cat_title('', false);
    } elseif (is_tag()) {
        $title = single_tag_title('', false);
    } elseif (is_author()) {
        $title = '<span class="vcard">' . get_the_author() . '</span>';
    } elseif (is_post_type_archive()) {
        $title = post_type_archive_title('', false);
    } elseif (is_tax()) {
        $title = single_term_title('', false);
    }

    return $title;
});

/*--- GSAP ---*/
add_action('wp_enqueue_scripts', function () {
    wp_enqueue_script('gsap-cdn', 'https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js', [], null, true);
    wp_enqueue_script('gsap-st-cdn', 'https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/ScrollTrigger.min.js', ['gsap-cdn'], null, true);
}, 1);

/**
 * Register custom form tag for CF7 to display subsidies.
 */
add_action('wpcf7_init', function () {
    wpcf7_add_form_tag('subsidy_checkboxes', 'App\\custom_subsidy_checkboxes_handler');
});

/**
 * Handler for the [subsidy_checkboxes] form tag.
 */
function custom_subsidy_checkboxes_handler($tag)
{
    $subsidies = get_field('subsidies', 'option');
    $output = '';

    if ($subsidies) {
        $output .= '<h2 class="text-2xl font-bold mb-4">Dofinansowania</h2>';
        $output .= '<div class="flex flex-col gap-2">';
        foreach ($subsidies as $subsidy) {
            if (!empty($subsidy['subsidy_name'])) {
                $name = esc_attr($subsidy['subsidy_name']);
                $output .= sprintf(
                    '<label class="flex items-center gap-2"><input type="checkbox" name="subsidies[]" value="%s" /> <span>%s</span></label>',
                    $name,
                    esc_html($name)
                );
            }
        }
        $output .= '</div>';
    }

    return $output;
}

/**
 * Disable Contact Form 7 auto <p> tags.
 */
add_filter('wpcf7_autop_or_not', '__return_false');

/*--- WYSIWYG HEIGHT FIX ---*/
add_action('admin_footer', function () {
  $screen = function_exists('get_current_screen') ? get_current_screen() : null;
  if (!$screen || $screen->base !== 'post') return;
  ?>
  <script>
    (function () {
      const TARGET_HEIGHT = 140;
      function applyInitialHeight() {
        document.querySelectorAll('.acf-editor-wrap iframe[id^="acf-editor-"]').forEach((iframe) => {
          if (iframe.dataset.acfInitialHeightApplied === '1') return;
          const current = parseInt(iframe.style.height || 0, 10);
          if (!current || current > TARGET_HEIGHT) {
            iframe.style.height = TARGET_HEIGHT + 'px';
          }
          iframe.dataset.acfInitialHeightApplied = '1';
        });
      }
      let tries = 0;
      const timer = setInterval(() => {
        applyInitialHeight();
        tries++;
        if (tries >= 40) clearInterval(timer);
      }, 250);
      const obs = new MutationObserver(() => applyInitialHeight());
      obs.observe(document.body, { childList: true, subtree: true });
      window.addEventListener('load', () => setTimeout(applyInitialHeight, 500));
    })();
  </script>
  <?php
});

/*--- REDIRECT TAXONOMY TO PAGE ---*/
add_action('template_redirect', function () {
    if (!is_tax() && !is_category() && !is_tag()) {
        return;
    }
    $term = get_queried_object();
    if ($term instanceof \WP_Term) {
        $redirect_url = get_field('linked_page', $term);
        if ($redirect_url) {
            wp_safe_redirect($redirect_url, 301);
            exit;
        }
    }
});

/**
 * Rejestracja endpointu AJAX do filtrowania archiwum produktów.
 */
add_action('wp_ajax_filter_product_archive', 'App\\filter_product_archive_handler');
add_action('wp_ajax_nopriv_filter_product_archive', 'App\\filter_product_archive_handler');

/**
 * Handler dla filtrowania archiwum produktów.
 */
function filter_product_archive_handler() {
    if (!check_ajax_referer('product_archive_nonce', 'nonce', false)) {
        wp_send_json_error('Błąd weryfikacji.', 401);
        return;
    }

    $category_slug = isset($_POST['category']) ? sanitize_text_field($_POST['category']) : 'all';
    $paged = isset($_POST['page']) ? absint($_POST['page']) : 1;

    $args = [
        'post_type'      => 'product',
        'posts_per_page' => wc_get_default_products_per_row() * wc_get_default_product_rows_per_page(),
        'paged'          => $paged,
    ];

    if ($category_slug !== 'all') {
        $args['tax_query'] = [
            [
                'taxonomy' => 'product_cat',
                'field'    => 'slug',
                'terms'    => $category_slug,
            ],
        ];
    }

    $products_query = new \WP_Query($args);

    ob_start();

    if ($products_query->have_posts()) {
        echo '<section data-gsap-anim="section"><div class="">';
        
        // Toolbar sortowania - pozostawiamy, może być potrzebny
        do_action('woocommerce_before_shop_loop');
        
        woocommerce_product_loop_start();

        while ($products_query->have_posts()) {
            $products_query->the_post();
            wc_get_template_part('content', 'product');
        }

        woocommerce_product_loop_end();

        // --- POCZĄTEK ZMIANY ---
        // Ręczne generowanie paginacji zamiast do_action()
        $total_pages = $products_query->max_num_pages;

        if ($total_pages > 1) {
            $pagination_args = [
                // Zmieniamy 'base' i 'format', aby używać ?paged=
                'base'      => add_query_arg('paged', '%#%'),
                'format'    => '',
                'current'   => $paged,
                'total'     => $total_pages,
                'prev_text' => __('&laquo;'),
                'next_text' => __('&raquo;'),
                'type'      => 'list',
            ];
            
            echo '<nav class="woocommerce-pagination">';
            echo paginate_links($pagination_args);
            echo '</nav>';
        }
        // --- KONIEC ZMIANY ---
        
        echo '</div></section>';
    } else {
        do_action('woocommerce_no_products_found');
    }

    // Przywracamy oryginalne globalne zapytanie
    wp_reset_postdata(); // Używamy wp_reset_postdata, ponieważ nie zmienialiśmy globalnego $wp_query

    $html = ob_get_clean();
    wp_send_json_success(['html' => $html]);
}

/*--- WOOCOMMERCE HOOKS ---*/

add_action( 'init', function () {

    // Zdejmij cenę z domyślnego miejsca (priorytet 10)
    remove_action(
        'woocommerce_single_product_summary',
        'woocommerce_template_single_price',
        10
    );

    // Usuń domyślny przycisk z woocommerce_single_variation — mamy własny w variable.php
    remove_action(
        'woocommerce_single_variation',
        'woocommerce_single_variation_add_to_cart_button',
        20
    );

    // Usuń meta (SKU, kategorie, tagi)
    remove_action(
        'woocommerce_single_product_summary',
        'woocommerce_template_single_meta',
        40
    );

} );

// Cena dla SIMPLE — przed add to cart (30)
add_action( 'woocommerce_single_product_summary', function () {
    global $product;
    if ( ! $product || $product->is_type( 'variable' ) ) {
        return;
    }
    woocommerce_template_single_price();
}, 25 );

