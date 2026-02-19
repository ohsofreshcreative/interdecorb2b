@php
defined('ABSPATH') || exit;
@endphp

@php global $product; @endphp

<li class="relative flex !p-10">
	@if($product && $product->is_on_sale())
	<span class="onsale">Promocja!</span>
	@endif
	<figure class="woocommerce-product-figure relative">
		<a href="{{ get_permalink() }}">
			<img src="{{ get_the_post_thumbnail_url($product->get_id(), '') }}"
				alt="{{ get_the_title() }}" class="img-xs max-h-52 !object-contain" />
		</a>
	</figure>

	<div>
		<h5 class="woocommerce-loop-product__title text-h7">
			<a class="block" href="{{ get_permalink() }}">{!! get_the_title() !!}</a>
		</h5>
		<p>Kod produktu: {{ $product->get_sku() }}</p>
	</div>
	<div>
		stan magazynowy:
		@if ($product->is_type('variable'))
		{{-- Dla produktów wariantowych, które są w sprzedaży, pokaż ogólny status --}}
		@if ($product->is_in_stock())
		Dostępny
		@else
		Brak
		@endif
		@else
		{{-- Logika dla produktów prostych --}}
		@if ($product->get_stock_status() === 'instock')
		Na stanie
		@elseif ($product->get_stock_status() === 'onbackorder')
		Na zamówienie
		@else
		Brak
		@endif
		@endif
	</div>

	<div class="mt-6 text-center flex">
		<div>
			@if ($product->is_type('simple') && $product->is_purchasable() && $product->is_in_stock())
			<div class="quantity-wrapper flex items-center justify-center my-4">
				<button type="button" class="quantity-button quantity-minus bg-gray-200 px-3 py-1 rounded-l">-</button>
				@php
				woocommerce_quantity_input([
				'min_value' => $product->get_min_purchase_quantity(),
				'max_value' => $product->get_max_purchase_quantity(),
				'input_value' => $product->get_min_purchase_quantity(),
				], $product, true);
				@endphp
				<button type="button" class="quantity-button quantity-plus bg-gray-200 px-3 py-1 rounded-r">+</button>
			</div>
			@endif
		</div>

		@php do_action('woocommerce_after_shop_loop_item'); @endphp
	</div>
</li>