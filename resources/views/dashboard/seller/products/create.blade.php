<x-dashboard-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Add New Product') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">
                <form action="{{ route('seller.products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 gap-6">
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Product Name</label>
                            <input type="text" name="name" class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring-hiyoucan-500 focus:border-hiyoucan-500" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Category</label>
                            <select name="category_id" class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring-hiyoucan-500 focus:border-hiyoucan-500">
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Price (Rp)</label>
                                <input type="number" name="price" class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring-hiyoucan-500 focus:border-hiyoucan-500" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Stock</label>
                                <input type="number" name="stock" class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring-hiyoucan-500 focus:border-hiyoucan-500" required>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Product Image</label>
                            <input type="file" name="image" class="mt-1 w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-hiyoucan-50 file:text-hiyoucan-700 hover:file:bg-hiyoucan-100" required>
                            <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG. Max size: 2MB.</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" rows="4" class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring-hiyoucan-500 focus:border-hiyoucan-500" required></textarea>
                        </div>

                        <button type="submit" class="w-full bg-hiyoucan-700 text-white py-3 rounded-md font-bold hover:bg-hiyoucan-800">Save Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-dashboard-layout>