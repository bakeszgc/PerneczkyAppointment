<form action="{!! $url !!}" {!! $attributes !!}>
    @csrf
    <x-button :full="true" role="{{ $label == __('cookieConsent::cookies.essentials') ? 'cta' : 'ctaMain' }}">
        <span class="{!! $basename !!}__label text-sm">{{ $label }}</span>
    </x-button>
</form>