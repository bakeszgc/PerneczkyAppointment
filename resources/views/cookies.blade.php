@use('Whitecube\LaravelCookieConsent\Facades\Cookies')
<x-user-layout title="Cookies" currentView="user">

    <x-breadcrumbs :links="['Cookies' => '']" />
    <x-headline class="mb-4">Cookie policy</x-headline>

    <x-card class="mb-8 text-justify">
        <p class="text-xl max-md:text-base mb-4">
            Just like us, most websites use cookies. But what exactly are they?
        </p>
        <p class="text-base max-md:text-sm mb-4">
            Cookies are small text files stored in your browser that help improve a website's functionality and user experience. There are several types of cookies - usually categorized as essential, analytics, and optional cookies.
        </p>

        <ul class="list-disc pl-6 *:mb-2 *:last:mb-0 text-base max-md:text-sm mb-4">
            <li>
                <a class="font-bold hover:text-blue-700 transition-all" href="#essential-cookies">Essential cookies</a> are required for the website to operate properly and can't be opted out.
            </li>
            <li>
                <a class="font-bold hover:text-blue-700 transition-all" href="#analytic-cookies">Analytic cookies</a> help collect insights about website usage or support marketing purposes.
            </li>
            <li>
                <a class="font-bold hover:text-blue-700 transition-all" href="#optional-cookies">Optional cookies</a> usually enhance the user experience and make the website easier to use
            </li>
        </ul>

        <p class="text-base max-md:text-sm mb-0">
            You can view all the cookies our website uses, along with a short description of their purpose, below. By clicking the <a class="font-bold hover:text-blue-700 transition-all" href="#manage-cookies">Manage cookies</a> button at the bottom of the page, you can review and adjust your cookie preferences at any time.
        </p>
    </x-card>

    @foreach(Cookies::getCategories() as $category)
        <div id="{{ strtr(strtolower($category->title),array(' ' => '-')) }}" class="-translate-y-20"></div>
        <h2 class="text-2xl max-md:text-xl font-bold mb-2">{{ $category->title }}</h2>
        <x-card class="mb-8">
            @foreach($category->getCookies() as $cookie)
                <div class="flex gap-2 border-b-2 pb-4 mb-4 last:pb-0 last:mb-0 last:border-0">
                    <div class="text-lg max-md:text-base max-sm:text-sm">🍪</div>
                    <div class="flex-1">
                        <h3 class="text-lg max-md:text-base max-sm:text-sm font-medium mb-2 break-all">{{ $cookie->name }}</h3>
                        
                        <p class="mb-2">{{ $cookie->description }}</p>
                        <p class="mb-4 last:mb-0">Duration: {{ \Carbon\CarbonInterval::minutes($cookie->duration)->cascade() }}</p>
                    </div>
                </div>   
            @endforeach
        </x-card>
    @endforeach

    <div id="manage-cookies" class="-translate-y-20"></div>
    <div class="mb-4">
        @cookieconsentbutton(action: 'reset', label: 'Manage cookies', attributes: ['id' => 'reset-button', 'class' => 'btn'])
    </div>

</x-user-layout>