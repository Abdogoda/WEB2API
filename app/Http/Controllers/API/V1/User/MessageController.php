<?php

namespace App\Http\Controllers\API\V1\User;

use App\Http\Controllers\API\BaseApiController;
use App\Http\Requests\Message\StoreMessageRequest;
use App\Services\MessageService;
use Illuminate\Http\JsonResponse;

class MessageController extends BaseApiController
{

    public function __construct(protected MessageService $messageService)
    {
    }

    public function store(StoreMessageRequest $request): JsonResponse
    {
        return $this->sendResponse($this->messageService->createMessage($request->validated()), 'Message sent successfully', 201);
    }
}
