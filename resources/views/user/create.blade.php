<x-user-layout title="Create an Account - ">
    <x-card class="p-8 mb-8">
        <h1 class=" font-bold text-2xl text-center mb-8">Create a New Account</h1>
        <div class="m-4">
            <form action="{{route('user.store')}}" method="POST">
                @csrf

                <div class=" mb-4 grid grid-cols-2 gap-4">
                    <div>
                        <x-label for="first_name">First name *</x-label>
                        <x-input-field name="first_name" id="first_name" value="{{old('first_name')}}" placeholder="John" class="w-full"/>
                        @error('first_name')
                            <p class=" text-red-500">{{$message}}</p>
                        @enderror
                    </div>
                    <div>
                        <x-label for="last_name">Last name *</x-label>
                        <x-input-field name="last_name" id="last_name" value="{{old('last_name')}}" placeholder="Example" class="w-full"/>
                        @error('last_name')
                            <p class=" text-red-500">{{$message}}</p>
                        @enderror
                    </div>
                    
                </div>

                <div class="mb-4 grid grid-cols-2 gap-4">
                    <div>
                        <x-label for="date_of_birth">Date of birth *</x-label>
                        <x-input-field type="date" name="date_of_birth" id="date_of_birth" value="{{old('date_of_birth')}}" class="w-full"/>
                        @error('date_of_birth')
                            <p class=" text-red-500">{{$message}}</p>
                        @enderror
                    </div>
                    <div>
                        <x-label for="telephone_number">Telephone number *</x-label>
                        <x-input-field type="tel" name="telephone_number" id="telephone_number" value="{{old('telephone_number')}}" placeholder="+36123456789" class="w-full"/>
                        @error('telephone_number')
                            <p class=" text-red-500">{{$message}}</p>
                        @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <x-label for="email">Email *</x-label>
                    <x-input-field type="email" name="email" id="email" value="{{old('email')}}" placeholder="john@example.com" class="w-full"/>
                    @error('email')
                        <p class=" text-red-500">{{$message}}</p>
                    @enderror
                </div>
                
                <div class="flex gap-4 mb-8 max-md:flex-col">
                    <div class="flex-grow">
                        <div class="mb-4">
                            <x-label for="password">Password *</x-label>
                            <x-input-field type="password" name="password" id="password" class="w-full"/>
                            @error('password')
                                <p class=" text-red-500">{{$message}}</p>
                            @enderror
                        </div>

                        <div>
                            <x-label for="password_confirmation">Confirm your password *</x-label>
                            <x-input-field type="password" name="password_confirmation" id="password_confirmation" class="w-full"/>
                            @error('password_confirmation')
                                <p class=" text-red-500">{{$message}}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="flex-grow-0">
                        <span class="font-medium text-base">Your password must contain</span>
                        <ul class="list-disc *:ml-6 mb-4">
                            <li>
                                at least one <span class="font-medium">undercase letter</span>
                            </li>
                            <li>
                                at least one <span class="font-medium">uppercase letter</span>
                            </li>
                            <li>
                                at least one <span class="font-medium">number</span>
                            </li>
                            <li>
                                and be at least <span class="font-medium">8 characters long</span>
                            </li>
                        </ul>
                        Fields marked with * are <span class="font-medium">required</span>
                    </div>
                </div>

                <x-button role="loginMain" :full="true">Register</x-button>
            </form>

            <p class="text-center font-medium mt-2">
                Already have an account?
                <a href="{{ route('login') }}" class=" text-blue-700 hover:underline">
                    Sign in here!
                </a>
            </p>
        </div>
        
    </x-card>
</x-user-layout>