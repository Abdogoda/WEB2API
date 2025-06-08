@extends('layouts.admin.app')

@section('title', 'Messages')
@section('style')
@endsection

@section('content')
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-3xl font-bold">Messages Management</h2>
            @if ($unreadMessagesCount > 0)
                {{-- @hasPermissionTo(\App\Enums\PermissionsEnum::MARK_ALL_MESSAGES_AS_READ->value) --}}
                    <button onclick="openModal('markAllReadModal')" class="bg-blue-500 text-white px-4 py-2 rounded">Mark All Read ({{ $unreadMessagesCount }})</button>
                {{-- @endhasPermissionTo --}}
            @endif
        </div>
        
         <div class="bg-gray-900 shadow-lg rounded-lg p-4">
        @forelse($messages as $message)
            <div onclick="openMessageActionModal({{ $message->id }}, '{{ $message->name }}', '{{ $message->message }}', '{{ $message->email }}', '{{ $message->created_at->diffForHumans() }}', {{ $message->is_read }})" class="block border-b border-gray-700 p-4 hover:bg-gray-800 transition">
                <div class="flex items-start space-x-4">
                    <div class="flex-1">
                        <p class="font-bold text-gray-200">{{ $message->name }}</p>
                        <p class="text-gray-400 text-sm">{{ Str::limit($message->message, 80) }}</p>
                    </div>
                    <div class="text-right">
                        <span class="text-sm text-gray-500">
                            {{ $message->created_at->diffForHumans() }}
                        </span>
                        @if(!$message->is_read)
                            <span class="ml-2 inline-block bg-blue-500 text-white text-xs px-2 py-1 rounded-full">New</span>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <p class="text-center py-4 text-gray-400">No messages available.</p>
        @endforelse
    </div>

    <div class="mt-6 text-gray-300">
        {{ $messages->links() }}
    </div>

    <div class="mt-6">
        {{ $messages->links() }}
    </div>
    </div>

    <!-- Mark All Read Modal -->
    <div id="markAllReadModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center">
        <div class="bg-gray-800 p-6 rounded shadow-md w-96">
            <h3 class="text-xl font-bold mb-4">Mark all read</h3>
            <p class="mb-4">Are you sure you want to mark all messages as read?</p>
            <form action="{{ route('admin.messages.mark-all-as-read') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeModal('markAllReadModal')" class="bg-gray-500 text-white px-4 py-2 rounded">Cancel</button>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Confirm</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Message Action Modal -->
    <div id="messageActionModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center">
        <div class="bg-gray-900 p-6 rounded-lg shadow-lg w-1/2">
            <h2 class="text-xl font-bold text-white" id="message-name"></h2>
            <a href="" class="text-gray-400" id="message-email"></a>
            <p class="text-gray-500 text-sm" id="message-time"></p>
            <hr class="my-4 border-gray-700">
            <p class="text-gray-300 leading-relaxed" id="message-message"></p>
            
            <div class="mt-6 grid grid-cols-1 sm:grid-cols-[repeat(auto-fit,minmax(150px,1fr))] gap-2">
                <form id="markAsReadMessageForm" method="POST">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="w-full bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Mark as Read</button>
                </form>

                <form id="deleteMessageForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700" onclick="return confirm('Are you sure?')">Delete</button>
                </form>
                <button onclick="closeModal('messageActionModal')" class="w-full bg-gray-700 text-white px-4 py-2 rounded hover:bg-gray-600">Close</button>
            </div>
        </div>

    
@endsection

@section('script')
        <script>
          function openMessageActionModal(id, name, message, email, createdAt, isRead) {
            // Set Message Forms
            let markAsReadForm = document.getElementById('markAsReadMessageForm');
            let deleteForm = document.getElementById('deleteMessageForm');

            deleteForm.action = `/admin/messages/${id}`;

            if (isRead) {
                markAsReadForm.style.display = 'none';
            } else {
                markAsReadForm.action = `/admin/messages/${id}/mark-as-read`;
                markAsReadForm.style.display = 'block';
            }

            // Set message details
            document.getElementById('message-name').innerText = name;
            document.getElementById('message-email').innerText = email;
            document.getElementById('message-email').href = 'mailto:' + email;
            document.getElementById('message-time').innerText = createdAt;
            document.getElementById('message-message').innerText = message;

            // Open Modal
            openModal('messageActionModal');
          }
        </script>
@endsection