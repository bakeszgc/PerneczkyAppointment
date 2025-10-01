@if ($type == 'textarea')
    <textarea name="{{ $name }}" id="{{ $id }}" @disabled($disabled) @readonly($readonly) {{ $attributes->merge(['class' => 'border border-slate-300 rounded-md p-2 h-20 resize-none hover:border-blue-500 hover:drop-shadow transition-all disabled:bg-slate-100 disabled:text-slate-400 disabled:hover:border-slate-300 disabled:hover:drop-shadow-none']) }}>{{ $slot }}</textarea>
@else
    <input type="{{$type}}" placeholder="{{$placeholder}}" name="{{$name}}" value="{{ $value }}" id="{{ $id }}" @checked($checked) @disabled($disabled) @readonly($readonly)
    {{ $attributes->merge(['class' => 'max-md:text-sm border border-slate-300 p-2 hover:border-blue-500 hover:drop-shadow transition-all disabled:bg-slate-100 disabled:text-slate-400 disabled:hover:border-slate-300 disabled:hover:drop-shadow-none' . ($type == 'radio' ? ' rounded-full' : ' rounded-md')]) }}/>
@endif