<select name="{{ $name }}" id="{{ $id }}" {{ $disabled ? 'disabled' : '' }} {{ $attributes->merge(['class' => 'border border-slate-300 rounded-md hover:border-blue-500 hover:drop-shadow transition-all disabled:bg-slate-100 disabled:text-slate-400 disabled:hover:border-slate-300 disabled:hover:drop-shadow-none']) }}>
    {{ $slot }}
</select>