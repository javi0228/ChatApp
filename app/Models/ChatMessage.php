<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
        'user_id',
        'chat_room_id',
    ];

    protected $casts=[
        'created_at'=>'datetime:H:i',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
