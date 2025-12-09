<x-dashboard-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit User Info</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">
                <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Name</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring-hiyoucan-500 focus:border-hiyoucan-500" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring-hiyoucan-500 focus:border-hiyoucan-500" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">New Password (Optional)</label>
                            <input type="password" name="password" class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring-hiyoucan-500 focus:border-hiyoucan-500">
                            <p class="text-xs text-gray-500">Biarkan kosong jika tidak ingin mengganti password user.</p>
                        </div>

                        <div class="flex justify-end gap-4">
                            <a href="{{ route('admin.users') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-300">Cancel</a>
                            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700">Save Changes</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-dashboard-layout>