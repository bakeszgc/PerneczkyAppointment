<select name="{{ $name }}" id="{{ $id }}" {{ $disabled ? 'disabled' : '' }} {{ $attributes->merge(['class' => 'border border-slate-300 rounded-md transition-all max-md:text-sm cursor-pointer
hover:border-blue-500 hover:shadow-md
focus:text-slate-700
disabled:bg-slate-100 disabled:text-slate-300 disabled:hover:border-slate-300 disabled:hover:shadow-none disabled:cursor-default']) }}>
    {{ $slot }}
</select>

<script>
    document.addEventListener('DOMContentLoaded',()=>{
        const selectInputs = document.querySelectorAll("select[name={{ $name }}]");
        selectInputs.forEach(input => {
            toggleSelectGreyText(input)
            input.addEventListener('change',()=>{
                toggleSelectGreyText(input)
            });
        });
    });
    
    function toggleSelectGreyText(input) {
        if (!input.value || input.value == '' || input.value == 'empty') {
            input.classList.add('text-slate-300');
        } else {
            input.classList.remove('text-slate-300');
        }
    }
</script>