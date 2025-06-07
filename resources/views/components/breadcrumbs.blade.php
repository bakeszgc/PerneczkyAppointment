<div>
    <a href="{{ route('home') }}" class="hover:underline">Home</a>
    @foreach ($links as $linkDisplay => $link)
        @if ($linkDisplay !== 'All')
            ➡️
            <a href="{{$link}}" class="hover:underline">{{$linkDisplay}}</a>
        @endif
    @endforeach
</div>