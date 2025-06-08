@extends('layouts.admin.app')

@section('title', 'Orders')
@section('style')
@endsection

@section('content')
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-3xl font-bold">Order Management</h2>
        </div>
        
        <div class="bg-gray-800 rounded-lg shadow-md p-4">
            <form method="GET" class="flex mb-4">
                <input type="search" name="search" value="{{ request('search') }}" class="w-full bg-gray-700 text-white p-2 rounded outline-none" placeholder="Search orders...">
                <button class="bg-blue-600 hover:bg-blue-500 text-white px-4 py-2 rounded ml-2">Search</button>
            </form>
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr>
                        <th class="p-3 border-b border-gray-600">#</th>
                        <th class="p-3 border-b border-gray-600">Order Number</th>
                        <th class="p-3 border-b border-gray-600">User</th>
                        <th class="p-3 border-b border-gray-600">Date</th>
                        <th class="p-3 border-b border-gray-600">Total</th>
                        <th class="p-3 border-b border-gray-600">Status</th>
                        <th class="p-3 border-b border-gray-600">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $order)
                        <tr class="border-b border-gray-700 hover:bg-gray-800 transition">
                            <td class="p-4">{{ $loop->iteration }}</td>
                            <td class="p-4 font-bold">{{ $order->order_number }}</td>
                            <td class="p-4">{{ $order->user->name }}</td>
                            <td class="p-4">{{ $order->created_at->format('M d, Y h:i A') }}</td>
                            <td class="p-4 text-green-400">${{ number_format($order->total_amount, 2) }}</td>
                            <td class="p-4">
                                <span class="px-2 py-1 text-white text-sm rounded 
                                    {{ $order->statuses->first()->status == 'pending' ? 'bg-yellow-600' : 
                                    ($order->statuses->first()->status == 'processing' ? 'bg-blue-600' : 
                                    ($order->statuses->first()->status == 'completed' ? 'bg-green-600' : 'bg-red-600')) }}">
                                    {{ ucfirst($order->statuses->first()->status) }}
                                </span>
                            </td>
                            <td class="py-2 px-4">
                                <a href="{{ route('admin.orders.show', $order->order_number) }}" class="bg-blue-600 hover:bg-blue-500 text-white px-4 py-2 rounded">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-4 text-center">No orders found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection

@section('script')
@endsection