<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('viewAny', Order::class);

        $query = Order::query();

        if ($request->has('search')) {
            $query->where('order_number', 'like', '%' . $request->search . '%');
        }

        $orders = $query->with(['statuses', 'items', 'user'])->latest()->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        Gate::authorize('view', $order);

        $order->load(['items', 'statuses', 'user']);
        return view('admin.orders.show', compact('order'));
    }

    public function status(Request $request, Order $order)
    {
        Gate::authorize('changeStatus', $order);

        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
            'description' => 'nullable|string|max:1000',
        ]);

        if ($request->status == 'cancelled' && $order->status == 'completed') {
            return redirect()->back()->withErrors('You cannot cancel a completed order');
        }

        $order->statuses()->create([
            'order_id' => $order->id,
            'status' => $request->status,
            'notes' => $request->description,
            'user_id' => auth()->id(),
        ]);

        return redirect()->back()->with('success', 'Order Status updated successfully');
    }
}
