@extends('layouts.app')

@section('content')
  @php
    do_action('get_header', 'shop');
    do_action('woocommerce_before_main_content');

    // Pobieramy ID strony, która jest ustawiona jako strona sklepu w WooCommerce
    $shop_page_id = get_option('woocommerce_shop_page_id');
  @endphp

  @if ($shop_page_id)
    @php
      // Pobieramy post (stronę) sklepu
      $shop_page = get_post($shop_page_id);
      // Ustawiamy globalne dane posta, aby funkcje takie jak the_content() działały poprawnie
      setup_postdata($shop_page);
    @endphp

    {{-- Renderujemy treść strony sklepu - to tutaj pojawią się Twoje bloki ACF --}}
    <div class="max-w-none">
      {!! apply_filters('the_content', $shop_page->post_content) !!}
    </div>

    @php
      // Przywracamy oryginalne dane posta
      wp_reset_postdata();
    @endphp
  @else
    {{-- Komunikat awaryjny, jeśli strona sklepu nie jest ustawiona --}}
    <div class="c-main">
      <div class="alert alert-warning">
        {{ __('Please set a shop page in WooCommerce settings.', 'sage') }}
      </div>
    </div>
  @endif

  @php
    do_action('woocommerce_after_main_content');
    do_action('get_sidebar', 'shop');
    do_action('get_footer', 'shop');
  @endphp
@endsection