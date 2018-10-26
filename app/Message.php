<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'conversation_id',
        'sender_id',
        'message'
    ];

    public function conversation(){
        return $this->belongsTo('App\Conversation', 'conversation_id');
    }

    public function user(){
        return $this->belongsTo('App\User', 'sender_id');
    }
}
