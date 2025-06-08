@extends('layouts.admin.app')

@section('title', 'Product Details')

@section('content')
    <h2 class="text-3xl font-bold text-white mb-4">Product Details</h2>
    <div class="bg-gray-800 p-6 rounded-lg shadow-md">
        <div class="flex space-x-6 mb-6">
            <div class="">
                @if ($product->images->count() > 0)
                    <div class="w-80 h-80">
                        <img id="mainImage" src="{{ asset('storage/' . $product->primaryImage()->path) }}" 
                            class="w-full h-full object-cover rounded-lg border border-gray-600" alt="Product Image">
                    </div>

                    <div class="flex space-x-2 mt-2">
                        @foreach ($product->images as $image)
                            <div class="relative flex-1 group">

                                <img src="{{ asset('storage/' . $image->path) }}" 
                                    class="w-full h-20 object-cover rounded-lg border border-gray-600 cursor-pointer hover:opacity-80"
                                    onclick="changeMainImage(this)">

                                <button onclick="confirmDelete('{{ route('admin.products.images.destroy', $image) }}')"
                                        title="Delete Image"
                                        class="absolute top-1 right-1 bg-red-600 text-white w-5 h-5 flex justify-center items-center rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    &times;
                                </button>

                                @if (!$image->is_primary)
                                    <button onclick="confirmSetPrimary('{{ route('admin.products.images.primary', $image) }}')"
                                            title="Set as Primary"
                                            class="absolute top-1 left-1 bg-green-500 text-white w-5 h-5 flex justify-center items-center rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                        ✓
                                    </button>
                                @endif
                            </div>
                        @endforeach
                        @if ($product->images->count() < 5)
                            <div class="flex-1">
                                <div class="w-full h-20 border-2 border-dashed border-gray-500 rounded-lg flex justify-center items-center cursor-pointer hover:bg-gray-700"
                                    onclick="openModal('uploadImageModal')">
                                    <span class="text-white text-2xl font-bold">+</span>
                                </div>
                            </div>
                        @endif
                    </div>
                @else
                    <span class="text-gray-500">No Images Available</span>
                @endif
            </div>

            <div class="">
                <h1 class="text-xl font-bold text-white mb-3">{{ $product->name }}</h1>
                <p class="text-gray-400 mt-2">Category: <a href="{{ route('admin.categories.show', $product->category) }}">{{ $product->category->name }}</a></p>
                <p class="text-gray-400 mt-2">Price: ${{ $product->price }}</p>
                <p class="text-gray-400 mt-2">Stock: <span class="text-{{ $product->stock < 5 ? 'red' : 'green' }}-500">{{ $product->stock == 0 ? 'Out of stock' : $product->stock }}</span></p>
                @if ($product->featured)
                    <span class="px-2 py-1 text-xs font-semibold text-white bg-blue-500 rounded-full mr-2 my-2 inline-block">Featured</span>
                @endif
                <span class="bg-{{ $product->active ? 'green' : 'red' }}-500 px-2 py-1 text-xs font-semibold text-white rounded-full mr-2 my-2 inline-block">{{ $product->active ? 'Active' : 'Inactive' }}</span>
                <p class="text-gray-300">{{ $product->description }}</p>
                <div class="mt-4">
                    @hasPermissionTo(\App\Enums\PermissionsEnum::UPDATE_PRODUCT->value)
                        <button onclick="openModal('editProductModal')" class="bg-yellow-500 text-white px-3 py-1 rounded">Edit</button>
                    @endhasPermissionTo
                    @hasPermissionTo(\App\Enums\PermissionsEnum::DELETE_PRODUCT->value)
                        <button onclick="openModal('deleteProductModal')" class="bg-red-500 text-white px-3 py-1 rounded">Delete</button>
                    @endhasPermissionTo
                </div>
            </div>
        </div>
        
        @if ($simillarProducts->count() > 0)
            <hr class="border-gray-600 my-4">
            <h2 class="text-3xl font-bold text-white mb-4">Simillar Products</h2>
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
                        @foreach ($simillarProducts as $simillarProduct)
                            <tr class="hover:bg-gray-700">
                                <td class="p-3 border-b border-gray-700">{{ $loop->iteration }}</td>
                                <td class="p-3 border-b border-gray-700">
                                    @if ($simillarProduct->images->count() > 0)
                                        <img src="{{ asset('storage/' . $simillarProduct->images->where('is_primary', true)->first()->path) }}" class="w-12 h-12 rounded" alt="Product Image">
                                    @else
                                        <span class="text-gray-500">No Image</span>
                                    @endif
                                </td>

                                <td class="p-3 border-b border-gray-700">{{ $simillarProduct->name }}</td>
                                <td class="p-3 border-b border-gray-700">{{ $simillarProduct->price }}</td>
                                <td class="p-3 border-b border-gray-700">{{ $simillarProduct->stock }}</td>
                                <td class="p-3 border-b border-gray-700">
                                    {{-- @hasPermissionTo(\App\Enums\PermissionsEnum::VIEW_PRODUCT->value) --}}
                                        <a href="{{ route('admin.products.show', $simillarProduct->slug) }}" class="bg-blue-500 text-white px-3 py-1 rounded">View</a>
                                    {{-- @endhasPermissionTo --}}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    @hasPermissionTo(\App\Enums\PermissionsEnum::DELETE_PRODUCT->value)
        <!-- Delete Confirmation Modal -->
        <div id="deleteProductModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center">
            <div class="bg-gray-800 p-6 rounded shadow-md w-96">
                <h3 class="text-xl font-bold mb-4">Delete Product?</h3>
                <p>Are you sure you want to delete this product?</p>
                <form id="deleteProductForm" action="{{ route('admin.products.destroy', $product->slug) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="flex justify-end space-x-2 mt-4">
                        <button type="button" onclick="closeModal('deleteProductModal')" class="bg-gray-500 text-white px-4 py-2 rounded">Cancel</button>
                        <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    @endhasPermissionTo

    @hasPermissionTo(\App\Enums\PermissionsEnum::UPDATE_PRODUCT->value)
        <!-- Edit Product Modal -->
        <div id="editProductModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center">
            <div class="bg-gray-800 p-6 rounded shadow-md" style="width: 700px; max-width:100%">
                <h3 class="text-xl font-bold mb-4">Edit Product</h3>
                <form action="{{ route('admin.products.update', $product->slug) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="flex space-x-2 mb-4">
                        <div class="w-1/2">
                            <label class="block text-gray-400 mb-1">Product Name</label>
                            <input type="text" name="name" value="{{ $product->name }}" placeholder="Enter product name" class="w-full p-3 rounded bg-gray-700 text-gray-100 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 mb-4">
                        </div>
                        <div class="w-1/2">
                            <label class="block text-gray-400 mb-1">Product Category</label>
                            <select name="category_id" class="w-full p-3 rounded bg-gray-700 text-gray-100 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Select Category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="flex space-x-2 mb-4">
                        <div class="w-1/2">
                            <label class="block text-gray-400 mb-1">Product Price</label>
                            <input type="number" min="1" name="price" value="{{ $product->price }}" placeholder="Price" class="w-full p-3 rounded bg-gray-700 text-gray-100 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div class="w-1/2">
                            <label class="block text-gray-400 mb-1">Product Stock</label>
                            <input type="number" min="1" name="stock" value="{{ $product->stock }}" placeholder="Stock" class="w-full p-3 rounded bg-gray-700 text-gray-100 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>

                    <label class="block text-gray-400 mb-1">Product Description</label>
                    <textarea name="description" placeholder="Product Description" class="w-full p-3 rounded bg-gray-700 text-gray-100 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 mb-4">{{ $product->description }}</textarea>

                    <div class="flex items-center space-x-4 mb-4">
                        <label class="flex items-center space-x-2 text-gray-400">
                            <input type="checkbox" {{ $product->active ? 'checked' : ''}} name="active" class="w-5 h-5 text-blue-500 bg-gray-700 border border-gray-600 rounded focus:ring-2 focus:ring-blue-500">
                            <span>Active</span>
                        </label>
                        <label class="flex items-center space-x-2 text-gray-400">
                            <input type="checkbox" {{ $product->featured ? 'checked' : ''}} name="featured" class="w-5 h-5 text-blue-500 bg-gray-700 border border-gray-600 rounded focus:ring-2 focus:ring-blue-500">
                            <span>Featured</span>
                        </label>
                    </div>

                    <div class="flex justify-end space-x-2">
                        <button type="button" onclick="closeModal('editProductModal')" class="bg-gray-500 text-white px-4 py-2 rounded">Cancel</button>
                        <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded">Update</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Delete Image Confirmation Modal -->
        <div id="deleteImageModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center">
            <div class="bg-gray-800 p-6 rounded shadow-md w-96 text-center">
                <form id="deleteImageForm" method="post">
                    @csrf
                    @method('DELETE')
                    <h3 class="text-xl font-bold text-white">Confirm Deletion</h3>
                    <p class="text-gray-300 my-3">Are you sure you want to delete this image?</p>
                    <div class="flex justify-center space-x-4">
                        <button onclick="closeModal('deleteImageModal')" class="bg-gray-500 text-white px-4 py-2 rounded">Cancel</button>
                        <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded">Delete</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Set Primary Image Confirmation Modal -->
        <div id="primaryImageModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center">
            <div class="bg-gray-800 p-6 rounded shadow-md w-96 text-center">
                <form id="primaryImageForm" method="post">
                    @csrf
                    @method('PUT')
                    <h3 class="text-xl font-bold text-white">Confirm Set Primary</h3>
                    <p class="text-gray-300 my-3">Are you sure you want to set this image as primary?</p>
                    <div class="flex justify-center space-x-4">
                        <button onclick="closeModal('primaryImageModal')" class="bg-gray-500 text-white px-4 py-2 rounded">Cancel</button>
                        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Confirm</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Upload Image Modal -->
        <div id="uploadImageModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center">
            <div class="bg-gray-800 p-6 rounded shadow-md w-96">
                <h3 class="text-xl font-bold text-white mb-4">Upload Images</h3>
                <form action="{{ route('admin.products.images.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="file" name="images[]" multiple class="w-full p-2 rounded bg-gray-700 text-gray-100 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <div class="flex justify-end space-x-2 mt-4">
                        <button type="button" onclick="closeModal('uploadImageModal')" class="bg-gray-500 text-white px-4 py-2 rounded">Cancel</button>
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Upload</button>
                    </div>
                </form>
            </div>
        </div>
    @endhasPermissionTo
@endsection

@section('script')
    <script>
        function changeMainImage(thumbnail) {
            document.getElementById('mainImage').src = thumbnail.src;
        }

        function confirmDelete(deleteUrl) {
            document.getElementById('deleteImageForm').action = deleteUrl;
            openModal('deleteImageModal');
        }

        function confirmSetPrimary(primaryUrl) {
            document.getElementById('primaryImageForm').action = primaryUrl;
            openModal('primaryImageModal');
        }
    </script>
@endsection
