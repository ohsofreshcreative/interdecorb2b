@php
$sectionClass = '';
$sectionClass .= $flip ? ' order-flip' : '';
$sectionClass .= $wide ? ' wide' : '';
$sectionClass .= $nomt ? ' !mt-0' : '';
$sectionClass .= $gap ? ' wider-gap' : '';

if (!empty($background) && $background !== 'none') {
$sectionClass .= ' ' . $background;
}
@endphp

<!--- logos -->

<section data-gsap-anim="section" @if(!empty($section_id)) id="{{ $section_id }}" @endif class="b-logos c-main relative -smt {{ $sectionClass }} {{ $section_class }}">

	<div class="__wrapper relative">
		<h4 data-gsap-element="header" class="w-full md:w-1/2">{{ $g_logos['title'] }}</h4>
	</div>

	@if (!empty($g_logos['gallery']))
    <div class="">
        <div class="flex justify-between items-center gap-6">
           
            @foreach ($g_logos['gallery'] as $image)
             <div class="bg-white w-56 h-32 mt-6 mb-6 flex items-center justify-center">
                <img src="{{ $image['url'] }}" alt="{{ $image['alt'] }}" class="h-12 w-auto">
            </div>
            @endforeach
       
        </div>
    </div>
    @endif

</section>