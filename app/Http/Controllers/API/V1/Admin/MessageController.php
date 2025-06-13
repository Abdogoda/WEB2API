<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Http\Controllers\API\BaseApiController;
use App\Http\Resources\MessageResource;
use App\Models\Message;
use Illuminate\Http\JsonResponse;
use App\Services\Admin\MessageService;

class MessageController extends BaseApiController
{
    public function __construct(protected MessageService $messageService)
    {
    }

    public function index(): JsonResponse
    {
        $messages = $this->messageService->getPaginatedMessages();
        $unreadMessagesCount = $this->messageService->getUnreadCount();
        return $this->sendResponse([
            'messages' => MessageResource::collection($messages),
            'unread_count' => $unreadMessagesCount
        ], 'Messages retrieved successfully');
    }

    public function show(Message $message): JsonResponse
    {
        $message = $this->messageService->getMessage($message);
        return $this->sendResponse(new MessageResource($message), 'Message retrieved successfully');
    }

    public function markAsRead(Message $message): JsonResponse
    {
        $message = $this->messageService->markAsRead($message);
        return $this->sendResponse(new MessageResource($message), 'Message marked as read');
    }

    public function markAllAsRead(): JsonResponse
    {
        $this->messageService->markAllAsRead();
        return $this->sendResponse(message: 'All messages marked as read');
    }

    public function destroy(Message $message): JsonResponse
    {
        $this->messageService->deleteMessage($message);
        return $this->sendResponse(message: 'Message deleted successfully');
    }
}
