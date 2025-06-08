<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index()
    {
        $messages = Message::latest()->paginate(10);
        $unreadMessagesCount = Message::where('is_read', 0)->count();
        return view('admin.messages.index', compact('messages', 'unreadMessagesCount'));
    }

    public function markAsRead(Message $message)
    {
        $message->update(['is_read' => 1]);
        return redirect()->route('admin.messages.index')->with('success', 'Message marked as read');
    }

    public function markAllAsRead()
    {
        Message::where('is_read', 0)->update(['is_read' => 1]);
        return redirect()->route('admin.messages.index')->with('success', 'All messages marked as read');
    }

    public function destroy(Message $message)
    {
        $message->delete();
        return redirect()->route('admin.messages.index')->with('success', 'Message deleted successfully');
    }
}
