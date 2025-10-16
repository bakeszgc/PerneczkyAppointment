<div {{ $attributes->merge(['class' => '']) }}>
    <p class="font-semibold text-base max-md:text-sm mb-2">Password criteria checklist</p>
    <ul class="mb-2 max-md:text-sm">
        <li class="flex gap-2 items-center">
            <div class="text-slate-500" id="lcLetterIconFalse">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4">
                    <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25Zm-1.72 6.97a.75.75 0 1 0-1.06 1.06L10.94 12l-1.72 1.72a.75.75 0 1 0 1.06 1.06L12 13.06l1.72 1.72a.75.75 0 1 0 1.06-1.06L13.06 12l1.72-1.72a.75.75 0 1 0-1.06-1.06L12 10.94l-1.72-1.72Z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="text-[#0018d5] hidden" id="lcLetterIconTrue">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4">
                    <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm13.36-1.814a.75.75 0 1 0-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 0 0-1.06 1.06l2.25 2.25a.75.75 0 0 0 1.14-.094l3.75-5.25Z" clip-rule="evenodd" />
                </svg>
            </div>
            <p>contains at least one <span class="font-semibold">lowercase letter</span></p>
        </li>
        <li class="flex gap-2 items-center">
            <div class="text-slate-500" id="ucLetterIconFalse">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4">
                    <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25Zm-1.72 6.97a.75.75 0 1 0-1.06 1.06L10.94 12l-1.72 1.72a.75.75 0 1 0 1.06 1.06L12 13.06l1.72 1.72a.75.75 0 1 0 1.06-1.06L13.06 12l1.72-1.72a.75.75 0 1 0-1.06-1.06L12 10.94l-1.72-1.72Z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="text-[#0018d5] hidden" id="ucLetterIconTrue">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4">
                    <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm13.36-1.814a.75.75 0 1 0-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 0 0-1.06 1.06l2.25 2.25a.75.75 0 0 0 1.14-.094l3.75-5.25Z" clip-rule="evenodd" />
                </svg>
            </div>
            contains at least one <span class="font-semibold">uppercase letter</span>
        </li>
        <li class="flex gap-2 items-center">
            <div class="text-slate-500" id="numberIconFalse">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4">
                    <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25Zm-1.72 6.97a.75.75 0 1 0-1.06 1.06L10.94 12l-1.72 1.72a.75.75 0 1 0 1.06 1.06L12 13.06l1.72 1.72a.75.75 0 1 0 1.06-1.06L13.06 12l1.72-1.72a.75.75 0 1 0-1.06-1.06L12 10.94l-1.72-1.72Z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="text-[#0018d5] hidden" id="numberIconTrue">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4">
                    <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm13.36-1.814a.75.75 0 1 0-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 0 0-1.06 1.06l2.25 2.25a.75.75 0 0 0 1.14-.094l3.75-5.25Z" clip-rule="evenodd" />
                </svg>
            </div>
            contains at least one <span class="font-semibold">number</span>
        </li>
        <li class="flex gap-2 items-center">
            <div class="text-slate-500" id="charLengthIconFalse">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4">
                    <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25Zm-1.72 6.97a.75.75 0 1 0-1.06 1.06L10.94 12l-1.72 1.72a.75.75 0 1 0 1.06 1.06L12 13.06l1.72 1.72a.75.75 0 1 0 1.06-1.06L13.06 12l1.72-1.72a.75.75 0 1 0-1.06-1.06L12 10.94l-1.72-1.72Z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="text-[#0018d5] hidden" id="charLengthIconTrue">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4">
                    <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm13.36-1.814a.75.75 0 1 0-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 0 0-1.06 1.06l2.25 2.25a.75.75 0 0 0 1.14-.094l3.75-5.25Z" clip-rule="evenodd" />
                </svg>
            </div>
            at least <span class="font-semibold">8 characters long</span>
        </li>
        <li class="flex gap-2 items-center">
            <div class="text-slate-500" id="matchIconFalse">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4">
                    <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25Zm-1.72 6.97a.75.75 0 1 0-1.06 1.06L10.94 12l-1.72 1.72a.75.75 0 1 0 1.06 1.06L12 13.06l1.72 1.72a.75.75 0 1 0 1.06-1.06L13.06 12l1.72-1.72a.75.75 0 1 0-1.06-1.06L12 10.94l-1.72-1.72Z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="text-[#0018d5] hidden" id="matchIconTrue">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4">
                    <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm13.36-1.814a.75.75 0 1 0-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 0 0-1.06 1.06l2.25 2.25a.75.75 0 0 0 1.14-.094l3.75-5.25Z" clip-rule="evenodd" />
                </svg>
            </div>
            matches with the confirmation field
        </li>
    </ul>

    Fields marked with * are <span class="font-semibold">required</span>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // PASSWORD CRITERIA CHECKLIST
            const passInput = document.getElementById('{{ $passwordInput }}');
            const passConfInput = document.getElementById('{{ $passwordConfInput }}');
            const passInputs = [passInput, passConfInput];
            const lcLetterIconFalse = document.getElementById('lcLetterIconFalse');
            const lcLetterIconTrue = document.getElementById('lcLetterIconTrue');
            const ucLetterIconFalse = document.getElementById('ucLetterIconFalse');
            const ucLetterIconTrue = document.getElementById('ucLetterIconTrue');
            const numberIconFalse = document.getElementById('numberIconFalse');
            const numberIconTrue = document.getElementById('numberIconTrue');
            const charLengthIconFalse = document.getElementById('charLengthIconFalse');
            const charLengthIconTrue = document.getElementById('charLengthIconTrue');
            const matchIconFalse = document.getElementById('matchIconFalse');
            const matchIconTrue = document.getElementById('matchIconTrue');
            const lcLetterRegex = /[a-z]/;
            const ucLetterRegex = /[A-Z]/;
            const numberRegex = /[0-9]/;
            
            passInputs.forEach(input => {
                input.addEventListener('input', function(){
                    const value = passInput.value;
                    const confValue = passConfInput.value;
                    
                    if (lcLetterRegex.test(value)) {
                        lcLetterIconFalse.classList.add('hidden');
                        lcLetterIconTrue.classList.remove('hidden');
                    } else {
                        lcLetterIconFalse.classList.remove('hidden');
                        lcLetterIconTrue.classList.add('hidden');
                    }
                    if (ucLetterRegex.test(value)) {
                        ucLetterIconFalse.classList.add('hidden');
                        ucLetterIconTrue.classList.remove('hidden');
                    } else {
                        ucLetterIconFalse.classList.remove('hidden');
                        ucLetterIconTrue.classList.add('hidden');
                    }
                    if (numberRegex.test(value)) {
                        numberIconFalse.classList.add('hidden');
                        numberIconTrue.classList.remove('hidden');
                    } else {
                        numberIconFalse.classList.remove('hidden');
                        numberIconTrue.classList.add('hidden');
                    }
                    if (value.length >= 8) {
                        charLengthIconFalse.classList.add('hidden');
                        charLengthIconTrue.classList.remove('hidden');
                    } else {
                        charLengthIconFalse.classList.remove('hidden');
                        charLengthIconTrue.classList.add('hidden');
                    }
                    if (value == confValue) {
                        matchIconFalse.classList.add('hidden');
                        matchIconTrue.classList.remove('hidden');
                    } else {
                        matchIconFalse.classList.remove('hidden');
                        matchIconTrue.classList.add('hidden');
                    }
                });
            });
        });
    </script>
</div>