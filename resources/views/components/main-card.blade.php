<aside {{ $attributes->merge(['class' => 'rounded-md p-4 bg-[#0018d5] text-white text-justify text-base max-sm:text-sm opacity-0 transition-all duration-500 ease-out shadow-2xl slide-from-'.$direction.($direction == 'left' ? ' -translate-x-1/2' : ' translate-x-1/2')]) }}>
    {{ $slot }}
</aside>