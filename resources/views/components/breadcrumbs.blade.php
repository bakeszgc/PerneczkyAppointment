<div>
    <a href="https://youtu.be/rkjNL4dX-U4?t=64" class="hover:underline">Home</a>
    @foreach ($links as $linkDisplay => $link)
        @if ($linkDisplay !== 'All')
            ➡️
            <a href="{{$link}}" class="hover:underline">{{$linkDisplay}}</a>
        @endif
    @endforeach
</div>