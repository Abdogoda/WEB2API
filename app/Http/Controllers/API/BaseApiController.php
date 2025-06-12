<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Pagination\AbstractCursorPaginator;

class BaseApiController extends Controller
{
  public function sendResponse($result = null, $message = 'Success', $code = 200): JsonResponse
  {
    $response = [
      'success' => true,
      'message' => $message
    ];

    if ($result) {
      if ($result instanceof JsonResource && $result->resource instanceof AbstractPaginator) {
        $response['data'] = $result;
        $response['meta'] = $this->getPaginationData($result->resource);
      } elseif ($result instanceof AbstractPaginator || $result instanceof AbstractCursorPaginator) {
        $response['data'] = $result->items();
        $response['meta'] = $this->getPaginationData($result);
      } else {
        $response['data'] = $result;
      }
    }
    return response()->json($response, $code);
  }

  public function sendError($error, $errorMessages = [], $code = 404): JsonResponse
  {
    $response = [
      'success' => false,
      'message' => $error
    ];
    if (!empty($errorMessages)) {
      $response['errors'] = $errorMessages;
    }

    return response()->json($response, $code);
  }

  public function sendValidationError($validator): JsonResponse
  {
    return $this->sendError('Validation Error', $validator->errors(), 422);
  }

  private static function getPaginationData($resource)
  {
    if ($resource instanceof AbstractPaginator || $resource instanceof AbstractCursorPaginator) {
      return [
        'total' => method_exists($resource, 'total') ? $resource->total() : null,
        'count' => $resource->count(),
        'per_page' => $resource->perPage(),
        'current_page' => $resource->currentPage(),
        'last_page' => method_exists($resource, 'lastPage') ? $resource->lastPage() : null,
        'from' => method_exists($resource, 'firstItem') ? $resource->firstItem() : null,
        'to' => method_exists($resource, 'lastItem') ? $resource->lastItem() : null,
        'next_page_url' => method_exists($resource, 'nextPageUrl') ? $resource->nextPageUrl() : null,
        'prev_page_url' => method_exists($resource, 'previousPageUrl') ? $resource->previousPageUrl() : null,
        'path' => method_exists($resource, 'path') ? $resource->path() : null,
      ];
    }
    return [];
  }
}
