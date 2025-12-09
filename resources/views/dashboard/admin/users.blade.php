<x-dashboard-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-gray-800" data-aos="fade-right">{{ __('User Management') }}</h2>
    </x-slot>

    <div class="bg-white p-4 rounded-lg shadow-sm mb-6 flex flex-col md:flex-row gap-4 justify-between" 
         data-aos="fade-down" data-aos-delay="100">
        <form action="{{ route('admin.users') }}" method="GET" class="flex w-full md:w-auto gap-2">
            <div class="relative w-full md:w-64">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name or email..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-hiyoucan-500 focus:border-hiyoucan-500 text-sm transition-all focus:ring-2">
                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            
            <select name="role" class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-hiyoucan-500 focus:border-hiyoucan-500 bg-white cursor-pointer hover:border-hiyoucan-500 transition">
                <option value="all">All Roles</option>
                <option value="seller" {{ request('role') == 'seller' ? 'selected' : '' }}>Seller</option>
                <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>Buyer</option>
            </select>
            
            <button type="submit" class="bg-hiyoucan-700 text-white px-4 py-2 rounded-lg hover:bg-hiyoucan-800 text-sm font-bold transition transform hover:scale-105 shadow-md">Filter</button>
        </form>
    </div>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100" 
         data-aos="fade-up" data-aos-delay="200">
        
        @if(session('success'))
            <div class="bg-green-50 text-green-700 p-4 border-b border-green-100 flex items-center gap-2 animate-pulse">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wider border-b border-gray-200">
                        <th class="p-4 font-bold">User Identity</th>
                        <th class="p-4 font-bold">Current Role</th>
                        <th class="p-4 font-bold">Seller Status</th>
                        <th class="p-4 font-bold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-100">
                    @forelse($users as $index => $user)
                    <tr class="hover:bg-gray-50 transition duration-150 ease-in-out" 
                        data-aos="fade-up" data-aos-delay="{{ 300 + ($index * 50) }}">
                        
                        <td class="p-4">
                            <div class="flex items-center gap-3">
                                <div class="h-8 w-8 rounded-full bg-hiyoucan-100 flex items-center justify-center text-hiyoucan-700 font-bold text-xs ring-2 ring-white shadow-sm">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-bold text-gray-900">{{ $user->name }}</p>
                                    <p class="text-gray-500 text-xs">{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>

                        <td class="p-4">
                            @if($user->role == 'seller')
                                <span class="px-2 py-1 rounded-full text-xs font-bold bg-purple-100 text-purple-700 border border-purple-200 shadow-sm">Seller</span>
                            @elseif($user->role == 'user')
                                <span class="px-2 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700 border border-blue-200 shadow-sm">Buyer</span>
                            @else
                                <span class="px-2 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-700 shadow-sm">{{ ucfirst($user->role) }}</span>
                            @endif
                        </td>

                        <td class="p-4">
                            @if($user->role == 'seller')
                                @if($user->email_verified_at)
                                    <span class="text-green-600 font-bold text-xs flex items-center gap-1 bg-green-50 px-2 py-1 rounded-full w-fit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        Active
                                    </span>
                                @else
                                    <span class="text-orange-500 font-bold text-xs flex items-center gap-1 animate-pulse bg-orange-50 px-2 py-1 rounded-full w-fit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        Pending Review
                                    </span>
                                @endif
                            @else
                                <span class="text-gray-300 text-xs">-</span>
                            @endif
                        </td>

                        <td class="p-4 text-right">
                            <div class="flex justify-end items-center gap-2 opacity-80 group-hover:opacity-100 transition-opacity">
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="text-blue-600 hover:text-white bg-blue-50 hover:bg-blue-600 p-1.5 rounded transition shadow-sm transform hover:-translate-y-0.5" title="Edit Info">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </a>

                                @if($user->role == 'seller')
                                    <form action="{{ route('admin.users.verify', $user->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        @if(!$user->email_verified_at)
                                            <input type="hidden" name="action" value="approve">
                                            <button type="submit" class="text-green-600 hover:text-white bg-green-50 hover:bg-green-600 p-1.5 rounded transition shadow-sm transform hover:-translate-y-0.5" title="Approve Seller">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            </button>
                                        @else
                                            <input type="hidden" name="action" value="reject">
                                            <button type="submit" class="text-orange-500 hover:text-white bg-orange-50 hover:bg-orange-500 p-1.5 rounded transition shadow-sm transform hover:-translate-y-0.5" title="Reject/Suspend Seller">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                                            </button>
                                        @endif
                                    </form>
                                @endif

                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this user permanently? This action cannot be undone.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-white bg-red-50 hover:bg-red-600 p-1.5 rounded transition shadow-sm transform hover:-translate-y-0.5" title="Delete User">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="p-8 text-center text-gray-500" data-aos="zoom-in">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-12 h-12 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                <p>No users found matching your criteria.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="mt-6" data-aos="fade-up" data-aos-delay="500">
        {{ $users->links() }}
    </div>

</x-dashboard-layout>