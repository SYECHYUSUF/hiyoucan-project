<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Hiyoucan</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-gray-900 bg-white">
    <div class="min-h-screen flex">
        
        <div class="flex-1 flex flex-col justify-center py-12 px-4 sm:px-6 lg:flex-none lg:px-20 xl:px-24 bg-white w-full lg:w-[40%]">
            <div class="mx-auto w-full max-w-sm lg:w-96">
                <div>
                    <a href="/" class="text-3xl font-bold text-hiyoucan-900 tracking-widest uppercase">Hiyoucan.</a>
                    <h2 class="mt-6 text-2xl font-bold text-gray-900">Welcome back</h2>
                    <p class="mt-2 text-sm text-gray-600">
                        Please enter your details to sign in.
                    </p>
                </div>

                <div class="mt-8">
                    @if(session('status'))
                        <div class="mb-4 text-sm font-medium text-green-600">{{ session('status') }}</div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" class="space-y-6">
                        @csrf

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email address</label>
                            <div class="mt-1">
                                <input id="email" name="email" type="email" autocomplete="email" required 
                                    class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-hiyoucan-500 focus:border-hiyoucan-500 sm:text-sm"
                                    value="{{ old('email') }}">
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                            <div class="mt-1">
                                <input id="password" name="password" type="password" autocomplete="current-password" required 
                                    class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-hiyoucan-500 focus:border-hiyoucan-500 sm:text-sm">
                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <input id="remember_me" name="remember" type="checkbox" class="h-4 w-4 text-hiyoucan-600 focus:ring-hiyoucan-500 border-gray-300 rounded">
                                <label for="remember_me" class="ml-2 block text-sm text-gray-900">Remember me</label>
                            </div>
                            @if (Route::has('password.request'))
                                <div class="text-sm">
                                    <a href="{{ route('password.request') }}" class="font-medium text-hiyoucan-600 hover:text-hiyoucan-500">Forgot password?</a>
                                </div>
                            @endif
                        </div>

                        <div>
                            <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-bold text-white bg-hiyoucan-800 hover:bg-hiyoucan-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-hiyoucan-500 transition">
                                Sign in
                            </button>
                        </div>
                    </form>

                    <div class="mt-6 text-center">
                        <p class="text-sm text-gray-600">
                            Don't have an account? 
                            <a href="{{ route('register') }}" class="font-medium text-hiyoucan-600 hover:text-hiyoucan-500">Sign up for free</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="hidden lg:block relative w-0 flex-1">
            <img class="absolute inset-0 h-full w-full object-cover" src="https://images.unsplash.com/photo-1556228578-0d85b1a4d571?q=80&w=1000" alt="Skincare Background">
            <div class="absolute inset-0 bg-hiyoucan-900/20 mix-blend-multiply"></div>
            <div class="absolute bottom-0 left-0 p-20 text-white">
                <h1 class="text-4xl font-bold mb-4">Natural beauty starts here.</h1>
                <p class="text-lg text-hiyoucan-100">Join thousands of happy customers achieving their glow with Hiyoucan.</p>
            </div>
        </div>
    </div>
</body>
</html>