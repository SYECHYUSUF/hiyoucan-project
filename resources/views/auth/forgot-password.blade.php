<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Forgot Password - Hiyoucan</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-gray-900 bg-white">
    <div class="min-h-screen flex">
        
        <div class="flex-1 flex flex-col justify-center py-12 px-4 sm:px-6 lg:flex-none lg:px-20 xl:px-24 bg-white w-full lg:w-[40%]">
            <div class="mx-auto w-full max-w-sm lg:w-96">
                <div>
                    <a href="/" class="text-3xl font-bold text-hiyoucan-900 tracking-widest uppercase">Hiyoucan.</a>
                    <h2 class="mt-6 text-2xl font-bold text-gray-900">Reset Password</h2>
                    <p class="mt-2 text-sm text-gray-600 leading-relaxed">
                        Lupa kata sandi? Tidak masalah. Cukup beri tahu kami alamat email Anda dan kami akan mengirimkan tautan pengaturan ulang kata sandi.
                    </p>
                </div>

                <div class="mt-8">
                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                        @csrf

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email address</label>
                            <div class="mt-1">
                                <input id="email" name="email" type="email" :value="old('email')" required autofocus 
                                    class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-hiyoucan-500 focus:border-hiyoucan-500 sm:text-sm">
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>
                        </div>

                        <div>
                            <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-bold text-white bg-hiyoucan-800 hover:bg-hiyoucan-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-hiyoucan-500 transition">
                                Send Password Reset Link
                            </button>
                        </div>
                    </form>

                    <div class="mt-6 text-center">
                        <a href="{{ route('login') }}" class="font-medium text-sm text-hiyoucan-600 hover:text-hiyoucan-500 flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                            Back to Login
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="hidden lg:block relative w-0 flex-1">
            <img class="absolute inset-0 h-full w-full object-cover" src="https://images.unsplash.com/photo-1552693673-1bf958298935?q=80&w=1000&auto=format&fit=crop" alt="Nature Background">
            <div class="absolute inset-0 bg-hiyoucan-900/30 mix-blend-multiply"></div>
            <div class="absolute bottom-0 left-0 p-20 text-white">
                <h1 class="text-4xl font-bold mb-4">Secure & Simple.</h1>
                <p class="text-lg text-hiyoucan-100">We help you get back to your beauty journey safely.</p>
            </div>
        </div>
    </div>
</body>
</html>