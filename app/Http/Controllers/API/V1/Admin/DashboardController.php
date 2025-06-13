<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Http\Controllers\API\BaseApiController;
use App\Services\DashboardService;
use Illuminate\Http\JsonResponse;

class DashboardController extends BaseApiController
{
  public function __construct(protected DashboardService $dashboardService)
  {
  }

  public function index(): JsonResponse
  {
    $stats = $this->dashboardService->getDashboardStats();
    return $this->sendResponse($stats, 'Dashboard statistics retrieved successfully.');
  }
}
