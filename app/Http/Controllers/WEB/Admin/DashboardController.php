<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;

class DashboardController extends Controller
{
    public function __construct(protected DashboardService $dashboardService)
    {
    }

    public function index()
    {
        $stats = $this->dashboardService->getDashboardStats();
        return view('admin.dashboard', $stats);
    }
}
