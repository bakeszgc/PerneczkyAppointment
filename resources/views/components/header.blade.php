<div {{ $attributes->merge(['class' => 'parallax h-[30vh] flex items-center justify-center']) }}>
    <h1 class="text-4xl font-bold text-white">
        {{ $slot }}
    </h1>
</div>