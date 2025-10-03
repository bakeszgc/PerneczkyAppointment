<div {{ $attributes->merge(['class' => 'relative rounded-md overflow-hidden shadow-xl cursor-pointer group']) }} >
    <img src="{{ $barber->getPicture() }}" alt="{{ $barber->getName() }}" class=" min-w-full rounded-md z-0 transition-all group-hover:scale-105">

    <div class="w-full h-1/2 absolute bottom-0 left-0 z-10" style="background: linear-gradient(0deg,rgba(0, 0, 0, 1) 0%, rgba(255, 255, 255, 0) 100%);"></div>

    <h2 class="font-black text-xl max-lg:text-lg max-md:text-base max-sm:text-sm absolute bottom-[5%] left-[5%] text-white z-20 text-left">
        {{ $barber->getName() }}
    </h2>
</div>