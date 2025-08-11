@php
    $user = $userDetails['user'];
    $previous = $userDetails['previous'];
    $upcoming = $userDetails['upcoming'];
@endphp

<li {{ $attributes->merge(['class' => '']) }}>
    <div class="flex justify-between mb-4">
        <div>
            <h3 class="font-bold text-xl mb-1">
                <a href="{{ route('customers.show',$user) }}" class="hover:text-[#0018d5] transition-all">
                    {{ $user->first_name . ' ' . $user->last_name }}
                </a>
            </h3>
            <p class="text-slate-500">Email: {{ $user->email }}</p>
            <p class="text-slate-500">Tel: {{ $user->tel_number }}</p>
        </div>
        <x-link-button link="{{ route('customers.show',$user) }}" role="show">Details</x-link-button>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <x-appointment-button :appointment="$previous" type="latest" />
        <x-appointment-button :appointment="$upcoming" type="next" />
    </div>
</li>