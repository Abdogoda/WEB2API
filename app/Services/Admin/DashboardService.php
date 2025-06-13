<?php

namespace App\Services\Admin;

use App\Models\Category;
use App\Models\Message;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardService
{
  public function getDashboardStats()
  {
    $totalOrders = Order::count();
    $totalProducts = Product::count();
    $totalCategories = Category::count();
    $totalUsers = User::count();
    $verifiedUsers = User::whereNotNull('email_verified_at')->count();
    $totalMessages = Message::count();

    $ordersByMonth = DB::table('orders')
      ->whereNotNull('created_at')
      ->selectRaw("strftime('%m', created_at) as month, COUNT(*) as count")
      ->groupBy(DB::raw("strftime('%m', created_at)"))
      ->get();

    $topProducts = DB::table('order_items')
      ->select('products.name', DB::raw('SUM(order_items.quantity) as total_ordered'))
      ->join('products', 'order_items.product_id', '=', 'products.id')
      ->groupBy('products.id', 'products.name')
      ->orderByDesc('total_ordered')
      ->limit(5)
      ->get();

    $productsByCategory = Category::select('name')
      ->withCount('products')
      ->orderBy('products_count', 'desc')
      ->take(5)
      ->get();

    $bestSellerUsers = DB::table('orders')
      ->select('users.id', 'users.name', DB::raw('COUNT(orders.id) as total_orders'))
      ->join('users', 'orders.user_id', '=', 'users.id')
      ->groupBy('users.id', 'users.name')
      ->orderByDesc('total_orders')
      ->limit(5)
      ->get();

    $totalRevenue = DB::table('order_items')
      ->selectRaw('SUM(price * quantity) as revenue')
      ->value('revenue');

    return compact(
      'totalOrders',
      'totalProducts',
      'totalCategories',
      'totalUsers',
      'verifiedUsers',
      'totalMessages',
      'ordersByMonth',
      'topProducts',
      'bestSellerUsers',
      'totalRevenue',
      'productsByCategory'
    );
  }
}
