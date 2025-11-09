<div {{ $attributes->merge(['class' => 'w-fit']) }}>
    <a href="{{ route('auth.redirect', $provider) }}" @class(['p-2 rounded-md text-white max-w-fit flex justify-between items-center gap-2 transition-all hover:shadow-md', 'bg-[#0866FF] hover:bg-[#003BB3]' => $provider == 'facebook', 'bg-[#DC4E41] hover:bg-[#AD3E34]' => $provider == 'google'])>
        <div>
            <img src="{{ asset('logo/' . $provider . '.png') }}" alt="{{ ucfirst($provider) }} logo" class="h-8">
        </div>
        <p class="flex-1 text-center">
            <span class="text-base max-md:text-sm">
                {{ __('auth.sign_in_with') . ' ' . ucfirst($provider) . __('auth.with') }}
            </span>
        </p>
    </a>
</div>