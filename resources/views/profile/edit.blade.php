<x-public-layout>
    <div class="pt-32 pb-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-10 text-center" data-aos="fade-down">
            <h2 class="text-3xl font-bold text-hiyoucan-900">Profile Settings</h2>
            <p class="text-gray-500 mt-2">Manage your account information and security.</p>
        </div>

        <div class="space-y-8 max-w-4xl mx-auto">
            
            <div class="p-8 bg-white shadow-sm rounded-2xl border border-gray-100" data-aos="fade-up">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-8 bg-white shadow-sm rounded-2xl border border-gray-100" data-aos="fade-up" data-aos-delay="100">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-8 bg-white shadow-sm rounded-2xl border border-red-100" data-aos="fade-up" data-aos-delay="200">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-public-layout>