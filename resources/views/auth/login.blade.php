<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="text-center mb-8">
        <h2 class="text-3xl font-bold text-gray-900 dark:text-white">{{ __('Welcome Back') }}</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">{{ __('Sign in to manage classroom bookings') }}</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" class="text-gray-700 dark:text-gray-300 font-medium mb-1.5" />
            <x-text-input id="email" class="block w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="{{ __('Enter your email') }}" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <div class="flex items-center justify-between mb-1.5">
                <x-input-label for="password" :value="__('Password')" class="text-gray-700 dark:text-gray-300 font-medium" />
                @if (Route::has('password.request'))
                    <a class="text-sm text-limu-blue dark:text-limu-blue-light hover:underline font-medium transition-colors" href="{{ route('password.request') }}">
                        {{ __('Forgot password?') }}
                    </a>
                @endif
            </div>

            <x-text-input id="password" class="block w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password"
                            placeholder="{{ __('Enter your password') }}" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between pt-2">
            <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 dark:border-gray-600 text-limu-blue shadow-sm focus:ring-limu-blue w-5 h-5 transition-colors cursor-pointer dark:bg-gray-700" name="remember">
                <span class="ms-3 text-sm text-gray-600 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white transition-colors">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="pt-2">
            <x-primary-button>
                {{ __('Log in') }}
            </x-primary-button>
        </div>
        
        @if (Route::has('register'))
            <div class="text-center mt-6">
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    {{ __("Don't have an account?") }}
                    <a class="text-limu-blue dark:text-limu-blue-light hover:underline font-semibold transition-colors ms-1" href="{{ route('register') }}">
                        {{ __('Register here') }}
                    </a>
                </p>
            </div>
        @endif
    </form>
</x-guest-layout>
