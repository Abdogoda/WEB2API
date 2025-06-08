@extends('layouts.user.app')

@section('title', 'Shop')

@section('css')
    <style>
        input[type="checkbox"]:checked + label {
            background-color: #4CAF50;
            color: white;             
            border-color: #45a049;
        }
    </style>
@endsection

@section('content')
<section class="py-12 px-6">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
        <aside class="md:col-span-1 p-6 rounded-lg bg-gray-800 shadow-lg h-fit">
          
          <form method="GET" action="{{ route('shop') }}">
            <h3 class="text-lg font-semibold text-center text-gray-300 mb-4">Product Filters</h3>
            <hr class="border-gray-600 my-4">

            
            <div class="mb-4">
                <h4 class="font-semibold text-gray-200 mb-2">Category</h4>
                <div id="categoriesList" class="flex flex-wrap items-center gap-2 mb-4">
                  
                    @foreach ($categories as $category)
                        <input type="checkbox" name="category_ids[]" id="category_{{ $category->id }}" {{ request('category_ids') && in_array($category->id, request('category_ids')) ? 'checked' : ''}} value="{{ $category->id }}" class="hidden">
                        <label for="category_{{ $category->id }}"
                            data-category-id="{{ $category->id }}"
                            class="category-label cursor-pointer p-2 border border-gray-600 rounded text-gray-100 hover:bg-gray-700 transition">
                            {{ ucwords(str_replace('_', ' ', $category->name)) }}
                        </label>
                    @endforeach
                </div>
            </div>
            <hr class="border-gray-600 my-4">

            <div class="mb-4">
                <h4 class="font-semibold text-gray-200 mb-2">Price</h4>
                    <input type="number" name="min_price" min="0" placeholder="Min" value="{{ request('min_price') }}" class="w-full p-2 bg-gray-800 border border-gray-700 rounded text-gray-200 mb-2">
                    <input type="number" name="max_price" min="0" placeholder="Max" value="{{ request('max_price') }}" class="w-full p-2 bg-gray-800 border border-gray-700 rounded text-gray-200 mb-2">
            </div>
            <hr class="border-gray-600 my-4">

            <div class="mb-4">
                <h4 class="font-semibold text-gray-200 mb-2">Featured</h4>
                <label class="flex items-center space-x-2 text-gray-400">
                    <input type="checkbox" {{ request('featured') ? 'checked' : ''}} name="featured" class="w-5 h-5 text-blue-500 bg-gray-700 border border-gray-600 rounded focus:ring-2 focus:ring-blue-500">
                    <span>Show Featured Products</span>
                </label>
            </div>

            <div class="flex align-center gap-2">
              <button type="submit" class="flex-1 bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">Apply</button>
              <a href="{{ route('shop') }}" class="flex-1 bg-gray-600 text-center text-white py-2 rounded hover:bg-gray-700 transition">Clear</a>
            </div>
          </form>
        </aside>

        <div class="md:col-span-3">
            <h2 class="text-3xl font-bold text-center mb-8 relative group text-gray-800 transition-all duration-300 hover:text-blue-600 w-fit mx-auto pb-3">
                Our Products
                <span class="absolute bottom-[-6px] left-1/2 -translate-x-1/2 w-1/2 h-1 bg-blue-600 transition-all duration-300 group-hover:w-full group-hover:-translate-x-1/2"></span>
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                @forelse($products as $product)
                    <div class="bg-gray-800 shadow-lg rounded-lg overflow-hidden transform transition duration-300 hover:scale-105 hover:shadow-xl">
                        <a href="{{ route('product.show', $product->slug) }}">
                            <img src="{{ asset('storage/' . $product->primaryImage()->path) }}" alt="{{ $product->name }}" class="w-full h-48 object-cover">
                            <div class="p-4 text-center text-gray-300">
                                <h3 class="text-lg font-semibold">{{ $product->name }}</h3>
                                <p class="text-gray-400 text-sm truncate">{{ Str::limit($product->description, 50) }}</p>
                                <p class="text-blue-400 font-bold mt-2">${{ number_format($product->price, 2) }}</p>
                            </div>
                        </a>

                        <div class="p-4 text-center">
                            <button
                                onclick="addToCart({{ $product->id }}, '{{ $product->name }}', {{ $product->price }}, 1, '{{ asset('storage/' . $product->primaryImage()->path) }}')"
                                class="w-full bg-blue-600 hover:bg-blue-500 text-white font-semibold py-2 rounded-md transition">
                                Add to Cart
                            </button>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-400 col-span-3">No products found.</p>
                @endforelse

            </div>

            <!-- Pagination -->
            <div class="mt-6 flex justify-center">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</section>
@endsection
