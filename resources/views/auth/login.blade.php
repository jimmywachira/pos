<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="text-center mb-8">
        {{-- You can replace this with your logo --}}
        <h1 class="text-3xl font-bold text-gray-800 tracking-tight">
            Your<span class="text-blue-600">POS</span>
        </h1>
        <p class="text-gray-500 mt-2">Sign in to continue to your dashboard.</p>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div class="mb-4 relative">
            <x-input-label for="email" :value="__('Email')" />
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pt-7 pointer-events-none">
                <ion-icon name="mail-outline" class="text-gray-400"></ion-icon>
            </div>
            <x-text-input id="email" class="block mt-1 w-full pl-10" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="you@example.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mb-4 relative">
            <x-input-label for="password" :value="__('Password')" />
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pt-7 pointer-events-none">
                <ion-icon name="lock-closed-outline" class="text-gray-400"></ion-icon>
            </div>
            <x-text-input id="password" class="block mt-1 w-full pl-10" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between text-sm">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-gray-600">{{ __('Remember me') }}</span>
            </label>

            @if (Route::has('password.request'))
            <a class="font-medium text-blue-600 hover:text-blue-500" href="{{ route('password.request') }}">
                {{ __('Forgot your password?') }}
            </a>
            @endif
        </div>

        <div class="mt-6">
            <x-primary-button class="w-full justify-center py-3 text-sm font-semibold">
                {{ __('Log in') }}
            </x-primary-button>
        </div>

        <div class="relative my-6">
            <div class="absolute inset-0 flex items-center" aria-hidden="true">
                <div class="w-full border-t border-gray-300"></div>
            </div>
            <div class="relative flex justify-center text-sm">
                <span class="bg-white px-2 text-gray-500">Or continue with</span>
            </div>
        </div>

        <div>
            <a href="#" class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                <span class="sr-only">Sign in with Google</span>
                <ion-icon name="logo-google" class="text-xl"></ion-icon>
            </a>
        </div>

        <div class="text-center mt-8">
            <p class="text-sm text-gray-600">
                {{ __("Don't have an account?") }}
                <a href="{{ route('register') }}" class="font-medium text-blue-600 hover:text-blue-500">
                    {{ __('Register here') }}
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>
