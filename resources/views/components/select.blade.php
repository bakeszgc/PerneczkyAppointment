<select name="{{ $name }}" id="{{ $id }}" {{ $disabled ? 'disabled' : '' }} {{ $attributes->merge(['class' => 'border border-slate-300 rounded-md transition-all max-md:text-sm
hover:border-blue-500 hover:shadow-md
disabled:bg-slate-100 disabled:text-slate-400 disabled:hover:border-slate-300 disabled:hover:shadow-none']) }}>
    {{ $slot }}
</select>