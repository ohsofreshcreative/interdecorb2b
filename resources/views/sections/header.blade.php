@php
use App\Walkers\DropdownWalker;
use App\Walkers\MobileDropdownWalker;
@endphp

<header x-data="{ mobileOpen: false }" class="relative top-0 z-50 bg-secondary masthead fixed-top">

	<!-- Desktop Header -->
	<div class="hidden c-main py-4 md:px-4 lg:px-12 mx-auto md:block">
		<div class="topBar flex items-center justify-between w-full gap-4">
			<a class="brand w-1/6 min-w-25" href="{{ home_url('/') }}">
				@if ($logo)
				<img src="{{ $logo['url'] }}" alt="{{ $logo['alt'] ?? 'Logo' }}" class="relative w-auto h-12 -top-0.5">
				@else
				<span class="text-xl font-bold">{{ $siteName }}</span>
				@endif
			</a>

			<div x-data="productSearch()" @click.away="searchResults = []" class="relative w-1/3">
				<form role="search" method="get" action="{{ home_url('/') }}" class="relative">
					<input
						type=""
						name="s"
						placeholder="Szukaj produktów..."
						class="w-full p-2 pl-12 border border-primary rounded-full text-white"
						x-model="searchQuery"
						@input.debounce.300ms="searchProducts"
						autocomplete="off">
					<input type="hidden" name="post_type" value="product">
					<button type="submit" class="absolute top-1/2 -translate-y-1/2 left-1 p-2 text-gray-500 bg-primary rounded-full">
						<svg class="w-5 h-5" fill="#181918" viewBox="0 0 20 20">
							<path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path>
						</svg>
					</button>
					<button
						type="button"
						@click="searchQuery = ''; searchResults = []"
						x-show="searchQuery.length > 0"
						class="absolute top-1/2 -translate-y-1/2 right-2 p-1 text-white hover:text-gray-300 transition-colors">
						<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
							<path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" />
						</svg>
					</button>
				</form>
				<div x-show="searchResults.length > 0" class="absolute left-0 z-10 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg" style="display: none;">
					<ul>
						<template x-for="product in searchResults" :key="product.id">
							<li class="border-b last:border-b-0">
								<a :href="product.url" class="flex items-center p-2 hover:bg-gray-100">
									<img :src="product.image" class="w-12 h-12 mr-4">
									<span x-text="product.title"></span>
								</a>
							</li>
						</template>
					</ul>
				</div>
			</div>

			<div class="flex items-center gap-4">
				<a href=""><img src="/wp-content/uploads/2026/02/zamow.svg" /></a>
				<a href="/my-account/"><img src="/wp-content/uploads/2026/02/konto.svg" /></a>
				<a href="{{ wc_get_cart_url() }}" class="relative">
					<img src="/wp-content/uploads/2026/02/koszyk.svg" />
					@if(WC()->cart->get_cart_contents_count() > 0)
					<span class="cart-count absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center">
						{{ WC()->cart->get_cart_contents_count() }}
					</span>
					@endif
				</a>
			</div>
		</div>

		@if (has_nav_menu('primary_navigation'))
		<nav class="nav-primary w-full border-t border-primary-700 border-dashed pt-4 mt-6" aria-label="{{ wp_get_nav_menu_name('primary_navigation') }}">
			{!! wp_nav_menu([
			'theme_location' => 'primary_navigation',
			'menu_class' => 'nav flex justify-between gap-x-2 md:gap-x-3 lg:gap-x-6 text-sm font-medium items-center w-full',
			'container' => false,
			'echo' => false,
			'walker' => new DropdownWalker(),
			]) !!}
		</nav>
		@endif
	</div>

	<!-- Mobile Header Bar -->
	<div class="flex items-center justify-between p-4 mobile-menu fixed-top md:hidden gap-20">
		<a class="brand" href="{{ home_url('/') }}">
			@if ($logo)
			<img src="{{ $logo['url'] }}" alt="{{ $logo['alt'] ?? 'Logo' }}" class="relative w-auto h-12 -top-0.5 max-w-[200px]">
			@else
			<span class="text-lg font-bold">{{ $siteName }}</span>
			@endif
		</a>
		<button
			@click.stop="mobileOpen = !mobileOpen"
			class="p-2 primary bg-white rounded-md"
			aria-expanded="mobileOpen"
			aria-controls="mobile-menu-panel">
			<span class="sr-only">Otwórz menu główne</span>
			<svg x-show="!mobileOpen" class="block w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
				<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
			</svg>
			<svg x-show="mobileOpen" class="block w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" style="display: none;">
				<path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
			</svg>
		</button>
	</div>

	<!-- Mobile Menu Panel -->
	<div
		id="mobile-menu-panel"
		x-show="mobileOpen"
		@click.away="mobileOpen = false"
		@keydown.escape.window="mobileOpen = false"
		x-transition:enter="transition ease-out duration-200"
		x-transition:enter-start="opacity-0 transform translate-x-full"
		x-transition:enter-end="opacity-100 transform translate-x-0"
		x-transition:leave="transition ease-in duration-150"
		x-transition:leave-start="opacity-100 transform translate-x-0"
		x-transition:leave-end="opacity-0 transform translate-x-full"
		class="mobile-menu fixed top-0 right-0 bottom-0 w-full h-full bg-gradient shadow-xl z-[51] overflow-y-auto md:hidden" {{-- ZMIANA: Usunięto style="display: none;" i zmieniono z-40 na z-[51] --}}
		aria-label="Menu mobilne">
		<div class="p-4 relative z-10">
			<div class="flex items-center justify-between mb-6">
				<span class=""><a class="brand shrink-0" href="{{ home_url('/') }}"><img src="{{ $logo['url'] }}" alt="{{ $logo['alt'] ?? 'Logo' }}" class="w-auto max-w-[200px] h-12"></a></span>
				<button
					@click="mobileOpen = false"
					class="p-2 text-white rounded-md">
					<span class="sr-only">Zamknij menu</span>
					<svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
						<path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
					</svg>
				</button>
			</div>

			@if (has_nav_menu('primary_navigation'))
			<nav class="flex flex-col space-y-1 mt-20">
				{!! wp_nav_menu([
				'theme_location' => 'primary_navigation',
				'menu_class' => 'nav-mobile flex flex-col space-y-2',
				'container' => false,
				'echo' => false,
				'walker' => new MobileDropdownWalker(),
				]) !!}
			</nav>
			@endif

			<div class="mt-8">
				<a href="/kontakt" class="block w-full white-btn">
					Skontaktuj się z nami
				</a>
			</div>
		</div>
	</div>
</header>