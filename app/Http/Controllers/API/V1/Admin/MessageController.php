<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;

class MessageController extends Controller
{
    public function index()
    {
        $messages = Message::latest()->paginate(10);
        $unreadMessagesCount = Message::where('is_read', 0)->count();
        return response()->json([
            'data' => [
                'messages' => $messages,
                'unreadMessagesCount' => $unreadMessagesCount
            ]
        ]);
    }

    public function show(Message $message)
    {
        return response()->json([
            'message' => $message
        ]);
    }

    public function markAsRead(Message $message)
    {
        $message->update(['is_read' => 1]);
        return response()->json([
            'message' => 'Message marked as read',
            'message_data' => $message
        ]);
    }

    public function markAllAsRead()
    {
        Message::where('is_read', 0)->update(['is_read' => 1]);
        return response()->json([
            'message' => 'All messages marked as read'
        ]);
    }

    public function destroy(Message $message)
    {
        $message->delete();
        return response()->json([
            'message' => 'Message deleted successfully'
        ]);
    }
}
