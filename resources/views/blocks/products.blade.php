@php
  $sectionClass = '';
  $sectionClass .= !empty($flip) ? ' order-flip' : '';
  $sectionClass .= !empty($nolist) ? ' no-list' : '';
  $sectionClass .= !empty($wide) ? ' wide' : '';
  $sectionClass .= !empty($nomt) ? ' !mt-0' : '';
  $sectionClass .= !empty($gap) ? ' wider-gap' : '';

  if (!empty($background) && $background !== 'none') {
    $sectionClass .= ' ' . $background;
  }

  // Pobierz kategorie produktów do sidebara
  $product_categories = get_terms([
    'taxonomy'   => 'product_cat',
    'orderby'    => 'name',
    'hide_empty' => true,
  ]);

  // Sprawdź, czy w URL jest kategoria do filtrowania
  $current_cat_slug = get_query_var('product_cat');
@endphp

<section data-gsap-anim="section" @if(!empty($section_id)) id="{{ $section_id }}" @endif class="b-products relative -smt {{ $sectionClass }} {{ $section_class }}">
  <div class="__wrapper c-main relative">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 lg:gap-12">
      
      {{-- Sidebar z filtrami --}}
      <aside class="bg-white border border-dashed border-primary rounded-3xl lg:col-span-1 h-max p-10">
        <h5 class="text-lg text-primary font-semibold mb-4">Kategorie</h5>
        @if (!is_wp_error($product_categories) && !empty($product_categories))
          <ul class="space-y-2">
            {{-- Link do wszystkich produktów --}}
            <li>
              <a href="{{ get_permalink(wc_get_page_id('shop')) }}" class="{{ empty($current_cat_slug) ? 'font-bold' : '' }}">
                Wszystkie
              </a>
            </li>
            {{-- Lista kategorii --}}
            @foreach ($product_categories as $category)
              <li>
                <a href="{{ get_term_link($category) }}" class="{{ $current_cat_slug == $category->slug ? 'font-bold' : '' }}">
                  {{ $category->name }}
                </a>
              </li>
            @endforeach
          </ul>
        @endif
      </aside>

      {{-- Kontener na produkty --}}
      <div class="lg:col-span-3">
        @php
          // Używamy globalnej pętli WordPress, która jest świadoma kontekstu (np. wybranej kategorii)
          if (woocommerce_product_loop()) {
            do_action('woocommerce_before_shop_loop');
            woocommerce_product_loop_start();

            if (wc_get_loop_prop('total')) {
              while (have_posts()) {
                the_post();
                do_action('woocommerce_shop_loop');
                wc_get_template_part('content', 'product');
              }
            }

            woocommerce_product_loop_end();
            do_action('woocommerce_after_shop_loop');
          } else {
            do_action('woocommerce_no_products_found');
          }
        @endphp
      </div>

    </div>
  </div>
</section>