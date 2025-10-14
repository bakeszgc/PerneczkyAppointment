<button @class([
    'border rounded-md
    hover:drop-shadow-lg disabled:hover:drop-shadow-none
    transition-all font-bold
    p-2
    inline-flex gap-1 items-center justify-center
    max-sm:text-xs',

    'bg-blue-50 hover:bg-blue-200 border-blue-300 text-[#0018d5]
    disabled:bg-slate-100 border-slate-300 disabled:text-slate-400'
    => $role === 'create' || $role === 'search' || $role === 'restore',

    'bg-[#0018d5] hover:bg-[#0f0f0f] text-white
    disabled:bg-slate-300 disabled:text-slate-500'
    => $role == 'createMain' || $role === 'ctaMain' || $role === 'restoreMain',

    'bg-red-50 hover:bg-red-200 border-red-300 text-red-700
    disabled:bg-slate-100 border-slate-300 disabled:text-slate-400'
    => $role === 'destroy',

    'bg-red-600 hover:bg-red-800 text-white
    disabled:bg-slate-300 disabled:text-slate-500'
    => $role === 'destroyMain',

    'bg-green-600 hover:bg-green-800 text-white
    disabled:bg-slate-300 disabled:text-slate-500'
    => $role === 'timeoffMain' || $role == 'timeoffCreateMain',

    'bg-slate-50 hover:bg-slate-200 border-slate-300 text-slate-700
    disabled:bg-slate-100 disabled:text-slate-400'
    => $role === 'show' || $role === 'edit' || $role === '',

    'w-full' => $full,
    'w-fit' => !$full,

    'max-h-fit' => $maxHeightFit,
    'max-h-28' => !$maxHeightFit
    
]) {!! $value ? 'value="' . $value . '"' : '' !!} {{ $name ? "name=" . $name : '' }} {{ $role === 'reset' ? "type=reset" : '' }} {{ $id ? "id=" . $id  : '' }} {{ $hidden == true ? 'hidden' : '' }} {{ $disabled == true ? 'disabled' : '' }}>

    @switch($role)
        @case('create')
        @case('createMain')
        @case('timeoffCreateMain')
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
        @break
        
        @case('destroy')
        @case('destroyMain')
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
            </svg>
        @break

        @case('show')
        @case('showMain')
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
        @break

        @case('edit')
        @case('editMain')
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
            </svg>
        @break

        @case('search')
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
            </svg>
        @break

        @case('restore')
        @case('restoreMain')
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
            </svg>
        @break
        @default            
    @endswitch
    {{$slot}}
</button>