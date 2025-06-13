<?php

namespace App\Http\Controllers\API\V1\User;

use App\Http\Controllers\API\BaseApiController;
use App\Http\Resources\MessageResource;
use App\Models\Message;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MessageController extends BaseApiController
{
    public function index(Request $request): JsonResponse
    {
        $messages = Message::where('user_id', $request->user()->id)->latest()->get();
        return $this->sendResponse(MessageResource::collection($messages), 'Messages retrieved successfully.');
    }

    public function show(Message $message): JsonResponse
    {
        return $this->sendResponse(new MessageResource($message), 'Message retrieved successfully.');
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string|max:1000',
        ]);

        $message = Message::create($request->all());

        return $this->sendResponse($message, 'Message sent successfully', 201);
    }
}
