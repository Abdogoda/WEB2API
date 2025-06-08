@extends('layouts.admin.app')

@section('title', 'Categories')
@section('style')
@endsection

@section('content')
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-3xl font-bold">Category Management</h2>
            @hasPermissionTo(\App\Enums\PermissionsEnum::CREATE_CATEGORY->value)
                <button onclick="openModal('addCategoryModal')" class="bg-blue-500 text-white px-4 py-2 rounded">Add Category <i class="fa fa-plus"></i></button>
            @endhasPermissionTo
        </div>
        
        <div class="bg-gray-800 rounded-lg shadow-md p-4">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr>
                        <th class="p-3 border-b border-gray-600">#</th>
                        <th class="p-3 border-b border-gray-600">Image</th>
                        <th class="p-3 border-b border-gray-600">Name</th>
                        <th class="p-3 border-b border-gray-600">Products</th>
                        <th class="p-3 border-b border-gray-600">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categories as $category)
                        <tr class="hover:bg-gray-700">
                            <td class="p-3 border-b border-gray-700">{{$loop->iteration}}</td>
                            <td class="p-3 border-b border-gray-700">
                                @if ($category->image)
                                    <img src="{{ asset('storage/' . $category->image) }}" class="w-12 h-12 rounded" alt="Category Image">
                                @else
                                    <span class="text-gray-500">No Image</span>
                                @endif
                            </td>
                            <td class="p-3 border-b border-gray-700">{{$category->name}}</td>
                            <td class="p-3 border-b border-gray-700">{{$category->products_count}}</td>
                            <td class="p-3 border-b border-gray-700">
                                @hasPermissionTo(\App\Enums\PermissionsEnum::VIEW_CATEGORY->value)
                                    <a href="{{ route('admin.categories.show', $category->slug) }}" class="bg-blue-500 text-white px-3 py-1 rounded">View</a>
                                @endhasPermissionTo
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Category Modal -->
    <div id="addCategoryModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center">
        <div class="bg-gray-800 p-6 rounded shadow-md w-96">
            <h3 class="text-xl font-bold mb-4">Add Category</h3>
            <form action="" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="text" name="name" value="{{old('name')}}" placeholder="Category Name" class="w-full p-3 rounded bg-gray-700 text-gray-100 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 mb-4">
                <input type="file" name="image" placeholder="Category Image" class="w-full p-3 rounded bg-gray-700 text-gray-100 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 mb-4">
                <textarea name="description" placeholder="Category Description" class="w-full p-3 rounded bg-gray-700 text-gray-100 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 mb-4"></textarea>
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeModal('addCategoryModal')" class="bg-gray-500 text-white px-4 py-2 rounded">Cancel</button>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Save</button>
                </div>
            </form>
        </div>
    </div>

    
@endsection

@section('script')
@endsection