<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class BaseApiController extends Controller
{
  public function sendResponse($result = null, $message = 'Success', $code = 200): JsonResponse
  {
    $response = [
      'success' => true,
      'message' => $message
    ];

    if ($result)
      $response['data'] = $result;
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
}
