<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            
            <div>
                <x-input-label for="name" :value="__('Name')" />
                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="phone_number" :value="__('Phone Number')" />
                <x-text-input id="phone_number" class="block mt-1 w-full" type="text" name="phone_number" :value="old('phone_number')" required />
                <x-input-error :messages="$errors->get('phone_number')" class="mt-2" />
            </div>

            <div class="md:col-span-2">
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="password" :value="__('Password')" />
                <x-text-input id="password" class="block mt-1 w-full"
                                type="password"
                                name="password"
                                required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                <x-text-input id="password_confirmation" class="block mt-1 w-full"
                                type="password"
                                name="password_confirmation" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <div class="md:col-span-2">
                <x-input-label for="address" :value="__('Address')" />
                
                <textarea 
                    id="address" 
                    name="address" 
                    rows="3" 
                    class="block mt-1 w-full border-gray-700 bg-gray-900 text-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-lg shadow-sm placeholder-gray-600 transition duration-200" 
                    required 
                    placeholder="Masukkan alamat lengkap..."
                >{{ old('address') }}</textarea>

                <x-input-error :messages="$errors->get('address')" class="mt-2" />
            </div>
        </div>

        <div class="flex items-center justify-end mt-6">
            <x-primary-button class="w-full justify-center md:w-auto">
                {{ __('Register') }}
            </x-primary-button>
        </div>

        <div class="mt-4 pt-4 border-t border-gray-700 text-center"> <p class="text-sm text-gray-400"> Sudah punya akun? 
            <a href="{{ route('login') }}" class="font-bold text-emerald-400 hover:text-emerald-300 transition duration-150 ease-in-out">
                Masuk di sini
            </a>
        </p>
    </div>
    </form>
</x-guest-layout>