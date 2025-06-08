@extends('layouts.user.app')
@section('title', 'Home')

@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
@endsection

@section('content')
    <!-- Hero Section -->
    <section class="relative w-full flex items-center bg-cover bg-center" style="min-height:550px; background-image: url('{{ asset('assets/images/hero.jpg') }}');">
        <div class="absolute inset-0 bg-black bg-opacity-50"></div> <!-- Dark Overlay -->
        <div class="relative z-10 px-6">
            <h2 class="text-5xl font-bold">Welcome to Our Store</h2>
            <p class="mt-4 text-lg text-gray-300">Best quality products at affordable prices</p>
            <a href="{{ route('shop') }}" class="mt-6 inline-block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700">Shop Now</a>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="py-12 px-6">
        <h2 class="text-3xl font-bold text-center mb-8 relative group text-gray-800 transition-all duration-300 hover:text-blue-600 w-fit mx-auto">
            Our Categories
            <span class="absolute bottom-[-10px] left-1/2 -translate-x-1/2 w-1/2 h-1 bg-blue-600 transition-all duration-300 group-hover:w-full group-hover:-translate-x-1/2"></span>
        </h2>
        <div class="swiper mySwiper">
            <div class="swiper-wrapper">
                @foreach($categories as $category)
                    <div class="swiper-slide">
                        <a href="{{ route('category.show', $category->slug) }}" class="group block bg-gray-800 p-6 rounded-lg text-center transition-transform transform hover:scale-105 hover:shadow-lg hover:bg-gray-700">
                            <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="w-20 h-20 mx-auto object-cover rounded-md mb-4">
                            <h5 class="text-xl font-semibold text-white">{{ $category->name }}</h5>
                        </a>
                    </div>
                @endforeach
            </div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>

            <div class="swiper-pagination"></div>
        </div>
    </section>


    <!-- Featured Products -->
    <section class="py-12 px-6">
        <h2 class="text-3xl font-bold text-center mb-8 relative group text-gray-800 transition-all duration-300 hover:text-blue-600 w-fit mx-auto">
            Featured Products
            <span class="absolute bottom-[-10px] left-1/2 -translate-x-1/2 w-1/2 h-1 bg-blue-600 transition-all duration-300 group-hover:w-full group-hover:-translate-x-1/2"></span>
        </h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 justify-center">
            @foreach($featuredProducts as $product)
                <div class="bg-gray-800 shadow-lg rounded-lg overflow-hidden transform transition duration-300 hover:scale-105 hover:shadow-xl">
                    <a href="{{ route('product.show', $product->slug) }}">
                        <img src="{{ asset('storage/' . $product->primaryImage()->path) }}" alt="{{ $product->name }}" class="w-full h-52 object-cover">
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
            @endforeach
        </div>
    </section>

    <!-- New Products -->
    <section class="py-12 px-6">
        <h2 class="text-3xl font-bold text-center mb-8 relative group text-gray-800 transition-all duration-300 hover:text-blue-600 w-fit mx-auto">
            New Arrivals
            <span class="absolute bottom-[-10px] left-1/2 -translate-x-1/2 w-1/2 h-1 bg-blue-600 transition-all duration-300 group-hover:w-full group-hover:-translate-x-1/2"></span>
        </h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 justify-center">
            @foreach($latestProducts as $product)
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
            @endforeach
        </div>
    </section>

    <!-- Contact Us Section -->
    <section class="py-12 px-6">
        <h2 class="text-3xl font-bold text-center mb-8 relative group text-gray-800 transition-all duration-300 hover:text-blue-600 w-fit mx-auto">
            Contact Us
            <span class="absolute bottom-[-10px] left-1/2 -translate-x-1/2 w-1/2 h-1 bg-blue-600 transition-all duration-300 group-hover:w-full group-hover:-translate-x-1/2"></span>
        </h2>
        <div class="flex flex-col md:flex-row items-center justify-center md:space-x-10">
            
            <!-- Image on the Left -->
            <div class="md:w-1/2 flex justify-center">
                <img src="{{ asset('assets/images/contact-us.png') }}" alt="Contact Us" class="w-full max-w-sm rounded-lg shadow-lg">
            </div>

            <!-- Contact Form on the Right -->
            <div class="md:w-1/2 mt-6 md:mt-0 bg-gray-800 p-6 rounded-lg w-full max-w-md">
                <form action="{{ route('contact-us') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium" for="name">Name</label>
                        <input class="w-full p-3 rounded bg-gray-700 text-gray-100 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500" type="text" id="name" name="name">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium" for="email">Email</label>
                        <input class="w-full p-3 rounded bg-gray-700 text-gray-100 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500" type="email" id="email" name="email">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium" for="message">Message</label>
                        <textarea class="w-full p-3 rounded bg-gray-700 text-gray-100 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500" id="message" name="message" rows="4"></textarea>
                    </div>
                    <button type="submit" class="w-full py-3 bg-blue-600 rounded-lg font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">Send Message</button>
                </form>
            </div>
        </div>
    </section>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var swiper = new Swiper(".mySwiper", {
                slidesPerView: 1,
                spaceBetween: 20,
                loop: true,
                autoplay: {
                    delay: 3000,
                    disableOnInteraction: false,
                },
                pagination: {
                    el: ".swiper-pagination",
                    clickable: true,
                },
                navigation: {
                    nextEl: ".swiper-button-next",
                    prevEl: ".swiper-button-prev",
                },
                breakpoints: {
                    400: { slidesPerView: 1 }, 
                    640: { slidesPerView: 2 }, 
                    768: { slidesPerView: 3 }, 
                    1024: { slidesPerView: 4 }
                },
            });
        });
    </script>
@endsection