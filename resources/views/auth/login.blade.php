<x-guest-layout>
    <div class="relative">
        <div class="absolute -top-6 -right-2">
            <a href="{{ route('register') }}" class="text-xs font-bold uppercase tracking-widest text-white/50 hover:text-white transition-colors bg-white/10 px-3 py-1 rounded-full border border-white/10">
                Register
            </a>
        </div>

        <x-auth-session-status class="mb-4" :status="session('status')" />

        <div class="mb-8">
            <h2 class="text-2xl font-bold text-white">Welcome Back</h2>
            <p class="text-white/60 text-sm">Please enter your details to sign in.</p>
        </div>

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <div>
                <x-input-label for="email" :value="__('Email')" class="text-white/80" />
                <x-text-input id="email" 
                    class="block mt-1 w-full bg-white/5 border-white/10 text-white focus:ring-indigo-500 py-3" 
                    type="email" name="email" :value="old('email')" 
                    required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-1" />
            </div>

            <div>
                <x-input-label for="password" :value="__('Password')" class="text-white/80" />
                <x-text-input id="password" 
                    class="block mt-1 w-full bg-white/5 border-white/10 text-white focus:ring-indigo-500 py-3"
                    type="password"
                    name="password"
                    required autocomplete="current-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-1" />
            </div>

            <div class="flex items-center justify-between">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" class="rounded border-white/20 bg-white/5 text-indigo-500 focus:ring-indigo-500" name="remember">
                    <span class="ms-2 text-sm text-white/60">{{ __('Remember me') }}</span>
                </label>

                @if (Route::has('password.request'))
                    <a class="text-sm text-indigo-300 hover:text-indigo-200" href="{{ route('password.request') }}">
                        {{ __('Forgot password?') }}
                    </a>
                @endif
            </div>

            <div class="pt-4">
                <x-primary-button class="w-full justify-center py-3 bg-white text-indigo-900 hover:bg-indigo-50">
                    {{ __('Log in') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>