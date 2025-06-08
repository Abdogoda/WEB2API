
@extends('layouts.user.app')
@section('title', 'Cart')

@section('content')
<div class="container mx-auto px-4 py-8 text-gray-300">
    <h2 class="text-3xl font-bold text-center mb-8 relative group text-gray-800 transition-all duration-300 hover:text-blue-600 w-fit mx-auto pb-3">
        📦 Order Details - #{{ $order->order_number }}
        <span class="absolute bottom-[-6px] left-1/2 -translate-x-1/2 w-1/2 h-1 bg-blue-600 transition-all duration-300 group-hover:w-full group-hover:-translate-x-1/2"></span>
    </h2>

    <div class="flex space-x-4 border-b border-gray-700 pb-2">
        <button class="tab-button bg-gray-700 px-3 py-1 rounded hover:bg-gray-600 text-white"
                onclick="showTab('info')">ℹ️ Order Info</button>
        <button class="tab-button bg-gray-700 px-3 py-1 rounded hover:bg-gray-600 text-white"
                onclick="showTab('items')">🛍 Order Items</button>
        <button class="tab-button bg-gray-700 px-3 py-1 rounded hover:bg-gray-600 text-white"
                onclick="showTab('statuses')">📦 Order Status</button>
    </div>

    <div id="infoTab" class="order-tab-content mt-2">
        <h3 class="my-4 border-b border-gray-400 w-fit font-bold text-3xl text-white">Order Information</h3>
        <p class="mb-2"><b class="pr-2">Order Number:</b> <span class="font-bold">{{ $order->order_number }}</span></p>
        <p class="mb-2"><b class="pr-2">Placed on:</b> {{ $order->created_at->format('M d, Y h:i A') }}</p>
        <p class="mb-2"><b class="pr-2">Total:</b> <span class="text-green-400">${{ number_format($order->total_amount, 2) }}</span></p>
        <p class="mb-2"><b class="pr-2">Shipping Address:</b> {{ $order->shipping_address }}, {{ $order->shipping_city }}, {{ $order->shipping_state }}, {{ $order->shipping_country }}</p>
        <p class="mb-2"><b class="pr-2">Shipping Phone:</b> {{ $order->shipping_phone }}</p>

        @php
            $leatestStatus = $order->statuses()->orderBy('created_at', 'desc')->first();
            $leatestStatusColor = $leatestStatus->status == 'pending' ? 'yellow' : 
                              ($leatestStatus->status == 'processing' ? 'blue' : 
                              ($leatestStatus->status == 'completed' ? 'green' : 'red'));
        @endphp

        <p class="mb-2"><b class="pr-2">Status:</b> <span class="text-{{ $leatestStatusColor }}-600">{{ ucfirst($leatestStatus->status) }}</span></p>

        @if ($leatestStatus && $leatestStatus->status == 'pending')
            <button onclick="openModal('changeShippingAddressModal')" class="bg-blue-700 hover:bg-blue-600 text-white px-4 py-2 rounded mt-4 text-center mb-4">Change Shipping Address</button>
            <button onclick="openModal('cancelOrderModal')" class="bg-red-700 hover:bg-red-600 text-white px-4 py-2 rounded mt-4 text-center mb-4">Cancel Order</button>
        @endif
    </div>

    <div id="itemsTab" class="order-tab-content mt-2 hidden">
        <h3 class="my-4 border-b border-gray-400 w-fit font-bold text-3xl text-white">Order Items</h3>
        <table class="w-full border border-gray-700 text-gray-300">
            <thead class="bg-gray-800 text-white">
                <tr>
                    <th class="py-2 px-4">Image</th>
                    <th class="py-2 px-4">Name</th>
                    <th class="py-2 px-4">Quantity</th>
                    <th class="py-2 px-4">Price</th>
                    <th class="py-2 px-4">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->items as $item)
                    <tr class="border-b border-gray-700 text-center">
                        <td class="py-2 px-4">
                          @if ($item->product && $item->product->images->count() > 0)
                              <img src="{{ asset('storage/' . $item->product->primaryImage()->path) }}" class="w-12 h-12 rounded mx-auto" alt="Product Image">
                          @else
                              <span class="text-gray-500">No Image</span>
                          @endif
                        </td>
                        <td class="py-2 px-4">
                            @if ($item->product)
                                {{ $item->product->name }}
                            @else
                                <span class="text-gray-500">Not Available</span>
                            @endif
                        </td>
                        <td class="py-2 px-4">{{ $item->quantity }}</td>
                        <td class="py-2 px-4 text-green-400">${{ number_format($item->total, 2) }}</td>
                        <td class="py-2 px-4 text-green-400">${{ number_format($item->total * $item->quantity, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div id="statusesTab" class="order-tab-content mt-2 hidden">
        <h3 class="my-4 border-b border-gray-400 w-fit font-bold text-3xl text-white">Order Status History</h3>
        <ul class="pl-5 text-gray-300">
            @foreach ($order->statuses()->orderBy('created_at', 'desc')->get() as $status)
                @php
                    $color = $status->status == 'pending' ? 'yellow' : 
                                  ($status->status == 'processing' ? 'blue' : 
                                  ($status->status == 'completed' ? 'green' : 'red'));
                @endphp
                <li class=" relative pl-5 mb-3">
                    <span class="absolute left-0 top-1/4 transform -translate-y-1/2 h-2 w-2 rounded-full bg-{{ $color }}-600"></span>
                    <span class="text-{{ $color }}-600 font-semibold">{{ ucfirst($status->status) }}</span> - 
                    {{ $status->created_at->format('M d, Y h:i A') }} <br>
                    <span class="ml-3 text-gray-500">{{ $status->notes }}</span>
                </li>
            @endforeach
        </ul>
    </div>
</div>

<div id="changeShippingAddressModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center">
    <div class="bg-gray-800 p-6 rounded shadow-md w-96">
        <h3 class="text-xl font-bold mb-4">Change Shipping Address?</h3>
        <form action="{{ route('orders.update', $order->order_number)  }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="shipping_address" class="block text-sm font-medium text-gray-400">Shipping Address</label>
                <input type="text" name="shipping_address" id="shipping_address" value="{{ $order->shipping_address }}" class="mt-1 block w-full px-3 py-2 border border-gray-700 rounded bg-gray-800 text-gray-300">
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="mb-4">
                    <label for="shipping_country" class="block text-sm font-medium text-gray-400">Country</label>
                    <input type="text" name="shipping_country" id="shipping_country" value="{{ $order->shipping_country }}" class="mt-1 block w-full px-3 py-2 border border-gray-700 rounded bg-gray-800 text-gray-300">
                </div>
                <div class="mb-4">
                    <label for="shipping_state" class="block text-sm font-medium text-gray-400">State</label>
                    <input type="text" name="shipping_state" id="shipping_state" value="{{ $order->shipping_state }}" class="mt-1 block w-full px-3 py-2 border border-gray-700 rounded bg-gray-800 text-gray-300">
                </div>
                <div class="mb-4">
                    <label for="shipping_city" class="block text-sm font-medium text-gray-400">City</label>
                    <input type="text" name="shipping_city" id="shipping_city" value="{{ $order->shipping_city }}" class="mt-1 block w-full px-3 py-2 border border-gray-700 rounded bg-gray-800 text-gray-300">
                </div>
            </div>
            
            <label class="block text-gray-400 mb-1">Shipping Phone</label>
            <input type="text" name="shipping_phone" value="{{ $order->shipping_phone }}" placeholder="Shipping Phone" class="mb-3 w-full p-3 rounded bg-gray-700 text-gray-100 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
            
            <label class="block text-gray-400 mb-1">Shipping Address</label>
            <textarea name="shipping_address" placeholder="Shipping Address" class="mb-3 w-full p-3 rounded bg-gray-700 text-gray-100 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ $order->shipping_address }}</textarea>

            <label class="block text-gray-400 mb-1">Notes</label>
            <textarea name="notes" placeholder="Shipping Notes" class="mb-3 w-full p-3 rounded bg-gray-700 text-gray-100 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ $order->notes }}</textarea>

            <div class="flex justify-end space-x-2 mt-4">
                <button type="button" onclick="closeModal('changeShippingAddressModal')" class="bg-gray-500 text-white px-4 py-2 rounded">Cancel</button>
                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded">Confirm</button>
            </div>
        </form>
    </div>
</div>

<div id="cancelOrderModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center">
    <div class="bg-gray-800 p-6 rounded shadow-md w-96">
        <h3 class="text-xl font-bold mb-4">Cancel Order?</h3>
        <p>Are you sure you want to cancel your order with no {{$order->order_number}}!</p>
        <form action="{{ route('orders.cancel', $order->order_number)  }}" method="POST">
            @csrf
            <div class="flex justify-end space-x-2 mt-4">
                <button type="button" onclick="closeModal('cancelOrderModal')" class="bg-gray-500 text-white px-4 py-2 rounded">Cancel</button>
                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded">Confirm</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('script')
  <script>
      function showTab(tab) {
          document.getElementById('infoTab').classList.add('hidden');
          document.getElementById('itemsTab').classList.add('hidden');
          document.getElementById('statusesTab').classList.add('hidden');

          document.getElementById(tab + 'Tab').classList.remove('hidden');
      }
  </script>
@endsection
