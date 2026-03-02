@php
$sectionClass = '';
@endphp

<!--- contact --->

<section data-gsap-anim="section" class="contact bg-s-lighter relative -smt pt-20 {{ $sectionClass }} {{ $section_class }}">

	<div class="__wrapper c-main relative z-2">

		<div class="relative grid grid-cols-1 lg:grid-cols-2 gap-8 z-10">

			<div class="__contact flex flex-col gap-8">
				<div class="__box bg-white border border-primary rounded-3xl h-full p-10">
					<h4 data-gsap-element="header" class="text-primary">{!! $g_contact_1['header'] !!}</h4>
					<div data-gsap-element="txt" class="mt-4">
						{!! $g_contact_1['txt'] !!}
					</div>

					<div data-gsap-element="data" class="__data flex flex-col gap-2 mt-4">
						<a href="tel:{{ preg_replace('/\s+/', '', $g_contact_1['phone']) }}" class="__phone flex items-center text-xl">{{ $g_contact_1['phone'] }}</a>
						<a href="mailto:{{ $g_contact_1['mail'] }}" class="__mail flex items-center text-xl">{{ $g_contact_1['mail'] }}</a>
						<p class="text-lg __address">{{ $g_contact_1['address'] }}</p>
					</div>
				</div>
				<div class="__box bg-white border border-primary rounded-3xl h-full p-10">
					<h4 data-gsap-element="header" class="text-primary">{!! $g_contact_2['header'] !!}</h4>
					<div data-gsap-element="txt" class="text-lg mt-4">
						{!! $g_contact_2['address'] !!}
					</div>
					<a data-gsap-element="phone" href="tel:{{ preg_replace('/\s+/', '', $g_contact_1['phone']) }}" class="__phone flex items-center text-xl my-4">{{ $g_contact_1['phone'] }}</a>
					<div data-gsap-element="data">
						{!! $g_contact_2['data'] !!}
					</div>
				</div>
			</div>

			<div data-gsap-element="form" class="bg-white border border-primary rounded-3xl p-10">
				<h4 data-gsap-element="header" class="text-primary">{!! $g_contact_3['header'] !!}</h4>
				<div data-gsap-element="txt" class="my-4">
					{!! $g_contact_3['txt'] !!}
				</div>
				{!! do_shortcode($g_contact_3['shortcode']) !!}
			</div>

		</div>
	</div>

</section>