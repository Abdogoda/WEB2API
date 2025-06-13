<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Services\MessageService;
use Illuminate\Support\Facades\Gate;

class MessageController extends Controller
{
    public function __construct(protected MessageService $messageService)
    {
    }

    public function index()
    {
        // Gate::authorize('viewAny', Message::class);
        $messages = $this->messageService->getPaginatedMessages();
        $unreadMessagesCount = $this->messageService->getUnreadCount();
        return view('admin.messages.index', compact('messages', 'unreadMessagesCount'));
    }

    public function markAsRead(Message $message)
    {
        // Gate::authorize('markAsRead', $message);
        $this->messageService->markAsRead($message);
        return redirect()->route('admin.messages.index')->with('success', 'Message marked as read');
    }

    public function markAllAsRead()
    {
        // Gate::authorize('markAllAsRead', Message::class);
        $this->messageService->markAllAsRead();
        return redirect()->route('admin.messages.index')->with('success', 'All messages marked as read');
    }

    public function destroy(Message $message)
    {
        // Gate::authorize('delete', $message);
        $this->messageService->deleteMessage($message);
        return redirect()->route('admin.messages.index')->with('success', 'Message deleted successfully');
    }
}
