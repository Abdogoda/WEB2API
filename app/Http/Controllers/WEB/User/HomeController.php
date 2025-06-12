<?php

namespace App\Http\Controllers\WEB\User;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Message;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    public function index()
    {
        $categories = Category::all();
        $latestProducts = Product::where('active', 1)->where('stock', '>', 0)->latest()->limit(8)->get();
        $featuredProducts = Product::where('active', 1)->where('featured', 1)->where('stock', '>', 0)->limit(8)->get();

        return view('user.home', compact('categories', 'latestProducts', 'featuredProducts'));
    }

    public function contactUs(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'message' => 'required|string'
        ]);

        Message::create($data);
        return back()->with('success', 'Message sent successfully');
    }
}
