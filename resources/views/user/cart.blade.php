@extends('layouts.user.app')
@section('title', 'Cart')

@section('content')
<div class="container mx-auto px-4 py-8 text-gray-300">
    <h2 class="text-3xl font-bold text-center mb-8 relative group text-gray-800 transition-all duration-300 hover:text-blue-600 w-fit mx-auto pb-3">
        Your Shopping Cart
        <span class="absolute bottom-[-6px] left-1/2 -translate-x-1/2 w-1/2 h-1 bg-blue-600 transition-all duration-300 group-hover:w-full group-hover:-translate-x-1/2"></span>
    </h2>

    <div class="flex flex-col lg:flex-row gap-6">
      <!-- Cart Items -->
      <div id="cart-items"class="w-full lg:w-2/3 bg-gray-800 p-6 rounded-lg shadow-md">
        <p class="text-center text-gray-400">Loading cart...</p>
      </div>

      <!-- Cart Summary -->
      <div class="w-full lg:w-1/3 bg-gray-900 p-6 rounded-lg shadow-md">
        <div class="mb-4">
            <span id="cart-total" class="text-xl font-semibold">Total: $55.00</span>
        </div>

        <div class="">
            <button onclick="openModal('clearCartModal')" class="bg-red-700 hover:bg-red-600 text-white px-4 py-2 rounded w-full block text-center mb-4">
                Clear Cart
            </button>

            @auth
                <button onclick="updateCheckoutInputs(); openModal('checkoutModal')" class="bg-green-600 hover:bg-green-500 text-white px-4 py-2 rounded w-full block text-center mb-4">
                    Checkout
                </button>
            @else
                <a href="{{ route('login') }}" class="bg-green-600 hover:bg-green-500 text-white px-4 py-2 rounded w-full block text-center mb-4">
                    Login to Checkout
                </a>
            @endauth
        </div>
      </div>
    </div>
</div>


<!-- Clear Cart Confirmation Modal -->
<div id="clearCartModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center">
    <div class="bg-gray-800 p-6 rounded shadow-md w-96">
        <h3 class="text-xl font-bold mb-4">Clear Cart?</h3>
        <p>Are you sure you want to clear all cart items?</p>
        <div class="flex justify-end space-x-2 mt-4">
            <button type="button" onclick="closeModal('clearCartModal')" class="bg-gray-500 text-white px-4 py-2 rounded">Cancel</button>
            <button type="submit" onclick="clearCart(); displayCartItems();" class="bg-red-500 text-white px-4 py-2 rounded">Clear</button>
        </div>
    </div>
</div>

@auth
  <!-- Checkout Confirmation Modal -->
  <div id="checkoutModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center">
      <div class="bg-gray-800 p-6 rounded shadow-md" style="width: 700px; max-width:100%">
          <h3 class="text-xl font-bold mb-4">Confirm Checkout?</h3>
          <p>Are you sure you want to buy these products?</p>
          <form action="{{ route('orders.store') }}" method="POST">
            @csrf
            <div id="cart-hidden-inputs"></div>

            <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="country" class="block text-gray-300">Country</label>
                    <input type="text" id="country" name="shipping_country" value="{{auth()->user()->country}}" autocomplete="off" class="mb-3 w-full p-3 rounded bg-gray-700 text-gray-100 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label for="state" class="block text-gray-300">State</label>
                    <input type="text" id="state" name="shipping_state" value="{{auth()->user()->state}}" autocomplete="off" class="mb-3 w-full p-3 rounded bg-gray-700 text-gray-100 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label for="city" class="block text-gray-300">City</label>
                    <input type="text" id="city" name="shipping_city" value="{{auth()->user()->city}}" autocomplete="off" class="mb-3 w-full p-3 rounded bg-gray-700 text-gray-100 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            
            <label class="block text-gray-400 mb-1">Shipping Phone</label>
            <input type="text" name="shipping_phone" value="{{ auth()->user()->phone }}" placeholder="Shipping Phone" class="mb-3 w-full p-3 rounded bg-gray-700 text-gray-100 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
            
            <label class="block text-gray-400 mb-1">Shipping Address</label>
            <textarea name="shipping_address" placeholder="Shipping Address" class="mb-3 w-full p-3 rounded bg-gray-700 text-gray-100 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ auth()->user()->address }}</textarea>

            <label class="block text-gray-400 mb-1">Notes</label>
            <textarea name="notes" placeholder="Shipping Notes" class="mb-3 w-full p-3 rounded bg-gray-700 text-gray-100 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('notes') }}</textarea>

            <div class="flex justify-end space-x-2 mt-4">
              <button type="button" onclick="closeModal('checkoutModal')" class="bg-gray-500 text-white px-4 py-2 rounded">Cancel</button>
              <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Confirm</button>
            </div>
          </form>
      </div>
  </div>
  @endauth
@endsection

@section('script')
@endsection