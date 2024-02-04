<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChatRequest;
use App\Models\ChatMessage;
use App\Models\ChatRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ChatRoomController extends Controller
{
    public function index(Request $request)
    {
        $chat_rooms = ChatRoom::all();
        return inertia('Chats/Index', compact('chat_rooms'));
    }

    public function store(ChatRequest $request)
    {

        // Create the chat room
        auth()->user()->administrated_rooms()->create($request->validated());

        // Send flash session
        $request->session()->flash('flash.banner', 'Chat created!');
        $request->session()->flash('flash.bannerStyle', 'success');

        return redirect()->route('chat.index');

    }

    public function update(ChatRequest $request, ChatRoom $chat)
    {

        $validatedData = $request->validated();
        $chat->update($validatedData);

        return redirect()->route('chat.index');
    }

    public function delete_image(Request $request, ChatRoom $chat)
    {
        $chat_image = $chat->image;
        if (!$chat_image)
            return;

        if (Storage::disk('images')->delete('chat_rooms/' . $chat_image->filename . '.' . $chat_image->extension))
            $chat_image->delete();

        return redirect()->route('chat.index');

    }

    public function store_image(Request $request, ChatRoom $chat)
    {
        $file = $request->file('image');
        $filename = Str::uuid();
        $extension = explode('/', $file->getMimeType())[1];

        // Delete chat image if exists
        if ($chat_image = $chat->image) {
            $chat_image->delete();
            Storage::disk('images')->delete('chat_rooms/' . $chat_image->filename . '.' . $chat_image->extension);
        }

        if (Storage::disk('images')->putFileAs('chat_rooms', $file, $filename . '.' . $extension))
            $chat->image()->create([
                'filename' => $filename,
                'extension' => $extension,
            ]);

        return redirect()->route('chat.index');
    }

    /**
     * Store a message in db with given content and chat_room_id
     */
    public function store_message(Request $request)
    {

        ChatMessage::create([
            'content' => $request->get('message'),
            'chat_room_id' => $request->get('chat_room_id'),
            'user_id' => auth()->id(),
        ]);

    }

    public function messages(ChatRoom $chat)
    {
        return response()->json($chat->messages);
    }
}
