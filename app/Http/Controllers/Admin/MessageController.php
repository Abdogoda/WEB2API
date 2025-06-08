<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Support\Facades\Gate;

class MessageController extends Controller
{
    public function index()
    {
        // Gate::authorize('viewAny', Message::class);

        $messages = Message::latest()->paginate(10);
        $unreadMessagesCount = Message::where('is_read', 0)->count();
        return view('admin.messages.index', compact('messages', 'unreadMessagesCount'));
    }

    public function markAsRead(Message $message)
    {
        // Gate::authorize('markAsRead', $message);

        $message->update(['is_read' => 1]);
        return redirect()->route('admin.messages.index')->with('success', 'Message marked as read');
    }

    public function markAllAsRead()
    {
        // Gate::authorize('markAllAsRead', Message::class);

        Message::where('is_read', 0)->update(['is_read' => 1]);
        return redirect()->route('admin.messages.index')->with('success', 'All messages marked as read');
    }

    public function destroy(Message $message)
    {
        // Gate::authorize('delete', $message);

        $message->delete();
        return redirect()->route('admin.messages.index')->with('success', 'Message deleted successfully');
    }
}
