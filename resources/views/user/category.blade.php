@extends('layouts.user.app')

@section('title', $category->name)

@section('content')
<section class="py-12 px-6">
      <div class="mb-8 flex flex-col items-center text-center">
          <img src="{{ asset('storage/' . $category->image) }}" 
                alt="{{ $category->name }}" 
                class="w-40 h-40 object-cover rounded-full shadow-lg mb-4">
          <h1 class="text-3xl font-bold text-blue-600">{{ $category->name }}</h1>
          <p class="text-gray-600 max-w-3xl mt-2">{{ $category->description }}</p>
      </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6 mt-20">
        @forelse($products as $product)
            <div class="bg-gray-800 shadow-lg rounded-lg overflow-hidden transform transition duration-300 hover:scale-105 hover:shadow-xl">
                <a href="{{ route('product.show', $product->slug) }}">
                    <img src="{{ asset('storage/' . $product->primaryImage()->path) }}" 
                         alt="{{ $product->name }}" 
                         class="w-full h-48 object-cover">
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
            <p class="text-center text-gray-400 col-span-3">No products found in this category.</p>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-6 flex justify-center">
        {{ $products->links() }}
    </div>
</section>
@endsection
