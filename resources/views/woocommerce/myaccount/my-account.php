<?php

/**
 * My Account page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/my-account.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.0
 */

defined('ABSPATH') || exit;

/**
 * My Account navigation.
 *
 * @since 2.6.0
 */
do_action('woocommerce_account_navigation'); ?>

<div class="grid grid-cols-1 md:grid-cols-[2fr_1fr] gap-8">
	<div class="woocommerce-MyAccount-content !w-full float-right">
		<?php
		/**
		 * My Account content.
		 *
		 * @since 2.6.0
		 */
		do_action('woocommerce_account_content');
		?>
	
	</div>
	
	<!-- Prawa kolumna (sidebar) -->
	<div class="space-y-8">
		<!-- Powiadomienia -->
		<div class="bg-white p-6 rounded-xl shadow-sm">
			<div class="flex items-center gap-3">
				<img src="/wp-content/uploads/2026/03/notification.svg" />
				<b class="text-lg">Powiadomienia</b>
			</div>
			<div class="space-y-3 text-sm mt-2">
				<?php // TODO: Tutaj umieść logikę do wyświetlania dynamicznych powiadomień dla użytkownika.
				?>
				<p>Zamówienie #10247 zostało przekazane do realizacji.</p>
				<p>Faktura FV/10/2025/61 oczekuje na płatność do 12.11.2025.</p>
				<p class="text-red-600">Uwaga: Przekroczony limit kredytowy o 250,00 zł. Skontaktuj się z opiekunem.</p>
			</div>
		</div>
	
		<!-- Dane Twojego konta -->
		<div class="bg-white p-6 rounded-xl shadow-sm">
			<div class="flex justify-between items-center mb-4">
				<div class="flex items-center gap-3">
					<img src="/wp-content/uploads/2026/03/data.svg" />
					<b class="text-lg">Dane Twojego konta</b>
				</div>
				<a href="<?php echo esc_url(wc_get_endpoint_url('edit-account')); ?>" class="text-primary">
					<img src="/wp-content/uploads/2026/03/settings.svg" alt="Ustawienia" class="w-6 h-6">
				</a>
			</div>
			<div class="space-y-1 text-sm">
				<?php
				// TODO: Pobierz dane firmy z pól użytkownika (user meta)
				// $user_id = get_current_user_id();
				// $company_name = get_user_meta($user_id, 'billing_company', true);
				// $nip = get_user_meta($user_id, 'billing_nip', true);
				?>
				<p class="font-bold">Studio Dekoracji Wnętrz Elegant</p>
				<p>NIP: 521 001 22 33</p>
				<p>ul. Słoneczna 14, Warszawa</p>
				<p class="mt-2">Marek Kowalski</p>
				<p>marek@elegant.pl</p>
			</div>
		</div>
	</div>
</div>