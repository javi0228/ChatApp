<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChatRequest;
use App\Models\ChatMessage;
use App\Models\ChatRoom;
use Illuminate\Http\Request;

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

    /**
     * Store a message in db with given content and chat_room_id
     */
    public function store_message(Request $request) {
        
        ChatMessage::create([
            'content'=>$request->get('message'),
            'chat_room_id'=>$request->get('chat_room_id'),
            'user_id'=>auth()->id(),
        ]);

    }

    public function messages(ChatRoom $chat_room) {
        return response()->json($chat_room->messages);
    }
}
