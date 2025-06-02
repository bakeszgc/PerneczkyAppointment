<div class="fixed top-0 left-0 right-0 bottom-0 z-50 hidden items-center justify-center p-4 bg-black bg-opacity-50" {{ $id ? "id=" . $id : ''}}>
    <div>
        <x-card class="max-w-4xl">
            {{ $slot }}
        </x-card>
    </div>
</div>