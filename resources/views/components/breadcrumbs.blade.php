<div>
    <a href="https://youtu.be/rkjNL4dX-U4?t=64">Home</a>
    @foreach ($links as $linkDisplay => $link)
        @if ($linkDisplay !== 'All')
            →
            <a href="{{$link}}">{{$linkDisplay}}</a>
        @endif
    @endforeach
</div>