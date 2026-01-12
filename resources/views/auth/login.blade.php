<x-layouts.app>
    <div class="min-h-screen flex bg-gray-50">
        <!-- Left Side: Branding & Info -->
        <div class="hidden lg:flex w-1/2 bg-blue-600 relative overflow-hidden items-center justify-center">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-600 to-indigo-900 opacity-90"></div>
            <div class="absolute -top-24 -left-24 w-96 h-96 rounded-full bg-white opacity-10 blur-3xl"></div>
            <div class="absolute -bottom-24 -right-24 w-96 h-96 rounded-full bg-white opacity-10 blur-3xl"></div>

            <div class="relative z-10 text-white p-12 max-w-lg text-center">
                <h2 class="text-4xl font-bold mb-6">Welcome Back!</h2>
                <p class="text-lg text-blue-100 mb-8">Access your dashboard and manage your business efficiently.</p>
                <div class="flex justify-center space-x-4">
                    <div class="flex -space-x-2 overflow-hidden">
                        <img class="inline-block h-10 w-10 rounded-full" src="https://ui-avatars.com/api/?name=User+1&background=random" alt="" />
                        <img class="inline-block h-10 w-10 rounded-full" src="https://ui-avatars.com/api/?name=User+2&background=random" alt="" />
                        <img class="inline-block h-10 w-10 rounded-full" src="https://ui-avatars.com/api/?name=User+3&background=random" alt="" />
                        <img class="inline-block h-10 w-10 rounded-full" src="https://ui-avatars.com/api/?name=User+4&background=random" alt="" />
                    </div>
                    <div class="flex items-center text-sm">
                        <span>+2k happy users</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side: Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-6 sm:p-12 bg-white">
            <div class="w-full max-w-md space-y-8">
                <div class="text-center">
                    <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Sign in to your account</h2>
                    <p class="mt-2 text-sm text-gray-600">
                        Or
                        <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-500 transition-colors">
                            create a new account
                        </a>
                    </p>
                </div>

                <!-- Social Logins -->
                <div class="mt-6 grid grid-cols-2 gap-3">
                    <a href="#" class="w-full inline-flex justify-center py-2.5 px-4 border border-gray-300 rounded-lg shadow-sm bg-white text-sm text-gray-500 hover:bg-gray-50 transition duration-150">
                        <span class="sr-only">Sign in with Google</span>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M12.48 10.92v3.28h7.84c-.24 1.84-.853 3.187-1.787 4.133-1.147 1.147-2.933 2.4-6.053 2.4-4.827 0-8.6-3.893-8.6-8.72s3.773-8.72 8.6-8.72c2.6 0 4.507 1.027 5.907 2.347l2.307-2.307C18.747 1.44 16.133 0 12.48 0 5.867 0 .307 5.387.307 12s5.56 12 12.173 12c3.573 0 6.267-1.173 8.373-3.36 2.16-2.16 2.84-5.213 2.84-7.667 0-.76-.053-1.467-.173-2.053H12.48z" /></svg>
                    </a>
                    <a href="#" class="w-full inline-flex justify-center py-2.5 px-4 border border-gray-300 rounded-lg shadow-sm bg-white text-sm text-gray-500 hover:bg-gray-50 transition duration-150">
                        <span class="sr-only">Sign in with GitHub</span>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                            <path fill-rule="evenodd" d="M10 0C4.477 0 0 4.484 0 10.017c0 4.484 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0110 4.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0020 10.017C20 4.484 15.522 0 10 0z" clip-rule="evenodd" /></svg>
                    </a>
                </div>

                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white text-gray-500">Or continue with email</span>
                    </div>
                </div>

                <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-6">
                    @csrf

                    <div class="space-y-5">
                        <div>
                            <label for="email" class="block text-sm text-gray-700">Email Address</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                    </svg>
                                </div>
                                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="you@example.com">
                            </div>
                            @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div x-data="{ show: false }">
                            <label for="password" class="block text-sm text-gray-700">Password</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <input type="password" :type="show ? 'text' : 'password'" id="password" name="password" required class="block w-full pl-10 pr-10 py-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="••••••••">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <button type="button" @click="show = !show" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                                        <svg x-show="!show" class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        <svg x-show="show" class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.059 10.059 0 013.999-5.42m3.714-2.172a9.99 9.99 0 013.829-.408c4.477 0 8.268 2.943 9.542 7a10.05 10.05 0 01-2.004 3.664m-1.31 2.98l-14.14-14.14" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <input id="remember_me" type="checkbox" name="remember" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="remember_me" class="ml-2 block text-sm text-gray-900">Remember Me</label>
                            </div>

                            <div class="text-sm">
                                <a href="{{ route('password.request') }}" class="text-blue-600 hover:text-blue-500">Forgot your password?</a>
                            </div>
                        </div>
                    </div>

                    <div>
                        <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out transform hover:-translate-y-0.5">
                            Log in
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>
