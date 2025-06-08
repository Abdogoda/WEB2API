@extends('layouts.user.app')

@section('title', $product->name)

@section('content')
<section class="py-12 px-6">
    <div class="flex flex-col sm:flex-row space-x-6 mb-6">
        <!-- Product Image -->
        <div>
            @if ($product->images->count() > 0)
                <div class="w-80 h-80">
                    <img id="mainImage" src="{{ asset('storage/' . $product->primaryImage()->path) }}" 
                        class="w-full h-full object-cover rounded-lg border border-gray-600" alt="Product Image">
                </div>

                <div class="flex space-x-2 mt-2">
                    @foreach ($product->images as $image)
                        <div class="relative flex-1 group">

                            <img src="{{ asset('storage/' . $image->path) }}" 
                                class="w-full h-24 object-cover rounded-lg border border-gray-600 cursor-pointer hover:opacity-80"
                                onclick="changeMainImage(this)">
                        </div>
                    @endforeach
                </div>
            @else
                <span class="text-gray-500">No Images Available</span>
            @endif
        </div>

        <!-- Product Details -->
        <div>
            <h1 class="text-3xl font-bold text-gray-200">{{ $product->name }}</h1>
            <p class="text-gray-300 text-2xl font-semibold mt-4">Price: <span class="text-blue-600">${{ number_format($product->price, 2) }}</span></p>
            <p class="text-gray-300 mt-2">Category: <a href="{{ route('category.show', $product->category) }}" class="text-blue-300">{{ $product->category->name }}</a></p>
            @if ($product->featured)
                <span class="px-2 py-1 text-xs font-semibold text-white bg-blue-500 rounded-full mr-2 my-2 inline-block">Featured</span>
            @endif
            <p>{{ $product->description }}</p>

            <!-- Add to Cart Button -->
            <button onclick="addToCart({{ $product->id }}, '{{ $product->name }}', {{ $product->price }}, 1, '{{ asset('storage/' . $product->primaryImage()->path) }}')"
                class="mt-4 bg-blue-600 hover:bg-blue-500 text-white px-6 py-3 rounded-md text-lg font-semibold transition">
                Add to Cart
            </button>
        </div>
    </div>
    <hr class="border-gray-600 my-4">

    @if ($simillarProducts->count() > 0)
        <!-- Similar Products -->
        <div class="mt-12">
            <h2 class="text-3xl font-bold text-center mb-8 relative group text-gray-800 transition-all duration-300 hover:text-blue-600 w-fit mx-auto">
                Similar Products
                <span class="absolute bottom-[-10px] left-1/2 -translate-x-1/2 w-1/2 h-1 bg-blue-600 transition-all duration-300 group-hover:w-full group-hover:-translate-x-1/2"></span>
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                @foreach ($simillarProducts as $similar)
                    <div class="bg-gray-800 shadow-lg rounded-lg overflow-hidden transform transition duration-300 hover:scale-105 hover:shadow-xl">
                        <a href="{{ route('product.show', $similar->slug) }}">
                            <img src="{{ asset('storage/' . $similar->primaryImage()->path) }}" 
                                alt="{{ $similar->name }}" 
                                class="w-full h-48 object-cover">
                            <div class="p-4 text-center text-gray-300">
                                <h3 class="text-lg font-semibold">{{ $similar->name }}</h3>
                                <p class="text-blue-400 font-bold mt-2">${{ number_format($similar->price, 2) }}</p>
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
                @endforeach
            </div>
        </div>
    @endif
</section>
@endsection

@section('script')
    <script>
        function changeMainImage(thumbnail) {
            document.getElementById('mainImage').src = thumbnail.src;
        }
    </script>
@endsection