<?php

/**
 * My Account Dashboard
 *
 * Shows the first intro screen on the account dashboard.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/dashboard.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 4.4.0
 */

if (! defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

// Usunięto domyślną zawartość powitalną, ponieważ jest ona teraz w navigation.php

?>

<div class="space-y-8">

	<div class="w-full">
		<!-- Lewa kolumna -->
		<div class="space-y-8">
			<!-- Sekcja Promocji -->
			<div class="relative flex gap-4 rounded-lg bg-primary border-2 border-white border-dashed text-white px-6 py-5">
				<img src="/wp-content/uploads/2026/03/star.svg" />
				<div>
					<strong class="font-bold">Promocja!</strong>
					<span class="block sm:inline"> -10% na systemy SmartLine - tylko do końca listopada.</span>
				</div>
			</div>
			<!-- Ostatnie zamówienia -->
				<?php
				$customer_orders = wc_get_orders(apply_filters('woocommerce_my_account_my_orders_query', array(
					'customer' => get_current_user_id(),
					'limit'    => 5, // Ilość zamówień do wyświetlenia
					'orderby'  => 'date',
					'order'    => 'DESC',
				)));

				if ($customer_orders) {
					wc_get_template(
						'myaccount/orders.php',
						array(
							'current_page'    => 1,
							'customer_orders' => $customer_orders,
							'has_orders'      => count($customer_orders) > 0,
						)
					);
				} else {
					$no_orders_message = apply_filters('woocommerce_my_account_my_orders_message', __('No order has been made yet.', 'woocommerce'));
					echo '<div class="woocommerce-message woocommerce-message--info woocommerce-Message woocommerce-Message--info woocommerce-info">' . esc_html($no_orders_message) . '</div>';
				}
				?>

			<!-- Faktury i płatności -->
			<div class="bg-white p-6 rounded-xl shadow-sm">
				<div class="flex justify-between items-center mb-4">
					<h6 class="text-h7 font-bold">Faktury i płatności</h6>
					<a href="#" class="text-sm !underline hover:underline">Zobacz wszystkie</a>
				</div>
				<div>
					<?php // TODO: Tutaj umieść kod do wyświetlania faktur. Może to wymagać niestandardowej pętli lub integracji z wtyczką do faktur. 
					?>
					<p>Brak faktur do wyświetlenia.</p>
				</div>
			</div>

			<div class="grid grid-cols-1 md:grid-cols-2 gap-8">
				<!-- Do pobrania -->
				<div class="bg-white p-6 rounded-xl shadow-sm">
					<h6 class="text-h7 font-bold mb-4">Do pobrania</h6>
					<?php // TODO: Użyj pętli repeatera z ACF Options Page, aby wyświetlić pliki 
					?>
					<ul class="space-y-2">
						<li><a href="#" class="text-primary hover:underline">Katalog produktowy 2025 (PDF)</a></li>
						<li><a href="#" class="text-primary hover:underline">Cennik systemów rolet (XLS)</a></li>
						<li><a href="#" class="text-primary hover:underline">Paleta kolorów materiałów</a></li>
					</ul>
				</div>

				<!-- Kontakt do opiekuna -->
				<div class="bg-white p-6 rounded-xl shadow-sm">
					<h6 class="text-h7 font-bold mb-4">Kontakt do opiekuna</h6>
					<?php
					// TODO: Pobierz dane opiekuna przypisanego do użytkownika (np. z user meta)
					// $user_id = get_current_user_id();
					// $opiekun_name = get_user_meta($user_id, 'opiekun_name', true);
					// $opiekun_phone = get_user_meta($user_id, 'opiekun_phone', true);
					// $opiekun_email = get_user_meta($user_id, 'opiekun_email', true);
					?>
					<div class="space-y-1">
						<p class="font-semibold">Anna Nowak</p>
						<p>+48 600 123 456</p>
						<p><a href="mailto:anna.nowak@interdecorpro.pl" class="text-primary hover:underline">anna.nowak@interdecorpro.pl</a></p>
					</div>
				</div>
			</div>
		</div>

		
	</div>
</div>

<?php
/**
 * Deprecated woocommerce_before_my_account action.
 *
 * @deprecated 2.6.0
 */
do_action('woocommerce_before_my_account');

/**
 * Deprecated woocommerce_after_my_account action.
 *
 * @deprecated 2.6.0
 */
do_action('woocommerce_after_my_account');

/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */