<footer class="footer bg-secondary overflow-hidden relative z-10">
	<div class="__wrapper c-main relative z-10">

		<div class="__widgets grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-1 md:gap-6 pt-10 pb-36 mt-12">
			@for ($i = 1; $i <= 4; $i++)
				@if (is_active_sidebar('sidebar-footer-' . $i))
				<div>
				@php(dynamic_sidebar('sidebar-footer-' . $i))
		</div>
		@endif
		@endfor
	</div>

	<img class="__bg absolute top-1/2 left-1/2 -translate-y-[55%] -translate-x-1/2 !h-[140%] opacity-5 pointer-events-none" src="/wp-content/uploads/2026/02/fav.svg" />
	</div>

	<div class="bg-white py-10 footer-bottom border-t border-primary-lighter">
		<div class="c-main flex flex-col md:flex-row justify-between gap-6 ">
			<p class="">Copyright ©2025 <?php echo get_bloginfo('name'); ?>. All Rights Reserved</p>
			<p class="flex gap-2">Designed &amp; Developed by
				<a target="_blank" href="https://www.ohsofresh.pl" title="OhSoFresh"><img class="oh" src="/wp-content/themes/interdecorb2b/resources/images/ohsofresh.svg"></a>
			</p>
		</div>
	</div>

	</div>
</footer>