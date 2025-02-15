<div {{$attributes->merge(['class' => ''])}}>
    <ul>
        @foreach ($links as $name => $link)
            <x-menu-item :link="$link" @class([
                'font-bold' => $loop->first,
                'font-normal' => !$loop->first,
            ])>
                {{$name}}
            </x-menu-item>
        @endforeach
    </ul>
</div>