@php
$sectionClass = '';
$sectionClass .= $flip ? ' order-flip' : '';
$sectionClass .= $nolist ? ' no-list' : '';
$sectionClass .= $wide ? ' wide' : '';
$sectionClass .= $nomt ? ' !mt-0' : '';
$sectionClass .= $gap ? ' wider-gap' : '';

if (!empty($background) && $background !== 'none') {
$sectionClass .= ' ' . $background;
}
@endphp

<!--- duo -->

<section data-gsap-anim="section" @if(!empty($section_id)) id="{{ $section_id }}" @endif class="b-duo relative -mt-20 {{ $sectionClass }} {{ $section_class }}">

	<div class="__wrapper c-main relative">
		<div class="grid grid-cols-1 lg:grid-cols-2 items-center gap-8 lg:gap-10">

            <div class="__col bg-cover radius-img border border-primary px-10 py-14" style="background-image: linear-gradient(to right, rgba(24, 25, 24, 1), transparent), url('{{ $g_duo1['image']['url'] }}')">
				<h5 data-gsap-element="header" class="text-white">{!! $g_duo1['header'] !!}</h5>

				@if (!empty($g_duo1['button']))
				<a data-gsap-element="btn" class="main-btn m-btn align-self-bottom" href="{{ $g_duo1['button']['url'] }}">{{ $g_duo1['button']['title'] }}</a>
				@endif
			</div>

           <div class="__col bg-cover radius-img border border-primary px-10 py-14" style="background-image: linear-gradient(to right, rgba(24, 25, 24, 1), transparent), url('{{ $g_duo2['image']['url'] }}')">
                <h5 data-gsap-element="header" class="text-white">{!! $g_duo2['header'] !!}</h5>

				@if (!empty($g_duo2['button']))
				<a data-gsap-element="btn" class="main-btn m-btn align-self-bottom" href="{{ $g_duo2['button']['url'] }}">{{ $g_duo2['button']['title'] }}</a>
				@endif

			</div>

		</div>
	</div>

</section>