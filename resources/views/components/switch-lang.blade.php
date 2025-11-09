<a href="{{ route('lang.change', ['lang' => strtolower(App::getLocale()) == 'en' ? 'hu' : 'en']) }}" class="w-fit">
    <img src="{{ asset('flags/' . (strtolower(App::getLocale() == 'en' ? 'hu' : 'en')) . '_flag.svg') }}" alt={{ strtolower(App::getLocale()) == 'en' ? 'Hungarian' : 'English' }} class="h-4">
</a>