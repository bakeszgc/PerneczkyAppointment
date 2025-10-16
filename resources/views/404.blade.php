<x-user-layout title="Page not found" currentView="user">

    <x-breadcrumbs :links="[
        '???' => '',
        '404' => ''
    ]" />

    <x-headline class="mb-4">Whoops... looks like you took a wrong turn</x-headline>    

    <x-card class="mb-4">
        <h2 class="mb-4 text-lg max-md:text-base">
            You've hit a <span class="font-bold">404</span> — the page you're looking for has gone on a little vacation.
        </h2>

        <p class="mb-2">If you got here by clicking one of our buttons, give us a shout so we can sort it out.</p>
        <p>Until then, how about heading back to the homepage and giving your quest another go?</p>
    </x-card>

    <x-link-button :link="route('home')" role="backMain">
        Back to home
    </x-link-button>

</x-user-layout>