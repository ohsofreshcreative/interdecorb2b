@php
$sectionClass = collect([
    $nomt ? '!mt-0' : '',
    $background !== 'none' ? $background : '',
])->filter()->implode(' ');

$mainContentClass = $flip ? 'lg:order-1' : 'lg:order-2';
$sidebarClass = $flip ? 'lg:order-2' : 'lg:order-1';
@endphp

<!--- products --->

<section
    @if (!empty($section_id)) id="{{ $section_id }}" @endif
    class="b-products relative -smt {{ $sectionClass }} {{ $section_class }}"
    data-ajax-url="{{ admin_url('admin-ajax.php') }}"
    data-nonce="{{ wp_create_nonce('filter_products_nonce') }}"
>
    <div class="__wrapper c-main relative">
        
        @if (!empty($block_title) || !empty($content))
            <div class="w-full mb-10">
                @if (!empty($block_title))
                    <h2 class="m-header">{{ $block_title }}</h2>
                @endif
                @if (!empty($content))
                    <div class="prose max-w-none">
                        {!! $content !!}
                    </div>
                @endif
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-12">

            <div id="product-list-container" class="lg:col-span-3 {{ $mainContentClass }}">
                @if ($product_query->have_posts())
                    <div>
                        @php do_action('woocommerce_before_shop_loop'); @endphp
                    </div>

                    <ul class="products flex flex-col gap-6 mt-6">
                        @while ($product_query->have_posts()) @php $product_query->the_post() @endphp
                            @include('woocommerce.content-product')
                        @endwhile
                    </ul>

                    <div class="pagination mt-12">
                        @php
                            echo paginate_links([
                                'base' => str_replace(999999999, '%#%', esc_url(get_pagenum_link(999999999))),
                                'total' => $product_query->max_num_pages,
                                'current' => max(1, get_query_var('paged')),
                                'format' => '?paged=%#%',
                                'prev_text' => __('&laquo; Poprzednia'),
                                'next_text' => __('Następna &raquo;'),
                            ]);
                        @endphp
                    </div>

                    @php(wp_reset_postdata())
                @else
                    <p>{{ __('No products were found matching your selection.', 'woocommerce') }}</p>
                @endif
            </div>

           <aside class="lg:col-span-1 {{ $sidebarClass }}">
                <h3 class="text-lg font-semibold mb-4">Kategorie produktów</h3>
                @if (!empty($product_categories))
                    <ul class="product-categories-filter flex flex-col gap-2">
                        <li>
                            <button data-category-id="all" class="category-filter-btn active w-full text-left p-2 rounded hover:bg-gray-100">Wszystkie produkty</button>
                        </li>
                        @foreach ($product_categories as $category)
                            @include('partials.category-filter-item', ['category' => $category])
                        @endforeach
                    </ul>
                @else
                    <p>Brak kategorii do wyświetlenia.</p>
                @endif
            </aside>

        </div>
    </div>
</section>