<h6 class="text-primary">{!! esc_html__('Zamów ponownie', 'woocommerce') !!}</h6>
<p>{!! esc_html__('Poniżej znajduje się lista produktów z Twoich poprzednich zamówień. Możesz je łatwo dodać ponownie do koszyka.', 'woocommerce') !!}</p>

@if (! $products)
  <div class="woocommerce-message woocommerce-message--info woocommerce-Message woocommerce-Message--info woocommerce-info">
    {{ __('Nie masz jeszcze żadnych produktów w poprzednich zamówieniach.', 'woocommerce') }}
  </div>
@else
  <div class="reorder-product-list space-y-4 mt-8">
    @foreach ($products as $product)
      <div class="relative flex flex-col lg:flex-row lg:items-center border-b border-dashed border-gray-300 gap-6 py-4">
        {{-- Obrazek produktu --}}
        <figure class="woocommerce-product-figure relative !mb-0 mr-4 flex-shrink-0">
          <a href="{{ esc_url($product->get_permalink()) }}">
            {!! $product->get_image('woocommerce_thumbnail', ['class' => 'img-xs max-h-52 !max-w-30 !object-cover aspect-square']) !!}
          </a>
        </figure>

        <div class="flex flex-col lg:flex-row flex-grow lg:items-center w-full">
          {{-- Nazwa produktu i SKU --}}
          <div class="flex-grow mb-4 lg:mb-0 text-center lg:text-left">
            <h5 class="woocommerce-loop-product__title text-h7">
              <a class="block" href="{{ esc_url($product->get_permalink()) }}">{!! esc_html($product->get_name()) !!}</a>
            </h5>
            @if ($product->get_sku())
              <p class="text-sm text-gray-600">Kod produktu: {{ $product->get_sku() }}</p>
            @endif
          </div>

          {{-- Cena --}}
          <div class="w-full lg:w-32 text-center font-bold lg:flex-shrink-0 mb-4 lg:mb-0">
            <span class="lg:hidden font-normal">Cena: </span>{!! $product->get_price_html() !!}
          </div>

           {{-- Przycisk Dodaj do koszyka --}}
          <div class="w-full lg:w-48 flex items-center justify-center lg:justify-end lg:flex-shrink-0 gap-2">
            @php
              $button_text = $product->add_to_cart_text();
              $button_url = $product->add_to_cart_url();
              $button_classes = [
                  'button', 'main-btn', '!bg-primary', 'flex', 'items-center', 'justify-center',
                  '!w-10', '!h-10', 'rounded-full', 'product_type_' . $product->get_type()
              ];
              
              // Dodajemy klasy AJAX tylko dla produktów, które je wspierają
              if ($product->is_purchasable() && $product->is_in_stock()) {
                  $button_classes[] = 'add_to_cart_button';
                  if ($product->supports('ajax_add_to_cart')) {
                      $button_classes[] = 'ajax_add_to_cart';
                  }
              }

              // Zawsze ta sama ikona koszyka
              $icon = '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="20" viewBox="0 0 22 20" fill="none"><path d="M10.713 17.446C10.713 18.8565 9.62939 20 8.29272 20C6.95604 20 5.87245 18.8565 5.87245 17.446C5.87245 16.505 6.35479 15.6827 7.07284 15.2397C7.0598 15.2032 6.02524 10.7536 3.96925 1.89092C3.93556 1.74538 3.81798 1.64013 3.67875 1.62672L0.770066 1.62527C0.344795 1.62522 0 1.26142 0 0.81261C0 0.37875 0.322161 0.0243414 0.72782 0.00118521L3.6486 0C4.49098 0 5.22675 0.59372 5.45064 1.44544L5.83146 3.08707L21.2299 3.08721C21.6552 3.08721 22 3.45105 22 3.89982C22 3.93214 21.9898 4.01683 21.9695 4.15394L20.0496 12.2799C19.9581 12.6673 19.6171 12.9198 19.2524 12.8944L8.10187 12.8973L8.502 14.6256C8.5357 14.7712 8.65328 14.8764 8.7925 14.8898L17.7537 14.892C19.0904 14.892 20.1739 16.0355 20.1739 17.446C20.1739 18.7679 19.2222 19.8553 18.0023 19.9866C17.9206 19.9954 17.8376 20 17.7537 20C16.417 20 15.3334 18.8565 15.3334 17.446C15.3334 17.1181 15.392 16.8046 15.4986 16.5166H10.5477C10.6544 16.8046 10.713 17.1181 10.713 17.446ZM17.7538 16.5173C17.2677 16.5173 16.8737 16.9331 16.8737 17.446C16.8737 17.9589 17.2677 18.3747 17.7538 18.3747C18.2398 18.3747 18.6339 17.9589 18.6339 17.446C18.6339 16.9331 18.2398 16.5173 17.7538 16.5173ZM8.29267 16.5173C7.80662 16.5173 7.41258 16.9331 7.41258 17.446C7.41258 17.9589 7.80662 18.3747 8.29267 18.3747C8.77872 18.3747 9.17276 17.9589 9.17276 17.446C9.1728 16.9331 8.77876 16.5173 8.29267 16.5173ZM20.2501 4.71229H6.20774L7.72593 11.2726L18.7003 11.2721L20.2501 4.71229Z" fill="#FFF"></path></svg>';
            @endphp
            <a href="{{ esc_url($button_url) }}" 
               data-quantity="1" 
               data-product_id="{{ $product->get_id() }}"
               class="{{ esc_attr(implode(' ', array_filter($button_classes))) }}" 
               aria-label="{{ esc_attr($button_text) }}">
              {!! $icon !!}<span class="sr-only">{{ esc_html($button_text) }}</span>
            </a>
          </div>
        </div>
      </div>
    @endforeach
  </div>
@endif