<?php

/**
 * My Account navigation
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/navigation.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.3.0
 */

if (! defined('ABSPATH')) {
	exit;
}

do_action('woocommerce_before_account_navigation');
?>

<nav class="float-left w-1/4 pr-0 md:pr-8" aria-label="<?php esc_html_e('Account pages', 'woocommerce'); ?>">
	<div class="bg-white flex items-center gap-4 rounded-xl p-4">
		<img src="/wp-content/uploads/2026/03/user.svg" />
		<div>
			<?php
			$current_user = wp_get_current_user();
			echo 'Dzień dobry, <strong>' . esc_html($current_user->display_name) . '</strong>';
			?>
		</div>
	</div>

	 <ul class="bg-white rounded-xl p-8 mt-6 space-y-8">
        <?php
        $icons_map = [
            'dashboard'       => 'dashboard.svg',
            'orders'          => 'zamowienia.svg',
            'faktury'         => 'faktury.svg', 
            'reklamacje'      => 'reklamacje.svg', 
            'edit-address'    => 'adresy.svg',
            'edit-account'    => 'dane-firmy.svg',
            'downloads'       => 'materialy.svg', 
            'opiekun'         => 'kontakt.svg', 
            'pomoc'           => 'pomoc.svg', 
            'customer-logout' => 'logout.svg',
        ];

        foreach (wc_get_account_menu_items() as $endpoint => $label) :
            $icon_filename = $icons_map[$endpoint] ?? 'default.svg';
            $icon_path = '/wp-content/uploads/2026/03/' . $icon_filename;
        ?>
            <li class="<?php echo wc_get_account_menu_item_classes($endpoint); ?>">
                <a href="<?php echo esc_url(wc_get_account_endpoint_url($endpoint)); ?>" class="flex items-center gap-3" <?php echo wc_is_current_account_menu_item($endpoint) ? 'aria-current="page"' : ''; ?>>
                    <img src="<?php echo esc_url($icon_path); ?>" alt="" class="w-5 h-5" />
                    <span><?php echo esc_html($label); ?></span>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>

<?php do_action('woocommerce_after_account_navigation'); ?>