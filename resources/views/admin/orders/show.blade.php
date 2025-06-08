@extends('layouts.admin.app')

@section('title', '📦 Order Details - #{{ $order->order_number }}')

@section('content')
    <h2 class="text-3xl font-bold text-white mb-4">📦 Order Details - #{{ $order->order_number }}</h2>
    <div class="bg-gray-800 p-6 rounded-lg shadow-md">
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
            <p class="mb-2"><b class="pr-2">User Name:</b> {{ $order->user ? $order->user->name : 'Not available' }}</p>
            <p class="mb-2"><b class="pr-2">User Email:</b> {{ $order->user ? $order->user->email : 'Not available' }}</p>
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

            @if ($leatestStatus && $leatestStatus->status != 'completed' && $leatestStatus->status != 'cancelled')
                <button onclick="openModal('editOrderStatusModal')" class="bg-blue-700 hover:bg-blue-600 text-white px-4 py-2 rounded mt-4 text-center mb-4">Update Order Status</button>
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
                        {{ $status->created_at->format('M d, Y h:i A') }} 
                        @if ($status->byUser)
                            <span class="text-gray-500">by {{ $status->byUser->name }}</span>
                        @endif
                        <br>
                        <span class="ml-3 text-gray-500">{{ $status->notes }}  </span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>


    @hasPermissionTo(\App\Enums\PermissionsEnum::UPDATE_ORDER->value)
        <!-- Edit OrderStatus Modal -->
        <div id="editOrderStatusModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center">
            <div class="bg-gray-800 p-6 rounded shadow-md" style="width: 700px; max-width:100%">
                <h3 class="text-xl font-bold mb-4">Update Order Status</h3>
                <form action="{{ route('admin.orders.status', $order) }}" method="POST">
                    @csrf
                    <label for="status" class="text-gray-300">Status</label>
                    <select id="status" name="status" class="w-full p-3 rounded bg-gray-700 text-gray-100 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 mb-4">
                        <option value="pending" {{ $order->statuses->first()->status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="processing" {{ $order->statuses->first()->status == 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="completed" {{ $order->statuses->first()->status == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ $order->statuses->first()->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>

                    <label for="description" class="text-gray-300">OrderStatus Description</label>
                    <textarea id="description" name="description" placeholder="OrderStatus Description" class="w-full p-3 rounded bg-gray-700 text-gray-100 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 mb-4">{{ $order->description }}</textarea>
                    
                    <div class="flex justify-end space-x-2">
                        <button type="button" onclick="closeModal('editOrderStatusModal')" class="bg-gray-500 text-white px-4 py-2 rounded">Cancel</button>
                        <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded">Update</button>
                    </div>
                </form>
            </div>
        </div>
    @endhasPermissionTo
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
