<?php

namespace App\Http\Controllers\WEB\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Message\StoreMessageRequest;
use App\Models\Message;
use App\Services\CategoryService;
use App\Services\MessageService;
use App\Services\ProductService;

class HomeController extends Controller
{

    public function __construct(
        protected CategoryService $categoryService,
        protected ProductService $productService,
        protected MessageService $messageService
    ) {
    }

    public function index()
    {
        $categories = $this->categoryService->getAllCategories();
        $latestProducts = $this->productService->getlatestProducts(8);
        $featuredProducts = $this->productService->getFeaturedProducts(8);

        return view('user.home', compact('categories', 'latestProducts', 'featuredProducts'));
    }

    public function contactUs(StoreMessageRequest $request)
    {
        $this->messageService->createMessage($request->validated());
        return back()->with('success', 'Message sent successfully');
    }
}
