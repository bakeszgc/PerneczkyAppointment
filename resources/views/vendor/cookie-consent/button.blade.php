<form action="{!! $url !!}" {!! $attributes !!}>
    @csrf
    <x-button :full="true" role="{{ $label == 'Accept all' ? 'ctaMain' : 'cta' }}">
        <span class="{!! $basename !!}__label text-sm">{{ $label }}</span>
    </x-button>
</form>
