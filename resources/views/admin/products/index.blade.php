@extends('layouts.admin.app')

@section('title', 'Products')
@section('style')
@endsection

@section('content')
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-3xl font-bold">Product Management</h2>
            @hasPermissionTo(\App\Enums\PermissionsEnum::CREATE_CATEGORY->value)
                <button onclick="openModal('addProductModal')" class="bg-blue-500 text-white px-4 py-2 rounded">Add Product <i class="fa fa-plus"></i></button>
            @endhasPermissionTo
        </div>
        
        <div class="bg-gray-800 rounded-lg shadow-md p-4">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr>
                        <th class="p-3 border-b border-gray-600">#</th>
                        <th class="p-3 border-b border-gray-600">Image</th>
                        <th class="p-3 border-b border-gray-600">Name</th>
                        <th class="p-3 border-b border-gray-600">Category</th>
                        <th class="p-3 border-b border-gray-600">Price</th>
                        <th class="p-3 border-b border-gray-600">Stock</th>
                        <th class="p-3 border-b border-gray-600">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                        <tr class="hover:bg-gray-700">
                            <td class="p-3 border-b border-gray-700">{{$loop->iteration}}</td>
                            <td class="p-3 border-b border-gray-700">
                                @if ($product->images->count() > 0)
                                    <img src="{{ asset('storage/' . $product->primaryImage()->path) }}" class="w-12 h-12 rounded" alt="Product Image">
                                @else
                                    <span class="text-gray-500">No Image</span>
                                @endif
                            </td>
                            <td class="p-3 border-b border-gray-700">{{$product->name}}</td>
                            <td class="p-3 border-b border-gray-700"><a href="{{ route('admin.categories.show', $product->category->slug) }}">{{$product->category->name}}</a></td>
                            <td class="p-3 border-b border-gray-700">${{$product->price}}</td>
                            <td class="p-3 border-b border-gray-700 text-{{$product->stock < 5 ? 'red' : 'green'}}-500">{{$product->stock}}</td>
                            <td class="p-3 border-b border-gray-700">
                                @hasPermissionTo(\App\Enums\PermissionsEnum::VIEW_PRODUCT->value)
                                    <a href="{{ route('admin.products.show', $product->slug) }}" class="bg-blue-500 text-white px-3 py-1 rounded">View</a>
                                @endhasPermissionTo
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Product Modal -->
    <div id="addProductModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center"> 
        <div class="bg-gray-800 p-6 rounded shadow-md w-[500px]">
            <h3 class="text-xl font-bold mb-4 text-white">Add Product</h3>
            <form action="" method="POST" enctype="multipart/form-data">
                @csrf

                <label class="block text-gray-400 mb-1">Product Name</label>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="Enter product name" class="w-full p-3 rounded bg-gray-700 text-gray-100 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 mb-4">
                <div class="flex space-x-2 mb-4">
                    <div class="w-1/2">
                        <label class="block text-gray-400 mb-1">Product Price</label>
                        <input type="number" min="1" name="price" value="{{ old('price') }}" placeholder="Price" class="w-full p-3 rounded bg-gray-700 text-gray-100 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="w-1/2">
                        <label class="block text-gray-400 mb-1">Product Stock</label>
                        <input type="number" min="1" name="stock" value="{{ old('stock') }}" placeholder="Stock" class="w-full p-3 rounded bg-gray-700 text-gray-100 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                
                <div class="flex space-x-2 mb-4">
                    <div class="w-1/2">
                        <label class="block text-gray-400 mb-1">Product Category</label>
                        <select name="category_id" class="w-full p-3 rounded bg-gray-700 text-gray-100 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Select Category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="w-1/2">
                        <label class="block text-gray-400 mb-1">Product Images</label>
                        <input type="file" name="images[]" multiple class="w-full p-2 rounded bg-gray-700 text-gray-100 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <label class="block text-gray-400 mb-1">Product Description</label>
                <textarea name="description" placeholder="Product Description" class="w-full p-3 rounded bg-gray-700 text-gray-100 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 mb-4"></textarea>

                <div class="flex items-center space-x-4 mb-4">
                    <label class="flex items-center space-x-2 text-gray-400">
                        <input type="checkbox" checked name="active" class="w-5 h-5 text-blue-500 bg-gray-700 border border-gray-600 rounded focus:ring-2 focus:ring-blue-500">
                        <span>Active</span>
                    </label>
                    <label class="flex items-center space-x-2 text-gray-400">
                        <input type="checkbox" name="featured" class="w-5 h-5 text-blue-500 bg-gray-700 border border-gray-600 rounded focus:ring-2 focus:ring-blue-500">
                        <span>Featured</span>
                    </label>
                </div>

                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeModal('addProductModal')" class="bg-gray-500 text-white px-4 py-2 rounded">Cancel</button>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Save</button>
                </div>
            </form>
        </div>
    </div>

@endsection

@section('script')
@endsection