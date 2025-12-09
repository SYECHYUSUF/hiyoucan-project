<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register - Hiyoucan</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-gray-900 bg-white">
    <div class="min-h-screen flex">
        
        <div class="flex-1 flex flex-col justify-center py-12 px-4 sm:px-6 lg:flex-none lg:px-20 xl:px-24 bg-white w-full lg:w-[40%]">
            <div class="mx-auto w-full max-w-sm lg:w-96">
                <div>
                    <a href="/" class="text-3xl font-bold text-hiyoucan-900 tracking-widest uppercase">Hiyoucan.</a>
                    <h2 class="mt-6 text-2xl font-bold text-gray-900">Create an account</h2>
                    <p class="mt-2 text-sm text-gray-600">Start your journey with us today.</p>
                </div>

                <div class="mt-8">
                    <form method="POST" action="{{ route('register') }}" class="space-y-5">
                        @csrf

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Full Name</label>
                            <input type="text" name="name" value="{{ old('name') }}" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-hiyoucan-500 focus:border-hiyoucan-500 sm:text-sm">
                            <x-input-error :messages="$errors->get('name')" class="mt-1" />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Email address</label>
                            <input type="email" name="email" value="{{ old('email') }}" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-hiyoucan-500 focus:border-hiyoucan-500 sm:text-sm">
                            <x-input-error :messages="$errors->get('email')" class="mt-1" />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Password</label>
                            <input type="password" name="password" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-hiyoucan-500 focus:border-hiyoucan-500 sm:text-sm">
                            <x-input-error :messages="$errors->get('password')" class="mt-1" />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Confirm Password</label>
                            <input type="password" name="password_confirmation" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-hiyoucan-500 focus:border-hiyoucan-500 sm:text-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">I want to join as:</label>
                            <div class="grid grid-cols-2 gap-3">
                                <label class="border rounded-lg p-3 cursor-pointer hover:bg-gray-50 flex items-center justify-center gap-2 has-[:checked]:border-hiyoucan-500 has-[:checked]:bg-hiyoucan-50 transition">
                                    <input type="radio" name="role" value="buyer" class="text-hiyoucan-600 focus:ring-hiyoucan-500" checked>
                                    <span class="text-sm font-medium">Buyer</span>
                                </label>
                                <label class="border rounded-lg p-3 cursor-pointer hover:bg-gray-50 flex items-center justify-center gap-2 has-[:checked]:border-hiyoucan-500 has-[:checked]:bg-hiyoucan-50 transition">
                                    <input type="radio" name="role" value="seller" class="text-hiyoucan-600 focus:ring-hiyoucan-500">
                                    <span class="text-sm font-medium">Seller</span>
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-bold text-white bg-hiyoucan-800 hover:bg-hiyoucan-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-hiyoucan-500 transition">
                            Create Account
                        </button>
                    </form>

                    <div class="mt-6 text-center">
                        <p class="text-sm text-gray-600">
                            Already have an account? 
                            <a href="{{ route('login') }}" class="font-medium text-hiyoucan-600 hover:text-hiyoucan-500">Log in</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="hidden lg:block relative w-0 flex-1">
            <img class="absolute inset-0 h-full w-full object-cover" src="https://images.unsplash.com/photo-1616683693504-3ea7e9ad6fec?q=80&w=1000" alt="Register Background">
            <div class="absolute inset-0 bg-hiyoucan-900/20 mix-blend-multiply"></div>
        </div>
    </div>
</body>
</html>