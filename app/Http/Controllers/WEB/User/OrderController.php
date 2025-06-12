<?php

namespace App\Http\Controllers\WEB\User;

use App\Http\Controllers\Controller;
;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'cart_items' => 'required|array|min:1',
            'cart_items.*.id' => 'required|integer|exists:products,id',
            'cart_items.*.quantity' => 'required|integer|min:1',
            'shipping_country' => 'required|string|max:255',
            'shipping_city' => 'required|string|max:255',
            'shipping_state' => 'required|string|max:255',
            'shipping_address' => 'required|string|max:500',
            'shipping_phone' => 'required|string|max:20',
            'notes' => 'nullable|string|max:1000',
        ]);

        $cartItems = collect($data['cart_items']);
        $productIds = $cartItems->pluck('id');
        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

        $totalAmount = 0;
        $orderItems = [];
        $orderItems = [];

        foreach ($cartItems as $item) {
            $product = $products->get($item['id']);

            if (!$product) {
                $errors[] = "Product with ID {$item['id']} not found.";
                continue;
            }

            if ($product->stock < $item['quantity']) {
                $errors[] = "Product '{$product->name}' has insufficient stock.";
                continue;
            }

            $total = $product->price * $item['quantity'];
            $totalAmount += $total;

            $orderItems[] = new OrderItem([
                'product_id' => $product->id,
                'quantity' => $item['quantity'],
                'price' => $product->price,
                'total' => $total,
            ]);
        }

        if (!empty($errors)) {
            return redirect()->back()->withErrors($errors);
        }

        DB::beginTransaction();

        try {
            $order = Order::create([
                'user_id' => auth()->id(),
                'shipping_country' => $data['shipping_country'],
                'shipping_city' => $data['shipping_city'],
                'shipping_state' => $data['shipping_state'],
                'shipping_address' => $data['shipping_address'],
                'shipping_phone' => $data['shipping_phone'],
                'notes' => $data['notes'] ?? null,
                'total_amount' => $totalAmount,
                'order_number' => 'ORD-' . strtoupper(uniqid()),
            ]);

            $order->items()->saveMany($orderItems);

            foreach ($cartItems as $item) {
                Product::where('id', $item['id'])->decrement('stock', $item['quantity']);
            }

            $order->statuses()->create([
                'order_id' => $order->id,
                'status' => 'pending',
                'notes' => 'Order placed successfully.',
                'user_id' => auth()->id(),
            ]);

            DB::commit();

            return redirect()->route('profile')->with('order_success', true)->with('success', 'Order placed successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function show(Order $order)
    {
        $order->load(['items.product', 'statuses']);

        return view('user.order', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $data = $request->validate([
            'shipping_country' => 'sometimes|string|max:255',
            'shipping_city' => 'required|string|max:255',
            'shipping_state' => 'required|string|max:255',
            'shipping_address' => 'required|string|max:500',
            'shipping_phone' => 'required|string|max:20',
            'notes' => 'nullable|string|max:1000',
        ]);

        $order->update([
            'shipping_country' => $data['shipping_country'],
            'shipping_city' => $data['shipping_city'],
            'shipping_state' => $data['shipping_state'],
            'shipping_address' => $data['shipping_address'],
            'shipping_phone' => $data['shipping_phone'],
            'notes' => $data['notes'] ?? null,
        ]);

        return redirect()->route('orders.show', $order)->with('success', 'Order Info Changed Successfully');
    }

    public function cancel(Order $order)
    {
        $order->statuses()->create([
            'order_id' => $order->id,
            'status' => 'cancelled',
            'notes' => 'Order cancelled by customer.',
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('profile')->with('success', 'Order cancelled successfully.');
    }
}
