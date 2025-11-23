@php
    $user = $userDetails['user'];
    $previous = $userDetails['previous'];
    $upcoming = $userDetails['upcoming'];
@endphp

<li {{ $attributes->merge(['class' => '']) }}>
    <div class="flex justify-between mb-4">
        <div>
            <h3 @class(['font-bold text-xl max-md:text-base mb-1', 'text-slate-500' => $user->deleted_at])>
                <a href="{{ route('customers.show',$user) }}" class="hover:text-[#0018d5] transition-all">
                    {{ $user->getFullName() . $user->isDeleted() }}
                </a>
            </h3>
            <p class="text-slate-500">
                Email: <a href="mailto:{{ $user->email }}" class="text-blue-700 hover:underline">{{ $user->email }}</a>
            </p>
            <p class="text-slate-500">
                Tel: <a href="tel:{{ $user->tel_number }}" class="text-blue-700 hover:underline">{{ $user->tel_number }}</a>
            </p>
        </div>
        <x-link-button link="{{ route('customers.show',$user) }}" role="show" :maxHeightFit="true">
            <span class="max-sm:hidden">Details</span>
        </x-link-button>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <x-appointment-button :appointment="$previous" type="latest" />
        <x-appointment-button :appointment="$upcoming" type="next" />
    </div>
</li>