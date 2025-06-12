<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Http\Controllers\API\BaseApiController;
use App\Models\Message;
use Illuminate\Http\JsonResponse;

class MessageController extends BaseApiController
{
    public function index(): JsonResponse
    {
        $messages = Message::latest()->paginate(10);
        $unreadMessagesCount = Message::where('is_read', 0)->count();
        return $this->sendResponse([
            'messages' => $messages,
            'unread_count' => $unreadMessagesCount
        ], 'Messages retrieved successfully');
    }

    public function show(Message $message): JsonResponse
    {
        return $this->sendResponse($message, 'Message retrieved successfully');
    }

    public function markAsRead(Message $message): JsonResponse
    {
        $message->update(['is_read' => 1]);
        return $this->sendResponse($message, 'Message marked as read');
    }

    public function markAllAsRead(): JsonResponse
    {
        Message::where('is_read', 0)->update(['is_read' => 1]);
        return $this->sendResponse(message: 'All messages marked as read');
    }

    public function destroy(Message $message): JsonResponse
    {
        $message->delete();
        return $this->sendResponse(message: 'Message deleted successfully');
    }
}
