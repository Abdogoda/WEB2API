@extends('layouts.admin.app')

@section('title', 'Category Details')

@section('content')
    <h2 class="text-3xl font-bold text-white mb-4">Category Details</h2>
    <div class="bg-gray-800 p-6 rounded-lg shadow-md">
        <div class="flex space-x-6 mb-6">
            @if ($category->image)
                <img src="{{ asset('storage/' . $category->image) }}" class="w-32 h-32 p-3 object-cover rounded-lg border border-gray-600" alt="Category Image">
            @else
                <span class="text-gray-500">No Image</span>
            @endif
            <div>
                <h1 class="text-xl font-bold text-white mb-3">{{ $category->name }}</h1>
                <p class="text-gray-400 mt-2">Total Products: {{ count($category->products) }}</p>
                <p class="text-gray-300 mt-2">{{ $category->description }}</p>
                <div class="mt-4">
                    @hasPermissionTo(\App\Enums\PermissionsEnum::UPDATE_CATEGORY->value)
                        <button onclick="openModal('editCategoryModal')" class="bg-yellow-500 text-white px-3 py-1 rounded">Edit</button>
                    @endhasPermissionTo
                    @hasPermissionTo(\App\Enums\PermissionsEnum::DELETE_CATEGORY->value)
                        <button onclick="openModal('deleteCategoryModal')" class="bg-red-500 text-white px-3 py-1 rounded">Delete</button>
                    @endhasPermissionTo
                </div>
            </div>
        </div>
      @if ($category->products->count() > 0)
        <hr class="border-gray-600 my-4">
        <h3 class="text-2xl font-bold text-white mb-4">Category's Products</h3>
        <div class="bg-gray-700 rounded-lg p-4">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr>
                        <th class="p-3 border-b border-gray-600">ID</th>
                        <th class="p-3 border-b border-gray-600">Image</th>
                        <th class="p-3 border-b border-gray-600">Name</th>
                        <th class="p-3 border-b border-gray-600">Price</th>
                        <th class="p-3 border-b border-gray-600">Stock</th>   
                        <th class="p-3 border-b border-gray-600">Action</th>   
                    </tr>
                </thead>

                <tbody>
                    @foreach ($category->products as $product)
                        <tr class="hover:bg-gray-700">
                            <td class="p-3 border-b border-gray-700">{{ $loop->iteration }}</td>
                            <td class="p-3 border-b border-gray-700">
                                @if ($product->images->where('is_primary', true)->first())
                                    <img src="{{ asset('storage/' . $product->primaryImage()->path)}}" class="w-12 h-12 rounded" alt="Product Image">
                                @else
                                    <span class="text-gray-500">No Image</span>
                                @endif
                            </td>

                            <td class="p-3 border-b border-gray-700">{{ $product->name }}</td>
                            <td class="p-3 border-b border-gray-700">${{ $product->price }}</td>
                            <td class="p-3 border-b border-gray-700">{{ $product->stock }}</td>
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
          
      @endif
    </div>


    @hasPermissionTo(\App\Enums\PermissionsEnum::UPDATE_CATEGORY->value)
        <!-- Edit Category Modal -->
        <div id="editCategoryModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center">
            <div class="bg-gray-800 p-6 rounded shadow-md" style="width: 700px; max-width:100%">
                <h3 class="text-xl font-bold mb-4">Edit Category</h3>
                <form action="{{ route('admin.categories.update', $category->slug) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <label for="editImageInput">
                        <img id="editCategoryImage" src="{{ $category->image ? asset('storage/' . $category->image) : null }}" alt="Category Image" class="w-24 h-24 rounded mb-4">
                        <input type="file" id="editImageInput" name="image" placeholder="Category Image" style="display: none;">
                    </label>
                    <input type="text" id="editCategoryName" name="name" value="{{ $category->name }}" placeholder="Category Name" class="w-full p-3 rounded bg-gray-700 text-gray-100 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 mb-4">
                    <textarea id="editCategoryDescription" name="description" placeholder="Category Description" class="w-full p-3 rounded bg-gray-700 text-gray-100 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 mb-4">{{ $category->description }}</textarea>
                    
                    <div class="flex justify-end space-x-2">
                        <button type="button" onclick="closeModal('editCategoryModal')" class="bg-gray-500 text-white px-4 py-2 rounded">Cancel</button>
                        <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded">Update</button>
                    </div>
                </form>
            </div>
        </div>
    @endhasPermissionTo

    @hasPermissionTo(\App\Enums\PermissionsEnum::DELETE_CATEGORY->value)
        <!-- Delete Confirmation Modal -->
        <div id="deleteCategoryModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center">
            <div class="bg-gray-800 p-6 rounded shadow-md w-96">
                <h3 class="text-xl font-bold mb-4">Delete Category?</h3>
                <p>Are you sure you want to delete this category?</p>
                <form id="deleteCategoryForm" action="{{ route('admin.categories.destroy', $category->slug) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="flex justify-end space-x-2 mt-4">
                        <button type="button" onclick="closeModal('deleteCategoryModal')" class="bg-gray-500 text-white px-4 py-2 rounded">Cancel</button>
                        <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    @endhasPermissionTo
@endsection
