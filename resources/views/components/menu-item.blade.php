<a href="{{$link}}">
    <li {{$attributes->merge(['class' => ' px-4 py-2 hover:bg-slate-200'])}}>
        {{$slot}}
    </li>
</a>