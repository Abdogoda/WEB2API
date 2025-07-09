<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Http\Controllers\API\BaseApiController;
use App\Http\Resources\MessageResource;
use App\Models\Message;
use Illuminate\Http\JsonResponse;
use App\Services\MessageService;

class MessageController extends BaseApiController
{
    public function __construct(protected MessageService $messageService)
    {
    }

    public function index(): JsonResponse
    {
        // Gate::authorize('viewAny', Message::class);
        $messages = $this->messageService->getPaginatedMessages();
        $unreadMessagesCount = $this->messageService->getUnreadCount();
        return $this->sendResponse([
            'messages' => MessageResource::collection($messages),
            'unread_count' => $unreadMessagesCount
        ], 'Messages retrieved successfully');
    }

    public function show(Message $message): JsonResponse
    {
        // Gate::authorize('view', $message);
        $message = $this->messageService->getMessage($message);
        return $this->sendResponse(new MessageResource($message), 'Message retrieved successfully');
    }

    public function markAsRead(Message $message): JsonResponse
    {
        // Gate::authorize('markAsRead', $message);
        $message = $this->messageService->markAsRead($message);
        return $this->sendResponse(new MessageResource($message), 'Message marked as read');
    }

    public function markAllAsRead(): JsonResponse
    {
        // Gate::authorize('markAllAsRead', Message::class);
        $this->messageService->markAllAsRead();
        return $this->sendResponse(message: 'All messages marked as read');
    }

    public function destroy(Message $message): JsonResponse
    {
        // Gate::authorize('delete', $message);
        $this->messageService->deleteMessage($message);
        return $this->sendResponse(message: 'Message deleted successfully');
    }
}
