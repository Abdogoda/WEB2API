<?php

namespace App\Services;

use App\Models\Message;

class MessageService
{
  public function getPaginatedMessages($perPage = 10)
  {
    return Message::latest()->paginate($perPage);
  }

  public function getUnreadCount()
  {
    return Message::where('is_read', 0)->count();
  }

  public function getMessage(Message $message)
  {
    return $message;
  }

  public function markAsRead(Message $message)
  {
    $message->update(['is_read' => 1]);
    return $message;
  }

  public function markAllAsRead()
  {
    return Message::where('is_read', 0)->update(['is_read' => 1]);
  }

  public function deleteMessage(Message $message)
  {
    $message->delete();
  }

  public function createMessage(array $data)
  {
    return Message::create($data);
  }
}
