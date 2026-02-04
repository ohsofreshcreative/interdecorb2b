@php
$sectionClass = '';
$sectionClass .= $nomt ? ' !mt-0' : '';
@endphp

<!-- hero --->

<section
	data-gsap-anim="section"
	@if(!empty($section_id)) id="{{ $section_id }}" @endif
	class="b-hero relative {{ $sectionClass }} {{ $section_class }}" style="background-image:linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('{{ $g_hero['image']['url'] }}'); background-size:cover; background-position:center;">

	<div class="__wrapper c-main grid grid-cols-1 lg:grid-cols-[2fr_1fr] gap-8 items-end relative z-20 py-30">
		<div class="__content relative z-20 pt-20 pb-10 md:py-30">

			<h1 data-gsap-element="header" class="text-white bg-bg-brand">
				{{ $g_hero['title'] }}
			</h1>
			<div data-gsap-element="txt" class="text-xl md:text-2xl text-white mt-2 w-full md:w-2/3">
				{!! $g_hero['txt'] !!}
			</div>
			@if (!empty($g_hero['button1']))
			<div class="inline-buttons m-btn">
				<a data-gsap-element="button" class="second-btn left-btn"
					href="{{ $g_hero['button1']['url'] }}"
					target="{{ $g_hero['button1']['target'] }}">
					{{ $g_hero['button1']['title'] }}
				</a>
				@if (!empty($g_hero['button2']))
				<a data-gsap-element="button" class="white-btn"
					href="{{ $g_hero['button2']['url'] }}"
					target="{{ $g_hero['button2']['target'] }}">
					{{ $g_hero['button2']['title'] }}
				</a>
				@endif
			</div>
			@endif
		</div>
	</div>

</section>